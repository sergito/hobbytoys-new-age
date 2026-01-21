<?php
/**
 * Sistema Completo de Clientes Mayoristas - Hobby Toys
 * Maneja registro, precios especiales, panel de usuario y restricciones
 * 
 * @package HobbyToys
 * @version 2.0.0
 */

// Exit if accessed directly
defined('ABSPATH') || exit;


// ============================================
// SECCIÓN 1: TABLAS Y ROLES
// ============================================

/**
 * Crear rol mayorista personalizado
 */
function ht_add_mayorista_role() {
    $customer_role = get_role('customer');
    
    if ($customer_role && !get_role('cliente_mayorista')) {
        add_role(
            'cliente_mayorista',
            'Cliente Mayorista',
            $customer_role->capabilities
        );
        
        $mayorista_role = get_role('cliente_mayorista');
        if ($mayorista_role) {
            $mayorista_role->add_cap('view_wholesale_prices');
            $mayorista_role->add_cap('place_wholesale_orders');
            $mayorista_role->add_cap('read');
        }
    }
}
add_action('init', 'ht_add_mayorista_role');


/**
 * Crear tabla para solicitudes mayoristas
 */
function ht_create_mayorista_requests_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'ht_mayorista_requests';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        user_id bigint(20) UNSIGNED,
        razon_social varchar(200) NOT NULL,
        cuit varchar(20) NOT NULL,
        tipo_negocio varchar(100) NOT NULL,
        email varchar(100) NOT NULL,
        telefono varchar(50) NOT NULL,
        provincia varchar(100) NOT NULL,
        localidad varchar(100) NOT NULL,
        comentarios text,
        estado varchar(20) DEFAULT 'pendiente',
        fecha datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        fecha_aprobacion datetime,
        PRIMARY KEY (id),
        KEY user_id (user_id),
        KEY email (email),
        KEY estado (estado)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
add_action('after_setup_theme', 'ht_create_mayorista_requests_table');


// ============================================
// SECCIÓN 2: REGISTRO DE MAYORISTAS (AJAX)
// ============================================

/**
 * Ya está manejado en inc-formularios.php
 * No duplicar aquí
 */


// ============================================
// SECCIÓN 3: PRECIOS MAYORISTAS EN PRODUCTOS
// ============================================

/**
 * Agregar campo de precio mayorista en panel de producto
 */
add_action('woocommerce_product_options_pricing', 'ht_add_wholesale_price_field');
function ht_add_wholesale_price_field() {
    woocommerce_wp_text_input([
        'id' => '_wholesale_price',
        'label' => __('Precio Mayorista ($)', 'hobbytoys'),
        'placeholder' => 'Ej: 5000',
        'description' => __('Precio especial para clientes mayoristas', 'hobbytoys'),
        'type' => 'number',
        'custom_attributes' => [
            'step' => 'any',
            'min' => '0'
        ]
    ]);
}

/**
 * Guardar precio mayorista
 */
add_action('woocommerce_process_product_meta', 'ht_save_wholesale_price_field');
function ht_save_wholesale_price_field($post_id) {
    $wholesale_price = isset($_POST['_wholesale_price']) ? sanitize_text_field($_POST['_wholesale_price']) : '';
    update_post_meta($post_id, '_wholesale_price', $wholesale_price);
}

/**
 * Modificar precio mostrado según rol del usuario
 */
add_filter('woocommerce_product_get_price', 'ht_get_wholesale_price', 10, 2);
add_filter('woocommerce_product_get_regular_price', 'ht_get_wholesale_price', 10, 2);
function ht_get_wholesale_price($price, $product) {
    if (!is_user_logged_in() || !current_user_can('cliente_mayorista')) {
        return $price;
    }
    
    $wholesale_price = get_post_meta($product->get_id(), '_wholesale_price', true);
    
    if (!empty($wholesale_price) && $wholesale_price > 0) {
        return $wholesale_price;
    }
    
    return $price;
}

/**
 * Ocultar precio de oferta para mayoristas
 */
add_filter('woocommerce_product_get_sale_price', 'ht_hide_sale_price_for_wholesale', 10, 2);
function ht_hide_sale_price_for_wholesale($sale_price, $product) {
    if (is_user_logged_in() && current_user_can('cliente_mayorista')) {
        return '';
    }
    return $sale_price;
}

/**
 * Ocultar badge de oferta para mayoristas
 */
add_filter('woocommerce_sale_flash', 'ht_hide_sale_flash_for_wholesale', 10, 3);
function ht_hide_sale_flash_for_wholesale($html, $post, $product) {
    if (is_user_logged_in() && current_user_can('cliente_mayorista')) {
        return '';
    }
    return $html;
}


// ============================================
// SECCIÓN 4: BADGES MAYORISTAS
// ============================================

/**
 * Badge en loop de productos
 */
add_action('woocommerce_before_shop_loop_item_title', 'ht_add_wholesale_badge_loop', 5);
function ht_add_wholesale_badge_loop() {
    if (!is_user_logged_in() || !current_user_can('cliente_mayorista')) {
        return;
    }
    
    global $product;
    $wholesale_price = get_post_meta($product->get_id(), '_wholesale_price', true);
    
    if (!empty($wholesale_price) && $wholesale_price > 0) {
        echo '<span class="badge-mayorista">Precio Mayorista</span>';
    }
}

/**
 * Badge en página de producto
 */
add_action('woocommerce_single_product_summary', 'ht_add_wholesale_badge_single', 6);
function ht_add_wholesale_badge_single() {
    if (!is_user_logged_in() || !current_user_can('cliente_mayorista')) {
        return;
    }
    
    global $product;
    $wholesale_price = get_post_meta($product->get_id(), '_wholesale_price', true);
    
    if (!empty($wholesale_price) && $wholesale_price > 0) {
        echo '<div class="wholesale-badge-container mb-3">
            <span class="badge-mayorista-single">
                <i class="bi bi-star-fill me-2"></i>Precio Mayorista Exclusivo
            </span>
        </div>';
    }
}

/**
 * Estilos para badges
 */
add_action('wp_head', 'ht_wholesale_badge_styles');
function ht_wholesale_badge_styles() {
    if (!is_user_logged_in() || !current_user_can('cliente_mayorista')) {
        return;
    }
    ?>
    <style>
        .badge-mayorista {
            position: absolute;
            top: 10px;
            left: 10px;
            background: linear-gradient(135deg, #FFB900, #FF8C00);
            color: #2C3E50;
            padding: 0.4rem 0.8rem;
            border-radius: 0.5rem;
            font-weight: 700;
            font-size: 0.75rem;
            text-transform: uppercase;
            z-index: 10;
            box-shadow: 0 4px 12px rgba(255, 185, 0, 0.4);
            letter-spacing: 0.5px;
        }
        
        .badge-mayorista-single {
            display: inline-flex;
            align-items: center;
            background: linear-gradient(135deg, #FFB900, #FF8C00);
            color: #2C3E50;
            padding: 0.8rem 1.5rem;
            border-radius: 1rem;
            font-weight: 700;
            font-size: 0.95rem;
            text-transform: uppercase;
            box-shadow: 0 6px 20px rgba(255, 185, 0, 0.3);
            letter-spacing: 0.5px;
        }
        
        .products .product {
            position: relative;
        }
        
        .badge-mayorista,
        .badge-mayorista-single {
            animation: subtle-pulse 2s infinite;
        }
        
        @keyframes subtle-pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
    </style>
    <?php
}


// ============================================
// SECCIÓN 5: RESTRICCIONES PARA NO MAYORISTAS
// ============================================

/**
 * Redireccionar home a tienda para usuarios no mayoristas
 * DESACTIVADO: Este código estaba redirigiendo a todos los usuarios a la tienda
 */
// add_action('template_redirect', 'ht_redirect_home_for_non_wholesale');
function ht_redirect_home_for_non_wholesale() {
    // Solo aplicar si NO es mayorista Y está en home
    if ((is_home() || is_front_page()) && is_user_logged_in() && !current_user_can('cliente_mayorista')) {
        wp_redirect(wc_get_page_permalink('shop'));
        exit;
    }
}

/**
 * Ocultar todas las ofertas para usuarios no mayoristas
 */
add_filter('woocommerce_product_is_on_sale', 'ht_hide_sales_for_non_wholesale', 10, 2);
function ht_hide_sales_for_non_wholesale($on_sale, $product) {
    // Si es mayorista, mostrar todo normal
    if (is_user_logged_in() && current_user_can('cliente_mayorista')) {
        return $on_sale;
    }
    
    // Para el público general, nunca mostrar como "en oferta"
    return false;
}

/**
 * Forzar método de pago único para no mayoristas
 */
add_filter('woocommerce_available_payment_gateways', 'ht_limit_payment_for_non_wholesale');
function ht_limit_payment_for_non_wholesale($gateways) {
    // Si es mayorista, permitir todos los métodos
    if (is_user_logged_in() && current_user_can('cliente_mayorista')) {
        return $gateways;
    }
    
    // Para público general: SOLO contra reembolso (COD)
    $allowed = array('cod'); // cod = Contra Reembolso / Pago contra entrega
    
    foreach ($gateways as $gateway_id => $gateway) {
        if (!in_array($gateway_id, $allowed)) {
            unset($gateways[$gateway_id]);
        }
    }
    
    return $gateways;
}

/**
 * Modificar título del método COD para el público
 */
add_filter('woocommerce_gateway_title', 'ht_customize_cod_title', 10, 2);
function ht_customize_cod_title($title, $gateway_id) {
    if ($gateway_id === 'cod' && !current_user_can('cliente_mayorista')) {
        return 'Pago contra entrega';
    }
    return $title;
}


// ============================================
// SECCIÓN 6: CHECKOUT MAYORISTA
// ============================================

/**
 * Cambiar texto del botón agregar al carrito
 */
add_filter('woocommerce_product_single_add_to_cart_text', 'ht_change_add_to_cart_text_wholesale');
add_filter('woocommerce_product_add_to_cart_text', 'ht_change_add_to_cart_text_wholesale');
function ht_change_add_to_cart_text_wholesale($text) {
    if (is_user_logged_in() && current_user_can('cliente_mayorista')) {
        return 'Solicitar Pedido';
    }
    return $text;
}

/**
 * Nota en checkout para mayoristas
 */
add_action('woocommerce_before_checkout_form', 'ht_wholesale_checkout_notice');
function ht_wholesale_checkout_notice($checkout) {
    if (!is_user_logged_in() || !current_user_can('cliente_mayorista')) {
        return;
    }
    ?>
    <div class="mayorista-checkout-notice alert alert-info mb-4" style="background: linear-gradient(135deg, rgba(83, 79, 181, 0.1), rgba(238, 40, 91, 0.1)); border: 2px solid var(--secondary-color); border-radius: 1rem; padding: 1.5rem;">
        <h4 style="color: var(--secondary-color); font-weight: 700; margin-bottom: 1rem;">
            <i class="bi bi-info-circle-fill me-2"></i>Cliente Mayorista
        </h4>
        <p class="mb-2"><strong>Este es un pedido mayorista.</strong> Los precios mostrados son precios especiales para tu cuenta.</p>
        <ul class="mb-0" style="padding-left: 1.2rem;">
            <li>Tu pedido será revisado por nuestro equipo</li>
            <li>Recibirás confirmación y factura por email</li>
            <li>Los términos de pago se coordinarán según tu cuenta</li>
            <li>Ante cualquier duda, contactanos al WhatsApp</li>
        </ul>
    </div>
    <?php
}

/**
 * Campo de referencia interna para mayoristas
 */
add_filter('woocommerce_checkout_fields', 'ht_add_mayorista_reference_field');
function ht_add_mayorista_reference_field($fields) {
    if (!is_user_logged_in() || !current_user_can('cliente_mayorista')) {
        return $fields;
    }
    
    $fields['billing']['billing_mayorista_reference'] = array(
        'type' => 'text',
        'label' => __('Referencia Interna (Opcional)', 'hobbytoys'),
        'placeholder' => __('Ej: Orden de compra #123', 'hobbytoys'),
        'required' => false,
        'class' => array('form-row-wide'),
        'priority' => 35
    );
    
    return $fields;
}

add_action('woocommerce_checkout_update_order_meta', 'ht_save_mayorista_reference_field');
function ht_save_mayorista_reference_field($order_id) {
    if (!empty($_POST['billing_mayorista_reference'])) {
        update_post_meta($order_id, '_mayorista_reference', sanitize_text_field($_POST['billing_mayorista_reference']));
    }
}

/**
 * Meta datos del pedido mayorista
 */
add_action('woocommerce_checkout_create_order', 'ht_add_wholesale_order_meta', 10, 2);
function ht_add_wholesale_order_meta($order, $data) {
    if (is_user_logged_in() && current_user_can('cliente_mayorista')) {
        $order->update_meta_data('_is_wholesale_order', 'yes');
        $order->update_meta_data('_wholesale_user_id', get_current_user_id());
        
        $user = wp_get_current_user();
        $order->update_meta_data('_wholesale_company', get_user_meta($user->ID, 'billing_company', true));
        $order->update_meta_data('_wholesale_cuit', get_user_meta($user->ID, 'ht_cuit', true));
    }
}

/**
 * Estado inicial para pedidos mayoristas
 */
add_action('woocommerce_thankyou', 'ht_set_wholesale_order_status', 10, 1);
function ht_set_wholesale_order_status($order_id) {
    if (!$order_id) return;
    
    $order = wc_get_order($order_id);
    
    if ($order->get_meta('_is_wholesale_order') === 'yes') {
        $order->update_status('processing', 'Pedido mayorista en revisión');
    }
}

/**
 * Nota automática en pedidos mayoristas
 */
add_action('woocommerce_new_order', 'ht_add_note_to_wholesale_order');
function ht_add_note_to_wholesale_order($order_id) {
    $order = wc_get_order($order_id);
    
    if ($order->get_meta('_is_wholesale_order') === 'yes') {
        $company = $order->get_meta('_wholesale_company');
        $cuit = $order->get_meta('_wholesale_cuit');
        $reference = $order->get_meta('_mayorista_reference');
        
        $note = "PEDIDO MAYORISTA\n\n";
        $note .= "Empresa: {$company}\n";
        $note .= "CUIT: {$cuit}\n";
        
        if (!empty($reference)) {
            $note .= "Referencia Interna: {$reference}\n";
        }
        
        $note .= "\nEste pedido requiere revisión y aprobación del equipo de ventas mayoristas.";
        
        $order->add_order_note($note, false);
    }
}

/**
 * Email personalizado para mayoristas
 */
add_filter('woocommerce_email_subject_customer_processing_order', 'ht_wholesale_order_email_subject', 10, 2);
function ht_wholesale_order_email_subject($subject, $order) {
    if ($order->get_meta('_is_wholesale_order') === 'yes') {
        return 'Pedido Mayorista Recibido - Orden #' . $order->get_order_number();
    }
    return $subject;
}


// ============================================
// SECCIÓN 7: PANEL MI CUENTA MAYORISTA
// ============================================

/**
 * Registrar endpoints en las query vars de WooCommerce
 * CRÍTICO: Sin esto los endpoints no funcionan aunque estén registrados
 */
add_filter('woocommerce_get_query_vars', 'ht_add_wholesale_query_vars');
function ht_add_wholesale_query_vars($query_vars) {
    $query_vars['mayorista-dashboard'] = 'mayorista-dashboard';
    $query_vars['mayorista-pedidos'] = 'mayorista-pedidos';
    return $query_vars;
}

/**
 * Agregar endpoints personalizados al sistema de rewrite
 */
add_action('init', 'ht_add_wholesale_endpoints');
function ht_add_wholesale_endpoints() {
    add_rewrite_endpoint('mayorista-dashboard', EP_ROOT | EP_PAGES);
    add_rewrite_endpoint('mayorista-pedidos', EP_ROOT | EP_PAGES);
}

/**
 * Flush rewrite rules automáticamente la primera vez
 * Esto soluciona el problema de 404 en los endpoints mayoristas
 */
add_action('after_switch_theme', 'ht_flush_rewrite_rules_on_theme_activation');
function ht_flush_rewrite_rules_on_theme_activation() {
    // Registrar los endpoints
    ht_add_wholesale_endpoints();

    // Hacer flush de las reglas de reescritura
    flush_rewrite_rules(false);

    // Log para debug (opcional)
    error_log('HobbyToys: Rewrite rules flushed after theme activation');
}

/**
 * Verificar y hacer flush si los endpoints no están funcionando
 * Se ejecuta solo en el admin y solo una vez cada 24 horas
 */
add_action('admin_init', 'ht_check_wholesale_endpoints_health');
function ht_check_wholesale_endpoints_health() {
    // Solo verificar una vez cada 24 horas para no impactar performance
    $last_check = get_option('ht_wholesale_endpoints_last_check', 0);
    $now = time();

    // Si ya se verificó en las últimas 24 horas, salir
    if (($now - $last_check) < DAY_IN_SECONDS) {
        return;
    }

    // Verificar que los endpoints estén registrados
    $wc_endpoints = WC()->query->get_query_vars();

    // Si no están los endpoints, registrarlos y hacer flush
    if (!isset($wc_endpoints['mayorista-dashboard']) || !isset($wc_endpoints['mayorista-pedidos'])) {
        ht_add_wholesale_endpoints();
        flush_rewrite_rules(false);
        error_log('HobbyToys: Wholesale endpoints were missing and have been re-registered');
    }

    // Actualizar la última verificación
    update_option('ht_wholesale_endpoints_last_check', $now);
}

/**
 * Agregar items al menú Mi Cuenta
 */
add_filter('woocommerce_account_menu_items', 'ht_add_wholesale_menu_items', 10, 1);
function ht_add_wholesale_menu_items($items) {
    if (!current_user_can('cliente_mayorista')) {
        return $items;
    }
    
    $new_items = array();
    $new_items['mayorista-dashboard'] = 'Dashboard Mayorista';
    
    foreach ($items as $key => $label) {
        if ($key === 'dashboard') {
            continue;
        }
        $new_items[$key] = $label;
    }
    
    $new_items['mayorista-pedidos'] = 'Mis Pedidos Mayoristas';
    
    return $new_items;
}

/**
 * Contenido del Dashboard Mayorista
 */
add_action('woocommerce_account_mayorista-dashboard_endpoint', 'ht_mayorista_dashboard_content');
function ht_mayorista_dashboard_content() {
    $user = wp_get_current_user();
    $user_id = $user->ID;
    
    $company = get_user_meta($user_id, 'billing_company', true);
    $cuit = get_user_meta($user_id, 'ht_cuit', true);
    $tipo_negocio = get_user_meta($user_id, 'ht_tipo_negocio', true);
    
    $orders = wc_get_orders(array(
        'customer_id' => $user_id,
        'meta_key' => '_is_wholesale_order',
        'meta_value' => 'yes',
        'limit' => -1
    ));
    
    $total_orders = count($orders);
    $total_spent = 0;
    
    foreach ($orders as $order) {
        $total_spent += $order->get_total();
    }
    
    ?>
    <div class="mayorista-dashboard">
        <div class="mayorista-header mb-4 p-4" style="background: linear-gradient(135deg, var(--secondary-color), var(--primary-color)); color: white; border-radius: 1rem;">
            <h2 class="mb-2">¡Bienvenido, <?php echo esc_html($company); ?>!</h2>
            <p class="mb-0">Panel exclusivo para clientes mayoristas</p>
        </div>
        
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="info-card p-3" style="background: #f8f9fa; border-radius: 1rem; border: 2px solid var(--accent-color);">
                    <h6 style="color: var(--secondary-color); font-weight: 700;">Razón Social</h6>
                    <p class="mb-0 fw-bold"><?php echo esc_html($company); ?></p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-card p-3" style="background: #f8f9fa; border-radius: 1rem; border: 2px solid var(--accent-color);">
                    <h6 style="color: var(--secondary-color); font-weight: 700;">CUIT</h6>
                    <p class="mb-0 fw-bold"><?php echo esc_html($cuit); ?></p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-card p-3" style="background: #f8f9fa; border-radius: 1rem; border: 2px solid var(--accent-color);">
                    <h6 style="color: var(--secondary-color); font-weight: 700;">Tipo de Negocio</h6>
                    <p class="mb-0 fw-bold"><?php echo esc_html(ucfirst($tipo_negocio)); ?></p>
                </div>
            </div>
        </div>
        
        <h3 class="mb-3">Estadísticas de Compras</h3>
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="stat-card p-4 text-center" style="background: linear-gradient(135deg, rgba(238, 40, 91, 0.1), rgba(83, 79, 181, 0.1)); border-radius: 1rem; border: 2px solid var(--primary-color);">
                    <h2 style="color: var(--primary-color); font-weight: 900; font-size: 3rem;"><?php echo $total_orders; ?></h2>
                    <p class="mb-0 fw-bold">Pedidos Realizados</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="stat-card p-4 text-center" style="background: linear-gradient(135deg, rgba(255, 185, 0, 0.1), rgba(255, 140, 0, 0.1)); border-radius: 1rem; border: 2px solid var(--accent-color);">
                    <h2 style="color: var(--accent-color); font-weight: 900; font-size: 3rem;">$<?php echo number_format($total_spent, 0, ',', '.'); ?></h2>
                    <p class="mb-0 fw-bold">Total Comprado</p>
                </div>
            </div>
        </div>
        
        <h3 class="mb-3">Accesos Rápidos</h3>
        <div class="row g-3">
            <div class="col-md-4">
                <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="quick-link-card d-block p-3 text-center text-decoration-none" style="background: white; border: 2px solid var(--primary-color); border-radius: 1rem; transition: all 0.3s;">
                    <i class="bi bi-shop" style="font-size: 2.5rem; color: var(--primary-color);"></i>
                    <h5 class="mt-2 mb-0" style="color: var(--text-dark);">Ver Catálogo</h5>
                </a>
            </div>
            <div class="col-md-4">
                <a href="<?php echo esc_url(wc_get_account_endpoint_url('mayorista-pedidos')); ?>" class="quick-link-card d-block p-3 text-center text-decoration-none" style="background: white; border: 2px solid var(--secondary-color); border-radius: 1rem; transition: all 0.3s;">
                    <i class="bi bi-box-seam" style="font-size: 2.5rem; color: var(--secondary-color);"></i>
                    <h5 class="mt-2 mb-0" style="color: var(--text-dark);">Mis Pedidos</h5>
                </a>
            </div>
            <div class="col-md-4">
                <a href="<?php echo esc_url(home_url('/contacto')); ?>" class="quick-link-card d-block p-3 text-center text-decoration-none" style="background: white; border: 2px solid var(--accent-color); border-radius: 1rem; transition: all 0.3s;">
                    <i class="bi bi-headset" style="font-size: 2.5rem; color: var(--accent-color);"></i>
                    <h5 class="mt-2 mb-0" style="color: var(--text-dark);">Soporte</h5>
                </a>
            </div>
        </div>
        
        <style>
            .quick-link-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            }
        </style>
    </div>
    <?php
}

/**
 * Contenido de Pedidos Mayoristas
 */
add_action('woocommerce_account_mayorista-pedidos_endpoint', 'ht_mayorista_orders_content');
function ht_mayorista_orders_content() {
    $user_id = get_current_user_id();
    
    $orders = wc_get_orders(array(
        'customer_id' => $user_id,
        'meta_key' => '_is_wholesale_order',
        'meta_value' => 'yes',
        'limit' => -1,
        'orderby' => 'date',
        'order' => 'DESC'
    ));
    
    ?>
    <h2>Mis Pedidos Mayoristas</h2>
    
    <?php if (!empty($orders)) : ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Pedido</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Total</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order) : ?>
                    <tr>
                        <td>#<?php echo $order->get_order_number(); ?></td>
                        <td><?php echo $order->get_date_created()->date('d/m/Y'); ?></td>
                        <td>
                            <span class="badge bg-<?php echo $order->get_status() === 'completed' ? 'success' : 'warning'; ?>">
                                <?php echo wc_get_order_status_name($order->get_status()); ?>
                            </span>
                        </td>
                        <td>$<?php echo number_format($order->get_total(), 2, ',', '.'); ?></td>
                        <td>
                            <a href="<?php echo esc_url($order->get_view_order_url()); ?>" class="btn btn-sm btn-primary">
                                Ver Detalle
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else : ?>
        <div class="alert alert-info">
            <p class="mb-0">Aún no has realizado pedidos mayoristas.</p>
            <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="btn btn-primary mt-3">
                Explorar Catálogo
            </a>
        </div>
    <?php endif; ?>
    <?php
}


// ============================================
// SECCIÓN 8: PANEL ADMIN
// ============================================

/**
 * Agregar menús en admin
 */
add_action('admin_menu', 'ht_add_admin_menus');
function ht_add_admin_menus() {
    add_menu_page(
        'Solicitudes Mayoristas',
        'Mayoristas',
        'manage_options',
        'ht-mayorista-requests',
        'ht_mayorista_requests_page',
        'dashicons-groups',
        27
    );

    // Agregar submenú de diagnóstico
    add_submenu_page(
        'ht-mayorista-requests',
        'Diagnóstico Mayorista',
        'Diagnóstico',
        'manage_options',
        'ht-mayorista-diagnostico',
        'ht_mayorista_diagnostico_page'
    );
}

/**
 * Página admin de solicitudes
 */
function ht_mayorista_requests_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'ht_mayorista_requests';

    // Aprobar solicitud
    if (isset($_GET['action']) && $_GET['action'] === 'approve' && isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $request = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id));
        
        if ($request) {
            $user = new WP_User($request->user_id);
            $user->set_role('cliente_mayorista');
            
            $wpdb->update(
                $table_name,
                array('estado' => 'aprobado', 'fecha_aprobacion' => current_time('mysql')),
                array('id' => $id),
                array('%s', '%s'),
                array('%d')
            );
            
            delete_user_meta($request->user_id, 'ht_mayorista_pending');
            
            $subject = 'Solicitud mayorista aprobada - Hobby Toys';
            $body = sprintf(
                "<h2>¡Felicitaciones %s!</h2>
                <p>Tu solicitud de registro mayorista ha sido aprobada.</p>
                <p>Ya podés acceder a precios especiales en nuestra tienda.</p>
                <p><a href='%s'>Iniciar sesión</a></p>
                <br>
                <p>Saludos,<br>Equipo Hobby Toys</p>",
                esc_html($request->razon_social),
                wp_login_url()
            );
            wp_mail($request->email, $subject, $body, array('Content-Type: text/html; charset=UTF-8'));
            
            echo '<div class="notice notice-success"><p>Solicitud aprobada correctamente.</p></div>';
        }
    }

    // Rechazar solicitud
    if (isset($_GET['action']) && $_GET['action'] === 'reject' && isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $wpdb->update($table_name, array('estado' => 'rechazado'), array('id' => $id), array('%s'), array('%d'));
        echo '<div class="notice notice-warning"><p>Solicitud rechazada.</p></div>';
    }

    $requests = $wpdb->get_results("SELECT * FROM $table_name ORDER BY fecha DESC");
    ?>
    <div class="wrap">
        <h1 class="wp-heading-inline">Solicitudes de Registro Mayorista</h1>
        
        <?php if (empty($requests)): ?>
            <div class="notice notice-info"><p>No hay solicitudes pendientes.</p></div>
        <?php else: ?>
            <table class="wp-list-table widefat fixed striped" id="mayorista-table">
                <thead>
                    <tr>
                        <th width="120">Fecha</th>
                        <th>Razón Social</th>
                        <th width="120">CUIT</th>
                        <th>Email</th>
                        <th width="120">Tipo Negocio</th>
                        <th width="100">Estado</th>
                        <th width="200">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($requests as $req): ?>
                    <tr>
                        <td><?php echo date('d/m/Y', strtotime($req->fecha)); ?></td>
                        <td><strong><?php echo esc_html($req->razon_social); ?></strong></td>
                        <td><?php echo esc_html($req->cuit); ?></td>
                        <td><?php echo esc_html($req->email); ?></td>
                        <td><?php echo esc_html($req->tipo_negocio); ?></td>
                        <td>
                            <?php 
                            $colors = array(
                                'pendiente' => 'warning',
                                'aprobado' => 'success',
                                'rechazado' => 'error'
                            );
                            $color = $colors[$req->estado] ?? 'info';
                            ?>
                            <span class="badge badge-<?php echo $color; ?>"><?php echo ucfirst($req->estado); ?></span>
                        </td>
                        <td>
                            <?php if ($req->estado === 'pendiente'): ?>
                            <a href="?page=ht-mayorista-requests&action=approve&id=<?php echo $req->id; ?>" 
                               class="button button-primary button-small">Aprobar</a>
                            <a href="?page=ht-mayorista-requests&action=reject&id=<?php echo $req->id; ?>" 
                               class="button button-small">Rechazar</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <script>
    jQuery(document).ready(function($) {
        if ($('#mayorista-table').length) {
            $('#mayorista-table').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
                },
                order: [[0, 'desc']],
                pageLength: 25
            });
        }
    });
    </script>
    <?php
}

/**
 * Página de diagnóstico mayorista
 */
function ht_mayorista_diagnostico_page() {
    // Manejar acción de flush manual
    if (isset($_POST['ht_flush_rewrite_rules']) && check_admin_referer('ht_mayorista_diagnostico', 'ht_diagnostico_nonce')) {
        ht_add_wholesale_endpoints();
        flush_rewrite_rules(false);
        delete_option('ht_wholesale_endpoints_last_check'); // Resetear el check
        echo '<div class="notice notice-success is-dismissible"><p><strong>✓ Reglas de reescritura regeneradas correctamente</strong></p></div>';
    }

    // Obtener información del sistema
    $mayorista_role = get_role('cliente_mayorista');
    $mayoristas = get_users(array('role' => 'cliente_mayorista', 'number' => 10));
    $wc_endpoints = WC()->query->get_query_vars();
    $myaccount_page_id = get_option('woocommerce_myaccount_page_id');
    $last_check = get_option('ht_wholesale_endpoints_last_check', 0);

    ?>
    <div class="wrap">
        <h1>🔍 Diagnóstico Sistema Mayorista</h1>

        <div class="card" style="max-width: 100%; margin-top: 20px;">

            <!-- Rol Mayorista -->
            <h2>1. Rol Mayorista</h2>
            <?php if ($mayorista_role): ?>
                <div class="notice notice-success inline">
                    <p>✓ El rol <code>cliente_mayorista</code> existe correctamente</p>
                </div>
                <p><strong>Capacidades:</strong></p>
                <ul>
                    <?php foreach ($mayorista_role->capabilities as $cap => $enabled): ?>
                        <li><code><?php echo esc_html($cap); ?></code>: <?php echo $enabled ? 'Sí' : 'No'; ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <div class="notice notice-error inline">
                    <p>✗ El rol <code>cliente_mayorista</code> no existe</p>
                </div>
            <?php endif; ?>

            <!-- Usuarios Mayoristas -->
            <h2>2. Usuarios Mayoristas</h2>
            <?php if (empty($mayoristas)): ?>
                <div class="notice notice-warning inline">
                    <p>⚠ No hay usuarios con el rol mayorista actualmente</p>
                </div>
            <?php else: ?>
                <div class="notice notice-success inline">
                    <p>✓ Encontrados <?php echo count($mayoristas); ?> usuario(s) mayorista(s)</p>
                </div>
                <table class="widefat">
                    <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Email</th>
                            <th>Empresa</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($mayoristas as $user):
                            $company = get_user_meta($user->ID, 'billing_company', true);
                        ?>
                        <tr>
                            <td><strong><?php echo esc_html($user->display_name); ?></strong></td>
                            <td><?php echo esc_html($user->user_email); ?></td>
                            <td><?php echo $company ? esc_html($company) : '-'; ?></td>
                            <td><a href="<?php echo get_edit_user_link($user->ID); ?>" class="button button-small">Editar</a></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>

            <!-- Endpoints WooCommerce -->
            <h2>3. Endpoints de WooCommerce</h2>
            <?php
            $dashboard_exists = isset($wc_endpoints['mayorista-dashboard']);
            $pedidos_exists = isset($wc_endpoints['mayorista-pedidos']);
            ?>

            <table class="widefat">
                <thead>
                    <tr>
                        <th>Endpoint</th>
                        <th>Estado</th>
                        <th>URL</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><code>mayorista-dashboard</code></td>
                        <td>
                            <?php if ($dashboard_exists): ?>
                                <span style="color: green;">✓ Registrado</span>
                            <?php else: ?>
                                <span style="color: red;">✗ No registrado</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($myaccount_page_id): ?>
                                <a href="<?php echo wc_get_account_endpoint_url('mayorista-dashboard'); ?>" target="_blank">
                                    <?php echo wc_get_account_endpoint_url('mayorista-dashboard'); ?>
                                </a>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td><code>mayorista-pedidos</code></td>
                        <td>
                            <?php if ($pedidos_exists): ?>
                                <span style="color: green;">✓ Registrado</span>
                            <?php else: ?>
                                <span style="color: red;">✗ No registrado</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($myaccount_page_id): ?>
                                <a href="<?php echo wc_get_account_endpoint_url('mayorista-pedidos'); ?>" target="_blank">
                                    <?php echo wc_get_account_endpoint_url('mayorista-pedidos'); ?>
                                </a>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Verificación Automática -->
            <h2>4. Verificación Automática</h2>
            <?php if ($last_check > 0): ?>
                <div class="notice notice-info inline">
                    <p>Última verificación: <strong><?php echo date('d/m/Y H:i:s', $last_check); ?></strong></p>
                    <p>Próxima verificación: <strong><?php echo date('d/m/Y H:i:s', $last_check + DAY_IN_SECONDS); ?></strong></p>
                </div>
            <?php else: ?>
                <div class="notice notice-warning inline">
                    <p>⚠ No se ha ejecutado ninguna verificación automática aún</p>
                </div>
            <?php endif; ?>

            <!-- Página Mi Cuenta -->
            <h2>5. Configuración WooCommerce</h2>
            <?php if ($myaccount_page_id):
                $myaccount_page = get_post($myaccount_page_id);
            ?>
                <div class="notice notice-success inline">
                    <p>✓ Página "Mi Cuenta" configurada: <strong><?php echo esc_html($myaccount_page->post_title); ?></strong> (ID: <?php echo $myaccount_page_id; ?>)</p>
                    <p><a href="<?php echo get_permalink($myaccount_page_id); ?>" target="_blank" class="button">Ver Mi Cuenta</a></p>
                </div>
            <?php else: ?>
                <div class="notice notice-error inline">
                    <p>✗ WooCommerce no tiene una página "Mi Cuenta" configurada</p>
                </div>
            <?php endif; ?>

            <!-- Acción de Reparación -->
            <h2>6. Solución de Problemas</h2>
            <div class="notice notice-info inline">
                <p>Si los endpoints muestran errores 404, usa este botón para regenerar las reglas de reescritura:</p>
            </div>

            <form method="post" action="">
                <?php wp_nonce_field('ht_mayorista_diagnostico', 'ht_diagnostico_nonce'); ?>
                <button type="submit" name="ht_flush_rewrite_rules" class="button button-primary button-hero">
                    🔄 Regenerar Reglas de Reescritura
                </button>
            </form>

            <h3 style="margin-top: 30px;">Pasos Manuales Alternativos:</h3>
            <ol>
                <li>Ve a <a href="<?php echo admin_url('options-permalink.php'); ?>"><strong>Ajustes → Enlaces Permanentes</strong></a></li>
                <li>Haz clic en <strong>"Guardar cambios"</strong> (sin modificar nada)</li>
                <li>Regresa aquí y verifica que los endpoints funcionen</li>
            </ol>

        </div>

        <style>
            .card {
                background: white;
                padding: 30px;
                border-radius: 8px;
                box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            }
            .card h2 {
                color: #EE285B;
                border-bottom: 2px solid #534FB5;
                padding-bottom: 10px;
                margin-top: 30px;
            }
            .card h2:first-of-type {
                margin-top: 0;
            }
            .notice.inline {
                margin: 15px 0;
            }
            code {
                background: #f0f0f1;
                padding: 3px 6px;
                border-radius: 3px;
            }
        </style>
    </div>
    <?php
}

/**
 * Notice de emergencia en el admin si los endpoints no funcionan
 */
add_action('admin_notices', 'ht_mayorista_emergency_notice');
function ht_mayorista_emergency_notice() {
    // Solo mostrar a administradores
    if (!current_user_can('manage_options')) {
        return;
    }

    // Manejar acción de flush de emergencia
    if (isset($_GET['ht_emergency_flush']) && check_admin_referer('ht_emergency_flush', '_wpnonce')) {
        // Registrar query vars
        add_filter('woocommerce_get_query_vars', function($query_vars) {
            $query_vars['mayorista-dashboard'] = 'mayorista-dashboard';
            $query_vars['mayorista-pedidos'] = 'mayorista-pedidos';
            return $query_vars;
        });

        // Registrar endpoints
        add_rewrite_endpoint('mayorista-dashboard', EP_ROOT | EP_PAGES);
        add_rewrite_endpoint('mayorista-pedidos', EP_ROOT | EP_PAGES);

        // Flush
        flush_rewrite_rules(false);
        delete_option('ht_wholesale_endpoints_last_check');

        echo '<div class="notice notice-success is-dismissible"><p><strong>✓ Endpoints mayoristas regenerados correctamente.</strong> Ahora deberían funcionar sin errores 404.</p></div>';
        return;
    }

    // Verificar si los endpoints están registrados
    $wc_endpoints = WC()->query->get_query_vars();
    $dashboard_exists = isset($wc_endpoints['mayorista-dashboard']);
    $pedidos_exists = isset($wc_endpoints['mayorista-pedidos']);

    // Si faltan endpoints, mostrar notice de emergencia
    if (!$dashboard_exists || !$pedidos_exists) {
        $flush_url = wp_nonce_url(add_query_arg('ht_emergency_flush', '1'), 'ht_emergency_flush');
        ?>
        <div class="notice notice-error">
            <h3 style="margin-top: 15px;">⚠️ Endpoints Mayoristas No Funcionan</h3>
            <p><strong>Los endpoints de perfil mayorista no están registrados correctamente y darán error 404.</strong></p>
            <p>Haz clic en el botón de abajo para arreglar el problema inmediatamente:</p>
            <p>
                <a href="<?php echo esc_url($flush_url); ?>" class="button button-primary button-hero">
                    🔄 Arreglar Endpoints Mayoristas Ahora
                </a>
                <a href="<?php echo admin_url('admin.php?page=ht-mayorista-diagnostico'); ?>" class="button">
                    Ver Diagnóstico Completo
                </a>
            </p>
        </div>
        <?php
    }
}

/**
 * Cargar DataTables en admin
 */
add_action('admin_enqueue_scripts', 'ht_load_datatables_admin');
function ht_load_datatables_admin($hook) {
    if ($hook !== 'toplevel_page_ht-mayorista-requests') {
        return;
    }

    wp_enqueue_style('datatables', 'https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css');
    wp_enqueue_script('datatables', 'https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js', array('jquery'), null, true);
}

/**
 * Notificación admin para nuevos pedidos mayoristas
 */
add_action('woocommerce_order_status_processing', 'ht_notify_admin_wholesale_order', 10, 1);
function ht_notify_admin_wholesale_order($order_id) {
    $order = wc_get_order($order_id);
    
    if ($order->get_meta('_is_wholesale_order') !== 'yes') {
        return;
    }
    
    $admin_email = get_option('admin_email');
    $company = $order->get_meta('_wholesale_company');
    $cuit = $order->get_meta('_wholesale_cuit');
    
    $subject = 'Nuevo Pedido Mayorista #' . $order->get_order_number() . ' - ' . $company;
    
    $message = sprintf(
        "<h2>Nuevo Pedido Mayorista Recibido</h2>
        <p><strong>Número de Pedido:</strong> #%s</p>
        <p><strong>Empresa:</strong> %s</p>
        <p><strong>CUIT:</strong> %s</p>
        <p><strong>Total:</strong> $%s</p>
        <p><strong>Items:</strong> %s</p>
        <br>
        <p><a href='%s'>Ver pedido en el admin</a></p>
        <br>
        <p><em>Este es un pedido mayorista y requiere revisión especial.</em></p>",
        $order->get_order_number(),
        esc_html($company),
        esc_html($cuit),
        number_format($order->get_total(), 2, ',', '.'),
        $order->get_item_count(),
        admin_url('post.php?post=' . $order_id . '&action=edit')
    );
    
    wp_mail($admin_email, $subject, $message, array('Content-Type: text/html; charset=UTF-8'));
}

/**
 * Columna "Mayorista" en lista de pedidos
 */
add_filter('manage_edit-shop_order_columns', 'ht_add_wholesale_column_to_orders', 20);
function ht_add_wholesale_column_to_orders($columns) {
    $new_columns = array();
    
    foreach ($columns as $key => $column) {
        $new_columns[$key] = $column;
        
        if ($key === 'order_number') {
            $new_columns['wholesale'] = 'Mayorista';
        }
    }
    
    return $new_columns;
}

add_action('manage_shop_order_posts_custom_column', 'ht_display_wholesale_column_content', 20, 2);
function ht_display_wholesale_column_content($column, $post_id) {
    if ($column === 'wholesale') {
        $order = wc_get_order($post_id);
        
        if ($order->get_meta('_is_wholesale_order') === 'yes') {
            echo '<span style="background: #FFB900; color: #2C3E50; padding: 4px 8px; border-radius: 4px; font-weight: 700; font-size: 11px;">MAYORISTA</span>';
            
            $company = $order->get_meta('_wholesale_company');
            if ($company) {
                echo '<br><small>' . esc_html($company) . '</small>';
            }
        } else {
            echo '<span style="color: #999;">-</span>';
        }
    }
}

/**
 * Columna "Precio Mayorista" en productos
 */
add_filter('manage_product_posts_columns', 'ht_add_wholesale_price_column', 15);
function ht_add_wholesale_price_column($columns) {
    $new_columns = array();
    
    foreach ($columns as $key => $column) {
        $new_columns[$key] = $column;
        
        if ($key === 'price') {
            $new_columns['wholesale_price'] = 'Precio Mayorista';
        }
    }
    
    return $new_columns;
}

add_action('manage_product_posts_custom_column', 'ht_display_wholesale_price_column', 10, 2);
function ht_display_wholesale_price_column($column, $post_id) {
    if ($column === 'wholesale_price') {
        $wholesale_price = get_post_meta($post_id, '_wholesale_price', true);
        
        if (!empty($wholesale_price) && $wholesale_price > 0) {
           // echo '<span style="color: #FFB900; font-weight: 700;"> . number_format($wholesale_price, 2, ',', '.') . '</span>';
        } else {
            echo '<span style="color: #999;">No definido</span>';
        }
    }
}

/**
 * Mostrar referencia en admin de pedidos
 */
add_action('woocommerce_admin_order_data_after_billing_address', 'ht_display_mayorista_reference_in_admin');
function ht_display_mayorista_reference_in_admin($order) {
    $reference = $order->get_meta('_mayorista_reference');
    
    if (!empty($reference)) {
        echo '<p><strong>Referencia Interna Mayorista:</strong> ' . esc_html($reference) . '</p>';
    }
}


// ============================================
// SECCIÓN 9: SCRIPTS Y ENQUEUE
// ============================================

/**
 * Scripts mayorista para frontend
 */
add_action('wp_enqueue_scripts', 'ht_enqueue_mayorista_scripts', 20);
function ht_enqueue_mayorista_scripts() {
    if (!is_user_logged_in() || !current_user_can('cliente_mayorista')) {
        return;
    }
    
    wp_enqueue_script(
        'ht-mayorista-enhancements',
        get_stylesheet_directory_uri() . '/assets/js/mayorista-enhancements.js',
        array('jquery'),
        filemtime(get_stylesheet_directory() . '/assets/js/mayorista-enhancements.js'),
        true
    );
    
    wp_localize_script('ht-mayorista-enhancements', 'htMayorista', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('ht_mayorista_nonce'),
        'user_id' => get_current_user_id(),
        'is_wholesale' => true,
        'dashboard_url' => wc_get_account_endpoint_url('mayorista-dashboard')
    ));
}


// ============================================
// SECCIÓN 10: UTILIDADES Y SHORTCODES
// ============================================

/**
 * Shortcode para contenido solo mayoristas
 */
add_shortcode('is_wholesale', 'ht_is_wholesale_shortcode');
function ht_is_wholesale_shortcode($atts, $content = null) {
    if (is_user_logged_in() && current_user_can('cliente_mayorista')) {
        return do_shortcode($content);
    }
    return '';
}

/**
 * Shortcode para mostrar precio mayorista
 */
add_shortcode('wholesale_price', 'ht_wholesale_price_shortcode');
function ht_wholesale_price_shortcode($atts) {
    $atts = shortcode_atts(array('id' => 0), $atts);
    
    if (!is_user_logged_in() || !current_user_can('cliente_mayorista')) {
        return '';
    }
    
    $product_id = intval($atts['id']);
    if ($product_id <= 0) {
        return '';
    }
    
    $wholesale_price = get_post_meta($product_id, '_wholesale_price', true);
    
    if (!empty($wholesale_price) && $wholesale_price > 0) {
        //return '<span class="wholesale-price"> . number_format($wholesale_price, 2, ',', '.') . '</span>';
    }
    
    return '';
}

/**
 * AJAX: Obtener estadísticas mayorista
 */
add_action('wp_ajax_ht_get_wholesale_stats', 'ht_get_wholesale_stats_ajax');
function ht_get_wholesale_stats_ajax() {
    check_ajax_referer('ht_mayorista_nonce', 'nonce');
    
    if (!current_user_can('cliente_mayorista')) {
        wp_send_json_error('No autorizado');
        return;
    }
    
    $user_id = get_current_user_id();
    
    $orders = wc_get_orders(array(
        'customer_id' => $user_id,
        'meta_key' => '_is_wholesale_order',
        'meta_value' => 'yes',
        'limit' => -1
    ));
    
    $stats = array(
        'total_orders' => count($orders),
        'total_spent' => 0,
        'pending_orders' => 0,
        'completed_orders' => 0
    );
    
    foreach ($orders as $order) {
        $stats['total_spent'] += $order->get_total();
        
        if (in_array($order->get_status(), array('pending', 'on-hold'))) {
            $stats['pending_orders']++;
        } elseif ($order->get_status() === 'completed') {
            $stats['completed_orders']++;
        }
    }
    
    wp_send_json_success($stats);
}


// ============================================
// SECCIÓN 11: WIDGET SIDEBAR
// ============================================

/**
 * Registrar widget de información mayorista
 */
add_action('widgets_init', 'ht_register_wholesale_widget');
function ht_register_wholesale_widget() {
    register_widget('HT_Wholesale_Info_Widget');
}

class HT_Wholesale_Info_Widget extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'ht_wholesale_info',
            'Info Mayorista',
            array('description' => 'Muestra información para clientes mayoristas')
        );
    }
    
    public function widget($args, $instance) {
        if (!is_user_logged_in() || !current_user_can('cliente_mayorista')) {
            return;
        }
        
        echo $args['before_widget'];
        ?>
        <div class="wholesale-info-widget p-3" style="background: linear-gradient(135deg, rgba(255, 185, 0, 0.1), rgba(255, 140, 0, 0.1)); border-radius: 1rem; border: 2px solid #FFB900;">
            <h4 style="color: #FF8C00; font-weight: 700; margin-bottom: 1rem;">
                <i class="bi bi-star-fill me-2"></i>Cliente Mayorista
            </h4>
            
            <ul class="list-unstyled mb-3">
                <li class="mb-2">
                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                    Precios especiales
                </li>
                <li class="mb-2">
                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                    Envíos a todo el país
                </li>
                <li class="mb-2">
                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                    Asesor personalizado
                </li>
                <li class="mb-2">
                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                    Armado de pedidos en 24-48hs
                </li>
            </ul>
            
            <a href="<?php echo esc_url(wc_get_account_endpoint_url('mayorista-dashboard')); ?>" 
               class="btn btn-sm w-100" 
               style="background: #FFB900; color: #2C3E50; font-weight: 700;">
                Ver Mi Panel
            </a>
        </div>
        <?php
        echo $args['after_widget'];
    }
}


// ============================================
// SECCIÓN 12: HERRAMIENTAS ADMIN
// ============================================

/**
 * Menú de herramientas mayoristas
 */
add_action('admin_menu', 'ht_add_wholesale_tools_menu');
function ht_add_wholesale_tools_menu() {
    add_submenu_page(
        'woocommerce',
        'Herramientas Mayoristas',
        'Herramientas Mayoristas',
        'manage_woocommerce',
        'ht-wholesale-tools',
        'ht_wholesale_tools_page'
    );
}

function ht_wholesale_tools_page() {
    ?>
    <div class="wrap">
        <h1>Herramientas para Precios Mayoristas</h1>
        
        <div class="card" style="max-width: 800px; margin-top: 20px; padding: 20px;">
            <h2>Importar Precios Mayoristas desde CSV</h2>
            <p>Sube un archivo CSV con las columnas: <code>SKU, Precio Mayorista</code></p>
            
            <form method="post" enctype="multipart/form-data">
                <?php wp_nonce_field('ht_import_wholesale_prices', 'ht_import_nonce'); ?>
                <input type="file" name="wholesale_csv" accept=".csv" required>
                <input type="submit" name="import_wholesale_prices" class="button button-primary" value="Importar Precios">
            </form>
        </div>
        
        <div class="card" style="max-width: 800px; margin-top: 20px; padding: 20px;">
            <h2>Exportar Lista de Clientes Mayoristas</h2>
            <p>Descarga un CSV con todos los clientes mayoristas registrados.</p>
            
            <form method="post">
                <?php wp_nonce_field('ht_export_wholesale_customers', 'ht_export_nonce'); ?>
                <input type="submit" name="export_wholesale_customers" class="button button-primary" value="Exportar CSV">
            </form>
        </div>
    </div>
    <?php
}

/**
 * Procesar importación de precios
 */
add_action('admin_init', 'ht_process_wholesale_import');
function ht_process_wholesale_import() {
    if (!isset($_POST['import_wholesale_prices']) || !current_user_can('manage_woocommerce')) {
        return;
    }
    
    check_admin_referer('ht_import_wholesale_prices', 'ht_import_nonce');
    
    if (!isset($_FILES['wholesale_csv']) || $_FILES['wholesale_csv']['error'] !== UPLOAD_ERR_OK) {
        add_action('admin_notices', function() {
            echo '<div class="notice notice-error"><p>Error al subir archivo</p></div>';
        });
        return;
    }
    
    $file = $_FILES['wholesale_csv']['tmp_name'];
    $handle = fopen($file, 'r');
    
    if ($handle === false) {
        return;
    }
    
    $updated = 0;
    $row = 0;
    
    while (($data = fgetcsv($handle, 1000, ',')) !== false) {
        $row++;
        
        if ($row === 1 && strtolower($data[0]) === 'sku') {
            continue;
        }
        
        $sku = $data[0];
        $wholesale_price = floatval($data[1]);
        
        $product_id = wc_get_product_id_by_sku($sku);
        
        if ($product_id && $wholesale_price > 0) {
            update_post_meta($product_id, '_wholesale_price', $wholesale_price);
            $updated++;
        }
    }
    
    fclose($handle);
    
    add_action('admin_notices', function() use ($updated) {
        echo '<div class="notice notice-success"><p>' . $updated . ' precios mayoristas actualizados</p></div>';
    });
}

/**
 * Procesar exportación de clientes
 */
add_action('admin_init', 'ht_process_wholesale_export');
function ht_process_wholesale_export() {
    if (!isset($_POST['export_wholesale_customers']) || !current_user_can('manage_woocommerce')) {
        return;
    }
    
    check_admin_referer('ht_export_wholesale_customers', 'ht_export_nonce');
    
    $users = get_users(array(
        'role' => 'cliente_mayorista',
        'fields' => 'all'
    ));
    
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=clientes-mayoristas-' . date('Y-m-d') . '.csv');
    
    $output = fopen('php://output', 'w');
    
    fputcsv($output, array('ID', 'Email', 'Razón Social', 'CUIT', 'Tipo Negocio', 'Fecha Registro'));
    
    foreach ($users as $user) {
        fputcsv($output, array(
            $user->ID,
            $user->user_email,
            get_user_meta($user->ID, 'billing_company', true),
            get_user_meta($user->ID, 'ht_cuit', true),
            get_user_meta($user->ID, 'ht_tipo_negocio', true),
            date('d/m/Y', strtotime($user->user_registered))
        ));
    }
    
    fclose($output);
    exit;
}


/**
 * Sistema de Restricciones y Redirección para Mayoristas
 * Agregar este código en functions.php o mejor aún, 
 * al final del archivo inc-mayorista.php
 */

// ============================================
// 1. REDIRECCIÓN DE HOME A TIENDA PARA MAYORISTAS
// ============================================
//
//add_action('template_redirect', 'ht_redirect_wholesale_to_shop');
function ht_redirect_wholesale_to_shop() {
    // Solo ejecutar si el usuario está logueado y es mayorista
    if (!is_user_logged_in() || !current_user_can('cliente_mayorista')) {
        return;
    }
    
    // Redirigir si está en el home
   /* if (is_home() || is_front_page()) {
        wp_redirect(wc_get_page_permalink('shop'));
        exit;
    }*/
}

// ============================================
// 2. OCULTAR SECCIÓN DE ENVÍO GRATIS PARA MAYORISTAS
// ============================================

// Modificar el cálculo de envío gratis para mayoristas
add_filter('ht_free_shipping_progress', 'ht_disable_free_shipping_for_wholesale');
function ht_disable_free_shipping_for_wholesale($progress) {
    if (is_user_logged_in() && current_user_can('cliente_mayorista')) {
        return false; // No mostrar progreso de envío gratis
    }
    return $progress;
}

// Ocultar widget de envíos en barra superior para mayoristas
add_action('wp_head', 'ht_hide_shipping_bar_for_wholesale');
function ht_hide_shipping_bar_for_wholesale() {
    if (is_user_logged_in() && current_user_can('cliente_mayorista')) {
        ?>
        <style>
            .topbar-shipping-widget,
            .shipping-progress-data,
            .free-shipping-alert,
            .shipping-section .free-shipping-alert {
                display: none !important;
            }
        </style>
        <?php
    }
} 

// ============================================
// 3. FILTRAR MÉTODOS DE ENVÍO PARA MAYORISTAS
// ============================================

add_filter('woocommerce_package_rates', 'ht_custom_shipping_for_wholesale', 100, 2);
function ht_custom_shipping_for_wholesale($rates, $package) {
    if (!is_user_logged_in() || !current_user_can('cliente_mayorista')) {
        return $rates;
    }
    
    // Para mayoristas, eliminar envío gratis si existe
    foreach ($rates as $rate_key => $rate) {
        if ('free_shipping' === $rate->method_id) {
            unset($rates[$rate_key]);
        }
    }
    
    return $rates;
}

// ============================================
// 4. PERSONALIZAR MENSAJES DE ENVÍO PARA MAYORISTAS
// ============================================

add_filter('woocommerce_shipping_method_full_label', 'ht_customize_shipping_label_wholesale', 10, 2);
function ht_customize_shipping_label_wholesale($label, $method) {
    if (is_user_logged_in() && current_user_can('cliente_mayorista')) {
        // Agregar texto especial para mayoristas
        if (strpos($method->id, 'flat_rate') !== false) {
            $label .= ' <small style="color: var(--accent-color);">(Tarifa Mayorista)</small>';
        }
    }
    return $label;
}

// ============================================
// 5. OCULTAR PROMOCIONES Y OFERTAS EN LA TIENDA
// ============================================

// Ocultar badges de porcentaje de descuento (ya implementado pero mejorado)
add_filter('woocommerce_sale_flash', '__return_empty_string', 99);

// Ocultar sección de productos en oferta
add_action('pre_get_posts', 'ht_hide_sale_products_for_wholesale');
function ht_hide_sale_products_for_wholesale($query) {
    if (!is_user_logged_in() || !current_user_can('cliente_mayorista')) {
        return;
    }
    
    // Si es una consulta de productos en oferta, vaciarla
    if (!is_admin() && $query->is_main_query() && isset($query->query_vars['post__in'])) {
        $sale_products = wc_get_product_ids_on_sale();
        if ($query->query_vars['post__in'] == $sale_products) {
            $query->set('post__in', array(0)); // No mostrar productos
        }
    }
}

// ============================================
// 6. MODIFICAR INFORMACIÓN EN PRODUCTO INDIVIDUAL
// ============================================

// Ocultar sección de formas de pago con cuotas
add_action('woocommerce_single_product_summary', 'ht_hide_payment_info_for_wholesale', 1);
function ht_hide_payment_info_for_wholesale() {
    if (is_user_logged_in() && current_user_can('cliente_mayorista')) {
        // Remover la función que muestra métodos de pago con cuotas
        remove_action('woocommerce_single_product_summary', 'mostrar_precio_sin_iva', 11);
        
        // CSS para ocultar secciones específicas
        ?>
        <style>
            .payment-methods-section,
            .shipping-section .free-shipping-alert,
            .payment-card:has(.badge.bg-success),
            .guarantees-section .guarantee-badge:contains("30 días"),
            #cuotasModal {
                display: none !important;
            }
            
            /* Simplificar la sección de envíos */
            .shipping-section h2:after {
                content: " (Mayorista)";
                color: var(--accent-color);
                font-size: 0.8em;
            }
        </style>
        <?php
        
        // Agregar información específica para mayoristas
        add_action('woocommerce_single_product_summary', 'ht_add_wholesale_product_info', 12);
    }
}

function ht_add_wholesale_product_info() {
    ?>
    <div class="mayorista-info-box alert alert-info" style="background: linear-gradient(135deg, rgba(255, 185, 0, 0.1), rgba(255, 140, 0, 0.1)); border: 2px solid var(--accent-color); border-radius: 1rem; padding: 1.5rem; margin: 2rem 0;">
        <h5 style="color: var(--accent-color); font-weight: 700;">
            <i class="bi bi-info-circle-fill me-2"></i>Información para Cliente Mayorista
        </h5>
        <ul class="mb-0" style="padding-left: 1.2rem;">
            <li>Precio exclusivo mayorista aplicado</li>
            <li>Mínimo de compra: 10 unidades por referencia</li>
            <li>Envío según volumen - Consultar tarifas especiales</li>
            <li>Facturación con CUIT</li>
            <li>Condiciones de pago: A convenir</li>
        </ul>
    </div>
    <?php
}

// ============================================
// 7. PERSONALIZAR MENÚ PARA MAYORISTAS
// ============================================

add_filter('wp_nav_menu_items', 'ht_customize_menu_for_wholesale', 10, 2);
function ht_customize_menu_for_wholesale($items, $args) {
    if (!is_user_logged_in() || !current_user_can('cliente_mayorista')) {
        return $items;
    }
    
    // Agregar indicador de mayorista en el menú principal
    if ($args->theme_location == 'main-menu') {
        $wholesale_badge = '<li class="menu-item wholesale-indicator" style="margin-left: auto;">
            <a href="' . wc_get_account_endpoint_url('mayorista-dashboard') . '" class="nav-link">
                <span class="badge" style="background: var(--accent-color); color: var(--text-dark); font-weight: 700;">
                    <i class="bi bi-star-fill me-1"></i>CUENTA MAYORISTA
                </span>
            </a>
        </li>';
        $items .= $wholesale_badge;
    }
    
    return $items;
}

// ============================================
// 8. MODIFICAR PÁGINA DE TIENDA PARA MAYORISTAS
// ============================================

add_action('woocommerce_before_shop_loop', 'ht_add_wholesale_shop_banner', 5);
function ht_add_wholesale_shop_banner() {
    if (!is_user_logged_in() || !current_user_can('cliente_mayorista')) {
        return;
    }
    
    $user = wp_get_current_user();
    $company = get_user_meta($user->ID, 'billing_company', true);
    ?>
    <div class="wholesale-shop-banner mb-4" style="background: linear-gradient(135deg, var(--accent-color), #FF8C00); border-radius: 1rem; padding: 2rem; color: var(--text-dark);">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h3 style="font-weight: 900; margin-bottom: 0.5rem;">
                    ¡Bienvenido, <?php echo esc_html($company); ?>!
                </h3>
                <p class="mb-0" style="font-weight: 600;">
                    Estás viendo precios exclusivos mayoristas. Mínimo de compra: 10 unidades por producto.
                </p>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="<?php echo wc_get_account_endpoint_url('mayorista-dashboard'); ?>" 
                   class="btn btn-dark btn-lg" 
                   style="border-radius: 2rem; font-weight: 700;">
                    <i class="bi bi-speedometer2 me-2"></i>Mi Dashboard
                </a>
            </div>
        </div>
    </div>
    <?php
}

// ============================================
// 9. ESTABLECER CANTIDAD MÍNIMA PARA MAYORISTAS
// ============================================
/*
add_filter('woocommerce_quantity_input_args', 'ht_set_minimum_quantity_wholesale', 10, 2);
function ht_set_minimum_quantity_wholesale($args, $product) {
    if (is_user_logged_in() && current_user_can('cliente_mayorista')) {
        $args['min_value'] = 10;
        $args['step'] = 5; // Incrementos de 5
        
        if ($args['input_value'] < 10) {
            $args['input_value'] = 10;
        }
    }
    return $args;
}

// Validar cantidad mínima en el carrito
add_action('woocommerce_check_cart_items', 'ht_validate_wholesale_quantities');
function ht_validate_wholesale_quantities() {
    if (!is_user_logged_in() || !current_user_can('cliente_mayorista')) {
        return;
    }
    
    foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
        if ($cart_item['quantity'] < 10) {
            wc_add_notice(
                sprintf(
                    'Como cliente mayorista, la cantidad mínima para "%s" es 10 unidades.',
                    $cart_item['data']->get_name()
                ),
                'error'
            );
        }
    }
}
*/
// ============================================
// 10. PERSONALIZAR EMAILS PARA MAYORISTAS
// ============================================

add_filter('woocommerce_email_heading', 'ht_customize_email_heading_wholesale', 10, 2);
function ht_customize_email_heading_wholesale($heading, $email) {
    if (!isset($email->object) || !is_a($email->object, 'WC_Order')) {
        return $heading;
    }
    
    $order = $email->object;
    if ($order->get_meta('_is_wholesale_order') === 'yes') {
        $heading = '🌟 ' . $heading . ' (Pedido Mayorista)';
    }
    
    return $heading;
}

// ============================================
// 11. OCULTAR WIDGETS DE OFERTAS EN SIDEBAR
// ============================================

add_filter('sidebars_widgets', 'ht_filter_widgets_for_wholesale');
function ht_filter_widgets_for_wholesale($sidebars_widgets) {
    if (!is_user_logged_in() || !current_user_can('cliente_mayorista')) {
        return $sidebars_widgets;
    }
    
    // Lista de widgets a ocultar para mayoristas
    $widgets_to_hide = array(
        'woocommerce_products_on_sale',
        'woocommerce_top_rated_products',
        'custom_offers_widget' // Si tienes widgets custom
    );
    
    foreach ($sidebars_widgets as $sidebar => &$widgets) {
        if (!is_array($widgets)) continue;
        
        $widgets = array_filter($widgets, function($widget) use ($widgets_to_hide) {
            foreach ($widgets_to_hide as $hide) {
                if (strpos($widget, $hide) !== false) {
                    return false;
                }
            }
            return true;
        });
    }
    
    return $sidebars_widgets;
}

// ============================================
// 12. AGREGAR CLASE CSS AL BODY PARA MAYORISTAS
// ============================================

add_filter('body_class', 'ht_add_wholesale_body_class');
function ht_add_wholesale_body_class($classes) {
    if (is_user_logged_in() && current_user_can('cliente_mayorista')) {
        $classes[] = 'user-wholesale';
        $classes[] = 'hide-retail-features';
    }
    return $classes;
}

// ============================================
// 13. CSS ADICIONAL PARA MAYORISTAS
// ============================================

add_action('wp_head', 'ht_wholesale_additional_styles', 100);
function ht_wholesale_additional_styles() {
    if (!is_user_logged_in() || !current_user_can('cliente_mayorista')) {
        return;
    }
    ?>
    <style>
        /* Ocultar elementos de retail para mayoristas */
        body.user-wholesale .onsale,
        body.user-wholesale .woocommerce-message:contains("envío gratis"),
        body.user-wholesale .free-shipping-notice,
        body.user-wholesale .shipping-progress,
        body.user-wholesale .payment-methods-section .payment-card:has(span:contains("cuotas")),
        body.user-wholesale .hero-slider-section,
        body.user-wholesale .age-categories-section,
        body.user-wholesale .stickers-section,
        body.user-wholesale .vehicles-section {
            display: none !important;
        }
        
        /* Estilos especiales para mayoristas */
        body.user-wholesale .price {
            font-size: 1.5rem !important;
            color: var(--accent-color) !important;
            font-weight: 900 !important;
        }
        
        body.user-wholesale .price::after {
            content: " (Mayorista)";
            font-size: 0.7em;
            color: var(--secondary-color);
            font-weight: 600;
        }
        
        /* Destacar cantidad mínima */
        body.user-wholesale .quantity input {
            border: 2px solid var(--accent-color) !important;
            background: rgba(255, 185, 0, 0.1) !important;
        }
        
        /* Badge en productos */
        body.user-wholesale .products .product::before {
            content: "PRECIO MAYORISTA";
            position: absolute;
            top: 10px;
            right: 10px;
            background: var(--accent-color);
            color: var(--text-dark);
            padding: 5px 10px;
            border-radius: 5px;
            font-weight: 700;
            font-size: 0.75rem;
            z-index: 10;
        }
    </style>
    <?php
}