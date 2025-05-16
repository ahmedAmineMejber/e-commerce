
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
                $this->addToWishlist($userId);
                break;
            case 'remove':
                $this->removeFromWishlist($userId);
                break;
            default:
                $this->sendJsonResponse([
                    'success' => false,
                    'message' => 'Invalid action'
                ]);
        }
    }
    
    // Add product to wishlist
    private function addToWishlist($userId) {
        if (!isset($_POST['product_id'])) {
            $this->sendJsonResponse([
                'success' => false,
                'message' => 'Product ID is required'
            ]);
            return;
        }
        
        $productId = $_POST['product_id'];
        
        // Get product details
        $product = $this->productModel->read($productId);
        
        if (!$product) {
            $this->sendJsonResponse([
                'success' => false,
                'message' => 'Product not found'
            ]);
            return;
        }
        
        // Add to wishlist
        $result = $this->wishlistModel->addItem($userId, $productId, $product['price']);
        
        if ($result['success']) {
            // Update wishlist count in session
            $_SESSION['wishlist_count'] = $this->wishlistModel->getCountByUser($userId);
            
            $this->sendJsonResponse([
                'success' => true,
                'message' => 'Product added to wishlist',
                'count' => $_SESSION['wishlist_count']
            ]);
        } else {
            $this->sendJsonResponse([
                'success' => false,
                'message' => $result['message']
            ]);
        }
    }
    
    // Remove item from wishlist
    private function removeFromWishlist($userId) {
        if (!isset($_POST['product_id'])) {
            $this->sendJsonResponse([
                'success' => false,
                'message' => 'Product ID is required'
            ]);
            return;
        }
        
        $productId = $_POST['product_id'];
        
        // Remove from wishlist
        $result = $this->wishlistModel->removeByProductId($userId, $productId);
        
        if ($result['success']) {
            // Update wishlist count in session
            $_SESSION['wishlist_count'] = $this->wishlistModel->getCountByUser($userId);
            
            $this->sendJsonResponse([
                'success' => true,
                'message' => 'Item removed from wishlist',
                'count' => $_SESSION['wishlist_count']
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
    $wishlistController = new WishlistController();
    $wishlistController->handleRequest();
}
?>
