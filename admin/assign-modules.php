<?php
// Load config, database, helper functions, and admin auth
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

// Make sure only admins can access this page
requireAdmin();

// Set page title and CSS file
$pageTitle = 'Assign Modules | ' . SITE_NAME;
$pageCSS = 'admin-management.css';

// Get data for dropdowns and existing assignments
$programmes = getProgrammeOptions($pdo);
$modules = getAllModulesAdmin($pdo);
$assignments = getProgrammeModuleAssignments($pdo);

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
                <h1>Assign Modules to Programmes</h1>
                <p>Link modules to programmes and choose the year of study.</p>
            </div>
        </div>
    </section>

    <!-- Main assignment section -->
    <section class="admin-page-section">
        <div class="container">
            <?php if ($message === 'added'): ?>
                <div class="message success-message">Module assignment added successfully.</div>
            <?php elseif ($message === 'deleted'): ?>
                <div class="message success-message">Module assignment deleted successfully.</div>
            <?php elseif ($message === 'duplicate'): ?>
                <div class="message error-message">That assignment already exists.</div>
            <?php elseif ($message === 'error'): ?>
                <div class="message error-message">Something went wrong. Please try again.</div>
            <?php endif; ?>

            <div class="admin-form-card">
                <h2>Add Assignment</h2>

                <!-- Form to assign a module to a programme -->
                <form action="<?php echo BASE_URL; ?>/actions/assign-module-action.php" method="POST" class="admin-form">
                    <input type="hidden" name="action_type" value="add">

                    <div class="form-grid">
                        <div class="form-row">
                            <label for="programme_id">Programme</label>
                            <select id="programme_id" name="programme_id" required>
                                <option value="">Select programme</option>
                                <?php foreach ($programmes as $programme): ?>
                                    <option value="<?php echo (int)$programme['ProgrammeID']; ?>">
                                        <?php echo e($programme['ProgrammeName']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-row">
                            <label for="module_id">Module</label>
                            <select id="module_id" name="module_id" required>
                                <option value="">Select module</option>
                                <?php foreach ($modules as $module): ?>
                                    <option value="<?php echo (int)$module['ModuleID']; ?>">
                                        <?php echo e($module['ModuleName']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-row">
                            <label for="year">Year</label>
                            <select id="year" name="year" required>
                                <option value="">Select year</option>
                                <option value="1">Year 1</option>
                                <option value="2">Year 2</option>
                                <option value="3">Year 3</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="btn">Assign Module</button>
                </form>
            </div>

            <div class="admin-table-card">
                <h2>Existing Assignments</h2>

                <div class="table-wrap">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Programme</th>
                                <th>Module</th>
                                <th>Year</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Show all current assignments -->
                            <?php foreach ($assignments as $assignment): ?>
                                <tr>
                                    <td><?php echo e($assignment['ProgrammeName']); ?></td>
                                    <td><?php echo e($assignment['ModuleName']); ?></td>
                                    <td>Year <?php echo (int)$assignment['Year']; ?></td>
                                    <td>
                                        <!-- Form to delete an assignment -->
                                        <form action="<?php echo BASE_URL; ?>/actions/assign-module-action.php" method="POST" onsubmit="return confirm('Delete this assignment?');">
                                            <input type="hidden" name="action_type" value="delete">
                                            <input type="hidden" name="programme_module_id" value="<?php echo (int)$assignment['ProgrammeModuleID']; ?>">
                                            <button type="submit" class="btn-outline danger-btn">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Quick links to related admin pages -->
                <div class="admin-links-row">
                    <a href="<?php echo BASE_URL; ?>/admin/programmes.php" class="btn-outline">Manage Programmes</a>
                    <a href="<?php echo BASE_URL; ?>/admin/modules.php" class="btn-outline">Manage Modules</a>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>