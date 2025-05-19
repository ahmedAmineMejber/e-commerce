<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Define BASE_URL only if not already defined
if (!defined('BASE_URL')) {
    define('BASE_URL', '/E-commerceP/'); // Adjust to your project path
}

// Initialize session variables if not set
$_SESSION['cart_count'] = $_SESSION['cart_count'] ?? 0;
$_SESSION['wishlist_count'] = $_SESSION['wishlist_count'] ?? 0;

// Set variables for the template
$is_logged_in = isset($_SESSION['user_id']);
$wishlist_count = $_SESSION['wishlist_count'];
$cart_count = $_SESSION['cart_count'];
if($is_logged_in)
$is_admin = ($_SESSION['user_role'] === 'admin');

// For testing - simulate a logged in user (remove in production)
// $_SESSION['user_id'] = 1; // Uncomment to test logged-in state


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShopifyX - Online Shopping</title>

    <!--
    - favicon
  -->
    <link rel="shortcut icon" href="<?php echo BASE_URL; ?>/assets/images/logo/favicon.ico" type="image/x-icon">

    <!--
    - custom css link
  -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style-prefix.css">




    <!--
    - google font link
  -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

        <style>
.account-dropdown {
    position: relative;
    display: inline-block;
}

.account-dropdown .dropdown-menu {
    position: absolute;
    background-color: #fff;
    min-width: 160px;
    box-shadow: 0 8px 16px rgba(0,0,0,0.2);
    z-index: 1;
    border-radius: 8px;
    padding: 10px 0;
    margin-top: 10px;
    /* Initial state */
    opacity: 0;
    visibility: hidden;
    transform: translateY(10px);
    transition: 
        opacity 0.3s ease,
        visibility 0.3s ease,
        transform 0.3s ease;
    /* Add delay for hover out */
    transition-delay: 0.1s;
}

.account-dropdown .dropdown-menu a {
    color: #333;
    padding: 10px 20px;
    text-decoration: none;
    display: block;
    white-space: nowrap;
}

.account-dropdown .dropdown-menu a:hover {
    background-color: #f5f5f5;
}

.account-dropdown:hover .dropdown-menu {
    /* Show with animation */
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
    /* Remove delay when hovering in */
    transition-delay: 0s;
}

/* Add this to make the dropdown stay visible when moving cursor from button to menu */
.account-dropdown .dropdown-menu:hover {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.account-dropdown .action-btn {
    color: #333;
    font-size: 1.5rem;
    /* Make the button a bit larger for better hover area */
    padding: 8px;
    display: inline-block;
}
</style>
</head>

<body>


    <div class="overlay" data-overlay></div>

    <!--
    - MODAL
  -->
    <!-- 
  <div class="modal" data-modal>

    <div class="modal-close-overlay" data-modal-overlay></div>

    <div class="modal-content">

      <button class="modal-close-btn" data-modal-close>
        <ion-icon name="close-outline"></ion-icon>
      </button>

      <div class="newsletter-img">
        <img src="./assets/images/newsletter.png" alt="subscribe newsletter" width="400" height="400">
      </div>

      <div class="newsletter">

        <form action="#">

          <div class="newsletter-header">

            <h3 class="newsletter-title">Subscribe Newsletter.</h3>

            <p class="newsletter-desc">
              Subscribe the <b>Anon</b> to get latest products and discount update.
            </p>

          </div>

          <input type="email" name="email" class="email-field" placeholder="Email Address" required>

          <button type="submit" class="btn-newsletter">Subscribe</button>

        </form>

      </div>

    </div>

  </div> -->





    <!--
    - NOTIFICATION TOAST
  -->

    <div class="notification-toast" data-toast>

        <button class="toast-close-btn" data-toast-close>
            <ion-icon name="close-outline"></ion-icon>
        </button>

        <div class="toast-banner">
            <img src="./assets/images/products/jewellery-1.jpg" alt="Rose Gold Earrings" width="80" height="70">
        </div>

        <div class="toast-detail">

            <p class="toast-message">
                Someone in new just bought
            </p>

            <p class="toast-title">
                Rose Gold Earrings
            </p>

            <p class="toast-meta">
                <time datetime="PT2M">2 Minutes</time> ago
            </p>

        </div>

    </div>





    <!--
    - HEADER
  -->

    <header>
       <div class="header-main">
    <div class="container">
        <a href="<?php echo BASE_URL; ?>" class="header-logo">
            <img src="<?php echo BASE_URL; ?>assets/images/logo/logo.svg" alt="Anon's logo" width="120" height="36">
        </a>

        <div class="header-search-container">
            <form action="<?php echo BASE_URL; ?>search" method="GET">
                <input type="search" name="search" class="search-field" placeholder="Enter your product name..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                <button type="submit" class="search-btn">
                    <ion-icon name="search-outline"></ion-icon>
                </button>
            </form>
        </div>

        <div class="header-user-actions">



            <div class="account-dropdown">
                <a href="#" class="action-btn">
                    <ion-icon name="person-outline"></ion-icon>
                </a>
                <div class="dropdown-menu">
                    <?php if (!empty($is_logged_in)): ?>
                        <!-- <a href="<?php echo BASE_URL; ?>account">My Account</a> -->
                        <a href="<?php echo BASE_URL; ?>logout">Logout</a>

                    <?php else: ?>
                        <a href="<?php echo BASE_URL; ?>login">Login</a>
                        <a href="<?php echo BASE_URL; ?>register">Register</a>
                    <?php endif; ?>
                </div>
            </div>

            <a href="<?php echo BASE_URL; ?>wishlist" class="action-btn">
                <ion-icon name="heart-outline"></ion-icon>
                <span class="count"><?php echo $wishlist_count; ?></span>
            </a>

            <a href="<?php echo BASE_URL; ?>cart" class="action-btn">
                <ion-icon name="bag-handle-outline"></ion-icon>
                <span class="count"><?php echo $cart_count; ?></span>
            </a>
            <?php if (!empty($is_logged_in) && !empty($is_admin) && $is_admin === true): ?>
                <!-- Admin Dashboard Icon -->
                <a href="<?php echo BASE_URL; ?>admin" class="action-btn" title="Admin Dashboard">
                    <ion-icon name="speedometer-outline"></ion-icon>
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>


        <!-- DESKTOP NAVIGATION -->
        <nav class="desktop-navigation-menu">
            <div class="container">
                <ul class="desktop-menu-category-list">
                    <li class="menu-category"><a href="<?php echo BASE_URL; ?>" class="menu-title">Home</a></li>
                    <li class="menu-category"><a href="<?php echo BASE_URL; ?>products?category=men"
                            class="menu-title">Men's</a></li>
                    <li class="menu-category"><a href="<?php echo BASE_URL; ?>products?category=women"
                            class="menu-title">Women's</a></li>
                    <li class="menu-category"><a href="<?php echo BASE_URL; ?>products?category=jewelry"
                            class="menu-title">Jewelry</a></li>
                    <li class="menu-category"><a href="<?php echo BASE_URL; ?>products?category=perfume"
                            class="menu-title">Perfume</a></li>
                </ul>
            </div>
        </nav>

        <!-- MOBILE NAVIGATION -->
        <div class="mobile-bottom-navigation">
            <button class="action-btn" data-mobile-menu-open-btn>
                <ion-icon name="menu-outline"></ion-icon>
            </button>
            <a href="<?php echo BASE_URL; ?>cart" class="action-btn">
                <ion-icon name="bag-handle-outline"></ion-icon>
                <span class="count"><?php echo isset($_SESSION['cart_count']) ? $_SESSION['cart_count'] : '0'; ?></span>
            </a>
            <a href="<?php echo BASE_URL; ?>" class="action-btn">
                <ion-icon name="home-outline"></ion-icon>
            </a>
            <a href="<?php echo BASE_URL; ?>wishlist" class="action-btn">
                <ion-icon name="heart-outline"></ion-icon>
                <span
                    class="count"><?php echo isset($_SESSION['wishlist_count']) ? $_SESSION['wishlist_count'] : '0'; ?></span>
            </a>
            <button class="action-btn" data-mobile-menu-open-btn>
                <ion-icon name="grid-outline"></ion-icon>
            </button>
        </div>
    </header>
    <!--
    - custom js link
  -->

    <!--
    - ionicon link
  -->