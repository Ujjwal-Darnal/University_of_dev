<?php
// Load config, database, helper functions, and admin auth
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

// Restrict access to admin users only
requireAdmin();

// Set page title and CSS file
$pageTitle = 'Admin Dashboard | ' . SITE_NAME;
$pageCSS = 'admin.css';

// Get total counts for dashboard summary cards
$totalProgrammes = $pdo->query("SELECT COUNT(*) FROM Programmes")->fetchColumn();
$totalModules = $pdo->query("SELECT COUNT(*) FROM Modules")->fetchColumn();
$totalStaff = $pdo->query("SELECT COUNT(*) FROM Staff")->fetchColumn();
$totalInterested = $pdo->query("SELECT COUNT(*) FROM InterestedStudents")->fetchColumn();

// Get the 5 most recent interest registrations
$recentInterestStmt = $pdo->query("
    SELECT 
        i.StudentName,
        i.Email,
        i.RegisteredAt,
        p.ProgrammeName
    FROM InterestedStudents i
    INNER JOIN Programmes p ON i.ProgrammeID = p.ProgrammeID
    ORDER BY i.RegisteredAt DESC
    LIMIT 5
");
$recentInterests = $recentInterestStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include __DIR__ . '/../includes/header.php'; ?>
<?php include __DIR__ . '/../includes/navbar.php'; ?>

<main id="main-content">
    <!-- Admin dashboard top section -->
    <section class="admin-top">
        <div class="container">
            <div class="admin-top-box">
                <div>
                    <p class="eyebrow">Administration</p>
                    <h1>Dashboard</h1>
                    <p>Welcome, <?php echo e($_SESSION['admin_username'] ?? 'Admin'); ?>.</p>
                </div>

                <div class="admin-top-actions">
                    <a href="<?php echo BASE_URL; ?>/admin/logout.php" class="btn-outline">Logout</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Dashboard content -->
    <section class="admin-section">
        <div class="container">
            <div class="stats-grid">
                <!-- Summary cards -->
                <div class="stat-card">
                    <h3><?php echo (int)$totalProgrammes; ?></h3>
                    <p>Total Programmes</p>
                </div>

                <div class="stat-card">
                    <h3><?php echo (int)$totalModules; ?></h3>
                    <p>Total Modules</p>
                </div>

                <div class="stat-card">
                    <h3><?php echo (int)$totalStaff; ?></h3>
                    <p>Total Staff</p>
                </div>

                <div class="stat-card">
                    <h3><?php echo (int)$totalInterested; ?></h3>
                    <p>Interested Students</p>
                </div>
            </div>

            <!-- Quick links for admin tasks -->
            <div class="admin-card">
                <h2>Quick Actions</h2>
               <div class="admin-card">
    <h2>Quick Actions</h2>
    <div class="admin-links-row">
        <a href="<?php echo BASE_URL; ?>/admin/programmes.php" class="btn">Manage Programmes</a>
        <a href="<?php echo BASE_URL; ?>/admin/modules.php" class="btn">Manage Modules</a>
        <a href="<?php echo BASE_URL; ?>/admin/assign-modules.php" class="btn">Assign Modules</a>
        <a href="<?php echo BASE_URL; ?>/admin/interested-students.php" class="btn">Interested Students</a>
    </div>
</div>
            </div>

            <!-- Recent student interest registrations -->
            <div class="admin-card">
                <h2>Recent Interest Registrations</h2>

                <?php if ($recentInterests): ?>
                    <div class="table-wrap">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Programme</th>
                                    <th>Registered At</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Show recent registrations -->
                                <?php foreach ($recentInterests as $item): ?>
                                    <tr>
                                        <td><?php echo e($item['StudentName']); ?></td>
                                        <td><?php echo e($item['Email']); ?></td>
                                        <td><?php echo e($item['ProgrammeName']); ?></td>
                                        <td><?php echo e($item['RegisteredAt']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <!-- Show this if no registrations exist -->
                    <p>No interest registrations found yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>