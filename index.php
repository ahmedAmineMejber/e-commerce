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

// Admin routes
// Admin routes
$router->get('admin', 'views/admin/dashboard.php');
$router->get('admin/manage-products', 'views/admin/manage_products.php');
$router->get('admin/manage-products/add', 'views/admin/product_form.php');
$router->get('admin/manage-products/edit', 'views/admin/product_form.php');
$router->get('admin/manage-categories', 'views/admin/manage_categories.php');
$router->get('admin/manage-users', 'views/admin/manage_users.php');
$router->get('admin/manage-users/add', 'views/admin/user_form.php');
$router->get('admin/manage-users/edit', 'views/admin/user_form.php');
$router->get('admin/orders', 'views/admin/orders.php');
$router->get('admin/orders/view', 'views/admin/order_detail.php');
$router->get('admin/user-details', 'views/admin/user_detail.php');
$router->get('admin/user-orders', 'views/admin/user_orders.php');

// Order routes
$router->get('orders', 'views/orders.php');
$router->get('order-details', 'views/order_detail.php');

// Define your routes
$router->get('', 'views/includes/home.php');
$router->get('home', 'views/includes/home.php');
$router->get('products', 'views/product_list.php');
$router->get('product', 'views/product_detail.php');
$router->get('cart', 'views/cart.php');
$router->get('checkout', 'views/checkout.php');
$router->get('order-confirmation', 'views/confirmation.php');
$router->get('login', 'views/login.php');
$router->get('register', 'views/register.php');
$router->get('login2', 'views/login2.php');
$router->get('account', 'views/account.php');
$router->get('wishlist', 'views/wishlist.php');
$router->get('admin', 'views/admin/dashboard.php');
$router->get('test', 'test_header.php');
$router->get('index1', 'index1.php');
$router->get('search', 'views/product_list.php');
$router->get('logout', 'logout.php');

$router->get('admin', 'views/admin/dashboard.php');

// Admin orders management routes
$router->get('admin/orders', 'views/admin/orders.php');
$router->get('admin/orders/view', 'views/admin/order_detail.php');

$router->post('admin/orders', 'views/admin/orders.php');
$router->post('admin/orders/view', 'views/admin/order_detail.php');

// User management routes
$router->get('admin/user-details', 'views/admin/user_detail.php');
$router->get('admin/user-orders', 'views/admin/user_orders.php');

// User management routes
$router->post('admin/user-details', 'views/admin/user_detail.php');
$router->post('admin/user-orders', 'views/admin/user_orders.php');



// Handle form submissions 
$router->post('login', 'views/login.php');
$router->post('register', 'views/register.php');
$router->post('checkout', 'views/checkout.php');
$router->post('wishlist', 'controllers/WishlistController.php');
$router->post('cart', 'controllers/CartController.php');
$router->post('admin/manage-users/add', 'views/admin/user_form.php');
$router->post('admin/manage-users/edit', 'views/admin/user_form.php');
//for deleting a user
$router->post('admin/manage-users', 'views/admin/manage_users.php');

$router->post('admin/manage-products', 'views/admin/manage_products.php');
$router->post('admin/manage-products/add', 'views/admin/product_form.php');
$router->post('admin/manage-products/edit', 'views/admin/product_form.php');

$router->post('admin/manage-categories', 'views/admin/manage_categories.php');




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