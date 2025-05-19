<?php
// Check admin access
require_once 'controllers/AdminController.php';
$adminController = new AdminController();
$adminController->checkAdminAccess();

// Get user ID
$userId = isset($_GET['id']) ? $_GET['id'] : null;

// Redirect if no ID provided
if (!$userId) {
    header("Location: " . BASE_URL . "admin/manage-users");
    exit;
}

// Get user details
$user = $adminController->getUserById($userId);

if (!$user) {
    header("Location: " . BASE_URL . "admin/manage-users");
    exit;
}

// Get user orders
require_once 'controllers/OrderController.php';
$orderController = new OrderController();
$ordersResult = $orderController->getUserOrders($userId);
$orders = isset($ordersResult['orders']) ? $ordersResult['orders'] : [];

// Page title
$pageTitle = "Orders for " . $user['name'];
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
                    <div class="flex items-center">
                        <a href="<?= BASE_URL ?>admin/user-details?id=<?= $userId ?>" class="mr-4 text-gray-500 hover:text-gray-700">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <h2 class="text-xl font-semibold text-gray-800"><?= $pageTitle ?></h2>
                    </div>
                    <div class="flex items-center">
                        <span class="text-gray-600 mr-4"><?= $_SESSION['user_name'] ?></span>
                        <img src="<?= !empty($_SESSION['user_image']) ? $_SESSION['user_image'] : 'https://via.placeholder.com/40' ?>" alt="Profile" class="w-8 h-8 rounded-full">
                    </div>
                </div>
            </header>
            
            <main class="p-6">
                <!-- User info summary -->
                <div class="bg-white rounded-lg shadow mb-6 p-4 flex items-center">
                    <img src="<?= !empty($user['image']) ? $user['image'] : 'https://via.placeholder.com/60' ?>" alt="<?= $user['name'] ?>" class="w-16 h-16 rounded-full mr-4">
                    <div>
                        <h3 class="text-xl font-semibold"><?= $user['name'] ?></h3>
                        <p class="text-gray-600"><?= $user['email'] ?></p>
                        <p class="text-sm mt-1">
                            <span class="px-2 py-1 bg-<?= $user['role'] === 'admin' ? 'purple' : 'blue' ?>-100 text-<?= $user['role'] === 'admin' ? 'purple' : 'blue' ?>-800 rounded-full text-xs">
                                <?= ucfirst($user['role']) ?>
                            </span>
                        </p>
                    </div>
                </div>
                
                <!-- Orders table -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold">Order History</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <?php if (empty($orders)): ?>
                            <div class="p-6 text-center text-gray-500">
                                <i class="fas fa-shopping-cart text-gray-300 text-4xl mb-3"></i>
                                <p>No orders found for this user</p>
                            </div>
                        <?php else: ?>
                            <table class="w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php foreach ($orders as $order): ?>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                #<?= substr($order['id'], -6) ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <?= date('M j, Y', strtotime($order['order_date'])) ?>
                                                <div class="text-xs text-gray-500"><?= date('g:i A', strtotime($order['order_date'])) ?></div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                $<?= number_format($order['total_price'], 2) ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                <?php if ($order['status'] === 'in progress'): ?>
                                                    bg-yellow-100 text-yellow-800
                                                <?php elseif ($order['status'] === 'completed'): ?>
                                                    bg-green-100 text-green-800
                                                <?php else: ?>
                                                    bg-red-100 text-red-800
                                                <?php endif; ?>">
                                                    <?= ucfirst($order['status']) ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <?= count($order['items']) ?> items
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="<?= BASE_URL ?>admin/orders/view?id=<?= $order['id'] ?>" class="text-indigo-600 hover:text-indigo-900">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
