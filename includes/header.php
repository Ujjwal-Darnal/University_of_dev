<?php
if (!isset($pageTitle)) {
    $pageTitle = SITE_NAME;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($pageTitle); ?></title>

    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/global.css">

    <?php if (!empty($pageCSS)): ?>
        <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/<?php echo e($pageCSS); ?>">
    <?php endif; ?>
</head>
<body>
<a href="#main-content" class="skip-link">Skip to main content</a>