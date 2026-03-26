<?php
require_once __DIR__ . '/../includes/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

unset($_SESSION['staff_logged_in'], $_SESSION['staff_id'], $_SESSION['staff_name']);

header('Location: ' . BASE_URL . '/staff-login.php');
exit;