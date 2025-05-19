<?php
require_once 'config/database.php';

class User {
    private $conn;
    private $table = 'users';
    
    // User properties
    public $id;
    public $name;
    public $email;
    public $password;
    public $role;
    public $image;
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
    // Create user
    public function create($userData) {
        $query = "INSERT INTO " . $this->table . " (id, name, email, password, role, image) VALUES (:id, :name, :email, :password, :role, :image)";
        
        $stmt = $this->conn->prepare($query);
        
        // Clean data
        $this->id = htmlspecialchars(strip_tags($userData['id']));
        $this->name = htmlspecialchars(strip_tags($userData['name']));
        $this->email = htmlspecialchars(strip_tags($userData['email']));
        $this->password = $userData['password']; // Already hashed
        $this->role = htmlspecialchars(strip_tags($userData['role']));
        $this->image = htmlspecialchars(strip_tags($userData['image']));
        
        // Bind data
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':role', $this->role);
        $stmt->bindParam(':image', $this->image);
        
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
    
    // Read single user by ID
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
    
    // Find user by email
    public function findByEmail($email) {
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            return $row;
        }
        
        return false;
    }
    
    // Check if email exists
    public function emailExists($email) {
        $query = "SELECT COUNT(*) as count FROM " . $this->table . " WHERE email = :email";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $row['count'] > 0;
    }
    
    // Update user
    public function update($userData) {
        $query = "UPDATE " . $this->table . " SET 
            name = :name, 
            email = :email";
        
        // Check if password is being updated
        if (isset($userData['password']) && !empty($userData['password'])) {
            $query .= ", password = :password";
        }
        
        $query .= ", image = :image, role = :role 
            WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        // Clean data
        $this->id = htmlspecialchars(strip_tags($userData['id']));
        $this->name = htmlspecialchars(strip_tags($userData['name']));
        $this->email = htmlspecialchars(strip_tags($userData['email']));
        $this->image = htmlspecialchars(strip_tags($userData['image']));
        $this->role = htmlspecialchars(strip_tags($userData['role']));
        
        // Bind data
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':image', $this->image);
        $stmt->bindParam(':role', $this->role);
        
        // If password is being updated, hash it and bind
        if (isset($userData['password']) && !empty($userData['password'])) {
            $this->password = $userData['password']; // Already hashed in controller
            $stmt->bindParam(':password', $this->password);
        }
        
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
    
    // Delete user
    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        
        try {
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            // If there's a foreign key constraint (e.g., user has orders)
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    
    // Get all users (for admin)
    public function getAll() {
        $query = "SELECT id, name, email, role, image FROM " . $this->table;
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
