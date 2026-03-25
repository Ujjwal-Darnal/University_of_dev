<?php
// Load config, database connection, and helper functions
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

// Set page title and CSS file
$pageTitle = 'Staff | ' . SITE_NAME;
$pageCSS = 'staff.css';

// Get all staff members from the database
$staffMembers = getAllStaff($pdo);
?>

<?php include __DIR__ . '/includes/header.php'; ?>
<?php include __DIR__ . '/includes/navbar.php'; ?>

<main id="main-content">
    <!-- Page intro section -->
    <section class="page-top">
        <div class="container">
            <div class="page-top-box">
                <div>
                    <p class="eyebrow">Meet our academic team</p>
                    <h1>Our Staff</h1>
                    <p>
                        Explore the lecturers and academic professionals supporting
                        teaching and learning across University of Dev programmes.
                    </p>
                </div>
                <img src="<?php echo BASE_URL; ?>/assets/images/logo.png" alt="University of Dev logo">
            </div>
        </div>
    </section>

    <!-- Staff list section -->
    <section class="staff-section">
        <div class="container">
            <div class="section-heading left-align">
                <h2>Academic Staff Directory</h2>
                <p>Browse staff profiles and learn more about the people behind our programmes.</p>
            </div>

            <?php if ($staffMembers): ?>
                <div class="staff-grid">
                    <!-- Loop through all staff members -->
                    <?php foreach ($staffMembers as $staff): ?>
                        <article class="staff-card">
                            <div class="staff-image">
                                <img src="<?php echo BASE_URL; ?>/assets/images/logo.png" alt="<?php echo e($staff['Name']); ?>">
                            </div>

                            <div class="staff-content">
                                <h3><?php echo e($staff['Name']); ?></h3>
                               <p>
    Academic staff member at the University of Dev, contributing to teaching,
    module delivery, and student support across computing programmes.
</p>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <!-- Show this if no staff records are found -->
                <div class="empty-state">
                    <h3>No staff records found</h3>
                    <p>Staff information will appear here when available.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>