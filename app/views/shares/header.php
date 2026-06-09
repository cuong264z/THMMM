<?php
if (session_status() == PHP_SESSION_NONE)
{
    ini_set('session.save_handler', 'files');
    ini_set('session.save_path', 'C:/Windows/Temp');
    session_start();
}

require_once 'app/helpers/SessionHelper.php';
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý sản phẩm</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="http://localhost:8080/assets/css/style.css">
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark custom-navbar shadow-sm">
    <div class="container">

        <a class="navbar-brand font-weight-bold" href="/Product">
            <i class="fas fa-box-open mr-2"></i>
            Quản lý sản phẩm
        </a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">

                <li class="nav-item">
                    <a class="nav-link" href="/Product">
                        <i class="fas fa-list"></i> Danh sách sản phẩm
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/Category">
                        <i class="fas fa-folder"></i> Danh mục
                    </a>
                </li>

                <?php if(SessionHelper::isAdmin()): ?>
                <li class="nav-item ml-2">
                    <a class="btn btn-success" href="/Product/add">
                        <i class="fas fa-plus-circle"></i> Thêm sản phẩm
                    </a>
                </li>
                <li class="nav-item ml-2">
                    <a class="btn btn-primary" href="/Category/add">
                        <i class="fas fa-folder-plus"></i> Thêm danh mục
                    </a>
                </li>
                <?php endif; ?>

                <li class="nav-item ml-2">
                    <a class="btn btn-warning" href="/Product/cart">
                        <i class="fas fa-shopping-cart"></i> Giỏ hàng
                    </a>
                </li>

                <!-- Hiển thị theo JWT token -->
                <li class="nav-item ml-3" id="nav-user-info" style="display:none;">
                    <span class="nav-link text-light">
                        <i class="fas fa-user-circle"></i>
                        <span id="nav-username"></span>
                    </span>
                </li>

                <li class="nav-item ml-2" id="nav-logout" style="display:none;">
                    <a class="btn btn-danger" href="#" onclick="logout()">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </li>

                <li class="nav-item ml-2" id="nav-login">
                    <a class="btn btn-outline-light" href="/Account/login">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </a>
                </li>

                <li class="nav-item ml-2" id="nav-register">
                    <a class="btn btn-warning" href="/Account/register">
                        <i class="fas fa-user-plus"></i> Register
                    </a>
                </li>

            </ul>
        </div>
    </div>
</nav>

<script>
function logout() {
    localStorage.removeItem('jwtToken');
    location.href = '/Account/login';
}

document.addEventListener("DOMContentLoaded", function() {
    const token = localStorage.getItem('jwtToken');

    if (token) {
        document.getElementById('nav-login').style.display    = 'none';
        document.getElementById('nav-register').style.display = 'none';
        document.getElementById('nav-logout').style.display   = 'block';
        document.getElementById('nav-user-info').style.display = 'block';

        // Giải mã token để lấy username
        try {
            const payload = JSON.parse(atob(token.split('.')[1]));
            document.getElementById('nav-username').textContent =
                payload.data.username + ' (' + payload.data.role + ')';
        } catch(e) {}
    } else {
        document.getElementById('nav-login').style.display    = 'block';
        document.getElementById('nav-register').style.display = 'block';
        document.getElementById('nav-logout').style.display   = 'none';
        document.getElementById('nav-user-info').style.display = 'none';
    }
});
</script>

<div class="container py-5">