
<?php
require_once 'config/database.php';
require_once 'models/Order.php';
require_once 'models/OrderItem.php';
require_once 'models/Cart.php';
require_once 'models/Product.php';

class OrderController {
    private $orderModel;
    private $orderItemModel;
    private $cartModel;
    private $productModel;
    
    public function __construct() {
        $this->orderModel = new Order();
        $this->orderItemModel = new OrderItem();
        $this->cartModel = new Cart();
        $this->productModel = new Product();
    }
    
    // Process checkout
    public function processCheckout() {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Ensure user is logged in
        if (!isset($_SESSION['user_id'])) {
            return [
                'success' => false,
                'message' => 'User not logged in'
            ];
        }
        
        $userId = $_SESSION['user_id'];
        $cartItems = $this->cartModel->getUserCart($userId);
        
        if (empty($cartItems)) {
            return [
                'success' => false,
                'message' => 'Cart is empty'
            ];
        }
        
        // Calculate total
        $total = $this->cartModel->getCartTotal($userId);
        
        // Merge all form data into one array
        $orderData = array_merge([
            'id' => uniqid('order_'),
            'user_id' => $userId,
            'total_price' => $total,
            'status' => 'in progress',
            'payment_status' => 'pending',
            'method' => $_POST['method'] ?? 'Cash',
            'shipping_address' => $_POST['address'] ?? '',
            'city' => $_POST['city'] ?? '',
            'postal_code' => $_POST['postal_code'] ?? '',
            'country' => $_POST['country'] ?? '',
            'card_number' => $_POST['cc_number'] ?? '',
            'card_expiry' => $_POST['cc_expiry'] ?? '',
            'card_cvc' => $_POST['cc_cvc'] ?? ''
        ]);
        
    
        // Create order
        if (!$this->orderModel->create($orderData)) {
            return [
                'success' => false,
                'message' => 'Failed to create order'
            ];
        }
    
        // Add order items
        foreach ($cartItems as $item) {
            $itemData = [
                'id' => uniqid('item_'),
                'order_id' => $orderData['id'],
                'product_id' => $item['product_id'],
                'qty' => $item['qty'],
                'price' => $item['price']
            ];
            
            $this->orderItemModel->create($itemData);
            
            // Update product stock
            $product = $this->productModel->read($item['product_id']);
            if ($product) {
                $newStock = $product['stock'] - $item['qty'];
                $this->productModel->updateStock($item['product_id'], $newStock);
            }
        }
        
        // Clear cart
        $this->cartModel->clearCart($userId);
        $_SESSION['cart_count'] = 0;
        
        return [
            'success' => true,
            'order_id' => $orderData['id'],
            'message' => 'Order placed successfully'
        ];
    }
    
    
    // Get order details
    public function getOrderDetails($orderId) {
        // Start session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            return [
                'success' => false,
                'message' => 'User not logged in'
            ];
        }
        
        $userId = $_SESSION['user_id'];
        $isAdmin = isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
        
        // Get order
        $order = $this->orderModel->read($orderId);
        
        if (!$order) {
            return [
                'success' => false,
                'message' => 'Order not found'
            ];
        }
        
        // Check if user has permission to view this order
        if (!$isAdmin && $order['user_id'] !== $userId) {
            return [
                'success' => false,
                'message' => 'You do not have permission to view this order'
            ];
        }
        
        // Get order items
        $items = $this->orderItemModel->getOrderItems($orderId);
        
        return [
            'success' => true,
            'order' => $order,
            'items' => $items
        ];
    }
    
    // Get user's orders
    public function getUserOrders() {
        // Start session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            return [
                'success' => false,
                'message' => 'User not logged in'
            ];
        }
        
        $userId = $_SESSION['user_id'];
        
        // Get orders
        $orders = $this->orderModel->getUserOrders($userId);
        
        // Format orders to include items
        $formattedOrders = [];
        foreach ($orders as $order) {
            $items = $this->orderItemModel->getOrderItems($order['id']);
            $order['items'] = $items;
            $formattedOrders[] = $order;
        }
        
        return [
            'success' => true,
            'orders' => $formattedOrders
        ];
    }
    
    // Admin: Get all orders
    public function getAllOrders() {
        // Start session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Check if user is admin
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            return [
                'success' => false,
                'message' => 'Access denied'
            ];
        }
        
        // Get all orders
        $orders = $this->orderModel->getAllOrders();
        
        return [
            'success' => true,
            'orders' => $orders
        ];
    }
    
    // Admin: Update order status
    public function updateOrderStatus($orderId, $status) {
        // Start session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Check if user is admin
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            return [
                'success' => false,
                'message' => 'Access denied'
            ];
        }
        
        // Update status
        $result = $this->orderModel->updateStatus($orderId, $status);
        
        if ($result) {
            return [
                'success' => true,
                'message' => 'Order status updated'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Failed to update order status'
            ];
        }
    }
}
?>