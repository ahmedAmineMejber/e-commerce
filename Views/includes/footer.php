
<footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <!-- About -->
                <div>
                    <h3 class="footer-title">ShopifyX</h3>
                    <p class="text-gray-400 mb-4">
                        Your one-stop destination for all your shopping needs with the best prices and quality products.
                    </p>
                    <div class="social-links">
                        <a href="#" class="text-gray-400 hover:text-white">
                            <i class="fab fa-facebook"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white">
                            <i class="fab fa-twitter"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h3 class="footer-title">Quick Links</h3>
                    <ul class="footer-list">
                        <li>
                            <a href="<?php echo BASE_URL; ?>">Home</a>
                        </li>
                        <li>
                            <a href="<?php echo BASE_URL; ?>products">Products</a>
                        </li>
                        <li>
                            <a href="<?php echo BASE_URL; ?>categories">Categories</a>
                        </li>
                        <li>
                            <a href="<?php echo BASE_URL; ?>cart">Cart</a>
                        </li>
                    </ul>
                </div>
                
                <!-- Customer Service -->
                <div>
                    <h3 class="footer-title">Customer Service</h3>
                    <ul class="footer-list">
                        <li>
                            <a href="<?php echo BASE_URL; ?>contact">Contact Us</a>
                        </li>
                        <li>
                            <a href="<?php echo BASE_URL; ?>faq">FAQs</a>
                        </li>
                        <li>
                            <a href="<?php echo BASE_URL; ?>shipping">Shipping Policy</a>
                        </li>
                        <li>
                            <a href="<?php echo BASE_URL; ?>returns">Returns & Refunds</a>
                        </li>
                    </ul>
                </div>
                
                <!-- Newsletter -->
                <div>
                    <h3 class="footer-title">Newsletter</h3>
                    <p class="text-gray-400 mb-4">
                        Subscribe to get updates on new products and special promotions.
                    </p>
                    <form class="newsletter-form">
                        <input
                            type="email"
                            placeholder="Your email address"
                            class="newsletter-input"
                        />
                        <button type="submit" class="newsletter-btn">
                            Subscribe
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> ShopifyX. All rights reserved.</p>
            </div>
        </div>
    </footer>
    
        <script src="<?php echo BASE_URL; ?>assets/js/script.js"></script>
        <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>
</html>
