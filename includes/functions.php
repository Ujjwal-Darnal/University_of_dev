<?php

// Safely escape output for HTML to help prevent XSS.
function e($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

// Return a shortened version of text for previews/snippets.
function shortText($text, $length = 120)
{
    $text = trim((string)$text);

    // If the text is already within the limit, return it unchanged.
    if (mb_strlen($text) <= $length) {
        return $text;
    }

    // Cut the text and append ellipsis.
    return mb_substr($text, 0, $length) . '...';
}

// Get published programmes, with optional limit, level filter, and search term.
function getProgrammes(PDO $pdo, $limit = null, $level = null, $search = null)
{
    $sql = "SELECT 
                p.ProgrammeID,
                p.ProgrammeName,
                p.Description,
                p.Image,
                p.IsPublished,
                l.LevelName,
                s.Name AS ProgrammeLeader
            FROM Programmes p
            LEFT JOIN Levels l ON p.LevelID = l.LevelID
            LEFT JOIN Staff s ON p.ProgrammeLeaderID = s.StaffID
            WHERE p.IsPublished = 1";

    $params = [];

    // Filter by level name if provided.
    if (!empty($level)) {
        $sql .= " AND l.LevelName = :level";
        $params[':level'] = $level;
    }

    // Filter by programme name or description if a search term is provided.
    if (!empty($search)) {
        $sql .= " AND (p.ProgrammeName LIKE :search OR p.Description LIKE :search)";
        $params[':search'] = '%' . $search . '%';
    }

    // Sort programmes alphabetically.
    $sql .= " ORDER BY p.ProgrammeName ASC";

    // Apply result limit if specified.
    if ($limit !== null) {
        $sql .= " LIMIT " . (int)$limit;
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get a single published programme by its ID.
function getProgrammeById(PDO $pdo, $programmeId)
{
    $sql = "SELECT 
                p.ProgrammeID,
                p.ProgrammeName,
                p.Description,
                p.Image,
                p.IsPublished,
                l.LevelName,
                s.Name AS ProgrammeLeader
            FROM Programmes p
            LEFT JOIN Levels l ON p.LevelID = l.LevelID
            LEFT JOIN Staff s ON p.ProgrammeLeaderID = s.StaffID
            WHERE p.ProgrammeID = :programme_id
              AND p.IsPublished = 1
            LIMIT 1";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':programme_id' => $programmeId]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Get all modules for a programme and group them by academic year.
function getProgrammeModulesByYear(PDO $pdo, $programmeId)
{
    $sql = "SELECT 
                pm.Year,
                m.ModuleID,
                m.ModuleName,
                m.Description,
                m.Image,
                s.Name AS ModuleLeader
            FROM ProgrammeModules pm
            INNER JOIN Modules m ON pm.ModuleID = m.ModuleID
            LEFT JOIN Staff s ON m.ModuleLeaderID = s.StaffID
            WHERE pm.ProgrammeID = :programme_id
            ORDER BY pm.Year ASC, m.ModuleName ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':programme_id' => $programmeId]);

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Group modules into an array using Year as the key.
    $grouped = [];
    foreach ($rows as $row) {
        $year = $row['Year'];
        $grouped[$year][] = $row;
    }

    return $grouped;
}

// Get other programmes that share the same module, excluding the current programme.
function getSharedProgrammesForModule(PDO $pdo, $moduleId, $currentProgrammeId)
{
    $sql = "SELECT 
                p.ProgrammeID,
                p.ProgrammeName
            FROM ProgrammeModules pm
            INNER JOIN Programmes p ON pm.ProgrammeID = p.ProgrammeID
            WHERE pm.ModuleID = :module_id
            AND pm.ProgrammeID != :current_programme_id
            ORDER BY p.ProgrammeName ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':module_id' => $moduleId,
        ':current_programme_id' => $currentProgrammeId
    ]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get all staff members for dropdowns or admin selection lists.
function getAllStaff(PDO $pdo)
{
    $sql = "SELECT StaffID, Name
            FROM Staff
            ORDER BY Name ASC";

    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get all published programmes as simple ID/name options.
function getProgrammeOptions(PDO $pdo)
{
    $sql = "SELECT ProgrammeID, ProgrammeName
            FROM Programmes
            WHERE IsPublished = 1
            ORDER BY ProgrammeName ASC";

    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get the name of a published programme by its ID.
function getProgrammeNameById(PDO $pdo, $programmeId)
{
    $sql = "SELECT ProgrammeName
            FROM Programmes
            WHERE ProgrammeID = :programme_id
              AND IsPublished = 1
            LIMIT 1";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':programme_id' => $programmeId]);

    return $stmt->fetchColumn();
}

// Get all levels ordered by LevelID.
function getLevels(PDO $pdo)
{
    $stmt = $pdo->query("SELECT LevelID, LevelName FROM Levels ORDER BY LevelID ASC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get all staff as simple ID/name options.
function getStaffOptions(PDO $pdo)
{
    $stmt = $pdo->query("SELECT StaffID, Name FROM Staff ORDER BY Name ASC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get all programmes for admin view, regardless of publish status.
function getAllProgrammesAdmin(PDO $pdo)
{
    $sql = "SELECT
                p.ProgrammeID,
                p.ProgrammeName,
                p.Description,
                p.Image,
                l.LevelName,
                s.Name AS ProgrammeLeader
            FROM Programmes p
            LEFT JOIN Levels l ON p.LevelID = l.LevelID
            LEFT JOIN Staff s ON p.ProgrammeLeaderID = s.StaffID
            ORDER BY p.ProgrammeID DESC";

    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get all programmes including publish status and related IDs for admin editing.
function getAllProgrammesWithPublishStatus(PDO $pdo)
{
    $sql = "SELECT
                p.ProgrammeID,
                p.ProgrammeName,
                p.Description,
                p.Image,
                p.IsPublished,
                p.LevelID,
                p.ProgrammeLeaderID,
                l.LevelName,
                s.Name AS ProgrammeLeader
            FROM Programmes p
            LEFT JOIN Levels l ON p.LevelID = l.LevelID
            LEFT JOIN Staff s ON p.ProgrammeLeaderID = s.StaffID
            ORDER BY p.ProgrammeID DESC";

    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get all modules with their assigned module leader for admin view.
function getAllModulesAdmin(PDO $pdo)
{
    $sql = "SELECT
                m.ModuleID,
                m.ModuleName,
                m.Description,
                m.Image,
                m.ModuleLeaderID,
                s.Name AS ModuleLeader
            FROM Modules m
            LEFT JOIN Staff s ON m.ModuleLeaderID = s.StaffID
            ORDER BY m.ModuleID DESC";

    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get all programme-module assignments, including year, for admin management.
function getProgrammeModuleAssignments(PDO $pdo)
{
    $sql = "SELECT
                pm.ProgrammeModuleID,
                pm.Year,
                p.ProgrammeName,
                m.ModuleName
            FROM ProgrammeModules pm
            INNER JOIN Programmes p ON pm.ProgrammeID = p.ProgrammeID
            INNER JOIN Modules m ON pm.ModuleID = m.ModuleID
            ORDER BY p.ProgrammeName ASC, pm.Year ASC, m.ModuleName ASC";

    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get all interested students, optionally filtered by programme.
function getInterestedStudentsAdmin(PDO $pdo, $programmeId = null)
{
    $sql = "SELECT
                i.InterestID,
                i.StudentName,
                i.Email,
                i.RegisteredAt,
                p.ProgrammeID,
                p.ProgrammeName
            FROM InterestedStudents i
            INNER JOIN Programmes p ON i.ProgrammeID = p.ProgrammeID
            WHERE 1=1";

    $params = [];

    // Filter by programme if a programme ID is provided.
    if (!empty($programmeId)) {
        $sql .= " AND p.ProgrammeID = :programme_id";
        $params[':programme_id'] = $programmeId;
    }

    // Show newest registrations first.
    $sql .= " ORDER BY i.RegisteredAt DESC, p.ProgrammeName ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Find duplicate student interest records by programme and email.
function getDuplicateInterestGroups(PDO $pdo)
{
    $sql = "SELECT
                ProgrammeID,
                Email,
                COUNT(*) AS duplicate_count
            FROM InterestedStudents
            GROUP BY ProgrammeID, Email
            HAVING COUNT(*) > 1
            ORDER BY duplicate_count DESC, Email ASC";

    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get a single staff member by ID.
function getStaffById(PDO $pdo, $staffId)
{
    $stmt = $pdo->prepare("SELECT StaffID, Name FROM Staff WHERE StaffID = :staff_id LIMIT 1");
    $stmt->execute([':staff_id' => $staffId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Get all modules led by a specific staff member.
function getModulesByStaff(PDO $pdo, $staffId)
{
    $sql = "SELECT
                m.ModuleID,
                m.ModuleName,
                m.Description
            FROM Modules m
            WHERE m.ModuleLeaderID = :staff_id
            ORDER BY m.ModuleName ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':staff_id' => $staffId]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get distinct programmes connected to modules led by a specific staff member.
function getProgrammesByStaffModules(PDO $pdo, $staffId)
{
    $sql = "SELECT DISTINCT
                p.ProgrammeID,
                p.ProgrammeName,
                l.LevelName
            FROM Modules m
            INNER JOIN ProgrammeModules pm ON m.ModuleID = pm.ModuleID
            INNER JOIN Programmes p ON pm.ProgrammeID = p.ProgrammeID
            LEFT JOIN Levels l ON p.LevelID = l.LevelID
            WHERE m.ModuleLeaderID = :staff_id
            ORDER BY p.ProgrammeName ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':staff_id' => $staffId]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>