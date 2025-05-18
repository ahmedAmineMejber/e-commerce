<?php

require_once 'header.php';
 ?>






  <!--
    - MAIN
  -->

  <main>

    <!--
      - BANNER
    -->

    <div class="banner">

      <div class="container">

        <div class="slider-container has-scrollbar">

        

          <div class="slider-item">

            <img src="./assets/images/banner-3.jpg" alt="new fashion summer sale" class="banner-img">

            <div class="banner-content">

              <p class="banner-subtitle">Sale Offer</p>

              <h2 class="banner-title">New fashion summer sale</h2>

              <p class="banner-text">
                starting at &dollar; <b>29</b>.99
              </p>

              <a href="#" class="banner-btn">Shop now</a>

            </div>

          </div>

        </div>

      </div>

    </div>


    <!--
      - PRODUCT
    -->

    <div class="product-container">

      <div class="container">


        <!--
          - SIDEBAR
        -->

        <div class="sidebar  has-scrollbar" data-mobile-menu>

        <?php require_once 'models/Category.php';
        $categoryModel = new Category();
        $category = isset($_GET['category']) ? $_GET['category'] : null;
        $categories = $categoryModel->getAllCategories();
        ?>

          <div class="sidebar-category">

            <div class="sidebar-top">
              <h2 class="sidebar-title">Category</h2>

              <button class="sidebar-close-btn" data-mobile-menu-close-btn>
                <ion-icon name="close-outline"></ion-icon>
              </button>
            </div>

            <ul class="sidebar-menu-category-list">
          <?php foreach ($categories as $cat): ?>
              <li class="sidebar-menu-category">

                <a class="sidebar-accordion-menu" data-accordion-btn 
                href="<?php echo BASE_URL; ?>products?category=<?php echo urlencode($cat['name']); ?>">

                  <div class="menu-title-flex">
                    <img src="./assets/images/icons/<?php echo $cat['icon']; ?>" alt="clothes" width="20" height="20"
                      class="menu-title-img">

                    <p class="menu-title">                  <?php echo $cat['name']; ?>                    </p>
                  </div>

                </a>


              </li>
              <?php endforeach;?>

             

            </ul>

          </div>

          <div class="product-showcase">

            <h3 class="showcase-heading">best sellers</h3>

            <div class="showcase-wrapper">

              <div class="showcase-container">

                <div class="showcase">

                  <a href="#" class="showcase-img-box">
                    <img src="./assets/images/products/1.jpg" alt="baby fabric shoes" width="75" height="75"
                      class="showcase-img">
                  </a>

                  <div class="showcase-content">

                    <a href="#">
                      <h4 class="showcase-title">baby fabric shoes</h4>
                    </a>

                    <div class="showcase-rating">
                      <ion-icon name="star"></ion-icon>
                      <ion-icon name="star"></ion-icon>
                      <ion-icon name="star"></ion-icon>
                      <ion-icon name="star"></ion-icon>
                      <ion-icon name="star"></ion-icon>
                    </div>

                    <div class="price-box">
                      <del>$5.00</del>
                      <p class="price">$4.00</p>
                    </div>

                  </div>

                </div>

                <div class="showcase">

                  <a href="#" class="showcase-img-box">
                    <img src="./assets/images/products/2.jpg" alt="men's hoodies t-shirt" class="showcase-img"
                      width="75" height="75">
                  </a>

                  <div class="showcase-content">

                    <a href="#">
                      <h4 class="showcase-title">men's hoodies t-shirt</h4>
                    </a>
                    <div class="showcase-rating">
                      <ion-icon name="star"></ion-icon>
                      <ion-icon name="star"></ion-icon>
                      <ion-icon name="star"></ion-icon>
                      <ion-icon name="star"></ion-icon>
                      <ion-icon name="star-half-outline"></ion-icon>
                    </div>

                    <div class="price-box">
                      <del>$17.00</del>
                      <p class="price">$7.00</p>
                    </div>

                  </div>

                </div>

                <div class="showcase">

                  <a href="#" class="showcase-img-box">
                    <img src="./assets/images/products/3.jpg" alt="girls t-shirt" class="showcase-img" width="75"
                      height="75">
                  </a>

                  <div class="showcase-content">

                    <a href="#">
                      <h4 class="showcase-title">girls t-shirt</h4>
                    </a>
                    <div class="showcase-rating">
                      <ion-icon name="star"></ion-icon>
                      <ion-icon name="star"></ion-icon>
                      <ion-icon name="star"></ion-icon>
                      <ion-icon name="star"></ion-icon>
                      <ion-icon name="star-half-outline"></ion-icon>
                    </div>

                    <div class="price-box">
                      <del>$5.00</del>
                      <p class="price">$3.00</p>
                    </div>

                  </div>

                </div>

                <div class="showcase">

                  <a href="#" class="showcase-img-box">
                    <img src="./assets/images/products/4.jpg" alt="woolen hat for men" class="showcase-img" width="75"
                      height="75">
                  </a>

                  <div class="showcase-content">

                    <a href="#">
                      <h4 class="showcase-title">woolen hat for men</h4>
                    </a>
                    <div class="showcase-rating">
                      <ion-icon name="star"></ion-icon>
                      <ion-icon name="star"></ion-icon>
                      <ion-icon name="star"></ion-icon>
                      <ion-icon name="star"></ion-icon>
                      <ion-icon name="star"></ion-icon>
                    </div>

                    <div class="price-box">
                      <del>$15.00</del>
                      <p class="price">$12.00</p>
                    </div>

                  </div>

                </div>

              </div>

            </div>

          </div>

        </div>



        <div class="product-box">

          <!--
            - PRODUCT MINIMAL
          -->

          <div class="product-minimal">

          




          </div>



          <!--
            - PRODUCT FEATURED
          -->

          <div class="product-featured">

            <h2 class="title">Deal of the day</h2>

            <div class="showcase-wrapper has-scrollbar">

              <div class="showcase-container">

                <div class="showcase">
                  
                  <div class="showcase-banner">
                    <img src="./assets/images/products/shampoo.jpg" alt="shampoo, conditioner & facewash packs" class="showcase-img">
                  </div>

                  <div class="showcase-content">
                    
                    <div class="showcase-rating">
                      <ion-icon name="star"></ion-icon>
                      <ion-icon name="star"></ion-icon>
                      <ion-icon name="star"></ion-icon>
                      <ion-icon name="star-outline"></ion-icon>
                      <ion-icon name="star-outline"></ion-icon>
                    </div>

                    <a href="#">
                      <h3 class="showcase-title">shampoo, conditioner & facewash packs</h3>
                    </a>

                    <p class="showcase-desc">
                      Lorem ipsum dolor sit amet consectetur Lorem ipsum
                      dolor dolor sit amet consectetur Lorem ipsum dolor
                    </p>

                    <div class="price-box">
                      <p class="price">$150.00</p>

                      <del>$200.00</del>
                    </div>

                    <button class="add-cart-btn">add to cart</button>

                    <div class="showcase-status">
                      <div class="wrapper">
                        <p>
                          already sold: <b>20</b>
                        </p>

                        <p>
                          available: <b>40</b>
                        </p>
                      </div>

                      <div class="showcase-status-bar"></div>
                    </div>

                    <div class="countdown-box">

                      <p class="countdown-desc">
                        Hurry Up! Offer ends in:
                      </p>

                      <div class="countdown">

                        <div class="countdown-content">

                          <p class="display-number">360</p>

                          <p class="display-text">Days</p>

                        </div>

                        <div class="countdown-content">
                          <p class="display-number">24</p>
                          <p class="display-text">Hours</p>
                        </div>

                        <div class="countdown-content">
                          <p class="display-number">59</p>
                          <p class="display-text">Min</p>
                        </div>

                        <div class="countdown-content">
                          <p class="display-number">00</p>
                          <p class="display-text">Sec</p>
                        </div>

                      </div>

                    </div>

                  </div>

                </div>

              </div>

              <div class="showcase-container">
              
                <div class="showcase">
              
                  <div class="showcase-banner">
                    <img src="./assets/images/products/jewellery-1.jpg" alt="Rose Gold diamonds Earring" class="showcase-img">
                  </div>
              
                  <div class="showcase-content">
              
                    <div class="showcase-rating">
                      <ion-icon name="star"></ion-icon>
                      <ion-icon name="star"></ion-icon>
                      <ion-icon name="star"></ion-icon>
                      <ion-icon name="star-outline"></ion-icon>
                      <ion-icon name="star-outline"></ion-icon>
                    </div>
              
                    <h3 class="showcase-title">
                      <a href="#" class="showcase-title">Rose Gold diamonds Earring</a>
                    </h3>
              
                    <p class="showcase-desc">
                      Lorem ipsum dolor sit amet consectetur Lorem ipsum
                      dolor dolor sit amet consectetur Lorem ipsum dolor
                    </p>
              
                    <div class="price-box">
                      <p class="price">$1990.00</p>
                      <del>$2000.00</del>
                    </div>
              
                    <button class="add-cart-btn">add to cart</button>
              
                    <div class="showcase-status">
                      <div class="wrapper">
                        <p> already sold: <b>15</b> </p>
              
                        <p> available: <b>40</b> </p>
                      </div>
              
                      <div class="showcase-status-bar"></div>
                    </div>
              
                    <div class="countdown-box">
              
                      <p class="countdown-desc">Hurry Up! Offer ends in:</p>
              
                      <div class="countdown">
                        <div class="countdown-content">
                          <p class="display-number">360</p>
                          <p class="display-text">Days</p>
                        </div>
              
                        <div class="countdown-content">
                          <p class="display-number">24</p>
                          <p class="display-text">Hours</p>
                        </div>
              
                        <div class="countdown-content">
                          <p class="display-number">59</p>
                          <p class="display-text">Min</p>
                        </div>
              
                        <div class="countdown-content">
                          <p class="display-number">00</p>
                          <p class="display-text">Sec</p>
                        </div>
                      </div>
              
                    </div>
              
                  </div>
              
                </div>
              
              </div>

            </div>

          </div>



          <!--
            - PRODUCT GRID
          -->

          <div class="product-main">

            <h2 class="title">New Products</h2>
            <?php
    // Include the Product model and fetch featured products
    require_once 'models/Product.php';
    $productModel = new Product();
    $featuredProducts = $productModel->getFeaturedProducts(4);
    ?>
            <div class="product-grid">
            <?php foreach ($featuredProducts as $product): ?>
             

              <div class="showcase">
              
                <div class="showcase-banner">
                <img src="<?php echo BASE_URL; ?>assets/images/products/<?php echo htmlspecialchars($product['image']); ?>" 
                 alt="<?php echo htmlspecialchars($product['name']); ?>" 
                 class="product-img default" width="300">
            
            <!-- Hover image (using same image for demo) -->
            <img src="<?php echo BASE_URL; ?>assets/images/products/<?php echo htmlspecialchars($product['image']); ?>" 
                 alt="<?php echo htmlspecialchars($product['name']); ?>" 
                 class="product-img hover" width="300">
            

              
                  <p class="showcase-badge angle black">sale</p>
              <!-- Show Message (if any) -->
<?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-<?php echo $_SESSION['message']['type']; ?>">
        <?php echo $_SESSION['message']['text']; ?>
    </div>
    <?php unset($_SESSION['message']); ?>
<?php endif; ?>
                  <div class="showcase-actions">
    <!-- Add to Wishlist -->
    <form action="<?php echo BASE_URL; ?>wishlist_r.php" method="POST" style="display:inline;">
        <input type="hidden" name="action" value="add">
        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
        <button type="submit" class="btn-action">
            <ion-icon name="heart-outline"></ion-icon>
        </button>
    </form>

    <!-- View Product Details -->
    <a href="<?php echo BASE_URL; ?>product?id=<?php echo $product['id']; ?>" class="btn-action">
        <ion-icon name="eye-outline"></ion-icon>
    </a>

    <!-- Add to Cart -->
    <form action="<?php echo BASE_URL; ?>cart_r.php" method="POST" style="display:inline;">
        <input type="hidden" name="action" value="add">
        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
        <input type="hidden" name="quantity" value="1">
        <button type="submit" class="btn-action">
            <ion-icon name="bag-add-outline"></ion-icon>
        </button>
    </form>
</div>


                </div>
              
                <div class="showcase-content">
                  <a  href="<?php echo BASE_URL; ?>product?id=<?php echo $product['id']; ?>" class="showcase-category">
                  <?php echo $product['name']; ?>
                  </a>
              
                  <h3>
                    <a href="#" class="showcase-title"><?php echo $product['description']; ?></a>
                  </h3>
              

                  <div class="price-box">
                    <p class="price">$<?php echo number_format($product['price'], 2); ?></p>
                    <del><?php echo number_format($product['original_price'], 2); ?></del>
                  </div>

                </div>              
                <?php endforeach; ?>

              </div>

            </div>

          </div>

        </div>

      </div>

    </div>


    <div>

<div class="container">

  <div class="testimonials-box">

    <!--
      - TESTIMONIALS
    -->

    <div class="testimonial">

      <h2 class="title">testimonial</h2>

      <div class="testimonial-card">

        <img src="./assets/images/testimonial-1.jpg" alt="alan doe" class="testimonial-banner" width="80" height="80">

        <p class="testimonial-name">Alan Doe</p>

        <p class="testimonial-title">CEO & Founder Invision</p>

        <img src="./assets/images/icons/quotes.svg" alt="quotation" class="quotation-img" width="26">

        <p class="testimonial-desc">
          Lorem ipsum dolor sit amet consectetur Lorem ipsum
          dolor dolor sit amet.
        </p>

      </div>

    </div>



    <!--
      - CTA
    -->

    <div class="cta-container">

      <img src="./assets/images/cta-banner.jpg" alt="summer collection" class="cta-banner">

      <a href="#" class="cta-content">

        <p class="discount">25% Discount</p>

        <h2 class="cta-title">Summer collection</h2>

        <p class="cta-text">Starting @ $10</p>

        <button class="cta-btn">Shop now</button>

      </a>

    </div>



    <!--
      - SERVICE
    -->

    <div class="service">

      <h2 class="title">Our Services</h2>

      <div class="service-container">

        <a href="#" class="service-item">

          <div class="service-icon">
            <ion-icon name="boat-outline"></ion-icon>
          </div>

          <div class="service-content">

            <h3 class="service-title">Worldwide Delivery</h3>
            <p class="service-desc">For Order Over $100</p>

          </div>

        </a>

        <a href="#" class="service-item">
        
          <div class="service-icon">
            <ion-icon name="rocket-outline"></ion-icon>
          </div>
        
          <div class="service-content">
        
            <h3 class="service-title">Next Day delivery</h3>
            <p class="service-desc">UK Orders Only</p>
        
          </div>
        
        </a>

        <a href="#" class="service-item">
        
          <div class="service-icon">
            <ion-icon name="call-outline"></ion-icon>
          </div>
        
          <div class="service-content">
        
            <h3 class="service-title">Best Online Support</h3>
            <p class="service-desc">Hours: 8AM - 11PM</p>
        
          </div>
        
        </a>

        <a href="#" class="service-item">
        
          <div class="service-icon">
            <ion-icon name="arrow-undo-outline"></ion-icon>
          </div>
        
          <div class="service-content">
        
            <h3 class="service-title">Return Policy</h3>
            <p class="service-desc">Easy & Free Return</p>
        
          </div>
        
        </a>

        <a href="#" class="service-item">
        
          <div class="service-icon">
            <ion-icon name="ticket-outline"></ion-icon>
          </div>
        
          <div class="service-content">
        
            <h3 class="service-title">30% money back</h3>
            <p class="service-desc">For Order Over $100</p>
        
          </div>
        
        </a>

      </div>

    </div>

  </div>

</div>

</div>
  </main>


  <?php 

include 'footer.php';
?>
