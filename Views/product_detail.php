
<?php 
include 'includes/header.php';

// Check if product ID is provided
if (!isset($_GET['id'])) {
    header('Location: ' . BASE_URL . 'products');
    exit;
}

// Include models
require_once 'models/Product.php';
require_once 'models/Wishlist.php';

$productModel = new Product();
$wishlistModel = new Wishlist();

// Get product details
$productId = $_GET['id'];
$product = $productModel->read($productId);

// Check if product exists
if (!$product) {
    header('Location: ' . BASE_URL . 'not-found');
    exit;
}

// Check if product is in user's wishlist
$isInWishlist = false;
if (isset($_SESSION['user_id'])) {
    $isInWishlist = $wishlistModel->isInWishlist($_SESSION['user_id'], $productId);
}
?>

<main class="container py-8">
    <div class="mb-4">
        <a href="<?php echo BASE_URL; ?>products" class="text-blue-600 hover:underline inline-flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Back to Products
        </a>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Product Images -->
        <div>
            <div class="bg-white p-4 rounded-lg shadow-sm">
                <div class="aspect-square overflow-hidden rounded-lg">
                    <img 
                        src="<?php echo $product['image']; ?>" 
                        alt="<?php echo $product['name']; ?>"
                        class="w-full h-full object-cover"
                    >
                </div>
            </div>
        </div>
        
        <!-- Product Info -->
        <div>
            <div class="mb-4">
                <span class="text-sm text-gray-500"><?php echo $product['category_name']; ?></span>
                <h1 class="text-2xl lg:text-3xl font-bold"><?php echo $product['name']; ?></h1>
            </div>
            
            <div class="mb-4">
                <div class="flex items-center mb-2">
                    <div class="flex text-yellow-400 mr-2">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <span class="text-gray-600">4.5 (24 reviews)</span>
                </div>
                
                <p class="text-2xl font-bold text-gray-900">
                    $<?php echo number_format($product['price'], 2); ?>
                </p>
            </div>
            
            <div class="mb-6">
                <div class="flex items-center mb-4">
                    <div class="w-2 h-2 rounded-full mr-2 <?php echo $product['stock'] > 0 ? 'bg-green-500' : 'bg-red-500'; ?>"></div>
                    <span class="<?php echo $product['stock'] > 0 ? 'text-green-600' : 'text-red-600'; ?>">
                        <?php 
                        if ($product['stock'] === 0) {
                            echo 'Out of Stock';
                        } elseif ($product['stock'] < 5) {
                            echo 'Low Stock - Only ' . $product['stock'] . ' left';
                        } else {
                            echo 'In Stock';
                        }
                        ?>
                    </span>
                </div>
                
                <p class="text-gray-700">
                    <?php echo $product['description']; ?>
                </p>
            </div>
            
            <?php if (!empty($product['brand']) || !empty($product['color'])): ?>
            <div class="mb-6">
                <h3 class="font-semibold mb-2">Product Details</h3>
                <ul class="space-y-1 text-gray-600">
                    <?php if (!empty($product['brand'])): ?>
                        <li>Brand: <?php echo $product['brand']; ?></li>
                    <?php endif; ?>
                    <?php if (!empty($product['color'])): ?>
                        <li>Color: <?php echo $product['color']; ?></li>
                    <?php endif; ?>
                    <?php if (!empty($product['age_range'])): ?>
                        <li>Age Range: <?php echo $product['age_range']; ?></li>
                    <?php endif; ?>
                </ul>
            </div>
            <?php endif; ?>
            
            <!-- Add to Cart Section -->
            <div class="flex items-start space-x-4">
                <div class="cart-qty mb-4">
                    <button class="cart-qty-btn" data-action="dec">
                        <i class="fas fa-minus"></i>
                    </button>
                    <input 
                        type="number" 
                        id="product-quantity"
                        value="1" 
                        min="1" 
                        max="<?php echo $product['stock']; ?>" 
                        class="cart-qty-value"
                        <?php echo $product['stock'] === 0 ? 'disabled' : ''; ?>
                    >
                    <button class="cart-qty-btn" data-action="inc">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
                
                <button 
                    class="btn btn-primary add-to-cart-btn" 
                    data-id="<?php echo $product['id']; ?>"
                    <?php echo $product['stock'] === 0 ? 'disabled' : ''; ?>
                >
                    <i class="fas fa-shopping-cart mr-2"></i> Add to Cart
                </button>
                
                <button 
                    class="btn btn-outline btn-icon wishlist-btn <?php echo $isInWishlist ? 'active' : ''; ?>" 
                    data-id="<?php echo $product['id']; ?>"
                >
                    <i class="fas fa-heart"></i>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Product Tabs -->
    <div class="mt-12">
        <div class="border-b border-gray-200 mb-4">
            <ul class="flex flex-wrap -mb-px">
                <li class="mr-2">
                    <a href="#description" class="inline-block p-4 border-b-2 border-blue-600 font-medium text-blue-600">
                        Description
                    </a>
                </li>
                <li class="mr-2">
                    <a href="#details" class="inline-block p-4 border-b-2 border-transparent font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        Details
                    </a>
                </li>
                <li class="mr-2">
                    <a href="#reviews" class="inline-block p-4 border-b-2 border-transparent font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        Reviews
                    </a>
                </li>
            </ul>
        </div>
        
        <div id="description" class="bg-white p-6 rounded-lg shadow-sm">
            <h2 class="text-lg font-semibold mb-4">Product Description</h2>
            <p class="text-gray-700">
                <?php echo $product['description']; ?>
            </p>
            
            <!-- Add more detailed description here -->
            <p class="text-gray-700 mt-4">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla quam velit, vulputate eu pharetra nec, mattis ac neque. Duis vulputate commodo lectus, ac blandit elit tincidunt id. Sed rhoncus, tortor sed eleifend tristique, tortor mauris molestie elit, et lacinia ipsum quam nec dui.
            </p>
            
            <div class="mt-6">
                <h3 class="font-semibold mb-2">Features</h3>
                <ul class="list-disc pl-5 space-y-1 text-gray-700">
                    <li>High-quality materials</li>
                    <li>Durable construction</li>
                    <li>Elegant design</li>
                    <li>Easy to use</li>
                    <li>Versatile functionality</li>
                </ul>
            </div>
        </div>
    </div>
    
    <!-- Related Products -->
    <div class="mt-12">
        <h2 class="text-2xl font-bold mb-6">You May Also Like</h2>
        
        <?php
        // Get related products (same category)
        $relatedProducts = $productModel->getAll(1, 4, $product['category_name']);
        ?>
        
        <div class="product-grid">
            <?php foreach ($relatedProducts as $relProduct): ?>
                <?php if ($relProduct['id'] !== $product['id']): ?>
                <div class="product-card">
                    <a href="<?php echo BASE_URL; ?>product?id=<?php echo $relProduct['id']; ?>" class="product-image">
                        <img src="<?php echo $relProduct['image']; ?>" alt="<?php echo $relProduct['name']; ?>">
                    </a>
                    
                    <div class="product-content">
                        <div class="product-category"><?php echo $relProduct['category_name']; ?></div>
                        <h3 class="product-title">
                            <a href="<?php echo BASE_URL; ?>product?id=<?php echo $relProduct['id']; ?>">
                                <?php echo $relProduct['name']; ?>
                            </a>
                        </h3>
                        <p class="product-price">$<?php echo number_format($relProduct['price'], 2); ?></p>
                    </div>
                    
                    <div class="product-footer">
                        <button 
                            class="btn btn-primary add-to-cart-btn" 
                            data-id="<?php echo $relProduct['id']; ?>"
                            <?php echo $relProduct['stock'] === 0 ? 'disabled' : ''; ?>
                        >
                            <i class="fas fa-shopping-cart"></i> Add to Cart
                        </button>
                        <button 
                            class="btn btn-outline btn-icon wishlist-btn" 
                            data-id="<?php echo $relProduct['id']; ?>"
                        >
                            <i class="fas fa-heart"></i>
                        </button>
                    </div>
                </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</main>

<script>
    // Product quantity buttons
    document.addEventListener('DOMContentLoaded', function() {
        const qtyBtns = document.querySelectorAll('.cart-qty-btn');
        const qtyInput = document.getElementById('product-quantity');
        
        if (qtyInput) {
            qtyBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const currentValue = parseInt(qtyInput.value);
                    
                    if (this.dataset.action === 'inc') {
                        const max = parseInt(qtyInput.getAttribute('max'));
                        if (currentValue < max) {
                            qtyInput.value = currentValue + 1;
                        }
                    } else if (this.dataset.action === 'dec' && currentValue > 1) {
                        qtyInput.value = currentValue - 1;
                    }
                });
            });
        }
        
        // Add to cart with quantity
        const addToCartBtn = document.querySelector('.add-to-cart-btn');
        if (addToCartBtn) {
            addToCartBtn.addEventListener('click', function() {
                const productId = this.dataset.id;
                const quantity = qtyInput ? qtyInput.value : 1;
                
                fetch('<?php echo BASE_URL; ?>controllers/CartController.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'action=add&product_id=' + productId + '&quantity=' + quantity
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('Product added to cart!');
                        // Update cart count
                        const cartCount = document.querySelector('.cart-count');
                        if (cartCount) {
                            cartCount.textContent = data.count;
                        }
                    } else {
                        showToast('Error: ' + data.message, 'error');
                    }
                })
                .catch(error => {
                    showToast('Error: Could not add product to cart', 'error');
                });
            });
        }
    });
</script>

<?php include 'includes/footer.php'; ?>
