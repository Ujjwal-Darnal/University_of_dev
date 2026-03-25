<?php
// Load config, database connection, and helper functions
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

// Set page title and CSS file
$pageTitle = 'Register Interest | ' . SITE_NAME;
$pageCSS = 'register-interest.css';

// Get programme list for the dropdown
$programmes = getProgrammeOptions($pdo);

// Get selected programme from URL if provided
$selectedProgrammeId = isset($_GET['programme_id']) ? (int)$_GET['programme_id'] : 0;
$selectedProgrammeName = '';

// Check if selected programme exists
if ($selectedProgrammeId > 0) {
    $selectedProgrammeName = getProgrammeNameById($pdo, $selectedProgrammeId);
    if (!$selectedProgrammeName) {
        $selectedProgrammeId = 0;
    }
}

// Get success or error message from URL
$success = isset($_GET['success']) ? $_GET['success'] : '';
$error = isset($_GET['error']) ? $_GET['error'] : '';
?>

<?php include __DIR__ . '/includes/header.php'; ?>
<?php include __DIR__ . '/includes/navbar.php'; ?>

<main id="main-content">
    <!-- Page intro section -->
    <section class="register-top">
        <div class="container">
            <div class="register-top-box">
                <div>
                    <p class="eyebrow">Stay connected</p>
                    <h1>Register Your Interest</h1>
                    <p>
                        Tell us which programme interests you and we will keep you updated
                        with useful information about admissions, deadlines, and opportunities.
                    </p>
                </div>
                <img src="<?php echo BASE_URL; ?>/assets/images/logo.png" alt="University of Dev logo">
            </div>
        </div>
    </section>

    <!-- Interest form section -->
    <section class="form-section">
        <div class="container">
            <div class="form-layout">
                <div class="form-info-card">
                    <h2>Why register?</h2>
                    <ul class="benefit-list">
                        <li>Receive programme updates</li>
                        <li>Stay informed about admissions deadlines</li>
                        <li>Get notified about university events</li>
                        <li>Join the university mailing list</li>
                    </ul>

                    <?php if ($selectedProgrammeId > 0): ?>
                        <!-- Show selected programme if one was chosen earlier -->
                        <div class="selected-programme-box">
                            <h3>Selected Programme</h3>
                            <p><?php echo e($selectedProgrammeName); ?></p>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="form-card">
                    <h2>Interest Form</h2>

                    <?php if ($success === '1'): ?>
                        <!-- Show success message after form submission -->
                        <div class="message success-message">
                            Your interest has been registered successfully.
                        </div>
                    <?php endif; ?>

                    <?php if ($error === 'empty'): ?>
                        <div class="message error-message">
                            Please fill in all required fields.
                        </div>
                    <?php elseif ($error === 'invalid_email'): ?>
                        <div class="message error-message">
                            Please enter a valid email address.
                        </div>
                    <?php elseif ($error === 'invalid_programme'): ?>
                        <div class="message error-message">
                            Please select a valid programme.
                        </div>
                    <?php elseif ($error === 'duplicate'): ?>
                        <div class="message error-message">
                            You have already registered interest for this programme with this email.
                        </div>
                    <?php elseif ($error === 'db'): ?>
                        <div class="message error-message">
                            Something went wrong while saving your information.
                        </div>
                    <?php endif; ?>

                    <!-- Form to submit student interest -->
                    <form action="<?php echo BASE_URL; ?>/actions/register-interest-action.php" method="POST" class="interest-form">
                        <div class="form-row">
                            <label for="student_name">Full Name</label>
                            <input type="text" id="student_name" name="student_name" maxlength="100" required>
                        </div>

                        <div class="form-row">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" maxlength="150" required>
                        </div>

                        <div class="form-row">
                            <label for="programme_id">Programme of Interest</label>
                            <select id="programme_id" name="programme_id" required>
                                <option value="">Select a programme</option>
                                <?php foreach ($programmes as $programme): ?>
                                    <option value="<?php echo (int)$programme['ProgrammeID']; ?>"
                                        <?php echo $selectedProgrammeId === (int)$programme['ProgrammeID'] ? 'selected' : ''; ?>>
                                        <?php echo e($programme['ProgrammeName']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <button type="submit" class="btn submit-btn">Submit Interest</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>