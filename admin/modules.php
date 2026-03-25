<?php
// Load config, database, helper functions, and admin auth
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

// Restrict access to admin users only
requireAdmin();

// Set page title and CSS file
$pageTitle = 'Manage Modules | ' . SITE_NAME;
$pageCSS = 'admin-management.css';

// Get staff options and all modules for the page
$staffOptions = getStaffOptions($pdo);
$modules = getAllModulesAdmin($pdo);

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
                <h1>Manage Modules</h1>
                <p>Add, edit, and delete modules in the system.</p>
            </div>
        </div>
    </section>

    <!-- Module management section -->
    <section class="admin-page-section">
        <div class="container">
            <?php if ($message === 'added'): ?>
                <div class="message success-message">Module added successfully.</div>
            <?php elseif ($message === 'updated'): ?>
                <div class="message success-message">Module updated successfully.</div>
            <?php elseif ($message === 'deleted'): ?>
                <div class="message success-message">Module deleted successfully.</div>
            <?php elseif ($message === 'error'): ?>
                <div class="message error-message">Something went wrong. Please try again.</div>
            <?php endif; ?>

            <div class="admin-form-card">
                <h2>Add New Module</h2>

                <!-- Form to add a new module -->
                <form action="<?php echo BASE_URL; ?>/actions/module-action.php" method="POST" class="admin-form">
                    <input type="hidden" name="action_type" value="add">

                    <div class="form-grid">
                        <div class="form-row">
                            <label for="module_name">Module Name</label>
                            <input type="text" id="module_name" name="module_name" required maxlength="255">
                        </div>

                        <div class="form-row">
                            <label for="module_leader_id">Module Leader</label>
                            <select id="module_leader_id" name="module_leader_id" required>
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

                    <button type="submit" class="btn">Add Module</button>
                </form>
            </div>

            <div class="admin-table-card">
                <h2>Existing Modules</h2>

                <div class="table-wrap">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Module</th>
                                <th>Leader</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Loop through all existing modules -->
                            <?php foreach ($modules as $module): ?>
                                <tr>
                                    <td><?php echo (int)$module['ModuleID']; ?></td>
                                    <td><?php echo e($module['ModuleName']); ?></td>
                                    <td><?php echo e($module['ModuleLeader']); ?></td>
                                    <td><?php echo e(shortText($module['Description'], 80)); ?></td>
                                    <td>
                                        <details class="edit-box">
                                            <summary>Edit</summary>

                                            <!-- Form to edit an existing module -->
                                            <form action="<?php echo BASE_URL; ?>/actions/module-action.php" method="POST" class="inline-edit-form">
                                                <input type="hidden" name="action_type" value="edit">
                                                <input type="hidden" name="module_id" value="<?php echo (int)$module['ModuleID']; ?>">

                                                <input type="text" name="module_name" value="<?php echo e($module['ModuleName']); ?>" required>

                                                <select name="module_leader_id" required>
                                                    <?php foreach ($staffOptions as $staff): ?>
                                                        <option value="<?php echo (int)$staff['StaffID']; ?>" <?php echo $module['ModuleLeader'] === $staff['Name'] ? 'selected' : ''; ?>>
                                                            <?php echo e($staff['Name']); ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>

                                                <input type="text" name="image" value="<?php echo e($module['Image']); ?>" placeholder="image path">
                                                <textarea name="description" rows="4" required><?php echo e($module['Description']); ?></textarea>

                                                <button type="submit" class="btn">Save</button>
                                            </form>
                                        </details>

                                        <!-- Form to delete a module -->
                                        <form action="<?php echo BASE_URL; ?>/actions/module-action.php" method="POST" onsubmit="return confirm('Delete this module?');">
                                            <input type="hidden" name="action_type" value="delete">
                                            <input type="hidden" name="module_id" value="<?php echo (int)$module['ModuleID']; ?>">
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