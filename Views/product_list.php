
<?php 
include 'includes/header.php';

// Include the Product model
require_once 'models/Product.php';
require_once 'models/Category.php';

$productModel = new Product();
$categoryModel = new Category();

// Get query parameters
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$category = isset($_GET['category']) ? $_GET['category'] : null;
$search = isset($_GET['search']) ? $_GET['search'] : null;

// Get products
$products = $productModel->getAll($page, 12, $category, $search);
$totalProducts = $productModel->getCount($category, $search);
$totalPages = ceil($totalProducts / 12);

// Get all categories for filter
$categories = $categoryModel->getAllCategories();
?>

<main class="container py-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold">
            <?php if ($category): ?>
                <?php echo $category; ?>
            <?php elseif ($search): ?>
                Search Results for "<?php echo $search; ?>"
            <?php else: ?>
                All Products
            <?php endif; ?>
        </h1>
        
        <div class="flex items-center">
            <span class="text-gray-500 mr-2"><?php echo $totalProducts; ?> products</span>
            <select class="form-input w-40" id="sort-by">
                <option value="newest">Newest</option>
                <option value="price-low">Price: Low to High</option>
                <option value="price-high">Price: High to Low</option>
                <option value="popular">Most Popular</option>
            </select>
        </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        <!-- Filters -->
        <div class="md:col-span-1">
            <div class="bg-white p-4 rounded-lg shadow-sm mb-6">
                <h3 class="font-semibold mb-4">Categories</h3>
                <ul class="space-y-2">
                    <li>
                        <a href="<?php echo BASE_URL; ?>products" class="<?php echo !$category ? 'font-medium text-blue-600' : 'text-gray-600'; ?>">
                            All Categories
                        </a>
                    </li>
                    <?php foreach ($categories as $cat): ?>
                        <li>
                            <a href="<?php echo BASE_URL; ?>products?category=<?php echo urlencode($cat['name']); ?>" 
                               class="<?php echo $category === $cat['name'] ? 'font-medium text-blue-600' : 'text-gray-600'; ?>">
                                <?php echo $cat['name']; ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            
            <div class="bg-white p-4 rounded-lg shadow-sm mb-6">
                <h3 class="font-semibold mb-4">Price Range</h3>
                <div class="space-y-2">
                    <div>
                        <label for="min-price" class="block text-sm text-gray-600">Min Price</label>
                        <input type="number" id="min-price" class="form-input" placeholder="$0">
                    </div>
                    <div>
                        <label for="max-price" class="block text-sm text-gray-600">Max Price</label>
                        <input type="number" id="max-price" class="form-input" placeholder="$1000">
                    </div>
                    <button class="btn btn-primary w-full mt-2">Apply</button>
                </div>
            </div>
        </div>
        
        <!-- Product Grid -->
        <div class="md:col-span-3">
            <?php if (empty($products)): ?>
                <div class="bg-white p-8 rounded-lg shadow-sm text-center">
                    <div class="text-gray-400 mb-4">
                        <i class="fas fa-search fa-3x"></i>
                    </div>
                    <h2 class="text-xl font-semibold mb-2">No products found</h2>
                    <p class="text-gray-600 mb-4">
                        We couldn't find any products matching your criteria.
                    </p>
                    <a href="<?php echo BASE_URL; ?>products" class="btn btn-primary">
                        View All Products
                    </a>
                </div>
            <?php else: ?>
                <div class="product-grid">
                    <?php foreach ($products as $product): ?>
                        <div class="product-card">
                            <a href="<?php echo BASE_URL; ?>product?id=<?php echo $product['id']; ?>" class="product-image">
                                <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
                                <?php if ($product['stock'] < 5 && $product['stock'] > 0): ?>
                                    <span class="badge badge-orange absolute top-2 left-2">Low Stock</span>
                                <?php elseif ($product['stock'] === 0): ?>
                                    <span class="badge badge-red absolute top-2 left-2">Out of Stock</span>
                                <?php endif; ?>
                            </a>
                            
                            <div class="product-content">
                                <div class="product-category"><?php echo $product['category_name']; ?></div>
                                <h3 class="product-title">
                                    <a href="<?php echo BASE_URL; ?>product?id=<?php echo $product['id']; ?>">
                                        <?php echo $product['name']; ?>
                                    </a>
                                </h3>
                                <p class="product-price">$<?php echo number_format($product['price'], 2); ?></p>
                                <p class="product-description"><?php echo $product['description']; ?></p>
                            </div>
                            
                            <div class="product-footer">
                                <button 
                                    class="btn btn-primary add-to-cart-btn" 
                                    data-id="<?php echo $product['id']; ?>"
                                    <?php echo $product['stock'] === 0 ? 'disabled' : ''; ?>
                                >
                                    <i class="fas fa-shopping-cart"></i> Add to Cart
                                </button>
                                <button 
                                    class="btn btn-outline btn-icon wishlist-btn <?php echo isset($_SESSION['user_id']) && $wishlistModel->isInWishlist($_SESSION['user_id'], $product['id']) ? 'active' : ''; ?>" 
                                    data-id="<?php echo $product['id']; ?>"
                                >
                                    <i class="fas fa-heart"></i>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                    <div class="flex justify-center mt-8">
                        <div class="inline-flex">
                            <?php if ($page > 1): ?>
                                <a href="<?php echo BASE_URL; ?>products?page=<?php echo $page - 1; ?><?php echo $category ? '&category=' . urlencode($category) : ''; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>" class="btn btn-outline">
                                    <i class="fas fa-chevron-left mr-2"></i> Previous
                                </a>
                            <?php else: ?>
                                <button class="btn btn-outline" disabled>
                                    <i class="fas fa-chevron-left mr-2"></i> Previous
                                </button>
                            <?php endif; ?>
                            
                            <div class="flex mx-2">
                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <?php if ($i === $page): ?>
                                        <span class="btn btn-primary mx-1"><?php echo $i; ?></span>
                                    <?php elseif ($i <= 3 || $i >= $totalPages - 2 || abs($i - $page) <= 1): ?>
                                        <a href="<?php echo BASE_URL; ?>products?page=<?php echo $i; ?><?php echo $category ? '&category=' . urlencode($category) : ''; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>" class="btn btn-outline mx-1"><?php echo $i; ?></a>
                                    <?php elseif ($i === 4 && $page > 5): ?>
                                        <span class="btn btn-outline mx-1 disabled">...</span>
                                    <?php elseif ($i === $totalPages - 3 && $page < $totalPages - 4): ?>
                                        <span class="btn btn-outline mx-1 disabled">...</span>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </div>
                            
                            <?php if ($page < $totalPages): ?>
                                <a href="<?php echo BASE_URL; ?>products?page=<?php echo $page + 1; ?><?php echo $category ? '&category=' . urlencode($category) : ''; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>" class="btn btn-outline">
                                    Next <i class="fas fa-chevron-right ml-2"></i>
                                </a>
                            <?php else: ?>
                                <button class="btn btn-outline" disabled>
                                    Next <i class="fas fa-chevron-right ml-2"></i>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
