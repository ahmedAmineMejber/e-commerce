<?php 
require_once 'includes/header.php';

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

<style>
    /* Add to your existing .product-grid styles */
.product-grid .showcase-banner {
    height: 250px; /* Fixed height for image container */
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


    <!--
      - PRODUCT
    -->

    <div class="product-container">

        <div class="container">


            <!--
          - SIDEBAR
        -->

            <div class="sidebar  has-scrollbar" data-mobile-menu>

                <?php require_once 'models/Category.php';
        $categoryModel = new Category();
        $category = isset($_GET['category']) ? $_GET['category'] : null;
        $categories = $categoryModel->getAllCategories();
        ?>

                <div class="sidebar-category">

                    <div class="sidebar-top">
                        <h2 class="sidebar-title">Category</h2>

                        <button class="sidebar-close-btn" data-mobile-menu-close-btn>
                            <ion-icon name="close-outline"></ion-icon>
                        </button>
                    </div>

                    <ul class="sidebar-menu-category-list">
                        <?php foreach ($categories as $cat): ?>
                        <li class="sidebar-menu-category">

                            <a class="sidebar-accordion-menu" data-accordion-btn
                                href="<?php echo BASE_URL; ?>products?category=<?php echo urlencode($cat['name']); ?>">

                                <div class="menu-title-flex">
                                    <img src="./assets/images/icons/<?php echo $cat['icon']; ?>" alt="clothes"
                                        width="20" height="20" class="menu-title-img">

                                    <p class="menu-title"> <?php echo $cat['name']; ?> </p>
                                </div>

                            </a>


                        </li>
                        <?php endforeach;?>



                    </ul>

                </div>



            </div>



            <div class="product-box">

                <!--
            - PRODUCT MINIMAL
          -->

                <div class="product-minimal">






                </div>


                <!--
            - PRODUCT GRID
          -->

                <div class="product-main">
                    <span class="text-gray-500 mr-2"><?php echo $totalProducts; ?> products</span>

                    <h1 class="title">
                        <?php if ($category): ?>
                        <?php echo $category . ""; ?>
                        <?php elseif ($search): ?>
                        Search Results for "<?php echo $search; ?>"
                        <?php else: ?>
                        All Products
                        <?php endif; ?>
                    </h1>


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
        <div class="showcase">
            <div class="showcase-banner">
                <img src="<?php echo BASE_URL; ?>assets/images/products/<?php echo htmlspecialchars($product['image']); ?>"
                    alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-img default"
                    width="300">
                <img src="<?php echo BASE_URL; ?>assets/images/products/<?php echo htmlspecialchars($product['image']); ?>"
                    alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-img hover"
                    width="300">
                <p class="showcase-badge angle black">sale</p>
                <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-<?php echo $_SESSION['message']['type']; ?>">
                    <?php echo $_SESSION['message']['text']; ?>
                </div>
                <?php unset($_SESSION['message']); ?>
                <?php endif; ?>
                <div class="showcase-actions">
                    <form action="<?php echo BASE_URL; ?>wishlist_r.php" method="POST" style="display:inline;">
                        <input type="hidden" name="action" value="add">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <button type="submit" class="btn-action">
                            <ion-icon name="heart-outline"></ion-icon>
                        </button>
                    </form>
                    <a href="<?php echo BASE_URL; ?>product?id=<?php echo $product['id']; ?>" class="btn-action">
                        <ion-icon name="eye-outline"></ion-icon>
                    </a>
                    <form action="<?php echo BASE_URL; ?>cart_r.php" method="POST" style="display:inline;">
                        <input type="hidden" name="action" value="add">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="btn-action">
                            <ion-icon name="bag-add-outline"></ion-icon>
                        </button>
                    </form>
                </div>
            </div>
            <div class="showcase-content">
                <a href="<?php echo BASE_URL; ?>product?id=<?php echo $product['id']; ?>" class="showcase-category">
                    <?php echo $product['name']; ?>
                </a>
                <h3>
    <a href="#" class="showcase-title">
        <?php echo strlen($product['description']) > 50 ? substr($product['description'], 0, 50).'...' : $product['description']; ?>
    </a>
</h3>
                <div class="price-box">
                    <p class="price">$<?php echo number_format($product['price'], 2); ?></p>
                    <del>$<?php echo number_format($product['original_price'], 2); ?></del>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        <?php endif ?>
    </div>

            </div>

        </div>

    </div>


    <div>


    </div>
</main>

<?php include 'includes/footer.php'; ?>