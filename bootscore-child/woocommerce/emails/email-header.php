<?php
/**
 * Email Header Template - Hobby Toys Theme
 * 
 * @package HobbyToys/Templates/Emails
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Obtener configuraciones del tema
$site_name = get_bloginfo('name');
$site_url = home_url();
$logo_url = get_theme_mod('custom_logo') ? wp_get_attachment_image_url(get_theme_mod('custom_logo'), 'full') : '';
$primary_color = get_theme_mod('primary_color', '#EE285B');
$secondary_color = get_theme_mod('secondary_color', '#534fb5');

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo esc_html($email_heading); ?></title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;900&display=swap" rel="stylesheet">
    
    <!-- Estilos inline para compatibilidad con clientes de email -->
    <style type="text/css">
        <?php include get_template_directory() . '/woocommerce/emails/css/email-styles.css'; ?>
    </style>
    
    <!--[if mso]>
    <style type="text/css">
        .email-container { width: 600px !important; }
        .btn { mso-style-priority: 100 !important; }
    </style>
    <![endif]-->
</head>
<body>
    <div class="email-container">
        
        <!-- Header -->
        <div class="email-header" style="background: linear-gradient(135deg, <?php echo esc_attr($primary_color); ?> 0%, <?php echo esc_attr($secondary_color); ?> 100%);">
            <?php if ($logo_url) : ?>
                <img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr($site_name); ?>" class="logo" style="max-height: 60px; margin-bottom: 1rem;">
            <?php endif; ?>
            
            <h1 style="margin: 0; color: #ffffff; font-size: 2rem; font-weight: 900; position: relative; z-index: 1;">
                <?php echo esc_html($email_heading); ?>
            </h1>
            
            <?php if (isset($order) && $order) : ?>
                <p style="margin: 0.5rem 0 0 0; color: rgba(255,255,255,0.9); font-weight: 400;">
                    <?php printf(__('Pedido #%s', 'woocommerce'), $order->get_order_number()); ?>
                </p>
            <?php endif; ?>
        </div>
        
        <!-- Inicio del contenido -->
        <div class="email-content"><?php