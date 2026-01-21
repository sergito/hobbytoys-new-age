<?php
/**
 * Sistema de Cálculo de Envío Gratis por Código Postal
 * Hobby Toys - WooCommerce
 * 
 * @package HobbyToys
 * @version 1.0.0
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

// ============================================
// 1. CONFIGURACIÓN DE ZONAS Y UMBRALES
// ============================================

/**
 * Obtener configuración de zonas de envío
 * 
 * @return array Configuración de zonas con códigos postales y umbrales
 */
function ht_get_shipping_zones_config() {
    return [
        'la_plata' => [
            'name' => 'La Plata y alrededores',
            'threshold' => 50000,
            'postal_codes' => [
                // La Plata y alrededores
                'ranges' => [
                    ['start' => 1900, 'end' => 1925],
                ],
                'exact' => [] // Códigos postales específicos adicionales
            ],
            'localities' => ['la plata', 'berisso', 'ensenada', 'city bell', 'villa elisa', 'gonnet']
        ],
        'nacional' => [
            'name' => 'Resto del país',
            'threshold' => 90000,
            'postal_codes' => [
                'ranges' => [
                    ['start' => 1000, 'end' => 9999],
                ],
                'exact' => []
            ]
        ]
    ];
}

/**
 * Detectar zona según código postal
 * 
 * @param string $postal_code Código postal a verificar
 * @return string 'la_plata' o 'nacional'
 */
function ht_detect_zone_by_postal_code($postal_code) {
    $postal_code = preg_replace('/[^0-9]/', '', $postal_code);
    $postal_code_num = intval($postal_code);
    
    $zones = ht_get_shipping_zones_config();
    
    // Verificar La Plata
    foreach ($zones['la_plata']['postal_codes']['ranges'] as $range) {
        if ($postal_code_num >= $range['start'] && $postal_code_num <= $range['end']) {
            return 'la_plata';
        }
    }
    
    // Verificar códigos exactos de La Plata
    if (in_array($postal_code, $zones['la_plata']['postal_codes']['exact'])) {
        return 'la_plata';
    }
    
    // Por defecto es nacional
    return 'nacional';
}

/**
 * Obtener umbral de envío gratis según zona
 * 
 * @param string $zone Zona ('la_plata' o 'nacional')
 * @return int Monto mínimo para envío gratis
 */
function ht_get_threshold_by_zone($zone) {
    $zones = ht_get_shipping_zones_config();
    return isset($zones[$zone]['threshold']) ? $zones[$zone]['threshold'] : 90000;
}

/**
 * Obtener nombre de zona
 * 
 * @param string $zone Código de zona
 * @return string Nombre de la zona
 */
function ht_get_zone_name($zone) {
    $zones = ht_get_shipping_zones_config();
    return isset($zones[$zone]['name']) ? $zones[$zone]['name'] : 'Resto del país';
}


// ============================================
// 2. CÁLCULO DE PROGRESO DE ENVÍO GRATIS
// ============================================

/**
 * Calcular progreso de envío gratis para una zona específica
 * 
 * @param string $zone Zona de envío
 * @return array Información de progreso
 */
function ht_calculate_free_shipping_progress($zone = 'nacional') {
    if (!class_exists('WooCommerce')) {
        return [
            'cart_total' => 0,
            'threshold' => ht_get_threshold_by_zone($zone),
            'remaining' => ht_get_threshold_by_zone($zone),
            'percentage' => 0,
            'is_free' => false,
            'zone' => $zone,
            'zone_name' => ht_get_zone_name($zone)
        ];
    }
    
    $cart_total = WC()->cart->get_subtotal();
    $threshold = ht_get_threshold_by_zone($zone);
    $remaining = max(0, $threshold - $cart_total);
    $percentage = min(100, ($cart_total / $threshold) * 100);
    
    return [
        'cart_total' => $cart_total,
        'threshold' => $threshold,
        'remaining' => $remaining,
        'percentage' => $percentage,
        'is_free' => $cart_total >= $threshold,
        'zone' => $zone,
        'zone_name' => ht_get_zone_name($zone)
    ];
}


// ============================================
// 3. AJAX HANDLERS
// ============================================

/**
 * AJAX: Verificar código postal y calcular envío gratis
 */
add_action('wp_ajax_ht_check_postal_code', 'ht_ajax_check_postal_code');
add_action('wp_ajax_nopriv_ht_check_postal_code', 'ht_ajax_check_postal_code');

function ht_ajax_check_postal_code() {
    check_ajax_referer('ht_shipping_nonce', 'nonce');
    
    $postal_code = sanitize_text_field($_POST['postal_code']);
    
    if (empty($postal_code)) {
        wp_send_json_error([
            'message' => 'Por favor, ingresá un código postal válido'
        ]);
        return;
    }
    
    // Detectar zona
    $zone = ht_detect_zone_by_postal_code($postal_code);
    
    // Calcular progreso
    $progress = ht_calculate_free_shipping_progress($zone);
    
    // Guardar en sesión
    WC()->session->set('ht_shipping_zone', $zone);
    WC()->session->set('ht_postal_code', $postal_code);
    
    wp_send_json_success([
        'zone' => $zone,
        'zone_name' => $progress['zone_name'],
        'threshold' => $progress['threshold'],
        'cart_total' => $progress['cart_total'],
        'remaining' => $progress['remaining'],
        'percentage' => round($progress['percentage']),
        'is_free' => $progress['is_free'],
        'postal_code' => $postal_code,
        'message' => $progress['is_free'] 
            ? '¡Felicitaciones! Tu compra califica para envío gratis' 
            : 'Te faltan $' . number_format($progress['remaining'], 0, ',', '.') . ' para envío gratis'
    ]);
}

/**
 * AJAX: Obtener zona guardada en sesión
 */
add_action('wp_ajax_ht_get_saved_zone', 'ht_ajax_get_saved_zone');
add_action('wp_ajax_nopriv_ht_get_saved_zone', 'ht_ajax_get_saved_zone');

function ht_ajax_get_saved_zone() {
    check_ajax_referer('ht_shipping_nonce', 'nonce');
    
    if (!WC()->session) {
        wp_send_json_error(['message' => 'Sesión no disponible']);
        return;
    }
    
    $zone = WC()->session->get('ht_shipping_zone', 'nacional');
    $postal_code = WC()->session->get('ht_postal_code', '');
    
    $progress = ht_calculate_free_shipping_progress($zone);
    
    wp_send_json_success([
        'zone' => $zone,
        'zone_name' => $progress['zone_name'],
        'threshold' => $progress['threshold'],
        'cart_total' => $progress['cart_total'],
        'remaining' => $progress['remaining'],
        'percentage' => round($progress['percentage']),
        'is_free' => $progress['is_free'],
        'postal_code' => $postal_code,
        'has_saved_zone' => !empty($postal_code)
    ]);
}

/**
 * AJAX: Limpiar zona guardada
 */
add_action('wp_ajax_ht_clear_saved_zone', 'ht_ajax_clear_saved_zone');
add_action('wp_ajax_nopriv_ht_clear_saved_zone', 'ht_ajax_clear_saved_zone');

function ht_ajax_clear_saved_zone() {
    check_ajax_referer('ht_shipping_nonce', 'nonce');
    
    if (WC()->session) {
        WC()->session->set('ht_shipping_zone', null);
        WC()->session->set('ht_postal_code', null);
    }
    
    wp_send_json_success(['message' => 'Zona limpiada correctamente']);
}


// ============================================
// 4. ENQUEUE SCRIPTS Y ESTILOS
// ============================================

add_action('wp_enqueue_scripts', 'ht_enqueue_shipping_calculator_assets');

function ht_enqueue_shipping_calculator_assets() {
    // CSS
    wp_enqueue_style(
        'ht-shipping-calculator',
        get_stylesheet_directory_uri() . '/assets/css/envio-gratis-calculator.css',
        [],
        '1.0.0'
    );
    
    // JavaScript
    wp_enqueue_script(
        'ht-shipping-calculator',
        get_stylesheet_directory_uri() . '/assets/js/envio-gratis-calculator.js',
        ['jquery'],
        '1.0.0',
        true
    );
    
    // Localizar script con datos
    wp_localize_script('ht-shipping-calculator', 'htShipping', [
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('ht_shipping_nonce'),
        'cart_url' => wc_get_cart_url(),
        'shop_url' => get_permalink(wc_get_page_id('shop'))
    ]);
}


// ============================================
// 5. FRAGMENTOS DEL CARRITO (PARA ACTUALIZACIÓN AUTOMÁTICA)
// ============================================

add_filter('woocommerce_add_to_cart_fragments', 'ht_shipping_progress_fragment');

function ht_shipping_progress_fragment($fragments) {
    if (!WC()->session) {
        return $fragments;
    }
    
    $zone = WC()->session->get('ht_shipping_zone', 'nacional');
    $progress = ht_calculate_free_shipping_progress($zone);
    
    ob_start();
    ?>
    <div class="ht-shipping-progress-data" 
         data-zone="<?php echo esc_attr($zone); ?>"
         data-cart-total="<?php echo $progress['cart_total']; ?>"
         data-threshold="<?php echo $progress['threshold']; ?>"
         data-remaining="<?php echo $progress['remaining']; ?>"
         data-percentage="<?php echo round($progress['percentage']); ?>"
         data-is-free="<?php echo $progress['is_free'] ? 'true' : 'false'; ?>"
         style="display: none;">
    </div>
    <?php
    
    $fragments['.ht-shipping-progress-data'] = ob_get_clean();
    
    return $fragments;
}


// ============================================
// 6. SHORTCODE PARA BARRA DE PROGRESO
// ============================================

add_shortcode('ht_shipping_progress_bar', 'ht_shipping_progress_bar_shortcode');

function ht_shipping_progress_bar_shortcode($atts) {
    if (!class_exists('WooCommerce')) {
        return '';
    }
    
    $atts = shortcode_atts([
        'show_button' => 'yes',
        'position' => 'inline'
    ], $atts);
    
    $zone = WC()->session ? WC()->session->get('ht_shipping_zone', 'nacional') : 'nacional';
    $postal_code = WC()->session ? WC()->session->get('ht_postal_code', '') : '';
    $progress = ht_calculate_free_shipping_progress($zone);
    
    ob_start();
    ?>
    <div class="ht-shipping-progress-bar-container" data-position="<?php echo esc_attr($atts['position']); ?>">
        <?php if (!empty($postal_code)): ?>
            <div class="ht-shipping-progress-bar">
                <div class="ht-progress-info">
                    <span class="ht-zone-badge">
                        <i class="bi bi-geo-alt-fill"></i> 
                        <?php echo esc_html($progress['zone_name']); ?>
                        (CP: <?php echo esc_html($postal_code); ?>)
                    </span>
                    <span class="ht-progress-text">
                        <?php if ($progress['is_free']): ?>
                            <i class="bi bi-check-circle-fill text-success"></i>
                            <strong>¡Envío gratis!</strong>
                        <?php else: ?>
                            Te faltan <strong>$<?php echo number_format($progress['remaining'], 0, ',', '.'); ?></strong> para envío gratis
                        <?php endif; ?>
                    </span>
                </div>
                <div class="ht-progress-bar-wrapper">
                    <div class="ht-progress-bar-fill" 
                         style="width: <?php echo min(100, $progress['percentage']); ?>%"
                         data-percentage="<?php echo round($progress['percentage']); ?>">
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if ($atts['show_button'] === 'yes'): ?>
            <button type="button" 
                    class="btn btn-sm btn-outline-primary ht-open-shipping-calculator"
                    data-bs-toggle="modal" 
                    data-bs-target="#htShippingCalculatorModal">
                <i class="bi bi-calculator"></i>
                <?php echo empty($postal_code) ? 'Calcular envío gratis' : 'Cambiar código postal'; ?>
            </button>
        <?php endif; ?>
    </div>
    <?php
    
    return ob_get_clean();
}


// ============================================
// 7. MODAL HTML (AGREGADO AL FOOTER)
// ============================================

add_action('wp_footer', 'ht_add_shipping_calculator_modal');

function ht_add_shipping_calculator_modal() {
    if (!class_exists('WooCommerce')) {
        return;
    }
    
    $saved_zone = WC()->session ? WC()->session->get('ht_shipping_zone', 'nacional') : 'nacional';
    $saved_postal_code = WC()->session ? WC()->session->get('ht_postal_code', '') : '';
    $zones = ht_get_shipping_zones_config();
    ?>
    
    <!-- Modal Calculadora de Envío Gratis -->
    <div class="modal fade" id="htShippingCalculatorModal" tabindex="-1" aria-labelledby="htShippingCalculatorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background: var(--primary-color); color: white;">
                    <h5 class="modal-title fw-bold" id="htShippingCalculatorModalLabel">
                        <i class="bi bi-truck"></i> Calculá tu Envío Gratis
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    <!-- Formulario de Código Postal -->
                    <form id="htShippingCalculatorForm">
                        <div class="mb-4">
                            <label for="htPostalCode" class="form-label fw-bold">
                                <i class="bi bi-geo-alt-fill text-primary"></i> Ingresá tu código postal:
                            </label>
                            <input type="text" 
                                   class="form-control form-control-lg" 
                                   id="htPostalCode" 
                                   name="postal_code"
                                   placeholder="Ej: 1900"
                                   value="<?php echo esc_attr($saved_postal_code); ?>"
                                   pattern="[0-9]{4}"
                                   maxlength="4"
                                   required>
                            <small class="form-text text-muted">
                                Ingresá tu código postal de 4 dígitos para calcular si tu compra califica para envío gratis.
                            </small>
                        </div>
                        
                        <!-- Zonas de Envío Info -->
                        <div class="shipping-zones-info mb-4">
                            <h6 class="fw-bold mb-3">Montos para envío gratis:</h6>
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="zone-info-card" style="background: linear-gradient(135deg, rgba(238,40,91,0.1), rgba(238,40,91,0.05)); border-radius: 1rem; padding: 1rem; border: 2px solid var(--primary-color);">
                                        <div class="zone-icon text-center mb-2" style="font-size: 2rem;">🏙️</div>
                                        <h6 class="fw-bold text-center" style="color: var(--primary-color);">La Plata</h6>
                                        <p class="text-center mb-0 fw-bold" style="font-size: 1.1rem;">
                                            $<?php echo number_format($zones['la_plata']['threshold'], 0, ',', '.'); ?>
                                        </p>
                                        <small class="text-muted d-block text-center">CP: 1900-1925</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="zone-info-card" style="background: linear-gradient(135deg, rgba(83,79,181,0.1), rgba(83,79,181,0.05)); border-radius: 1rem; padding: 1rem; border: 2px solid var(--secondary-color);">
                                        <div class="zone-icon text-center mb-2" style="font-size: 2rem;">🇦🇷</div>
                                        <h6 class="fw-bold text-center" style="color: var(--secondary-color);">Nacional</h6>
                                        <p class="text-center mb-0 fw-bold" style="font-size: 1.1rem;">
                                            $<?php echo number_format($zones['nacional']['threshold'], 0, ',', '.'); ?>
                                        </p>
                                        <small class="text-muted d-block text-center">Resto del país</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Resultado -->
                        <div id="htShippingResult" class="shipping-result" style="display: none;">
                            <div class="alert" role="alert">
                                <div class="result-content"></div>
                            </div>
                            
                            <!-- Barra de Progreso -->
                            <div class="progress-container mb-3" style="display: none;">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="progress-label fw-bold"></span>
                                    <span class="progress-percentage fw-bold"></span>
                                </div>
                                <div class="progress" style="height: 20px; border-radius: 10px;">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                         role="progressbar" 
                                         style="width: 0%">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Botones -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg" id="htCalculateBtn">
                                <i class="bi bi-calculator"></i> Calcular Envío
                            </button>
                            <?php if (!empty($saved_postal_code)): ?>
                                <button type="button" class="btn btn-outline-secondary" id="htClearZoneBtn">
                                    <i class="bi bi-x-circle"></i> Limpiar
                                </button>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
                
                <div class="modal-footer justify-content-center" style="background: var(--light-bg);">
                    <small class="text-muted text-center">
                        <i class="bi bi-info-circle"></i> 
                        El envío gratis se calcula sobre el subtotal del carrito (sin costo de envío)
                    </small>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Data fragment para actualización automática -->
    <div class="ht-shipping-progress-data" style="display: none;"></div>
    
    <?php
}


// ============================================
// 8. BOTÓN EN EL MENÚ (HOOK PERSONALIZADO)
// ============================================

/**
 * Agregar botón de envío gratis en el header
 * Se puede llamar desde el template con: do_action('ht_shipping_calculator_button');
 */
add_action('ht_shipping_calculator_button', 'ht_display_shipping_calculator_button');

function ht_display_shipping_calculator_button() {
    if (!class_exists('WooCommerce')) {
        return;
    }
    ?>
    <li class="nav-item">
        <button type="button" 
                class="btn btn-outline-primary ht-open-shipping-calculator" 
                data-bs-toggle="modal" 
                data-bs-target="#htShippingCalculatorModal">
            <i class="bi bi-truck"></i>
            <span class="d-none d-lg-inline ms-1">Envío Gratis</span>
        </button>
    </li>
    <?php
}


// ============================================
// 9. WIDGET PARA SIDEBAR (OPCIONAL)
// ============================================

add_action('widgets_init', 'ht_register_shipping_calculator_widget');

function ht_register_shipping_calculator_widget() {
    register_widget('HT_Shipping_Calculator_Widget');
}

class HT_Shipping_Calculator_Widget extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'ht_shipping_calculator',
            'Calculadora de Envío Gratis',
            ['description' => 'Muestra la calculadora de envío gratis']
        );
    }
    
    public function widget($args, $instance) {
        if (!class_exists('WooCommerce')) {
            return;
        }
        
        echo $args['before_widget'];
        
        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }
        
        echo do_shortcode('[ht_shipping_progress_bar show_button="yes" position="widget"]');
        
        echo $args['after_widget'];
    }
    
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : 'Envío Gratis';
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">Título:</label>
            <input class="widefat" 
                   id="<?php echo $this->get_field_id('title'); ?>" 
                   name="<?php echo $this->get_field_name('title'); ?>" 
                   type="text" 
                   value="<?php echo esc_attr($title); ?>">
        </p>
        <?php
    }
    
    public function update($new_instance, $old_instance) {
        $instance = [];
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        return $instance;
    }
}


// ============================================
// 10. COMPATIBILIDAD CON SISTEMA EXISTENTE
// ============================================

/**
 * Override de la función existente ht_get_free_shipping_progress
 * para usar zona dinámica
 */
if (!function_exists('ht_get_free_shipping_progress_dynamic')) {
    function ht_get_free_shipping_progress_dynamic() {
        $zone = WC()->session ? WC()->session->get('ht_shipping_zone', 'nacional') : 'nacional';
        return ht_calculate_free_shipping_progress($zone);
    }
}

/**
 * Actualizar el AJAX existente para usar zona dinámica
 */
add_action('wp_ajax_get_shipping_progress_dynamic', 'ht_ajax_get_shipping_progress_dynamic');
add_action('wp_ajax_nopriv_get_shipping_progress_dynamic', 'ht_ajax_get_shipping_progress_dynamic');

function ht_ajax_get_shipping_progress_dynamic() {
    $zone = WC()->session ? WC()->session->get('ht_shipping_zone', 'nacional') : 'nacional';
    $progress = ht_calculate_free_shipping_progress($zone);
    wp_send_json_success($progress);
}