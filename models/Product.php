
<?php
require_once 'config/database.php';

class Product {
    private $conn;
    private $table = 'products';
    
    // Product properties
    public $id;
    public $admin_id;
    public $category_id;
    public $name;
    public $price;
    public $brand;
    public $color;
    public $age_range;
    public $image;
    public $stock;
    public $description;
    public $status;
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
    // Create product
    public function create($productData) {
        $query = "INSERT INTO " . $this->table . " 
            (id, admin_id, category_id, name, price, brand, color, age_range, image, stock, description, status) 
            VALUES 
            (:id, :admin_id, :category_id, :name, :price, :brand, :color, :age_range, :image, :stock, :description, :status)";
        
        $stmt = $this->conn->prepare($query);
        
        // Clean data
        $this->id = htmlspecialchars(strip_tags($productData['id']));
        $this->admin_id = htmlspecialchars(strip_tags($productData['admin_id']));
        $this->category_id = htmlspecialchars(strip_tags($productData['category_id']));
        $this->name = htmlspecialchars(strip_tags($productData['name']));
        $this->price = htmlspecialchars(strip_tags($productData['price']));
        $this->brand = htmlspecialchars(strip_tags($productData['brand']));
        $this->color = htmlspecialchars(strip_tags($productData['color']));
        $this->age_range = htmlspecialchars(strip_tags($productData['age_range']));
        $this->image = htmlspecialchars(strip_tags($productData['image']));
        $this->stock = htmlspecialchars(strip_tags($productData['stock']));
        $this->description = htmlspecialchars(strip_tags($productData['description']));
        $this->status = htmlspecialchars(strip_tags($productData['status']));
        
        // Bind data
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':admin_id', $this->admin_id);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':brand', $this->brand);
        $stmt->bindParam(':color', $this->color);
        $stmt->bindParam(':age_range', $this->age_range);
        $stmt->bindParam(':image', $this->image);
        $stmt->bindParam(':stock', $this->stock);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':status', $this->status);
        
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
    
    // Read single product
    public function read($id) {
        $query = "SELECT p.*, c.name as category_name FROM " . $this->table . " p
                  LEFT JOIN categories c ON p.category_id = c.id
                  WHERE p.id = :id AND p.status = 'active'";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            return $row;
        }
        
        return false;
    }
    
    // Get all products
    public function getAll($page = 1, $perPage = 12, $category = null, $search = null) {
        $offset = ($page - 1) * $perPage;
        
        $query = "SELECT p.*, c.name as category_name FROM " . $this->table . " p
                  LEFT JOIN categories c ON p.category_id = c.id
                  WHERE p.status = 'active'";
        
        // Add category filter if provided
        if ($category) {
            $query .= " AND c.name = :category";
        }
        
        // Add search filter if provided
        if ($search) {
            $query .= " AND (p.name LIKE :search OR p.description LIKE :search)";
        }
        
        $query .= " ORDER BY p.id DESC LIMIT :limit OFFSET :offset";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        
        // Bind category if provided
        if ($category) {
            $stmt->bindParam(':category', $category);
        }
        
        // Bind search if provided
        if ($search) {
            $searchTerm = "%$search%";
            $stmt->bindParam(':search', $searchTerm);
        }
        
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Get total product count (for pagination)
    public function getCount($category = null, $search = null) {
        $query = "SELECT COUNT(*) as total FROM " . $this->table . " p
                  LEFT JOIN categories c ON p.category_id = c.id
                  WHERE p.status = 'active'";
        
        // Add category filter if provided
        if ($category) {
            $query .= " AND c.name = :category";
        }
        
        // Add search filter if provided
        if ($search) {
            $query .= " AND (p.name LIKE :search OR p.description LIKE :search)";
        }
        
        $stmt = $this->conn->prepare($query);
        
        // Bind category if provided
        if ($category) {
            $stmt->bindParam(':category', $category);
        }
        
        // Bind search if provided
        if ($search) {
            $searchTerm = "%$search%";
            $stmt->bindParam(':search', $searchTerm);
        }
        
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $row['total'];
    }
    
    // Get featured products
    public function getFeaturedProducts($limit = 4) {

        $query = "SELECT p.*, c.name as category FROM " . $this->table . " p
                  LEFT JOIN categories c ON p.category_id = c.id
                  WHERE p.status = 'active'
                  ORDER BY p.id DESC LIMIT :limit";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Update product
    public function update($productData) {
        $query = "UPDATE " . $this->table . " SET 
            category_id = :category_id, 
            name = :name, 
            price = :price, 
            brand = :brand, 
            color = :color, 
            age_range = :age_range, 
            image = :image, 
            stock = :stock, 
            description = :description, 
            status = :status 
            WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        // Clean data
        $this->id = htmlspecialchars(strip_tags($productData['id']));
        $this->category_id = htmlspecialchars(strip_tags($productData['category_id']));
        $this->name = htmlspecialchars(strip_tags($productData['name']));
        $this->price = htmlspecialchars(strip_tags($productData['price']));
        $this->brand = htmlspecialchars(strip_tags($productData['brand']));
        $this->color = htmlspecialchars(strip_tags($productData['color']));
        $this->age_range = htmlspecialchars(strip_tags($productData['age_range']));
        $this->image = htmlspecialchars(strip_tags($productData['image']));
        $this->stock = htmlspecialchars(strip_tags($productData['stock']));
        $this->description = htmlspecialchars(strip_tags($productData['description']));
        $this->status = htmlspecialchars(strip_tags($productData['status']));
        
        // Bind data
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':brand', $this->brand);
        $stmt->bindParam(':color', $this->color);
        $stmt->bindParam(':age_range', $this->age_range);
        $stmt->bindParam(':image', $this->image);
        $stmt->bindParam(':stock', $this->stock);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':status', $this->status);
        
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
    
    // Delete product (set status to inactive)
    public function delete($id) {
        $query = "UPDATE " . $this->table . " SET status = 'inactive' WHERE id = :id";
        
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
    
    // Permanently delete product
    public function permanentDelete($id) {
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

    public function getDealOfTheDay($limit = 3) {
        $stmt = $this->conn->prepare("
            SELECT * FROM products 
            WHERE is_featured = 1 
            ORDER BY RAND() 
            LIMIT :limit
        ");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
