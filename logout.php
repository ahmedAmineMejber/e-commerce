<?php
require_once 'index.php';

// Include the AuthController
require_once 'controllers/AuthController.php';

// Create a new AuthController instance
$auth = new AuthController();

// Call the logout function
$result = $auth->logout();

// Redirect to login page with a logout message
header('Location: /E-commerceP/');
exit;
?>
