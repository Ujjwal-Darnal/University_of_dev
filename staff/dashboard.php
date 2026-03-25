<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/staff-auth.php';

requireStaff();

$pageTitle = 'Staff Dashboard | ' . SITE_NAME;
$pageCSS = 'staff-portal.css';

$staffId = (int)($_SESSION['staff_id'] ?? 0);
$staffName = $_SESSION['staff_name'] ?? 'Staff Member';

$modules = getModulesByStaff($pdo, $staffId);
$programmes = getProgrammesByStaffModules($pdo, $staffId);
?>

<?php include __DIR__ . '/../includes/header.php'; ?>
<?php include __DIR__ . '/../includes/navbar.php'; ?>

<main id="main-content">
    <section class="staff-dashboard-top">
        <div class="container">
            <div class="staff-dashboard-top-box">
                <div>
                    <p class="eyebrow">Staff Portal</p>
                    <h1>Welcome, <?php echo e($staffName); ?></h1>
                    <p>View your modules and related programmes.</p>
                </div>

                <div>
                    <a href="<?php echo BASE_URL; ?>/staff/logout.php" class="btn-outline">Logout</a>
                </div>
            </div>
        </div>
    </section>

    <section class="staff-dashboard-section">
        <div class="container">
            <div class="staff-stats-grid">
                <div class="staff-stat-card">
                    <h3><?php echo count($modules); ?></h3>
                    <p>Modules Led</p>
                </div>

                <div class="staff-stat-card">
                    <h3><?php echo count($programmes); ?></h3>
                    <p>Related Programmes</p>
                </div>
            </div>

            <div class="staff-card">
                <h2>My Modules</h2>

                <?php if ($modules): ?>
                    <div class="staff-grid">
                        <?php foreach ($modules as $module): ?>
                            <article class="staff-info-card">
                                <h3><?php echo e($module['ModuleName']); ?></h3>
                                <p><?php echo e(shortText($module['Description'], 160)); ?></p>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p>No modules assigned yet.</p>
                <?php endif; ?>
            </div>

            <div class="staff-card">
                <h2>Programmes Using My Modules</h2>

                <?php if ($programmes): ?>
                    <div class="table-wrap">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Programme</th>
                                    <th>Level</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($programmes as $programme): ?>
                                    <tr>
                                        <td><?php echo e($programme['ProgrammeName']); ?></td>
                                        <td><?php echo e($programme['LevelName']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p>No related programmes found.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>