<?php
// Load config, database, helper functions, and admin auth
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

// Restrict access to admin users only
requireAdmin();

// Set page title and CSS file
$pageTitle = 'Manage Programmes | ' . SITE_NAME;
$pageCSS = 'admin-management.css';

// Get levels, staff options, and all programmes including publish status
$levels = getLevels($pdo);
$staffOptions = getStaffOptions($pdo);
$programmes = getAllProgrammesWithPublishStatus($pdo);

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
                <h1>Manage Programmes</h1>
                <p>Add, edit, delete, publish, and unpublish programmes from the system.</p>
            </div>
        </div>
    </section>

    <!-- Main programme management section -->
    <section class="admin-page-section">
        <div class="container">
            <?php if ($message === 'added'): ?>
                <div class="message success-message">Programme added successfully.</div>
            <?php elseif ($message === 'updated'): ?>
                <div class="message success-message">Programme updated successfully.</div>
            <?php elseif ($message === 'deleted'): ?>
                <div class="message success-message">Programme deleted successfully.</div>
            <?php elseif ($message === 'error'): ?>
                <div class="message error-message">Something went wrong. Please try again.</div>
            <?php endif; ?>

            <div class="admin-form-card">
                <h2>Add New Programme</h2>

                <!-- Form to add a new programme -->
                <form action="<?php echo BASE_URL; ?>/actions/programme-action.php" method="POST" class="admin-form">
                    <input type="hidden" name="action_type" value="add">

                    <div class="form-grid">
                        <div class="form-row">
                            <label for="programme_name">Programme Name</label>
                            <input type="text" id="programme_name" name="programme_name" required maxlength="255">
                        </div>

                        <div class="form-row">
                            <label for="level_id">Level</label>
                            <select id="level_id" name="level_id" required>
                                <option value="">Select level</option>
                                <?php foreach ($levels as $level): ?>
                                    <option value="<?php echo (int)$level['LevelID']; ?>">
                                        <?php echo e($level['LevelName']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-row">
                            <label for="programme_leader_id">Programme Leader</label>
                            <select id="programme_leader_id" name="programme_leader_id" required>
                                <option value="">Select staff member</option>
                                <?php foreach ($staffOptions as $staff): ?>
                                    <option value="<?php echo (int)$staff['StaffID']; ?>">
                                        <?php echo e($staff['Name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-row">
                            <label for="image">Image Path</label>
                            <input type="text" id="image" name="image" maxlength="255" placeholder="optional image path">
                        </div>
                    </div>

                    <div class="form-row">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" rows="5" required maxlength="1000"></textarea>
                    </div>

                    <button type="submit" class="btn">Add Programme</button>
                </form>
            </div>

            <div class="admin-table-card">
                <h2>Existing Programmes</h2>

                <div class="table-wrap">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Programme</th>
                                <th>Level</th>
                                <th>Leader</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Loop through all programmes -->
                            <?php foreach ($programmes as $programme): ?>
                                <tr>
                                    <td><?php echo (int)$programme['ProgrammeID']; ?></td>
                                    <td><?php echo e($programme['ProgrammeName']); ?></td>
                                    <td><?php echo e($programme['LevelName']); ?></td>
                                    <td><?php echo e($programme['ProgrammeLeader']); ?></td>
                                    <td><?php echo e(shortText($programme['Description'], 80)); ?></td>

                                    <td>
                                        <!-- Show current publish status -->
                                        <?php if ((int)$programme['IsPublished'] === 1): ?>
                                            <span class="status-badge published">Published</span>
                                        <?php else: ?>
                                            <span class="status-badge unpublished">Unpublished</span>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <details class="edit-box">
                                            <summary>Edit</summary>

                                            <!-- Form to edit an existing programme -->
                                            <form action="<?php echo BASE_URL; ?>/actions/programme-action.php" method="POST" class="inline-edit-form">
                                                <input type="hidden" name="action_type" value="edit">
                                                <input type="hidden" name="programme_id" value="<?php echo (int)$programme['ProgrammeID']; ?>">

                                                <input type="text" name="programme_name" value="<?php echo e($programme['ProgrammeName']); ?>" required>

                                                <select name="level_id" required>
                                                    <?php foreach ($levels as $level): ?>
                                                        <option value="<?php echo (int)$level['LevelID']; ?>" <?php echo (int)$programme['LevelID'] === (int)$level['LevelID'] ? 'selected' : ''; ?>>
                                                            <?php echo e($level['LevelName']); ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>

                                                <select name="programme_leader_id" required>
                                                    <?php foreach ($staffOptions as $staff): ?>
                                                        <option value="<?php echo (int)$staff['StaffID']; ?>" <?php echo (int)$programme['ProgrammeLeaderID'] === (int)$staff['StaffID'] ? 'selected' : ''; ?>>
                                                            <?php echo e($staff['Name']); ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>

                                                <input type="text" name="image" value="<?php echo e($programme['Image']); ?>" placeholder="image path">
                                                <textarea name="description" rows="4" required><?php echo e($programme['Description']); ?></textarea>

                                                <button type="submit" class="btn">Save</button>
                                            </form>
                                        </details>

                                        <!-- Form to publish or unpublish a programme -->
                                        <form action="<?php echo BASE_URL; ?>/actions/toggle-programme-publish.php" method="POST">
                                            <input type="hidden" name="programme_id" value="<?php echo (int)$programme['ProgrammeID']; ?>">
                                            <input type="hidden" name="new_status" value="<?php echo (int)$programme['IsPublished'] === 1 ? 0 : 1; ?>">
                                            <button type="submit" class="btn-outline">
                                                <?php echo (int)$programme['IsPublished'] === 1 ? 'Unpublish' : 'Publish'; ?>
                                            </button>
                                        </form>

                                        <!-- Form to delete a programme -->
                                        <form action="<?php echo BASE_URL; ?>/actions/programme-action.php" method="POST" onsubmit="return confirm('Delete this programme?');">
                                            <input type="hidden" name="action_type" value="delete">
                                            <input type="hidden" name="programme_id" value="<?php echo (int)$programme['ProgrammeID']; ?>">
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
                    <a href="<?php echo BASE_URL; ?>/admin/assign-modules.php" class="btn">Assign Modules to Programmes</a>
                    <a href="<?php echo BASE_URL; ?>/admin/dashboard.php" class="btn-outline">Back to Dashboard</a>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>