<?php
session_start();

// Include database connection
require_once 'config/database.php';

// Base URL configuration
define('BASE_URL', 'http://localhost/boutique_en_ligne/');

// File upload directory
define('UPLOAD_DIR', 'assets/images/products/');

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Helper functions
function redirect($url) {
    header("Location: " . BASE_URL . $url);
    exit();
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}
?>