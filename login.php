<?php
// Load config and helper functions
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

// Start session if it has not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set page title and CSS file
$pageTitle = 'Admin Login | ' . SITE_NAME;
$pageCSS = 'login.css';

// Store login error message
$error = '';

// Check if login form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get and clean form input
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Simple admin login check
    if ($username === 'admin' && $password === 'admin123') {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = 'admin';

        // Redirect to admin dashboard after successful login
        header('Location: ' . BASE_URL . '/admin/dashboard.php');
        exit;
    } else {
        // Show error if login details are wrong
        $error = 'Invalid username or password.';
    }
}
?>

<?php include __DIR__ . '/includes/header.php'; ?>
<?php include __DIR__ . '/includes/navbar.php'; ?>

<main id="main-content">
    <!-- Admin login section -->
    <section class="portal-login-section">
        <div class="container">
            <div class="portal-login-wrap">
                <div class="portal-login-card portal-brand-card">
                    <div class="portal-brand-logo">
                        <img src="<?php echo BASE_URL; ?>/assets/images/logo.png" alt="University of Dev logo">
                    </div>
                    <p class="portal-eyebrow">Administrative Access</p>
                    <h1>Admin Portal Login</h1>
                    <p class="portal-text">
                        Secure access for administrators to manage programmes, modules,
                        mailing lists, and student registrations.
                    </p>
                </div>

                <div class="portal-login-card portal-form-card">
                    <h2>Sign In</h2>

                    <?php if ($error): ?>
                        <!-- Show error message if login fails -->
                        <div class="message error-message"><?php echo e($error); ?></div>
                    <?php endif; ?>

                    <!-- Admin login form -->
                    <form method="POST" action="login.php" class="portal-form">
                        <div class="form-row">
                            <label for="username">Username</label>
                            <input type="text" id="username" name="username" required>
                        </div>

                        <div class="form-row">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" required>
                        </div>

                        <button type="submit" class="btn portal-submit-btn">Enter Admin Portal</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>