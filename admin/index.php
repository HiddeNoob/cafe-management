<?php
    $redirect_url = '/admin/dashboard';
    require_once __DIR__ . '/../app/Functions/redirect_if_logged_in.php';
    header("Location: ./login/");

?>