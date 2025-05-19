<?php
// Check admin access
require_once 'controllers/AdminController.php';
$adminController = new AdminController();
$adminController->checkAdminAccess();

// Get dashboard summary
$summary = $adminController->getDashboardSummary();

// Get sales data for chart
$salesPeriod = isset($_GET['period']) ? $_GET['period'] : 'week';
$salesData = $adminController->getSalesData($salesPeriod);

// Get recent orders
$recentOrders = $adminController->getRecentOrders(5);

// Get top selling products
$topProducts = $adminController->getTopSellingProducts(5);

// Page title
$pageTitle = "Admin Dashboard";

// Define constants
define('APP_NAME', 'ShopifyX'); // Replace with your actual app name
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?> - </title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                        <a href="<?= BASE_URL ?>admin" class="flex items-center p-3 bg-gray-900 text-white rounded">
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
                    <h2 class="text-xl font-semibold text-gray-800">Dashboard</h2>
                    <div class="flex items-center">
                        <span class="text-gray-600 mr-4"><?= $_SESSION['user_name'] ?></span>
                    </div>
                </div>
            </header>
            
            <main class="p-6">
                <!-- Stats cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <!-- Users card -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-purple-100 text-purple-500">
                                <i class="fas fa-users text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-gray-500 text-sm">Total Users</p>
                                <p class="text-2xl font-semibold"><?= $summary['users'] ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Products card -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100 text-blue-500">
                                <i class="fas fa-box text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-gray-500 text-sm">Total Products</p>
                                <p class="text-2xl font-semibold"><?= $summary['products'] ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Orders card -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100 text-green-500">
                                <i class="fas fa-shopping-cart text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-gray-500 text-sm">Total Orders</p>
                                <p class="text-2xl font-semibold"><?= $summary['orders'] ?></p>
                                <div class="flex text-xs mt-1">
                                    <span class="text-yellow-600 mr-2">
                                        <i class="fas fa-clock mr-1"></i><?= $summary['pending_orders'] ?> pending
                                    </span>
                                    <span class="text-green-600">
                                        <i class="fas fa-check mr-1"></i><?= $summary['completed_orders'] ?> completed
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    

                </div>
                
               
                    
                    <!-- Quick actions and top products -->
                    <div class="space-y-6">
                        <!-- Quick actions -->
                        <div class="bg-white rounded-lg shadow">
                            <div class="p-4 border-b border-gray-200">
                                <h3 class="text-lg font-semibold">Quick Actions</h3>
                            </div>
                            <div class="p-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <a href="<?= BASE_URL ?>admin/manage-products/add" class="flex items-center p-3 bg-blue-50 hover:bg-blue-100 rounded-lg">
                                        <div class="p-2 bg-blue-100 rounded-full mr-3">
                                            <i class="fas fa-box text-blue-500"></i>
                                        </div>
                                        <span>Add New Product</span>
                                    </a>
                                    <a href="<?= BASE_URL ?>admin/manage-categories" class="flex items-center p-3 bg-purple-50 hover:bg-purple-100 rounded-lg">
                                        <div class="p-2 bg-purple-100 rounded-full mr-3">
                                            <i class="fas fa-tags text-purple-500"></i>
                                        </div>
                                        <span>Manage Categories</span>
                                    </a>
                                    <a href="<?= BASE_URL ?>admin/manage-users" class="flex items-center p-3 bg-green-50 hover:bg-green-100 rounded-lg">
                                        <div class="p-2 bg-green-100 rounded-full mr-3">
                                            <i class="fas fa-user-plus text-green-500"></i>
                                        </div>
                                        <span>Add New User</span>
                                    </a>
                                    <a href="<?= BASE_URL ?>admin/orders" class="flex items-center p-3 bg-yellow-50 hover:bg-yellow-100 rounded-lg">
                                        <div class="p-2 bg-yellow-100 rounded-full mr-3">
                                            <i class="fas fa-clipboard-list text-yellow-500"></i>
                                        </div>
                                        <span>View All Orders</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <!-- Recent orders -->
                    <div class="bg-white rounded-lg shadow">
                        <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                            <h3 class="text-lg font-semibold">Recent Orders</h3>
                            <a href="<?= BASE_URL ?>admin/orders" class="text-blue-500 hover:underline text-sm">View all</a>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php if (empty($recentOrders)): ?>
                                        <tr>
                                            <td colspan="4" class="px-4 py-4 text-center text-gray-500">No orders found</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($recentOrders as $order): ?>
                                            <tr>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <a href="<?= BASE_URL ?>admin/orders/view?id=<?= $order['id'] ?>" class="text-blue-600 hover:underline">
                                                        #<?= substr($order['id'], -6) ?>
                                                    </a>
                                                    <div class="text-xs text-gray-500"><?= date('M j', strtotime($order['order_date'])) ?></div>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <div class="text-sm"><?= $order['customer_name'] ?></div>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    $<?= number_format($order['total_price'], 2) ?>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap">
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
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <script>
        // Initialize sales chart
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('salesChart').getContext('2d');
            
            const salesData = <?= json_encode($salesData) ?>;
            const labels = salesData.map(item => item.name);
            const data = salesData.map(item => item.amount);
            
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Sales ($)',
                        data: data,
                        backgroundColor: 'rgba(59, 130, 246, 0.5)',
                        borderColor: 'rgb(59, 130, 246)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                // Format ticks as currency
                                callback: function(value) {
                                    return '$' + value;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>