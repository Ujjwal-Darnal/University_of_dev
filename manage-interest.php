<?php
// Load config, database connection, and helper functions
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

// Set page title and CSS file
$pageTitle = 'Manage Interest | ' . SITE_NAME;
$pageCSS = 'manage-interest.css';

// Store search results and get email/message from URL
$results = [];
$searchEmail = isset($_GET['email']) ? trim($_GET['email']) : '';
$message = isset($_GET['message']) ? $_GET['message'] : '';

// Search only if a valid email is entered
if ($searchEmail !== '' && filter_var($searchEmail, FILTER_VALIDATE_EMAIL)) {
    $sql = "SELECT 
                i.InterestID,
                i.StudentName,
                i.Email,
                i.RegisteredAt,
                p.ProgrammeName
            FROM InterestedStudents i
            INNER JOIN Programmes p ON i.ProgrammeID = p.ProgrammeID
            WHERE i.Email = :email
            ORDER BY i.RegisteredAt DESC";

    // Prepare and run query safely
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':email' => $searchEmail]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<?php include __DIR__ . '/includes/header.php'; ?>
<?php include __DIR__ . '/includes/navbar.php'; ?>

<main id="main-content">
    <!-- Top section for page intro -->
    <section class="manage-top">
        <div class="container">
            <div class="manage-top-box">
                <div>
                    <p class="eyebrow">Update your preferences</p>
                    <h1>Manage Your Interest</h1>
                    <p>
                        Search using your email address to view and withdraw your
                        registered interest in programmes.
                    </p>
                </div>
                <img src="<?php echo BASE_URL; ?>/assets/images/logo.png" alt="University of Dev logo">
            </div>
        </div>
    </section>

    <!-- Search and results section -->
    <section class="manage-section">
        <div class="container">
            <div class="manage-card">
                <h2>Find your registrations</h2>

                <?php if ($message === 'withdrawn'): ?>
                    <!-- Success message after withdrawal -->
                    <div class="message success-message">Your interest was withdrawn successfully.</div>
                <?php elseif ($message === 'invalid'): ?>
                    <div class="message error-message">Please enter a valid email address.</div>
                <?php elseif ($message === 'not_found'): ?>
                    <div class="message error-message">No interest registration was found.</div>
                <?php endif; ?>

                <!-- Email search form -->
                <form action="manage-interest.php" method="GET" class="search-form">
                    <div class="form-row">
                        <label for="email">Email Address</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            required
                            value="<?php echo e($searchEmail); ?>"
                            placeholder="Enter the email you used"
                        >
                    </div>

                    <button type="submit" class="btn">Search</button>
                </form>

                <?php if ($searchEmail !== '' && !filter_var($searchEmail, FILTER_VALIDATE_EMAIL)): ?>
                    <!-- Show error for invalid email input -->
                    <div class="message error-message">Please enter a valid email address.</div>
                <?php endif; ?>
            </div>

            <?php if ($results): ?>
                <div class="results-card">
                    <h2>Your Registered Interests</h2>

                    <div class="interest-list">
                        <!-- Loop through all found registrations -->
                        <?php foreach ($results as $row): ?>
                            <article class="interest-item">
                                <div class="interest-details">
                                    <h3><?php echo e($row['ProgrammeName']); ?></h3>
                                    <p><strong>Name:</strong> <?php echo e($row['StudentName']); ?></p>
                                    <p><strong>Email:</strong> <?php echo e($row['Email']); ?></p>
                                    <p><strong>Registered:</strong> <?php echo e($row['RegisteredAt']); ?></p>
                                </div>

                                <!-- Form to withdraw selected interest -->
                                <form action="<?php echo BASE_URL; ?>/actions/withdraw-interest.php" method="POST" class="withdraw-form">
                                    <input type="hidden" name="interest_id" value="<?php echo (int)$row['InterestID']; ?>">
                                    <input type="hidden" name="email" value="<?php echo e($row['Email']); ?>">
                                    <button type="submit" class="btn-outline danger-btn" onclick="return confirm('Are you sure you want to withdraw this interest?');">
                                        Withdraw
                                    </button>
                                </form>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php elseif ($searchEmail !== '' && filter_var($searchEmail, FILTER_VALIDATE_EMAIL)): ?>
                <!-- Show this when no matching registrations are found -->
                <div class="results-card empty-state">
                    <h3>No registrations found</h3>
                    <p>There are no registered interests for this email address.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>