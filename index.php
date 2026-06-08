<?php

// =========================
// REDIS SESSION
// =========================
ini_set(
    'session.save_handler',
    'redis'
);

ini_set(
    'session.save_path',
    'tcp://127.0.0.1:6379'
);

// =========================
// START SESSION
// =========================
session_start();

require_once __DIR__ . '/vendor/autoload.php';

require_once 'app/models/ProductModel.php';
require_once 'app/helpers/SessionHelper.php';

require_once 'app/controllers/ProductApiController.php';
require_once 'app/controllers/CategoryApiController.php';

// =========================
// GOOGLE OAUTH CONFIG
// =========================
define(
    'GOOGLE_CLIENT_ID',
    '506949535869-52rdtu6sbeiu65ehl9afjahmthd65lgn.apps.googleusercontent.com'
);

define(
    'GOOGLE_CLIENT_SECRET',
    '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'
);

define(
    'GOOGLE_REDIRECT_URI',
    'http://localhost:8080/Account/googleCallback'
);

// =========================
// GITHUB OAUTH CONFIG
// =========================
define(
    'GITHUB_CLIENT_ID',
    '0v231iZrLus8WDZoLZA2'
);

define(
    'GITHUB_CLIENT_SECRET',
    '72c042eb9db8d3f914e93f4f6062a457f0d8ea0f'
);

define(
    'GITHUB_REDIRECT_URI',
    'http://localhost:8080/Account/githubCallback'
);

// =========================
// URL
// =========================
$url = $_GET['url'] ?? '';

$url = rtrim($url, '/');

$url = filter_var(
    $url,
    FILTER_SANITIZE_URL
);

$url = explode('/', $url);

// =========================
// CONTROLLER
// =========================
$controllerName =
    isset($url[0]) && $url[0] != ''
    ? ucfirst($url[0]) . 'Controller'
    : 'DefaultController';

// =========================
// ACTION
// =========================
$action =
    isset($url[1]) && $url[1] != ''
    ? $url[1]
    : 'index';

// =========================
// API ROUTER
// URL:
// /Api/Product
// /Api/Product/1
// /Api/Category
// =========================
if (
    $controllerName === 'ApiController'
    && isset($url[1])
)
{
    $apiControllerName =
        ucfirst($url[1]) .
        'ApiController';

    if (
        file_exists(
            'app/controllers/' .
            $apiControllerName .
            '.php'
        )
    )
    {
        require_once
            'app/controllers/' .
            $apiControllerName .
            '.php';

        $controller =
            new $apiControllerName();

        $method =
            $_SERVER['REQUEST_METHOD'];

        $id =
            $url[2] ?? null;

        switch ($method)
        {
            case 'GET':

                if ($id)
                {
                    $action = 'show';
                }
                else
                {
                    $action = 'index';
                }

                break;

            case 'POST':

                $action = 'store';

                break;

            case 'PUT':

                if ($id)
                {
                    $action = 'update';
                }

                break;

            case 'DELETE':

                if ($id)
                {
                    $action = 'destroy';
                }

                break;

            default:

                http_response_code(405);

                echo json_encode([
                    'message' =>
                    'Method Not Allowed'
                ]);

                exit;
        }

        if (
            method_exists(
                $controller,
                $action
            )
        )
        {
            if ($id)
            {
                call_user_func_array(
                    [$controller, $action],
                    [$id]
                );
            }
            else
            {
                call_user_func_array(
                    [$controller, $action],
                    []
                );
            }
        }
        else
        {
            http_response_code(404);

            echo json_encode([
                'message' =>
                'Action not found'
            ]);
        }

        exit;
    }
    else
    {
        http_response_code(404);

        echo json_encode([
            'message' =>
            'Controller not found'
        ]);

        exit;
    }
}

// =========================
// NORMAL MVC ROUTER
// =========================
if (
    file_exists(
        'app/controllers/' .
        $controllerName .
        '.php'
    )
)
{
    require_once
        'app/controllers/' .
        $controllerName .
        '.php';

    $controller =
        new $controllerName();
}
else
{
    die('Controller not found');
}

// =========================
// CALL ACTION
// =========================
if (
    method_exists(
        $controller,
        $action
    )
)
{
    call_user_func_array(
        [$controller, $action],
        array_slice($url, 2)
    );
}
else
{
    die('Action not found');
}

?>