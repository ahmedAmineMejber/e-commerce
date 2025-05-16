
<?php 
include 'header.php';

// Check if form is submitted
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Include the Auth Controller
    require_once 'controllers/AuthController.php';
    $auth = new AuthController();
    
    // Validate form inputs
    if ($_POST['password'] !== $_POST['confirmPassword']) {
        $error = "Passwords don't match";
    } elseif (!isset($_POST['agreeToTerms'])) {
        $error = "Please agree to the Terms and Conditions to register";
    } else {
        // Process registration
        $userData = [
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'password' => $_POST['password'],
            // Default image
            'image' => 'assets/images/default-avatar.jpg'
        ];
        
        $result = $auth->register($userData);
        
        if ($result['success']) {
            // Redirect to login page
            header('Location: ' . BASE_URL . 'login?registered=true');
            exit;
        } else {
            $error = $result['message'];
        }
    }
}
?>

<main class="container py-12">
    <div class="form-card">
        <div class="form-title">
            <h1>Create an Account</h1>
            <p>Enter your details to sign up for a new account</p>
        </div>
        
        <?php if ($error): ?>
            <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="name" class="form-label">Full Name</label>
                <input
                    id="name"
                    name="name"
                    type="text"
                    placeholder="John Doe"
                    class="form-input"
                    required
                >
            </div>
            
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
            
            <div class="form-group">
                <label for="confirmPassword" class="form-label">Confirm Password</label>
                <input
                    id="confirmPassword"
                    name="confirmPassword"
                    type="password"
                    class="form-input"
                    required
                >
            </div>
            
            <div class="form-group">
                <div class="flex items-center">
                    <input
                        id="agreeToTerms"
                        name="agreeToTerms"
                        type="checkbox"
                        class="form-checkbox"
                        required
                    >
                    <label for="agreeToTerms" class="ml-2">
                        I agree to the
                        <a href="<?php echo BASE_URL; ?>terms" class="text-blue-600 hover:underline">
                            Terms of Service
                        </a>
                        and
                        <a href="<?php echo BASE_URL; ?>privacy" class="text-blue-600 hover:underline">
                            Privacy Policy
                        </a>
                    </label>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary w-full mb-4">
                Create Account
            </button>
            
            <div class="relative mb-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-200"></div>
                </div>
                <div class="relative flex justify-center text-xs uppercase">
                    <span class="bg-white px-2 text-gray-500">Or sign up with</span>
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
                Already have an account? 
                <a href="<?php echo BASE_URL; ?>login" class="text-blue-600 font-medium hover:underline">
                    Sign in
                </a>
            </p>
        </form>
    </div>
</main>

<?php include 'footer.php'; ?>
