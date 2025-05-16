<?php
require_once '../models/Product.php';
require_once '../models/Category.php';
require_once '../models/User.php';

class AdminController {
    private $productModel;
    private $categoryModel;
    private $userModel;
    
    public function __construct() {
        if (!isAdmin()) {
            redirect('');
        }
        
        $this->productModel = new Product();
        $this->categoryModel = new Category();
        $this->userModel = new User();
    }
    
    public function dashboard() {
        $productCount = $this->productModel->getProductCount();
        $userCount = $this->userModel->getUserCount();
        $orderCount = 0; // Implement this
        
        include '../views/admin/dashboard.php';
    }
    
    public function manageProducts() {
        $products = $this->productModel->getAllProducts(1000); // Get all products
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Handle product creation/update
        }
        
        include '../views/admin/manage_products.php';
    }
    
    // Add other admin methods as needed
}
?>