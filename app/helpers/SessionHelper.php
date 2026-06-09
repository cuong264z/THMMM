<?php

class SessionHelper
{
    // Khởi động session nếu chưa bắt đầu
    public static function start()
    {
            if (session_status() == PHP_SESSION_NONE)
        {
            ini_set('session.save_handler', 'files');
            ini_set('session.save_path', 'C:/Windows/Temp');
            session_start();
        }
    }

    // Kiểm tra đăng nhập
    public static function isLoggedIn()
    {
        self::start();

        return isset($_SESSION['username']);
    }

    // Kiểm tra admin
    public static function isAdmin()
    {
        self::start();

        return isset($_SESSION['username'])
            && isset($_SESSION['role'])
            && $_SESSION['role'] === 'admin';
    }

    // Lấy role
    public static function getRole()
    {
        self::start();

        return $_SESSION['role'] ?? 'guest';
    }
}
?>