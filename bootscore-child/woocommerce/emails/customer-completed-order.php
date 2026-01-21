<?php
/**
 * Customer completed order email - Hobby Toys Theme
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
$status_message = hobbytoys_get_status_message('completed', $order);
?>

<!-- Introducción del email -->
<div class="email-introduction" style="margin-bottom: 2rem; padding: 2rem; background: linear-gradient(135deg, #e8f5e8 0%, rgba(39, 174, 96, 0.1) 100%); border-radius: 1rem; border-left: 4px solid #27ae60; text-align: center;">
    
    <!-- Celebración -->
    <div style="font-size: 3rem; margin-bottom: 1rem;">🎉✨🎊</div>
    
    <!-- Saludo personalizado -->
    <p style="margin: 0 0 1rem 0; font-size: 1.2rem; font-weight: 700;">
        <?php
        if (!empty($order->get_billing_first_name())) {
            printf(__('¡Felicidades %s!', 'woocommerce'), esc_html($order->get_billing_first_name()));
        } else {
            echo __('¡Felicidades!', 'woocommerce');
        }
        ?>
    </p>
    
    <!-- Mensaje principal -->
    <h2 style="color: #27ae60; font-size: 1.8rem; margin: 0 0 1rem 0; font-weight: 900;">
        <?php echo esc_html($status_message['title']); ?>
    </h2>
    
    <p style="margin: 0 0 1rem 0; font-weight: 600; line-height: 1.6; font-size: 1.1rem;">
        <?php echo esc_html($status_message['message']); ?>
    </p>
    
    <!-- Estado y número de pedido -->
    <div style="display: flex; align-items: center; justify-content: center; gap: 1rem; margin-top: 1.5rem; flex-wrap: wrap;">
        <?php echo hobbytoys_get_order_status_badge('completed'); ?>
        <span style="font-weight: 700; color: #534fb5; font-size: 1.1rem;">
            <?php printf(__('Pedido #%s', 'woocommerce'), $order->get_order_number()); ?>
        </span>
    </div>
</div>

<!-- Botones de acción principales -->
<div style="text-align: center; margin: 2rem 0;">
    <?php
    echo hobbytoys_get_action_button(
        __('📝 Dejar una Reseña', 'woocommerce'),
        wc_get_page_permalink('shop'), // Cambiar por URL de reseñas
        'primary'
    );
    
    echo hobbytoys_get_action_button(
        __('🛍️ Comprar de Nuevo', 'woocommerce'),
        wc_get_page_permalink('shop'),
        'secondary'
    );
    
    echo hobbytoys_get_action_button(
        __('👀 Ver Pedido', 'woocommerce'),
        $order->get_view_order_url(),
        'outline'
    );
    ?>
</div>

<!-- Resumen del pedido completado -->
<div style="margin: 2rem 0;">
    <h2 style="color: #534fb5; font-size: 1.3rem; margin-bottom: 1rem; font-weight: 900; text-align: center;">
        📋 <?php _e('Resumen de tu Compra', 'woocommerce'); ?>
    </h2>
    
    <?php echo hobbytoys_format_order_table($order, $sent_to_admin); ?>
</div>

<!-- Información de entrega -->
<div class="info-box" style="background-color: #e8f5e8; padding: 1.5rem; border-radius: 1rem; border-left: 4px solid #27ae60; margin: 2rem 0; text-align: center;">
    <h3 style="margin-top: 0; color: #27ae60; font-size: 1.2rem; font-weight: 900;">
        🚚 <?php _e('¡Entrega Completada!', 'woocommerce'); ?>
    </h3>
    <p style="margin: 0.5rem 0; font-weight: 600;">
        <?php 
        $delivery_date = $order->get_date_completed();
        if ($delivery_date) {
            printf(
                __('Entregado el %s', 'woocommerce'), 
                hobbytoys_format_date($delivery_date, 'd/m/Y')
            );
        } else {
            _e('Tu pedido ha sido completado exitosamente.', 'woocommerce');
        }
        ?>
    </p>
</div>

<!-- Programa de fidelidad / Puntos -->
<div class="info-box" style="background-color: #fff3cd; padding: 1.5rem; border-radius: 1rem; border-left: 4px solid #ffb900; margin: 2rem 0; text-align: center;">
    <h3 style="margin-top: 0; color: #e67e22; font-size: 1.1rem; font-weight: 900;">
        ⭐ <?php _e('¡Has Ganado Puntos!', 'woocommerce'); ?>
    </h3>
    <p style="margin: 0; font-weight: 600;">
        <?php 
        $points_earned = round($order->get_total() * 0.1); // 10% del total como puntos
        printf(
            __('Has ganado %d puntos con esta compra. ¡Úsalos en tu próxima compra!', 'woocommerce'),
            $points_earned
        );
        ?>
    </p>
    <div style="margin-top: 1rem;">
        <?php
        echo hobbytoys_get_action_button(
            __('Ver mis Puntos', 'woocommerce'),
            wc_get_page_permalink('myaccount'),
            'outline'
        );
        ?>
    </div>
</div>

<!-- Invitación a compartir en redes sociales -->
<div style="text-align: center; padding: 2rem; background: linear-gradient(135deg, #f4efe8 0%, rgba(238, 40, 91, 0.05) 100%); border-radius: 1rem; margin: 2rem 0;">
    <h3 style="color: #EE285B; font-weight: 900; margin: 0 0 1rem 0;">
        📱 <?php _e('¡Comparte tu Alegría!', 'woocommerce'); ?>
    </h3>
    <p style="margin: 0 0 1.5rem 0; font-weight: 600; color: #534fb5;">
        <?php _e('¿Te gustó tu compra? ¡Compártelo con tus amigos!', 'woocommerce'); ?>
    </p>
    
    <!-- Botones de redes sociales -->
    <div style="display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_home_url()); ?>" 
           target="_blank" 
           style="display: inline-flex; align-items: center; padding: 0.5rem 1rem; background-color: #3b5998; color: white; text-decoration: none; border-radius: 0.5rem; font-weight: 600;">
            📘 Facebook
        </a>
        <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode('¡Acabo de comprar en ' . get_bloginfo('name') . '! 🛍️'); ?>&url=<?php echo urlencode(get_home_url()); ?>" 
           target="_blank"
           style="display: inline-flex; align-items: center; padding: 0.5rem 1rem; background-color: #1da1f2; color: white; text-decoration: none; border-radius: 0.5rem; font-weight: 600;">
            🐦 Twitter
        </a>
        <a href="https://api.whatsapp.com/send?text=<?php echo urlencode('¡Mira lo que compré en ' . get_bloginfo('name') . '! ' . get_home_url()); ?>" 
           target="_blank"
           style="display: inline-flex; align-items: center; padding: 0.5rem 1rem; background-color: #25d366; color: white; text-decoration: none; border-radius: 0.5rem; font-weight: 600;">
            💬 WhatsApp
        </a>
    </div>
</div>

<!-- Productos recomendados -->
<?php 
$related_products = hobbytoys_get_related_products($order, 4);
if (!empty($related_products)) : 
?>
<div style="margin: 2rem 0;">
    <h3 style="color: #534fb5; font-weight: 900; text-align: center; margin-bottom: 1.5rem;">
        💡 <?php _e('Basado en tu compra, te recomendamos', 'woocommerce'); ?>
    </h3>
    <div style="text-align: center;">
        <?php
        echo hobbytoys_get_action_button(
            __('Ver Recomendaciones', 'woocommerce'),
            wc_get_page_permalink('shop'),
            'secondary'
        );
        ?>
    </div>
</div>
<?php endif; ?>

<!-- Información del cliente (simplificada para pedido completado) -->
<div style="margin: 2rem 0;">
    <h3 style="color: #6c757d; font-weight: 700; margin-bottom: 1rem; text-align: center;">
        📋 <?php _e('Detalles de Entrega', 'woocommerce'); ?>
    </h3>
    
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin: 1rem 0;">
        <div style="text-align: center; padding: 1rem; background-color: #f8f9fa; border-radius: 0.5rem;">
            <strong style="color: #534fb5;"><?php _e('Enviado a:', 'woocommerce'); ?></strong><br>
            <span style="font-size: 0.9rem; color: #6c757d;">
                <?php echo esc_html($order->get_billing_first_name() . ' ' . $order->get_billing_last_name()); ?>
            </span>
        </div>
        <div style="text-align: center; padding: 1rem; background-color: #f8f9fa; border-radius: 0.5rem;">
            <strong style="color: #534fb5;"><?php _e('Método de pago:', 'woocommerce'); ?></strong><br>
            <span style="font-size: 0.9rem; color: #6c757d;">
                <?php echo esc_html($order->get_payment_method_title()); ?>
            </span>
        </div>
    </div>
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

<!-- Mensaje final de agradecimiento -->
<div style="text-align: center; padding: 2rem; background: linear-gradient(135deg, #EE285B 0%, #534fb5 100%); border-radius: 1rem; margin: 2rem 0; color: white;">
    <h3 style="color: white; font-weight: 900; margin: 0 0 1rem 0; font-size: 1.5rem;">
        🎯 <?php _e('¡Misión Cumplida!', 'woocommerce'); ?>
    </h3>
    <p style="margin: 0; font-weight: 600; color: rgba(255,255,255,0.9); font-size: 1.1rem;">
        <?php _e('Gracias por elegirnos. ¡Esperamos verte pronto!', 'woocommerce'); ?>
    </p>
    <div style="margin-top: 1.5rem;">
        <?php
        echo hobbytoys_get_action_button(
            __('🏠 Volver al Inicio', 'woocommerce'),
            get_home_url(),
            'outline'
        );
        ?>
    </div>
</div>

<?php
/*
 * @hooked hobbytoys_email_footer() - Salida del footer del email
 */
do_action('woocommerce_email_footer', $email);
?>