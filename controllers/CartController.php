
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
    // Handle form requests (no AJAX)
public function handleRequest() {
    // Start session if not already started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['message'] = [
            'type' => 'error',
            'text' => 'You must be logged in to manage your cart.'
        ];
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }

    $userId = $_SESSION['user_id'];
    $action = $_POST['action'] ?? '';

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
            $_SESSION['message'] = [
                'type' => 'error',
                'text' => 'Invalid action.'
            ];
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit;
    }
}

// Add product to cart
private function addToCart($userId) {
    $productId = $_POST['product_id'] ?? null;
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

    if (!$productId) {
        $_SESSION['message'] = [
            'type' => 'error',
            'text' => 'Product ID is required.'
        ];
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }

    // Get product details
    $product = $this->productModel->read($productId);

    if (!$product) {
        $_SESSION['message'] = [
            'type' => 'error',
            'text' => 'Product not found.'
        ];
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }

    // Check stock
    if ($product['stock'] < $quantity) {
        $_SESSION['message'] = [
            'type' => 'error',
            'text' => 'Not enough stock available.'
        ];
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }

    // Add to cart
    $result = $this->cartModel->addItem($userId, $productId, $product['price'], $quantity);

    if ($result['success']) {
        $_SESSION['message'] = [
            'type' => 'success',
            'text' => 'Product added to cart!'
        ];
    } else {
        $_SESSION['message'] = [
            'type' => 'error',
            'text' => $result['message']
        ];
    }

    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

// Update cart item quantity
private function updateCart($userId) {
    $itemId = $_POST['item_id'] ?? null;
    $quantity = (int)($_POST['quantity'] ?? 0);

    if (!$itemId || $quantity <= 0) {
        $_SESSION['message'] = [
            'type' => 'error',
            'text' => 'Item ID and valid quantity are required.'
        ];
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }

    // Update cart
    $result = $this->cartModel->updateQuantity($itemId, $quantity);

    if ($result['success']) {
        $_SESSION['message'] = [
            'type' => 'success',
            'text' => 'Cart updated successfully.'
        ];
    } else {
        $_SESSION['message'] = [
            'type' => 'error',
            'text' => $result['message']
        ];
    }

    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

// Remove item from cart
private function removeFromCart($userId) {
    $itemId = $_POST['item_id'] ?? null;

    if (!$itemId) {
        $_SESSION['message'] = [
            'type' => 'error',
            'text' => 'Item ID is required.'
        ];
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }

    // Remove from cart
    $result = $this->cartModel->removeItem($itemId);

    if ($result['success']) {
        $_SESSION['message'] = [
            'type' => 'success',
            'text' => 'Item removed from cart.'
        ];
    } else {
        $_SESSION['message'] = [
            'type' => 'error',
            'text' => $result['message']
        ];
    }

    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
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
