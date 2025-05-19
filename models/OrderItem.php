
<?php
require_once 'config/database.php';

class OrderItem {
    private $conn;
    private $table = 'order_items';
    
    // OrderItem properties
    public $id;
    public $order_id;
    public $product_id;
    public $qty;
    public $price;
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
    // Create order item
    public function create($itemData) {
        $query = "INSERT INTO " . $this->table . " (id, order_id, product_id, qty, price) VALUES (:id, :order_id, :product_id, :qty, :price)";
        
        $stmt = $this->conn->prepare($query);
        
        // Clean data
        $this->id = htmlspecialchars(strip_tags($itemData['id']));
        $this->order_id = htmlspecialchars(strip_tags($itemData['order_id']));
        $this->product_id = htmlspecialchars(strip_tags($itemData['product_id']));
        $this->qty = htmlspecialchars(strip_tags($itemData['qty']));
        $this->price = htmlspecialchars(strip_tags($itemData['price']));
        
        // Bind data
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':order_id', $this->order_id);
        $stmt->bindParam(':product_id', $this->product_id);
        $stmt->bindParam(':qty', $this->qty);
        $stmt->bindParam(':price', $this->price);
        
        try {
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    
    // Get items by order ID
    public function getOrderItems($orderId) {
        $query = "SELECT oi.*, p.name as product_name, p.image 
                  FROM " . $this->table . " oi
                  JOIN products p ON oi.product_id = p.id
                  WHERE oi.order_id = :order_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':order_id', $orderId);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>