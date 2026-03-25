<?php
// Load config file and database connection
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

// Only allow form submission by POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/register-interest.php');
    exit;
}

// Get and clean submitted form data
$studentName = trim($_POST['student_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$programmeId = isset($_POST['programme_id']) ? (int)$_POST['programme_id'] : 0;

// Check for empty required fields
if ($studentName === '' || $email === '' || $programmeId <= 0) {
    header('Location: ' . BASE_URL . '/register-interest.php?error=empty');
    exit;
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: ' . BASE_URL . '/register-interest.php?error=invalid_email');
    exit;
}

// Make sure the selected programme exists
$programmeCheck = $pdo->prepare("SELECT ProgrammeID FROM Programmes WHERE ProgrammeID = :programme_id LIMIT 1");
$programmeCheck->execute([':programme_id' => $programmeId]);

if (!$programmeCheck->fetch()) {
    header('Location: ' . BASE_URL . '/register-interest.php?error=invalid_programme');
    exit;
}

try {
    // Insert student interest into the database
    $sql = "INSERT INTO InterestedStudents (ProgrammeID, StudentName, Email)
            VALUES (:programme_id, :student_name, :email)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':programme_id' => $programmeId,
        ':student_name' => $studentName,
        ':email' => $email
    ]);

    header('Location: ' . BASE_URL . '/register-interest.php?success=1');
    exit;
} catch (PDOException $e) {
    // Handle duplicate registration error
    if ($e->getCode() === '23000') {
        header('Location: ' . BASE_URL . '/register-interest.php?error=duplicate');
        exit;
    }

    // Handle other database errors
    header('Location: ' . BASE_URL . '/register-interest.php?error=db');
    exit;
}