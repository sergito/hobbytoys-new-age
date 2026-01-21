<?php
/**
 * Multi-Step Checkout (4 Steps)
 * Professional checkout with minimal fields
 *
 * Steps:
 * 1. Carrito (Cart Review)
 * 2. Envío (Shipping Info)
 * 3. Pago (Payment Method)
 * 4. Confirmación (Order Confirmation)
 *
 * @package HobbyToys_Pro
 */

if (!defined('ABSPATH')) exit;

/**
 * =============================================================================
 * CHECKOUT INITIALIZATION
 * =============================================================================
 */

/**
 * Initialize multi-step checkout session
 */
add_action('wp', 'ht_init_multistep_checkout');
function ht_init_multistep_checkout() {
    if (!is_checkout() || is_wc_endpoint_url('order-received')) {
        return;
    }

    if (!WC()->session) {
        return;
    }

    // Initialize step if not set
    if (!WC()->session->get('checkout_step')) {
        WC()->session->set('checkout_step', 1);
    }
}

/**
 * Get current checkout step
 */
function ht_get_checkout_step() {
    if (!WC()->session) {
        return 1;
    }
    return absint(WC()->session->get('checkout_step', 1));
}

/**
 * Set checkout step
 */
function ht_set_checkout_step($step) {
    if (!WC()->session) {
        return;
    }
    WC()->session->set('checkout_step', absint($step));
}

/**
 * =============================================================================
 * AJAX HANDLERS FOR STEP NAVIGATION
 * =============================================================================
 */

/**
 * AJAX: Go to next step
 */
add_action('wp_ajax_ht_checkout_next_step', 'ht_ajax_checkout_next_step');
add_action('wp_ajax_nopriv_ht_checkout_next_step', 'ht_ajax_checkout_next_step');
function ht_ajax_checkout_next_step() {
    check_ajax_referer('hobbytoys_nonce', 'nonce');

    $current_step = ht_get_checkout_step();
    $next_step = min($current_step + 1, 4);

    // Save form data to session
    if (isset($_POST['form_data'])) {
        parse_str($_POST['form_data'], $form_data);
        ht_save_checkout_data($current_step, $form_data);
    }

    ht_set_checkout_step($next_step);

    wp_send_json_success([
        'step'    => $next_step,
        'message' => 'Paso actualizado correctamente'
    ]);
}

/**
 * AJAX: Go to previous step
 */
add_action('wp_ajax_ht_checkout_prev_step', 'ht_ajax_checkout_prev_step');
add_action('wp_ajax_nopriv_ht_checkout_prev_step', 'ht_ajax_checkout_prev_step');
function ht_ajax_checkout_prev_step() {
    check_ajax_referer('hobbytoys_nonce', 'nonce');

    $current_step = ht_get_checkout_step();
    $prev_step = max($current_step - 1, 1);

    ht_set_checkout_step($prev_step);

    wp_send_json_success([
        'step'    => $prev_step,
        'message' => 'Paso anterior cargado'
    ]);
}

/**
 * AJAX: Go to specific step
 */
add_action('wp_ajax_ht_checkout_goto_step', 'ht_ajax_checkout_goto_step');
add_action('wp_ajax_nopriv_ht_checkout_goto_step', 'ht_ajax_checkout_goto_step');
function ht_ajax_checkout_goto_step() {
    check_ajax_referer('hobbytoys_nonce', 'nonce');

    $step = isset($_POST['step']) ? absint($_POST['step']) : 1;
    $step = min(max($step, 1), 4);

    ht_set_checkout_step($step);

    wp_send_json_success([
        'step'    => $step,
        'message' => 'Navegando al paso ' . $step
    ]);
}

/**
 * =============================================================================
 * DATA PERSISTENCE
 * =============================================================================
 */

/**
 * Save checkout data to session
 */
function ht_save_checkout_data($step, $data) {
    if (!WC()->session) {
        return;
    }

    $checkout_data = WC()->session->get('ht_checkout_data', []);

    switch ($step) {
        case 1: // Cart data (if needed)
            $checkout_data['cart'] = $data;
            break;

        case 2: // Shipping data
            $checkout_data['shipping'] = $data;
            break;

        case 3: // Payment data
            $checkout_data['payment'] = $data;
            break;
    }

    WC()->session->set('ht_checkout_data', $checkout_data);
}

/**
 * Get saved checkout data
 */
function ht_get_checkout_data($step = null) {
    if (!WC()->session) {
        return [];
    }

    $checkout_data = WC()->session->get('ht_checkout_data', []);

    if ($step !== null) {
        $keys = ['cart', 'shipping', 'payment'];
        $key = isset($keys[$step - 1]) ? $keys[$step - 1] : '';
        return isset($checkout_data[$key]) ? $checkout_data[$key] : [];
    }

    return $checkout_data;
}

/**
 * Clear checkout data on order completion
 */
add_action('woocommerce_thankyou', 'ht_clear_checkout_session');
function ht_clear_checkout_session($order_id) {
    if (!WC()->session) {
        return;
    }

    WC()->session->set('checkout_step', 1);
    WC()->session->set('ht_checkout_data', []);
}

/**
 * =============================================================================
 * MINIMAL CHECKOUT FIELDS
 * =============================================================================
 */

/**
 * Reduce checkout fields to minimum
 */
add_filter('woocommerce_checkout_fields', 'ht_minimal_checkout_fields');
function ht_minimal_checkout_fields($fields) {

    // BILLING FIELDS - Keep only essential
    $billing_keep = [
        'billing_first_name',
        'billing_last_name',
        'billing_email',
        'billing_phone',
    ];

    foreach ($fields['billing'] as $key => $field) {
        if (!in_array($key, $billing_keep)) {
            unset($fields['billing'][$key]);
        }
    }

    // SHIPPING FIELDS - Minimum for delivery
    $shipping_keep = [
        'shipping_address_1',
        'shipping_city',
        'shipping_state',
        'shipping_postcode',
    ];

    foreach ($fields['shipping'] as $key => $field) {
        if (!in_array($key, $shipping_keep)) {
            unset($fields['shipping'][$key]);
        }
    }

    // Remove order notes
    unset($fields['order']['order_comments']);

    // Update labels
    if (isset($fields['billing']['billing_first_name'])) {
        $fields['billing']['billing_first_name']['label'] = 'Nombre';
        $fields['billing']['billing_first_name']['placeholder'] = 'Tu nombre';
    }

    if (isset($fields['billing']['billing_last_name'])) {
        $fields['billing']['billing_last_name']['label'] = 'Apellido';
        $fields['billing']['billing_last_name']['placeholder'] = 'Tu apellido';
    }

    if (isset($fields['billing']['billing_email'])) {
        $fields['billing']['billing_email']['label'] = 'Email';
        $fields['billing']['billing_email']['placeholder'] = 'tu@email.com';
    }

    if (isset($fields['billing']['billing_phone'])) {
        $fields['billing']['billing_phone']['label'] = 'Teléfono';
        $fields['billing']['billing_phone']['placeholder'] = 'Ej: 11 1234 5678';
    }

    if (isset($fields['shipping']['shipping_address_1'])) {
        $fields['shipping']['shipping_address_1']['label'] = 'Dirección';
        $fields['shipping']['shipping_address_1']['placeholder'] = 'Calle y número';
    }

    if (isset($fields['shipping']['shipping_city'])) {
        $fields['shipping']['shipping_city']['label'] = 'Ciudad';
        $fields['shipping']['shipping_city']['placeholder'] = 'Tu ciudad';
    }

    if (isset($fields['shipping']['shipping_state'])) {
        $fields['shipping']['shipping_state']['label'] = 'Provincia';
    }

    if (isset($fields['shipping']['shipping_postcode'])) {
        $fields['shipping']['shipping_postcode']['label'] = 'Código Postal';
        $fields['shipping']['shipping_postcode']['placeholder'] = '1234';
    }

    return $fields;
}

/**
 * =============================================================================
 * STEP INDICATOR TEMPLATE
 * =============================================================================
 */

/**
 * Display checkout step indicator
 */
function ht_checkout_step_indicator() {
    $current_step = ht_get_checkout_step();

    $steps = [
        1 => [
            'icon'  => 'bi-cart-check',
            'title' => 'Carrito',
            'desc'  => 'Revisá tu pedido'
        ],
        2 => [
            'icon'  => 'bi-truck',
            'title' => 'Envío',
            'desc'  => 'Datos de entrega'
        ],
        3 => [
            'icon'  => 'bi-credit-card',
            'title' => 'Pago',
            'desc'  => 'Método de pago'
        ],
        4 => [
            'icon'  => 'bi-check-circle',
            'title' => 'Confirmación',
            'desc'  => 'Revisión final'
        ],
    ];

    ?>
    <div class="ht-checkout-steps">
        <div class="steps-container">
            <?php foreach ($steps as $step_num => $step_data) : ?>
                <div class="step-item <?php echo $step_num === $current_step ? 'active' : ''; ?> <?php echo $step_num < $current_step ? 'completed' : ''; ?>" data-step="<?php echo $step_num; ?>">
                    <div class="step-number">
                        <?php if ($step_num < $current_step) : ?>
                            <i class="bi bi-check-lg"></i>
                        <?php else : ?>
                            <i class="bi <?php echo esc_attr($step_data['icon']); ?>"></i>
                        <?php endif; ?>
                    </div>
                    <div class="step-content">
                        <div class="step-title"><?php echo esc_html($step_data['title']); ?></div>
                        <div class="step-desc"><?php echo esc_html($step_data['desc']); ?></div>
                    </div>
                </div>
                <?php if ($step_num < 4) : ?>
                    <div class="step-connector <?php echo $step_num < $current_step ? 'completed' : ''; ?>"></div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
}

/**
 * =============================================================================
 * CUSTOM CHECKOUT STYLES
 * =============================================================================
 */

/**
 * Add inline styles for multi-step checkout
 */
add_action('wp_head', 'ht_checkout_inline_styles');
function ht_checkout_inline_styles() {
    if (!is_checkout()) return;

    ?>
    <style>
        /* Step Indicator */
        .ht-checkout-steps {
            background: #fff;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            margin-bottom: 2rem;
        }

        .steps-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .step-item {
            display: flex;
            align-items: center;
            flex-direction: column;
            text-align: center;
            position: relative;
            flex: 1;
        }

        .step-number {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: #e9ecef;
            color: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            transition: all 0.3s ease;
        }

        .step-item.active .step-number {
            background: linear-gradient(135deg, #EE285B, #C41E3A);
            color: #fff;
            box-shadow: 0 4px 15px rgba(238, 40, 91, 0.3);
        }

        .step-item.completed .step-number {
            background: #198754;
            color: #fff;
        }

        .step-title {
            font-weight: 700;
            font-size: 1rem;
            color: #2c3e50;
            margin-bottom: 0.25rem;
        }

        .step-desc {
            font-size: 0.875rem;
            color: #6c757d;
        }

        .step-connector {
            height: 3px;
            background: #e9ecef;
            flex: 1;
            margin: 0 1rem;
            max-width: 100px;
            position: relative;
            top: -30px;
        }

        .step-connector.completed {
            background: #198754;
        }

        /* Step Content */
        .checkout-step-content {
            background: #fff;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            margin-bottom: 1.5rem;
        }

        .step-content-hidden {
            display: none;
        }

        /* Navigation Buttons */
        .checkout-step-navigation {
            display: flex;
            gap: 1rem;
            justify-content: space-between;
            margin-top: 2rem;
        }

        .btn-checkout-prev,
        .btn-checkout-next {
            padding: 1rem 2.5rem;
            font-weight: 700;
            border-radius: 0.75rem;
            transition: all 0.3s ease;
        }

        .btn-checkout-prev {
            background: #fff;
            border: 2px solid #e9ecef;
            color: #6c757d;
        }

        .btn-checkout-prev:hover {
            border-color: #dee2e6;
            background: #f8f9fa;
        }

        .btn-checkout-next {
            background: linear-gradient(135deg, #EE285B, #C41E3A);
            color: #fff;
            border: none;
        }

        .btn-checkout-next:hover {
            box-shadow: 0 5px 20px rgba(238, 40, 91, 0.4);
            transform: translateY(-2px);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .steps-container {
                flex-wrap: wrap;
            }

            .step-item {
                flex-basis: 100%;
                margin-bottom: 1rem;
            }

            .step-connector {
                display: none;
            }

            .step-number {
                width: 50px;
                height: 50px;
                font-size: 1.25rem;
            }

            .checkout-step-content {
                padding: 1.5rem;
            }
        }
    </style>
    <?php
}

/**
 * =============================================================================
 * CHECKOUT CUSTOMIZATIONS
 * =============================================================================
 */

/**
 * Custom checkout button text
 */
add_filter('woocommerce_order_button_text', 'ht_custom_checkout_button_text');
function ht_custom_checkout_button_text() {
    return 'Finalizar Compra';
}

/**
 * Add custom class to checkout button
 */
add_filter('woocommerce_order_button_html', 'ht_custom_checkout_button_html');
function ht_custom_checkout_button_html($button) {
    $button = str_replace('button alt', 'button alt btn-checkout-next', $button);
    return $button;
}
