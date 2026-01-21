<?php
/**
 * Customer processing order email - Hobby Toys Theme
 * 
 * @package HobbyToys/Templates/Emails
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

// Incluir funciones de email
include_once get_stylesheet_directory() . '/woocommerce/emails/email-functions.php';

/*
 * @hooked hobbytoys_email_header() - Salida del header del email
 */
do_action('woocommerce_email_header', $email_heading, $email);

// Obtener mensaje personalizado
$status_message = hobbytoys_get_status_message('processing', $order);
?>

<!-- Introducción del email -->
<div class="email-introduction" style="margin-bottom: 2rem; padding: 1.5rem; background-color: #f4efe8; border-radius: 1rem; border-left: 4px solid #EE285B;">
    
    <!-- Saludo personalizado -->
    <p style="margin: 0 0 1rem 0; font-size: 1.1rem; font-weight: 700;">
        <?php
        if (!empty($order->get_billing_first_name())) {
            printf(__('¡Hola %s! 👋', 'woocommerce'), esc_html($order->get_billing_first_name()));
        } else {
            echo __('¡Hola! 👋', 'woocommerce');
        }
        ?>
    </p>
    
    <!-- Mensaje principal -->
    <h2 style="color: #EE285B; font-size: 1.5rem; margin: 0 0 1rem 0; font-weight: 900;">
        <?php echo esc_html($status_message['title']); ?>
    </h2>
    
    <p style="margin: 0 0 1rem 0; font-weight: 600; line-height: 1.6;">
        <?php echo esc_html($status_message['message']); ?>
    </p>
    
    <!-- Estado y número de pedido -->
    <div style="display: flex; align-items: center; gap: 1rem; margin-top: 1rem; flex-wrap: wrap;">
        <?php echo hobbytoys_get_order_status_badge('processing'); ?>
        <span style="font-weight: 700; color: #534fb5;">
            <?php printf(__('Pedido #%s', 'woocommerce'), $order->get_order_number()); ?>
        </span>
        <span style="font-weight: 600; color: #6c757d;">
            <?php echo hobbytoys_format_date($order->get_date_created()); ?>
        </span>
    </div>
</div>

<!-- Botones de acción -->
<div style="text-align: center; margin: 2rem 0;">
    <?php
    echo hobbytoys_get_action_button(
        __('Ver Pedido', 'woocommerce'),
        $order->get_view_order_url(),
        'primary'
    );
    
    echo hobbytoys_get_action_button(
        __('Seguir Comprando', 'woocommerce'),
        wc_get_page_permalink('shop'),
        'secondary'
    );
    ?>
</div>

<!-- Detalles del pedido -->
<div style="margin: 2rem 0;">
    <h2 style="color: #534fb5; font-size: 1.3rem; margin-bottom: 1rem; font-weight: 900;">
        📦 <?php _e('Detalles de tu Pedido', 'woocommerce'); ?>
    </h2>
    
    <?php echo hobbytoys_format_order_table($order, $sent_to_admin); ?>
</div>

<!-- Información del cliente -->
<?php echo hobbytoys_format_customer_details($order); ?>

<!-- Información de seguimiento (si está disponible) -->
<?php if ($order->get_meta('_tracking_number')) : ?>
<div class="info-box" style="background-color: #e8f5e8; padding: 1.5rem; border-radius: 1rem; border-left: 4px solid #27ae60; margin: 2rem 0;">
    <h3 style="margin-top: 0; color: #27ae60; font-size: 1.1rem; font-weight: 900;">
        🚛 <?php _e('Información de Seguimiento', 'woocommerce'); ?>
    </h3>
    <p style="margin: 0; font-weight: 600;">
        <?php _e('Número de seguimiento:', 'woocommerce'); ?> 
        <strong><?php echo esc_html($order->get_meta('_tracking_number')); ?></strong>
    </p>
    <?php if ($order->get_meta('_tracking_url')) : ?>
        <p style="margin: 0.5rem 0 0 0;">
            <a href="<?php echo esc_url($order->get_meta('_tracking_url')); ?>" 
               style="color: #27ae60; text-decoration: none; font-weight: 700;">
                <?php _e('Rastrear mi pedido →', 'woocommerce'); ?>
            </a>
        </p>
    <?php endif; ?>
</div>
<?php endif; ?>

<!-- Tiempo estimado de entrega -->
<div class="info-box" style="background-color: #fff3cd; padding: 1.5rem; border-radius: 1rem; border-left: 4px solid #ffb900; margin: 2rem 0;">
    <h3 style="margin-top: 0; color: #e67e22; font-size: 1.1rem; font-weight: 900;">
        ⏰ <?php _e('Tiempo Estimado de Entrega', 'woocommerce'); ?>
    </h3>
    <p style="margin: 0; font-weight: 600;">
        <?php 
        $estimated_delivery = $order->get_meta('_estimated_delivery');
        if ($estimated_delivery) {
            echo esc_html($estimated_delivery);
        } else {
            _e('Te contactaremos pronto con los detalles de entrega.', 'woocommerce');
        }
        ?>
    </p>
</div>

<!-- Contenido adicional personalizado -->
<?php if ($additional_content) : ?>
<div class="email-additional-content" style="background-color: #f4efe8; padding: 1.5rem; border-radius: 1rem; margin: 2rem 0; border: 1px dashed #EE285B;">
    <h3 style="margin-top: 0; color: #EE285B; font-weight: 900;">
        💬 <?php _e('Información Adicional', 'woocommerce'); ?>
    </h3>
    <?php echo wp_kses_post(wpautop(wptexturize($additional_content))); ?>
</div>
<?php endif; ?>

<!-- Productos relacionados o recomendaciones -->
<?php 
$related_products = hobbytoys_get_related_products($order, 4);
if (!empty($related_products)) : 
?>
<div style="margin: 2rem 0;">
    <h3 style="color: #534fb5; font-weight: 900; text-align: center; margin-bottom: 1rem;">
        ⭐ <?php _e('Te podría interesar también', 'woocommerce'); ?>
    </h3>
    <div style="text-align: center;">
        <?php
        echo hobbytoys_get_action_button(
            __('Ver Productos Relacionados', 'woocommerce'),
            wc_get_page_permalink('shop'),
            'outline'
        );
        ?>
    </div>
</div>
<?php endif; ?>

<!-- Mensaje de agradecimiento -->
<div style="text-align: center; padding: 2rem; background: linear-gradient(135deg, #f4efe8 0%, rgba(238, 40, 91, 0.05) 100%); border-radius: 1rem; margin: 2rem 0;">
    <h3 style="color: #EE285B; font-weight: 900; margin: 0 0 1rem 0;">
        🙏 <?php _e('¡Gracias por tu confianza!', 'woocommerce'); ?>
    </h3>
    <p style="margin: 0; font-weight: 600; color: #534fb5;">
        <?php _e('Estamos emocionados de ser parte de tu experiencia de compra.', 'woocommerce'); ?>
    </p>
</div>

<?php
/*
 * @hooked hobbytoys_email_footer() - Salida del footer del email
 */
do_action('woocommerce_email_footer', $email);
?>