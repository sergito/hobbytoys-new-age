/**
 * Calculador de Envíos Compacto - JavaScript
 * Hobby Toys WooCommerce
 * 
 * @package HobbyToys
 * @version 1.0.0
 */

(function($) {
    'use strict';
    
    // Configuración de zonas de envío
    const SHIPPING_CONFIG = {
        laPlata: {
            minZip: 1900,
            maxZip: 1999,
            zoneName: 'La Plata',
            daysMin: 1,
            daysMax: 2,
            costBase: 2500,
            freeShippingMin: 90000
        },
        buenosAires: {
            minZip: 1000,
            maxZip: 9999,
            zoneName: 'Buenos Aires',
            daysMin: 3,
            daysMax: 5,
            costBase: 3500,
            freeShippingMin: 90000
        },
        nacional: {
            minZip: 0,
            maxZip: 9999,
            zoneName: 'Argentina',
            daysMin: 5,
            daysMax: 10,
            costBase: 5000,
            freeShippingMin: 90000
        }
    };
    
    /**
     * Determinar zona según código postal
     */
    function getShippingZone(zipCode) {
        const zip = parseInt(zipCode);
        
        if (zip >= SHIPPING_CONFIG.laPlata.minZip && zip <= SHIPPING_CONFIG.laPlata.maxZip) {
            return SHIPPING_CONFIG.laPlata;
        } else if (zip >= SHIPPING_CONFIG.buenosAires.minZip && zip <= SHIPPING_CONFIG.buenosAires.maxZip) {
            return SHIPPING_CONFIG.buenosAires;
        } else {
            return SHIPPING_CONFIG.nacional;
        }
    }
    
    /**
     * Calcular fecha de entrega
     */
    function calculateDeliveryDate(daysMin, daysMax) {
        const today = new Date();
        const deliveryMin = new Date(today);
        const deliveryMax = new Date(today);
        
        // Sumar días (ignorando fines de semana por simplicidad)
        let addedDays = 0;
        while (addedDays < daysMin) {
            deliveryMin.setDate(deliveryMin.getDate() + 1);
            if (deliveryMin.getDay() !== 0 && deliveryMin.getDay() !== 6) {
                addedDays++;
            }
        }
        
        addedDays = 0;
        while (addedDays < daysMax) {
            deliveryMax.setDate(deliveryMax.getDate() + 1);
            if (deliveryMax.getDay() !== 0 && deliveryMax.getDay() !== 6) {
                addedDays++;
            }
        }
        
        return {
            min: deliveryMin,
            max: deliveryMax
        };
    }
    
    /**
     * Formatear fecha en español
     */
    function formatDate(date) {
        const days = ['domingo', 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado'];
        const months = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 
                       'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
        
        const dayName = days[date.getDay()];
        const day = date.getDate();
        const month = months[date.getMonth()];
        
        return `${dayName} ${day} de ${month}`;
    }
    
    /**
     * Generar HTML del resultado
     */
    function generateResultHTML(zone, productPrice, deliveryDates) {
        const shippingCost = productPrice >= zone.freeShippingMin ? 0 : zone.costBase;
        const isFree = shippingCost === 0;
        
        let deliveryText;
        if (zone.daysMin === zone.daysMax) {
            deliveryText = `Llega el ${formatDate(deliveryDates.min)}`;
        } else {
            deliveryText = `Llega entre el ${formatDate(deliveryDates.min)} y ${formatDate(deliveryDates.max)}`;
        }
        
        const cssClass = isFree ? 'shipping-result-success' : 'shipping-result-warning';
        const icon = isFree ? 'bi-check-circle-fill text-success' : 'bi-truck text-warning';
        
        return `
            <div class="${cssClass}">
                <div class="d-flex align-items-start">
                    <i class="bi ${icon}" style="font-size: 1.3rem; margin-right: 0.75rem; margin-top: 0.1rem;"></i>
                    <div class="flex-grow-1">
                        <div class="fw-bold mb-1" style="font-size: 1rem; color: var(--text-dark);">
                            ${deliveryText}
                        </div>
                        <div class="shipping-cost" style="font-size: 0.95rem;">
                            ${isFree 
                                ? '<span class="text-success fw-bold"><i class="bi bi-gift-fill me-1"></i>¡Envío GRATIS!</span>'
                                : `<span style="color: var(--text-dark);">Costo de envío: <strong style="color: var(--primary-color);">$${shippingCost.toLocaleString('es-AR')}</strong></span>`
                            }
                        </div>
                        ${!isFree ? `
                            <div class="mt-2 small" style="color: #666;">
                                <i class="bi bi-info-circle me-1"></i>
                                Te faltan <strong>$${(zone.freeShippingMin - productPrice).toLocaleString('es-AR')}</strong> para envío gratis
                            </div>
                        ` : ''}
                    </div>
                </div>
            </div>
        `;
    }
    
    /**
     * Inicializar calculador
     */
    function initShippingCalculator() {
        const $form = $('#htCompactShippingForm');
        const $result = $('#htShippingResult');
        const $defaultMsg = $('#htShippingDefaultMsg');
        const $zipInput = $('#htShippingZipCode');
        const $button = $('#htCalcShippingBtn');
        
        if ($form.length === 0) return;
        
        // Solo números en código postal
        $zipInput.on('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
        
        // Submit del formulario
        $form.on('submit', function(e) {
            e.preventDefault();
            
            const zipCode = $zipInput.val().trim();
            
            // Validación
            if (zipCode.length !== 4) {
                showError('Por favor ingresá un código postal válido de 4 dígitos');
                return;
            }
            
            // Deshabilitar botón
            $button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Calculando...');
            
            // Simular delay de red (remover en producción)
            setTimeout(function() {
                calculateShipping(zipCode);
                $button.prop('disabled', false).text('Calcular');
            }, 500);
        });
        
        /**
         * Calcular envío
         */
        function calculateShipping(zipCode) {
            const zone = getShippingZone(zipCode);
            const productPrice = parseFloat($form.find('input[name="product_price"]').val());
            const deliveryDates = calculateDeliveryDate(zone.daysMin, zone.daysMax);
            
            const resultHTML = generateResultHTML(zone, productPrice, deliveryDates);
            
            $defaultMsg.fadeOut(200, function() {
                $result.html(resultHTML).fadeIn(400);
            });
            
            // Guardar en sessionStorage para mantener entre recargas
            sessionStorage.setItem('ht_last_zip', zipCode);
            sessionStorage.setItem('ht_shipping_result', resultHTML);
        }
        
        /**
         * Mostrar error
         */
        function showError(message) {
            const errorHTML = `
                <div class="alert alert-danger alert-dismissible fade show mb-0" role="alert" style="border-radius: 0.75rem;">
                    <i class="bi bi-exclamation-triangle me-2"></i>${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            
            $defaultMsg.fadeOut(200, function() {
                $result.html(errorHTML).fadeIn(400);
            });
            
            setTimeout(function() {
                $result.fadeOut(400, function() {
                    $defaultMsg.fadeIn(200);
                });
            }, 4000);
        }
        
        // Restaurar último cálculo si existe
        const lastZip = sessionStorage.getItem('ht_last_zip');
        const lastResult = sessionStorage.getItem('ht_shipping_result');
        
        if (lastZip && lastResult) {
            $zipInput.val(lastZip);
            $defaultMsg.hide();
            $result.html(lastResult).show();
        }
    }
    
    // Inicializar cuando el DOM esté listo
    $(document).ready(function() {
        initShippingCalculator();
    });
    
})(jQuery);