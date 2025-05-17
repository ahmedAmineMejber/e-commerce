'use strict';

// modal variables
const modal = document.querySelector('[data-modal]');
const modalCloseBtn = document.querySelector('[data-modal-close]');
const modalCloseOverlay = document.querySelector('[data-modal-overlay]');

// modal function
const modalCloseFunc = function () { modal.classList.add('closed') }

// modal eventListener
modalCloseOverlay.addEventListener('click', modalCloseFunc);
modalCloseBtn.addEventListener('click', modalCloseFunc);





// notification toast variables
const notificationToast = document.querySelector('[data-toast]');
const toastCloseBtn = document.querySelector('[data-toast-close]');

// notification toast eventListener
toastCloseBtn.addEventListener('click', function () {
  notificationToast.classList.add('closed');
});





// mobile menu variables
const mobileMenuOpenBtn = document.querySelectorAll('[data-mobile-menu-open-btn]');
const mobileMenu = document.querySelectorAll('[data-mobile-menu]');
const mobileMenuCloseBtn = document.querySelectorAll('[data-mobile-menu-close-btn]');
const overlay = document.querySelector('[data-overlay]');

for (let i = 0; i < mobileMenuOpenBtn.length; i++) {

  // mobile menu function
  const mobileMenuCloseFunc = function () {
    mobileMenu[i].classList.remove('active');
    overlay.classList.remove('active');
  }

  mobileMenuOpenBtn[i].addEventListener('click', function () {
    mobileMenu[i].classList.add('active');
    overlay.classList.add('active');
  });

  mobileMenuCloseBtn[i].addEventListener('click', mobileMenuCloseFunc);
  overlay.addEventListener('click', mobileMenuCloseFunc);

}





// accordion variables
const accordionBtn = document.querySelectorAll('[data-accordion-btn]');
const accordion = document.querySelectorAll('[data-accordion]');

for (let i = 0; i < accordionBtn.length; i++) {

  accordionBtn[i].addEventListener('click', function () {

    const clickedBtn = this.nextElementSibling.classList.contains('active');

    for (let i = 0; i < accordion.length; i++) {

      if (clickedBtn) break;

      if (accordion[i].classList.contains('active')) {

        accordion[i].classList.remove('active');
        accordionBtn[i].classList.remove('active');

      }

    }

    this.nextElementSibling.classList.toggle('active');
    this.classList.toggle('active');

  });

}// Product Interactions
document.addEventListener('DOMContentLoaded', function() {
  // Initialize tooltips
  tippy('[data-tippy-content]');
  
  // Add to cart functionality
  document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
      btn.addEventListener('click', function(e) {
          e.preventDefault();
          const productId = this.getAttribute('data-product-id');
          
          fetch(`${BASE_URL}cart/add`, {
              method: 'POST',
              headers: {
                  'Content-Type': 'application/json',
              },
              body: JSON.stringify({ product_id: productId })
          })
          .then(response => response.json())
          .then(data => {
              if (data.success) {
                  // Update cart count in header
                  document.querySelectorAll('.cart-count').forEach(el => {
                      el.textContent = data.cart_count;
                  });
                  
                  // Show success message
                  showToast('Product added to cart!', 'success');
              } else {
                  showToast(data.message, 'error');
              }
          })
          .catch(error => {
              console.error('Error:', error);
              showToast('An error occurred', 'error');
          });
      });
  });
  
  // Wishlist functionality
  document.querySelectorAll('.wishlist-btn').forEach(btn => {
      btn.addEventListener('click', function(e) {
          e.preventDefault();
          const productId = this.getAttribute('data-product-id');
          
          fetch(`${BASE_URL}wishlist/toggle`, {
              method: 'POST',
              headers: {
                  'Content-Type': 'application/json',
              },
              body: JSON.stringify({ product_id: productId })
          })
          .then(response => response.json())
          .then(data => {
              if (data.success) {
                  // Update wishlist count in header
                  document.querySelectorAll('.wishlist-count').forEach(el => {
                      el.textContent = data.wishlist_count;
                  });
                  
                  // Toggle heart icon
                  const icon = this.querySelector('ion-icon');
                  if (data.action === 'added') {
                      icon.setAttribute('name', 'heart');
                      showToast('Added to wishlist!', 'success');
                  } else {
                      icon.setAttribute('name', 'heart-outline');
                      showToast('Removed from wishlist', 'info');
                  }
              } else {
                  showToast(data.message, 'error');
              }
          })
          .catch(error => {
              console.error('Error:', error);
              showToast('An error occurred', 'error');
          });
      });
  });
});

// Toast notification function
function showToast(message, type = 'success') {
  const toast = document.createElement('div');
  toast.className = `toast toast-${type}`;
  toast.textContent = message;
  document.body.appendChild(toast);
  
  setTimeout(() => {
      toast.classList.add('show');
  }, 10);
  
  setTimeout(() => {
      toast.classList.remove('show');
      setTimeout(() => {
          toast.remove();
      }, 300);
  }, 3000);
}