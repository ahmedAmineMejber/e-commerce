<?php
// Check admin access
require_once 'controllers/AdminController.php';
$adminController = new AdminController();
$adminController->checkAdminAccess();

// Handle user deletion
$deleteSuccess = false;
$deleteError = '';

if (isset($_POST['delete_user']) && isset($_POST['user_id'])) {
    $result = $adminController->deleteUser($_POST['user_id']);
    if ($result['success']) {
        $deleteSuccess = true;
    } else {
        $deleteError = $result['message'];
    }
}

// Get all users
$users = $adminController->getAllUsers();

// Page title
$pageTitle = "Manage Users";
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
                        <a href="<?= BASE_URL ?>admin/manage-categories" class="flex items-center p-3 hover:bg-gray-700 rounded">
                            <i class="fas fa-tags w-6"></i>
                            <span>Categories</span>
                        </a>
                    </li>
                    <li class="mb-1">
                        <a href="<?= BASE_URL ?>admin/manage-users" class="flex items-center p-3 bg-gray-900 text-white rounded">
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
                    <h2 class="text-xl font-semibold text-gray-800">Manage Users</h2>
                    <a href="<?= BASE_URL ?>admin/manage-users/add" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        <i class="fas fa-plus mr-2"></i> Add New User
                    </a>
                </div>
            </header>
            
            <main class="p-6">
                <?php if ($deleteSuccess): ?>
                    <div class="bg-green-50 border border-green-200 text-green-800 p-4 rounded mb-6">
                        User deleted successfully.
                    </div>
                <?php endif; ?>
                
                <?php if ($deleteError): ?>
                    <div class="bg-red-50 border border-red-200 text-red-800 p-4 rounded mb-6">
                        <?= $deleteError ?>
                    </div>
                <?php endif; ?>
                
                <!-- User filters -->
                <div class="bg-white rounded-lg shadow mb-6 p-4">
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="md:w-1/4">
                            <label for="roleFilter" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                            <select id="roleFilter" class="w-full px-3 py-2 border border-gray-300 rounded">
                                <option value="">All Roles</option>
                                <option value="admin">Admin</option>
                                <option value="client">Client</option>
                            </select>
                        </div>
                        <div class="md:w-3/4">
                            <label for="searchFilter" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                            <input type="text" id="searchFilter" placeholder="Search users by name or email..." class="w-full px-3 py-2 border border-gray-300 rounded">
                        </div>
                    </div>
                </div>
                
                <!-- User List -->
                <div class="bg-white rounded-lg shadow">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php if (empty($users)): ?>
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">No users found</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($users as $user): ?>
                                        <tr class="user-item" data-role="<?= $user['role'] ?>">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">

                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900"><?= $user['name'] ?></div>
                                                        <div class="text-sm text-gray-500">ID: <?= substr($user['id'], 0, 8) ?></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900"><?= $user['email'] ?></div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                <?= $user['role'] === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' ?>">
                                                    <?= ucfirst($user['role']) ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="<?= BASE_URL ?>admin/user-details?id=<?= $user['id'] ?>" class="text-indigo-600 hover:text-indigo-900 mr-3">View</a>
                                                <a href="<?= BASE_URL ?>admin/manage-users/edit?id=<?= $user['id'] ?>" class="text-green-600 hover:text-green-900 mr-3">Edit</a>
                                                <?php if ($_SESSION['user_id'] !== $user['id']): ?>
                                                <form method="POST" action="" class="inline" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                                    <button type="submit" name="delete_user" class="text-red-600 hover:text-red-900">Delete</button>
                                                </form>
                                                <?php endif; ?>
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
        const roleFilter = document.getElementById('roleFilter');
        const searchFilter = document.getElementById('searchFilter');
        const userItems = document.querySelectorAll('.user-item');
        
        function applyFilters() {
            const role = roleFilter.value.toLowerCase();
            const search = searchFilter.value.toLowerCase();
            
            userItems.forEach(item => {
                const itemRole = item.getAttribute('data-role').toLowerCase();
                const userName = item.querySelector('td:first-child .text-sm.font-medium').textContent.toLowerCase();
                const userEmail = item.querySelector('td:nth-child(2) .text-sm').textContent.toLowerCase();
                
                const matchRole = !role || itemRole === role;
                const matchSearch = !search || userName.includes(search) || userEmail.includes(search);
                
                if (matchRole && matchSearch) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        }
        
        roleFilter.addEventListener('change', applyFilters);
        searchFilter.addEventListener('input', applyFilters);
    });
    </script>
</body>
</html>