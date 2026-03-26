<?php
// Load config (constants like SITE_NAME, BASE_URL) and helper functions
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

// Set page title and CSS file
$pageTitle = 'About Us | ' . SITE_NAME;
$pageCSS = 'about.css';
?>

<?php include __DIR__ . '/includes/header.php'; ?>
<?php include __DIR__ . '/includes/navbar.php'; ?>

<main id="main-content">

    <!-- About hero section (intro to the university) -->
    <section class="about-hero">
        <div class="container">
            <div class="about-hero-box">
                <div>
                    <p class="eyebrow">Who we are</p>
                    <h1>About University of Dev</h1>
                    <p>
                        University of Dev is committed to delivering innovative, career-focused
                        education that prepares students for success in modern computing and digital industries.
                    </p>
                </div>

                <!-- Logo image using BASE_URL -->
                <img src="<?php echo BASE_URL; ?>/assets/images/stdsss.jpeg" alt="University of Dev logo">
            </div>
        </div>
    </section>

    <!-- Main about content (mission, vision, reasons) -->
    <section class="about-section">
        <div class="container">
            <div class="about-grid">

                <!-- Mission -->
                <div class="about-card">
                    <h2>Our Mission</h2>
                    <p>
                        Our mission is to provide accessible, high-quality learning experiences
                        that combine academic excellence with practical industry knowledge.
                    </p>
                </div>

                <!-- Vision -->
                <div class="about-card">
                    <h2>Our Vision</h2>
                    <p>
                        We aim to become a leading destination for students seeking strong
                        foundations in computing, software development, and digital innovation.
                    </p>
                </div>

                <!-- Why choose this university -->
                <div class="about-card">
                    <h2>Why Choose Us</h2>
                    <p>
                        Our programmes are designed to support progression, employability,
                        and lifelong learning through clear programme structures and expert teaching staff.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Highlight section (student-focused approach) -->
    <section class="about-highlight">
        <div class="container">
            <div class="highlight-box">
                <h2>Student-Centred Learning</h2>
                <p>
                    From undergraduate pathways to postgraduate study, University of Dev offers
                    a student-first environment where learners can explore modules, connect with staff,
                    and make informed decisions about their academic future.
                </p>
            </div>
        </div>
    </section>

</main>

<?php include __DIR__ . '/includes/footer.php'; ?>