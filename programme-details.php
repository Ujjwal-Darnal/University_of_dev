<?php
// Load config, database connection, and helper functions
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

// Get programme ID from URL
$programmeId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Store programme details and grouped modules
$programme = null;
$modulesByYear = [];

// Only search if a valid programme ID is provided
if ($programmeId > 0) {
    $programme = getProgrammeById($pdo, $programmeId);

    // If programme exists, get its modules grouped by year
    if ($programme) {
        $modulesByYear = getProgrammeModulesByYear($pdo, $programmeId);
    }
}

// Set page title dynamically
$pageTitle = ($programme ? $programme['ProgrammeName'] : 'Programme Details') . ' | ' . SITE_NAME;
$pageCSS = 'programme-details.css';
?>

<?php include __DIR__ . '/includes/header.php'; ?>
<?php include __DIR__ . '/includes/navbar.php'; ?>

<main id="main-content">
    <?php if (!$programme): ?>
        <!-- Show message if programme is not found -->
        <section class="not-found-section">
            <div class="container">
                <div class="not-found-box">
                    <h1>Programme not found</h1>
                    <p>The programme you are looking for does not exist or was not found.</p>
                    <a href="programmes.php" class="btn">Back to Programmes</a>
                </div>
            </div>
        </section>
    <?php else: ?>
        <!-- Programme details section -->
        <section class="details-hero">
            <div class="container">
                <div class="details-hero-box">
                    <div class="details-copy">
                        <span class="programme-badge"><?php echo e($programme['LevelName']); ?></span>
                        <h1><?php echo e($programme['ProgrammeName']); ?></h1>
                        <p><?php echo e($programme['Description']); ?></p>

                        <div class="detail-meta">
                            <div class="meta-card">
                                <h3>Programme Leader</h3>
                                <p><?php echo e($programme['ProgrammeLeader']); ?></p>
                            </div>

                            <div class="meta-card">
                                <h3>Study Level</h3>
                                <p><?php echo e($programme['LevelName']); ?></p>
                            </div>
                        </div>

                        <div class="detail-actions">
                            <a href="register-interest.php?programme_id=<?php echo (int)$programme['ProgrammeID']; ?>" class="btn">
                                Register Interest
                            </a>
                            <a href="programmes.php" class="btn-outline">Back to Programmes</a>
                        </div>
                    </div>

                    <div class="details-visual">
                        <div class="details-logo-box">
                            <img src="<?php echo BASE_URL; ?>/assets/images/logo.png" alt="University of Dev logo">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Modules grouped by study year -->
        <section class="modules-section">
            <div class="container">
                <div class="section-heading left-align">
                    <h2>Modules by Year</h2>
                    <p>
                        Explore the structure of the programme and the staff members involved in teaching.
                    </p>
                </div>

                <?php if ($modulesByYear): ?>
                    <!-- Loop through each year -->
                    <?php foreach ($modulesByYear as $year => $modules): ?>
                        <div class="year-block">
                            <h3 class="year-heading">Year <?php echo e($year); ?></h3>

                            <div class="module-grid">
                                <!-- Loop through modules in that year -->
                                <?php foreach ($modules as $module): ?>
                                    <?php $sharedProgrammes = getSharedProgrammesForModule($pdo, $module['ModuleID'], $programmeId); ?>

                                    <article class="module-card">
                                        <div class="module-card-top">
                                            <h4><?php echo e($module['ModuleName']); ?></h4>
                                            <span class="module-year-tag">Year <?php echo e($year); ?></span>
                                        </div>

                                        <p><?php echo e($module['Description']); ?></p>

                                        <p class="module-leader">
                                            <strong>Module Leader:</strong>
                                            <?php echo e($module['ModuleLeader']); ?>
                                        </p>

                                        <?php if (!empty($sharedProgrammes)): ?>
                                            <!-- Show other programmes that use the same module -->
                                            <div class="shared-programmes">
                                                <strong>Also appears in:</strong>
                                                <ul>
                                                    <?php foreach ($sharedProgrammes as $shared): ?>
                                                        <li><?php echo e($shared['ProgrammeName']); ?></li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>
                                        <?php endif; ?>
                                    </article>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- Show this if no modules are assigned -->
                    <div class="empty-state">
                        <h3>No modules available</h3>
                        <p>This programme does not currently have modules assigned.</p>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    <?php endif; ?>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>