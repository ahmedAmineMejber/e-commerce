<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start session for user authentication
session_start();

// Define base URL for the application
define('BASE_URL', '/E-commerceP/');

// Get the current route from URL
$request_uri = $_SERVER['REQUEST_URI'];
$route = trim(parse_url($request_uri, PHP_URL_PATH), '/');

$basePath = 'E-commerceP';
$route = trim(parse_url($request_uri, PHP_URL_PATH), '/');
$route = preg_replace("#^$basePath/#", '', $route);


// Set default route if empty
if (empty($route)) {
    $route = 'home';
}
echo "Parsed route: " . $route;


// Route to the appropriate page
switch ($route) {
    case 'E-commerceP':
        include 'views/includes/home.php';
        break;
    case 'products':
        include 'views/product_list.php';
        break;
    case 'product':
        include 'views/product_detail.php';
        break;
    case 'cart':
        include 'views/cart.php';
        break;
    case 'checkout':
        include 'views/checkout.php';
        break;
    case 'order-confirmation':
        include 'views/confirmation.php';
        break;
    case 'login':
        include 'views/login.php';
        break;
    case 'register':
        include 'views/register.php';
        break;
    case 'account':
        include 'views/account.php';
        break;
    case 'wishlist':
        include 'views/wishlist.php';
        break;
    case 'admin':
        include 'views/admin/dashboard.php';
        break;
    default:
        include 'views/not_found.php';
        break;
}
?>