<?php
require_once 'config/database.php';

class Category {
    private $conn;
    private $table = 'categories';
    
    // Category properties
    public $id;
    public $name;
    public $description;
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
    // Create category
    public function create($categoryData) {
        $query = "INSERT INTO " . $this->table . " (id, name, description) VALUES (:id, :name, :description)";
        
        $stmt = $this->conn->prepare($query);
        
        // Clean data
        $this->id = htmlspecialchars(strip_tags($categoryData['id']));
        $this->name = htmlspecialchars(strip_tags($categoryData['name']));
        $this->description = htmlspecialchars(strip_tags($categoryData['description']));
        
        // Bind data
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':description', $this->description);
        
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
    
    // Get all categories
    public function getAllCategories() {
       
        
    
        $query = "SELECT * FROM " . $this->table . " ORDER BY name";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }
    
    // Read single category
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
    
    // Update category
    public function update($categoryData) {
        $query = "UPDATE " . $this->table . " SET 
            name = :name, 
            description = :description 
            WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        // Clean data
        $this->id = htmlspecialchars(strip_tags($categoryData['id']));
        $this->name = htmlspecialchars(strip_tags($categoryData['name']));
        $this->description = htmlspecialchars(strip_tags($categoryData['description']));
        
        // Bind data
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':description', $this->description);
        
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
    
    // Delete category
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
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
}
?>