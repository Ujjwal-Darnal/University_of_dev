<?php
// Load config, database connection, and helper functions
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

// Start session if it is not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set page title and CSS file
$pageTitle = 'Staff Login | ' . SITE_NAME;
$pageCSS = 'staff-portal.css';

// Store login error message
$error = '';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get and clean username and password input
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Continue only if both fields are filled
    if ($username !== '' && $password !== '') {
        $sql = "SELECT 
                    su.StaffUserID,
                    su.StaffID,
                    su.Username,
                    su.Password,
                    s.Name
                FROM StaffUsers su
                INNER JOIN Staff s ON su.StaffID = s.StaffID
                WHERE su.Username = :username
                LIMIT 1";

        // Find matching staff user
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':username' => $username]);
        $staffUser = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check login details and store session data
        if ($staffUser && $password === $staffUser['Password']) {
            $_SESSION['staff_logged_in'] = true;
            $_SESSION['staff_id'] = (int)$staffUser['StaffID'];
            $_SESSION['staff_name'] = $staffUser['Name'];
            $_SESSION['staff_username'] = $staffUser['Username'];

            // Redirect to staff dashboard after successful login
            header('Location: ' . BASE_URL . '/staff/dashboard.php');
            exit;
        }
    }

    // Show error if login fails
    $error = 'Invalid username or password.';
}
?>

<?php include __DIR__ . '/includes/header.php'; ?>
<?php include __DIR__ . '/includes/navbar.php'; ?>

<main id="main-content">
    <!-- Staff login section -->
    <section class="portal-login-section">
        <div class="container">
            <div class="portal-login-wrap">
                <div class="portal-login-card portal-brand-card">
                    <div class="portal-brand-logo">
                        <img src="<?php echo BASE_URL; ?>/assets/images/logo.png" alt="University of Dev logo">
                    </div>
                    <p class="portal-eyebrow">Academic Access</p>
                    <h1>Staff Portal Login</h1>
                    <p class="portal-text">
                        Secure access for academic staff to view modules, related programmes,
                        and teaching information.
                    </p>
                </div>

                <div class="portal-login-card portal-form-card">
                    <h2>Sign In</h2>

                    <?php if ($error): ?>
                        <!-- Show error message if login details are wrong -->
                        <div class="message error-message"><?php echo e($error); ?></div>
                    <?php endif; ?>

                    <!-- Staff login form -->
                    <form method="POST" action="staff-login.php" class="portal-form">
                        <div class="form-row">
                            <label for="username">Username</label>
                            <input type="text" id="username" name="username" required>
                        </div>

                        <div class="form-row">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" required>
                        </div>

                        <button type="submit" class="btn portal-submit-btn">Enter Staff Portal</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>