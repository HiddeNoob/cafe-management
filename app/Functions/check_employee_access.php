<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if(!isset($_SESSION['user']) && !($_SESSION['user'] instanceof Employee)) {
        $redirect_url = '/admin/login/';
        require_once __DIR__ . '/access_restricted.php';
        header('HTTP/1.1 403 Forbidden');
        exit();
    }
?>