<?php
// Load config and helper functions
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

// Set page title and CSS file
$pageTitle = 'Contact Us | ' . SITE_NAME;
$pageCSS = 'contact.css';
?>

<?php include __DIR__ . '/includes/header.php'; ?>
<?php include __DIR__ . '/includes/navbar.php'; ?>

<main id="main-content">

    <!-- Contact page intro section -->
    <section class="contact-hero">
        <div class="container">
            <div class="contact-hero-box">
                <div>
                    <p class="eyebrow">Get in touch</p>
                    <h1>Contact Us</h1>
                    <p>
                        Reach out to the University of Dev for admissions support,
                        programme enquiries, and general information.
                    </p>
                </div>
                <img src="<?php echo BASE_URL; ?>/assets/images/logo.png" alt="University of Dev logo">
            </div>
        </div>
    </section>

    <!-- Contact details section -->
    <section class="contact-section">
        <div class="container">
            <div class="contact-grid">

                <!-- Admissions contact information -->
                <div class="contact-card">
                    <h2>Admissions Office</h2>
                    <p><strong>Email:</strong> admissions@unidev.local</p>
                    <p><strong>Phone:</strong> +44 0000 123456</p>
                    <p><strong>Hours:</strong> Monday to Friday, 9:00 AM - 5:00 PM</p>
                </div>

                <!-- Main campus address -->
                <div class="contact-card">
                    <h2>Main Campus</h2>
                    <p>University of Dev</p>
                    <p>123 Innovation Street</p>
                    <p>London, United Kingdom</p>
                </div>

                <!-- Student support information -->
            <div class="contact-card">
    <h2>Student Support</h2>

    <p>
        Need help with programmes, applications, or your interest submission?
        Our support team is here to assist you.
    </p>

    <p>
        Email us directly or register your interest and we will get back to you.
    </p>

    <div class="support-actions">
        <a href="mailto:admissions@unidev.local" class="btn-outline">Email Support</a>
    
    </div>
</div>
            </div>
        </div>
    </section>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>