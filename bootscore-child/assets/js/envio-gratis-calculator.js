/**
 * Calculadora de Envío Gratis - JavaScript
 * Hobby Toys - WooCommerce
 * 
 * @package HobbyToys
 * @version 1.0.0
 */

(function($) {
    'use strict';
    
    // ============================================
    // 1. VARIABLES GLOBALES
    // ============================================
    
    let currentZone = 'nacional';
    let currentPostalCode = '';
    
    
    // ============================================
    // 2. INICIALIZACIÓN
    // ============================================
    
    $(document).ready(function() {
        initShippingCalculator();
        loadSavedZone();
        bindEvents();
        
        // Actualizar cuando el carrito cambie
        $(document.body).on('updated_cart_totals', function() {
            updateProgressBar();
        });
        
        // Actualizar con fragmentos de WooCommerce
        $(document.body).on('wc_fragments_loaded wc_fragments_refreshed', function() {
            updateProgressBar();
        });
    });
    
    
    // ============================================
    // 3. INICIALIZACIÓN
    // ============================================
    
    function initShippingCalculator() {
        console.log('Shipping Calculator initialized');
        
        // Formatear input de código postal (solo números)
        $('#htPostalCode').on('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 4);
        });
    }
    
    
    // ============================================
    // 4. CARGAR ZONA GUARDADA
    // ============================================
    
    function loadSavedZone() {
        $.ajax({
            url: htShipping.ajaxurl,
            type: 'POST',
            data: {
                action: 'ht_get_saved_zone',
                nonce: htShipping.nonce
            },
            success: function(response) {
                if (response.success && response.data.has_saved_zone) {
                    currentZone = response.data.zone;
                    currentPostalCode = response.data.postal_code;
                    
                    // Actualizar barra de progreso si existe
                    updateProgressBar(response.data);
                    
                    console.log('Saved zone loaded:', currentZone);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading saved zone:', error);
            }
        });
    }
    
    
    // ============================================
    // 5. BIND EVENTS
    // ============================================
    
    function bindEvents() {
        // Submit del formulario
        $('#htShippingCalculatorForm').on('submit', function(e) {
            e.preventDefault();
            calculateShipping();
        });
        
        // Botón limpiar
        $('#htClearZoneBtn').on('click', function(e) {
            e.preventDefault();
            clearSavedZone();
        });
        
        // Al abrir el modal, enfocar input
        $('#htShippingCalculatorModal').on('shown.bs.modal', function() {
            $('#htPostalCode').focus();
        });

        // Al cerrar el modal, recargar la página si se calculó un nuevo CP
        let shippingCalculated = false;
        $('#htShippingCalculatorForm').on('submit', function() {
            shippingCalculated = true;
        });

        $('#htShippingCalculatorModal').on('hidden.bs.modal', function() {
            if (shippingCalculated) {
                // Recargar la página para actualizar todos los elementos
                location.reload();
            }
        });
    }
    
    
    // ============================================
    // 6. CALCULAR ENVÍO
    // ============================================
    
    function calculateShipping() {
        const postalCode = $('#htPostalCode').val().trim();
        const $form = $('#htShippingCalculatorForm');
        const $btn = $('#htCalculateBtn');
        const $result = $('#htShippingResult');
        
        // Validación básica
        if (postalCode.length !== 4) {
            showError('Por favor, ingresá un código postal válido de 4 dígitos');
            return;
        }
        
        // Deshabilitar botón y mostrar loading
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Calculando...');
        $result.hide();
        
        // AJAX Request
        $.ajax({
            url: htShipping.ajaxurl,
            type: 'POST',
            data: {
                action: 'ht_check_postal_code',
                nonce: htShipping.nonce,
                postal_code: postalCode
            },
            success: function(response) {
                if (response.success) {
                    currentZone = response.data.zone;
                    currentPostalCode = response.data.postal_code;
                    
                    showResult(response.data);
                    updateProgressBar(response.data);
                    
                    // Actualizar fragmentos del carrito
                    updateCartFragments();
                    
                    // Opcional: cerrar modal después de 3 segundos si califica para envío gratis
                    if (response.data.is_free) {
                        setTimeout(function() {
                            $('#htShippingCalculatorModal').modal('hide');
                        }, 3000);
                    }
                } else {
                    showError(response.data.message || 'Error al calcular el envío');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                showError('Error de conexión. Por favor, intentá nuevamente.');
            },
            complete: function() {
                // Restaurar botón
                $btn.prop('disabled', false).html('<i class="bi bi-calculator"></i> Calcular Envío');
            }
        });
    }
    
    
    // ============================================
    // 7. MOSTRAR RESULTADO
    // ============================================
    
    function showResult(data) {
        const $result = $('#htShippingResult');
        const $alert = $result.find('.alert');
        const $content = $result.find('.result-content');
        const $progressContainer = $result.find('.progress-container');
        const $progressBar = $progressContainer.find('.progress-bar');
        const $progressLabel = $progressContainer.find('.progress-label');
        const $progressPercentage = $progressContainer.find('.progress-percentage');
        
        // Configurar alerta según resultado
        if (data.is_free) {
            $alert.removeClass('alert-warning alert-info').addClass('alert-success');
            $content.html(`
                <div class="text-center">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">🎉</div>
                    <h5 class="fw-bold mb-2">¡Felicitaciones!</h5>
                    <p class="mb-2">Tu compra califica para <strong>envío gratis</strong></p>
                    <p class="mb-0 small">
                        <i class="bi bi-geo-alt-fill"></i> 
                        ${data.zone_name} (CP: ${data.postal_code})
                    </p>
                </div>
            `);
            
            // Mostrar progreso completo
            $progressContainer.show();
            $progressBar.css('width', '100%')
                        .removeClass('bg-warning')
                        .addClass('bg-success');
            $progressLabel.html('<i class="bi bi-check-circle-fill"></i> Objetivo alcanzado');
            $progressPercentage.text('100%');
            
        } else {
            $alert.removeClass('alert-success alert-info').addClass('alert-warning');
            
            const formattedRemaining = formatCurrency(data.remaining);
            const formattedThreshold = formatCurrency(data.threshold);
            const formattedCartTotal = formatCurrency(data.cart_total);
            
            $content.html(`
                <div class="text-center">
                    <div style="font-size: 2.5rem; margin-bottom: 1rem;">📦</div>
                    <h6 class="fw-bold mb-2">${data.zone_name}</h6>
                    <p class="mb-2">
                        Te faltan <strong class="text-danger">${formattedRemaining}</strong> 
                        para envío gratis
                    </p>
                    <p class="mb-0 small text-muted">
                        Tu carrito: ${formattedCartTotal} / ${formattedThreshold}
                    </p>
                </div>
            `);
            
            // Mostrar progreso
            $progressContainer.show();
            $progressBar.css('width', data.percentage + '%')
                        .removeClass('bg-success')
                        .addClass('bg-warning');
            $progressLabel.html('Progreso hacia envío gratis');
            $progressPercentage.text(Math.round(data.percentage) + '%');
        }
        
        // Mostrar resultado con animación
        $result.slideDown(300);
        
        // Scroll al resultado en mobile
        if ($(window).width() < 768) {
            setTimeout(function() {
                $result[0].scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }, 350);
        }
    }
    
    
    // ============================================
    // 8. MOSTRAR ERROR
    // ============================================
    
    function showError(message) {
        const $result = $('#htShippingResult');
        const $alert = $result.find('.alert');
        const $content = $result.find('.result-content');
        const $progressContainer = $result.find('.progress-container');
        
        $alert.removeClass('alert-success alert-warning').addClass('alert-danger');
        $content.html(`
            <div class="text-center">
                <i class="bi bi-exclamation-triangle-fill" style="font-size: 2rem; color: var(--bs-danger);"></i>
                <p class="mb-0 mt-2">${message}</p>
            </div>
        `);
        
        $progressContainer.hide();
        $result.slideDown(300);
    }
    
    
    // ============================================
    // 9. ACTUALIZAR BARRA DE PROGRESO
    // ============================================
    
    function updateProgressBar(data = null) {
        const $container = $('.ht-shipping-progress-bar-container');
        
        if (!$container.length) {
            return;
        }
        
        // Si no hay data, obtenerla del fragmento o del servidor
        if (!data) {
            const $fragment = $('.ht-shipping-progress-data');
            
            if ($fragment.length && $fragment.data('zone')) {
                data = {
                    zone: $fragment.data('zone'),
                    cart_total: parseFloat($fragment.data('cart-total')),
                    threshold: parseFloat($fragment.data('threshold')),
                    remaining: parseFloat($fragment.data('remaining')),
                    percentage: parseFloat($fragment.data('percentage')),
                    is_free: $fragment.data('is-free') === 'true'
                };
            } else {
                // Si no hay fragmento, obtener del servidor
                loadSavedZone();
                return;
            }
        }
        
        // Si no hay zona guardada, ocultar barra
        if (!currentPostalCode && !data.postal_code) {
            $container.find('.ht-shipping-progress-bar').hide();
            return;
        }
        
        // Actualizar elementos de la barra
        const $bar = $container.find('.ht-shipping-progress-bar');
        const $progressFill = $bar.find('.ht-progress-bar-fill');
        const $progressText = $bar.find('.ht-progress-text');
        
        if (!$bar.length) {
            return;
        }
        
        // Actualizar progreso
        $progressFill.css('width', Math.min(100, data.percentage) + '%');
        $progressFill.attr('data-percentage', Math.round(data.percentage));
        
        // Actualizar texto
        if (data.is_free) {
            $progressText.html(`
                <i class="bi bi-check-circle-fill text-success"></i>
                <strong>¡Envío gratis!</strong>
            `);
            $progressFill.addClass('is-free');
        } else {
            const formattedRemaining = formatCurrency(data.remaining);
            $progressText.html(`
                Te faltan <strong>${formattedRemaining}</strong> para envío gratis
            `);
            $progressFill.removeClass('is-free');
        }
        
        // Mostrar barra con animación
        $bar.slideDown(300);
    }
    
    
    // ============================================
    // 10. LIMPIAR ZONA GUARDADA
    // ============================================
    
    function clearSavedZone() {
        if (!confirm('¿Querés limpiar el código postal guardado?')) {
            return;
        }
        
        const $btn = $('#htClearZoneBtn');
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Limpiando...');
        
        $.ajax({
            url: htShipping.ajaxurl,
            type: 'POST',
            data: {
                action: 'ht_clear_saved_zone',
                nonce: htShipping.nonce
            },
            success: function(response) {
                if (response.success) {
                    // Limpiar variables
                    currentZone = 'nacional';
                    currentPostalCode = '';
                    
                    // Limpiar formulario
                    $('#htPostalCode').val('');
                    $('#htShippingResult').hide();
                    
                    // Ocultar barra de progreso
                    $('.ht-shipping-progress-bar-container .ht-shipping-progress-bar').slideUp(300);
                    
                    // Actualizar fragmentos del carrito
                    updateCartFragments();
                    
                    // Cerrar modal
                    $('#htShippingCalculatorModal').modal('hide');
                    
                    // Mostrar notificación
                    showNotification('Código postal limpiado correctamente', 'success');
                }
            },
            error: function() {
                showNotification('Error al limpiar el código postal', 'error');
            },
            complete: function() {
                $btn.prop('disabled', false).html('<i class="bi bi-x-circle"></i> Limpiar');
            }
        });
    }
    
    
    // ============================================
    // 11. ACTUALIZAR FRAGMENTOS DEL CARRITO
    // ============================================
    
    function updateCartFragments() {
        if (typeof wc_cart_fragments_params !== 'undefined') {
            // Trigger evento de actualización de fragmentos
            $(document.body).trigger('wc_fragment_refresh');
        }
    }
    
    
    // ============================================
    // 12. UTILIDADES
    // ============================================
    
    /**
     * Formatear moneda
     */
    function formatCurrency(amount) {
        return '$' + Math.round(amount).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }
    
    /**
     * Mostrar notificación
     */
    function showNotification(message, type = 'info') {
        // Si WooCommerce tiene sistema de notificaciones, usarlo
        if (typeof wc_add_notice !== 'undefined') {
            // WooCommerce notice
            const noticeClass = type === 'success' ? 'success' : (type === 'error' ? 'error' : 'info');
            const notice = `<div class="woocommerce-message ${noticeClass}">${message}</div>`;
            
            $('.woocommerce-notices-wrapper').first().html(notice);
            
            $('html, body').animate({
                scrollTop: $('.woocommerce-notices-wrapper').first().offset().top - 100
            }, 500);
            
            setTimeout(function() {
                $('.woocommerce-notices-wrapper .woocommerce-message').fadeOut(300, function() {
                    $(this).remove();
                });
            }, 4000);
            
        } else {
            // Fallback: alert simple
            alert(message);
        }
    }
    
    /**
     * Debug log
     */
    function log(message, data = null) {
        if (window.console && typeof console.log === 'function') {
            console.log('[Shipping Calculator]', message, data || '');
        }
    }
    
    
    // ============================================
    // 13. PÚBLICO API
    // ============================================
    
    // Exponer funciones públicas
    window.htShippingCalculator = {
        getCurrentZone: function() {
            return currentZone;
        },
        getCurrentPostalCode: function() {
            return currentPostalCode;
        },
        openModal: function() {
            $('#htShippingCalculatorModal').modal('show');
        },
        updateProgress: function() {
            updateProgressBar();
        }
    };
    
})(jQuery);