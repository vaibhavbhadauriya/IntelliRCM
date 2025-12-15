<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Get current page for active navigation
$current_page = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="IntelliRCM - Better Revenue Starts Here. 98%+ Coding Accuracy, 20+ Years Experience">
    <title><?php echo $page_title ?? 'IntelliRCM | Better Revenue Starts Here'; ?></title>
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Sticky Navigation -->
    <nav class="navbar" id="navbar">
        <div class="container nav-container">
            <div class="logo-container">
                <a href="index.php">
                    <img src="https://www.intellircm.com/wp-content/uploads/2021/03/IntelliRCM-Logo-1.png" alt="IntelliRCM Logo" class="logo-img">
                </a>
            </div>
            
            <ul class="nav-menu" id="navMenu">
                <li><a href="our-approach.php" class="nav-link <?php echo $current_page === 'our-approach' ? 'active' : ''; ?>">Our Approach</a></li>
                <li><a href="services.php" class="nav-link <?php echo $current_page === 'services' ? 'active' : ''; ?>">What We Do</a></li>
                <li><a href="who-we-serve.php" class="nav-link <?php echo $current_page === 'who-we-serve' ? 'active' : ''; ?>">Who We Serve</a></li>
                <li><a href="case-studies.php" class="nav-link <?php echo $current_page === 'case-studies' ? 'active' : ''; ?>">Case Studies</a></li>
                <li><a href="blog.php" class="nav-link <?php echo $current_page === 'blog' ? 'active' : ''; ?>">Blog</a></li>
                <li><a href="about.php" class="nav-link <?php echo $current_page === 'about' ? 'active' : ''; ?>">About</a></li>
                <li><a href="contact.php" class="nav-cta <?php echo $current_page === 'contact' ? 'active' : ''; ?>">Let's Talk</a></li>
            </ul>
            
            <button class="hamburger" id="hamburger" aria-label="Menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </nav>
