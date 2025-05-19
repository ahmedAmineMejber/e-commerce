<?php
// Check admin access
require_once 'controllers/AdminController.php';
$adminController = new AdminController();
$adminController->checkAdminAccess();

// Initialize variables
$user = null;
$formError = '';
$formSuccess = '';

// Get user ID from URL parameter
if (!isset($_GET['id'])) {
    header("Location: " . BASE_URL . "admin/manage-users");
    exit;
}

// Get user details
$user = $adminController->getUserById($_GET['id']);

// If user doesn't exist, redirect
if (!$user) {
    header("Location: " . BASE_URL . "admin/manage-users");
    exit;
}

// Handle form submission for user update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
    $userData = [
        'id' => $user['id'],
        'name' => $_POST['name'],
        'email' => $_POST['email'],
        'role' => $_POST['role'],
        'image' => $user['image'] // Default to existing image
    ];
    
    // Check if password is being updated
    if (!empty($_POST['password'])) {
        $userData['password'] = $_POST['password'];
    }
    
    // For image upload
    $imageUploaded = false;
    
    // Check if image file is uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "uploads/users/";
        
        // Create directory if it doesn't exist
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        
        $fileName = basename($_FILES['image']['name']);
        $targetPath = $targetDir . uniqid() . '_' . $fileName;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            $userData['image'] = $targetPath;
            $imageUploaded = true;
        }
    }
    
    $result = $adminController->updateUser($userData);
    
    if ($result['success']) {
        $formSuccess = $result['message'];
        // Refresh user data
        $user = $adminController->getUserById($user['id']);
    } else {
        $formError = $result['message'];
    }
}

// Page title
$pageTitle = "User Details";
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
                    <h2 class="text-xl font-semibold text-gray-800"><?= $pageTitle ?></h2>
                    <a href="<?= BASE_URL ?>admin/manage-users" class="text-gray-600 hover:text-gray-900">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Users
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
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- User Info Card -->
                    <div class="col-span-1">
                        <div class="bg-white rounded-lg shadow overflow-hidden">
                            <div class="p-6 flex flex-col items-center">
                                <img src="<?= !empty($user['image']) ? $user['image'] : 'https://via.placeholder.com/150' ?>" alt="<?= $user['name'] ?>" 
                                    class="h-32 w-32 rounded-full object-cover border-4 border-gray-200">
                                <h3 class="mt-4 text-xl font-semibold"><?= $user['name'] ?></h3>
                                <p class="text-gray-500"><?= $user['email'] ?></p>
                                <span class="mt-2 px-3 py-1 text-xs font-semibold rounded-full 
                                    <?= $user['role'] === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' ?>">
                                    <?= ucfirst($user['role']) ?>
                                </span>
                                <div class="mt-6 w-full">
                                    <a href="<?= BASE_URL ?>admin/user-orders?id=<?= $user['id'] ?>" class="block w-full text-center px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                                        View Orders
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- User Edit Form -->
                    <div class="col-span-1 lg:col-span-2">
                        <div class="bg-white rounded-lg shadow p-6">
                            <h3 class="text-lg font-medium border-b pb-2 mb-4">Edit User Information</h3>
                            
                            <form method="POST" enctype="multipart/form-data">
                                <div class="space-y-4">
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                                        <input type="text" id="name" name="name" class="w-full px-3 py-2 border border-gray-300 rounded" value="<?= $user['name'] ?>" required>
                                    </div>
                                    
                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                        <input type="email" id="email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded" value="<?= $user['email'] ?>" required>
                                    </div>
                                    
                                    <div>
                                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                                        <input type="password" id="password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded" placeholder="Leave blank to keep current password">
                                        <p class="text-xs text-gray-500 mt-1">Only fill this if you want to change the password</p>
                                    </div>
                                    
                                    <div>
                                        <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                                        <select id="role" name="role" class="w-full px-3 py-2 border border-gray-300 rounded" required>
                                            <option value="client" <?= $user['role'] === 'client' ? 'selected' : '' ?>>Client</option>
                                            <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Profile Image</label>
                                        <input type="file" id="image" name="image" class="w-full px-3 py-2 border border-gray-300 rounded" accept="image/*">
                                        <p class="text-xs text-gray-500 mt-1">Leave blank to keep current image</p>
                                    </div>
                                    
                                    <div class="pt-4 flex justify-end">
                                        <button type="submit" name="update_user" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                            Update User
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>