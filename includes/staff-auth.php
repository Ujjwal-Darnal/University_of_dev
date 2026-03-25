<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isStaffLoggedIn()
{
    return isset($_SESSION['staff_logged_in']) && $_SESSION['staff_logged_in'] === true;
}

function requireStaff()
{
    if (!isStaffLoggedIn()) {
        header('Location: ' . BASE_URL . '/staff-login.php');
        exit;
    }
}