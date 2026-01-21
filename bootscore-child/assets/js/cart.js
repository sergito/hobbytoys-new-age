jQuery(document).ready(function($) {
    
    // Actualización automática de cantidad
    let updateCartTimer;
    $('.qty').on('change', function() {
        clearTimeout(updateCartTimer);
        updateCartTimer = setTimeout(function() {
            $('[name="update_cart"]').trigger('click');
        }, 1000);
    });

    // Animación al agregar producto
    $(document.body).on('added_to_cart', function() {
        // Actualizar contador del carrito
        updateCartCount();
        
        // Mostrar notificación
        showCartNotification();
    });

    // Actualizar contador del carrito
    function updateCartCount() {
        $.ajax({
            url: wc_cart_fragments_params.ajax_url,
            type: 'POST',
            data: {
                action: 'get_cart_count'
            },
            success: function(response) {
                if (response) {
                    $('.cart-items-count').text(response);
                }
            }
        });
    }

    // Notificación al agregar al carrito
    function showCartNotification() {
        const notification = $('<div class="cart-notification">').html(`
            <i class="bi bi-check-circle-fill me-2"></i>
            <span>Producto agregado al carrito</span>
        `);
        
        $('body').append(notification);
        
        setTimeout(function() {
            notification.addClass('show');
        }, 100);
        
        setTimeout(function() {
            notification.removeClass('show');
            setTimeout(function() {
                notification.remove();
            }, 300);
        }, 3000);
    }

    // Confirmación antes de eliminar
    $('.remove-item').on('click', function(e) {
        if (!confirm('¿Estás seguro de que querés eliminar este producto del carrito?')) {
            e.preventDefault();
            return false;
        }
    });

    // Highlight del item actualizado
    $(document.body).on('updated_cart_totals', function() {
        $('.cart-item-card').addClass('item-updated');
        setTimeout(function() {
            $('.cart-item-card').removeClass('item-updated');
        }, 1000);
    });

    // Actualizar barra de progreso de envío gratis
    function updateShippingProgress() {
        const cartTotal = parseFloat($('.cart-subtotal .amount').text().replace(/[^0-9.-]+/g, ''));
        const freeShippingMin = 90000;
        const percentage = Math.min(100, (cartTotal / freeShippingMin) * 100);
        const remaining = Math.max(0, freeShippingMin - cartTotal);
        
        $('.progress-bar').css('width', percentage + '%');
        
        if (remaining > 0) {
            $('.shipping-text strong').text('¡Estás cerca del envío gratis!');
            $('.badge.bg-success').text('Faltan $' + remaining.toLocaleString('es-AR'));
        } else {
            $('.free-shipping-progress').replaceWith(`
                <div class="alert alert-success mb-4" style="border-left: 4px solid #28a745;">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <strong>¡Genial! Tenés envío gratis</strong>
                </div>
            `);
        }
    }

    // Validación de cupones
    $('form.woocommerce-cart-form').on('submit', function(e) {
        const couponCode = $('#coupon_code').val();
        
        if ($(e.originalEvent.submitter).attr('name') === 'apply_coupon') {
            if (!couponCode || couponCode.trim() === '') {
                e.preventDefault();
                alert('Por favor ingresá un código de cupón');
                $('#coupon_code').focus();
                return false;
            }
        }
    });

    // Smooth scroll a mensajes de error
    if ($('.woocommerce-error, .woocommerce-message').length) {
        $('html, body').animate({
            scrollTop: $('.woocommerce-error, .woocommerce-message').offset().top - 100
        }, 500);
    }

    // Sticky summary responsive
    function handleStickySummary() {
        if ($(window).width() > 991) {
            const summaryOffset = $('.cart-summary-wrapper').offset().top;
            const scrollTop = $(window).scrollTop();
            
            if (scrollTop > summaryOffset - 20) {
                $('.cart-summary-card').addClass('is-sticky');
            } else {
                $('.cart-summary-card').removeClass('is-sticky');
            }
        }
    }

    $(window).on('scroll', handleStickySummary);
    $(window).on('resize', handleStickySummary);

    // Tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();
});