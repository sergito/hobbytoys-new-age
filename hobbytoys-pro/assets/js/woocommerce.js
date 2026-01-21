/**
 * HobbyToys Pro - WooCommerce JavaScript
 * Multi-step checkout and WooCommerce specific functionality
 *
 * @package HobbyToys_Pro
 */

(function ($) {
    'use strict';

    /**
     * Document Ready
     */
    $(document).ready(function () {

        // Initialize multi-step checkout
        if ($('body').hasClass('woocommerce-checkout')) {
            initMultiStepCheckout();
        }

        // Update cart count
        updateCartCount();

        // Product quantity buttons
        initQuantityButtons();

    });

    /**
     * ==========================================================================
     * MULTI-STEP CHECKOUT
     * ==========================================================================
     */

    /**
     * Initialize multi-step checkout
     */
    function initMultiStepCheckout() {
        let currentStep = 1;

        // Create step navigation buttons
        createStepNavigation();

        // Show current step
        showStep(currentStep);

        /**
         * Next step button click
         */
        $(document).on('click', '.btn-checkout-next:not([type="submit"])', function (e) {
            e.preventDefault();

            // Validate current step
            if (!validateStep(currentStep)) {
                return;
            }

            // Save data
            saveStepData(currentStep);

            // Go to next step
            if (currentStep < 4) {
                currentStep++;
                goToStep(currentStep);
            }
        });

        /**
         * Previous step button click
         */
        $(document).on('click', '.btn-checkout-prev', function (e) {
            e.preventDefault();

            if (currentStep > 1) {
                currentStep--;
                goToStep(currentStep);
            }
        });

        /**
         * Step indicator click (go to specific step)
         */
        $(document).on('click', '.step-item', function () {
            const step = parseInt($(this).data('step'));

            if (step <= currentStep) {
                currentStep = step;
                goToStep(currentStep);
            }
        });

        /**
         * Go to specific step
         */
        function goToStep(step) {
            $.ajax({
                url: hobbytoys_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'ht_checkout_goto_step',
                    nonce: hobbytoys_ajax.nonce,
                    step: step
                },
                success: function (response) {
                    if (response.success) {
                        showStep(step);
                        updateStepIndicator(step);
                        scrollToTop();
                    }
                }
            });
        }

        /**
         * Show specific step content
         */
        function showStep(step) {
            $('.checkout-step-content').addClass('step-content-hidden');
            $('.checkout-step-' + step).removeClass('step-content-hidden');

            // Update navigation buttons
            if (step === 1) {
                $('.btn-checkout-prev').hide();
            } else {
                $('.btn-checkout-prev').show();
            }

            if (step === 4) {
                $('.btn-checkout-next:not([type="submit"])').hide();
                $('.btn-checkout-next[type="submit"]').show();
            } else {
                $('.btn-checkout-next:not([type="submit"])').show();
                $('.btn-checkout-next[type="submit"]').hide();
            }
        }

        /**
         * Update step indicator UI
         */
        function updateStepIndicator(step) {
            $('.step-item').each(function () {
                const $item = $(this);
                const itemStep = parseInt($item.data('step'));

                $item.removeClass('active completed');

                if (itemStep === step) {
                    $item.addClass('active');
                } else if (itemStep < step) {
                    $item.addClass('completed');
                }
            });

            $('.step-connector').each(function (index) {
                if (index < step - 1) {
                    $(this).addClass('completed');
                } else {
                    $(this).removeClass('completed');
                }
            });
        }

        /**
         * Validate step before proceeding
         */
        function validateStep(step) {
            let isValid = true;

            switch (step) {
                case 1: // Cart
                    // Cart validation (if needed)
                    break;

                case 2: // Shipping
                    // Validate shipping fields
                    const requiredShipping = [
                        'billing_first_name',
                        'billing_last_name',
                        'billing_email',
                        'billing_phone',
                        'shipping_address_1',
                        'shipping_city',
                        'shipping_state',
                        'shipping_postcode'
                    ];

                    isValid = validateFields(requiredShipping);
                    break;

                case 3: // Payment
                    // Validate payment method selected
                    if (!$('input[name="payment_method"]:checked').length) {
                        alert('Por favor seleccioná un método de pago');
                        isValid = false;
                    }
                    break;
            }

            return isValid;
        }

        /**
         * Validate specific fields
         */
        function validateFields(fieldNames) {
            let isValid = true;

            fieldNames.forEach(function (fieldName) {
                const $field = $('[name="' + fieldName + '"]');

                if ($field.length && !$field.val().trim()) {
                    $field.addClass('error');
                    isValid = false;
                } else {
                    $field.removeClass('error');
                }
            });

            if (!isValid) {
                alert('Por favor completá todos los campos requeridos');
            }

            return isValid;
        }

        /**
         * Save step data to session
         */
        function saveStepData(step) {
            const $form = $('.woocommerce-checkout');
            const formData = $form.serialize();

            $.ajax({
                url: hobbytoys_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'ht_checkout_next_step',
                    nonce: hobbytoys_ajax.nonce,
                    form_data: formData
                }
            });
        }

        /**
         * Create step navigation HTML
         */
        function createStepNavigation() {
            const $navigation = $(`
                <div class="checkout-step-navigation">
                    <button type="button" class="btn btn-checkout-prev">
                        <i class="bi bi-chevron-left me-2"></i>
                        Anterior
                    </button>
                    <button type="button" class="btn btn-checkout-next">
                        Siguiente
                        <i class="bi bi-chevron-right ms-2"></i>
                    </button>
                    <button type="submit" class="btn btn-checkout-next" style="display:none;">
                        Finalizar Compra
                        <i class="bi bi-check-lg ms-2"></i>
                    </button>
                </div>
            `);

            $('.woocommerce-checkout').append($navigation);
        }

        /**
         * Scroll to top of checkout
         */
        function scrollToTop() {
            $('html, body').animate({
                scrollTop: $('.ht-checkout-steps').offset().top - 100
            }, 400);
        }
    }

    /**
     * ==========================================================================
     * CART FUNCTIONALITY
     * ==========================================================================
     */

    /**
     * Update cart count in header
     */
    function updateCartCount() {
        $.ajax({
            url: hobbytoys_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'ht_get_cart_count',
                nonce: hobbytoys_ajax.nonce
            },
            success: function (response) {
                if (response.count !== undefined) {
                    $('.cart-count').text(response.count);

                    if (response.count > 0) {
                        $('.cart-count').show();
                    } else {
                        $('.cart-count').hide();
                    }
                }
            }
        });
    }

    /**
     * Update cart on product added
     */
    $(document.body).on('added_to_cart', function () {
        updateCartCount();
    });

    /**
     * ==========================================================================
     * PRODUCT QUANTITY BUTTONS
     * ==========================================================================
     */

    /**
     * Initialize quantity +/- buttons
     */
    function initQuantityButtons() {
        // Add quantity buttons if they don't exist
        $('input.qty').each(function () {
            const $input = $(this);

            if ($input.parent().find('.qty-btn').length) {
                return; // Already initialized
            }

            const $minus = $('<button type="button" class="qty-btn qty-minus"><i class="bi bi-dash"></i></button>');
            const $plus = $('<button type="button" class="qty-btn qty-plus"><i class="bi bi-plus"></i></button>');

            $input.before($minus).after($plus);

            // Wrap in container
            $input.parent().addClass('qty-container');
        });

        // Minus button
        $(document).on('click', '.qty-minus', function () {
            const $input = $(this).siblings('input.qty');
            const min = parseInt($input.attr('min')) || 1;
            let val = parseInt($input.val()) || min;

            if (val > min) {
                $input.val(val - 1).trigger('change');
            }
        });

        // Plus button
        $(document).on('click', '.qty-plus', function () {
            const $input = $(this).siblings('input.qty');
            const max = parseInt($input.attr('max')) || 999;
            let val = parseInt($input.val()) || 1;

            if (val < max) {
                $input.val(val + 1).trigger('change');
            }
        });
    }

    /**
     * ==========================================================================
     * SINGLE PRODUCT GALLERY
     * ==========================================================================
     */

    /**
     * Initialize product gallery
     */
    if ($('.woocommerce-product-gallery').length) {
        // WooCommerce native gallery is already initialized
        // Add any custom enhancements here
    }

})(jQuery);

/**
 * ==========================================================================
 * QUANTITY BUTTONS STYLES (inline)
 * ==========================================================================
 */
const qtyStyles = document.createElement('style');
qtyStyles.textContent = `
    .qty-container {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .qty-btn {
        width: 36px;
        height: 36px;
        border: 1px solid #dee2e6;
        background: #fff;
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 1rem;
        color: #6c757d;
    }

    .qty-btn:hover {
        background: #EE285B;
        border-color: #EE285B;
        color: #fff;
    }

    .qty-btn:active {
        transform: scale(0.95);
    }

    input.qty {
        width: 60px !important;
        text-align: center;
        border: 1px solid #dee2e6;
        border-radius: 0.5rem;
        padding: 0.5rem;
        font-weight: 600;
    }

    input.qty:focus {
        border-color: #EE285B;
        outline: none;
        box-shadow: 0 0 0 3px rgba(238, 40, 91, 0.1);
    }
`;
document.head.appendChild(qtyStyles);
