<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>ShopifyX - Online Shopping</title>

  <link rel="shortcut icon" href="<?php echo BASE_URL; ?>assets/images/logo/favicon.ico" type="image/x-icon">
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style-prefix.css">
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">


  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap"
    rel="stylesheet">
</head>

<body>
<div class="overlay" data-overlay></div>

<!-- MODAL NEWSLETTER -->
<div class="modal" data-modal>
  <div class="modal-close-overlay" data-modal-overlay></div>
  <div class="modal-content">
    <button class="modal-close-btn" data-modal-close>
      <ion-icon name="close-outline"></ion-icon>
    </button>
    <div class="newsletter-img">
      <img src="<?php echo BASE_URL; ?>assets/images/newsletter.png" alt="subscribe newsletter" width="400" height="400">
    </div>
    <div class="newsletter">
      <form action="#">
        <div class="newsletter-header">
          <h3 class="newsletter-title">Subscribe Newsletter.</h3>
          <p class="newsletter-desc">Subscribe to <b>ShopifyX</b> for product updates and discounts.</p>
        </div>
        <input type="email" name="email" class="email-field" placeholder="Email Address" required>
        <button type="submit" class="btn-newsletter">Subscribe</button>
      </form>
    </div>
  </div>
</div>

<!-- TOAST -->
<div class="notification-toast" data-toast>
  <button class="toast-close-btn" data-toast-close>
    <ion-icon name="close-outline"></ion-icon>
  </button>
  <div class="toast-banner">
    <img src="<?php echo BASE_URL; ?>assets/images/products/jewellery-1.jpg" alt="Rose Gold Earrings" width="80" height="70">
  </div>
  <div class="toast-detail">
    <p class="toast-message">Someone just bought</p>
    <p class="toast-title">Rose Gold Earrings</p>
    <p class="toast-meta"><time datetime="PT2M">2 Minutes</time> ago</p>
  </div>
</div>

<!-- HEADER -->
<header>
  <div class="header-main">
    <div class="container">
      <a href="<?php echo BASE_URL; ?>" class="header-logo">
        <img src="<?php echo BASE_URL; ?>assets/images/logo/logo.svg" alt="ShopifyX logo" width="120" height="36">
      </a>
      <div class="header-search-container">
        <input type="search" name="search" class="search-field" placeholder="Enter your product name...">
        <button class="search-btn">
          <ion-icon name="search-outline"></ion-icon>
        </button>
      </div>
      <div class="header-user-actions">

        <!-- User / Account -->
        <?php if (isset($_SESSION['user_id'])): ?>
          <a href="<?php echo BASE_URL; ?>account" class="action-btn">
            <ion-icon name="person-outline"></ion-icon>
          </a>
        <?php else: ?>
          <a href="<?php echo BASE_URL; ?>login" class="action-btn">
            <ion-icon name="person-outline"></ion-icon>
          </a>
        <?php endif; ?>

        <!-- Wishlist -->
        <a href="<?php echo BASE_URL; ?>wishlist" class="action-btn">
          <ion-icon name="heart-outline"></ion-icon>
          <span class="count">
            <?php echo isset($_SESSION['wishlist_count']) ? $_SESSION['wishlist_count'] : '0'; ?>
          </span>
        </a>

        <!-- Cart -->
        <a href="<?php echo BASE_URL; ?>cart" class="action-btn">
          <ion-icon name="bag-handle-outline"></ion-icon>
          <span class="count">
            <?php echo isset($_SESSION['cart_count']) ? $_SESSION['cart_count'] : '0'; ?>
          </span>
        </a>
      </div>
    </div>
  </div>

  <!-- DESKTOP NAVIGATION -->
  <nav class="desktop-navigation-menu">
    <div class="container">
      <ul class="desktop-menu-category-list">
        <li class="menu-category"><a href="<?php echo BASE_URL; ?>" class="menu-title">Home</a></li>
        <li class="menu-category"><a href="<?php echo BASE_URL; ?>products?category=men" class="menu-title">Men's</a></li>
        <li class="menu-category"><a href="<?php echo BASE_URL; ?>products?category=women" class="menu-title">Women's</a></li>
        <li class="menu-category"><a href="<?php echo BASE_URL; ?>products?category=jewelry" class="menu-title">Jewelry</a></li>
        <li class="menu-category"><a href="<?php echo BASE_URL; ?>products?category=perfume" class="menu-title">Perfume</a></li>
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
      <span class="count"><?php echo isset($_SESSION['wishlist_count']) ? $_SESSION['wishlist_count'] : '0'; ?></span>
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

