<?php
// Load config, database connection, and helper functions
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

// Set page title and CSS file
$pageTitle = 'Programmes | ' . SITE_NAME;
$pageCSS = 'programmes.css';

// Get selected filters from URL
$selectedLevel = isset($_GET['level']) ? trim($_GET['level']) : '';
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';

// Only allow valid study levels
$allowedLevels = ['Undergraduate', 'Postgraduate'];
if (!in_array($selectedLevel, $allowedLevels, true)) {
    $selectedLevel = '';
}

// Get filtered programmes
$programmes = getProgrammes($pdo, null, $selectedLevel, $searchTerm);
?>

<?php include __DIR__ . '/includes/header.php'; ?>
<?php include __DIR__ . '/includes/navbar.php'; ?>

<main id="main-content">
    <!-- Page intro section -->
    <section class="page-hero">
        <div class="container">
            <div class="page-hero-box">
                <div>
                    <p class="eyebrow">Explore your future</p>
                    <h1>Our Programmes</h1>
                    <p>
                        Browse undergraduate and postgraduate degrees, compare study options,
                        and discover modules and academic staff.
                    </p>
                </div>
                <img src="<?php echo BASE_URL; ?>/assets/images/logo.png" alt="University of Dev logo">
            </div>
        </div>
    </section>

    <!-- Search and filter form -->
    <section class="filters-section">
        <div class="container">
            <form action="programmes.php" method="GET" class="filters-form">
                <div class="form-group">
                    <label for="search">Search programmes</label>
                    <input 
                        type="text" 
                        name="search" 
                        id="search" 
                        placeholder="e.g. Cyber Security"
                        value="<?php echo e($searchTerm); ?>"
                    >
                </div>

                <div class="form-group">
                    <label for="level">Filter by level</label>
                    <select name="level" id="level">
                        <option value="">All levels</option>
                        <option value="Undergraduate" <?php echo $selectedLevel === 'Undergraduate' ? 'selected' : ''; ?>>
                            Undergraduate
                        </option>
                        <option value="Postgraduate" <?php echo $selectedLevel === 'Postgraduate' ? 'selected' : ''; ?>>
                            Postgraduate
                        </option>
                    </select>
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn">Apply Filters</button>
                    <a href="programmes.php" class="btn-outline">Reset</a>
                </div>
            </form>
        </div>
    </section>

    <!-- Programmes list section -->
    <section class="programmes-list-section">
        <div class="container">
            <div class="section-heading left-align">
                <h2>Available Programmes</h2>
                <p>
                    <?php echo count($programmes); ?> programme(s) found
                    <?php if ($selectedLevel): ?>
                        for <strong><?php echo e($selectedLevel); ?></strong>
                    <?php endif; ?>
                    <?php if ($searchTerm): ?>
                        matching <strong>"<?php echo e($searchTerm); ?>"</strong>
                    <?php endif; ?>
                </p>
            </div>

            <?php if ($programmes): ?>
                <div class="programme-grid">
                    <!-- Loop through all programmes -->
                    <?php foreach ($programmes as $programme): ?>
                        <article class="programme-card-full">
                            <div class="programme-card-image">
                                <img src="<?php echo BASE_URL; ?>/assets/images/logo.png" alt="Programme visual">
                            </div>

                            <div class="programme-card-body">
                                <span class="programme-badge"><?php echo e($programme['LevelName']); ?></span>
                                <h3><?php echo e($programme['ProgrammeName']); ?></h3>
                                <p><?php echo e(shortText($programme['Description'], 150)); ?></p>
                                <p><strong>Programme Leader:</strong> <?php echo e($programme['ProgrammeLeader']); ?></p>

                                <div class="programme-card-actions">
                                    <a href="programme-details.php?id=<?php echo (int)$programme['ProgrammeID']; ?>" class="btn">
                                        View Details
                                    </a>
                                    <a href="register-interest.php?programme_id=<?php echo (int)$programme['ProgrammeID']; ?>" class="btn-outline">
                                        Register Interest
                                    </a>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <!-- Show this if no programmes match the filters -->
                <div class="empty-state">
                    <h3>No programmes found</h3>
                    <p>Try changing your search term or filter.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>