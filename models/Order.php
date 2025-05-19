
<?php
require_once 'config/database.php';

class Order {
    private $conn;
    private $table = 'orders';
    
    // Order properties
    public $id;
    public $user_id;
    public $total_price;
    public $status;
    public $payment_status;
    public $shipping_address;
    public $city;
    public $postal_code;
    public $country;
    public $card_number;
    public $card_expiry;
    public $method;


    public $card_cvc;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
    // Create order
    public function create($orderData) {
        $query = "INSERT INTO " . $this->table . " 
        (id, user_id, total_price, status, payment_status, shipping_address, city, postal_code, method, country, card_number, card_expiry, card_cvc) 
        VALUES 
        (:id, :user_id, :total_price, :status, :payment_status, :shipping_address, :city, :postal_code, :method, :country, :card_number, :card_expiry, :card_cvc)";
    
    $stmt = $this->conn->prepare($query);
    
    // Clean data
    $this->id = htmlspecialchars(strip_tags($orderData['id']));
    $this->user_id = htmlspecialchars(strip_tags($orderData['user_id']));
    $this->total_price = htmlspecialchars(strip_tags($orderData['total_price']));
    $this->status = htmlspecialchars(strip_tags($orderData['status']));
    $this->payment_status = htmlspecialchars(strip_tags($orderData['payment_status']));
    $this->shipping_address = htmlspecialchars(strip_tags($orderData['shipping_address']));
    $this->city = htmlspecialchars(strip_tags($orderData['city']));
    $this->postal_code = htmlspecialchars(strip_tags($orderData['postal_code']));
    $this->method = htmlspecialchars(strip_tags($orderData['method']));
    $this->country = htmlspecialchars(strip_tags($orderData['country']));
    $this->card_number = htmlspecialchars(strip_tags($orderData['card_number']));
    $this->card_expiry = htmlspecialchars(strip_tags($orderData['card_expiry']));
    $this->card_cvc = htmlspecialchars(strip_tags($orderData['card_cvc']));
    
    // Bind data
    $stmt->bindParam(':id', $this->id);
    $stmt->bindParam(':user_id', $this->user_id);
    $stmt->bindParam(':total_price', $this->total_price);
    $stmt->bindParam(':status', $this->status);
    $stmt->bindParam(':payment_status', $this->payment_status);
    $stmt->bindParam(':shipping_address', $this->shipping_address);
    $stmt->bindParam(':city', $this->city);
    $stmt->bindParam(':postal_code', var: $this->postal_code);
    $stmt->bindParam(':method', var: $this->method);
    $stmt->bindParam(':country', $this->country);
    $stmt->bindParam(':card_number', $this->card_number);
    $stmt->bindParam(':card_expiry', $this->card_expiry);
    $stmt->bindParam(':card_cvc', $this->card_cvc);
        
        try {
            if ($stmt->execute()) {
                return $this->id;
            }
            return false;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    
    // Read single order
    public function read($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            return $row;
        }
        
        return false;
    }
    
    // Get user's orders
    public function getUserOrders($userId) {
        $query = "SELECT * FROM " . $this->table . " WHERE user_id = :user_id ORDER BY order_date DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Get all orders (for admin)
    public function getAllOrders() {
        $query = "SELECT o.*, u.name as customer_name, u.email as customer_email 
                 FROM " . $this->table . " o
                 JOIN users u ON o.user_id = u.id
                 ORDER BY o.order_date DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Update order status
    public function updateStatus($id, $status) {
        $query = "UPDATE " . $this->table . " SET status = :status WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':status', $status);
        
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
    
    // Update payment status
    public function updatePaymentStatus($id, $paymentStatus) {
        $query = "UPDATE " . $this->table . " SET payment_status = :payment_status WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':payment_status', $paymentStatus);
        
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
}
?>