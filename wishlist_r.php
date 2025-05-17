<?php
//this is the link between us and our controller
require_once 'controllers/WishlistController.php';

// Initialize the controller
$wishlistController = new WishlistController();

// Handle the incoming request
$wishlistController->handleRequest();
?>
