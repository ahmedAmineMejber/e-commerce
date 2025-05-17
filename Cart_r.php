<?php
//this is the link between us and our controller

require_once 'controllers/CartController.php';

$cartController = new CartController();
$cartController->handleRequest();
?>
