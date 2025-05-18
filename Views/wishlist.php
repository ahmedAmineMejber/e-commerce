<?php 
include 'views/includes/header.php';

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
$wishlistCount = $wishlistModel->getCountByUser($userId);
?>
<style>
    /* Add to your existing .product-grid styles */
.product-grid .showcase-banner {
    height: 280px; /* Fixed height for image container */
    overflow: hidden; /* Ensure images don't overflow */
    position: relative;
}

.product-grid .product-img {
    width: 100%;
    height: 100%;
    object-fit: cover; /* Ensures images fill container without distortion */
    transition: var(--transition-timing);
}

/* Optional: Set a minimum height for the content area */
.product-grid .showcase-content {
    min-height: 120px; /* Adjust as needed */
    padding: 15px 20px;
    display: flex;
    flex-direction: column;
}
</style>

<main>
    <div class="product-container">

        <div class="container">

            <div class="product-main">
                <span class="text-gray-500 mr-2"><?php echo $wishlistCount; ?> products</span>

                <h1 class="title">
                    My Wishlist
                </h1>
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
        <div class="showcase">
            <div class="showcase-banner">
                <!-- Product Images -->
                <img src="<?php echo BASE_URL; ?>assets/images/products/<?php echo htmlspecialchars($item['image']); ?>"
                    alt="<?php echo htmlspecialchars($item['name']); ?>" class="product-img default"
                    width="300">
                <img src="<?php echo BASE_URL; ?>assets/images/products/<?php echo htmlspecialchars($item['image']); ?>"
                    alt="<?php echo htmlspecialchars($item['name']); ?>" class="product-img hover"
                    width="300">

                <!-- Stock Badge -->
                <?php if ($item['stock'] < 5 && $item['stock'] > 0): ?>
                <p class="showcase-badge angle red">Low Stock</p>
                <?php elseif ($item['stock'] === 0): ?>
                <p class="showcase-badge angle black">Out of Stock</p>
                <?php elseif ($item['stock'] > 5): ?>
                <p class="showcase-badge angle black">Sale</p>
                <?php endif; ?>

                <!-- Showcase Actions -->
                <div class="showcase-actions">
                    <!-- Remove from Wishlist -->
                    <form action="<?php echo BASE_URL; ?>wishlist_r.php" method="POST" style="display:inline;">
                        <input type="hidden" name="action" value="remove">
                        <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                        <button type="submit" class="btn-action">
                            <ion-icon name="trash-outline"></ion-icon>
                        </button>
                    </form>

                    <!-- View Product Details -->
                    <a href="<?php echo BASE_URL; ?>product?id=<?php echo $item['product_id']; ?>" class="btn-action">
                        <ion-icon name="eye-outline"></ion-icon>
                    </a>

                    <!-- Add to Cart -->
                    <form action="<?php echo BASE_URL; ?>cart_r.php" method="POST" style="display:inline;">
                        <input type="hidden" name="action" value="add">
                        <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="btn-action" <?php echo $item['stock'] === 0 ? 'disabled' : ''; ?>>
                            <ion-icon name="bag-add-outline"></ion-icon>
                        </button>
                    </form>
                </div>
            </div>

            <div class="showcase-content">
                <!-- Product Name -->
                <a href="<?php echo BASE_URL; ?>product?id=<?php echo $item['product_id']; ?>" class="showcase-category">
                    <?php echo htmlspecialchars($item['name']); ?>
                </a>

                <!-- Product Description -->
                <h3>
                    <a href="#" class="showcase-title"><?php echo htmlspecialchars($item['description']); ?></a>
                </h3>

                <!-- Price Box -->
                <div class="price-box">
                    <p class="price">$<?php echo number_format($item['price'], 2); ?></p>
                    <?php if (isset($item['original_price']) && $item['original_price'] > $item['price']): ?>
                    <del>$<?php echo number_format($item['original_price'], 2); ?></del>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
        </div>
    </div>
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

<?php include 'views/includes/footer.php'; ?>