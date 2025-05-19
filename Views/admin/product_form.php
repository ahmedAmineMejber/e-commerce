<?php
// Check admin access
require_once 'controllers/AdminController.php';
$adminController = new AdminController();
$adminController->checkAdminAccess();

// Get all categories
$categories = $adminController->getAllCategories();

// Initialize variables
$product = null;
$isEdit = false;
$formError = '';
$formSuccess = '';

// Check if this is an edit action
if (isset($_GET['id'])) {
    // Get product details
    require_once 'models/Product.php';
    $productModel = new Product();
    $product = $productModel->read($_GET['id']);
    
    if (!$product) {
        header("Location: " . BASE_URL . "admin/manage-products");
        exit;
    }
    
    $isEdit = true;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productData = [
        'name' => $_POST['name'],
        'category_id' => $_POST['category_id'],
        'price' => $_POST['price'],
        'original_price' => $_POST['original_price'],
        'brand' => $_POST['brand'],
        'color' => $_POST['color'],
        'age_range' => $_POST['age_range'],
        'rating' => $_POST['rating'],
        'stock' => $_POST['stock'],
        'description' => $_POST['description'],
        'status' => $_POST['status']
    ];
    
    // For image upload
    $imageUploaded = false;
    $imageHoverUploaded = false;
    
  // Check if main image file is uploaded
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $targetDir = __DIR__ . "/../../assets/images/products/";
    
    // Create directory if it doesn't exist
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    
    $fileName = basename($_FILES['image']['name']);
    $targetPath = $targetDir . $fileName;
    
    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
        // Save only the relative path for DB consistency
        $productData['image'] = $fileName;
        $imageUploaded = true;
    }
}

// Check if hover image file is uploaded
if (isset($_FILES['image_hover']) && $_FILES['image_hover']['error'] === UPLOAD_ERR_OK) {
    $targetDir = __DIR__ . "/../../assets/images/products/";
    
    // Create directory if it doesn't exist
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    
    $fileName = basename($_FILES['image_hover']['name']);
    $targetPath = $targetDir . $fileName;
    
    if (move_uploaded_file($_FILES['image_hover']['tmp_name'], $targetPath)) {
        // Save only the relative path for DB consistency
        $productData['image_hover'] = $fileName;
        $imageHoverUploaded = true;
    }
}

// If editing and no new image uploaded, keep the old one
if ($isEdit) {
    if (!$imageUploaded) {
        $productData['image'] = $product['image'];
    }
    if (!$imageHoverUploaded) {
        $productData['image_hover'] = $product['image_hover'] ?? '';
    }
    
    $productData['id'] = $product['id'];
    $result = $adminController->updateProduct($productData);
} else {
    // If adding and no image uploaded, use a placeholder
    if (!$imageUploaded) {
        $productData['image'] = 'placeholder.jpg';
    }
    if (!$imageHoverUploaded) {
        $productData['image_hover'] = 'placeholder.jpg';
    }
    
    $result = $adminController->addProduct($productData);
}

if ($result['success']) {
    $formSuccess = $result['message'];
    
    if (!$isEdit) {
        // Reset form for new product
        $product = null;
    } else {
        // Refresh product data
        $product = $productModel->read($product['id']);
    }
} else {
    $formError = $result['message'];
}

}

// Page title
$pageTitle = $isEdit ? "Edit Product" : "Add New Product";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?> - </title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="bg-gray-800 text-white w-64 flex-shrink-0">
            <div class="p-4">
                <h1 class="text-2xl font-bold"></h1>
                <p class="text-sm text-gray-400">Admin Panel</p>
            </div>
            <nav class="p-2">
                <ul>
                    <li class="mb-1">
                        <a href="<?= BASE_URL ?>admin" class="flex items-center p-3 hover:bg-gray-700 rounded">
                            <i class="fas fa-tachometer-alt w-6"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="mb-1">
                        <a href="<?= BASE_URL ?>admin/manage-products" class="flex items-center p-3 bg-gray-900 text-white rounded">
                            <i class="fas fa-box w-6"></i>
                            <span>Products</span>
                        </a>
                    </li>
                    <li class="mb-1">
                        <a href="<?= BASE_URL ?>admin/manage-categories" class="flex items-center p-3 hover:bg-gray-700 rounded">
                            <i class="fas fa-tags w-6"></i>
                            <span>Categories</span>
                        </a>
                    </li>
                    <li class="mb-1">
                        <a href="<?= BASE_URL ?>admin/manage-users" class="flex items-center p-3 hover:bg-gray-700 rounded">
                            <i class="fas fa-users w-6"></i>
                            <span>Users</span>
                        </a>
                    </li>
                    <li class="mb-1">
                        <a href="<?= BASE_URL ?>admin/orders" class="flex items-center p-3 hover:bg-gray-700 rounded">
                            <i class="fas fa-shopping-cart w-6"></i>
                            <span>Orders</span>
                        </a>
                    </li>
                </ul>
                <hr class="my-4 border-gray-600">
                <ul>
                    <li class="mb-1">
                        <a href="<?= BASE_URL ?>" class="flex items-center p-3 hover:bg-gray-700 rounded">
                            <i class="fas fa-store w-6"></i>
                            <span>Visit Store</span>
                        </a>
                    </li>
                    <li class="mb-1">
                        <a href="<?= BASE_URL ?>logout" class="flex items-center p-3 hover:bg-gray-700 rounded">
                            <i class="fas fa-sign-out-alt w-6"></i>
                            <span>Logout</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>
        
        <!-- Main content -->
        <div class="flex-1 overflow-y-auto">
            <header class="bg-white shadow">
                <div class="flex justify-between items-center px-6 py-4">
                    <h2 class="text-xl font-semibold text-gray-800"><?= $pageTitle ?></h2>
                    <a href="<?= BASE_URL ?>admin/manage-products" class="text-gray-600 hover:text-gray-900">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Products
                    </a>
                </div>
            </header>
            
            <main class="p-6">
                <?php if ($formSuccess): ?>
                    <div class="bg-green-50 border border-green-200 text-green-800 p-4 rounded mb-6">
                        <?= $formSuccess ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($formError): ?>
                    <div class="bg-red-50 border border-red-200 text-red-800 p-4 rounded mb-6">
                        <?= $formError ?>
                    </div>
                <?php endif; ?>
                
                <div class="bg-white rounded-lg shadow p-6">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Basic Information -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-medium border-b pb-2">Basic Information</h3>
                                
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Product Name</label>
                                    <input type="text" id="name" name="name" class="w-full px-3 py-2 border border-gray-300 rounded" value="<?= $product ? $product['name'] : '' ?>" required>
                                </div>
                                
                                <div>
                                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                                    <select id="category_id" name="category_id" class="w-full px-3 py-2 border border-gray-300 rounded" required>
                                        <option value="">Select Category</option>
                                        <?php foreach ($categories as $category): ?>
                                            <option value="<?= $category['id'] ?>" <?= ($product && $product['category_id'] === $category['id']) ? 'selected' : '' ?>>
                                                <?= $category['name'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Price</label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">$</span>
                                        <input type="number" id="price" name="price" step="0.01" min="0" class="w-full pl-8 px-3 py-2 border border-gray-300 rounded" value="<?= $product ? $product['price'] : '' ?>" required>
                                    </div>
                                </div>
                                <div>
                                    <label for="original_price" class="block text-sm font-medium text-gray-700 mb-1">original Price</label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">$</span>
                                        <input type="number" id="original_price" name="original_price" step="0.01" min="0" class="w-full pl-8 px-3 py-2 border border-gray-300 rounded" value="<?= $product ? $product['original_price'] : '' ?>" required>
                                    </div>
                                </div>
                                <div>
                                    <label for="stock" class="block text-sm font-medium text-gray-700 mb-1">Stock Quantity</label>
                                    <input type="number" id="stock" name="stock" min="0" class="w-full px-3 py-2 border border-gray-300 rounded" value="<?= $product ? $product['stock'] : 0 ?>" required>
                                </div>
                                
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                    <select id="status" name="status" class="w-full px-3 py-2 border border-gray-300 rounded" required>
                                        <option value="active" <?= ($product && $product['status'] === 'active') ? 'selected' : '' ?>>Active</option>
                                        <option value="inactive" <?= ($product && $product['status'] === 'inactive') ? 'selected' : '' ?>>Inactive</option>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Additional Information -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-medium border-b pb-2">Additional Information</h3>
                                
                                <div>
                                    <label for="brand" class="block text-sm font-medium text-gray-700 mb-1">Brand</label>
                                    <input type="text" id="brand" name="brand" class="w-full px-3 py-2 border border-gray-300 rounded" value="<?= $product ? $product['brand'] : '' ?>">
                                </div>
                                
                                <div>
                                    <label for="color" class="block text-sm font-medium text-gray-700 mb-1">Color</label>
                                    <input type="text" id="color" name="color" class="w-full px-3 py-2 border border-gray-300 rounded" value="<?= $product ? $product['color'] : '' ?>">
                                </div>
                                
                                <div>
                                    <label for="age_range" class="block text-sm font-medium text-gray-700 mb-1">Age Range</label>
                                    <input type="text" id="age_range" name="age_range" class="w-full px-3 py-2 border border-gray-300 rounded" value="<?= $product ? $product['age_range'] : '' ?>">
                                </div>
                                <div>
                                    <label for="rating" class="block text-sm font-medium text-gray-700 mb-1">rating</label>
                                    <input type="number" id="rating" name="rating" class="w-full px-3 py-2 border border-gray-300 rounded" value="<?= $product ? $product['rating'] : '' ?>">
                                </div>
                                
                                <div>
                                    <label for="featured" class="flex items-center">
                                        <input type="checkbox" id="featured" name="featured" class="mr-2" <?= ($product && isset($product['is_featured']) && $product['is_featured'] == 1) ? 'checked' : '' ?>>
                                        <span class="text-sm font-medium text-gray-700">Featured Product</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Product Images -->
                        <div class="mt-6 space-y-4">
                            <h3 class="text-lg font-medium border-b pb-2">Product Images</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Main Image</label>
                                    <input type="file" id="image" name="image" class="w-full px-3 py-2 border border-gray-300 rounded" accept="image/*">
                                    <?php if ($product && !empty($product['image'])): ?>
                                        <div class="mt-2">
                                            <p class="text-sm text-gray-500">Current Image:</p>
                                            <img src="<?php echo BASE_URL; ?>assets/images/products/<?= $product['image'] ?>" alt="<?= $product['name'] ?>" class="h-12 w-12 object-cover rounded">
                                            </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div>
                                    <label for="image_hover" class="block text-sm font-medium text-gray-700 mb-1">Hover Image</label>
                                    <input type="file" id="image_hover" name="image_hover" class="w-full px-3 py-2 border border-gray-300 rounded" accept="image/*">
                                    <?php if ($product && !empty($product['image_hover'])): ?>
                                        <div class="mt-2">
                                            <p class="text-sm text-gray-500">Current Hover Image:</p>
                                            <img src="<?php echo BASE_URL; ?>assets/images/products/<?= $product['image_hover'] ?>" alt="<?= $product['name'] ?>" class="h-12 w-12 object-cover rounded">
                                            </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Product Description -->
                        <div class="mt-6 space-y-4">
                            <h3 class="text-lg font-medium border-b pb-2">Product Description</h3>
                            
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                <textarea id="description" name="description" rows="6" class="w-full px-3 py-2 border border-gray-300 rounded" required><?= $product ? $product['description'] : '' ?></textarea>
                            </div>
                        </div>
                        
                        <!-- Form buttons -->
                        <div class="mt-6 flex justify-end">
                            <a href="<?= BASE_URL ?>admin/manage-products" class="px-4 py-2 border border-gray-300 rounded bg-white text-gray-700 mr-2 hover:bg-gray-50">
                                Cancel
                            </a>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                <?= $isEdit ? 'Update Product' : 'Add Product' ?>
                            </button>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
