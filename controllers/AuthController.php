
<?php
require_once 'config/database.php';
require_once 'models/User.php';

class AuthController {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new User();
    }
    
    public function register($userData) {
        // Validate email
        if (!filter_var($userData['email'], FILTER_VALIDATE_EMAIL)) {
            return [
                'success' => false,
                'message' => 'Invalid email format'
            ];
        }
        
        // Check if email already exists
        if ($this->userModel->emailExists($userData['email'])) {
            return [
                'success' => false,
                'message' => 'Email already in use'
            ];
        }
        
        // Hash password
        $userData['password'] = password_hash($userData['password'], PASSWORD_BCRYPT);
        
        // Generate unique ID
        $userData['id'] = uniqid('user_');
        
        // Set default role
        $userData['role'] = 'client';
        
        // Register user
        $result = $this->userModel->create($userData);
        
        if ($result) {
            return [
                'success' => true,
                'message' => 'Registration successful'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Registration failed. Please try again.'
            ];
        }
    }
    
    public function login($email, $password) {
        // Validate input
        if (empty($email) || empty($password)) {
            return [
                'success' => false,
                'message' => 'Please enter both email and password'
            ];
        }
        
        // Get user by email
        $user = $this->userModel->findByEmail($email);
        
        if (!$user) {
            return [
                'success' => false,
                'message' => 'Invalid email or password'
            ];
        }
        
        // Verify password
        if (!password_verify($password, $user['password'])) {
            return [
                'success' => false,
                'message' => 'Invalid email or password'
            ];
        }
        
        // Start session and set user data
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['user_image'] = $user['image'];
        
        // Get cart and wishlist count
        require_once 'models/Cart.php';
        require_once 'models/Wishlist.php';
        
        $cartModel = new Cart();
        $wishlistModel = new Wishlist();
        
        $_SESSION['cart_count'] = $cartModel->getCountByUser($user['id']);
        $_SESSION['wishlist_count'] = $wishlistModel->getCountByUser($user['id']);
        
        return [
            'success' => true,
            'user' => [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'role' => $user['role'],
                'image' => $user['image']
            ]
        ];
    }
    
    public function logout() {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Unset all session variables
        $_SESSION = [];
        
        // Destroy the session
        session_destroy();
        
        return [
            'success' => true,
            'message' => 'Logged out successfully'
        ];
    }
    
    public function isLoggedIn() {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        return isset($_SESSION['user_id']);
    }
    
    public function isAdmin() {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
    }
    
    public function getCurrentUser() {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user_id'])) {
            return null;
        }
        
        return [
            'id' => $_SESSION['user_id'],
            'name' => $_SESSION['user_name'],
            'email' => $_SESSION['user_email'],
            'role' => $_SESSION['user_role'],
            'image' => $_SESSION['user_image']
        ];
    }
}
?>
