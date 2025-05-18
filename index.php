<?php
//to get rid of a header error (header already set)
ob_start();


// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start session for user authentication
session_start();

// Define base URL
define('BASE_URL', '/E-commerceP/');


// // Get the current route from URL
// $request_uri = $_SERVER['REQUEST_URI'];
// $route = trim(parse_url($request_uri, PHP_URL_PATH), '/');

// $basePath = 'E-commerceP';
// $route = trim(parse_url($request_uri, PHP_URL_PATH), '/');
// $route = preg_replace("#^$basePath/#", '', $route);


// // Set default route if empty

// echo "Parsed route: " . $route;


// Include the router
require_once 'router.php';
$router = new Router();

// Define your routes
$router->get('', 'views/includes/home2.php');
$router->get('home', 'views/includes/home.php');
$router->get('products', 'views/product_list.php');
$router->get('product', 'views/product_detail.php');
$router->get('cart', 'views/cart.php');
$router->get('checkout', 'views/checkout.php');
$router->get('order-confirmation', 'views/confirmation.php');
$router->get('login', 'views/login.php');
$router->get('register', 'views/register.php');
$router->get('account', 'views/account.php');
$router->get('wishlist', 'views/wishlist.php');
$router->get('admin', 'views/admin/dashboard.php');
$router->get('test', 'test_header.php');
$router->get('index1', 'index1.php');
$router->get('home2', 'views/includes/home2.php');
$router->get('search', 'views/product_list.php');
$router->get('cart2', 'views/cart2.php');



// Handle form submissions 
$router->post('login', 'views/login.php');
$router->post('register', 'views/register.php');
$router->post('checkout', 'controllers/CheckoutController.php');
$router->post('wishlist', 'controllers/WishlistController.php');
$router->post('cart', 'controllers/CartController.php');

// Fallback for 404
$router->get('404', 'views/not_found.php');

// Dispatch the request
$router->dispatch();
?>
<?php
$error = '';
if (isset($_SESSION['login_error'])) {
    $error = $_SESSION['login_error'];
    unset($_SESSION['login_error']);
}
// Flush output buffer
ob_end_flush();

?>