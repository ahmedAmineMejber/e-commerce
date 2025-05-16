
<?php
require_once 'config/database.php';
require_once 'models/Cart.php';
require_once 'models/Product.php';

class CartController {
    private $cartModel;
    private $productModel;
    
    public function __construct() {
        $this->cartModel = new Cart();
        $this->productModel = new Product();
    }
    
    // Handle AJAX requests
    public function handleRequest() {
        // Start session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            $this->sendJsonResponse([
                'success' => false,
                'message' => 'User not logged in'
            ]);
            return;
        }
        
        $userId = $_SESSION['user_id'];
        $action = isset($_POST['action']) ? $_POST['action'] : '';
        
        switch ($action) {
            case 'add':
                $this->addToCart($userId);
                break;
            case 'update':
                $this->updateCart($userId);
                break;
            case 'remove':
                $this->removeFromCart($userId);
                break;
            default:
                $this->sendJsonResponse([
                    'success' => false,
                    'message' => 'Invalid action'
                ]);
        }
    }
    
    // Add product to cart
    private function addToCart($userId) {
        if (!isset($_POST['product_id'])) {
            $this->sendJsonResponse([
                'success' => false,
                'message' => 'Product ID is required'
            ]);
            return;
        }
        
        $productId = $_POST['product_id'];
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
        
        // Get product details
        $product = $this->productModel->read($productId);
        
        if (!$product) {
            $this->sendJsonResponse([
                'success' => false,
                'message' => 'Product not found'
            ]);
            return;
        }
        
        // Check stock
        if ($product['stock'] < $quantity) {
            $this->sendJsonResponse([
                'success' => false,
                'message' => 'Not enough stock available'
            ]);
            return;
        }
        
        // Add to cart
        $result = $this->cartModel->addItem($userId, $productId, $product['price'], $quantity);
        
        if ($result['success']) {
            // Update cart count in session
            $_SESSION['cart_count'] = $this->cartModel->getCountByUser($userId);
            
            $this->sendJsonResponse([
                'success' => true,
                'message' => 'Product added to cart',
                'count' => $_SESSION['cart_count']
            ]);
        } else {
            $this->sendJsonResponse([
                'success' => false,
                'message' => $result['message']
            ]);
        }
    }
    
    // Update cart item quantity
    private function updateCart($userId) {
        if (!isset($_POST['item_id']) || !isset($_POST['quantity'])) {
            $this->sendJsonResponse([
                'success' => false,
                'message' => 'Item ID and quantity are required'
            ]);
            return;
        }
        
        $itemId = $_POST['item_id'];
        $quantity = (int)$_POST['quantity'];
        
        if ($quantity <= 0) {
            $this->sendJsonResponse([
                'success' => false,
                'message' => 'Quantity must be greater than 0'
            ]);
            return;
        }
        
        // Update cart
        $result = $this->cartModel->updateQuantity($itemId, $quantity);
        
        if ($result['success']) {
            // Get updated cart total
            $total = $this->cartModel->getCartTotal($userId);
            
            // Get the individual item to calculate its subtotal
            $cartItems = $this->cartModel->getUserCart($userId);
            $subtotal = 0;
            
            foreach ($cartItems as $item) {
                if ($item['id'] === $itemId) {
                    $subtotal = $item['price'] * $item['qty'];
                    break;
                }
            }
            
            $this->sendJsonResponse([
                'success' => true,
                'message' => 'Cart updated',
                'total' => $total,
                'subtotal' => $subtotal
            ]);
        } else {
            $this->sendJsonResponse([
                'success' => false,
                'message' => $result['message']
            ]);
        }
    }
    
    // Remove item from cart
    private function removeFromCart($userId) {
        if (!isset($_POST['item_id'])) {
            $this->sendJsonResponse([
                'success' => false,
                'message' => 'Item ID is required'
            ]);
            return;
        }
        
        $itemId = $_POST['item_id'];
        
        // Remove from cart
        $result = $this->cartModel->removeItem($itemId);
        
        if ($result['success']) {
            // Update cart count in session
            $_SESSION['cart_count'] = $this->cartModel->getCountByUser($userId);
            
            // Get updated cart total
            $total = $this->cartModel->getCartTotal($userId);
            
            $this->sendJsonResponse([
                'success' => true,
                'message' => 'Item removed from cart',
                'count' => $_SESSION['cart_count'],
                'total' => $total
            ]);
        } else {
            $this->sendJsonResponse([
                'success' => false,
                'message' => $result['message']
            ]);
        }
    }
    
    // Send JSON response
    private function sendJsonResponse($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cartController = new CartController();
    $cartController->handleRequest();
}
?>
