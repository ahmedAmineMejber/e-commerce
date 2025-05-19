
<?php
require_once 'config/database.php';

class Cart {
    private $conn;
    private $table = 'cart';
    
    // Cart properties
    public $id;
    public $user_id;
    public $product_id;
    public $price;
    public $qty;
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
    // Add item to cart
    public function addItem($userId, $productId, $price, $qty = 1) {
        // Check if product already in cart
        $existingItem = $this->getItem($userId, $productId);
        
        if ($existingItem) {
            // Update quantity if already in cart
            return $this->updateQuantity($existingItem['id'], $existingItem['qty'] + $qty);
        }
        
        // Insert new item
        $query = "INSERT INTO " . $this->table . " (id, user_id, product_id, price, qty) VALUES (:id, :user_id, :product_id, :price, :qty)";
        
        $stmt = $this->conn->prepare($query);
        
        // Generate unique ID
        $id = uniqid('cart_');
        
        // Bind data
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':product_id', $productId);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':qty', $qty);
        
        try {
            if ($stmt->execute()) {
                $cartModel = new Cart();
    $_SESSION['cart_count'] = count($cartModel->getUserCart($_SESSION['user_id']));
                return [
                    'success' => true,
                    'id' => $id
                ];
            }
            return [
                'success' => false,
                'message' => 'Failed to add item to cart'
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }
    
    // Get specific cart item
    public function getItem($userId, $productId) {
        $query = "SELECT * FROM " . $this->table . " WHERE user_id = :user_id AND product_id = :product_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':product_id', $productId);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            return $row;
        }
        
        return false;
    }
    
    // Update cart item quantity
    public function updateQuantity($id, $qty) {
        $query = "UPDATE " . $this->table . " SET qty = :qty WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':qty', $qty);
        
        try {
            if ($stmt->execute()) {
                return [
                    'success' => true
                ];
            }
            return [
                'success' => false,
                'message' => 'Failed to update cart'
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }
    
    // Remove item from cart
    public function removeItem($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        
        try {
            if ($stmt->execute()) {
                $cartModel = new Cart();
    $_SESSION['cart_count'] = count($cartModel->getUserCart($_SESSION['user_id']));
                return [
                    'success' => true
                ];
            }
            return [
                'success' => false,
                'message' => 'Failed to remove item from cart'
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }
    
    // Get user's cart
    public function getUserCart($userId) {
        $query = "SELECT c.*, 
                  p.name, p.image, p.stock 
                  FROM " . $this->table . " c
                  JOIN products p ON c.product_id = p.id
                  WHERE c.user_id = :user_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Get cart total price
    public function getCartTotal($userId) {
        $query = "SELECT SUM(price * qty) as total FROM " . $this->table . " WHERE user_id = :user_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $row['total'] ? $row['total'] : 0;
    }
    
    // Get cart count
    public function getCountByUser($userId) {
        $query = "SELECT COUNT(*) as count FROM " . $this->table . " WHERE user_id = :user_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $row['count'];
    }
    
    // Clear user's cart
    public function clearCart($userId) {
        $query = "DELETE FROM " . $this->table . " WHERE user_id = :user_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        
        try {
            if ($stmt->execute()) {
                $cartModel = new Cart();
                $_SESSION['cart_count'] = count($cartModel->getUserCart($_SESSION['user_id']));
                return true;
            }
            return false;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
}
?>
