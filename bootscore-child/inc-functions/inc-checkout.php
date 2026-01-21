<?php
/**
 * Personalizaciones del Checkout y Carrito
 * Diseño limpio, minimalista, simple y con excelente usabilidad
 */

// ============================================
// ESTILOS PERSONALIZADOS PARA EL CHECKOUT
// ============================================
add_action('wp_head', 'ht_checkout_custom_styles');
function ht_checkout_custom_styles() {
    if (!is_checkout()) return;
    ?>
    <style>
    /* ============================================
       CHECKOUT - DISEÑO MINIMALISTA Y PROFESIONAL
    ============================================ */

    /* Contenedor principal del checkout */
    .woocommerce-checkout {
        background: #f8f9fa;
    }

    .woocommerce-checkout .site-content {
        padding-top: 2rem !important;
        padding-bottom: 3rem !important;
    }

    /* Formulario de checkout */
    #customer_details {
        background: white;
        border-radius: 1rem;
        padding: 2rem;
        box-shadow: 0 2px 15px rgba(0,0,0,0.05);
        margin-bottom: 2rem;
    }

    /* Títulos iguales para ambas secciones */
    .woocommerce-billing-fields h3,
    .woocommerce-shipping-fields h3,
    .woocommerce-additional-fields h3,
    #order_review_heading {
        font-size: 1.5rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 3px solid var(--primary-color, #EE285B);
    }

    /* Campos del formulario */
    .woocommerce-checkout .form-row input.input-text,
    .woocommerce-checkout .form-row textarea,
    .woocommerce-checkout .form-row select {
        border: 2px solid #e9ecef;
        border-radius: 0.75rem;
        padding: 0.875rem 1rem;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: #ffffff;
    }

    .woocommerce-checkout .form-row input.input-text:focus,
    .woocommerce-checkout .form-row textarea:focus,
    .woocommerce-checkout .form-row select:focus {
        border-color: var(--primary-color, #EE285B);
        box-shadow: 0 0 0 3px rgba(238, 40, 91, 0.1);
        outline: none;
    }

    .woocommerce-checkout .form-row label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
    }

    .woocommerce-checkout .form-row label .required {
        color: #dc3545;
        font-weight: 700;
    }

    /* Resumen del pedido */
    .woocommerce-checkout-review-order {
        background: white;
        border-radius: 1rem;
        padding: 2rem;
        box-shadow: 0 2px 15px rgba(0,0,0,0.05);
    }

    .woocommerce-checkout-review-order-table {
        margin-bottom: 0;
        border: none;
    }

    .woocommerce-checkout-review-order-table thead {
        background: rgba(238, 40, 91, 0.05);
        border-radius: 0.5rem;
    }

    .woocommerce-checkout-review-order-table thead th {
        border: none;
        padding: 1rem;
        font-weight: 700;
        color: #2c3e50;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }

    /* Ancho de columnas: menos para producto, más para subtotal */
    .woocommerce-checkout-review-order-table .product-name {
        width: 40%;
    }

    .woocommerce-checkout-review-order-table .product-total {
        width: 60%;
    }

    .woocommerce-checkout-review-order-table tbody tr {
        border-bottom: 1px solid #f1f3f5;
    }

    .woocommerce-checkout-review-order-table tbody td {
        padding: 1rem;
        vertical-align: middle;
    }

    .woocommerce-checkout-review-order-table .product-name {
        font-weight: 600;
        color: #2c3e50;
        font-size: 0.95rem;
    }

    .woocommerce-checkout-review-order-table .product-total {
        font-weight: 700;
        color: var(--primary-color, #EE285B);
        text-align: right;
        font-size: 1rem;
    }

    .woocommerce-checkout-review-order-table tfoot tr {
        border-top: 2px solid #dee2e6;
    }

    .woocommerce-checkout-review-order-table tfoot th,
    .woocommerce-checkout-review-order-table tfoot td {
        padding: 1rem;
        font-weight: 700;
        font-size: 1rem;
    }

    .woocommerce-checkout-review-order-table tfoot td {
        text-align: right;
    }

    .order-total th,
    .order-total td {
        color: var(--primary-color, #EE285B);
        font-size: 1.5rem !important;
        padding-top: 1.25rem !important;
    }

    /* Métodos de pago - SIN degradados */
    /*
    .woocommerce-checkout-payment {
        background: white;
        border-radius: 0.75rem;
        padding: 1.5rem;
        margin-top: 2rem;
        border: 1px solid #e9ecef;
    }

    .woocommerce-checkout-payment ul.payment_methods {
        border: none;
        padding: 0;
        margin-bottom: 1.5rem;
    }

    .woocommerce-checkout-payment ul.payment_methods li {
        background: white;
        border: 3px solid #e9ecef;
        border-radius: 0.75rem;
        margin-bottom: 1rem;
        padding: 1.25rem 1.5rem;
        transition: all 0.3s ease;
        position: relative;
    }

    .woocommerce-checkout-payment ul.payment_methods li:hover {
        border-color: rgba(238, 40, 91, 0.4);
        background: rgba(238, 40, 91, 0.02);
    }

    .woocommerce-checkout-payment ul.payment_methods li:has(input[type="radio"]:checked) {
        border-color: var(--primary-color, #EE285B);
        background: rgba(238, 40, 91, 0.05);
    }

    .woocommerce-checkout-payment ul.payment_methods li input[type="radio"]:checked + label {
        color: var(--primary-color, #EE285B);
    }

    .woocommerce-checkout-payment ul.payment_methods li label {
        font-weight: 600;
        font-size: 1rem;
        color: #2c3e50;
        cursor: pointer;
        display: flex;
        align-items: center;
        margin: 0;
        gap: 0.5rem;
    }

    .woocommerce-checkout-payment ul.payment_methods li label img {
        max-height: 24px;
        margin-left: 0.75rem;
    }

    .woocommerce-checkout-payment ul.payment_methods li input[type="radio"] {
        width: 24px;
        height: 24px;
        margin-right: 1rem;
        accent-color: var(--primary-color, #EE285B);
        cursor: pointer;
        flex-shrink: 0;
    }

    .payment_box {
        background: #f8f9fa;
        border-radius: 0.5rem;
        padding: 1rem;
        margin: 0.75rem 1rem 1rem 1rem;
        border-left: 3px solid var(--primary-color, #EE285B);
    }

    .payment_box p {
        margin: 0;
        color: #495057;
        line-height: 1.6;
        font-size: 0.9rem;
    }
    */

    /* Botón de realizar pedido - SIN degradado */
    /*
    #place_order {
        background: var(--primary-color, #EE285B);
        border: none;
        color: white;
        font-size: 1.25rem;
        font-weight: 700;
        padding: 1.25rem 2rem;
        border-radius: 0.75rem;
        width: 100%;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    #place_order:hover {
        background: #d91f4f;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(238, 40, 91, 0.3);
    }

    #place_order:active {
        transform: translateY(0);
    }
    */

    /* Métodos de envío en checkout - Radio buttons más visibles con iconos */
    .woocommerce-shipping-methods {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .woocommerce-shipping-methods li {
        background: white;
        border: 3px solid #e9ecef;
        border-radius: 0.75rem;
        padding: 1.25rem 1.5rem;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        position: relative;
    }

    .woocommerce-shipping-methods li:hover {
        border-color: rgba(238, 40, 91, 0.4);
        background: rgba(238, 40, 91, 0.02);
    }

    .woocommerce-shipping-methods li input[type="radio"]:checked + label {
        color: var(--primary-color, #EE285B);
    }

    .woocommerce-shipping-methods li:has(input[type="radio"]:checked) {
        border-color: var(--primary-color, #EE285B);
        background: rgba(238, 40, 91, 0.05);
    }

    .woocommerce-shipping-methods li input[type="radio"] {
        width: 24px;
        height: 24px;
        margin-right: 1rem;
        accent-color: var(--primary-color, #EE285B);
        cursor: pointer;
        flex-shrink: 0;
    }

    .woocommerce-shipping-methods li label {
        font-weight: 600;
        color: #2c3e50;
        margin: 0;
        flex: 1;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .woocommerce-shipping-methods li label::before {
        content: "🚚";
        font-size: 1.5rem;
        margin-right: 0.5rem;
    }

    .woocommerce-shipping-methods li .woocommerce-Price-amount {
        font-weight: 700;
        color: var(--primary-color, #EE285B);
        font-size: 1.15rem;
        margin-left: auto;
    }

    /* Cupón de descuento */
    /*
    .woocommerce-form-coupon-toggle {
        background: rgba(238, 40, 91, 0.05);
        border-radius: 0.75rem;
        padding: 1rem 1.5rem;
        margin-bottom: 2rem;
        border-left: 4px solid var(--primary-color, #EE285B);
    }

    .woocommerce-form-coupon {
        background: white;
        border-radius: 0.75rem;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .woocommerce-form-coupon input[type="text"] {
        border: 2px solid #e9ecef;
        border-radius: 0.75rem 0 0 0.75rem;
        padding: 0.875rem 1rem;
        font-size: 1rem;
    }

    .woocommerce-form-coupon button {
        border-radius: 0 0.75rem 0.75rem 0;
        background: var(--primary-color, #EE285B);
        border: none;
        padding: 0.875rem 1.5rem;
        font-weight: 600;
    }

    .woocommerce-form-coupon button:hover {
        background: #d91f4f;
    }
        */

    /* Ajuste de columnas en checkout: 5 para facturación (41.666667%), 7 para pedido (58.333333%) */
    @media (min-width: 992px) {
        .woocommerce-checkout #customer_details {
            width: 100% !important;
            max-width:100% !important;
            flex: 0 0 41.666667%;
        }

        .woocommerce-checkout #order_review_heading,
        .woocommerce-checkout #order_review {
            width: 100% !important;
            max-width: 100% !important;
            flex: 0 0 58.333333%;
        }
    }

    /* Responsive */
    @media (max-width: 768px) {
        #customer_details,
        .woocommerce-checkout-review-order {
            padding: 1.5rem;
        }

        .woocommerce-billing-fields h3,
        .woocommerce-shipping-fields h3,
        .woocommerce-additional-fields h3,
        #order_review_heading {
            font-size: 1.25rem;
        }

        #place_order {
            font-size: 1.1rem;
            padding: 1rem 1.5rem;
        }
    }

    /* Mensajes de información útil */
    .checkout-info-banner {
        background: rgba(34, 197, 94, 0.1);
        border-left: 4px solid #22c55e;
        border-radius: 0.75rem;
        padding: 1.25rem 1.5rem;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
    }

    .checkout-info-banner i {
        font-size: 2rem;
        color: #22c55e;
        margin-right: 1rem;
    }

    .checkout-info-banner p {
        margin: 0;
        color: #047857;
        font-weight: 600;
        line-height: 1.6;
    }
    </style>
    <?php
}

// ============================================
// AGREGAR INFORMACIÓN ÚTIL EN EL CHECKOUT
// ============================================
add_action('woocommerce_before_checkout_form', 'ht_checkout_info_banner', 5);
function ht_checkout_info_banner() {
    ?>
    <div class="checkout-info-banner">
        <i class="bi bi-shield-check-fill"></i>
        <div>
            <p><strong>Compra 100% segura.</strong> Todos tus datos están protegidos con encriptación SSL.</p>
            <p class="small mb-0 mt-1">
                <i class="bi bi-truck me-2"></i>Envíos a todo el país
                <i class="bi bi-credit-card ms-3 me-2"></i>Hasta 6 cuotas sin interés
                <i class="bi bi-arrow-repeat ms-3 me-2"></i>20 días para cambios
            </p>
        </div>
    </div>
    <?php
}

// ============================================
// PERSONALIZAR LABELS DE MÉTODOS DE PAGO
// ============================================
add_filter('woocommerce_gateway_title', 'ht_custom_payment_gateway_titles', 10, 2);
function ht_custom_payment_gateway_titles($title, $gateway_id) {
    $icons = [
        'bacs' => '🏦',
        'cheque' => '📝',
        'cod' => '💵',
        'paypal' => '',
        'stripe' => '💳'
    ];

    if (isset($icons[$gateway_id])) {
        $title = $icons[$gateway_id] . ' ' . $title;
    }

    return $title;
}

// ============================================
// MEJORAR DESCRIPCIÓN DE MÉTODOS DE ENVÍO
// Labels limpios y minimalistas (iconos solo en checkout)
// ============================================
add_filter('woocommerce_cart_shipping_method_full_label', 'ht_custom_shipping_method_label', 10, 2);
function ht_custom_shipping_method_label($label, $method) {
    // En el carrito usamos labels simples y limpios
    // Los iconos y descripciones están ocultos por CSS para mantener el diseño minimalista
    return $label;
}

// ============================================
// ESTILOS PERSONALIZADOS PARA EL CARRITO
// Diseño minimalista y limpio
// ============================================
add_action('wp_head', 'ht_cart_custom_styles');
function ht_cart_custom_styles() {
    if (!is_cart()) return;
    ?>
    <style>
    /* ============================================
       CARRITO - DISEÑO MINIMALISTA
    ============================================ */

    /* Contenedor principal del carrito */
    .woocommerce-cart {
        background: #f8f9fa;
    }

    .woocommerce-cart .site-content {
        padding-top: 2rem !important;
        padding-bottom: 3rem !important;
    }

    /* Título del carrito */
    /*
    .woocommerce-cart .entry-title {
        font-size: 2.5rem;
        font-weight: 800;
        color: #2c3e50;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
    }

    .woocommerce-cart .entry-title:before {
        content: "🛒";
        margin-right: 1rem;
        font-size: 2.5rem;
    }
        */

    /* Tabla del carrito */
    /*
    .woocommerce-cart-form {
        background: white;
        border-radius: 1rem;
        padding: 2rem;
        box-shadow: 0 2px 15px rgba(0,0,0,0.05);
        margin-bottom: 2rem;
    }

    .woocommerce-cart-form__contents {
        border: none;
    }

    .woocommerce-cart-form__contents thead {
        background: rgba(238, 40, 91, 0.05);
        border-radius: 0.5rem;
    }

    .woocommerce-cart-form__contents thead th {
        border: none;
        padding: 1.25rem 1rem;
        font-weight: 700;
        color: #2c3e50;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
        vertical-align: middle;
    }

    .woocommerce-cart-form__contents tbody tr {
        border-bottom: 1px solid #f1f3f5;
        transition: all 0.3s ease;
    }

    .woocommerce-cart-form__contents tbody tr:hover {
        background: rgba(248, 249, 250, 0.5);
    }

    .woocommerce-cart-form__contents tbody td {
        padding: 1.5rem 1rem;
        vertical-align: middle;
        border: none;
    }
        */

    /* Imagen del producto en carrito */
    /*
    .woocommerce-cart-form__contents .product-thumbnail {
        width: 100px;
    }

    .woocommerce-cart-form__contents .product-thumbnail img {
        border-radius: 0.75rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }

    .woocommerce-cart-form__contents .product-thumbnail img:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
        */

    /* Nombre del producto */
    .woocommerce-cart-form__contents .product-name a {
        font-weight: 600;
        font-size: 1.1rem;
        color: #2c3e50;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .woocommerce-cart-form__contents .product-name a:hover {
        color: var(--primary-color, #EE285B);
    }

    /* Precio */
    .woocommerce-cart-form__contents .product-price,
    .woocommerce-cart-form__contents .product-subtotal {
        font-weight: 700;
        font-size: 1.15rem;
        color: var(--primary-color, #EE285B);
    }

    /* Campo de cantidad */
    /*
    .woocommerce-cart-form__contents .quantity {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .woocommerce-cart-form__contents .quantity input[type="number"] {
        width: 80px;
        height: 45px;
        border: 2px solid #e9ecef;
        border-radius: 0.75rem;
        text-align: center;
        font-weight: 600;
        font-size: 1.1rem;
        transition: all 0.3s ease;
    }

    .woocommerce-cart-form__contents .quantity input[type="number"]:focus {
        border-color: var(--primary-color, #EE285B);
        box-shadow: 0 0 0 3px rgba(238, 40, 91, 0.1);
        outline: none;
    }
    */

    
    /* Totales del carrito - Centrado en un row con Bootstrap */
    .cart-collaterals {
        margin-top: 2rem;
    }

    .cart_totals {
        background: white;
        border-radius: 1rem;
        padding: 2rem;
        box-shadow: 0 2px 15px rgba(0,0,0,0.05);
        width: 100%!important;
    }

    .cart_totals h2 {
        font-size: 1.75rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 3px solid var(--primary-color, #EE285B);
        display: flex;
        align-items: center;
    }

    .cart_totals h2:before {
        content: "💰";
        margin-right: 0.75rem;
        font-size: 2rem;
    }

    .cart_totals table {
        border: none;
    }

    .cart_totals table tr {
        border-bottom: 1px solid #f1f3f5;
    }

    .cart_totals table th,
    .cart_totals table td {
        padding: 1rem 0;
        border: none;
        vertical-align: middle;
    }

    .cart_totals table th {
        font-weight: 600;
        color: #495057;
        font-size: 1rem;
    }

    .cart_totals table td {
        text-align: right;
        font-weight: 700;
        color: #2c3e50;
        font-size: 1.1rem;
    }

    .cart_totals .order-total th,
    .cart_totals .order-total td {
        font-size: 1.5rem;
        color: var(--primary-color, #EE285B);
        padding-top: 1.5rem;
        border-top: 2px solid #dee2e6;
    }

    /* Botón finalizar compra - SIN degradado */
    .wc-proceed-to-checkout {
        margin-top: 1.5rem;
    }

    .wc-proceed-to-checkout .checkout-button {
        background: var(--primary-color, #EE285B);
        border: none;
        color: white;
        font-size: 1.25rem;
        font-weight: 700;
        padding: 1.25rem 2rem;
        border-radius: 0.75rem;
        width: 100%;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 1px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .wc-proceed-to-checkout .checkout-button:before {
        content: "→";
        margin-right: 0.75rem;
        font-size: 1.5rem;
    }

    .wc-proceed-to-checkout .checkout-button:hover {
        background: #d91f4f;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(238, 40, 91, 0.3);
    }

    .wc-proceed-to-checkout .checkout-button:active {
        transform: translateY(0);
    }

    /* Cupón de descuento */
    /*
    .coupon {
        display: flex;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }

    .coupon input[type="text"] {
        flex: 1;
        border: 2px solid #e9ecef;
        border-radius: 0.75rem;
        padding: 0.875rem 1rem;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .coupon input[type="text"]:focus {
        border-color: var(--secondary-color, #534fb5);
        box-shadow: 0 0 0 3px rgba(83, 79, 181, 0.1);
        outline: none;
    }

    .coupon button {
        background: var(--primary-color, #EE285B);
        border: none;
        color: white;
        padding: 0.875rem 1.5rem;
        border-radius: 0.75rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .coupon button:hover {
        background: #d91f4f;
        transform: translateY(-2px);
    }
        */

    /* Banner informativo en el carrito - SIN degradado */
    .cart-info-banner {
        background: rgba(34, 197, 94, 0.1);
        border-left: 4px solid #22c55e;
        border-radius: 0.75rem;
        padding: 1.25rem 1.5rem;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
    }

    .cart-info-banner i {
        font-size: 2rem;
        color: #22c55e;
        margin-right: 1rem;
    }

    .cart-info-banner p {
        margin: 0;
        color: #047857;
        font-weight: 600;
        line-height: 1.6;
    }

    /* Cross-sells (productos relacionados) */
    .cross-sells {
        margin-top: 3rem;
        background: white;
        border-radius: 1rem;
        padding: 2rem;
        box-shadow: 0 2px 15px rgba(0,0,0,0.05);
    }

    .cross-sells h2 {
        font-size: 1.75rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 3px solid var(--secondary-color, #534fb5);
    }

    /* Carrito vacío */
    .woocommerce-cart .cart-empty {
        background: white;
        border-radius: 1rem;
        padding: 3rem 2rem;
        text-align: center;
        box-shadow: 0 2px 15px rgba(0,0,0,0.05);
    }

    .woocommerce-cart .cart-empty:before {
        content: "🛒";
        display: block;
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .woocommerce-cart .return-to-shop {
        margin-top: 2rem;
    }

    .woocommerce-cart .return-to-shop a {
        background: var(--primary-color, #EE285B);
        color: white;
        padding: 1rem 2rem;
        border-radius: 0.75rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s ease;
    }

    .woocommerce-cart .return-to-shop a:hover {
        background: #d91f4f;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(238, 40, 91, 0.3);
    }

    /* Métodos de envío en carrito - Radio buttons más visibles con iconos */
    .woocommerce-cart .woocommerce-shipping-methods {
        list-style: none;
        padding: 0;
        margin: 1rem 0;
    }

    .woocommerce-cart .woocommerce-shipping-methods li {
        background: white;
        border: 3px solid #e9ecef;
        border-radius: 0.75rem;
        padding: 1.25rem 1.5rem;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
        position: relative;
    }

    .woocommerce-cart .woocommerce-shipping-methods li:hover {
        border-color: rgba(238, 40, 91, 0.4);
        background: rgba(238, 40, 91, 0.02);
    }

    .woocommerce-cart .woocommerce-shipping-methods li input[type="radio"]:checked + label {
        color: var(--primary-color, #EE285B);
    }

    .woocommerce-cart .woocommerce-shipping-methods li:has(input[type="radio"]:checked) {
        border-color: var(--primary-color, #EE285B);
        background: rgba(238, 40, 91, 0.05);
    }

    .woocommerce-cart .woocommerce-shipping-methods li input[type="radio"] {
        width: 24px;
        height: 24px;
        margin-right: 1rem;
        accent-color: var(--primary-color, #EE285B);
        cursor: pointer;
        flex-shrink: 0;
    }

    .woocommerce-cart .woocommerce-shipping-methods li label {
        font-weight: 600;
        color: #2c3e50;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        width: 100%;
    }

    .woocommerce-cart .woocommerce-shipping-methods li label::before {
        content: "🚚";
        font-size: 1.5rem;
        margin-right: 0.5rem;
    }

    .woocommerce-cart .woocommerce-shipping-methods li .woocommerce-Price-amount {
        font-weight: 700;
        color: var(--primary-color, #EE285B);
        margin-left: auto;
        font-size: 1.15rem;
    }

    /* Responsive */
    /*
    @media (max-width: 768px) {
        .woocommerce-cart-form,
        .cart_totals {
            padding: 1.5rem;
        }

        .woocommerce-cart .entry-title {
            font-size: 2rem;
        }

        .woocommerce-cart-form__contents .product-thumbnail {
            width: 80px;
        }

        .woocommerce-cart-form__contents .product-name a {
            font-size: 1rem;
        }

        .woocommerce-cart-form__contents thead {
            display: none;
        }

        .woocommerce-cart-form__contents tbody tr {
            display: grid;
            grid-template-columns: 80px 1fr;
            gap: 1rem;
            padding: 1rem 0;
        }

        .woocommerce-cart-form__contents tbody td {
            padding: 0.5rem 0;
        }

        .woocommerce-cart-form__contents .product-thumbnail {
            grid-row: 1 / 4;
        }

        .woocommerce-cart-form__contents .product-remove {
            grid-column: 2;
            text-align: right;
        }

        .coupon {
            flex-direction: column;
        }

        .coupon button {
            width: 100%;
        }
    }
    */
    </style>
    <?php
}


// ============================================
// AGREGAR INFORMACIÓN ÚTIL EN EL CARRITO
// ============================================
add_action('woocommerce_before_cart', 'ht_cart_info_banner', 5);
function ht_cart_info_banner() {
    global $woocommerce;
    $cart_total = $woocommerce->cart->get_cart_contents_total();
    $free_shipping_threshold = 90000;
    $remaining = $free_shipping_threshold - $cart_total;

    if ($remaining > 0) {
        ?>
        <div class="cart-info-banner">
            <i class="bi bi-truck"></i>
            <div>
                <p><strong>¡Te faltan $<?php echo number_format($remaining, 0, ',', '.'); ?> para envío gratis!</strong></p>
                <p class="small mb-0 mt-1">Agregá más productos y ahorrá en el envío a todo el país.</p>
            </div>
        </div>
        <?php
    } else {
        ?>
        <div class="cart-info-banner">
            <i class="bi bi-check-circle-fill"></i>
            <div>
                <p><strong>¡Felicitaciones! Tenés envío gratis a todo el país.</strong></p>
                <p class="small mb-0 mt-1">
                    <i class="bi bi-credit-card me-2"></i>Hasta 6 cuotas sin interés
                    <i class="bi bi-arrow-repeat ms-3 me-2"></i>20 días para cambios
                </p>
            </div>
        </div>
        <?php
    }
}

// ============================================
// REDIRECCIÓN AUTOMÁTICA A PÁGINA DE PEDIDO COMPLETADO
// ============================================
// DESACTIVADO: Esta redirección bloqueaba el flujo nativo de checkout de WooCommerce
// add_action('woocommerce_thankyou', 'ht_redirect_to_custom_order_completed_page');
function ht_redirect_to_custom_order_completed_page($order_id) {
    // Evitar redirecciones múltiples
    if (!$order_id || isset($_GET['redirected'])) {
        return;
    }

    // Obtener el pedido
    $order = wc_get_order($order_id);
    if (!$order) {
        return;
    }

    // Buscar la página que usa la plantilla "Pedido Completado"
    $pages = get_pages(array(
        'meta_key' => '_wp_page_template',
        'meta_value' => 'page-order-completed.php',
        'number' => 1
    ));

    // Si existe la página, redirigir a ella
    if (!empty($pages)) {
        $page_url = get_permalink($pages[0]->ID);
        $redirect_url = add_query_arg(array(
            'order_id' => $order_id,
            'key' => $order->get_order_key(), // Agregar key para validación
            'redirected' => '1'
        ), $page_url);

        wp_redirect($redirect_url);
        exit;
    }
}
