/**
 * HobbyToys - Main JavaScript
 * Interactive functionality for product cards
 */

(function() {
    'use strict';

    // ==========================================================================
    // WISHLIST FUNCTIONALITY
    // ==========================================================================

    const WISHLIST_KEY = 'hobbytoys_wishlist';
    let wishlist = JSON.parse(localStorage.getItem(WISHLIST_KEY)) || [];

    /**
     * Initialize wishlist on page load
     */
    function initWishlist() {
        updateWishlistUI();
        updateWishlistCount();

        // Add event listeners to all wishlist buttons
        document.querySelectorAll('.btn-wishlist').forEach(button => {
            button.addEventListener('click', handleWishlistClick);
        });
    }

    /**
     * Handle wishlist button click
     */
    function handleWishlistClick(e) {
        e.preventDefault();
        e.stopPropagation();

        const button = e.currentTarget;
        const productId = button.dataset.productId;

        if (!productId) return;

        const index = wishlist.indexOf(productId);

        if (index > -1) {
            // Remove from wishlist
            wishlist.splice(index, 1);
            button.classList.remove('active');
            showNotification('Eliminado de tu lista de deseos', 'info');
        } else {
            // Add to wishlist
            wishlist.push(productId);
            button.classList.add('active');

            // Animate heart
            button.classList.add('animate__animated', 'animate__heartBeat');
            setTimeout(() => {
                button.classList.remove('animate__animated', 'animate__heartBeat');
            }, 600);

            showNotification('¡Agregado a tu lista de deseos!', 'success');
        }

        // Save to localStorage
        localStorage.setItem(WISHLIST_KEY, JSON.stringify(wishlist));

        // Update UI
        updateWishlistUI();
        updateWishlistCount();
    }

    /**
     * Update wishlist button states
     */
    function updateWishlistUI() {
        document.querySelectorAll('.btn-wishlist').forEach(button => {
            const productId = button.dataset.productId;

            if (wishlist.includes(productId)) {
                button.classList.add('active');
            } else {
                button.classList.remove('active');
            }
        });
    }

    /**
     * Update wishlist counter in header
     */
    function updateWishlistCount() {
        const countElement = document.querySelector('.wishlist-count');
        if (countElement) {
            countElement.textContent = wishlist.length;

            if (wishlist.length === 0) {
                countElement.style.display = 'none';
            } else {
                countElement.style.display = 'flex';
            }
        }
    }

    // ==========================================================================
    // ADD TO CART FUNCTIONALITY
    // ==========================================================================

    let cartCount = 1; // Start with 1 item (as shown in design)

    /**
     * Initialize add to cart buttons
     */
    function initAddToCart() {
        document.querySelectorAll('.btn-add-to-cart').forEach(button => {
            button.addEventListener('click', handleAddToCart);
        });
    }

    /**
     * Handle add to cart click
     */
    function handleAddToCart(e) {
        const button = e.currentTarget;
        const productCard = button.closest('.product-card');
        const productTitle = productCard.querySelector('.product-card__title a').textContent;

        // Animate button
        button.classList.add('animate__animated', 'animate__pulse');

        setTimeout(() => {
            button.classList.remove('animate__animated', 'animate__pulse');

            // Update cart count
            cartCount++;
            updateCartCount();

            // Show notification
            showNotification(`"${productTitle}" agregado al carrito`, 'success');
        }, 300);
    }

    /**
     * Update cart counter in header
     */
    function updateCartCount() {
        const countElement = document.querySelector('.cart-count');
        if (countElement) {
            countElement.textContent = cartCount;

            // Animate count
            countElement.classList.add('animate__animated', 'animate__bounceIn');
            setTimeout(() => {
                countElement.classList.remove('animate__animated', 'animate__bounceIn');
            }, 600);
        }
    }

    // ==========================================================================
    // NOTIFICATIONS
    // ==========================================================================

    /**
     * Show notification toast
     */
    function showNotification(message, type = 'info') {
        const iconClass = {
            'success': 'bi-check-circle-fill',
            'error': 'bi-x-circle-fill',
            'warning': 'bi-exclamation-triangle-fill',
            'info': 'bi-info-circle-fill'
        }[type] || 'bi-info-circle-fill';

        const bgClass = {
            'success': 'bg-success',
            'error': 'bg-danger',
            'warning': 'bg-warning',
            'info': 'bg-info'
        }[type] || 'bg-info';

        const notification = document.createElement('div');
        notification.className = `notification ${bgClass} text-white`;
        notification.innerHTML = `
            <i class="bi ${iconClass} me-2"></i>
            <span>${message}</span>
        `;

        document.body.appendChild(notification);

        // Show with animation
        setTimeout(() => {
            notification.classList.add('show');
        }, 100);

        // Hide after 3 seconds
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 3000);
    }

    // ==========================================================================
    // SCROLL EFFECTS
    // ==========================================================================

    /**
     * Add scroll reveal effect to product cards
     */
    function initScrollEffects() {
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observe all product cards
        document.querySelectorAll('.product-card').forEach(card => {
            observer.observe(card);
        });
    }

    // ==========================================================================
    // PRODUCT TITLE FORMATTING
    // ==========================================================================

    /**
     * Convert product titles to title case
     */
    function formatProductTitles() {
        document.querySelectorAll('.product-card__title a').forEach(title => {
            const text = title.textContent;
            const titleCase = text.toLowerCase().replace(/(^|\s)\S/g, letter => {
                return letter.toUpperCase();
            });
            title.textContent = titleCase;
        });
    }

    // ==========================================================================
    // NAVBAR SCROLL BEHAVIOR
    // ==========================================================================

    let lastScroll = 0;

    function handleNavbarScroll() {
        const header = document.querySelector('.header');
        const currentScroll = window.pageYOffset;

        if (currentScroll > 100) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }

        lastScroll = currentScroll;
    }

    // ==========================================================================
    // PRICE FORMATTING
    // ==========================================================================

    /**
     * Format numbers as currency
     */
    function formatCurrency(number) {
        return '$' + number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    // ==========================================================================
    // INITIALIZE ON DOM READY
    // ==========================================================================

    document.addEventListener('DOMContentLoaded', function() {
        console.log('🎮 HobbyToys initialized!');

        // Initialize all features
        initWishlist();
        initAddToCart();
        initScrollEffects();
        formatProductTitles();

        // Add scroll listener
        window.addEventListener('scroll', handleNavbarScroll);

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    });

})();

// ==========================================================================
// INLINE STYLES FOR NOTIFICATIONS
// ==========================================================================

const notificationStyles = document.createElement('style');
notificationStyles.textContent = `
    .notification {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        padding: 1rem 1.5rem;
        border-radius: 0.75rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        z-index: 9999;
        display: flex;
        align-items: center;
        font-size: 0.9375rem;
        font-weight: 600;
        opacity: 0;
        transform: translateX(100%);
        transition: all 0.3s ease;
        max-width: 350px;
    }

    .notification.show {
        opacity: 1;
        transform: translateX(0);
    }

    .notification i {
        font-size: 1.25rem;
    }

    .header.scrolled {
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    @media (max-width: 767.98px) {
        .notification {
            bottom: 1rem;
            right: 1rem;
            left: 1rem;
            font-size: 0.875rem;
            max-width: none;
        }
    }
`;
document.head.appendChild(notificationStyles);
