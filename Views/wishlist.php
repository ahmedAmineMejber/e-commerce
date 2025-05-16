
<?php 
include 'header.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page
    header('Location: ' . BASE_URL . 'login?redirect=wishlist');
    exit;
}

// Include the Wishlist model
require_once 'models/Wishlist.php';
$wishlistModel = new Wishlist();

// Get wishlist items
$userId = $_SESSION['user_id'];
$wishlistItems = $wishlistModel->getUserWishlist($userId);
?>

<main class="container py-8">
    <h1 class="text-3xl font-bold mb-8">My Wishlist</h1>
    
    <?php if (empty($wishlistItems)): ?>
        <div class="text-center py-16">
            <div class="inline-block p-6 rounded-full bg-gray-100 mb-6">
                <i class="fas fa-heart text-4xl text-gray-400"></i>
            </div>
            <h2 class="text-2xl font-semibold mb-2">Your wishlist is empty</h2>
            <p class="text-gray-500 mb-6 max-w-md mx-auto">
                Items added to your wishlist will be saved here for you to revisit anytime.
            </p>
            <a href="<?php echo BASE_URL; ?>products" class="btn btn-primary">
                Explore Products
            </a>
        </div>
    <?php else: ?>
        <div class="product-grid">
            <?php foreach ($wishlistItems as $item): ?>
                <div class="product-card">
                    <div class="relative">
                        <a href="<?php echo BASE_URL; ?>product?id=<?php echo $item['product_id']; ?>" class="product-image">
                            <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>">
                        </a>
                        <button 
                            class="btn-danger absolute top-2 right-2"
                            onclick="removeFromWishlist('<?php echo $item['product_id']; ?>')"
                        >
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    
                    <div class="product-content">
                        <div class="product-category"><?php echo $item['category']; ?></div>
                        <h3 class="product-title">
                            <a href="<?php echo BASE_URL; ?>product?id=<?php echo $item['product_id']; ?>">
                                <?php echo $item['name']; ?>
                            </a>
                        </h3>
                        <p class="product-price">$<?php echo number_format($item['price'], 2); ?></p>
                        
                        <div class="flex items-center mt-2 mb-2">
                            <div class="w-2 h-2 rounded-full mr-2 <?php echo $item['stock'] > 0 ? 'bg-green-500' : 'bg-red-500'; ?>"></div>
                            <span class="text-sm <?php echo $item['stock'] > 0 ? 'text-green-600' : 'text-red-600'; ?>">
                                <?php echo $item['stock'] > 0 ? 'In Stock' : 'Out of Stock'; ?>
                            </span>
                        </div>
                        
                        <p class="product-description"><?php echo $item['description']; ?></p>
                    </div>
                    
                    <div class="product-footer">
                        <button 
                            class="btn btn-primary add-to-cart-btn w-full" 
                            data-id="<?php echo $item['product_id']; ?>"
                            <?php echo $item['stock'] === 0 ? 'disabled' : ''; ?>
                        >
                            <i class="fas fa-shopping-cart"></i> Add to Cart
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<script>
    function removeFromWishlist(productId) {
        if (confirm('Are you sure you want to remove this item from your wishlist?')) {
            fetch('<?php echo BASE_URL; ?>controllers/WishlistController.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=remove&product_id=' + productId
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reload the page to reflect changes
                    location.reload();
                } else {
                    showToast('Error: ' + data.message, 'error');
                }
            })
            .catch(error => {
                showToast('Error: Could not remove item from wishlist', 'error');
            });
        }
    }
</script>

<?php include 'footer.php'; ?>
