
<?php 
include 'header.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page
    header('Location: ' . BASE_URL . 'login?redirect=cart');
    exit;
}

// Include the Cart model
require_once 'models/Cart.php';
$cartModel = new Cart();

// Get cart items
$userId = $_SESSION['user_id'];
$cartItems = $cartModel->getUserCart($userId);
$cartTotal = $cartModel->getCartTotal($userId);
?>

<main class="container py-8">
    <h1 class="text-3xl font-bold mb-8">Your Shopping Cart</h1>
    
    <?php if (empty($cartItems)): ?>
        <div class="text-center py-16">
            <div class="inline-block p-6 rounded-full bg-gray-100 mb-6">
                <i class="fas fa-shopping-cart text-4xl text-gray-400"></i>
            </div>
            <h2 class="text-2xl font-semibold mb-2">Your cart is empty</h2>
            <p class="text-gray-500 mb-6 max-w-md mx-auto">
                Looks like you haven't added any products to your cart yet.
            </p>
            <a href="<?php echo BASE_URL; ?>products" class="btn btn-primary">
                Start Shopping
            </a>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="md:col-span-2">
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cartItems as $item): ?>
                            <tr>
                                <td>
                                    <div class="cart-product">
                                        <img 
                                            src="<?php echo $item['image']; ?>" 
                                            alt="<?php echo $item['name']; ?>"
                                            class="cart-product-image"
                                        >
                                        <div>
                                            <h3><?php echo $item['name']; ?></h3>
                                            <?php if ($item['stock'] < 5): ?>
                                                <p class="text-sm text-orange-600">Only <?php echo $item['stock']; ?> left in stock</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td>$<?php echo number_format($item['price'], 2); ?></td>
                                <td>
                                    <div class="cart-qty">
                                        <button 
                                            class="cart-qty-btn" 
                                            data-action="dec" 
                                            data-id="<?php echo $item['id']; ?>"
                                        >
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <input 
                                            type="number" 
                                            value="<?php echo $item['qty']; ?>" 
                                            min="1" 
                                            max="<?php echo $item['stock']; ?>" 
                                            class="cart-qty-value"
                                            readOnly
                                        >
                                        <button 
                                            class="cart-qty-btn" 
                                            data-action="inc" 
                                            data-id="<?php echo $item['id']; ?>"
                                        >
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </td>
                                <td class="item-subtotal-<?php echo $item['id']; ?>">
                                    $<?php echo number_format($item['price'] * $item['qty'], 2); ?>
                                </td>
                                <td>
                                    <button 
                                        class="text-red-500 hover:text-red-700"
                                        onclick="removeCartItem('<?php echo $item['id']; ?>')"
                                    >
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <div>
                <div class="cart-summary">
                    <h2 class="text-xl font-semibold mb-4">Order Summary</h2>
                    
                    <div class="space-y-2 mb-4">
                        <div class="flex justify-between">
                            <span>Subtotal</span>
                            <span class="cart-total">$<?php echo number_format($cartTotal, 2); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span>Shipping</span>
                            <span class="text-green-600">Free</span>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-200 pt-4 mb-4">
                        <div class="flex justify-between font-bold">
                            <span>Total</span>
                            <span class="cart-total">$<?php echo number_format($cartTotal, 2); ?></span>
                        </div>
                    </div>
                    
                    <a href="<?php echo BASE_URL; ?>checkout" class="btn btn-primary w-full">
                        Proceed to Checkout
                    </a>
                    
                    <div class="mt-4">
                        <a href="<?php echo BASE_URL; ?>products" class="text-blue-600 hover:underline inline-flex items-center">
                            <i class="fas fa-arrow-left mr-2"></i> Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</main>

<script>
    function removeCartItem(itemId) {
        if (confirm('Are you sure you want to remove this item from your cart?')) {
            fetch('<?php echo BASE_URL; ?>controllers/CartController.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=remove&item_id=' + itemId
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
                showToast('Error: Could not remove item from cart', 'error');
            });
        }
    }
</script>

<?php include 'footer.php'; ?>
