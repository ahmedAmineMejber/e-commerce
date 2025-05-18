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

// Update wishlist count
  require_once 'models/Wishlist.php';
  $wishlistModel = new Wishlist();
  $_SESSION['wishlist_count'] = count($wishlistModel->getUserWishlist($_SESSION['user_id']));


// Update cart count
  require_once 'models/Cart.php';
  $cartModel = new Cart();
  $_SESSION['cart_count'] = count($cartModel->getUserCart($_SESSION['user_id']));

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
    <meta name="msapplication-TileImage" content="assets2/images/favicon.ico">
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
    </style>

</head>

<body class="theme-color4 light ltr">
    <style>
        header .profile-dropdown ul li {
            display: block;
            padding: 5px 20px;
            border-bottom: 1px solid #ddd;
            line-height: 35px;
        }

        header .profile-dropdown ul li:last-child {
            border-color: #fff;
        }

        header .profile-dropdown ul {
            padding: 10px 0;
            min-width: 250px;
        }

        .name-usr {
            background: #e87316;
            padding: 8px 12px;
            color: #fff;
            font-weight: bold;
            text-transform: uppercase;
            line-height: 24px;
        }

        .name-usr span {
            margin-right: 10px;
        }

        @media (max-width:600px) {
            .h-logo {
                max-width: 150px !important;
            }

            i.sidebar-bar {
                font-size: 22px;
            }

            .mobile-menu ul li a svg {
                width: 20px;
                height: 20px;
            }

            .mobile-menu ul li a span {
                margin-top: 0px;
                font-size: 12px;
            }

            .name-usr {
                padding: 5px 12px;
            }
        }
    </style>
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
                <a href="<?php echo BASE_URL; ?>logout.php" class="text-red-600 hover:underline">Logout</a>
                <?php endif; ?>
                <div class="header-user-actions">
                    <?php if (!empty($is_logged_in)): ?>
                    <a href="<?php echo BASE_URL; ?>account" class="action-btn">
                        <ion-icon name="person-outline"></ion-icon>
                    </a>
                    <?php else: ?>
                    <a href="<?php echo BASE_URL; ?>login" class="action-btn">
                        <ion-icon name="person-outline"></ion-icon>
                    </a>
                    <?php endif; ?>

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

    <div class="mobile-menu d-sm-none">
        <ul>
            <li>
                <a href="demo3.php" class="active">
                    <i data-feather="home"></i>
                    <span>Home</span>
                </a>
            </li>
            <li>
                <a href="javascript:void(0)">
                    <i data-feather="align-justify"></i>
                    <span>Category</span>
                </a>
            </li>
            <li>
                <a href="javascript:void(0)">
                    <i data-feather="shopping-bag"></i>
                    <span>Cart</span>
                </a>
            </li>
            <li>
                <a href="javascript:void(0)">
                    <i data-feather="heart"></i>
                    <span>Wishlist</span>
                </a>
            </li>
            <li>
                <a href="user-dashboard.php">
                    <i data-feather="user"></i>
                    <span>Account</span>
                </a>
            </li>
        </ul>
    </div>
    <style>
        a.disabled,
        a.disabled:hover .fas {
            color: grey !important;
            cursor: not-allowed;
        }
    </style>
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
                    <h3>Wishlist</h3>
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="<?php echo BASE_URL; ?>/index.htm">
                                    <i class="fas fa-home"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Wishlist</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>
    <?php 

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page
    header('Location: ' . BASE_URL . 'login?redirect=cart');
    exit;
}

// Include the Cart model
require_once 'models/Cart.php';
$cartModel = new Cart();

// Get cart items
$userId = $_SESSION['user_id'];
$cartItems = $cartModel->getUserCart($userId);
$cartTotal = $cartModel->getCartTotal($userId);
?>
    <!-- Cart Section Start -->
<!-- Cart Section Start -->
<section class="cart-section section-b-space">
    <div class="container">
        <div class="row">
            <?php if (empty($cartItems)): ?>
            <div class="col-md-12 text-center py-5">
                <div class="empty-cart">
                    <i class="fas fa-shopping-cart fa-4x mb-4"></i>
                    <h3>Your cart is empty</h3>
                    <p class="mb-4">Looks like you haven't added any products to your cart yet.</p>
                    <a href="<?php echo BASE_URL; ?>products" class="btn btn-solid-default">
                        Start Shopping
                    </a>
                </div>
            </div>
            <?php else: ?>
            <div class="col-md-12 text-center">
                <table class="table cart-table">
                    <thead>
                        <tr class="table-head">
                            <th scope="col">image</th>
                            <th scope="col">product name</th>
                            <th scope="col">price</th>
                            <th scope="col">quantity</th>
                            <th scope="col">total</th>
                            <th scope="col">action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cartItems as $item): ?>
                        <tr>
                            <td>
                                <a href="<?php echo BASE_URL; ?>product?id=<?php echo $item['product_id']; ?>">
                                    <img src="<?php echo BASE_URL; ?>assets/images/products/<?php echo $item['image']; ?>" 
                                         class="blur-up lazyloaded" alt="<?php echo $item['name']; ?>">
                                </a>
                            </td>
                            <td>
                                <a href="<?php echo BASE_URL; ?>product?id=<?php echo $item['product_id']; ?>">
                                    <?php echo $item['name']; ?>
                                </a>
                                <?php if ($item['stock'] < 5): ?>
                                <div class="text-danger small mt-1">
                                    Only <?php echo $item['stock']; ?> left in stock
                                </div>
                                <?php endif; ?>
                                <div class="mobile-cart-content row">
                                    <div class="col">
                                        <div class="qty-box">
                                            <div class="input-group">
                                                <input type="text" name="quantity" 
                                                       class="form-control input-number" 
                                                       value="<?php echo $item['qty']; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <h2>$<?php echo number_format($item['price'], 2); ?></h2>
                                    </div>
                                    <div class="col">
                                        <h2 class="td-color">
                                            <form action="<?php echo BASE_URL; ?>cart" method="post" style="display:inline;">
                                                <input type="hidden" name="action" value="remove">
                                                <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                                                <button type="submit" class="text-red-500 hover:text-red-700">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        </h2>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <h2>$<?php echo number_format($item['price'], 2); ?></h2>
                            </td>
                            <td>
                                <form action="<?php echo BASE_URL; ?>cart" method="post" class="quantity-form">
                                    <input type="hidden" name="action" value="update">
                                    <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                                    <div class="qty-box">
                                        <div class="input-group cart-qty">

                                            <input type="number" name="quantity" 
                                                   class="form-control input-number cart-qty-value" 
                                                   value="<?php echo $item['qty']; ?>" 
                                                   min="1" max="<?php echo $item['stock']; ?>" 
                                                   onchange="this.form.submit()">

                                        </div>
                                    </div>
                                </form>
                            </td>
                            <td>
                                <h2 class="td-color item-subtotal-<?php echo $item['id']; ?>">
                                    $<?php echo number_format($item['price'] * $item['qty'], 2); ?>
                                </h2>
                            </td>
                            <td style="text-align: center; vertical-align: middle;">
    <form action="<?php echo BASE_URL; ?>cart" method="post" style="display: inline-block; margin: 0 auto;">
        <input type="hidden" name="action" value="remove">
        <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
        <button type="submit" class="text-red-500 hover:text-red-700" style="border: none; background: none; cursor: pointer;">
            <i class="fas fa-times"></i>
        </button>
    </form>
</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="col-12 mt-md-5 mt-4">
                <div class="row">
                    <div class="col-sm-7 col-5 order-1">
                        <div class="left-side-button text-end d-flex d-block justify-content-end">
                            <form action="<?php echo BASE_URL; ?>cart" method="post" style="display:inline;">
                                <input type="hidden" name="action" value="clear">
                                <button type="submit" class="text-decoration-underline theme-color d-block text-capitalize bg-transparent border-0">
                                    clear all items
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="col-sm-5 col-7">
                        <div class="left-side-button float-start">
                            <a href="<?php echo BASE_URL; ?>products" class="btn btn-solid-default btn fw-bold mb-0 ms-0">
                                <i class="fas fa-arrow-left"></i> Continue Shopping
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="cart-checkout-section">
                <div class="row g-4">
                    <div class="col-lg-4 col-sm-6">
                        <div class="promo-section">
                            <!-- Coupon code input can be added here -->
                        </div>
                    </div>

                    <div class="col-lg-4 col-sm-6">
                        <div class="checkout-button">
                            <a href="<?php echo BASE_URL; ?>checkout" class="btn btn-solid-default btn fw-bold">
                                Check Out <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="cart-box">
                            <div class="cart-box-details">
                                <div class="total-details">
                                    <div class="top-details">
                                        <h3>Cart Totals</h3>
                                        <h6>Sub Total <span class="cart-total">$<?php echo number_format($cartTotal, 2); ?></span></h6>
                                        <h6>Tax <span>$0.00</span></h6>
                                        <h6>Total <span class="cart-total">$<?php echo number_format($cartTotal, 2); ?></span></h6>
                                    </div>
                                    <div class="bottom-details">
                                        <a href="<?php echo BASE_URL; ?>checkout">Process Checkout</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>


   
  

                    <div class="ratio_asos mt-4">
                        <div class="container">
                            <div class="row m-0">
                                <div class="col-sm-12 p-0">
                                    <div
                                        class="product-wrapper product-style-2 slide-4 p-0 light-arrow bottom-space spacing-slider">
                                        <div>
                                            
                                        </div>

                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tap-to-top">
        <a href="#home">
            <i class="fas fa-chevron-up"></i>
        </a>
    </div>
    <div class="bg-overlay"></div>
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
// This handles direct input changes (when user types a number)
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.cart-qty-value').forEach(input => {
        input.addEventListener('change', function() {
            this.form.submit();
        });
    });
});
</script>
    
<script src="./assets/js/script.js"></script>
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>