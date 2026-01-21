/**
 * HobbyToys Pro - Main JavaScript
 * Core functionality for theme interactions
 *
 * @package HobbyToys_Pro
 */

(function ($) {
    'use strict';

    /**
     * Document Ready
     */
    $(document).ready(function () {

        // Initialize components
        initTooltips();
        initWishlist();
        initScrollEffects();
        initProductTitles();

    });

    /**
     * ==========================================================================
     * BOOTSTRAP COMPONENTS
     * ==========================================================================
     */

    /**
     * Initialize Bootstrap tooltips
     */
    function initTooltips() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }

    /**
     * ==========================================================================
     * WISHLIST FUNCTIONALITY
     * ==========================================================================
     */

    /**
     * Initialize wishlist
     */
    function initWishlist() {
        const STORAGE_KEY = 'ht_wishlist';

        // Load wishlist from localStorage
        let wishlist = JSON.parse(localStorage.getItem(STORAGE_KEY)) || [];

        // Update wishlist buttons on page load
        updateWishlistButtons();

        // Add to wishlist event
        $(document).on('click', '.btn-wishlist', function (e) {
            e.preventDefault();

            const $btn = $(this);
            const productId = $btn.data('product-id');

            if (!productId) return;

            const index = wishlist.indexOf(productId);

            if (index > -1) {
                // Remove from wishlist
                wishlist.splice(index, 1);
                $btn.find('i').removeClass('bi-heart-fill').addClass('bi-heart');
                showNotification('Eliminado de tu lista de deseos', 'info');
            } else {
                // Add to wishlist
                wishlist.push(productId);
                $btn.find('i').removeClass('bi-heart').addClass('bi-heart-fill');
                $btn.addClass('animate__animated animate__heartBeat');

                setTimeout(function () {
                    $btn.removeClass('animate__animated animate__heartBeat');
                }, 1000);

                showNotification('¡Agregado a tu lista de deseos!', 'success');
            }

            // Save to localStorage
            localStorage.setItem(STORAGE_KEY, JSON.stringify(wishlist));

            // Update UI
            updateWishlistButtons();
        });

        /**
         * Update wishlist button states
         */
        function updateWishlistButtons() {
            $('.btn-wishlist').each(function () {
                const $btn = $(this);
                const productId = $btn.data('product-id');

                if (wishlist.indexOf(productId) > -1) {
                    $btn.find('i').removeClass('bi-heart').addClass('bi-heart-fill');
                } else {
                    $btn.find('i').removeClass('bi-heart-fill').addClass('bi-heart');
                }
            });
        }
    }

    /**
     * ==========================================================================
     * SCROLL EFFECTS
     * ==========================================================================
     */

    /**
     * Initialize scroll effects
     */
    function initScrollEffects() {
        // Animate elements on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observe product cards
        document.querySelectorAll('.ht-product-card').forEach(function (card) {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            observer.observe(card);
        });
    }

    /**
     * ==========================================================================
     * PRODUCT TITLES
     * ==========================================================================
     */

    /**
     * Convert product titles to title case
     */
    function initProductTitles() {
        $('.ht-product-card__title, .woocommerce-loop-product__title').each(function () {
            const $title = $(this);
            const text = $title.text();
            const titleCase = text.toLowerCase().replace(/(^|\s)\S/g, function (letter) {
                return letter.toUpperCase();
            });
            $title.text(titleCase);
        });
    }

    /**
     * ==========================================================================
     * NOTIFICATIONS
     * ==========================================================================
     */

    /**
     * Show notification toast
     */
    function showNotification(message, type) {
        type = type || 'info';

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

        const $notification = $(`
            <div class="ht-notification ${bgClass} text-white">
                <i class="bi ${iconClass} me-2"></i>
                <span>${message}</span>
            </div>
        `);

        // Add to body
        $('body').append($notification);

        // Show with animation
        setTimeout(function () {
            $notification.addClass('show');
        }, 100);

        // Hide after 3 seconds
        setTimeout(function () {
            $notification.removeClass('show');
            setTimeout(function () {
                $notification.remove();
            }, 300);
        }, 3000);
    }

    /**
     * ==========================================================================
     * SHIPPING CALCULATOR
     * ==========================================================================
     */

    /**
     * Calculate shipping
     */
    $(document).on('click', '#calculate-shipping', function () {
        const zipCode = $('#shipping-zip').val().trim();

        if (!zipCode) {
            showNotification('Por favor ingresá tu código postal', 'warning');
            return;
        }

        if (zipCode.length < 4) {
            showNotification('Código postal inválido', 'error');
            return;
        }

        // Show loading
        const $btn = $(this);
        const originalText = $btn.html();
        $btn.html('<i class="bi bi-hourglass-split me-1"></i>Calculando...').prop('disabled', true);

        // Simulate AJAX call (replace with real implementation)
        setTimeout(function () {
            const $results = $('#shipping-results');
            $results.html(`
                <div class="alert alert-success mb-0">
                    <i class="bi bi-truck me-2"></i>
                    <strong>Envío estándar:</strong> $1.500 (5-7 días hábiles)
                </div>
            `);

            $btn.html(originalText).prop('disabled', false);
            showNotification('Costo de envío calculado', 'success');
        }, 1500);
    });

    /**
     * ==========================================================================
     * UTILITIES
     * ==========================================================================
     */

    /**
     * Format number as currency
     */
    function formatCurrency(number) {
        return '$' + number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    /**
     * Debounce function
     */
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

})(jQuery);

/**
 * ==========================================================================
 * NOTIFICATION STYLES (inline)
 * ==========================================================================
 */
const notificationStyles = document.createElement('style');
notificationStyles.textContent = `
    .ht-notification {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        padding: 1rem 1.5rem;
        border-radius: 0.75rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        z-index: 9999;
        display: flex;
        align-items: center;
        font-size: 0.9375rem;
        font-weight: 600;
        opacity: 0;
        transform: translateX(100%);
        transition: all 0.3s ease;
    }

    .ht-notification.show {
        opacity: 1;
        transform: translateX(0);
    }

    @media (max-width: 767.98px) {
        .ht-notification {
            bottom: 1rem;
            right: 1rem;
            left: 1rem;
            font-size: 0.875rem;
        }
    }
`;
document.head.appendChild(notificationStyles);
