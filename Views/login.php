
<?php 
if (!defined('BASE_URL')) {
    define('BASE_URL', '/ecommerce-pDb/');
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'views/includes/header.php';

// Check if form is submitted
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Include the Auth Controller
    require_once 'controllers/AuthController.php';
    $auth = new AuthController();
    
    // Process login
    $result = $auth->login($_POST['email'], $_POST['password']);
    
    if ($result['success']) {
        // Redirect to home page
        header('Location: ' . BASE_URL);
        exit;
    } else {
        $error = $result['message'];
    }
}
?>

<main class="container py-12">
    <div class="form-card">
        <div class="form-title">
            <h1>Login to Your Account</h1>
            <p>Enter your credentials to access your account</p>
        </div>
        
        <?php if ($error): ?>
            <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input
                    id="email"
                    name="email"
                    type="email" 
                    placeholder="name@example.com"
                    class="form-input"
                    required
                >
            </div>
            
            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    class="form-input"
                    required
                >
            </div>
            
            <div class="flex justify-between items-center mb-6">
                <div>
                    <input type="checkbox" id="remember" class="form-checkbox">
                    <label for="remember">Remember me</label>
                </div>
                <a href="<?php echo BASE_URL; ?>forgot-password" class="text-blue-600 hover:underline">
                    Forgot Password?
                </a>
            </div>
            
            <button type="submit" class="btn btn-primary w-full mb-4">
                Sign In
            </button>
            
            <div class="relative mb-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-200"></div>
                </div>
                <div class="relative flex justify-center text-xs uppercase">
                    <span class="bg-white px-2 text-gray-500">Or sign in with</span>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4 mb-6">
                <button type="button" class="btn btn-outline flex justify-center items-center">
                    <i class="fab fa-google mr-2"></i> Google
                </button>
                <button type="button" class="btn btn-outline flex justify-center items-center">
                    <i class="fab fa-facebook-f mr-2"></i> Facebook
                </button>
            </div>
            
            <p class="text-center">
                Don't have an account? 
                <a href="<?php echo BASE_URL; ?>register" class="text-blue-600 font-medium hover:underline">
                    Sign up
                </a>
            </p>
        </form>
    </div>
</main>

<?php include 'footer.php'; ?>
