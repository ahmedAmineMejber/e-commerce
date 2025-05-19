<?php
// Check admin access
require_once 'controllers/AdminController.php';
$adminController = new AdminController();
$adminController->checkAdminAccess();

// Get all categories
$categories = $adminController->getAllCategories();

// Handle category deletion
$deleteSuccess = false;
$deleteError = '';

if (isset($_POST['delete_category']) && isset($_POST['category_id'])) {
    $result = $adminController->deleteCategory($_POST['category_id']);
    if ($result['success']) {
        $deleteSuccess = true;
        // Refresh category list
        $categories = $adminController->getAllCategories();
    } else {
        $deleteError = $result['message'];
    }
}

// Handle add/edit category
$formSuccess = '';
$formError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_category'])) {
    $categoryData = [
        'name' => $_POST['name'],
        'description' => $_POST['description'],
        'icon' => $_POST['icon']
    ];
    
    if (isset($_POST['category_id']) && !empty($_POST['category_id'])) {
        // Edit category
        $categoryData['id'] = $_POST['category_id'];
        $result = $adminController->updateCategory($categoryData);
    } else {
        // Add new category
        $result = $adminController->addCategory($categoryData);
    }
    
    if ($result['success']) {
        $formSuccess = $result['message'];
        // Refresh category list
        $categories = $adminController->getAllCategories();
    } else {
        $formError = $result['message'];
    }
}

// Page title
$pageTitle = "Manage Categories";
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
                        <a href="<?= BASE_URL ?>admin/manage-products" class="flex items-center p-3 hover:bg-gray-700 rounded">
                            <i class="fas fa-box w-6"></i>
                            <span>Products</span>
                        </a>
                    </li>
                    <li class="mb-1">
                        <a href="<?= BASE_URL ?>admin/manage-categories" class="flex items-center p-3 bg-gray-900 text-white rounded">
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
                    <h2 class="text-xl font-semibold text-gray-800">Manage Categories</h2>
                    <button id="addCategoryBtn" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        <i class="fas fa-plus mr-2"></i> Add New Category
                    </button>
                </div>
            </header>
            
            <main class="p-6">
                <?php if ($deleteSuccess): ?>
                    <div class="bg-green-50 border border-green-200 text-green-800 p-4 rounded mb-6">
                        Category deleted successfully.
                    </div>
                <?php endif; ?>
                
                <?php if ($deleteError): ?>
                    <div class="bg-red-50 border border-red-200 text-red-800 p-4 rounded mb-6">
                        <?= $deleteError ?>
                    </div>
                <?php endif; ?>
                
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
                
                <!-- Category Form Modal -->
                <div id="categoryModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
                    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-semibold" id="modalTitle">Add New Category</h3>
                            <button type="button" class="text-gray-400 hover:text-gray-500" id="closeModal">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        
                        <form method="POST" action="">
                            <input type="hidden" id="category_id" name="category_id" value="">
                            
                            <div class="mb-4">
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Category Name</label>
                                <input type="text" id="name" name="name" class="w-full px-3 py-2 border border-gray-300 rounded" required>
                            </div>
                            
                            <div class="mb-4">
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                <textarea id="description" name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded"></textarea>
                            </div>
                            
                            <div class="mb-6">
                                <label for="icon" class="block text-sm font-medium text-gray-700 mb-1">Icon (Font Awesome class)</label>
                                <input type="text" id="icon" name="icon" class="w-full px-3 py-2 border border-gray-300 rounded" placeholder="fas fa-tag">
                                <div class="mt-2 text-sm text-gray-500">
                                    Preview: <i id="iconPreview" class="fas fa-tag"></i>
                                </div>
                            </div>
                            
                            <div class="flex justify-end">
                                <button type="button" class="px-4 py-2 border border-gray-300 rounded bg-white text-gray-700 mr-2 hover:bg-gray-50" id="cancelBtn">
                                    Cancel
                                </button>
                                <button type="submit" name="save_category" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                    Save Category
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Category List -->
                <div class="bg-white rounded-lg shadow">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Icon</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php if (empty($categories)): ?>
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No categories found</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($categories as $category): ?>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <i class="<?= $category['icon'] ?>">
                                                <img src="<?php echo BASE_URL; ?>assets/images/icons/<?php echo $category['icon']; ?>" alt="clothes"
                                                width="20" height="20" class="menu-title-img">
                                                </i>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900"><?= $category['name'] ?></div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm text-gray-500"><?= $category['description'] ?></div>
                                            </td>

                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <button type="button" class="text-indigo-600 hover:text-indigo-900 mr-3 edit-category-btn"
                                                    data-id="<?= $category['id'] ?>"
                                                    data-name="<?= $category['name'] ?>"
                                                    data-description="<?= $category['description'] ?>"
                                                    data-icon="<?= $category['icon'] ?>">
                                                    Edit
                                                </button>
                                                <form method="POST" action="" class="inline" onsubmit="return confirm('Are you sure you want to delete this category?');">
                                                    <input type="hidden" name="category_id" value="<?= $category['id'] ?>">
                                                    <button type="submit" name="delete_category" class="text-red-600 hover:text-red-900">Delete</button>
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
        const modal = document.getElementById('categoryModal');
        const addBtn = document.getElementById('addCategoryBtn');
        const closeBtn = document.getElementById('closeModal');
        const cancelBtn = document.getElementById('cancelBtn');
        const editBtns = document.querySelectorAll('.edit-category-btn');
        const modalTitle = document.getElementById('modalTitle');
        const categoryIdInput = document.getElementById('category_id');
        const nameInput = document.getElementById('name');
        const descriptionInput = document.getElementById('description');
        const iconInput = document.getElementById('icon');
        const iconPreview = document.getElementById('iconPreview');
        
        // Open modal for new category
        addBtn.addEventListener('click', function() {
            modalTitle.textContent = 'Add New Category';
            categoryIdInput.value = '';
            nameInput.value = '';
            descriptionInput.value = '';
            iconInput.value = 'fas fa-tag';
            iconPreview.className = 'fas fa-tag';
            modal.classList.remove('hidden');
        });
        
        // Close modal
        closeBtn.addEventListener('click', function() {
            modal.classList.add('hidden');
        });
        
        cancelBtn.addEventListener('click', function() {
            modal.classList.add('hidden');
        });
        
        // Open modal for edit
        editBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const description = this.getAttribute('data-description');
                const icon = this.getAttribute('data-icon');
                
                modalTitle.textContent = 'Edit Category';
                categoryIdInput.value = id;
                nameInput.value = name;
                descriptionInput.value = description;
                iconInput.value = icon;
                iconPreview.className = icon;
                
                modal.classList.remove('hidden');
            });
        });
        
        // Update icon preview
        iconInput.addEventListener('input', function() {
            iconPreview.className = this.value;
        });
    });
    </script>
</body>
</html>