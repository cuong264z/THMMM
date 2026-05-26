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

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark custom-navbar shadow-sm">

    <div class="container">

        <!-- LOGO -->
        <a class="navbar-brand font-weight-bold"
           href="/Product">

            <i class="fas fa-box-open mr-2"></i>

            Quản lý sản phẩm

        </a>

        <!-- MOBILE BUTTON -->
        <button class="navbar-toggler"
                type="button"
                data-toggle="collapse"
                data-target="#navbarNav">

            <span class="navbar-toggler-icon"></span>

        </button>

        <!-- MENU -->
        <div class="collapse navbar-collapse"
             id="navbarNav">

            <ul class="navbar-nav ml-auto">

                <!-- PRODUCT -->
                <li class="nav-item">

                    <a class="nav-link"
                       href="/Product">

                        Danh sách sản phẩm

                    </a>

                </li>

                <!-- CATEGORY -->
                <li class="nav-item">

                    <a class="nav-link"
                       href="/Category">

                        Danh mục

                    </a>

                </li>

                <!-- ADD PRODUCT -->
                <li class="nav-item ml-2">

                    <a class="btn btn-success px-3"
                       href="/Product/add">

                        <i class="fas fa-plus-circle"></i>

                        Thêm sản phẩm

                    </a>

                </li>

                <!-- ADD CATEGORY -->
                <li class="nav-item ml-2">

                    <a class="btn btn-primary px-3"
                       href="/Category/add">

                        <i class="fas fa-folder-plus"></i>

                        Thêm danh mục

                    </a>

                </li>

            </ul>

        </div>

    </div>

</nav>

<!-- MAIN -->
<div class="container py-5">