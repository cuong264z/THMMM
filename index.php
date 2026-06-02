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

// =========================
// GOOGLE OAUTH CONFIG
// =========================
define('GOOGLE_CLIENT_ID', '506949535869-52rdtu6sbeiu65ehl9afjahmthd65lgn.apps.googleusercontent.com');
define('GOOGLE_CLIENT_SECRET', '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');
define('GOOGLE_REDIRECT_URI', 'http://localhost:8080/Account/googleCallback');

// =========================
// GITHUB OAUTH CONFIG (Đã sửa chữ O thành số 0 ở đầu Client ID)
// =========================
define('GITHUB_CLIENT_ID', '0v231iZrLus8WDZoLZA2');
define('GITHUB_CLIENT_SECRET', '72c042eb9db8d3f914e93f4f6062a457f0d8ea0f');
define('GITHUB_REDIRECT_URI', 'http://localhost:8080/Account/githubCallback');

// =========================
// DEBUG REDIS
// =========================
echo "Session save handler: "
    . ini_get('session.save_handler');

echo "<br>";

echo "Session save path: "
    . ini_get('session.save_path');

echo "<hr>";

// =========================
// REQUIRE FILES
// =========================
require_once 'app/models/ProductModel.php';
require_once 'app/helpers/SessionHelper.php';

// TEST SESSION
$_SESSION['test'] = 'hello redis';

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
// CHECK CONTROLLER
// =========================
if (
    !file_exists(
        'app/controllers/' .
        $controllerName .
        '.php'
    )
)
{
    die('Controller not found');
}

// =========================
// REQUIRE CONTROLLER
// =========================
require_once
    'app/controllers/' .
    $controllerName .
    '.php';

// =========================
// CREATE OBJECT
// =========================
$controller =
    new $controllerName();

// =========================
// CHECK ACTION
// =========================
if (
    !method_exists(
        $controller,
        $action
    )
)
{
    die('Action not found');
}

// =========================
// RUN ACTION
// =========================
call_user_func_array(
    [$controller, $action],
    array_slice($url, 2)
);

?>
