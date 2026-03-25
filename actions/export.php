<?php
// Load config and database connection
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Only allow logged-in admin users to access this file
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ' . BASE_URL . '/login.php');
    exit;
}

// Get programme filter from URL if provided
$programmeId = isset($_GET['programme_id']) ? (int)$_GET['programme_id'] : 0;

// Base query to get mailing list data
$sql = "SELECT
            i.InterestID,
            i.StudentName,
            i.Email,
            p.ProgrammeName,
            i.RegisteredAt
        FROM InterestedStudents i
        INNER JOIN Programmes p ON i.ProgrammeID = p.ProgrammeID
        WHERE 1=1";

$params = [];

// Apply programme filter if a valid programme ID is selected
if ($programmeId > 0) {
    $sql .= " AND i.ProgrammeID = :programme_id";
    $params[':programme_id'] = $programmeId;
}

// Show newest registrations first
$sql .= " ORDER BY i.RegisteredAt DESC";

// Run query and fetch results
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Set headers so browser downloads the file as CSV
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=mailing_list.csv');

// Open output stream for CSV download
$output = fopen('php://output', 'w');

// Add CSV column headings
fputcsv($output, ['Interest ID', 'Student Name', 'Email', 'Programme Name', 'Registered At']);

// Write each row into the CSV file
foreach ($rows as $row) {
    fputcsv($output, [
        $row['InterestID'],
        $row['StudentName'],
        $row['Email'],
        $row['ProgrammeName'],
        $row['RegisteredAt']
    ]);
}

// Close output stream and end script
fclose($output);
exit;