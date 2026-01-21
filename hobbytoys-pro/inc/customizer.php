<?php
/**
 * Theme Customizer
 *
 * @package HobbyToys_Pro
 */

if (!defined('ABSPATH')) exit;

add_action('customize_register', 'ht_customize_register');
function ht_customize_register($wp_customize) {

    /**
     * HobbyToys Pro Settings Section
     */
    $wp_customize->add_section('hobbytoys_settings', [
        'title'    => __('HobbyToys Pro Settings', 'hobbytoys-pro'),
        'priority' => 30,
    ]);

    // Free Shipping Threshold
    $wp_customize->add_setting('ht_free_shipping_min', [
        'default'           => 90000,
        'sanitize_callback' => 'absint',
    ]);

    $wp_customize->add_control('ht_free_shipping_min', [
        'label'       => __('Monto Envío Gratis', 'hobbytoys-pro'),
        'description' => __('Monto mínimo para envío gratis (en pesos)', 'hobbytoys-pro'),
        'section'     => 'hobbytoys_settings',
        'type'        => 'number',
    ]);

    // WhatsApp Number
    $wp_customize->add_setting('ht_whatsapp_number', [
        'default'           => '5492215608027',
        'sanitize_callback' => 'sanitize_text_field',
    ]);

    $wp_customize->add_control('ht_whatsapp_number', [
        'label'       => __('Número WhatsApp', 'hobbytoys-pro'),
        'description' => __('Número completo con código de país (ej: 5492215608027)', 'hobbytoys-pro'),
        'section'     => 'hobbytoys_settings',
        'type'        => 'text',
    ]);
}
