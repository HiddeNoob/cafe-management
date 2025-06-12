<?php
    require_once __DIR__ . "/../autoload.php";
    session_start();
    if(!isset($_SESSION['user']) && !$_SESSION['user'] instanceof Customer) {
        $redirect_url = '/login/';
        require_once __DIR__ . '/access_restricted.php';
        header('HTTP/1.1 403 Forbidden');
        exit();
    }
?>