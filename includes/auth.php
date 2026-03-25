<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isAdminLoggedIn()
{
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

function requireAdmin()
{
    if (!isAdminLoggedIn()) {
        header('Location: ' . BASE_URL . '/login.php');
        exit;
    }
}