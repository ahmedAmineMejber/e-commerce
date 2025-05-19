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

<?php 

if (!defined('BASE_URL')) {
    define('BASE_URL', '/ecommerce-pDb/');
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if form is submitted
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Include the Auth Controller
    require_once 'controllers/AuthController.php';
    $auth = new AuthController();
    
    // Process login
    $result = $auth->login($_POST['email'], $_POST['password']);
    
    if ($result['success']) {
        // Redirect to home page
        header('Location: ' . BASE_URL);
        exit;
    } else {
        $error = $result['message'];
    }
    
}
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

  
    <style>
        input [type="text"]:focus,
        [type="email"]:focus,
        [type="url"]:focus,
        [type="password"]:focus,
        [type="number"]:focus,
        [type="date"]:focus,
        [type="datetime-local"]:focus,
        [type="month"]:focus,
        [type="search"]:focus,
        [type="tel"]:focus,
        [type="time"]:focus,
        [type="week"]:focus,
        [multiple]:focus,
        textarea:focus,
        select:focus {
            --tw-ring-color: transparent !important;
            border-color: transparent !important;
        }

        input [type="text"]:hover,
        [type="email"]:hover,
        [type="url"]:hover,
        [type="password"]:hover,
        [type="number"]:hover,
        [type="date"]:hover,
        [type="datetime-local"]:hover,
        [type="month"]:hover,
        [type="search"]:hover,
        [type="tel"]:hover,
        [type="time"]:hover,
        [type="week"]:hover,
        [multiple]:hover,
        textarea:hover,
        select:hover {
            --tw-ring-color: transparent !important;
            border-color: transparent !important;
        }

        input [type="text"]:active,
        [type="email"]:active,
        [type="url"]:active,
        [type="password"]:active,
        [type="number"]:active,
        [type="date"]:active,
        [type="datetime-local"]:active,
        [type="month"]:active,
        [type="search"]:active,
        [type="tel"]:active,
        [type="time"]:active,
        [type="week"]:active,
        [multiple]:active,
        textarea:active,
        select:active {
            --tw-ring-color: transparent !important;
            border-color: transparent !important;
        }
    </style>
    <!-- Log In Section Start -->
   <!-- Log In Section Start -->
<div class="login-section">
    <div class="materialContainer">
        <div class="box">
            <?php if (isset($error) && !empty($error)): ?>
            <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
                <?php echo $error; ?>
            </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo BASE_URL; ?>login">
                <input type="hidden" name="_token" value="<?php echo $_SESSION['_token'] ?? '' ?>">
                <div class="login-title">
                    <h2>Login</h2>
                </div>
                <div class="input">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="" required autofocus autocomplete="username">
                </div>

                <div class="input">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required autocomplete="current-password">
                </div>



                <div class="button login">
                    <button type="submit">
                        <span>Log In</span>
                        <i class="fa fa-check"></i>
                    </button>
                </div>

                <p>Don't have an account? <a href="<?php echo BASE_URL; ?>register" class="theme-color">Sign up now</a></p>
            </form>
        </div>
    </div>
</div>
<!-- Log In Section End -->

    <!-- Log In Section End -->

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