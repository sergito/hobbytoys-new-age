<?php
/**
 * HobbyToys Pro Theme Functions
 *
 * Tema ultra profesional para tienda de juguetes
 * Optimizado para SEO, performance y conversiones
 *
 * @package HobbyToys_Pro
 * @version 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Theme Constants
 */
define('HOBBYTOYS_VERSION', '1.0.0');
define('HOBBYTOYS_DIR', get_stylesheet_directory());
define('HOBBYTOYS_URI', get_stylesheet_directory_uri());

/**
 * =============================================================================
 * 1. THEME SETUP
 * =============================================================================
 */

add_action('after_setup_theme', 'hobbytoys_setup');
function hobbytoys_setup() {

    // Theme supports
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption']);
    add_theme_support('customize-selective-refresh-widgets');

    // WooCommerce support
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');

    // Image sizes for products
    add_image_size('hobbytoys-product-card', 400, 400, true);
    add_image_size('hobbytoys-product-featured', 800, 800, true);
    add_image_size('hobbytoys-category-thumb', 150, 150, true);

    // Content width
    $GLOBALS['content_width'] = 1200;
}

/**
 * =============================================================================
 * 2. ENQUEUE STYLES & SCRIPTS
 * =============================================================================
 */

add_action('wp_enqueue_scripts', 'hobbytoys_enqueue_assets', 20);
function hobbytoys_enqueue_assets() {

    // Parent theme style
    wp_enqueue_style('bootscore-parent', get_template_directory_uri() . '/style.css');

    // Bootstrap Icons (CDN)
    wp_enqueue_style('bootstrap-icons', 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css', [], '1.11.3');

    // Google Fonts
    wp_enqueue_style('hobbytoys-fonts', 'https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700&display=swap', [], null);

    // Theme main styles (compiled SCSS)
    wp_enqueue_style('hobbytoys-main', HOBBYTOYS_URI . '/assets/css/main.css', ['bootscore-parent'], HOBBYTOYS_VERSION);

    // Custom theme scripts
    wp_enqueue_script('hobbytoys-main', HOBBYTOYS_URI . '/assets/js/main.js', ['jquery'], HOBBYTOYS_VERSION, true);

    // Localize script for AJAX
    wp_localize_script('hobbytoys-main', 'hobbytoys_ajax', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('hobbytoys_nonce')
    ]);

    // WooCommerce specific scripts
    if (is_woocommerce() || is_cart() || is_checkout()) {
        wp_enqueue_script('hobbytoys-woocommerce', HOBBYTOYS_URI . '/assets/js/woocommerce.js', ['jquery'], HOBBYTOYS_VERSION, true);
    }
}

/**
 * =============================================================================
 * 3. LOAD MODULAR FUNCTIONS
 * =============================================================================
 */

// Core functions
require_once HOBBYTOYS_DIR . '/inc/theme-setup.php';
require_once HOBBYTOYS_DIR . '/inc/customizer.php';

// WooCommerce functions
if (class_exists('WooCommerce')) {
    require_once HOBBYTOYS_DIR . '/inc/woocommerce-setup.php';
    require_once HOBBYTOYS_DIR . '/inc/woocommerce-product-card.php';
    require_once HOBBYTOYS_DIR . '/inc/woocommerce-single-product.php';
    require_once HOBBYTOYS_DIR . '/inc/woocommerce-checkout.php';
    require_once HOBBYTOYS_DIR . '/inc/woocommerce-seo.php';
}

/**
 * =============================================================================
 * 4. UTILITY FUNCTIONS
 * =============================================================================
 */

/**
 * Debug function for development
 *
 * @param mixed $var Variable to debug
 * @param bool $exit Exit after output
 */
function ht_debug($var, $exit = false) {
    if (!WP_DEBUG) return;

    echo '<pre style="background:#1e1e1e;color:#dcdcdc;padding:15px;border-radius:5px;font-size:12px;line-height:1.5;overflow:auto;">';
    if (is_array($var) || is_object($var)) {
        print_r($var);
    } else {
        var_dump($var);
    }
    echo '</pre>';

    if ($exit) exit;
}

/**
 * Get theme color variable
 *
 * @param string $color Color name (primary, secondary, accent, etc.)
 * @return string Color hex code
 */
function ht_get_color($color = 'primary') {
    $colors = [
        'primary'   => '#EE285B',
        'secondary' => '#534fb5',
        'accent'    => '#FFB900',
        'cyan'      => '#0dcaf0',
        'green'     => '#198754',
        'orange'    => '#fd7e14',
        'red'       => '#dc3545',
        'dark'      => '#2c3e50',
        'light'     => '#f8f9fa',
    ];

    return isset($colors[$color]) ? $colors[$color] : $colors['primary'];
}

/**
 * =============================================================================
 * 5. SECURITY & PERFORMANCE
 * =============================================================================
 */

// Remove WordPress emojis (performance)
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('admin_print_styles', 'print_emoji_styles');
remove_filter('the_content_feed', 'wp_staticize_emoji');
remove_filter('comment_text_rss', 'wp_staticize_emoji');
remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

// Remove unnecessary meta tags
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wp_shortlink_wp_head');

/**
 * =============================================================================
 * 6. SEO ENHANCEMENTS
 * =============================================================================
 */

/**
 * Add preconnect for external resources
 */
add_action('wp_head', 'ht_add_resource_hints', 1);
function ht_add_resource_hints() {
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
    echo '<link rel="preconnect" href="https://cdn.jsdelivr.net">' . "\n";
    echo '<link rel="dns-prefetch" href="//www.google-analytics.com">' . "\n";
}

/**
 * =============================================================================
 * END OF FUNCTIONS.PHP
 * Todas las demás funciones están modularizadas en /inc/
 * =============================================================================
 */
