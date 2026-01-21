<?php
/**
 * WooCommerce Setup & Configuration
 *
 * @package HobbyToys_Pro
 */

if (!defined('ABSPATH')) exit;

/**
 * =============================================================================
 * WOOCOMMERCE GENERAL SETUP
 * =============================================================================
 */

/**
 * Disable default WooCommerce styles
 */
add_filter('woocommerce_enqueue_styles', '__return_empty_array');

/**
 * Custom product loop columns
 */
add_filter('loop_shop_columns', 'ht_loop_columns');
function ht_loop_columns() {
    return 4; // 4 products per row
}

/**
 * Products per page
 */
add_filter('loop_shop_per_page', 'ht_products_per_page');
function ht_products_per_page() {
    return 20;
}

/**
 * Custom add to cart text
 */
add_filter('woocommerce_product_single_add_to_cart_text', 'ht_custom_cart_button_text');
add_filter('woocommerce_product_add_to_cart_text', 'ht_custom_cart_button_text');
function ht_custom_cart_button_text() {
    return __('Agregar al Carrito', 'hobbytoys-pro');
}

/**
 * =============================================================================
 * PRODUCT CATEGORIES
 * =============================================================================
 */

/**
 * Category colors and icons configuration
 * This will be used in product cards for visual identification
 */
function ht_get_category_config() {
    return [
        // Categorías principales con iconos Bootstrap y colores
        'aire-libre' => [
            'icon'  => 'bi-sun-fill',
            'color' => '#0dcaf0',
            'name'  => 'Aire Libre'
        ],
        'arte-manualidades' => [
            'icon'  => 'bi-palette-fill',
            'color' => '#EE285B',
            'name'  => 'Arte y Manualidades'
        ],
        'bebes' => [
            'icon'  => 'bi-balloon-heart-fill',
            'color' => '#FFB900',
            'name'  => 'Bebés'
        ],
        'bloques-construccion' => [
            'icon'  => 'bi-bricks',
            'color' => '#fd7e14',
            'name'  => 'Bloques y Construcción'
        ],
        'ciencia-educacion' => [
            'icon'  => 'bi-mortarboard-fill',
            'color' => '#534fb5',
            'name'  => 'Ciencia y Educación'
        ],
        'coleccionables' => [
            'icon'  => 'bi-star-fill',
            'color' => '#FFB900',
            'name'  => 'Coleccionables'
        ],
        'deportes' => [
            'icon'  => 'bi-trophy-fill',
            'color' => '#198754',
            'name'  => 'Deportes'
        ],
        'disfraces' => [
            'icon'  => 'bi-mask',
            'color' => '#d63384',
            'name'  => 'Disfraces'
        ],
        'electronica' => [
            'icon'  => 'bi-controller',
            'color' => '#6f42c1',
            'name'  => 'Electrónica'
        ],
        'figuras-accion' => [
            'icon'  => 'bi-person-arms-up',
            'color' => '#dc3545',
            'name'  => 'Figuras de Acción'
        ],
        'instrumentos-musicales' => [
            'icon'  => 'bi-music-note-beamed',
            'color' => '#0d6efd',
            'name'  => 'Música'
        ],
        'juegos-mesa' => [
            'icon'  => 'bi-dice-5-fill',
            'color' => '#198754',
            'name'  => 'Juegos de Mesa'
        ],
        'munecas' => [
            'icon'  => 'bi-heart-fill',
            'color' => '#EE285B',
            'name'  => 'Muñecas'
        ],
        'peluches' => [
            'icon'  => 'bi-hearts',
            'color' => '#d63384',
            'name'  => 'Peluches'
        ],
        'puzzles' => [
            'icon'  => 'bi-puzzle-fill',
            'color' => '#0dcaf0',
            'name'  => 'Puzzles'
        ],
        'vehiculos' => [
            'icon'  => 'bi-car-front-fill',
            'color' => '#fd7e14',
            'name'  => 'Vehículos'
        ],
    ];
}

/**
 * Get category data by slug
 */
function ht_get_category_data($slug) {
    $config = ht_get_category_config();

    if (isset($config[$slug])) {
        return $config[$slug];
    }

    // Default fallback with generated color
    return [
        'icon'  => 'bi-box-seam-fill',
        'color' => '#' . substr(md5($slug), 0, 6),
        'name'  => ucwords(str_replace(['-', '_'], ' ', $slug))
    ];
}

/**
 * =============================================================================
 * AGE BADGES CONFIGURATION
 * =============================================================================
 */

/**
 * Age range colors for product badges
 */
function ht_get_age_colors() {
    return [
        '0-a-3-anos'  => '#EE285B',  // Rosa (bebés)
        '4-a-6-anos'  => '#0dcaf0',  // Cyan (preescolar)
        '7-a-12-anos' => '#FFB900',  // Amarillo (escolar)
        '12adultos'   => '#198754',  // Verde (adolescentes/adultos)
    ];
}

/**
 * Get age badge color
 */
function ht_get_age_color($age_slug) {
    $colors = ht_get_age_colors();
    return isset($colors[$age_slug]) ? $colors[$age_slug] : '#6c757d';
}

/**
 * =============================================================================
 * CART & CHECKOUT
 * =============================================================================
 */

/**
 * Enable cart fragments
 */
add_filter('woocommerce_add_to_cart_fragments', 'ht_cart_count_fragments');
function ht_cart_count_fragments($fragments) {
    ob_start();
    ?>
    <span class="cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
    <?php
    $fragments['.cart-count'] = ob_get_clean();
    return $fragments;
}

/**
 * Get cart count (for AJAX)
 */
add_action('wp_ajax_ht_get_cart_count', 'ht_ajax_get_cart_count');
add_action('wp_ajax_nopriv_ht_get_cart_count', 'ht_ajax_get_cart_count');
function ht_ajax_get_cart_count() {
    wp_send_json([
        'count' => WC()->cart->get_cart_contents_count()
    ]);
}

/**
 * =============================================================================
 * BREADCRUMBS
 * =============================================================================
 */

/**
 * Custom breadcrumb defaults
 */
add_filter('woocommerce_breadcrumb_defaults', 'ht_breadcrumb_defaults');
function ht_breadcrumb_defaults() {
    return [
        'delimiter'   => '<i class="bi bi-chevron-right mx-2"></i>',
        'wrap_before' => '<nav class="woocommerce-breadcrumb mb-4" aria-label="breadcrumb"><ol class="breadcrumb mb-0">',
        'wrap_after'  => '</ol></nav>',
        'before'      => '<li class="breadcrumb-item">',
        'after'       => '</li>',
        'home'        => _x('Inicio', 'breadcrumb', 'hobbytoys-pro'),
    ];
}
