<?php
if (session_status() == PHP_SESSION_NONE)
{
    session_start();
}

require_once 'app/helpers/SessionHelper.php';
?>

<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Quản lý sản phẩm</title>

    <!-- Bootstrap -->
    <link rel="stylesheet"
          href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- CSS -->
    <link rel="stylesheet"
          href="http://localhost:8080/assets/css/style.css">

</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark custom-navbar shadow-sm">

    <div class="container">

        <a class="navbar-brand font-weight-bold"
           href="/Product">

            <i class="fas fa-box-open mr-2"></i>

            Quản lý sản phẩm

        </a>

        <button class="navbar-toggler"
                type="button"
                data-toggle="collapse"
                data-target="#navbarNav">

            <span class="navbar-toggler-icon"></span>

        </button>

        <div class="collapse navbar-collapse"
             id="navbarNav">

            <ul class="navbar-nav ml-auto">

                <li class="nav-item">
                    <a class="nav-link"
                       href="/Product">

                        <i class="fas fa-list"></i>
                        Danh sách sản phẩm

                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link"
                       href="/Category">

                        <i class="fas fa-folder"></i>
                        Danh mục

                    </a>
                </li>

                <?php if(SessionHelper::isAdmin()): ?>

                <li class="nav-item ml-2">
                    <a class="btn btn-success"
                       href="/Product/add">

                        <i class="fas fa-plus-circle"></i>
                        Thêm sản phẩm

                    </a>
                </li>

                <li class="nav-item ml-2">
                    <a class="btn btn-primary"
                       href="/Category/add">

                        <i class="fas fa-folder-plus"></i>
                        Thêm danh mục

                    </a>
                </li>

                <?php endif; ?>

                <li class="nav-item ml-2">
                    <a class="btn btn-warning"
                       href="/Product/cart">

                        <i class="fas fa-shopping-cart"></i>
                        Giỏ hàng

                    </a>
                </li>

                <?php if(SessionHelper::isLoggedIn()): ?>

                    <li class="nav-item ml-3">

                        <span class="nav-link text-light">

                            <i class="fas fa-user-circle"></i>

                            <?= htmlspecialchars($_SESSION['username']) ?>

                            (<?= htmlspecialchars($_SESSION['role']) ?>)

                        </span>

                    </li>

                    <li class="nav-item ml-2">

                        <a class="btn btn-danger"
                           href="/Account/logout">

                            <i class="fas fa-sign-out-alt"></i>

                            Logout

                        </a>

                    </li>

                <?php else: ?>

                    <li class="nav-item ml-2">

                        <a class="btn btn-outline-light"
                           href="/Account/login">

                            <i class="fas fa-sign-in-alt"></i>

                            Login

                        </a>

                    </li>

                    <li class="nav-item ml-2">

                        <a class="btn btn-warning"
                           href="/Account/register">

                            <i class="fas fa-user-plus"></i>

                            Register

                        </a>

                    </li>

                <?php endif; ?>

            </ul>

        </div>

    </div>

</nav>

<div class="container py-5">