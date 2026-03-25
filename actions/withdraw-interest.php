<?php
// Load config file and database connection
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

// Only allow access through a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/manage-interest.php');
    exit;
}

// Get submitted interest ID and email
$interestId = isset($_POST['interest_id']) ? (int)$_POST['interest_id'] : 0;
$email = trim($_POST['email'] ?? '');

// Validate input before continuing
if ($interestId <= 0 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: ' . BASE_URL . '/manage-interest.php?message=invalid');
    exit;
}

// Check that the interest record exists and matches the email
$checkStmt = $pdo->prepare("SELECT InterestID FROM InterestedStudents WHERE InterestID = :interest_id AND Email = :email LIMIT 1");
$checkStmt->execute([
    ':interest_id' => $interestId,
    ':email' => $email
]);

if (!$checkStmt->fetch()) {
    header('Location: ' . BASE_URL . '/manage-interest.php?message=not_found');
    exit;
}

// Delete the selected interest record
$deleteStmt = $pdo->prepare("DELETE FROM InterestedStudents WHERE InterestID = :interest_id AND Email = :email");
$deleteStmt->execute([
    ':interest_id' => $interestId,
    ':email' => $email
]);

// Redirect back with success message
header('Location: ' . BASE_URL . '/manage-interest.php?email=' . urlencode($email) . '&message=withdrawn');
exit;