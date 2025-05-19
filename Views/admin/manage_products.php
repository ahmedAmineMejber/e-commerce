<?php
// Check admin access
require_once 'controllers/AdminController.php';
$adminController = new AdminController();
$adminController->checkAdminAccess();

// Get all products
$products = $adminController->getAllProducts();

// Get all categories for filter
$categories = $adminController->getAllCategories();

// Handle product deletion
$deleteSuccess = false;
$deleteError = '';

if (isset($_POST['delete_product']) && isset($_POST['product_id'])) {
    $result = $adminController->deleteProduct($_POST['product_id']);
    if ($result['success']) {
        $deleteSuccess = true;
        // Refresh product list
        $products = $adminController->getAllProducts();
    } else {
        $deleteError = $result['message'];
    }
}

// Page title
$pageTitle = "Manage Products";
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
                    <h2 class="text-xl font-semibold text-gray-800">Manage Products</h2>
                    <a href="<?= BASE_URL ?>admin/manage-products/add" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        <i class="fas fa-plus mr-2"></i> Add New Product
                    </a>
                </div>
            </header>
            
            <main class="p-6">
                <?php if ($deleteSuccess): ?>
                    <div class="bg-green-50 border border-green-200 text-green-800 p-4 rounded mb-6">
                        Product deleted successfully.
                    </div>
                <?php endif; ?>
                
                <?php if ($deleteError): ?>
                    <div class="bg-red-50 border border-red-200 text-red-800 p-4 rounded mb-6">
                        <?= $deleteError ?>
                    </div>
                <?php endif; ?>
                
                <!-- Filters -->
                <div class="bg-white rounded-lg shadow mb-6 p-4">
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="md:w-1/4">
                            <label for="categoryFilter" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <select id="categoryFilter" class="w-full px-3 py-2 border border-gray-300 rounded">
                                <option value="">All Categories</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= $category['name'] ?>"><?= $category['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="md:w-1/4">
                            <label for="statusFilter" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select id="statusFilter" class="w-full px-3 py-2 border border-gray-300 rounded">
                                <option value="">All Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="md:w-1/2">
                            <label for="searchFilter" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                            <input type="text" id="searchFilter" placeholder="Search products..." class="w-full px-3 py-2 border border-gray-300 rounded">
                        </div>
                    </div>
                </div>
                
                <!-- Product List -->
                <div class="bg-white rounded-lg shadow">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Original Price</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="productList">
                                <?php if (empty($products)): ?>
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">No products found</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($products as $product): ?>
                                        <tr class="product-item" 
                                            data-category="<?= isset($product['category_name']) ? $product['category_name'] : '' ?>" 
                                            data-status="<?= $product['status'] ?>">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <img src="<?php echo BASE_URL; ?>assets/images/products/<?= $product['image'] ?>" alt="<?= $product['name'] ?>" class="h-12 w-12 object-cover rounded">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <?= $product['name'] ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <?= isset($product['category_name']) ? $product['category_name'] : 'N/A' ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                $<?= number_format($product['price'], 2) ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                $<?= number_format($product['original_price'], 2) ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <?php if ($product['stock'] <= 5): ?>
                                                    <span class="text-red-600"><?= $product['stock'] ?></span>
                                                <?php else: ?>
                                                    <?= $product['stock'] ?>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                <?= $product['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                                    <?= ucfirst($product['status']) ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="<?= BASE_URL ?>admin/manage-products/edit?id=<?= $product['id'] ?>" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                                <form method="POST" action="" class="inline" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                                    <button type="submit" name="delete_product" class="text-red-600 hover:text-red-900">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Filter functionality
        const categoryFilter = document.getElementById('categoryFilter');
        const statusFilter = document.getElementById('statusFilter');
        const searchFilter = document.getElementById('searchFilter');
        const productItems = document.querySelectorAll('.product-item');
        
        function applyFilters() {
            const category = categoryFilter.value.toLowerCase();
            const status = statusFilter.value.toLowerCase();
            const search = searchFilter.value.toLowerCase();
            
            productItems.forEach(item => {
                const itemCategory = item.getAttribute('data-category').toLowerCase();
                const itemStatus = item.getAttribute('data-status').toLowerCase();
                const itemName = item.querySelector('td:nth-child(2)').textContent.toLowerCase();
                
                const matchCategory = !category || itemCategory === category;
                const matchStatus = !status || itemStatus === status;
                const matchSearch = !search || itemName.includes(search);
                
                if (matchCategory && matchStatus && matchSearch) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        }
        
        categoryFilter.addEventListener('change', applyFilters);
        statusFilter.addEventListener('change', applyFilters);
        searchFilter.addEventListener('input', applyFilters);
    });
    </script>
</body>
</html>