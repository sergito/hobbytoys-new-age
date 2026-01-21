<?php
/**
 * Email Functions - Hobby Toys Theme
 * Funciones auxiliares para mejorar los emails de WooCommerce
 * 
 * @package HobbyToys/Functions/Emails
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Función para obtener el estado del pedido con estilo
 */
function hobbytoys_get_order_status_badge($status) {
    $status_classes = [
        'processing' => 'status-processing',
        'completed' => 'status-completed',
        'on-hold' => 'status-on-hold',
        'cancelled' => 'status-cancelled',
        'refunded' => 'status-cancelled',
        'failed' => 'status-cancelled',
        'pending' => 'status-on-hold'
    ];
    
    $status_labels = [
        'processing' => '⚡ Procesando',
        'completed' => '✅ Completado',
        'on-hold' => '⏳ En Espera',
        'cancelled' => '❌ Cancelado',
        'refunded' => '💰 Reembolsado',
        'failed' => '⚠️ Fallido',
        'pending' => '📋 Pendiente'
    ];
    
    $class = isset($status_classes[$status]) ? $status_classes[$status] : 'status-on-hold';
    $label = isset($status_labels[$status]) ? $status_labels[$status] : ucfirst($status);
    
    return sprintf(
        '<span class="order-status %s" style="display: inline-block; padding: 0.5rem 1rem; border-radius: 1rem; font-weight: 700; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">%s</span>',
        esc_attr($class),
        esc_html($label)
    );
}

/**
 * Función para formatear la tabla de productos del pedido
 */
function hobbytoys_format_order_table($order, $sent_to_admin = false) {
    if (!$order) return;
    
    $items = $order->get_items();
    if (empty($items)) return;
    
    ob_start();
    ?>
    <table class="order-table" style="width: 100%; margin: 1.5rem 0; border-collapse: collapse; border-radius: 1rem; overflow: hidden; box-shadow: 0 8px 32px rgba(238, 40, 91, 0.1);">
        <thead>
            <tr>
                <th style="background-color: #EE285B; color: #ffffff; padding: 1rem; font-weight: 700; text-align: left;">
                    <?php _e('Producto', 'woocommerce'); ?>
                </th>
                <th style="background-color: #EE285B; color: #ffffff; padding: 1rem; font-weight: 700; text-align: center;">
                    <?php _e('Cantidad', 'woocommerce'); ?>
                </th>
                <th style="background-color: #EE285B; color: #ffffff; padding: 1rem; font-weight: 700; text-align: right;">
                    <?php _e('Precio', 'woocommerce'); ?>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $row_count = 0;
            foreach ($items as $item_id => $item) : 
                $product = $item->get_product();
                $row_count++;
                $row_style = ($row_count % 2 == 0) ? 'background-color: #f8f9fa;' : '';
            ?>
                <tr style="<?php echo $row_style; ?>">
                    <td style="padding: 1rem; border-bottom: 1px solid #eee;">
                        <strong><?php echo esc_html($item->get_name()); ?></strong>
                        <?php
                        // Mostrar variaciones
                        if ($product && $product->is_type('variation')) {
                            $variation_attributes = $product->get_variation_attributes();
                            if (!empty($variation_attributes)) {
                                echo '<br><small style="color: #6c757d;">';
                                foreach ($variation_attributes as $attribute => $value) {
                                    echo esc_html(wc_attribute_label($attribute)) . ': ' . esc_html($value) . ' ';
                                }
                                echo '</small>';
                            }
                        }
                        
                        // Mostrar SKU si está disponible
                        if ($product && $product->get_sku()) {
                            echo '<br><small style="color: #6c757d;">SKU: ' . esc_html($product->get_sku()) . '</small>';
                        }
                        ?>
                    </td>
                    <td style="padding: 1rem; border-bottom: 1px solid #eee; text-align: center;">
                        <span style="background-color: #EE285B; color: white; padding: 0.25rem 0.75rem; border-radius: 0.5rem; font-weight: 700;">
                            <?php echo esc_html($item->get_quantity()); ?>
                        </span>
                    </td>
                    <td style="padding: 1rem; border-bottom: 1px solid #eee; text-align: right; font-weight: 700;">
                        <?php echo $order->get_formatted_line_subtotal($item); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <?php
            // Mostrar totales
            $totals = $order->get_order_item_totals();
            if ($totals) :
                foreach ($totals as $total) :
            ?>
                <tr class="total-row" style="background-color: #f4efe8; font-weight: 700;">
                    <td colspan="2" style="padding: 1rem; border-top: 2px solid #EE285B; text-align: right;">
                        <?php echo esc_html($total['label']); ?>
                    </td>
                    <td style="padding: 1rem; border-top: 2px solid #EE285B; text-align: right; font-size: 1.1rem;">
                        <?php echo wp_kses_post($total['value']); ?>
                    </td>
                </tr>
            <?php 
                endforeach;
            endif; 
            ?>
        </tfoot>
    </table>
    <?php
    return ob_get_clean();
}

/**
 * Función para mostrar información del cliente
 */
function hobbytoys_format_customer_details($order) {
    if (!$order) return;
    
    $billing_address = $order->get_formatted_billing_address();
    $shipping_address = $order->get_formatted_shipping_address();
    
    ob_start();
    ?>
    <div class="customer-info" style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin: 2rem 0;">
        
        <!-- Información de facturación -->
        <div class="info-box" style="background-color: #f8f9fa; padding: 1.5rem; border-radius: 1rem; border-left: 4px solid #534fb5;">
            <h3 style="margin-top: 0; color: #534fb5; font-size: 1.1rem; font-weight: 900;">
                💳 <?php _e('Información de Facturación', 'woocommerce'); ?>
            </h3>
            
            <?php if ($billing_address) : ?>
                <p style="margin: 0; line-height: 1.6; font-weight: 600;">
                    <?php echo wp_kses_post($billing_address); ?>
                </p>
            <?php endif; ?>
            
            <?php if ($order->get_billing_email()) : ?>
                <p style="margin: 0.5rem 0 0 0; font-weight: 600;">
                    ✉️ <?php echo esc_html($order->get_billing_email()); ?>
                </p>
            <?php endif; ?>
            
            <?php if ($order->get_billing_phone()) : ?>
                <p style="margin: 0.5rem 0 0 0; font-weight: 600;">
                    📞 <?php echo esc_html($order->get_billing_phone()); ?>
                </p>
            <?php endif; ?>
        </div>
        
        <!-- Información de envío -->
        <div class="info-box" style="background-color: #f8f9fa; padding: 1.5rem; border-radius: 1rem; border-left: 4px solid #534fb5;">
            <h3 style="margin-top: 0; color: #534fb5; font-size: 1.1rem; font-weight: 900;">
                🚚 <?php _e('Información de Envío', 'woocommerce'); ?>
            </h3>
            
            <?php if ($shipping_address) : ?>
                <p style="margin: 0; line-height: 1.6; font-weight: 600;">
                    <?php echo wp_kses_post($shipping_address); ?>
                </p>
            <?php else : ?>
                <p style="margin: 0; font-style: italic; color: #6c757d;">
                    <?php _e('Misma dirección de facturación', 'woocommerce'); ?>
                </p>
            <?php endif; ?>
            
            <?php 
            // Mostrar método de envío
            $shipping_methods = $order->get_shipping_methods();
            if ($shipping_methods) :
                foreach ($shipping_methods as $shipping_method) :
            ?>
                <p style="margin: 0.5rem 0 0 0; font-weight: 600;">
                    📦 <?php echo esc_html($shipping_method->get_name()); ?>
                    <?php if ($shipping_method->get_total() > 0) : ?>
                        - <?php echo wc_price($shipping_method->get_total()); ?>
                    <?php endif; ?>
                </p>
            <?php 
                endforeach;
            endif; 
            ?>
        </div>
    </div>
    
    <!-- Método de pago -->
    <div class="info-box" style="background-color: #f4efe8; padding: 1.5rem; border-radius: 1rem; border-left: 4px solid #ffb900; margin: 1rem 0;">
        <h3 style="margin-top: 0; color: #e67e22; font-size: 1.1rem; font-weight: 900;">
            💰 <?php _e('Método de Pago', 'woocommerce'); ?>
        </h3>
        <p style="margin: 0; font-weight: 600;">
            <?php echo esc_html($order->get_payment_method_title()); ?>
        </p>
        
        <?php if ($order->get_transaction_id()) : ?>
            <p style="margin: 0.5rem 0 0 0; font-size: 0.9rem; color: #6c757d;">
                ID Transacción: <?php echo esc_html($order->get_transaction_id()); ?>
            </p>
        <?php endif; ?>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Función para crear botones de acción
 */
function hobbytoys_get_action_button($text, $url, $style = 'primary') {
    $button_styles = [
        'primary' => 'background-color: #EE285B; color: #ffffff;',
        'secondary' => 'background-color: #ffb900; color: #2C3E50;',
        'outline' => 'background-color: transparent; color: #EE285B; border: 2px solid #EE285B;'
    ];
    
    $button_style = isset($button_styles[$style]) ? $button_styles[$style] : $button_styles['primary'];
    
    return sprintf(
        '<a href="%s" class="btn btn-%s" style="display: inline-block; padding: 0.75rem 2rem; %s text-decoration: none; border-radius: 1rem; font-weight: 700; text-align: center; margin: 0.5rem; transition: all 0.3s ease;">%s</a>',
        esc_url($url),
        esc_attr($style),
        $button_style,
        esc_html($text)
    );
}

/**
 * Función para formatear fechas
 */
function hobbytoys_format_date($date, $format = null) {
    if (!$date) return '';
    
    if (is_string($date)) {
        $date = new DateTime($date);
    }
    
    if (!$format) {
        $format = 'd/m/Y H:i';
    }
    
    return $date->format($format);
}

/**
 * Función para obtener productos relacionados o recomendados
 */
function hobbytoys_get_related_products($order, $limit = 4) {
    if (!$order) return [];
    
    $related_products = [];
    $items = $order->get_items();
    
    foreach ($items as $item) {
        $product = $item->get_product();
        if ($product) {
            $related = wc_get_related_products($product->get_id(), $limit);
            $related_products = array_merge($related_products, $related);
        }
    }
    
    return array_unique(array_slice($related_products, 0, $limit));
}

/**
 * Función para generar mensaje personalizado según el estado del pedido
 */
function hobbytoys_get_status_message($status, $order = null) {
    $messages = [
        'processing' => [
            'title' => '🎉 ¡Tu pedido está en proceso!',
            'message' => 'Estamos preparando tu pedido con mucho cariño. Te notificaremos cuando esté listo para enviar.'
        ],
        'completed' => [
            'title' => '✅ ¡Pedido completado!',
            'message' => '¡Esperamos que disfrutes tu compra! No olvides dejarnos una reseña.'
        ],
        'on-hold' => [
            'title' => '⏳ Pedido en espera',
            'message' => 'Tu pedido está en espera. Te contactaremos pronto para confirmar los detalles.'
        ],
        'cancelled' => [
            'title' => '❌ Pedido cancelado',
            'message' => 'Tu pedido ha sido cancelado. Si tienes preguntas, no dudes en contactarnos.'
        ],
        'refunded' => [
            'title' => '💰 Reembolso procesado',
            'message' => 'El reembolso de tu pedido ha sido procesado. Recibirás el dinero en los próximos días.'
        ]
    ];
    
    return isset($messages[$status]) ? $messages[$status] : [
        'title' => '📋 Actualización de pedido',
        'message' => 'Tu pedido ha sido actualizado. Revisa los detalles a continuación.'
    ];
}

/**
 * Hook para personalizar los emails de WooCommerce
 */
add_action('init', 'hobbytoys_customize_woocommerce_emails');

function hobbytoys_customize_woocommerce_emails() {
    // Remover acciones por defecto
    remove_action('woocommerce_email_header', 'WC_Emails::email_header');
    remove_action('woocommerce_email_footer', 'WC_Emails::email_footer');
    
    // Agregar nuestras acciones personalizadas
    add_action('woocommerce_email_header', 'hobbytoys_email_header', 10, 2);
    add_action('woocommerce_email_footer', 'hobbytoys_email_footer', 10, 1);
}

/**
 * Header personalizado
 */
function hobbytoys_email_header($email_heading, $email) {
    include get_template_directory() . '/woocommerce/emails/email-header.php';
}

/**
 * Footer personalizado
 */
function hobbytoys_email_footer($email) {
    include get_template_directory() . '/woocommerce/emails/email-footer.php';
}

/**
 * Añadir estilos CSS inline a los emails
 */
add_filter('woocommerce_email_styles', 'hobbytoys_email_styles');

function hobbytoys_email_styles($css) {
    $custom_css = file_get_contents(get_template_directory() . '/woocommerce/emails/css/email-styles.css');
    return $css . $custom_css;
}