/**
 * JavaScript para Sistema Mayorista - Hobby Toys
 * Guardar como: /assets/js/mayorista-enhancements.js
 */

jQuery(document).ready(function($) {
    
    // ============================================
    // 1. TOOLTIPS INFORMATIVOS PARA PRECIOS MAYORISTAS
    // ============================================
    
    // Solo para usuarios mayoristas
    if ($('body').hasClass('logged-in') && $('.badge-mayorista, .badge-mayorista-single').length) {
        
        // Agregar tooltip en precio
        $('.price').each(function() {
            const $price = $(this);
            
            if (!$price.find('.mayorista-tooltip').length) {
                $price.append('<span class="mayorista-tooltip" data-bs-toggle="tooltip" title="Precio exclusivo mayorista"><i class="bi bi-info-circle ms-1"></i></span>');
            }
        });
        
        // Inicializar tooltips de Bootstrap
        if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }
    }
    
    
    // ============================================
    // 2. CONFIRMACIÓN AL AGREGAR PRODUCTOS AL CARRITO
    // ============================================
    
    $(document).on('click', '.single_add_to_cart_button', function(e) {
        if (!$('body').hasClass('logged-in') || !$('.badge-mayorista-single').length) {
            return;
        }
        
        const $button = $(this);
        
        // Si ya confirmó, continuar
        if ($button.data('confirmed')) {
            return;
        }
        
        e.preventDefault();
        
        // Mostrar confirmación personalizada
        const productName = $('.product_title').text();
        const price = $('.price .amount').first().text();
        const quantity = $('input.qty').val() || 1;
        
        if (confirm(`¿Confirmar pedido mayorista?\n\nProducto: ${productName}\nCantidad: ${quantity}\nPrecio: ${price}\n\nEste producto se agregará a tu pedido mayorista.`)) {
            $button.data('confirmed', true);
            $button.trigger('click');
        }
    });
    
    
    // ============================================
    // 3. ACTUALIZACIÓN DINÁMICA DE TOTALES EN CARRITO
    // ============================================
    
    $(document.body).on('updated_cart_totals', function() {
        if ($('.badge-mayorista').length) {
            // Agregar nota mayorista en carrito
            if (!$('.mayorista-cart-notice').length) {
                $('.cart_totals').prepend(`
                    <div class="mayorista-cart-notice alert alert-warning mb-3" style="border-radius: 1rem; border: 2px solid #FFB900;">
                        <i class="bi bi-star-fill me-2"></i>
                        <strong>Pedido Mayorista:</strong> Los precios mostrados son precios especiales para tu cuenta.
                    </div>
                `);
            }
        }
    });
    
    
    // ============================================
    // 4. CALCULADORA DE DESCUENTO VS PRECIO REGULAR
    // ============================================
    
    // Solo en página de producto
    if ($('.badge-mayorista-single').length && $('.product_title').length) {
        
        // Agregar sección de información de ahorro
        const $priceContainer = $('.summary .price');
        
        if ($priceContainer.length) {
            $priceContainer.after(`
                <div class="mayorista-savings-info mt-3 p-3" style="background: linear-gradient(135deg, rgba(255, 185, 0, 0.1), rgba(255, 140, 0, 0.1)); border-radius: 1rem; border: 2px solid #FFB900;">
                    <h6 style="color: #FF8C00; font-weight: 700; margin-bottom: 0.5rem;">
                        <i class="bi bi-piggy-bank-fill me-2"></i>Beneficio Mayorista
                    </h6>
                    <p class="mb-1"><strong>Estás viendo el precio mayorista exclusivo.</strong></p>
                    <p class="mb-0 small text-muted">Este precio no está disponible para el público general.</p>
                </div>
            `);
        }
    }
    
    
    // ============================================
    // 5. PANEL MAYORISTA - GRÁFICO DE PEDIDOS
    // ============================================
    
    if ($('.mayorista-dashboard').length) {
        
        // Agregar animación de entrada a las tarjetas
        $('.stat-card, .info-card, .quick-link-card').each(function(index) {
            const $card = $(this);
            setTimeout(function() {
                $card.css({
                    'opacity': '0',
                    'transform': 'translateY(20px)'
                }).animate({
                    opacity: 1
                }, 600, function() {
                    $(this).css('transform', 'translateY(0)');
                });
            }, index * 100);
        });
        
        // Tooltip en estadísticas
        $('.stat-card h2').each(function() {
            $(this).attr('title', 'Actualizado en tiempo real');
        });
    }
    
    
    // ============================================
    // 6. FILTRO RÁPIDO EN TABLA DE PEDIDOS
    // ============================================
    
    if ($('.mayorista-orders-content table').length) {
        
        // Agregar campo de búsqueda
        $('h2').first().after(`
            <div class="orders-search mb-3">
                <input type="text" 
                       class="form-control" 
                       id="orderSearch" 
                       placeholder="Buscar por número de pedido, fecha o total..."
                       style="max-width: 400px;">
            </div>
        `);
        
        // Funcionalidad de búsqueda
        $('#orderSearch').on('keyup', function() {
            const value = $(this).val().toLowerCase();
            
            $('table tbody tr').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });
    }
    
    
    // ============================================
    // 7. ANIMACIONES PARA BADGES
    // ============================================
    
    // Hacer que los badges sean más llamativos al scroll
    $(window).on('scroll', function() {
        $('.badge-mayorista, .badge-mayorista-single').each(function() {
            const $badge = $(this);
            const elementTop = $badge.offset().top;
            const elementBottom = elementTop + $badge.outerHeight();
            const viewportTop = $(window).scrollTop();
            const viewportBottom = viewportTop + $(window).height();
            
            if (elementBottom > viewportTop && elementTop < viewportBottom) {
                $badge.addClass('in-view');
            }
        });
    });
    
    // CSS para la animación (agregar dinámicamente)
    $('<style>')
        .prop('type', 'text/css')
        .html(`
            .badge-mayorista.in-view,
            .badge-mayorista-single.in-view {
                animation: badge-highlight 2s ease-in-out;
            }
            
            @keyframes badge-highlight {
                0%, 100% { 
                    box-shadow: 0 4px 12px rgba(255, 185, 0, 0.4); 
                }
                50% { 
                    box-shadow: 0 8px 24px rgba(255, 185, 0, 0.8);
                    transform: scale(1.05);
                }
            }
            
            .mayorista-tooltip {
                cursor: help;
                color: #FFB900;
                font-size: 0.85em;
            }
        `)
        .appendTo('head');
    
    
    // ============================================
    // 8. CONTADOR DE PRODUCTOS EN PEDIDO
    // ============================================
    
    if ($('body').hasClass('woocommerce-cart') && $('.badge-mayorista').length) {
        
        // Contar items mayoristas
        let wholesaleItemCount = 0;
        let wholesaleTotal = 0;
        
        $('.cart-item').each(function() {
            // Verificar si tiene precio mayorista (simplificado)
            wholesaleItemCount++;
            const itemTotal = parseFloat($(this).find('.amount').last().text().replace(/[^0-9.-]+/g, ''));
            wholesaleTotal += itemTotal;
        });
        
        // Agregar resumen mayorista
        if (wholesaleItemCount > 0 && !$('.mayorista-summary').length) {
            $('.cart_totals').before(`
                <div class="mayorista-summary mb-4 p-4" style="background: linear-gradient(135deg, rgba(255, 185, 0, 0.1), rgba(255, 140, 0, 0.1)); border-radius: 1rem; border: 2px solid #FFB900;">
                    <h4 style="color: #FF8C00; font-weight: 700; margin-bottom: 1rem;">
                        <i class="bi bi-cart-check-fill me-2"></i>Resumen Pedido Mayorista
                    </h4>
                    <div class="row">
                        <div class="col-6">
                            <p class="mb-1"><strong>Productos:</strong></p>
                            <p class="h3 text-primary">${wholesaleItemCount}</p>
                        </div>
                        <div class="col-6">
                            <p class="mb-1"><strong>Subtotal:</strong></p>
                            <p class="h3 text-success">$${wholesaleTotal.toFixed(2)}</p>
                        </div>
                    </div>
                    <hr>
                    <p class="mb-0 small text-muted">
                        <i class="bi bi-info-circle me-1"></i>
                        Tu pedido será revisado por nuestro equipo antes de la confirmación final.
                    </p>
                </div>
            `);
        }
    }
    
    
    // ============================================
    // 9. COPIAR INFORMACIÓN DE PEDIDO
    // ============================================
    
    if ($('.woocommerce-order-overview').length && $('.badge-mayorista').length) {
        
        // Agregar botón para copiar información
        $('.woocommerce-order-overview').after(`
            <div class="text-center mt-3">
                <button type="button" class="btn btn-outline-secondary btn-sm copy-order-info">
                    <i class="bi bi-clipboard me-1"></i>Copiar Información del Pedido
                </button>
            </div>
        `);
        
        // Funcionalidad de copiar
        $('.copy-order-info').on('click', function() {
            const orderNumber = $('.woocommerce-order-overview__order strong').text();
            const orderDate = $('.woocommerce-order-overview__date strong').text();
            const orderTotal = $('.woocommerce-order-overview__total strong').text();
            
            const orderInfo = `
Pedido Mayorista - Hobby Toys
─────────────────────────────
Número: ${orderNumber}
Fecha: ${orderDate}
Total: ${orderTotal}
Estado: Procesando

¡Gracias por tu pedido!
            `.trim();
            
            // Copiar al portapapeles
            if (navigator.clipboard) {
                navigator.clipboard.writeText(orderInfo).then(function() {
                    // Cambiar texto del botón temporalmente
                    const $btn = $('.copy-order-info');
                    const originalText = $btn.html();
                    
                    $btn.html('<i class="bi bi-check-circle me-1"></i>¡Copiado!').addClass('btn-success');
                    
                    setTimeout(function() {
                        $btn.html(originalText).removeClass('btn-success');
                    }, 2000);
                });
            }
        });
    }
    
    
    // ============================================
    // 10. NOTIFICACIÓN DE BIENVENIDA MAYORISTA
    // ============================================
    
    // Solo en primera visita después de login
    if ($('body').hasClass('logged-in') && 
        $('.badge-mayorista').length && 
        !sessionStorage.getItem('mayorista_welcome_shown')) {
        
        // Mostrar notificación de bienvenida
        setTimeout(function() {
            if (!$('.mayorista-welcome-toast').length) {
                $('body').append(`
                    <div class="mayorista-welcome-toast" style="position: fixed; top: 80px; right: 20px; z-index: 9999; max-width: 350px;">
                        <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 1rem; border: 2px solid #28a745; box-shadow: 0 8px 24px rgba(0,0,0,0.2);">
                            <h5 class="alert-heading">
                                <i class="bi bi-star-fill me-2"></i>¡Bienvenido, Cliente Mayorista!
                            </h5>
                            <p class="mb-2">Estás viendo precios especiales exclusivos para tu cuenta.</p>
                            <hr>
                            <p class="mb-0 small">
                                <a href="/mi-cuenta/mayorista-dashboard/" class="alert-link">
                                    Ir a mi Panel Mayorista <i class="bi bi-arrow-right"></i>
                                </a>
                            </p>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                `);
                
                // Guardar en sessionStorage para no mostrar nuevamente
                sessionStorage.setItem('mayorista_welcome_shown', 'true');
                
                // Auto-cerrar después de 8 segundos
                setTimeout(function() {
                    $('.mayorista-welcome-toast .alert').fadeOut(function() {
                        $(this).parent().remove();
                    });
                }, 8000);
            }
        }, 1000);
    }
    
    
    // ============================================
    // 11. VALIDACIÓN ADICIONAL EN CHECKOUT
    // ============================================
    
    if ($('body').hasClass('woocommerce-checkout') && $('.mayorista-checkout-notice').length) {
        
        // Agregar campo de referencia interna (opcional)
        $('.woocommerce-billing-fields').append(`
            <p class="form-row form-row-wide mayorista-reference-field">
                <label for="billing_mayorista_reference">
                    Referencia Interna (Opcional)
                </label>
                <input type="text" 
                       class="input-text" 
                       name="billing_mayorista_reference" 
                       id="billing_mayorista_reference" 
                       placeholder="Ej: Orden de compra #123">
                <span class="description">Si tienes una referencia interna para este pedido, ingrésala aquí.</span>
            </p>
        `);
        
        // Resaltar que es pedido mayorista
        $('#place_order').before(`
            <div class="mayorista-checkout-reminder mt-3 mb-3 text-center p-3" style="background: rgba(255, 185, 0, 0.1); border-radius: 1rem;">
                <i class="bi bi-info-circle-fill me-2" style="color: #FFB900;"></i>
                <strong>Este es un pedido mayorista.</strong> Recibirás confirmación por email.
            </div>
        `);
    }
    
    
    console.log('✓ Sistema Mayorista cargado correctamente');
    
});