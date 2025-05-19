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

// For testing - simulate a logged in user (remove in production)
// $_SESSION['user_id'] = 1; // Uncomment to test logged-in state
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="MkRqEzTGuoSx6LqJUm0OAKxSgNUYt26wTT7RMUZY">
    <link rel="manifest" href="manifest.json">
    <link rel="apple-touch-icon" href="<?php echo BASE_URL; ?>/assets2/images/favicon.ico">
    <link rel="icon" href="<?php echo BASE_URL; ?>/assets2/images/favicon.ico" type="image/x-icon">
    <link rel="icon" href="<?php echo BASE_URL; ?>/assets2/images/favicon.ico" type="image/x-icon">
    <meta name="theme-color" content="#e87316">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="Surfside Media">
    <meta name="msapplication-TileImage" conten="<?php echo BASE_URL; ?>/assets2/images/favicon.ico">
    <meta name="msapplication-TileColor" content="#FFFFFF">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Surfside Media">
    <meta name="keywords" content="Surfside Media">
    <meta name="author" content="Surfside Media">
    <link rel="preconnect" href="https://fonts.gstatic.com">

    <title>SurfsideMedia</title>

    <link id="rtl-link" rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>/assets2/css/vendors/bootstrap.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets2/css/vendors/ion.rangeSlider.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>/assets2/css/vendors/font-awesome.css">
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>/assets2/css/vendors/feather-icon.css">
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>/assets2/css/vendors/animate.css">
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>/assets2/css/vendors/slick/slick.css">
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>/assets2/css/vendors/slick/slick-theme.css">
    <link id="color-link" rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>/assets2/css/demo4.css">
    <style>
        .h-logo {
            max-width: 185px !important;
        }

        .f-logo {
            max-width: 220px !important;
        }

        @media only screen and (max-width: 600px) {
            .h-logo {
                max-width: 110px !important;
            }
        }
        .product-buttons {
    gap: 10px; /* Space between buttons */
}


    </style>
    <link id="color-link" rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>/assets2/css/demo2.css">
    <!--
    - favicon
  -->
  <link rel="shortcut icon" href="./assets/images/logo/favicon.ico" type="image/x-icon">

<!--
- custom css link
-->
<link rel="stylesheet" href="./assets/css/style-prefix.css">




<!--
- google font link
-->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap"
    rel="stylesheet">
<style>
    .product-count .count-items {
    display: flex;
    align-items: center;
    gap: 20px; /* Adjust spacing between items */
}

.product-count .count-item {
    display: flex;
    align-items: center;
    gap: 8px; /* Adjust spacing between elements within each item */
}

.product-count img {
    height: 20px; /* Adjust icon size as needed */
    width: auto;
}

.product-count .p-counter {
    font-weight: bold;
}

.product-count .lang {
    color: #666; /* Adjust text color as needed */
}
</style>

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


<?php 

// Check if product ID is provided
if (!isset($_GET['id'])) {
    header('Location: ' . BASE_URL . 'products');
    exit;
}

// Include models
require_once 'models/Product.php';
require_once 'models/Wishlist.php';

$productModel = new Product();
$wishlistModel = new Wishlist();

// Get product details
$productId = $_GET['id'];
$product = $productModel->read($productId);

// Check if product exists
if (!$product) {
    header('Location: ' . BASE_URL . 'not-found');
    exit;
}

// Check if product is in user's wishlist
$isInWishlist = false;
if (isset($_SESSION['user_id'])) {
    $isInWishlist = $wishlistModel->isInWishlist($_SESSION['user_id'], $productId);
}
?>


<body class="theme-color4 light ltr">
    
<header>
        <div class="header-main">
            <div class="container">
                <a href="<?php echo BASE_URL; ?>" class="header-logo">
                    <img src="<?php echo BASE_URL; ?>assets/images/logo/logo.svg" alt="Anon's logo" width="120"
                        height="36">
                </a>

                <div class="header-search-container">
                    <form action="<?php echo BASE_URL; ?>search" method="GET">
                        <input type="search" name="search" class="search-field" placeholder="Enter your product name..."
                            value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                        <button type="submit" class="search-btn">
                            <ion-icon name="search-outline"></ion-icon>
                        </button>
                    </form>

                </div>
                <?php if (isset($_SESSION['user_id'])): ?>
                <a href="<?php echo BASE_URL; ?>logout" class="text-red-600 hover:underline">Logout</a>
                <?php endif; ?>
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
                </div>
            </div>
        </div>


    </header>

   
    <section class="breadcrumb-section section-b-space" style="padding-top:20px;padding-bottom:20px;">
        <ul class="circles">
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
        </ul>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h3><?php echo $product['name']; ?></h3>
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="<?php echo BASE_URL; ?>">
                                    <i class="fas fa-home"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Product Details</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section> <!-- Shop Section start -->

    <section>
        <div class="container">
            <div class="row gx-4 gy-5">
                <div class="col-lg-12 col-12">
                    <div class="details-items">
                        <div class="row g-4">
                        <div class="col-md-6">
    <div class="row">
        <!-- Thumbnail Images -->
        <div class="col-lg-2">
            <div class="details-image-vertical black-slide rounded">
                <div>
                    <img 
                        src="<?php echo BASE_URL; ?>assets/images/products/<?php echo htmlspecialchars($product['image']); ?>"
                        class="img-fluid blur-up lazyload" 
                        alt="<?php echo htmlspecialchars($product['name']); ?>">
                </div>
                
                <?php if (!empty($product['image_hover'])): ?>
                <div>
                    <img 
                        src="<?php echo BASE_URL; ?>assets/images/products/<?php echo htmlspecialchars($product['image_hover']); ?>"
                        class="img-fluid blur-up lazyload" 
                        alt="<?php echo htmlspecialchars($product['name']); ?> (Hover)">
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Main Image and Hover Image -->
        <div class="col-lg-10">
            <div class="details-image-1 ratio_asos">
                <div>
                    <img 
                        src="<?php echo BASE_URL; ?>assets/images/products/<?php echo htmlspecialchars($product['image']); ?>" 
                        id="zoom_01" 
                        data-zoom-image="<?php echo BASE_URL; ?>assets/images/products/<?php echo htmlspecialchars($product['image']); ?>" 
                        class="img-fluid w-100 image_zoom_cls-0 blur-up lazyload" 
                        alt="<?php echo htmlspecialchars($product['name']); ?>">
                </div>
                
                <?php if (!empty($product['image_hover'])): ?>
                <div>
                    <img 
                        src="<?php echo BASE_URL; ?>assets/images/products/<?php echo htmlspecialchars($product['image_hover']); ?>" 
                        id="zoom_02" 
                        data-zoom-image="<?php echo BASE_URL; ?>assets/images/products/<?php echo htmlspecialchars($product['image_hover']); ?>" 
                        class="img-fluid w-100 image_zoom_cls-1 blur-up lazyload" 
                        alt="<?php echo htmlspecialchars($product['name']); ?> (Hover)">
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>


                            <div class="col-md-6">
                                <div class="cloth-details-size">
                                <div class="product-count">
    <ul style="display: flex; list-style: none; padding: 0; margin: 0; gap: 20px;">
        <li style="display: flex; align-items: center; gap: 8px;">
            <img src="<?php echo BASE_URL; ?>/assets2/images/gif/fire.gif"
                 class="img-fluid blur-up lazyload" alt="image">
            <span class="p-counter">37</span>
            <span class="lang">orders in last 24 hours</span>
        </li>
        <li style="display: flex; align-items: center; gap: 8px;">
            <img src="<?php echo BASE_URL; ?>/assets2/images/gif/person.gif"
                 class="img-fluid user_img blur-up lazyload" alt="image">
            <span class="p-counter">44</span>
            <span class="lang">active view this</span>
        </li>
    </ul>
</div>

                                    <div class="details-image-concept">
                                        <h2><?php echo $product['name']; ?></h2>
                                    </div>

                                    <div class="label-section">
                                        <span class="badge badge-grey-color">#1 Best seller</span>
                                        <span class="label-text">in <?php echo $product['category_name']; ?></span>
                                    </div>
                                    <?php if (!empty($product['brand']) || !empty($product['color'])): ?>

                                    <h3 class="font-semibold mb-2">Product Details : </h3>
                                    <?php if (!empty($product['brand'])): ?>
                                    <div class="label-section">
                                        <span class="badge badge-grey-color">Brand  </span> 
                                        <span class="label-text"> <?php echo $product['brand']; ?></span>
                                    </div>
                                    <?php endif; ?>

                                    <?php if (!empty($product['color'])): ?>
                                    <div class="label-section">
                                        <span class="badge badge-grey-color">Color</span>
                                        <span class="label-text"><?php echo $product['color']; ?></span>
                                    </div>
                                    <?php endif; ?>


                                    <?php if (!empty($product['age_range'])): ?>
                                    <div class="label-section">
                                        <span class="badge badge-grey-color">Age Range</span>
                                        <span class="label-text"><?php echo $product['age_range']; ?></span>
                                    </div>
                                    <?php endif; ?>

                                    <?php if (!empty($product['rating'])): ?>

<div class="label-section">

<span class="badge badge-grey-color">Rating</span>

<span class="label-text">
<div class="product-grid">


<div class="showcase-rating">
        <?php
        $rating = $product['rating']; // Get rating (0-5)
        $fullStars = floor($rating); // Full stars count
        $hasHalfStar = ($rating - $fullStars) >= 0.5; // Check for half star
        
        // Display 5 stars
        for ($i = 1; $i <= 5; $i++): 
            if ($i <= $fullStars): ?>
                <ion-icon name="star"></ion-icon>
            <?php elseif ($i == $fullStars + 1 && $hasHalfStar): ?>
                <ion-icon name="star-half"></ion-icon>
            <?php else: ?>
                <ion-icon name="star-outline"></ion-icon>
            <?php endif;
        endfor; ?>

</div></div></span>
</div>
<?php endif; ?>

                                        <?php endif; ?>

                                    <h3 class="price-detail"> $<?php echo number_format($product['price'], 2); ?>
                                    <del><?php echo number_format($product['original_price'], 2); ?></del><span>10% off</span>
                                </h3>

                                   
                                <div id="selectSize" class="addeffect-section product-description border-product">
    <h6 class="product-title product-title-2 d-block">Quantity</h6>
        <form action="<?php echo BASE_URL; ?>cart" method="post" class="quantity-form"  id="cart-form">
            <input type="hidden" name="action" value="add">
            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
            
            <div class="qty-box">
                <div class="input-group">
                    <span class="input-group-prepend">
                        <button type="button" class="btn quantity-left-minus" 
                            onclick="adjustQuantity(this, -1)" 
                            data-type="minus">
                            <i class="fas fa-minus"></i>
                        </button>
                    </span>
                    
                    <input type="number" name="quantity" class="form-control input-number" 
                        value="1" min="1" 
                        max="<?php echo $product['stock']; ?>">
                    
                    <span class="input-group-append">
                        <button type="button" class="btn quantity-right-plus" 
                            onclick="adjustQuantity(this, 1)" 
                            data-type="plus">
                            <i class="fas fa-plus"></i>
                        </button>
                    </span>
                </div>
            </div>
        </form>
        <div class="product-buttons">

    </div>
</div>



<div class="product-buttons">
    <!-- Wishlist Button -->
    <form action="<?php echo BASE_URL; ?>wishlist_r.php" method="post" class="product-button-form">
    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
    
    <?php if ($isInWishlist): ?>
        <input type="hidden" name="action" value="remove">
        <button type="submit" class="btn btn-solid remove">
            <i class="fa fa-bookmark fz-16 me-2"></i>
            <span>Remove from Wishlist</span>
        </button>
    <?php else: ?>
        <input type="hidden" name="action" value="add">
        <button type="submit" class="btn btn-solid add">
            <i class="fa fa-bookmark fz-16 me-2"></i>
            <span>Add to Wishlist</span>
        </button>
    <?php endif; ?>
</form>


    <!-- Add to Cart Button -->
       <!-- we used the "form" to have a link with our upper form that has the id of also "cart-form" -->

        <button type="submit" class="btn btn-solid hover-solid btn-animation"  form="cart-form">
            <i class="fa fa-shopping-cart"></i>
            <span>Add to Cart</span>
        </button>

</div>
                                   
<ul class="product-count shipping-order">
    <li>
        <img src="<?php echo BASE_URL; ?>/assets2/images/gif/truck.png" 
             class="img-fluid blur-up lazyload" alt="image">
        <span class="lang">Free shipping for orders above $500 USD</span>
    </li>
</ul>

<!-- Stock and Timer -->
<div class="mt-2 mt-md-3 border-product">
    <h6 class="product-title hurry-title d-block">
        Hurry Up! Only <span><?php echo $product['stock']; ?></span> left in stock
    </h6>
    <div class="progress">
        <div class="progress-bar" role="progressbar" 
             style="width: <?php echo ($product['stock'] / 100) * 100; ?>%">
        </div>
    </div>

                                    </div>

                                    <div class="border-product">
                                        <h6 class="product-title d-block">share it</h6>
                                        <div class="product-icon">
                                            <ul class="product-social">
                                                <li>
                                                    <a href="https://www.facebook.com/">
                                                        <i class="fab fa-facebook-f"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="https://www.google.com/">
                                                        <i class="fab fa-google-plus-g"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="https://twitter.com/">
                                                        <i class="fab fa-twitter"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="https://www.instagram.com/">
                                                        <i class="fab fa-instagram"></i>
                                                    </a>
                                                </li>
                                                <li class="pe-0">
                                                    <a href="https://www.google.com/">
                                                        <i class="fas fa-rss"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

    <script src="<?php echo BASE_URL; ?>/assets2/js/jquery-3.5.1.min.js"></script>
    <script src="<?php echo BASE_URL; ?>/assets2/js/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="<?php echo BASE_URL; ?>/assets2/js/feather/feather.min.js"></script>
    <script src="<?php echo BASE_URL; ?>/assets2/js/lazysizes.min.js"></script>
    <script src="<?php echo BASE_URL; ?>/assets2/js/slick/slick.js"></script>
    <script src="<?php echo BASE_URL; ?>/assets2/js/slick/slick-animation.min.js"></script>
    <script src="<?php echo BASE_URL; ?>/assets2/js/slick/custom_slick.js"></script>
    <script src="<?php echo BASE_URL; ?>/assets2/js/price-filter.js"></script>
    <script src="<?php echo BASE_URL; ?>/assets2/js/ion.rangeSlider.min.js"></script>
    <script src="<?php echo BASE_URL; ?>/assets2/js/filter.js"></script>
    <script src="<?php echo BASE_URL; ?>/assets2/js/newsletter.js"></script>
    <script src="<?php echo BASE_URL; ?>/assets2/js/cart_modal_resize.js"></script>
    <script src="<?php echo BASE_URL; ?>/assets2/js/bootstrap/bootstrap-notify.min.js"></script>
    <script src="<?php echo BASE_URL; ?>/assets2/js/theme-setting.js"></script>
    <script src="<?php echo BASE_URL; ?>/assets2/js/script.js"></script>
    <script>
        $(function () {
            $('[data-bs-toggle="tooltip"]').tooltip()
        });
    </script>
<script>
// Quantity adjustment without auto-submission
function adjustQuantity(button, delta) {
    const form = button.closest('form');
    const quantityInput = form.querySelector('input[name="quantity"]');
    const currentQuantity = parseInt(quantityInput.value) || 1;
    const newQuantity = Math.max(1, currentQuantity + delta);
    
    // Update the input value
    quantityInput.value = newQuantity;
}
</script>


<script src="./assets/js/script.js"></script>
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>