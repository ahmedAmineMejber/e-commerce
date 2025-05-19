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


<?php
// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page
    header("Location: " . BASE_URL . "login");
    exit;
}

// Include required files
require_once 'models/Cart.php';
require_once 'controllers/OrderController.php';

// Initialize cart model
$cartModel = new Cart();
$userId = $_SESSION['user_id'];

// Get cart items and total
$cartItems = $cartModel->getUserCart($userId);
$cartTotal = $cartModel->getCartTotal($userId);

// Process checkout if form is submitted
$orderSuccess = false;
$orderId = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderController = new OrderController();
    $result = $orderController->processCheckout();
    
    if ($result['success']) {
        $orderSuccess = true;
        $orderId = $result['order_id'];
        // Redirect to order confirmation
        header("Location: " . BASE_URL . "order-confirmation?id=" . $orderId);
        exit;
    } else {
        $errorMessage = $result['message'];
    }
}

// Page title
$pageTitle = "Checkout";
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
                    <h3>Checkout</h3>
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="/">
                                    <i class="fas fa-home"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Checkout</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>

    <?php if (empty($cartItems)): ?>
        <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 p-4 rounded">
            <p>Your cart is empty. Please add items to your cart before checkout.</p>
            <a href="<?= BASE_URL ?>products" class="inline-block mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Continue Shopping
            </a>
        </div>
    <?php else: ?>
        <?php if ($errorMessage): ?>
            <div class="bg-red-50 border border-red-200 text-red-800 p-4 rounded mb-6">
                <?= $errorMessage ?>
            </div>
        <?php endif; ?>
    <!-- Cart Section Start -->
    <section class="section-b-space">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-8">
                <form class="needs-validation" method="POST" action="" id="checkoutForm">
                    <input type="hidden" name="_token" value="CVH6XgdFhoUV6OBdiTIlT2bviIidpb0qD6U1Vf68">
                    
                    <div id="shippingAddress" class="row g-4">
                        <h3 class="mb-3 theme-color">Shipping address</h3>
                        <div class="col-md-12">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" id="address" name="address" required></textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="postal_code" class="form-label">Postal Code</label>
                            <input type="text" class="form-control" id="postal_code" name="postal_code" placeholder="123456" required>
                        </div>

                        <div class="col-md-3">
                            <label for="country" class="form-label">Country</label>
                            <input type="text" class="form-control" id="country" name="country" placeholder="Country" required>
                        </div>
                        <div class="col-md-3">
                            <label for="city" class="form-label">City</label>
                            <input type="text" class="form-control" id="city" name="city" placeholder="City" required>
                        </div>

                    </div>

                    <hr class="my-lg-5 my-4">

                    <h3 class="mb-3">Payment</h3>

                    <div class="d-block my-3">
                        <div class="form-check custome-radio-box">
                            <input class="form-check-input" type="radio" name="method" id="cash" value="Cash" checked>
                            <label class="form-check-label" for="cash">Cash</label>
                        </div>
                        <div class="form-check custome-radio-box">
                            <input class="form-check-input" type="radio" name="method" id="credit_card" value="credit_card">
                            <label class="form-check-label" for="credit_card">Credit Card</label>
                        </div>
                    </div>

                    <div id="creditCardFields" class="row g-4" style="display: none;">

                        <div class="col-md-6">
                            <label for="cc-number" class="form-label">Credit card number</label>
                            <input type="text" class="form-control" id="cc-number" name="cc_number" placeholder="1234 5678 1234 5678">
                        </div>
                        <div class="col-md-3">
                            <label for="cc-expiry" class="form-label">Expiration</label>
                            <input type="text" class="form-control" id="cc-expiry" name="cc_expiry" placeholder="MM/YY">
                        </div>
                        <div class="col-md-3">
                            <label for="cc-cvc" class="form-label">CVV</label>
                            <input type="text" class="form-control" id="cc-cvc" name="cc_cvc" placeholder="123">
                        </div>
                    </div>

                    <button class="btn btn-solid-default mt-4" type="submit">Place Order</button>
                </form>
            </div>

            <div class="col-lg-4">
                <div class="your-cart-box">
                    <h3 class="mb-3 d-flex text-capitalize">Your cart<span class="badge bg-theme new-badge rounded-pill ms-auto bg-dark">0</span></h3>
                    <ul class="list-group mb-3">
                        <li class="list-group-item d-flex justify-content-between lh-condensed active">
                            <div class="text-dark">
                                <h6 class="my-0">Tax</h6>
                            </div>
                            <?php $tax = $cartTotal * 0.08; ?>

                            <span>$<?= number_format($tax, 2) ?></span>
                        </li>
                        <li class="list-group-item d-flex lh-condensed justify-content-between">
                            <span class="fw-bold">Total (USD)</span>
                            <?php $finalTotal = $cartTotal + $tax; ?>
                            <strong>$ <?= number_format($finalTotal, 2) ?></strong>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

    <!-- Cart Section End -->

   
    <div class="tap-to-top">
        <a href="#home">
            <i class="fas fa-chevron-up"></i>
        </a>
    </div>
    <div class="bg-overlay"></div>
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
    const cashOption = document.getElementById('cash');
    const creditCardOption = document.getElementById('credit_card');
    const creditCardFields = document.getElementById('creditCardFields');

    cashOption.addEventListener('change', () => {
        creditCardFields.style.display = 'none';
    });

    creditCardOption.addEventListener('change', () => {
        creditCardFields.style.display = 'block';
    });
</script>

    </script>
    
<script src="./assets/js/script.js"></script>
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>