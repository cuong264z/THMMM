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

// START SESSION
session_start();

// DEBUG
echo "Session save handler: "
    . ini_get('session.save_handler');

echo "<br>";

echo "Session save path: "
    . ini_get('session.save_path');

echo "<hr>";

require_once 'app/models/ProductModel.php';
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