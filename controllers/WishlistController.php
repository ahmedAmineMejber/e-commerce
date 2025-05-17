
<?php
require_once 'config/database.php';
require_once 'models/Wishlist.php';
require_once 'models/Product.php';

class WishlistController {
    private $wishlistModel;
    private $productModel;
    
    public function __construct() {
        $this->wishlistModel = new Wishlist();
        $this->productModel = new Product();
    }
    
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
            'text' => 'You must be logged in to add items to your wishlist.'
        ];
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }

    $userId = $_SESSION['user_id'];
    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'add':
            $this->addToWishlist($userId);
            break;
        case 'remove':
            $this->removeFromWishlist($userId);
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

private function addToWishlist($userId) {
    $productId = $_POST['product_id'] ?? null;

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

    // Add to wishlist
    $result = $this->wishlistModel->addItem($userId, $productId, $product['price']);
    
    if ($result['success']) {
        $_SESSION['message'] = [
            'type' => 'success',
            'text' => 'Product added to your wishlist!'
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

private function removeFromWishlist($userId) {
    $productId = $_POST['product_id'] ?? null;

    if (!$productId) {
        $_SESSION['message'] = [
            'type' => 'error',
            'text' => 'Product ID is required.'
        ];
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }

    // Remove from wishlist
    $result = $this->wishlistModel->removeByProductId($userId, $productId);
    
    if ($result['success']) {
        $_SESSION['message'] = [
            'type' => 'success',
            'text' => 'Product removed from your wishlist.'
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
    $wishlistController = new WishlistController();
    $wishlistController->handleRequest();
}
?>
