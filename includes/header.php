<?php
$pageTitle = $pageTitle ?? 'Thikana';
$currentPage = $currentPage ?? current_page();
$flash = get_flash();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($pageTitle); ?> | Thikana</title>
    <meta name="description" content="Thikana is a trust-first PG and hostel discovery platform for students who want clear pricing, verified listings, and direct WhatsApp contact.">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
</head>
<body>
<div class="site-shell">
    <header class="site-header">
        <div class="container">
            <nav class="navbar">
                <a href="<?php echo BASE_URL; ?>/index.php" class="brand" aria-label="Thikana home">
                    <span class="brand-mark" aria-hidden="true">
                        <span class="pin-shape"></span>
                        <span class="home-shape"></span>
                    </span>
                    <span class="brand-text">Thikana</span>
                </a>

                <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="navMenu" data-nav-toggle>
                    <span></span>
                    <span></span>
                    <span></span>
                </button>

                <div class="nav-menu" id="navMenu" data-nav-menu>
                    <a class="<?php echo $currentPage === 'index.php' ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>/index.php">Home</a>
                    <a class="<?php echo $currentPage === 'listings.php' || $currentPage === 'listing-details.php' ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>/listings.php">Listings</a>
                    <a class="<?php echo $currentPage === 'compare.php' ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>/compare.php">Compare</a>
                    <a class="<?php echo $currentPage === 'roommate-board.php' || $currentPage === 'submit-roommate-post.php' ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>/roommate-board.php">Roommate Board</a>
                    <a class="<?php echo $currentPage === 'owner-list-property.php' || $currentPage === 'process-owner-listing.php' ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>/owner-list-property.php">List Property</a>
                    <button class="theme-toggle" type="button" data-theme-toggle aria-label="Toggle dark mode" aria-pressed="false">
                        <span class="theme-toggle-track">
                            <span class="theme-toggle-thumb"></span>
                        </span>
                        <span class="theme-toggle-label">Dark mode</span>
                    </button>
                </div>
            </nav>
        </div>
    </header>

    <main class="page-main">
        <?php if ($flash): ?>
            <div class="container flash-wrap">
                <div class="flash-message <?php echo e($flash['type']); ?>">
                    <?php echo e($flash['message']); ?>
                </div>
            </div>
        <?php endif; ?>
