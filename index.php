<?php
// Load config, database connection, and helper functions
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

// Set page title and CSS file for this page
$pageTitle = 'Home | ' . SITE_NAME;
$pageCSS = 'index.css';

// Get 3 featured programmes from the database
$programmes = getProgrammes($pdo, 3);
?>

<?php include __DIR__ . '/includes/header.php'; ?>
<?php include __DIR__ . '/includes/navbar.php'; ?>

<main id="main-content">

    <!-- Hero section -->
    <section class="hero">
        <div class="container hero-inner">
            <div class="hero-text">
                <h1>Welcome to University of Dev</h1>
                <p>
                    Explore our programmes, discover modules, and connect with
                    expert academic staff. Build your future with confidence.
                </p>

                <div class="hero-buttons">
                    <a href="<?php echo BASE_URL; ?>/programmes.php" class="btn">Explore Programmes</a>
                    <a href="<?php echo BASE_URL; ?>/register-interest.php" class="btn-outline">Register Interest</a>
                </div>
            </div>

            <div class="hero-image">
                <img src="<?php echo BASE_URL; ?>/assets/images/stds.jpeg" alt="students picture">
            </div>
        </div>
    </section>

    <!-- Featured programmes section -->
    <section class="featured">
        <div class="container">
            <div class="section-heading">
                <h2>Featured Programmes</h2>
                <p>
                    Discover some of our most popular programmes designed to support
                    your academic and professional development.
                </p>
            </div>

            <?php if ($programmes): ?>
                <div class="card-grid">
                    <!-- Loop through each programme -->
                    <?php foreach ($programmes as $programme): ?>
                        <article class="card">
                            <div class="card-image">
                                <img src="<?php echo BASE_URL; ?>/assets/images/logo.png" alt="Programme image">
                            </div>

                            <div class="card-content">
                                <!-- Escape output for safety -->
                                <h3><?php echo e($programme['ProgrammeName']); ?></h3>

                                <p class="meta">
                                    <?php echo e($programme['LevelName']); ?> · 
                                    <?php echo e($programme['ProgrammeLeader']); ?>
                                </p>

                                <!-- Shorten long description text -->
                                <p>
                                    <?php echo e(shortText($programme['Description'], 120)); ?>
                                </p>

                                <!-- Send programme ID safely in URL -->
                                <a href="<?php echo BASE_URL; ?>/programme-details.php?id=<?php echo (int)$programme['ProgrammeID']; ?>" class="link">
                                    View Details →
                                </a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <!-- Show message when no programmes are available -->
                <p>No programmes available at the moment.</p>
            <?php endif; ?>

            <div class="section-footer">
                <a href="<?php echo BASE_URL; ?>/programmes.php" class="btn-outline">
                    View All Programmes
                </a>
            </div>
        </div>
    </section>

    <!-- Features section -->
    <section class="features">
        <div class="container">
            <div class="section-heading">
                <h2>What makes us different</h2>
                <p>
                    We provide a modern, student-focused platform to explore university education.
                </p>
            </div>

            <div class="card-grid">
                <article class="info-card">
                    <h3>Clear Programme Structure</h3>
                    <p>
                        Understand modules by year and how your degree is structured
                        before you apply.
                    </p>
                </article>

                <article class="info-card">
                    <h3>Expert Academic Staff</h3>
                    <p>
                        Learn from experienced lecturers and professionals across
                        all computing disciplines.
                    </p>
                </article>

                <article class="info-card">
                    <h3>Simple Registration</h3>
                    <p>
                        Register your interest quickly and stay updated with
                        admissions and opportunities.
                    </p>
                </article>
            </div>
        </div>
    </section>

    <!-- Call to action section -->
    <section class="cta">
        <div class="container">
            <div class="cta-box">
                <h2>Start your journey today</h2>
                <p>
                    Register your interest and stay informed about programmes,
                    deadlines, and upcoming opportunities.
                </p>

                <a href="<?php echo BASE_URL; ?>/register-interest.php" class="btn">
                    Register Now
                </a>
            </div>
        </div>
    </section>

</main>

<?php include __DIR__ . '/includes/footer.php'; ?>