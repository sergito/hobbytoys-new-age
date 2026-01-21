<?php 

// Mover debajo del breadcrumb
//remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
//add_action( 'woocommerce_before_main_content', 'woocommerce_result_count', 25 );

// Ocultar título "Tienda" en la página de tienda
//add_filter( 'woocommerce_show_page_title', '__return_false' );

add_filter( 'woocommerce_product_single_add_to_cart_text', function() {
    return __( 'Agregar', 'bootscore' );
});


// ============================================
// REORGANIZACIÓN PROFESIONAL DEL SINGLE PRODUCT
// Jerarquía: Categoría → Tags → Título → Precio → Precio sin IVA →
// Descripción → Cantidad + Agregar → Envíos → Pagos → Garantías → Compartir
// ============================================

/**
 * 1. Mostrar categorías al inicio (antes del título)
 */
add_action('woocommerce_single_product_summary', 'ht_show_product_categories', 2);
function ht_show_product_categories() {
    global $product;

    $categories = get_the_terms($product->get_id(), 'product_cat');

    if ($categories && !is_wp_error($categories)) {
        echo '<div class="product-categories mb-2">';
        foreach ($categories as $cat) {
            // Excluir categoría "JUGUETERIA"
            if (strtoupper($cat->name) !== 'JUGUETERIA') {
                echo '<a href="' . get_term_link($cat) . '" class="badge bg-primary text-white me-2 mb-2 text-decoration-none" style="font-size: 0.8rem; padding: 0.5rem 1rem; border-radius: 0.5rem;">';
                echo '<i class="bi bi-folder-fill me-1"></i>' . esc_html($cat->name);
                echo '</a>';
            }
        }
        echo '</div>';
    }
}

/**
 * 2. Mostrar tags después de categorías
 */
//add_action('woocommerce_single_product_summary', 'ht_show_product_tags', 3);
function ht_show_product_tags() {
    global $product;

    $tags = get_the_terms($product->get_id(), 'product_tag');

    if ($tags && !is_wp_error($tags)) {
        echo '<div class="product-tags mb-2">';
        foreach ($tags as $tag) {
            echo '<a href="' . get_term_link($tag) . '" class="badge bg-light text-dark me-2 mb-2 text-decoration-none" style="font-size: 0.8rem; padding: 0.4rem 0.8rem; border-radius: 0.4rem; border: 1px solid #dee2e6;">';
            echo '<i class="bi bi-tag me-1"></i>' . esc_html($tag->name);
            echo '</a>';
        }
        echo '</div>';
    }
}

/**
 * 3. Badge de edad después del título
 * DESHABILITADO - Ahora se muestra directamente en content-product.php con colores personalizados
 */
/* function mostrar_badge_edades() {
    global $product;

    if ( ! $product ) return;

    $categorias = get_the_terms( $product->get_id(), 'pa_edades' );

    if ( $categorias && ! is_wp_error( $categorias ) ) {
        echo '<div class="product-edad-badge mb-3">';
        foreach ( $categorias as $cat ) {
            echo '<span class="badge bg-warning text-dark" style="font-size: 0.9rem; padding: 0.5rem 1rem; border-radius: 0.5rem;">';
            echo '<i class="bi bi-person-fill me-1"></i>Edad: ' . esc_html( $cat->name );
            echo '</span>';
            break; // Solo la primera
        }
        echo '</div>';
    }
}
add_action( 'woocommerce_shop_loop_item_title', 'mostrar_badge_edades', 5 );
add_action( 'woocommerce_single_product_summary', 'mostrar_badge_edades', 6 ); */


/**
 * Agregar badge de categoría en productos relacionados
 * Actualizado para coincidir con el loop principal
 */
//add_action('woocommerce_before_shop_loop_item_title', 'ht_add_category_badge_related', 5);
function ht_add_category_badge_related() {
    // Solo en sección de productos relacionados (single product page)
    if (!is_product()) {
        return;
    }

    global $product;

    if (!$product) {
        return;
    }

    // Obtener la categoría principal del producto
    $categories = get_the_terms($product->get_id(), 'product_cat');

    if ($categories && !is_wp_error($categories)) {
        // Filtrar para excluir "JUGUETERIA"
        foreach ($categories as $cat) {
            if (strtoupper($cat->name) !== 'JUGUETERIA') {
                echo '<span class="badge badge-categoria bg-primary text-white mb-2 px-3 py-2" style="font-size: 0.75rem; font-weight: 600; border-radius: 0.5rem;">';
                echo '<i class="bi bi-tag-fill me-1"></i>' . esc_html($cat->name);
                echo '</span>';
                break; // Solo mostrar la primera categoría válida
            }
        }
    }
}


// Remover el meta default de WooCommerce (ya mostramos categorías y tags arriba)
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);

// ============================================
// 9. Formas de pago (priority 40)
// ============================================
add_action('woocommerce_single_product_summary', 'ht_show_payment_methods', 40);
function ht_show_payment_methods() {
    global $product;
    ?>
    <!-- Formas de Pago -->
    <div class="payment-info-compact mt-4">
        <div class="payment-compact-header mb-3">
            <h6 class="mb-0 fw-bold d-flex align-items-center">
                <i class="bi bi-credit-card-2-front me-2" style="color: var(--primary-color); font-size: 1.3rem;"></i>
                <span style="color: var(--text-dark);">Formas de Pago</span>
            </h6>
        </div>
        
        <div class="payment-options-list">
            <div class="payment-option mb-2">
                <i class="bi bi-check-circle-fill text-success me-2"></i>
                <span>Hasta <strong>6 cuotas sin interés</strong> con tarjetas de crédito</span>
            </div>
            <div class="payment-option mb-2">
                <i class="bi bi-check-circle-fill text-success me-2"></i>
                <span>Banco Provincia: <strong>4 cuotas + 10% reintegro</strong></span>
            </div>
            <div class="payment-option mb-2">
                <i class="bi bi-check-circle-fill text-success me-2"></i>
                <span>Transferencia bancaria: <strong>5% descuento</strong></span>
            </div>
        </div>
        
        <button 
            type="button" 
            class="btn btn-outline-primary btn-sm w-100 mt-3" 
            data-bs-toggle="modal" 
            data-bs-target="#paymentDetailsModal"
            style="border-radius: 0.75rem; font-weight: 600;">
            <i class="bi bi-info-circle me-2"></i>Ver todas las opciones de pago
        </button>
    </div>

    <!-- Modal con detalles completos de pago -->
    <?php ht_payment_details_modal($product); ?>

    <style>
    .payment-info-compact {
        background: linear-gradient(135deg, rgba(248, 249, 250, 0.8), rgba(255, 251, 230, 0.3));
        border: 2px solid rgba(238, 40, 91, 0.1);
        border-radius: 1rem;
        padding: 1.25rem;
        transition: all 0.3s ease;
    }
    
    .payment-info-compact:hover {
        border-color: rgba(238, 40, 91, 0.2);
        box-shadow: 0 5px 20px rgba(238, 40, 91, 0.08);
    }
    
    .payment-option {
        font-size: 0.95rem;
        color: var(--text-dark);
        line-height: 1.6;
    }
    
    .guarantee-badge {
        transition: all 0.3s ease;
        background: white;
        cursor: default;
    }
    
    .guarantee-badge:hover {
        background: linear-gradient(145deg, #ffffff, #f0f0f0);
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    @media (max-width: 576px) {
        .payment-info-compact {
            padding: 1rem;
        }
        .payment-option {
            font-size: 0.9rem;
        }
    }
    </style>
    <?php
}


// ============================================
// 10. Garantías (priority 45)
// ============================================
add_action('woocommerce_single_product_summary', 'ht_show_guarantees', 45);
function ht_show_guarantees() {
    ?>
    <!-- Garantías -->
    <div class="guarantees-section mt-4">
        <h6 class="mb-3 fw-bold"><i class="bi bi-shield-check me-2" style="color: var(--primary-color);"></i>Nuestras Garantías</h6>
        <div class="row g-2">
            <div class="col-6 col-md-3">
                <div class="guarantee-badge text-center p-3 border rounded-3 h-100">
                    <i class="bi bi-shield-check text-success fs-3"></i>
                    <div class="small mt-2 fw-semibold">Compra<br>Segura</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="guarantee-badge text-center p-3 border rounded-3 h-100">
                    <i class="bi bi-arrow-repeat text-info fs-3"></i>
                    <div class="small mt-2 fw-semibold">20 días<br>para cambios</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="guarantee-badge text-center p-3 border rounded-3 h-100">
                    <i class="bi bi-headset text-primary fs-3"></i>
                    <div class="small mt-2 fw-semibold">Atención<br>personalizada</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="guarantee-badge text-center p-3 border rounded-3 h-100">
                    <i class="bi bi-star-fill text-warning fs-3"></i>
                    <div class="small mt-2 fw-semibold">+30 años<br>de experiencia</div>
                </div>
            </div>
        </div>
    </div>

    <style>
    .guarantee-badge {
        transition: all 0.3s ease;
        background: white;
        cursor: default;
    }

    .guarantee-badge:hover {
        background: linear-gradient(145deg, #ffffff, #f0f0f0);
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    </style>
    <?php
}

// ============================================
// 11. Botones de compartir (priority 50)
// ============================================
add_action('woocommerce_single_product_summary', 'ht_show_sharing_buttons', 50);
function ht_show_sharing_buttons() {
    ?>
    <!-- Compartir producto -->
    <div class="product-sharing mt-4 pt-3 border-top">
        <h6 class="mb-3 fw-bold"><i class="bi bi-share me-2" style="color: var(--primary-color);"></i>Compartir producto</h6>
        <div class="d-flex gap-2">
            <?php
            $url_articulo = urlencode(get_permalink());
            $title_articulo = str_replace(' ', '%20', get_the_title());
            $whatsapp_url = 'https://wa.me/?text='.$title_articulo . ' ' . $url_articulo;
            $facebook_url = 'https://www.facebook.com/sharer/sharer.php?u='.$url_articulo;
            ?>
            <a href="<?php echo $whatsapp_url; ?>" target="_blank" class="btn btn-success btn-sm">
                <i class="bi bi-whatsapp me-1"></i>WhatsApp
            </a>
            <a href="<?php echo $facebook_url; ?>" target="_blank" class="btn btn-primary btn-sm">
                <i class="bi bi-facebook me-1"></i>Facebook
            </a>
            <button type="button" class="btn btn-outline-secondary btn-sm" id="copyLinkBtn" title="Copiar link">
                <i class="bi bi-link-45deg me-1"></i>Copiar link
            </button>
        </div>
    </div>
    <?php
}

/**
 * Modal con detalles completos de formas de pago
 */
function ht_payment_details_modal($product) {
    // Obtener y validar el precio del producto
    $precio_raw = $product->get_price();
    $precio = floatval($precio_raw);
    
    // Si el precio no es válido, no mostrar la calculadora de cuotas
    $mostrar_calculadora = ($precio > 0);
    ?>
    <div class="modal fade" id="paymentDetailsModal" tabindex="-1" aria-labelledby="paymentDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border-radius: 1.5rem; overflow: hidden;">
                <div class="modal-header" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: white; border: none;">
                    <h5 class="modal-title fw-bold" id="paymentDetailsModalLabel">
                        <i class="bi bi-credit-card me-2"></i>Formas de Pago Disponibles
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    
                    <!-- Tarjetas de Crédito -->
                    <div class="payment-detail-card mb-4">
                        <div class="payment-card-header d-flex align-items-center mb-3">
                            <div class="payment-icon me-3">
                                <i class="bi bi-credit-card" style="font-size: 2rem; color: var(--primary-color);"></i>
                            </div>
                            <div>
                                <h6 class="mb-1 fw-bold">Tarjetas de Crédito</h6>
                                <p class="mb-0 small text-muted">Visa y Mastercard</p>
                            </div>
                        </div>
                        <div class="payment-card-content">
                            <div class="alert alert-success mb-2" style="border-radius: 0.75rem;">
                                <strong>¡Hasta 6 cuotas sin interés!</strong> en tarjetas bancarias
                            </div>
                            <p class="small mb-0">Válido comprando a través de la web</p>
                        </div>
                    </div>

                    <!-- Banco Provincia -->
                    <div class="payment-detail-card mb-4">
                        <div class="payment-card-header d-flex align-items-center mb-3">
                            <div class="payment-icon me-3">
                                <i class="bi bi-bank" style="font-size: 2rem; color: #FF6B35;"></i>
                            </div>
                            <div>
                                <h6 class="mb-1 fw-bold">Banco Provincia</h6>
                                <p class="mb-0 small text-muted">Promoción especial</p>
                            </div>
                        </div>
                        <div class="payment-card-content">
                            <div class="alert alert-warning mb-2" style="border-radius: 0.75rem;">
                                <strong>4 cuotas sin interés + 10% de reintegro sin tope</strong>
                            </div>
                            <p class="small mb-1">✓ Válido todos los días</p>
                            <p class="small mb-0 fw-bold text-primary">Vigencia hasta 30/09/2025</p>
                        </div>
                    </div>

                    <!-- Transferencia -->
                    <div class="payment-detail-card mb-4">
                        <div class="payment-card-header d-flex align-items-center mb-3">
                            <div class="payment-icon me-3">
                                <i class="bi bi-bank2" style="font-size: 2rem; color: var(--secondary-color);"></i>
                            </div>
                            <div>
                                <h6 class="mb-1 fw-bold">Transferencia Bancaria</h6>
                                <p class="mb-0 small text-muted">CBU o Alias</p>
                            </div>
                        </div>
                        <div class="payment-card-content">
                            <div class="alert alert-info mb-2" style="border-radius: 0.75rem;">
                                <strong>5% de descuento adicional</strong>
                            </div>
                            <p class="small mb-1">✓ Recibís los datos después de finalizar la compra</p>
                            <p class="small mb-0">✓ El dinero debe estar acreditado para procesar el envío</p>
                        </div>
                    </div>

                    <!-- Calculadora de cuotas -->
                    <?php if ($mostrar_calculadora) : ?>
                    <div class="cuotas-calculator mt-4 p-3" style="background: var(--light-bg); border-radius: 1rem;">
                        <h6 class="fw-bold mb-3">Calculá tus cuotas</h6>
                        <?php
                        $cuotas = [3, 6, 9, 12];
                        echo '<div class="row g-2">';
                        foreach ($cuotas as $cuota) {
                            $monto_cuota = $precio / $cuota;
                            echo '<div class="col-6 col-md-3">';
                            echo '<div class="cuota-option text-center p-2 border rounded" style="background: white;">';
                            echo '<div class="fw-bold" style="color: var(--primary-color);">' . $cuota . ' cuotas</div>';
                            echo '<div class="small">$' . number_format($monto_cuota, 2, ',', '.') . '</div>';
                            echo '</div>';
                            echo '</div>';
                        }
                        echo '</div>';
                        ?>
                    </div>
                    <?php endif; ?>

                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <style>
    /* Modal z-index fix - Ensure modal appears above all elements and can be closed */
    #paymentDetailsModal {
        z-index: 100000 !important;
    }

    #paymentDetailsModal ~ .modal-backdrop {
        z-index: 99999 !important;
    }

    /* Asegurar que el backdrop sea clickeable para cerrar */
    .modal-backdrop {
        pointer-events: auto !important;
    }

    #paymentDetailsModal .modal-dialog {
        z-index: 100001 !important;
        pointer-events: auto !important;
    }

    .payment-detail-card {
        border: 2px solid rgba(238, 40, 91, 0.1);
        border-radius: 1rem;
        padding: 1.25rem;
        transition: all 0.3s ease;
    }

    .payment-detail-card:hover {
        border-color: rgba(238, 40, 91, 0.25);
        box-shadow: 0 5px 20px rgba(238, 40, 91, 0.1);
    }

    .cuota-option {
        transition: all 0.2s ease;
        cursor: default;
    }

    .cuota-option:hover {
        transform: translateY(-2px);
        box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    }
    </style>
    <?php
}
// ============================================
// NUEVA SECCIÓN: PAGO Y ENVÍOS (ANCHO COMPLETO)
// Esta sección aparece DEBAJO del summary, ocupando toda la columna (col-12)
// Hook: woocommerce_after_single_product_summary - Prioridad 5 (antes de tabs)
// ============================================
// DESHABILITADA - La información ya está en el summary principal
// add_action('woocommerce_after_single_product_summary', 'ht_payment_shipping_fullwidth_section', 5);
function ht_payment_shipping_fullwidth_section() {
    global $product;
    
    if (!$product) {
        return;
    }
    
    $precio = $product->get_price();
    $falta_para_envio_gratis = max(0, 90000 - $precio);
    $porcentaje = min(100, ($precio / 90000) * 100);
    ?>
    
    <!-- Contenedor de ancho completo para Pago y Envíos -->
    <div class="container-fluid py-5" style="background: linear-gradient(to bottom, #ffffff, #f8f9fa);">
        <div class="container">
            <div class="row g-4">
                
                <!-- COLUMNA IZQUIERDA: Formas de Pago -->
                <div class="col-12 col-lg-6">
                    <div class="payment-methods-section border rounded p-4 shadow-sm h-100" style="background: white;">
                        <h2 class="text-center mb-4 text-primary">
                            <i class="bi bi-credit-card me-2"></i>Formas de Pago
                        </h2>
                        
                        <!-- Botón para ver cuotas -->
                        <div class="text-center mb-4">
                            <button type="button" class="btn btn-primary btn-lg pulse-animation" data-bs-toggle="modal" data-bs-target="#cuotasModal">
                                <i class="bi bi-calculator me-2"></i>Calculá tus cuotas
                            </button>
                        </div>

                        <!-- Tarjetas de crédito -->
                        <div class="payment-card mb-4 p-4 rounded-3 bg-gradient-primary text-white">
                            <div class="text-center">
                                <div class="payment-icons mb-3">
                                    <img src="https://hobbytoys.com.ar/wp-content/uploads/2020/10/visa-logo.png" alt="Visa" class="payment-logo me-2" style="height: 40px;">
                                    <img src="https://hobbytoys.com.ar/wp-content/uploads/2020/10/mastercard-logo.png" alt="Mastercard" class="payment-logo" style="height: 40px;">
                                </div>
                                <h3 class="fw-bold mb-2">¡Hasta 6 cuotas sin interés!</h3>
                                <p class="mb-1">Visa y Mastercard bancarias</p>
                                <p class="small opacity-75">(Comprando a través de la web)</p>
                            </div>
                        </div>

                        <!-- Banco Provincia -->
                        <div class="payment-card mb-4 p-4 rounded-3 bg-gradient-orange">
                            <div class="text-center">
                                <img src="https://hobbytoys.com.ar/wp-content/uploads/2020/10/logo_provincia.png" 
                                     alt="Banco Provincia" 
                                     class="mb-3"
                                     style="height: 60px; width: auto;">
                                <h3 class="fw-bold mb-2">4 cuotas sin interés</h3>
                                <h3 class="fw-bold text-success mb-2">+ 10% de reintegro sin tope</h3>
                                <p class="small">Promoción válida todos los días</p>
                                <p class="small fw-bold">Hasta el 30/09/2025</p>
                            </div>
                        </div>

                        <!-- Transferencia bancaria -->
                        <div class="payment-card mb-4 p-4 rounded-3 bg-gradient-blue text-white">
                            <div class="text-center">
                                <i class="bi bi-bank fs-1 mb-3"></i>
                                <h3 class="fw-bold mb-2">Transferencia bancaria</h3>
                                <p class="mb-1">Te llegarán los datos después de finalizar la compra</p>
                                <p class="small opacity-75">Para retirar o procesar el envío, el dinero debe estar acreditado en nuestra cuenta</p>
                                <div class="badge bg-success mt-2">5% de descuento adicional</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- COLUMNA DERECHA: Información de Envíos -->
                <div class="col-12 col-lg-6">
                    <div class="shipping-section border rounded p-4 shadow-sm h-100" style="background: white;">
                        <h2 class="text-center mb-4 text-primary">
                            <i class="bi bi-truck me-2"></i>Información de Envíos
                        </h2>
                        
                        <?php if ($falta_para_envio_gratis > 0): ?>
                        <div class="free-shipping-alert mb-4 p-4 rounded-3 bg-warning bg-opacity-10 border border-warning">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h5 class="mb-1">Estás cerca del envío gratis</h5>
                                    <p class="mb-0">Te faltan <strong class="text-warning fs-4">$<?php echo number_format($falta_para_envio_gratis, 0, ',', '.'); ?></strong></p>
                                </div>
                                <span class="badge bg-success fs-6 p-2">ENVÍO GRATIS</span>
                            </div>
                            <div class="progress" style="height: 20px; border-radius: 10px;">
                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" 
                                     role="progressbar"
                                     style="width: <?php echo $porcentaje; ?>%"
                                     aria-valuenow="<?php echo $porcentaje; ?>"
                                     aria-valuemin="0" 
                                     aria-valuemax="100">
                                    <?php echo round($porcentaje); ?>%
                                </div>
                            </div>
                        </div>
                        <?php else: ?>
                        <div class="alert alert-success text-center p-4 mb-4">
                            <i class="bi bi-check-circle-fill me-2 fs-3"></i>
                            <h4 class="mb-0">¡Este producto tiene envío gratis!</h4>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Información adicional de envíos -->
                        <div class="shipping-info-cards">
                            <div class="info-card mb-3 p-3 rounded-3" style="background: #f0f9ff; border-left: 4px solid #0ea5e9;">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-geo-alt-fill fs-4 me-3" style="color: #0ea5e9;"></i>
                                    <div>
                                        <h6 class="fw-bold mb-1">Envíos a todo el país</h6>
                                        <p class="mb-0 small">Llegamos a todas las provincias de Argentina</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="info-card mb-3 p-3 rounded-3" style="background: #f0fdf4; border-left: 4px solid #22c55e;">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-clock-fill fs-4 me-3" style="color: #22c55e;"></i>
                                    <div>
                                        <h6 class="fw-bold mb-1">Entrega rápida</h6>
                                        <p class="mb-0 small">CABA y GBA: 24-48hs | Interior: 3-7 días hábiles</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="info-card mb-3 p-3 rounded-3" style="background: #fef3c7; border-left: 4px solid #f59e0b;">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-shield-check fs-4 me-3" style="color: #f59e0b;"></i>
                                    <div>
                                        <h6 class="fw-bold mb-1">Envío seguro</h6>
                                        <p class="mb-0 small">Todos los envíos están asegurados y tienen tracking</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-center mt-4">
                            <a href="https://hobbytoys.com.ar/envios/" 
                               class="btn btn-outline-primary btn-lg" 
                               target="_blank">
                                <i class="bi bi-box-seam me-2"></i>Ver todas las opciones de envío
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal de Cuotas -->
    <div class="modal fade" id="cuotasModal" tabindex="-1" aria-labelledby="cuotasModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #EE285B 0%, #534fb5 100%); color: white;">
                    <h5 class="modal-title" id="cuotasModalLabel">
                        <i class="bi bi-calculator me-2"></i>Calculá tus cuotas
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="text-center mb-4">
                        <h3 class="fw-bold text-primary">Precio del producto</h3>
                        <p class="display-4 fw-black mb-0" style="color: #EE285B;">
                            $<?php echo number_format($precio, 2, ',', '.'); ?>
                        </p>
                    </div>
                    
                    <div class="row g-3">
                        <?php 
                        $cuotas = array(
                            array('num' => 3, 'color' => '#06D6A0'),
                            array('num' => 6, 'color' => '#534fb5'),
                            array('num' => 12, 'color' => '#FFB900')
                        );
                        
                        foreach ($cuotas as $cuota): 
                            $valor_cuota = $precio / $cuota['num'];
                        ?>
                        <div class="col-md-4">
                            <div class="cuota-card p-3 text-center rounded-3 h-100" style="background: <?php echo $cuota['color']; ?>20; border: 2px solid <?php echo $cuota['color']; ?>;">
                                <div class="fs-1 fw-black mb-2" style="color: <?php echo $cuota['color']; ?>;">
                                    <?php echo $cuota['num']; ?>x
                                </div>
                                <div class="fs-4 fw-bold mb-1" style="color: #2C3E50;">
                                    $<?php echo number_format($valor_cuota, 2, ',', '.'); ?>
                                </div>
                                <p class="small mb-0 fw-bold" style="color: <?php echo $cuota['color']; ?>;">
                                    <?php echo $cuota['num']; ?> cuotas sin interés
                                </p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="alert alert-info mt-4 mb-0">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        <strong>Importante:</strong> Las cuotas sin interés aplican para tarjetas Visa y Mastercard de bancos adheridos.
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php
}


// Estilos inline para la página de producto
add_action('wp_enqueue_scripts', 'my_single_product_inline_styles');
function my_single_product_inline_styles() {
    if (!is_product()) return;
    
    $custom_css = "
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .bg-gradient-orange {
            background: linear-gradient(135deg, #fff5e6 0%, #ffe4cc 100%);
        }
        
        .bg-gradient-blue {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        /* Animación de pulso */
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        .pulse-animation {
            animation: pulse 2s infinite;
        }
        
        /* Tarjetas de pago */
        .payment-card {
            transition: all 0.3s ease;
            border: none;
            position: relative;
            overflow: hidden;
        }
        
        .payment-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
            transform: rotate(45deg);
            transition: all 0.6s;
            opacity: 0;
        }
        
        .payment-card:hover::before {
            animation: shine 0.6s ease-in-out;
        }
        
        @keyframes shine {
            0% { transform: rotate(45deg) translateY(-100%); opacity: 0; }
            50% { opacity: 1; }
            100% { transform: rotate(45deg) translateY(100%); opacity: 0; }
        }
        
        .payment-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        
        /* Badges de garantía */
        .guarantee-badge {
            transition: all 0.3s ease;
            background: white;
        }
        
        .guarantee-badge:hover {
            background: linear-gradient(145deg, #ffffff, #f0f0f0);
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        /* Progress bar personalizada */
        .progress {
            background-color: rgba(255,255,255,0.3);
            border: 1px solid rgba(0,0,0,0.1);
        }
        
        /* Secciones principales */
        .payment-methods-section,
        .shipping-section {
            background: white;
        }
        
        /* Logos de pago */
        .payment-logo {
            filter: brightness(0) invert(1);
            opacity: 0.9;
        }
        
        /* Info cards de envío */
        .info-card {
            transition: transform 0.3s ease;
        }
        
        .info-card:hover {
            transform: translateX(5px);
        }
        
        /* Modal de cuotas */
        .cuota-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .cuota-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .payment-methods-section,
            .shipping-section {
                margin-bottom: 1rem;
            }
        }
    ";
    wp_add_inline_style('woocommerce-general', $custom_css);
}


// Agregar precio sin IVA después del precio principal
add_action('woocommerce_single_product_summary', 'mostrar_precio_sin_iva', 11);
function mostrar_precio_sin_iva() {
    global $product;
    
    // Verificar que el producto exista y tenga un precio válido
    if (!$product || !$product->get_price()) {
        return;
    }
    
    // Convertir explícitamente a float
    $precio = floatval($product->get_price());
    
    // Verificar que el precio sea mayor a 0
    if ($precio <= 0) {
        return;
    }
    
    $precio_sin_iva = $precio / 1.21; // Dividir por 1.21 para quitar el 21% de IVA
    
    ?>
    <div class="precio-sin-iva-container mb-3">
        <span class="text-muted small">
            Precio sin impuestos nacionales: 
            <strong>$<?php echo number_format($precio_sin_iva, 2, ',', '.'); ?></strong>
        </span>
    </div>
    <?php
}

// 8. Calculador de envíos - DESPUÉS del add to cart (priority 35)
add_action('woocommerce_single_product_summary', 'ht_compact_shipping_calculator', 35);
function ht_compact_shipping_calculator() {
    global $product;
    $precio = $product->get_price();

    // Calcular si califica para envío gratis en cada zona
    $la_plata_threshold = 50000;
    $nacional_threshold = 90000;

    $is_free_laplata = $precio >= $la_plata_threshold;
    $is_free_nacional = $precio >= $nacional_threshold;

    // Calcular fechas de entrega
    $ahora = new DateTime('now', new DateTimeZone('America/Argentina/Buenos_Aires'));
    $hora_actual = (int)$ahora->format('H');

    // Si es antes de las 13hs, puede llegar hoy
    $llega_hoy = $hora_actual < 13;

    // Fecha de entrega La Plata (1-2 días)
    $entrega_laplata_min = clone $ahora;
    $entrega_laplata_min->modify('+1 day');
    $entrega_laplata_max = clone $ahora;
    $entrega_laplata_max->modify('+2 days');

    // Fecha de entrega Nacional (5-7 días)
    $entrega_nacional_min = clone $ahora;
    $entrega_nacional_min->modify('+5 days');
    $entrega_nacional_max = clone $ahora;
    $entrega_nacional_max->modify('+7 days');

    $meses = ['ene', 'feb', 'mar', 'abr', 'may', 'jun', 'jul', 'ago', 'sep', 'oct', 'nov', 'dic'];

    ?>
    <div class="ht-shipping-info-static mt-4 mb-4">
        <h6 class="mb-3 fw-bold">
            <i class="bi bi-truck me-2" style="color: var(--primary-color);"></i>Opciones de Envío
        </h6>

        <?php if ($llega_hoy): ?>
        <div class="alert alert-success mb-3" style="border-radius: 0.75rem; border-left: 4px solid #059669;">
            <i class="bi bi-clock-fill me-2"></i>
            <strong>¡Comprá antes de las 13hs y recibilo hoy mismo!</strong>
        </div>
        <?php endif; ?>

        <!-- Opción La Plata -->
        <div class="shipping-option mb-3" style="background: linear-gradient(135deg, rgba(238, 40, 91, 0.05), rgba(238, 40, 91, 0.02)); border: 2px solid rgba(238, 40, 91, 0.15); border-radius: 0.75rem; padding: 1rem;">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                    <div class="fw-bold" style="font-size: 1rem; color: var(--primary-color);">
                        <i class="bi bi-geo-alt-fill me-1"></i>La Plata (CP: 1900-1925)
                    </div>
                    <div class="small text-muted">
                        <i class="bi bi-calendar3 me-1"></i>
                        Llega entre el <?php echo $entrega_laplata_min->format('d'); ?> y <?php echo $entrega_laplata_max->format('d'); ?> de <?php echo $meses[(int)$entrega_laplata_max->format('n') - 1]; ?>
                    </div>
                </div>
                <div class="text-end">
                    <?php if ($is_free_laplata): ?>
                        <span class="badge bg-success" style="font-size: 0.9rem; padding: 0.5rem 1rem;">
                            <i class="bi bi-gift-fill me-1"></i>¡GRATIS!
                        </span>
                    <?php else: ?>
                        <div class="text-muted small">Gratis desde:</div>
                        <div class="fw-bold" style="color: var(--primary-color);">$<?php echo number_format($la_plata_threshold, 0, ',', '.'); ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Opción Nacional -->
        <div class="shipping-option mb-3" style="background: linear-gradient(135deg, rgba(83, 79, 181, 0.05), rgba(83, 79, 181, 0.02)); border: 2px solid rgba(83, 79, 181, 0.15); border-radius: 0.75rem; padding: 1rem;">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                    <div class="fw-bold" style="font-size: 1rem; color: var(--secondary-color);">
                        <i class="bi bi-geo-alt-fill me-1"></i>Resto del País
                    </div>
                    <div class="small text-muted">
                        <i class="bi bi-calendar3 me-1"></i>
                        Llega entre el <?php echo $entrega_nacional_min->format('d'); ?> y <?php echo $entrega_nacional_max->format('d'); ?> de <?php echo $meses[(int)$entrega_nacional_max->format('n') - 1]; ?>
                    </div>
                </div>
                <div class="text-end">
                    <?php if ($is_free_nacional): ?>
                        <span class="badge bg-success" style="font-size: 0.9rem; padding: 0.5rem 1rem;">
                            <i class="bi bi-gift-fill me-1"></i>¡GRATIS!
                        </span>
                    <?php else: ?>
                        <div class="text-muted small">Gratis desde:</div>
                        <div class="fw-bold" style="color: var(--secondary-color);">$<?php echo number_format($nacional_threshold, 0, ',', '.'); ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="alert alert-info mt-3 mb-0" style="border-radius: 0.75rem; font-size: 0.9rem; background: rgba(83, 79, 181, 0.08); border: 1px solid rgba(83, 79, 181, 0.2);">
            <i class="bi bi-info-circle-fill me-2"></i>
            Para calcular el envío a tu zona específica, ingresá tu código postal en el menú superior
        </div>
    </div>

    <style>
    .ht-shipping-info-static {
        background: #ffffff;
        border: 2px solid rgba(0, 0, 0, 0.08);
        border-radius: 1rem;
        padding: 1.25rem;
    }

    .shipping-option {
        transition: all 0.3s ease;
    }

    .shipping-option:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    @media (max-width: 576px) {
        .ht-shipping-info-static {
            padding: 1rem;
        }

        .shipping-option {
            padding: 0.75rem !important;
        }
    }
    </style>
    <?php
}

/**
 * AJAX: Calcular envío (versión con API externa opcional)
 * Permite integración futura con Correo Argentino, Andreani, etc.
 */

add_action('wp_ajax_ht_calculate_shipping', 'ht_ajax_calculate_shipping');
add_action('wp_ajax_nopriv_ht_calculate_shipping', 'ht_ajax_calculate_shipping');

function ht_ajax_calculate_shipping() {
    check_ajax_referer('ht_calc_shipping', 'shipping_nonce');
    
    $zip_code = sanitize_text_field($_POST['zip_code']);
    $product_id = intval($_POST['product_id']);
    $product_price = floatval($_POST['product_price']);
    
    // Validar código postal
    if (strlen($zip_code) !== 4 || !ctype_digit($zip_code)) {
        wp_send_json_error([
            'message' => 'Código postal inválido'
        ]);
        return;
    }
    
    // Obtener zona
    $zone = ht_get_shipping_zone_by_zip($zip_code);
    
    // Calcular costo
    $shipping_cost = $product_price >= $zone['free_shipping_min'] 
        ? 0 
        : $zone['cost_base'];
    
    // Calcular fechas de entrega
    $delivery_dates = ht_calculate_delivery_dates($zone['days_min'], $zone['days_max']);
    
    wp_send_json_success([
        'zone_name' => $zone['zone_name'],
        'shipping_cost' => $shipping_cost,
        'is_free' => $shipping_cost === 0,
        'delivery_min' => $delivery_dates['min'],
        'delivery_max' => $delivery_dates['max'],
        'delivery_text' => ht_format_delivery_text($delivery_dates),
        'message_html' => ht_generate_shipping_message_html($zone, $shipping_cost, $delivery_dates, $product_price)
    ]);
}

/**
 * Determinar zona por código postal
 */
function ht_get_shipping_zone_by_zip($zip_code) {
    $zip = intval($zip_code);
    
    // La Plata (1900-1999)
    if ($zip >= 1900 && $zip <= 1999) {
        return [
            'zone_name' => 'La Plata',
            'days_min' => 1,
            'days_max' => 2,
            'cost_base' => 2500,
            'free_shipping_min' => 90000
        ];
    }
    
    // Buenos Aires (1000-9999)
    if ($zip >= 1000 && $zip <= 9999) {
        return [
            'zone_name' => 'Buenos Aires',
            'days_min' => 3,
            'days_max' => 5,
            'cost_base' => 3500,
            'free_shipping_min' => 90000
        ];
    }
    
    // Nacional (resto)
    return [
        'zone_name' => 'Argentina',
        'days_min' => 5,
        'days_max' => 10,
        'cost_base' => 5000,
        'free_shipping_min' => 90000
    ];
}

/**
 * Calcular fechas de entrega (saltando fines de semana)
 */
function ht_calculate_delivery_dates($days_min, $days_max) {
    $today = new DateTime();
    
    // Fecha mínima
    $delivery_min = clone $today;
    $added_days = 0;
    while ($added_days < $days_min) {
        $delivery_min->modify('+1 day');
        if ($delivery_min->format('N') < 6) { // Lunes a viernes
            $added_days++;
        }
    }
    
    // Fecha máxima
    $delivery_max = clone $today;
    $added_days = 0;
    while ($added_days < $days_max) {
        $delivery_max->modify('+1 day');
        if ($delivery_max->format('N') < 6) {
            $added_days++;
        }
    }
    
    return [
        'min' => $delivery_min,
        'max' => $delivery_max
    ];
}

/**
 * Formatear texto de entrega
 */
function ht_format_delivery_text($delivery_dates) {
    $days_es = ['domingo', 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado'];
    $months_es = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio',
                  'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
    
    $min_date = $delivery_dates['min'];
    $max_date = $delivery_dates['max'];
    
    $min_formatted = sprintf(
        '%s %d de %s',
        $days_es[$min_date->format('w')],
        $min_date->format('j'),
        $months_es[$min_date->format('n') - 1]
    );
    
    if ($min_date->format('Y-m-d') === $max_date->format('Y-m-d')) {
        return "Llega el " . $min_formatted;
    }
    
    $max_formatted = sprintf(
        '%s %d de %s',
        $days_es[$max_date->format('w')],
        $max_date->format('j'),
        $months_es[$max_date->format('n') - 1]
    );
    
    return sprintf('Llega entre el %s y %s', $min_formatted, $max_formatted);
}

/**
 * Generar HTML del mensaje
 */
function ht_generate_shipping_message_html($zone, $cost, $dates, $product_price) {
    $is_free = $cost === 0;
    $delivery_text = ht_format_delivery_text($dates);
    
    $css_class = $is_free ? 'shipping-result-success' : 'shipping-result-warning';
    $icon = $is_free ? 'bi-check-circle-fill text-success' : 'bi-truck text-warning';
    
    $html = '<div class="' . $css_class . '">';
    $html .= '<div class="d-flex align-items-start">';
    $html .= '<i class="bi ' . $icon . '" style="font-size: 1.3rem; margin-right: 0.75rem; margin-top: 0.1rem;"></i>';
    $html .= '<div class="flex-grow-1">';
    $html .= '<div class="fw-bold mb-1" style="font-size: 1rem; color: var(--text-dark);">' . esc_html($delivery_text) . '</div>';
    
    if ($is_free) {
        $html .= '<div class="shipping-cost" style="font-size: 0.95rem;">';
        $html .= '<span class="text-success fw-bold"><i class="bi bi-gift-fill me-1"></i>¡Envío GRATIS!</span>';
        $html .= '</div>';
    } else {
        $html .= '<div class="shipping-cost" style="font-size: 0.95rem;">';
        $html .= '<span style="color: var(--text-dark);">Costo de envío: <strong style="color: var(--primary-color);">$' . number_format($cost, 0, ',', '.') . '</strong></span>';
        $html .= '</div>';
        
        $remaining = $zone['free_shipping_min'] - $product_price;
        $html .= '<div class="mt-2 small" style="color: #666;">';
        $html .= '<i class="bi bi-info-circle me-1"></i>';
        $html .= 'Te faltan <strong>$' . number_format($remaining, 0, ',', '.') . '</strong> para envío gratis';
        $html .= '</div>';
    }
    
    $html .= '</div></div></div>';
    
    return $html;
}

/**
 * Custom Fields para Características del Producto
 */

// Agregar meta boxes en el admin de productos
add_action('add_meta_boxes', 'ht_add_product_features_metabox');
function ht_add_product_features_metabox() {
    add_meta_box(
        'ht_product_features',
        'Características del Producto',
        'ht_product_features_metabox_callback',
        'product',
        'normal',
        'high'
    );
}

/**
 * Contenido del meta box
 */
function ht_product_features_metabox_callback($post) {
    wp_nonce_field('ht_product_features_nonce', 'ht_product_features_nonce_field');
    
    // Obtener valores guardados
    $feature_1 = get_post_meta($post->ID, '_ht_feature_1', true);
    $feature_2 = get_post_meta($post->ID, '_ht_feature_2', true);
    $feature_3 = get_post_meta($post->ID, '_ht_feature_3', true);
    ?>
    
    <div class="ht-product-features-admin" style="padding: 15px 12px;">
        <p class="description" style="margin-bottom: 20px; padding: 12px; background: #f0f6fc; border-left: 4px solid #0073aa; border-radius: 4px;">
            <strong>💡 Instrucciones:</strong> Ingresá hasta 3 características destacadas del producto. 
            Solo se mostrarán las que tengan contenido. Dejá en blanco las que no uses.
            <br><small>Ejemplos: "Apto desde 3 años", "Material resistente", "Incluye pilas"</small>
        </p>
        
        <div class="feature-field-group" style="margin-bottom: 20px;">
            <label for="ht_feature_1" style="display: block; margin-bottom: 8px; font-weight: 600; color: #1e1e1e;">
                <i class="dashicons dashicons-yes" style="color: #46b450;"></i> Característica 1
            </label>
            <input 
                type="text" 
                id="ht_feature_1" 
                name="ht_feature_1" 
                value="<?php echo esc_attr($feature_1); ?>" 
                placeholder="Ej: Apto desde 3 años"
                style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;">
        </div>
        
        <div class="feature-field-group" style="margin-bottom: 20px;">
            <label for="ht_feature_2" style="display: block; margin-bottom: 8px; font-weight: 600; color: #1e1e1e;">
                <i class="dashicons dashicons-yes" style="color: #46b450;"></i> Característica 2
            </label>
            <input 
                type="text" 
                id="ht_feature_2" 
                name="ht_feature_2" 
                value="<?php echo esc_attr($feature_2); ?>" 
                placeholder="Ej: Material plástico seguro y resistente"
                style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;">
        </div>
        
        <div class="feature-field-group" style="margin-bottom: 20px;">
            <label for="ht_feature_3" style="display: block; margin-bottom: 8px; font-weight: 600; color: #1e1e1e;">
                <i class="dashicons dashicons-yes" style="color: #46b450;"></i> Característica 3
            </label>
            <input 
                type="text" 
                id="ht_feature_3" 
                name="ht_feature_3" 
                value="<?php echo esc_attr($feature_3); ?>" 
                placeholder="Ej: Incluye instrucciones ilustradas"
                style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;">
        </div>
        
        <div style="padding: 12px; background: #fffbcc; border-left: 4px solid #ffb900; border-radius: 4px; margin-top: 20px;">
            <strong>📌 Tip:</strong> Usá características que diferencien tu producto y respondan preguntas frecuentes de los clientes.
        </div>
    </div>
    
    <style>
    .ht-product-features-admin input:focus {
        border-color: #0073aa;
        box-shadow: 0 0 0 1px #0073aa;
        outline: none;
    }
    
    .feature-field-group label {
        transition: color 0.2s;
    }
    
    .feature-field-group input:focus + label {
        color: #0073aa;
    }
    </style>
    <?php
}

/**
 * Guardar los custom fields
 */
add_action('save_post_product', 'ht_save_product_features');
function ht_save_product_features($post_id) {
    // Verificar nonce
    if (!isset($_POST['ht_product_features_nonce_field']) || 
        !wp_verify_nonce($_POST['ht_product_features_nonce_field'], 'ht_product_features_nonce')) {
        return;
    }
    
    // Verificar autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    // Verificar permisos
    if (!current_user_can('edit_product', $post_id)) {
        return;
    }
    
    // Guardar las características
    if (isset($_POST['ht_feature_1'])) {
        update_post_meta($post_id, '_ht_feature_1', sanitize_text_field($_POST['ht_feature_1']));
    }
    
    if (isset($_POST['ht_feature_2'])) {
        update_post_meta($post_id, '_ht_feature_2', sanitize_text_field($_POST['ht_feature_2']));
    }
    
    if (isset($_POST['ht_feature_3'])) {
        update_post_meta($post_id, '_ht_feature_3', sanitize_text_field($_POST['ht_feature_3']));
    }
}

/**
 * Mostrar características en el front-end
 */
function ht_display_product_features($product) {
    $features = [];
    
    // Obtener las 3 características
    for ($i = 1; $i <= 3; $i++) {
        $feature = get_post_meta($product->get_id(), '_ht_feature_' . $i, true);
        if (!empty($feature)) {
            $features[] = $feature;
        }
    }
    
    // Solo mostrar si hay al menos una característica
    if (empty($features)) {
        return;
    }
    ?>
    
    <div class="product-features-list mb-4">
        <h6 class="features-title mb-3 fw-bold" style="color: var(--secondary-color); font-size: 1rem;">
            <i class="bi bi-star-fill me-2" style="color: var(--accent-color);"></i>
            Características Destacadas
        </h6>
        <ul class="features-items list-unstyled mb-0">
            <?php foreach ($features as $feature): ?>
                <li class="feature-item mb-2 d-flex align-items-start">
                    <i class="bi bi-check-circle-fill me-2 flex-shrink-0" 
                       style="color: var(--success-color); font-size: 1.1rem; margin-top: 2px;"></i>
                    <span style="color: var(--text-dark); font-size: 0.95rem; line-height: 1.5;">
                        <?php echo esc_html($feature); ?>
                    </span>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    
    <style>
    .product-features-list {
        background: linear-gradient(135deg, rgba(248, 249, 250, 0.6), rgba(255, 251, 230, 0.3));
        border: 2px solid rgba(238, 40, 91, 0.08);
        border-radius: 1rem;
        padding: 1.25rem;
        transition: all 0.3s ease;
    }
    
    .product-features-list:hover {
        border-color: rgba(238, 40, 91, 0.15);
        box-shadow: 0 3px 15px rgba(238, 40, 91, 0.06);
    }
    
    .feature-item {
        animation: fadeInLeft 0.4s ease-out backwards;
    }
    
    .feature-item:nth-child(1) { animation-delay: 0.1s; }
    .feature-item:nth-child(2) { animation-delay: 0.2s; }
    .feature-item:nth-child(3) { animation-delay: 0.3s; }
    
    @keyframes fadeInLeft {
        from {
            opacity: 0;
            transform: translateX(-15px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @media (max-width: 576px) {
        .product-features-list {
            padding: 1rem;
        }
        .features-title {
            font-size: 0.95rem;
        }
        .feature-item span {
            font-size: 0.9rem;
        }
    }
    </style>
    <?php
}

/**
 * Estilos personalizados para productos relacionados
 */
//add_action('wp_head', 'ht_related_products_styles');
function ht_related_products_styles() {
    if (!is_product()) {
        return;
    }
    ?>
    <style>
    /* Sección de Productos Relacionados */
    .related.products {
        margin-top: 4rem;
        padding-top: 3rem;
        border-top: 2px solid rgba(238, 40, 91, 0.1);
    }
    
    .related.products > h2 {
        font-size: 1.75rem;
        font-weight: 900;
        color: var(--primary-color);
        margin-bottom: 2rem;
        position: relative;
        padding-bottom: 1rem;
        letter-spacing: 0.5px;
    }
    
    .related.products > h2::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 80px;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
        border-radius: 2px;
    }
    
    .related.products > h2::before {
        content: '🔥';
        margin-right: 0.75rem;
        font-size: 1.5rem;
    }
    
    /* Productos relacionados en mobile */
    @media (max-width: 767.98px) {
        .related.products {
            margin-top: 2.5rem;
            padding-top: 2rem;
        }
        
        .related.products > h2 {
            font-size: 1.35rem;
            margin-bottom: 1.5rem;
        }
        
        .related.products .products {
            display: grid !important;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }
    }
    
    /* Badge de categoría en productos relacionados */
    .related.products .product .cat-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(255, 185, 0, 0.95);
        color: var(--text-dark);
        padding: 0.35rem 0.75rem;
        border-radius: 1rem;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        z-index: 10;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }
    </style>
    <?php
}