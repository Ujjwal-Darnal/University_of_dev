<?php
// Load config file (for BASE_URL)
require_once __DIR__ . '/../includes/config.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Clear all session data and destroy session
session_unset();
session_destroy();

// Redirect user to login page after logout
header('Location: ' . BASE_URL . '/login.php');
exit;