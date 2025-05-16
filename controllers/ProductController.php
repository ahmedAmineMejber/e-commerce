<?php
require_once '../models/Product.php';
require_once '../models/Category.php';

class ProductController {
    private $productModel;
    private $categoryModel;
    
    public function __construct() {
        $this->productModel = new Product();
        $this->categoryModel = new Category();
    }
    
    public function index() {
        $products = $this->productModel->getAllProducts();
        $categories = $this->categoryModel->getAllCategories();
        
        include '../views/home.php';
    }
    
    public function show($id) {
        $product = $this->productModel->getProductById($id);
        
        if (!$product) {
            // Handle product not found
            include '../views/404.php';
            return;
        }
        
        include '../views/product_detail.php';
    }
    
    public function search() {
        if (isset($_GET['query'])) {
            $query = sanitize($_GET['query']);
            $products = $this->productModel->searchProducts($query);
            
            include '../views/product_list.php';
        } else {
            redirect('');
        }
    }
    
    // Add other product-related controller methods
}
?>