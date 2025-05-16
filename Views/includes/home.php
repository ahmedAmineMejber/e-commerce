
<?php include 'header.php'; ?>

<!-- Hero Section -->
<section class="hero">
    <!-- Background pattern -->
    <div class="hero-pattern">
        <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
            <path d="M0,0 L100,0 L100,100 L0,100 Z" fill="url(#grid)" />
        </svg>
        <svg width="0" height="0">
            <defs>
                <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
                    <path d="M 10 0 L 0 0 0 10" fill="none" stroke="white" stroke-width="0.5" />
                </pattern>
            </defs>
        </svg>
    </div>

    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title">
                Shop the Latest Trends
            </h1>
            <p class="hero-text">
                Discover our curated collection of premium products at unbeatable prices.
                Free shipping on orders over $50!
            </p>
            <div class="hero-buttons">
                <a href="<?php echo BASE_URL; ?>products" class="btn btn-primary btn-lg">
                    Shop Now
                </a>
                <a href="<?php echo BASE_URL; ?>categories" class="btn btn-outline btn-lg">
                    Browse Categories
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products Section -->
<section class="container py-12">
    <h2 class="text-center mb-8">Featured Products</h2>
    
    <?php
    // Include the Product model and fetch featured products
    require_once 'models/Product.php';
    $productModel = new Product();
    $featuredProducts = $productModel->getFeaturedProducts(4);
    ?>
    
    <div class="product-grid">
        <?php foreach ($featuredProducts as $product): ?>
        <div class="product-card">
            <a href="<?php echo BASE_URL; ?>product?id=<?php echo $product['id']; ?>" class="product-image">
                <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
            </a>
            <div class="product-content">
                <div class="product-category"><?php echo $product['category']; ?></div>
                <h3 class="product-title">
                    <a href="<?php echo BASE_URL; ?>product?id=<?php echo $product['id']; ?>">
                        <?php echo $product['name']; ?>
                    </a>
                </h3>
                <p class="product-price">$<?php echo number_format($product['price'], 2); ?></p>
            </div>
            <div class="product-footer">
                <button class="btn btn-primary add-to-cart-btn" data-id="<?php echo $product['id']; ?>">
                    <i class="fas fa-shopping-cart"></i> Add to Cart
                </button>
                <button class="btn btn-outline btn-icon wishlist-btn" data-id="<?php echo $product['id']; ?>">
                    <i class="fas fa-heart"></i>
                </button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    
    <div class="text-center mt-8">
        <a href="<?php echo BASE_URL; ?>products" class="btn btn-outline">View All Products</a>
    </div>
</section>

<!-- Categories Section -->
<section class="bg-gray-100 py-12">
    <div class="container">
        <h2 class="text-center mb-8">Shop by Category</h2>
        
        <?php
        // Include the Category model and fetch categories
        require_once 'models/Category.php';
        $categoryModel = new Category();
        $categories = $categoryModel->getAllCategories();
        ?>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <?php foreach ($categories as $category): ?>
            <a href="<?php echo BASE_URL; ?>products?category=<?php echo urlencode($category['name']); ?>" class="category-card">
                <div class="bg-white rounded-lg shadow p-4 text-center hover:shadow-md transition-shadow">
                    <div class="w-16 h-16 mx-auto mb-4 flex items-center justify-center bg-blue-100 rounded-full">
                        <i class="fas fa-<?php echo $category['icon']; ?> text-blue-600 text-xl"></i>
                    </div>
                    <h3 class="font-medium"><?php echo $category['name']; ?></h3>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="container py-12">
    <h2 class="text-center mb-8">What Our Customers Say</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 shadow rounded-lg">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-gray-200 rounded-full overflow-hidden mr-4">
                    <img src="<?php echo BASE_URL; ?>assets/images/testimonial-1.jpg" alt="Customer" class="w-full h-full object-cover">
                </div>
                <div>
                    <h3 class="font-medium">Sarah Johnson</h3>
                    <div class="text-yellow-400">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
            </div>
            <p>"The product quality exceeded my expectations. Fast shipping and excellent customer service!"</p>
        </div>
        
        <div class="bg-white p-6 shadow rounded-lg">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-gray-200 rounded-full overflow-hidden mr-4">
                    <img src="<?php echo BASE_URL; ?>assets/images/testimonial-2.jpg" alt="Customer" class="w-full h-full object-cover">
                </div>
                <div>
                    <h3 class="font-medium">Michael Brown</h3>
                    <div class="text-yellow-400">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                </div>
            </div>
            <p>"Great selection of products at competitive prices. Will definitely shop here again!"</p>
        </div>
        
        <div class="bg-white p-6 shadow rounded-lg">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-gray-200 rounded-full overflow-hidden mr-4">
                    <img src="<?php echo BASE_URL; ?>assets/images/testimonial-3.jpg" alt="Customer" class="w-full h-full object-cover">
                </div>
                <div>
                    <h3 class="font-medium">Emily Davis</h3>
                    <div class="text-yellow-400">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
            </div>
            <p>"The checkout process was seamless and my order arrived earlier than expected. Very satisfied!"</p>
        </div>
    </div>
</section>
<form action="" method="post">
    <input type="submit" name="logout" value="logout">
</form>


<?php 
include 'footer.php';
if(isset($_POST['logout'])){
    session_destroy();
} ?>
