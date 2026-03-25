<?php
// Load config, database, helper functions, and admin auth
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

// Restrict access to admin users only
requireAdmin();

// Set page title and CSS file
$pageTitle = 'Interested Students | ' . SITE_NAME;
$pageCSS = 'admin-students.css';

// Get selected programme filter from URL
$selectedProgrammeId = isset($_GET['programme_id']) ? (int)$_GET['programme_id'] : 0;

// Get programme list, student records, and duplicate registrations
$programmes = getProgrammeOptions($pdo);
$students = getInterestedStudentsAdmin($pdo, $selectedProgrammeId > 0 ? $selectedProgrammeId : null);
$duplicates = getDuplicateInterestGroups($pdo);

// Get feedback message from URL
$message = $_GET['message'] ?? '';
?>

<?php include __DIR__ . '/../includes/header.php'; ?>
<?php include __DIR__ . '/../includes/navbar.php'; ?>

<main id="main-content">
    <!-- Page heading -->
    <section class="admin-page-top">
        <div class="container">
            <div class="admin-page-top-box">
                <h1>Interested Students</h1>
                <p>View mailing lists, export data, and remove duplicate registrations.</p>
            </div>
        </div>
    </section>

    <!-- Main admin section -->
    <section class="admin-page-section">
        <div class="container">
            <?php if ($message === 'duplicates_removed'): ?>
                <div class="message success-message">Duplicate registrations were removed successfully.</div>
            <?php elseif ($message === 'error'): ?>
                <div class="message error-message">Something went wrong. Please try again.</div>
            <?php endif; ?>

            <div class="admin-form-card">
                <h2>Filter and Export</h2>

                <!-- Form to filter students by programme -->
                <form method="GET" action="interested-students.php" class="admin-form">
                    <div class="form-grid one-col-mobile">
                        <div class="form-row">
                            <label for="programme_id">Filter by Programme</label>
                            <select name="programme_id" id="programme_id">
                                <option value="">All programmes</option>
                                <?php foreach ($programmes as $programme): ?>
                                    <option value="<?php echo (int)$programme['ProgrammeID']; ?>" <?php echo $selectedProgrammeId === (int)$programme['ProgrammeID'] ? 'selected' : ''; ?>>
                                        <?php echo e($programme['ProgrammeName']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="admin-links-row">
                        <button type="submit" class="btn">Apply Filter</button>
                        <a href="<?php echo BASE_URL; ?>/admin/interested-students.php" class="btn-outline">Reset</a>
                        <a href="<?php echo BASE_URL; ?>/actions/export.php<?php echo $selectedProgrammeId > 0 ? '?programme_id=' . (int)$selectedProgrammeId : ''; ?>" class="btn-outline">
                            Export CSV
                        </a>
                    </div>
                </form>
            </div>

            <div class="admin-table-card">
                <h2>Mailing List</h2>

                <?php if ($students): ?>
                    <div class="table-wrap">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Student Name</th>
                                    <th>Email</th>
                                    <th>Programme</th>
                                    <th>Registered At</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Show all interested student records -->
                                <?php foreach ($students as $student): ?>
                                    <tr>
                                        <td><?php echo (int)$student['InterestID']; ?></td>
                                        <td><?php echo e($student['StudentName']); ?></td>
                                        <td><?php echo e($student['Email']); ?></td>
                                        <td><?php echo e($student['ProgrammeName']); ?></td>
                                        <td><?php echo e($student['RegisteredAt']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <!-- Show this if no student records are found -->
                    <p>No interested students found.</p>
                <?php endif; ?>
            </div>

            <div class="admin-table-card">
                <h2>Duplicate Registrations</h2>

                <?php if ($duplicates): ?>
                    <div class="table-wrap">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Programme ID</th>
                                    <th>Email</th>
                                    <th>Duplicate Count</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Show duplicate registration groups -->
                                <?php foreach ($duplicates as $duplicate): ?>
                                    <tr>
                                        <td><?php echo (int)$duplicate['ProgrammeID']; ?></td>
                                        <td><?php echo e($duplicate['Email']); ?></td>
                                        <td><?php echo (int)$duplicate['duplicate_count']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Button to remove duplicate records -->
                    <form action="<?php echo BASE_URL; ?>/actions/remove-duplicate-interest.php" method="POST" onsubmit="return confirm('Remove duplicate registrations and keep the oldest entries?');">
                        <button type="submit" class="btn-outline danger-btn">Remove Duplicates</button>
                    </form>
                <?php else: ?>
                    <!-- Show this if no duplicates exist -->
                    <p>No duplicates found.</p>
                <?php endif; ?>
            </div>

            <div class="admin-links-row">
                <a href="<?php echo BASE_URL; ?>/admin/dashboard.php" class="btn-outline">Back to Dashboard</a>
            </div>
        </div>
    </section>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>