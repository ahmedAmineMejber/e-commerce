
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
require_once 'controllers/OrderController.php';

// Check if order ID is provided
if (!isset($_GET['id'])) {
    // Redirect to home page
    header("Location: " . BASE_URL);
    exit;
}

$orderId = $_GET['id'];
$orderController = new OrderController();
$result = $orderController->getOrderDetails($orderId);

// If order not found or user has no permission
if (!$result['success']) {
    // Redirect to home page
    header("Location: " . BASE_URL);
    exit;
}

$order = $result['order'];
$items = $result['items'];

// Page title
$pageTitle = "Order Confirmation";
?>

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
            <a href="javascript:void(0)" class="toggle-category">
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
    <!-- Order Success Section Start -->
    <section class="pt-0">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 p-0">
                    <div class="success-icon">
                        <div class="main-container">
                            <div class="check-container">
                                <div class="check-background">
                                    <svg viewbox="0 0 65 51" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M7 25L27.3077 44L58.5 7" stroke="white" stroke-width="13" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                </div>
                                <div class="check-shadow"></div>
                            </div>
                        </div>

                        <div class="success-contain">
                            <h4>Order Success</h4>
                            <h5 class="font-light">Payment Is Successfully Processsed And Your Order Is On The Way</h5>
                            <h6 class="font-light">Transaction ID:<?= substr($order['id'], 6) ?></h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Order Success Section End -->

    <!-- Oder Details Section Start -->
    <section class="section-b-space cart-section order-details-table">
        <div class="container">
            <div class="title title1 title-effect mb-1 title-left">
                <h2 class="mb-3">Order Details</h2>
            </div>
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="col-sm-12 table-responsive">
                        <table class="table cart-table table-borderless">
                            <tbody>
                               
                <?php foreach ($items as $item): ?>

                                <tr class="table-order">
                                    <td>
                                        <a href="javascript:void(0)">
                                            <img src="<?php echo BASE_URL; ?>assets/images/products/<?php echo $item['image']; ?>" 
                                             class="img-fluid blur-up lazyload" alt="">
                                        </a>
                                    </td>
                                    <td>
                                        <p class="font-light">Product Name</p>
                                        <h5><?= $item['product_name'] ?></h5>
                                    </td>
                                    <td>
                                        <p class="font-light">Quantity</p>
                                        <h5><?= $item['qty'] ?></h5>
                                    </td>
                                    <td>
                                        <p class="font-light">Price</p>
                                        <h5>$<?= number_format($item['qty'] * $item['price'], 2) ?></h5>
                                    </td>
                                </tr>
                                <?php endforeach; ?>

                            </tbody>
                            <tfoot>
                                <tr class="table-order">
                                    <td colspan="3">
                                        <h5 class="font-light">Subtotal :</h5>
                                    </td>
                                    <td>
                                        <h4>$<?= number_format($order['total_price'] , 2) ?></h4>
                                    </td>
                                </tr>

                                <tr class="table-order">
                                    <td colspan="3">
                                        <h5 class="font-light">Shipping :</h5>
                                    </td>
                                    <td>
                                        <h4>Free</h4>
                                    </td>
                                </tr>

                                <tr class="table-order">
                                    <td colspan="3">
                                        <h5 class="font-light">Tax(GST) :</h5>
                                    </td>
                                    <td>
                                        <h4>$<?= $tax = number_format($order['total_price'] * 0.08, 2) ?></h4>
                                    </td>
                                </tr>

                                <tr class="table-order">
                                    <td colspan="3">
                                        <h4 class="theme-color fw-bold">Total Price :</h4>
                                    </td>
                                    <td>
                                        <h4 class="theme-color fw-bold">$<?=  $total =  number_format($order['total_price'] + $tax , 2) ?></h4>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="order-success">
                        <div class="row g-4">
                            <div class="col-sm-6">
                                <h4>summery</h4>
                                <ul class="order-details">
                                    <li>Order ID: <?= substr($order['id'], 6) ?></li>
                                    <li>Order Date: <?= date('F j, Y', strtotime($order['order_date'])) ?></li>
                                    <li>Order Total: <?php echo number_format($order['total_price'] + $tax , 2) ?></li>
                                </ul>
                            </div>

                            <div class="col-sm-6">
                                <h4>shipping address</h4>
                                <ul class="order-details">
                                    <li><?php echo$order['shipping_address'] ?></li>

                                </ul>
                            </div>

                            <div class="col-12">
                                <div class="payment-mode">
                                    <h4>payment method</h4>
                                    <p><?php echo$order['method'] ?></p>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="delivery-sec">
                                    <h3>expected date of delivery: <span> <?= date('F j, Y', strtotime('+5 days')) ?> - <?= date('F j, Y', strtotime('+7 days')) ?></span></h3>
                                    <a href="<?= BASE_URL ?>products">Continue Shopping</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Order Details Section End -->

   
   

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
    
    
<script src="./assets/js/script.js"></script>
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
<script>
    $(function() {
        $('[data-bs-toggle="tooltip"]').tooltip()
    });
</script>    
</body>
</html>