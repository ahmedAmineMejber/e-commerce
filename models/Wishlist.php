
<?php
require_once 'config/database.php';

class Wishlist {
    private $conn;
    private $table = 'wishlist';
    
    // Wishlist properties
    public $id;
    public $user_id;
    public $product_id;
    public $price;
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
    // Add item to wishlist
    public function addItem($userId, $productId, $price) {
        // Check if product already in wishlist
        $existingItem = $this->getItem($userId, $productId);
        
        if ($existingItem) {
            return [
                'success' => true,
                'message' => 'Item already in wishlist',
                'id' => $existingItem['id']
            ];
        }
        
        // Insert new item
        $query = "INSERT INTO " . $this->table . " (id, user_id, product_id, price) VALUES (:id, :user_id, :product_id, :price)";
        
        $stmt = $this->conn->prepare($query);
        
        // Generate unique ID
        $id = uniqid('wish_');
        
        // Bind data
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':product_id', $productId);
        $stmt->bindParam(':price', $price);
        
        try {
            if ($stmt->execute()) {
                return [
                    'success' => true,
                    'id' => $id
                ];
            }
            return [
                'success' => false,
                'message' => 'Failed to add item to wishlist'
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }
    
    // Get specific wishlist item
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
    
    // Remove item from wishlist
    public function removeItem($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        
        try {
            if ($stmt->execute()) {
                return [
                    'success' => true
                ];
            }
            return [
                'success' => false,
                'message' => 'Failed to remove item from wishlist'
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }
    
    // Remove item by product ID
    public function removeByProductId($userId, $productId) {
        $query = "DELETE FROM " . $this->table . " WHERE user_id = :user_id AND product_id = :product_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':product_id', $productId);
        
        try {
            if ($stmt->execute()) {
                return [
                    'success' => true
                ];
            }
            return [
                'success' => false,
                'message' => 'Failed to remove item from wishlist'
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }
    
    // Get user's wishlist
    public function getUserWishlist($userId) {
        $query = "SELECT w.*, 
                  p.name, p.image, p.stock, p.description, c.name as category
                  FROM " . $this->table . " w
                  JOIN products p ON w.product_id = p.id
                  JOIN categories c ON p.category_id = c.id
                  WHERE w.user_id = :user_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Check if product is in user's wishlist
    public function isInWishlist($userId, $productId) {
        $query = "SELECT COUNT(*) as count FROM " . $this->table . " WHERE user_id = :user_id AND product_id = :product_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':product_id', $productId);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $row['count'] > 0;
    }
    
    // Get wishlist count
    public function getCountByUser($userId) {
        $query = "SELECT COUNT(*) as count FROM " . $this->table . " WHERE user_id = :user_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $row['count'];
    }
}
?>
