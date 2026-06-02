<?php

require_once('app/config/database.php');
require_once('app/models/AccountModel.php');

// Thêm các thư viện bên thứ ba (Google SDK & GitHub OAuth)
use Google\Client;
use Google\Service\Oauth2;
use League\OAuth2\Client\Provider\Github;

class AccountController
{
    private $accountModel;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->accountModel = new AccountModel($this->db);

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    // =========================================================================
    // 1. GIAO DIỆN FORM ĐĂNG KÝ / ĐĂNG NHẬP TRUYỀN THỐNG
    // =========================================================================

    // FORM REGISTER
    public function register()
    {
        include_once 'app/views/account/register.php';
    }

    // FORM LOGIN
    public function login()
    {
        include_once 'app/views/account/login.php';
    }

    // =========================================================================
    // 2. XỬ LÝ ĐĂNG KÝ VÀ ĐĂNG NHẬP THỦ CÔNG (DATABASE)
    // =========================================================================

    // SAVE ACCOUNT (ĐĂNG KÝ)
    public function save()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $username = $_POST['username'] ?? '';
            $fullName = $_POST['fullname'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirmpassword'] ?? '';
            $role = $_POST['role'] ?? 'user';

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

    // LOGIN TRUYỀN THỐNG (CHÉK ĐĂNG NHẬP FORM)
    public function checkLogin()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            $account = $this->accountModel->getAccountByUsername($username);

            if ($account && password_verify($password, $account->password))
            {
                $_SESSION['username'] = $account->username;
                $_SESSION['role'] = $account->role;
                $_SESSION['fullname'] = $account->fullname;

                header('Location: /Product');
                exit();
            }
            else
            {
                $error = $account ? "Mật khẩu không đúng!" : "Không tìm thấy tài khoản!";
                include_once 'app/views/account/login.php';
            }
        }
    }

    // =========================================================================
    // 3. ĐĂNG NHẬP BẰNG GOOGLE (Sử dụng google/apiclient)
    // =========================================================================

    // Cấu hình Google Client
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

    // Chuyển hướng sang Google Auth
    public function googleLogin()
    {
        $client = $this->getGoogleClient();
        header('Location: ' . $client->createAuthUrl());
        exit();
    }

    // Xử lý dữ liệu Google trả về
    public function googleCallback()
    {
        if (!isset($_GET['code'])) {
            header('Location: /Account/login?error=auth_failed');
            exit();
        }

        $client = $this->getGoogleClient();
        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
        
        if (isset($token['error'])) {
            header('Location: /Account/login?error=invalid_token');
            exit();
        }
        
        $client->setAccessToken($token);

        $googleService = new Oauth2($client);
        $googleUser = $googleService->userinfo->get();

        $email = $googleUser->email;
        $fullName = $googleUser->name;
        $googleId = $googleUser->id;
        $provider = 'google';

        // Kiểm tra xem tài khoản này đã từng đăng nhập bằng Google chưa
        $user = $this->accountModel->getAccountByProvider($provider, $googleId);

        if (!$user) {
            // Tự động tạo mới tài khoản nếu chưa tồn tại
            $this->accountModel->saveSocialAccount($email, $fullName, $provider, $googleId, 'user');
            $user = $this->accountModel->getAccountByProvider($provider, $googleId);
        }

        // Đăng nhập session vào hệ thống Redis
        $_SESSION['username'] = $user->username;
        $_SESSION['role'] = $user->role;
        $_SESSION['fullname'] = $user->fullname;

        header('Location: /Product');
        exit();
    }

    // =========================================================================
    // 4. ĐĂNG NHẬP BẰNG GITHUB (Sử dụng league/oauth2-github)
    // =========================================================================

    // Cấu hình GitHub Provider
    private function getGithubProvider()
    {
        return new Github([
            'clientId'          => GITHUB_CLIENT_ID,
            'clientSecret'      => GITHUB_CLIENT_SECRET,
            'redirectUri'       => GITHUB_REDIRECT_URI,
        ]);
    }

    // Chuyển hướng sang GitHub Auth
    public function githubLogin()
    {
        $provider = $this->getGithubProvider();
        $authUrl = $provider->getAuthorizationUrl([
            'scope' => ['user:email']
        ]);
        
        $_SESSION['oauth2state'] = $provider->getState();
        
        header('Location: ' . $authUrl);
        exit();
    }

    // Xử lý dữ liệu GitHub trả về
    public function githubCallback()
    {
        if (empty($_GET['code']) || (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state']))) {
            unset($_SESSION['oauth2state']);
            header('Location: /Account/login?error=invalid_state');
            exit();
        }

        $provider = $this->getGithubProvider();

        try {
            // Đổi mã code lấy Access Token
            $token = $provider->getAccessToken('authorization_code', [
                'code' => $_GET['code']
            ]);

            // Lấy thông tin đối tượng User từ GitHub
            $githubUser = $provider->getResourceOwner($token);

            $githubId = $githubUser->getId();
            $fullName = $githubUser->getName() ?? $githubUser->getNickname();
            $email = $githubUser->getEmail() ?? ($githubUser->getNickname() . '@github.com'); 
            $providerName = 'github';

            // Kiểm tra xem tài khoản này đã từng liên kết bằng GitHub chưa
            $user = $this->accountModel->getAccountByProvider($providerName, $githubId);

            if (!$user) {
                // Tự động lưu tài khoản mạng xã hội mới
                $this->accountModel->saveSocialAccount($email, $fullName, $providerName, $githubId, 'user');
                $user = $this->accountModel->getAccountByProvider($providerName, $githubId);
            }

            // Thiết lập Session đồng bộ
            $_SESSION['username'] = $user->username;
            $_SESSION['role'] = $user->role;
            $_SESSION['fullname'] = $user->fullname;

            header('Location: /Product');
            exit();

        } catch (\Exception $e) {
            header('Location: /Account/login?error=auth_failed');
            exit();
        }
    }

    // =========================================================================
    // 5. ĐĂNG XUẤT HỆ THỐNG
    // =========================================================================
    
    // LOGOUT
    public function logout()
    {
        session_destroy();

        header('Location: /Product');
        exit();
    }
}
?>