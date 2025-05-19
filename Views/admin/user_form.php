<?php
// Check admin access
require_once 'controllers/AdminController.php';
$adminController = new AdminController();
$adminController->checkAdminAccess();

// Initialize variables
$formError = '';
$formSuccess = '';
$isEdit = false;
$user = null;

// Check if this is an edit action
if (isset($_GET['id'])) {
    // Get user details
    require_once 'models/User.php';
    $userModel = new User();
    $user = $userModel->read($_GET['id']);
    
    if (!$user) {
        header("Location: " . BASE_URL . "admin/manage-users");
        exit;
    }
    
    $isEdit = true;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userData = [
        'name' => $_POST['name'],
        'email' => $_POST['email'],
        'role' => $_POST['role'],
        'image' => 'uploads/users/default.png' // Default image
    ];
    
    // For password
    if ($isEdit) {
        // If editing and password provided, update it
        if (!empty($_POST['password'])) {
            $userData['password'] = $_POST['password'];
        }
        $userData['id'] = $user['id'];
    } else {
        // For new user, password is required
        if (empty($_POST['password'])) {
            $formError = "Password is required for new users";
        } else {
            $userData['password'] = $_POST['password'];
            // Generate ID for new user
            $userData['id'] = uniqid('user_');
        }
    }
    
    // For image upload
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
        }
    } else if ($isEdit) {
        // Keep existing image if editing
        $userData['image'] = $user['image'];
    }
    
    // Submit data if no errors
    if (empty($formError)) {
        if ($isEdit) {
            $result = $adminController->updateUser($userData);
        } else {
            $result = $adminController->addUser($userData);
        }
        
        if ($result['success']) {
            $formSuccess = $result['message'];
            if (!$isEdit) {
                // Reset form for new user
                $_POST = [];
            }
        } else {
            $formError = $result['message'];
        }
    }
}

// Page title
$pageTitle = $isEdit ? "Edit User" : "Add New User";
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
                
                <div class="bg-white rounded-lg shadow p-6">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Basic Information -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-medium border-b pb-2">Basic Information</h3>
                                
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                                    <input type="text" id="name" name="name" class="w-full px-3 py-2 border border-gray-300 rounded" value="<?= $isEdit ? $user['name'] : (isset($_POST['name']) ? $_POST['name'] : '') ?>" required>
                                </div>
                                
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                                    <input type="email" id="email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded" value="<?= $isEdit ? $user['email'] : (isset($_POST['email']) ? $_POST['email'] : '') ?>" required>
                                </div>
                                
                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                                        Password <?= $isEdit ? '(Leave blank to keep current)' : '(Required)' ?>
                                    </label>
                                    <input type="password" id="password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded" <?= $isEdit ? '' : 'required' ?>>
                                </div>
                                
                                <div>
                                    <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                                    <select id="role" name="role" class="w-full px-3 py-2 border border-gray-300 rounded" required>
                                        <option value="client" <?= $isEdit && $user['role'] === 'client' ? 'selected' : '' ?>>Client</option>
                                        <option value="admin" <?= $isEdit && $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Profile Image -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-medium border-b pb-2">Profile Image</h3>
                                
                                <div>
                                    <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Upload Image</label>
                                    <input type="file" id="image" name="image" class="w-full px-3 py-2 border border-gray-300 rounded" accept="image/*">
                                </div>
                                
                                <?php if ($isEdit && !empty($user['image'])): ?>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500 mb-2">Current Image:</p>
                                        <img src="<?= $user['image'] ?>" alt="Current profile image" class="h-32 w-32 object-cover rounded-full border-2 border-gray-300">
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Form buttons -->
                        <div class="mt-6 flex justify-end">
                            <a href="<?= BASE_URL ?>admin/manage-users" class="px-4 py-2 border border-gray-300 rounded bg-white text-gray-700 mr-2 hover:bg-gray-50">
                                Cancel
                            </a>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                <?= $isEdit ? 'Update User' : 'Add User' ?>
                            </button>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>
</body>
</html>