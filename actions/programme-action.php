<?php
// Include core configuration, database connection, helper functions, and authentication
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

// Ensure only admin users can access this file
requireAdmin();

// Only allow POST requests (form submissions)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/admin/programmes.php');
    exit;
}

// Determine which action is being performed (add, edit, delete)
$actionType = $_POST['action_type'] ?? '';

try {

   
       // ADD NEW PROGRAMME
     
    if ($actionType === 'add') {

        // Get and sanitize form input values
        $programmeName = trim($_POST['programme_name'] ?? '');
        $levelId = (int)($_POST['level_id'] ?? 0);
        $programmeLeaderId = (int)($_POST['programme_leader_id'] ?? 0);
        $description = trim($_POST['description'] ?? '');
        $image = trim($_POST['image'] ?? '');

        // Validate required fields
        if ($programmeName === '' || $levelId <= 0 || $programmeLeaderId <= 0 || $description === '') {
            throw new Exception('Missing required fields.');
        }

        // Prepare SQL query to insert new programme
        $stmt = $pdo->prepare("
            INSERT INTO Programmes (
                ProgrammeName,
                LevelID,
                ProgrammeLeaderID,
                Description,
                Image
            ) VALUES (
                :programme_name,
                :level_id,
                :programme_leader_id,
                :description,
                :image
            )
        ");

        // Execute query with bound parameters
        $stmt->execute([
            ':programme_name' => $programmeName,
            ':level_id' => $levelId,
            ':programme_leader_id' => $programmeLeaderId,
            ':description' => $description,
            ':image' => $image !== '' ? $image : null // Allow null if no image provided
        ]);

        // Redirect with success message
        header('Location: ' . BASE_URL . '/admin/programmes.php?message=added');
        exit;
    }

    /* 
       EDIT EXISTING PROGRAMME */
    if ($actionType === 'edit') {

        // Get and sanitize input values
        $programmeId = (int)($_POST['programme_id'] ?? 0);
        $programmeName = trim($_POST['programme_name'] ?? '');
        $levelId = (int)($_POST['level_id'] ?? 0);
        $programmeLeaderId = (int)($_POST['programme_leader_id'] ?? 0);
        $description = trim($_POST['description'] ?? '');
        $image = trim($_POST['image'] ?? '');

        // Validate required fields
        if ($programmeId <= 0 || $programmeName === '' || $levelId <= 0 || $programmeLeaderId <= 0 || $description === '') {
            throw new Exception('Missing required fields.');
        }

        // Prepare SQL query to update programme
        $stmt = $pdo->prepare("
            UPDATE Programmes
            SET
                ProgrammeName = :programme_name,
                LevelID = :level_id,
                ProgrammeLeaderID = :programme_leader_id,
                Description = :description,
                Image = :image
            WHERE ProgrammeID = :programme_id
        ");

        // Execute update query
        $stmt->execute([
            ':programme_name' => $programmeName,
            ':level_id' => $levelId,
            ':programme_leader_id' => $programmeLeaderId,
            ':description' => $description,
            ':image' => $image !== '' ? $image : null,
            ':programme_id' => $programmeId
        ]);

        // Redirect with success message
        header('Location: ' . BASE_URL . '/admin/programmes.php?message=updated');
        exit;
    }

    // DELETE PROGRAMME 
    if ($actionType === 'delete') {

        // Get programme ID
        $programmeId = (int)($_POST['programme_id'] ?? 0);

        // Validate ID
        if ($programmeId <= 0) {
            throw new Exception('Invalid programme ID.');
        }

        // Prepare delete query
        $stmt = $pdo->prepare("DELETE FROM Programmes WHERE ProgrammeID = :programme_id");

        // Execute delete
        $stmt->execute([
            ':programme_id' => $programmeId
        ]);

        // Redirect with success message
        header('Location: ' . BASE_URL . '/admin/programmes.php?message=deleted');
        exit;
    }

    // If action type is not recognised
    throw new Exception('Invalid action type.');

} catch (Throwable $e) {

    // Display detailed error (useful for debugging)
    die('Programme action error: ' . $e->getMessage());
}