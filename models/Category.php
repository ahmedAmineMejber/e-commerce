
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
        // For demo purposes, let's create mock categories with icons
        // In a real app, you would fetch from the database
        
        $categories = [
            [
                'id' => 'cat_1',
                'name' => 'Electronics',
                'description' => 'Electronic devices and gadgets',
                'icon' => 'laptop'
            ],
            [
                'id' => 'cat_2',
                'name' => 'Fashion',
                'description' => 'Clothing and accessories',
                'icon' => 'tshirt'
            ],
            [
                'id' => 'cat_3',
                'name' => 'Home & Kitchen',
                'description' => 'Home appliances and kitchen items',
                'icon' => 'home'
            ],
            [
                'id' => 'cat_4',
                'name' => 'Books',
                'description' => 'Books and educational materials',
                'icon' => 'book'
            ],
            [
                'id' => 'cat_5',
                'name' => 'Sports',
                'description' => 'Sports equipment and accessories',
                'icon' => 'futbol'
            ],
            [
                'id' => 'cat_6',
                'name' => 'Beauty',
                'description' => 'Beauty and personal care products',
                'icon' => 'spa'
            ],
            [
                'id' => 'cat_7',
                'name' => 'Toys',
                'description' => 'Toys and games for kids',
                'icon' => 'gamepad'
            ],
            [
                'id' => 'cat_8',
                'name' => 'Health',
                'description' => 'Health and wellness products',
                'icon' => 'heartbeat'
            ]
        ];
        
        return $categories;
        
        // In a real app, you'd use something like:
        /*
        $query = "SELECT * FROM " . $this->table . " ORDER BY name";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
        */
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
