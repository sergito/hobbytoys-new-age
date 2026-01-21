<?php
/**
 * Theme Setup Functions
 *
 * @package HobbyToys_Pro
 */

if (!defined('ABSPATH')) exit;

/**
 * Register Navigation Menus
 */
add_action('init', 'ht_register_menus');
function ht_register_menus() {
    register_nav_menus([
        'primary'    => __('Menú Principal', 'hobbytoys-pro'),
        'categories' => __('Menú Categorías', 'hobbytoys-pro'),
        'footer'     => __('Menú Footer', 'hobbytoys-pro'),
    ]);
}

/**
 * Register Widget Areas
 */
add_action('widgets_init', 'ht_register_sidebars');
function ht_register_sidebars() {

    // Shop Sidebar
    register_sidebar([
        'name'          => __('Sidebar Tienda', 'hobbytoys-pro'),
        'id'            => 'shop-sidebar',
        'description'   => __('Sidebar para páginas de tienda y categorías', 'hobbytoys-pro'),
        'before_widget' => '<div id="%1$s" class="widget %2$s mb-4">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title h5 mb-3">',
        'after_title'   => '</h3>',
    ]);

    // Footer columns
    for ($i = 1; $i <= 4; $i++) {
        register_sidebar([
            'name'          => sprintf(__('Footer Columna %d', 'hobbytoys-pro'), $i),
            'id'            => 'footer-' . $i,
            'description'   => sprintf(__('Columna %d del footer', 'hobbytoys-pro'), $i),
            'before_widget' => '<div id="%1$s" class="widget %2$s mb-4">',
            'after_widget'  => '</div>',
            'before_title'  => '<h4 class="widget-title h6 mb-3">',
            'after_title'   => '</h4>',
        ]);
    }
}
