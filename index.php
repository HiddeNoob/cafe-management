<?php 
    require_once __DIR__ . '/app/Functions/log_errors.php';
    if(session_status() === PHP_SESSION_NONE) {
        header('Location: /login/');
    }else{
        header('Location: dashboard/index.php');
    }
?>