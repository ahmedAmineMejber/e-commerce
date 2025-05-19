
<?php
require_once 'config/database.php';
require_once 'models/User.php';
require_once 'models/Product.php';
require_once 'models/Category.php';
require_once 'models/Order.php';

class AdminController {
    private $userModel;
    private $productModel;
    private $categoryModel;
    private $orderModel;
    
    public function __construct() {
        $this->userModel = new User();
        $this->productModel = new Product();
        $this->categoryModel = new Category();
        $this->orderModel = new Order();
    }
    
    // Check admin access
    public function checkAdminAccess() {
        // Start session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Check if user is logged in and is an admin
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            // Redirect to home page
            header("Location: " . BASE_URL);
            exit;
        }
    }
    
    // Dashboard summary
    public function getDashboardSummary() {
        // Get counts of users, products, orders
        $users = $this->userModel->getAll();
        $userCount = count($users);
        
        $products = $this->productModel->getAll();
        $productCount = count($products);
        
        $orders = $this->orderModel->getAllOrders();
        $orderCount = count($orders);
        
        // Calculate revenue
        $revenue = 0;
        foreach ($orders as $order) {
            if ($order['payment_status'] === 'paid') {
                $revenue += $order['total_price'];
            }
        }
        
        return [
            'users' => $userCount,
            'products' => $productCount,
            'orders' => $orderCount,
            'revenue' => $revenue
        ];
    }
    
    // Get all users
    public function getAllUsers() {
        return $this->userModel->getAll();
    }
    
    // Get all products
    public function getAllProducts() {
        return $this->productModel->getAll();
    }
    
    // Get all categories
    public function getAllCategories() {
        return $this->categoryModel->getAllCategories();
    }
    
    // Add product
    public function addProduct($productData) {
        // Generate unique ID
        $productData['id'] = uniqid('prod_');
        
        // Set admin ID from session
        $productData['admin_id'] = $_SESSION['user_id'];
        
        // Add product
        $result = $this->productModel->create($productData);
        
        if ($result) {
            return [
                'success' => true,
                'message' => 'Product added successfully'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Failed to add product'
            ];
        }
    }
    
    // Update product
    public function updateProduct($productData) {
        // Update product
        $result = $this->productModel->update($productData);
        
        if ($result) {
            return [
                'success' => true,
                'message' => 'Product updated successfully'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Failed to update product'
            ];
        }
    }
    
    // Delete product
    public function deleteProduct($productId) {
        // Delete product (set as inactive)
        $result = $this->productModel->delete($productId);
        
        if ($result) {
            return [
                'success' => true,
                'message' => 'Product deleted successfully'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Failed to delete product'
            ];
        }
    }
    
    // Add category
    public function addCategory($categoryData) {
        // Generate unique ID
        $categoryData['id'] = uniqid('cat_');
        
        // Add category
        $result = $this->categoryModel->create($categoryData);
        
        if ($result) {
            return [
                'success' => true,
                'message' => 'Category added successfully'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Failed to add category'
            ];
        }
    }
    
    // Update category
    public function updateCategory($categoryData) {
        // Update category
        $result = $this->categoryModel->update($categoryData);
        
        if ($result) {
            return [
                'success' => true,
                'message' => 'Category updated successfully'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Failed to update category'
            ];
        }
    }
    
    // Delete category
    public function deleteCategory($categoryId) {
        // Delete category
        $result = $this->categoryModel->delete($categoryId);
        
        if ($result) {
            return [
                'success' => true,
                'message' => 'Category deleted successfully'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Failed to delete category'
            ];
        }
    }
}
?>