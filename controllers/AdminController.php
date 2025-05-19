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
        
        // Calculate revenue and order statistics
        $revenue = 0;
        $completedOrders = 0;
        $pendingOrders = 0;
        $cancelledOrders = 0;
        
        foreach ($orders as $order) {
            if ($order['payment_status'] === 'paid') {
                $revenue += $order['total_price'];
            }
            
            if ($order['status'] === 'completed') {
                $completedOrders++;
            } elseif ($order['status'] === 'in progress') {
                $pendingOrders++;
            } elseif ($order['status'] === 'cancelled') {
                $cancelledOrders++;
            }
        }
        
        return [
            'users' => $userCount,
            'products' => $productCount,
            'orders' => $orderCount,
            'revenue' => $revenue,
            'completed_orders' => $completedOrders,
            'pending_orders' => $pendingOrders,
            'cancelled_orders' => $cancelledOrders
        ];
    }
    
    // Get all users
    public function getAllUsers() {
        return $this->userModel->getAll();
    }

    // Get user by ID
    public function getUserById($userId) {
        return $this->userModel->read($userId);
    }

    // Add user
    public function addUser($userData) {
        // Hash password
        $userData['password'] = password_hash($userData['password'], PASSWORD_BCRYPT);
        
        // Add user
        $result = $this->userModel->create($userData);
        
        if ($result) {
            return [
                'success' => true,
                'message' => 'User added successfully'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Failed to add user'
            ];
        }
    }

    // Update user
    public function updateUser($userData) {
        // Hash password if provided
        if (isset($userData['password']) && !empty($userData['password'])) {
            $userData['password'] = password_hash($userData['password'], PASSWORD_BCRYPT);
        }
        
        // Update user
        $result = $this->userModel->update($userData);
        
        if ($result) {
            return [
                'success' => true,
                'message' => 'User updated successfully'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Failed to update user'
            ];
        }
    }

    // Delete user
    public function deleteUser($userId) {
        // Don't allow deleting your own account
        if ($userId === $_SESSION['user_id']) {
            return [
                'success' => false,
                'message' => 'You cannot delete your own account'
            ];
        }
        
        // Delete user
        $result = $this->userModel->delete($userId);
        
        if ($result) {
            return [
                'success' => true,
                'message' => 'User deleted successfully'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Failed to delete user'
            ];
        }
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
 // Get sales data for dashboard chart
 public function getSalesData($period = 'week') {
    $sales = [];
    
    switch ($period) {
        case 'week':
            // Get last 7 days
            for ($i = 6; $i >= 0; $i--) {
                $date = date('Y-m-d', strtotime("-$i days"));
                $dayName = date('D', strtotime("-$i days"));
                $sales[$dayName] = [
                    'date' => $date,
                    'name' => $dayName,
                    'amount' => 0
                ];
            }
            break;
        case 'month':
            // Get last 30 days in 5-day intervals
            for ($i = 0; $i < 6; $i++) {
                $startDate = date('Y-m-d', strtotime("-" . (30 - ($i * 5)) . " days"));
                $endDate = $i == 5 ? date('Y-m-d') : date('Y-m-d', strtotime("-" . (25 - ($i * 5)) . " days"));
                $label = date('M d', strtotime($startDate)) . ' - ' . date('M d', strtotime($endDate));
                
                $sales[$label] = [
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'name' => $label,
                    'amount' => 0
                ];
            }
            break;
        case 'year':
            // Get last 12 months
            for ($i = 11; $i >= 0; $i--) {
                $monthDate = date('Y-m', strtotime("-$i months"));
                $monthName = date('M', strtotime("-$i months"));
                $sales[$monthName] = [
                    'month' => $monthDate,
                    'name' => $monthName,
                    'amount' => 0
                ];
            }
            break;
    }
    
    // Get all orders
    $orders = $this->orderModel->getAllOrders();
    
    // Calculate sales per period
    foreach ($orders as $order) {
        if ($order['payment_status'] === 'paid') {
            $orderDate = date('Y-m-d', strtotime($order['order_date']));
            $orderMonth = date('Y-m', strtotime($order['order_date']));
            $orderDayName = date('D', strtotime($order['order_date']));
            
            switch ($period) {
                case 'week':
                    if (isset($sales[$orderDayName]) && (strtotime($orderDate) >= strtotime('-7 days'))) {
                        $sales[$orderDayName]['amount'] += $order['total_price'];
                    }
                    break;
                case 'month':
                    foreach ($sales as $label => $data) {
                        if (strtotime($orderDate) >= strtotime($data['start_date']) && 
                            strtotime($orderDate) <= strtotime($data['end_date'])) {
                            $sales[$label]['amount'] += $order['total_price'];
                            break;
                        }
                    }
                    break;
                case 'year':
                    $orderMonthName = date('M', strtotime($order['order_date']));
                    if (isset($sales[$orderMonthName]) && 
                        (strtotime($orderMonth) >= strtotime(date('Y-m', strtotime('-11 months'))))) {
                        $sales[$orderMonthName]['amount'] += $order['total_price'];
                    }
                    break;
            }
        }
    }
    
    // Format data for charts
    $formattedData = [];
    foreach ($sales as $key => $value) {
        $formattedData[] = [
            'name' => $key,
            'amount' => $value['amount']
        ];
    }
    
    return $formattedData;
}

// Get recent orders for dashboard
public function getRecentOrders($limit = 5) {
    // Get all orders
    $orders = $this->orderModel->getAllOrders();
    
    // Sort by date (newest first)
    usort($orders, function($a, $b) {
        return strtotime($b['order_date']) - strtotime($a['order_date']);
    });
    
    // Return only the number specified by limit
    return array_slice($orders, 0, $limit);
}

// Get top selling products
public function getTopSellingProducts($limit = 5) {
    return $this->productModel->getTopSelling($limit);
}


}


?>