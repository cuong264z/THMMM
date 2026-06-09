<?php

require_once('app/config/database.php');
require_once('app/models/AccountModel.php');
require_once __DIR__ . '/../utils/JWTHandler.php';

use Google\Client;
use Google\Service\Oauth2;
use League\OAuth2\Client\Provider\Github;

class AccountController
{
    private $accountModel;
    private $db;
    private $jwtHandler;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->accountModel = new AccountModel($this->db);
        $this->jwtHandler = new JWTHandler();

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    // =========================================================================
    // 1. GIAO DIỆN FORM ĐĂNG KÝ / ĐĂNG NHẬP TRUYỀN THỐNG
    // =========================================================================

    public function register()
    {
        include_once 'app/views/account/register.php';
    }

    public function login()
    {
        include_once 'app/views/account/login.php';
    }

    // =========================================================================
    // 2. XỬ LÝ ĐĂNG KÝ VÀ ĐĂNG NHẬP THỦ CÔNG (DATABASE)
    // =========================================================================

    public function save()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $username        = $_POST['username'] ?? '';
            $fullName        = $_POST['fullname'] ?? '';
            $password        = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirmpassword'] ?? '';
            $role            = $_POST['role'] ?? 'user';

            $errors = [];

            if (empty($username))
            {
                $errors['username'] = "Vui lòng nhập username!";
            }

            if (empty($fullName))
            {
                $errors['fullname'] = "Vui lòng nhập họ tên!";
            }

            if (empty($password))
            {
                $errors['password'] = "Vui lòng nhập mật khẩu!";
            }

            if ($password != $confirmPassword)
            {
                $errors['confirmPass'] = "Mật khẩu xác nhận không khớp!";
            }

            if (!in_array($role, ['admin', 'user']))
            {
                $role = 'user';
            }

            if ($this->accountModel->getAccountByUsername($username))
            {
                $errors['account'] = "Tài khoản đã tồn tại!";
            }

            if (count($errors) > 0)
            {
                include_once 'app/views/account/register.php';
            }
            else
            {
                $result = $this->accountModel->save(
                    $username,
                    $fullName,
                    $password,
                    $role
                );

                if ($result)
                {
                    header('Location: /Account/login');
                    exit();
                }
            }
        }
    }

    // CHECK LOGIN — trả về JWT token
    public function checkLogin()
    {
        header('Content-Type: application/json');

        $data     = json_decode(file_get_contents("php://input"), true);
        $username = $data['username'] ?? '';
        $password = $data['password'] ?? '';

        $account = $this->accountModel->getAccountByUsername($username);

        if ($account && password_verify($password, $account->password))
        {
            $token = $this->jwtHandler->encode([
                'id'       => $account->id,
                'username' => $account->username,
                'role'     => $account->role
            ]);

            echo json_encode(['token' => $token]);
        }
        else
        {
            http_response_code(401);
            echo json_encode(['message' => 'Invalid credentials']);
        }
    }

    // =========================================================================
    // 3. ĐĂNG NHẬP BẰNG GOOGLE
    // =========================================================================

    private function getGoogleClient()
    {
        $client = new Client();
        $client->setClientId(GOOGLE_CLIENT_ID);
        $client->setClientSecret(GOOGLE_CLIENT_SECRET);
        $client->setRedirectUri(GOOGLE_REDIRECT_URI);
        $client->addScope('email');
        $client->addScope('profile');
        return $client;
    }

    public function googleLogin()
    {
        $client = $this->getGoogleClient();
        header('Location: ' . $client->createAuthUrl());
        exit();
    }

    public function googleCallback()
    {
        if (!isset($_GET['code'])) {
            header('Location: /Account/login?error=auth_failed');
            exit();
        }

        $client = $this->getGoogleClient();
        $token  = $client->fetchAccessTokenWithAuthCode($_GET['code']);

        if (isset($token['error'])) {
            header('Location: /Account/login?error=invalid_token');
            exit();
        }

        $client->setAccessToken($token);

        $googleService = new Oauth2($client);
        $googleUser    = $googleService->userinfo->get();

        $email      = $googleUser->email;
        $fullName   = $googleUser->name;
        $googleId   = $googleUser->id;
        $provider   = 'google';

        $user = $this->accountModel->getAccountByProvider($provider, $googleId);

        if (!$user) {
            $this->accountModel->saveSocialAccount($email, $fullName, $provider, $googleId, 'user');
            $user = $this->accountModel->getAccountByProvider($provider, $googleId);
        }

        $_SESSION['username'] = $user->username;
        $_SESSION['role']     = $user->role;
        $_SESSION['fullname'] = $user->fullname;

        header('Location: /Product');
        exit();
    }

    // =========================================================================
    // 4. ĐĂNG NHẬP BẰNG GITHUB
    // =========================================================================

    private function getGithubProvider()
    {
        return new Github([
            'clientId'     => GITHUB_CLIENT_ID,
            'clientSecret' => GITHUB_CLIENT_SECRET,
            'redirectUri'  => GITHUB_REDIRECT_URI,
        ]);
    }

    public function githubLogin()
    {
        $provider = $this->getGithubProvider();
        $authUrl  = $provider->getAuthorizationUrl(['scope' => ['user:email']]);

        $_SESSION['oauth2state'] = $provider->getState();

        header('Location: ' . $authUrl);
        exit();
    }

    public function githubCallback()
    {
        if (empty($_GET['code']) || (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state']))) {
            unset($_SESSION['oauth2state']);
            header('Location: /Account/login?error=invalid_state');
            exit();
        }

        $provider = $this->getGithubProvider();

        try {
            $token      = $provider->getAccessToken('authorization_code', ['code' => $_GET['code']]);
            $githubUser = $provider->getResourceOwner($token);

            $githubId     = $githubUser->getId();
            $fullName     = $githubUser->getName() ?? $githubUser->getNickname();
            $email        = $githubUser->getEmail() ?? ($githubUser->getNickname() . '@github.com');
            $providerName = 'github';

            $user = $this->accountModel->getAccountByProvider($providerName, $githubId);

            if (!$user) {
                $this->accountModel->saveSocialAccount($email, $fullName, $providerName, $githubId, 'user');
                $user = $this->accountModel->getAccountByProvider($providerName, $githubId);
            }

            $_SESSION['username'] = $user->username;
            $_SESSION['role']     = $user->role;
            $_SESSION['fullname'] = $user->fullname;

            header('Location: /Product');
            exit();

        } catch (\Exception $e) {
            header('Location: /Account/login?error=auth_failed');
            exit();
        }
    }

    // =========================================================================
    // 5. ĐĂNG XUẤT
    // =========================================================================

    public function logout()
    {
        session_destroy();
        header('Location: /Product');
        exit();
    }
}
?>