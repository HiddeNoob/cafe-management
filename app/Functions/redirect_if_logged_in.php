<?php
    session_start();
    if($_SESSION['user'] ?? null) {
        $redirect_url = $redirect_url ?? '/';
        header("Location: $redirect_url");
        exit();
    }