<?php
// Check admin access
require_once 'controllers/AdminController.php';
$adminController = new AdminController();
$adminController->checkAdminAccess();

// Get order ID
$orderId = isset($_GET['id']) ? $_GET['id'] : null;

// Redirect if no ID provided
if (!$orderId) {
    header("Location: " . BASE_URL . "admin/orders");
    exit;
}

// Get order details
require_once 'controllers/OrderController.php';
$orderController = new OrderController();
$orderData = $orderController->getOrderDetails($orderId);

// Check if order exists
if (!$orderData['success']) {
    header("Location: " . BASE_URL . "admin/orders");
    exit;
}

$order = $orderData['order'];
$items = $orderData['items'];

// Process status update
$statusUpdated = false;
$statusMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $newStatus = $_POST['order_status'];
    $result = $orderController->updateOrderStatus($orderId, $newStatus);
    
    if ($result['success']) {
        $statusUpdated = true;
        $statusMessage = 'Order status updated successfully';
        $order['status'] = $newStatus;
    } else {
        $statusMessage = 'Failed to update order status';
    }
}

// Page title
$pageTitle = "Order #" . substr($orderId, -6);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?> - /title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="bg-gray-800 text-white w-64 flex-shrink-0">
            <div class="p-4">
                <h1 class="text-2xl font-bold">/h1>
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
                        <a href="<?= BASE_URL ?>admin/manage-users" class="flex items-center p-3 hover:bg-gray-700 rounded">
                            <i class="fas fa-users w-6"></i>
                            <span>Users</span>
                        </a>
                    </li>
                    <li class="mb-1">
                        <a href="<?= BASE_URL ?>admin/orders" class="flex items-center p-3 bg-gray-900 text-white rounded">
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
                        <a href="<?= BASE_URL ?>admin/orders" class="mr-4 text-gray-500 hover:text-gray-700">
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
                <?php if ($statusUpdated): ?>
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                        <p><?= $statusMessage ?></p>
                    </div>
                <?php elseif (!empty($statusMessage)): ?>
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                        <p><?= $statusMessage ?></p>
                    </div>
                <?php endif; ?>
                
                <!-- Order summary -->
                <div class="bg-white rounded-lg shadow mb-6">
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold">Order Summary</h3>
                    </div>
                    <div class="p-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Order Date</p>
                            <p class="font-semibold"><?= date('M j, Y g:i A', strtotime($order['order_date'])) ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Total Amount</p>
                            <p class="font-semibold">$<?= number_format($order['total_price'], 2) ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Status</p>
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                            <?php if ($order['status'] === 'in progress'): ?>
                                bg-yellow-100 text-yellow-800
                            <?php elseif ($order['status'] === 'completed'): ?>
                                bg-green-100 text-green-800
                            <?php else: ?>
                                bg-red-100 text-red-800
                            <?php endif; ?>">
                                <?= ucfirst($order['status']) ?>
                            </span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Payment Status</p>
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                            <?php if ($order['payment_status'] === 'pending'): ?>
                                bg-blue-100 text-blue-800
                            <?php elseif ($order['payment_status'] === 'paid'): ?>
                                bg-green-100 text-green-800
                            <?php else: ?>
                                bg-red-100 text-red-800
                            <?php endif; ?>">
                                <?= ucfirst($order['payment_status']) ?>
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Customer information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="bg-white rounded-lg shadow">
                        <div class="p-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold">Customer Information</h3>
                        </div>
                        <div class="p-4">
                            <p class="font-semibold"><?= $order['customer_name'] ?></p>
                            <p class="text-sm text-gray-500"><?= $order['customer_email'] ?></p>
                            <div class="mt-4">
                                <a href="<?= BASE_URL ?>admin/user-details?id=<?= $order['user_id'] ?>" class="text-indigo-600 hover:text-indigo-900 text-sm">
                                    View customer profile
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-lg shadow">
                        <div class="p-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold">Update Order Status</h3>
                        </div>
                        <div class="p-4">
                            <form action="" method="POST">
                                <div class="mb-4">
                                    <label for="order_status" class="block text-sm font-medium text-gray-700 mb-1">Change Status</label>
                                    <select id="order_status" name="order_status" class="w-full border-gray-300 rounded-md shadow-sm p-2">
                                        <option value="in progress" <?= $order['status'] === 'in progress' ? 'selected' : '' ?>>In Progress</option>
                                        <option value="completed" <?= $order['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                                        <option value="cancelled" <?= $order['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                    </select>
                                </div>
                                <button type="submit" name="update_status" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">
                                    Update Status
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Order items -->
                <div class="bg-white rounded-lg shadow mb-6">
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold">Order Items</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($items as $item): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <?php if (!empty($item['image'])): ?>
                                                    <div class="flex-shrink-0 h-12 w-12">
                                                        <img class="h-12 w-12 rounded-md object-cover" src="<?= $item['image'] ?>" alt="<?= $item['product_name'] ?>">
                                                    </div>
                                                <?php endif; ?>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900"><?= $item['product_name'] ?></div>
                                                    <div class="text-xs text-gray-500">ID: <?= substr($item['product_id'], 0, 8) ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            $<?= number_format($item['price'], 2) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <?= $item['qty'] ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            $<?= number_format($item['price'] * $item['qty'], 2) ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-right font-medium">Grand Total:</td>
                                    <td class="px-6 py-4 text-right font-bold">$<?= number_format($order['total_price'], 2) ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                
                <div class="flex justify-between">
                    <a href="<?= BASE_URL ?>admin/orders" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md">
                        Back to Orders
                    </a>
                    <button onclick="window.print()" class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-md">
                        <i class="fas fa-print mr-2"></i> Print Order
                    </button>
                </div>
            </main>
        </div>
    </div>
</body>
</html>