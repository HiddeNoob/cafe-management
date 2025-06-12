<?php
    require_once __DIR__ . '/../app/autoload.php';
    require_once __DIR__ . '/../app/Functions/log_errors.php'; 
    session_start();
    isset($_SESSION['user']) && $_SESSION['user']->logout();;
?>