<?php
// Load config file and database connection
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

// Start session if it is not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Restrict access to logged-in admin users only
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ' . BASE_URL . '/login.php');
    exit;
}

// Only allow POST requests for form actions
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/admin/modules.php');
    exit;
}

// Get the requested action type
$actionType = $_POST['action_type'] ?? '';

try {
    // Add a new module
    if ($actionType === 'add') {
        $moduleName = trim($_POST['module_name'] ?? '');
        $moduleLeaderId = (int)($_POST['module_leader_id'] ?? 0);
        $description = trim($_POST['description'] ?? '');
        $image = trim($_POST['image'] ?? '');

        // Check required fields
        if ($moduleName === '' || $moduleLeaderId <= 0 || $description === '') {
            throw new Exception('Invalid input');
        }

        $stmt = $pdo->prepare("
            INSERT INTO Modules (ModuleName, ModuleLeaderID, Description, Image)
            VALUES (:module_name, :module_leader_id, :description, :image)
        ");
        $stmt->execute([
            ':module_name' => $moduleName,
            ':module_leader_id' => $moduleLeaderId,
            ':description' => $description,
            ':image' => $image !== '' ? $image : null
        ]);

        header('Location: ' . BASE_URL . '/admin/modules.php?message=added');
        exit;
    }

    // Edit an existing module
    if ($actionType === 'edit') {
        $moduleId = (int)($_POST['module_id'] ?? 0);
        $moduleName = trim($_POST['module_name'] ?? '');
        $moduleLeaderId = (int)($_POST['module_leader_id'] ?? 0);
        $description = trim($_POST['description'] ?? '');
        $image = trim($_POST['image'] ?? '');

        // Check required fields
        if ($moduleId <= 0 || $moduleName === '' || $moduleLeaderId <= 0 || $description === '') {
            throw new Exception('Invalid input');
        }

        $stmt = $pdo->prepare("
            UPDATE Modules
            SET ModuleName = :module_name,
                ModuleLeaderID = :module_leader_id,
                Description = :description,
                Image = :image
            WHERE ModuleID = :module_id
        ");
        $stmt->execute([
            ':module_name' => $moduleName,
            ':module_leader_id' => $moduleLeaderId,
            ':description' => $description,
            ':image' => $image !== '' ? $image : null,
            ':module_id' => $moduleId
        ]);

        header('Location: ' . BASE_URL . '/admin/modules.php?message=updated');
        exit;
    }

    // Delete a module
    if ($actionType === 'delete') {
        $moduleId = (int)($_POST['module_id'] ?? 0);

        // Make sure module ID is valid
        if ($moduleId <= 0) {
            throw new Exception('Invalid input');
        }

        $stmt = $pdo->prepare("DELETE FROM Modules WHERE ModuleID = :module_id");
        $stmt->execute([':module_id' => $moduleId]);

        header('Location: ' . BASE_URL . '/admin/modules.php?message=deleted');
        exit;
    }

    // Handle unknown action types
    throw new Exception('Unknown action');
} catch (Throwable $e) {
    // Redirect with error message if something goes wrong
    header('Location: ' . BASE_URL . '/admin/modules.php?message=error');
    exit;
}