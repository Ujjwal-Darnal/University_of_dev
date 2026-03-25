<?php
// Load config file and database connection
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

// Start session if it has not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Restrict access to logged-in admin users only
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ' . BASE_URL . '/login.php');
    exit;
}

// Only allow this action through a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/admin/programmes.php');
    exit;
}

// Get programme ID and new publish status from form
$programmeId = (int)($_POST['programme_id'] ?? 0);
$newStatus = (int)($_POST['new_status'] ?? 0);

// Validate input before updating
if ($programmeId <= 0 || !in_array($newStatus, [0, 1], true)) {
    header('Location: ' . BASE_URL . '/admin/programmes.php?message=error');
    exit;
}

// Update programme publish status
$stmt = $pdo->prepare("UPDATE Programmes SET IsPublished = :status WHERE ProgrammeID = :programme_id");
$stmt->execute([
    ':status' => $newStatus,
    ':programme_id' => $programmeId
]);

header('Location: ' . BASE_URL . '/admin/programmes.php?message=updated');
exit;