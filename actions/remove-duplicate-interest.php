<?php
// Load config file and database connection
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

// Start session if it has not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Only allow logged-in admin users to access this action
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ' . BASE_URL . '/login.php');
    exit;
}

// Only allow this action to run through a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/admin/interested-students.php');
    exit;
}

try {
    // Remove duplicate interest records, keeping the oldest one
    $sql = "
        DELETE i1
        FROM InterestedStudents i1
        INNER JOIN InterestedStudents i2
            ON i1.ProgrammeID = i2.ProgrammeID
           AND i1.Email = i2.Email
           AND i1.InterestID > i2.InterestID
    ";

    $pdo->exec($sql);

    header('Location: ' . BASE_URL . '/admin/interested-students.php?message=duplicates_removed');
    exit;
} catch (Throwable $e) {
    // Redirect with error message if something goes wrong
    header('Location: ' . BASE_URL . '/admin/interested-students.php?message=error');
    exit;
}