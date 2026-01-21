<?php
/**
 * Template Name: Pedido Completado
 * Description: Página de confirmación de pedido completado
 *
 * @package Bootscore
 * @version 1.0.0
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

get_header();

// Obtener el ID del pedido desde la URL (múltiples formatos)
$order_id = 0;

// Intentar obtener de diferentes parámetros
if (isset($_GET['order_id'])) {
    $order_id = absint($_GET['order_id']);
} elseif (isset($_GET['order'])) {
    $order_id = absint($_GET['order']);
} elseif (isset($_GET['key'])) {
    // Si viene con key de WooCommerce, obtener el pedido por key
    $order_key = wc_clean($_GET['key']);
    $order_id = wc_get_order_id_by_order_key($order_key);
}

// Intentar obtener el último pedido del usuario si no hay ID
if (!$order_id && is_user_logged_in()) {
    $customer_orders = wc_get_orders(array(
        'customer_id' => get_current_user_id(),
        'limit' => 1,
        'orderby' => 'date',
        'order' => 'DESC',
    ));
    if (!empty($customer_orders)) {
        $order_id = $customer_orders[0]->get_id();
    }
}

$order = $order_id ? wc_get_order($order_id) : null;

// Verificar que el pedido existe
// Permitir ver el pedido si: es del usuario actual, o tiene la key correcta, o es admin
$can_view = false;
if ($order) {
    if (current_user_can('manage_woocommerce')) {
        $can_view = true;
    } elseif ($order->get_customer_id() === get_current_user_id()) {
        $can_view = true;
    } elseif (isset($_GET['key']) && $order->get_order_key() === wc_clean($_GET['key'])) {
        $can_view = true;
    }
}

if (!$order || !$can_view) {
    // Si no hay pedido válido, mostrar mensaje
    ?>
    <div class="container py-5">
        <div class="alert alert-warning text-center" role="alert">
            <i class="bi bi-exclamation-triangle fs-2 d-block mb-3"></i>
            <h4>Pedido no encontrado</h4>
            <p>No se pudo encontrar información sobre este pedido.</p>
            <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" class="btn btn-custom mt-3">
                <i class="bi bi-person-circle me-2"></i>Ver Mis Pedidos
            </a>
        </div>
    </div>
    <?php
    get_footer();
    exit;
}
?>

<style>
.order-success-header {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: #fff;
    padding: 4rem 0 2rem;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.order-success-header::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
    background-size: 50px 50px;
    animation: moveBackground 20s linear infinite;
}

@keyframes moveBackground {
    0% { transform: translate(0, 0); }
    100% { transform: translate(50px, 50px); }
}

.success-icon {
    width: 100px;
    height: 100px;
    background: rgba(255,255,255,0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    font-size: 3rem;
    animation: scaleIn 0.5s ease-out;
    position: relative;
    z-index: 1;
}

@keyframes scaleIn {
    0% { transform: scale(0); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

.order-timeline {
    position: relative;
    padding: 2rem 0;
}

.timeline-item {
    display: flex;
    align-items: center;
    margin-bottom: 2rem;
    position: relative;
}

.timeline-icon {
    width: 50px;
    height: 50px;
    background: var(--light-bg);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
    color: var(--secondary-color);
    flex-shrink: 0;
    z-index: 2;
    position: relative;
    transition: all 0.3s;
}

.timeline-item.active .timeline-icon {
    background: var(--primary-color);
    color: #fff;
    transform: scale(1.1);
}

.timeline-item.completed .timeline-icon {
    background: var(--secondary-color);
    color: #fff;
}

.timeline-content {
    flex: 1;
    padding-left: 1.5rem;
}

.timeline-item::before {
    content: '';
    position: absolute;
    left: 24px;
    top: 50px;
    width: 2px;
    height: calc(100% + 2rem);
    background: var(--light-bg);
    z-index: 1;
}

.timeline-item:last-child::before {
    display: none;
}

.order-info-card {
    background: #fff;
    border-radius: 1.2rem;
    padding: 2rem;
    box-shadow: 0 6px 24px rgba(238, 40, 91, 0.08);
    margin-bottom: 1.5rem;
    border: 1px solid var(--light-bg);
    transition: all 0.3s;
}

.order-info-card:hover {
    box-shadow: 0 12px 32px rgba(238, 40, 91, 0.12);
    transform: translateY(-3px);
}

.order-product-item {
    display: flex;
    align-items: center;
    padding: 1rem;
    border-bottom: 1px solid var(--light-bg);
    transition: background 0.2s;
}

.order-product-item:hover {
    background: var(--light-bg);
}

.order-product-item:last-child {
    border-bottom: none;
}

.product-thumbnail {
    width: 70px;
    height: 70px;
    object-fit: contain;
    border-radius: 0.8rem;
    background: #fff;
    border: 1px solid var(--light-bg);
    margin-right: 1rem;
}

.order-summary-row {
    display: flex;
    justify-content: space-between;
    padding: 0.8rem 0;
    border-bottom: 1px solid rgba(0,0,0,0.05);
}

.order-summary-row.total {
    border-top: 2px solid var(--primary-color);
    border-bottom: none;
    font-weight: 700;
    font-size: 1.3rem;
    color: var(--primary-color);
    padding-top: 1rem;
    margin-top: 0.5rem;
}

.status-badge {
    display: inline-block;
    padding: 0.5rem 1.2rem;
    border-radius: 2rem;
    font-weight: 600;
    font-size: 0.9rem;
}

.status-badge.processing {
    background: #fff3cd;
    color: #856404;
}

.status-badge.completed {
    background: #d4edda;
    color: #155724;
}

.status-badge.pending {
    background: #d1ecf1;
    color: #0c5460;
}

@media (max-width: 768px) {
    .order-success-header {
        padding: 2.5rem 0 1.5rem;
    }

    .success-icon {
        width: 80px;
        height: 80px;
        font-size: 2.5rem;
    }

    .order-info-card {
        padding: 1.2rem;
    }

    .product-thumbnail {
        width: 60px;
        height: 60px;
    }
}
</style>

<!-- Header de Éxito -->
<section class="order-success-header">
    <div class="container">
        <div class="success-icon">
            <i class="bi bi-check-circle-fill"></i>
        </div>
        <h1 class="fw-bold mb-3">¡Pedido Completado!</h1>
        <p class="lead mb-2">Gracias por tu compra en Hobby Toys</p>
        <p class="mb-0">Tu pedido #<?php echo $order->get_order_number(); ?> ha sido recibido exitosamente</p>
    </div>
</section>

<section class="py-5" style="background: var(--light-bg);">
    <div class="container">
        <div class="row g-4">

            <!-- Columna Principal -->
            <div class="col-lg-8">

                <!-- Información del Pedido -->
                <div class="order-info-card">
                    <h4 class="fw-bold mb-4" style="color: var(--secondary-color);">
                        <i class="bi bi-info-circle me-2"></i>Información del Pedido
                    </h4>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="d-flex align-items-start gap-2">
                                <i class="bi bi-hash text-muted mt-1"></i>
                                <div>
                                    <small class="text-muted d-block">Número de Pedido</small>
                                    <strong style="color: var(--primary-color);">#<?php echo $order->get_order_number(); ?></strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start gap-2">
                                <i class="bi bi-calendar-check text-muted mt-1"></i>
                                <div>
                                    <small class="text-muted d-block">Fecha del Pedido</small>
                                    <strong><?php echo $order->get_date_created()->date_i18n('d/m/Y H:i'); ?></strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start gap-2">
                                <i class="bi bi-envelope text-muted mt-1"></i>
                                <div>
                                    <small class="text-muted d-block">Email de Confirmación</small>
                                    <strong><?php echo $order->get_billing_email(); ?></strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start gap-2">
                                <i class="bi bi-credit-card text-muted mt-1"></i>
                                <div>
                                    <small class="text-muted d-block">Método de Pago</small>
                                    <strong><?php echo $order->get_payment_method_title(); ?></strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Timeline del Pedido -->
                <div class="order-info-card">
                    <h4 class="fw-bold mb-4" style="color: var(--secondary-color);">
                        <i class="bi bi-clock-history me-2"></i>Estado del Pedido
                    </h4>
                    <div class="order-timeline">
                        <div class="timeline-item completed">
                            <div class="timeline-icon">
                                <i class="bi bi-cart-check"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 class="fw-bold mb-1">Pedido Recibido</h6>
                                <small class="text-muted">Tu pedido ha sido confirmado</small>
                            </div>
                        </div>

                        <div class="timeline-item <?php echo in_array($order->get_status(), ['processing', 'completed']) ? 'active' : ''; ?>">
                            <div class="timeline-icon">
                                <i class="bi bi-box-seam"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 class="fw-bold mb-1">Procesando</h6>
                                <small class="text-muted">Estamos preparando tu pedido</small>
                            </div>
                        </div>

                        <div class="timeline-item <?php echo $order->get_status() === 'completed' ? 'completed' : ''; ?>">
                            <div class="timeline-icon">
                                <i class="bi bi-truck"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 class="fw-bold mb-1">En Camino</h6>
                                <small class="text-muted">Tu pedido está siendo enviado</small>
                            </div>
                        </div>

                        <div class="timeline-item">
                            <div class="timeline-icon">
                                <i class="bi bi-house-check"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 class="fw-bold mb-1">Entregado</h6>
                                <small class="text-muted">Disfruta tu compra</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Productos del Pedido -->
                <div class="order-info-card">
                    <h4 class="fw-bold mb-4" style="color: var(--secondary-color);">
                        <i class="bi bi-bag-check me-2"></i>Productos
                    </h4>
                    <?php
                    foreach ($order->get_items() as $item_id => $item) {
                        $product = $item->get_product();
                        $thumbnail = $product ? wp_get_attachment_image_url($product->get_image_id(), 'thumbnail') : '';
                        ?>
                        <div class="order-product-item">
                            <?php if ($thumbnail): ?>
                                <img src="<?php echo esc_url($thumbnail); ?>" alt="<?php echo esc_attr($item->get_name()); ?>" class="product-thumbnail">
                            <?php else: ?>
                                <div class="product-thumbnail d-flex align-items-center justify-content-center" style="background: var(--light-bg);">
                                    <i class="bi bi-image text-muted"></i>
                                </div>
                            <?php endif; ?>
                            <div class="flex-grow-1">
                                <h6 class="mb-1 fw-bold"><?php echo $item->get_name(); ?></h6>
                                <small class="text-muted">Cantidad: <?php echo $item->get_quantity(); ?></small>
                            </div>
                            <div class="text-end">
                                <strong style="color: var(--primary-color);"><?php echo $order->get_formatted_line_subtotal($item); ?></strong>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>

            </div>

            <!-- Columna Lateral -->
            <div class="col-lg-4">

                <!-- Resumen de Pago -->
                <div class="order-info-card">
                    <h5 class="fw-bold mb-3" style="color: var(--secondary-color);">
                        <i class="bi bi-receipt me-2"></i>Resumen de Pago
                    </h5>

                    <div class="order-summary-row">
                        <span class="text-muted">Subtotal</span>
                        <strong><?php echo $order->get_subtotal_to_display(); ?></strong>
                    </div>

                    <?php if ($order->get_shipping_total() > 0): ?>
                    <div class="order-summary-row">
                        <span class="text-muted">Envío</span>
                        <strong><?php echo wc_price($order->get_shipping_total()); ?></strong>
                    </div>
                    <?php endif; ?>

                    <?php if ($order->get_total_tax() > 0): ?>
                    <div class="order-summary-row">
                        <span class="text-muted">IVA</span>
                        <strong><?php echo wc_price($order->get_total_tax()); ?></strong>
                    </div>
                    <?php endif; ?>

                    <?php if ($order->get_total_discount() > 0): ?>
                    <div class="order-summary-row">
                        <span class="text-muted">Descuento</span>
                        <strong style="color: var(--accent-color);">-<?php echo wc_price($order->get_total_discount()); ?></strong>
                    </div>
                    <?php endif; ?>

                    <div class="order-summary-row total">
                        <span>Total</span>
                        <span><?php echo $order->get_formatted_order_total(); ?></span>
                    </div>
                </div>

                <!-- Dirección de Envío -->
                <div class="order-info-card">
                    <h5 class="fw-bold mb-3" style="color: var(--secondary-color);">
                        <i class="bi bi-geo-alt me-2"></i>Enviar a
                    </h5>
                    <p class="mb-2">
                        <strong><?php echo $order->get_shipping_first_name() . ' ' . $order->get_shipping_last_name(); ?></strong>
                    </p>
                    <p class="text-muted mb-0 small">
                        <?php echo $order->get_shipping_address_1(); ?><br>
                        <?php if ($order->get_shipping_address_2()) echo $order->get_shipping_address_2() . '<br>'; ?>
                        <?php echo $order->get_shipping_city() . ', ' . $order->get_shipping_state(); ?><br>
                        <?php echo $order->get_shipping_postcode(); ?>
                    </p>
                </div>

                <!-- Dirección de Facturación -->
                <div class="order-info-card">
                    <h5 class="fw-bold mb-3" style="color: var(--secondary-color);">
                        <i class="bi bi-file-earmark-text me-2"></i>Facturar a
                    </h5>
                    <p class="mb-2">
                        <strong><?php echo $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(); ?></strong>
                    </p>
                    <p class="text-muted mb-0 small">
                        <?php echo $order->get_billing_address_1(); ?><br>
                        <?php if ($order->get_billing_address_2()) echo $order->get_billing_address_2() . '<br>'; ?>
                        <?php echo $order->get_billing_city() . ', ' . $order->get_billing_state(); ?><br>
                        <?php echo $order->get_billing_postcode(); ?>
                    </p>
                </div>

                <!-- Acciones -->
                <div class="d-grid gap-2">
                    <a href="<?php echo esc_url($order->get_view_order_url()); ?>" class="btn btn-custom btn-lg">
                        <i class="bi bi-eye me-2"></i>Ver Detalles del Pedido
                    </a>
                    <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="btn btn-outline-secondary btn-lg">
                        <i class="bi bi-shop me-2"></i>Seguir Comprando
                    </a>
                </div>

            </div>
        </div>
    </div>
</section>

<!-- Sección de Ayuda -->
<section class="py-4" style="background: #fff;">
    <div class="container">
        <div class="alert alert-info border-0" style="background: linear-gradient(135deg, var(--light-bg), #fff); border-left: 4px solid var(--primary-color) !important;">
            <div class="d-flex align-items-center gap-3">
                <i class="bi bi-info-circle fs-2" style="color: var(--primary-color);"></i>
                <div>
                    <h5 class="fw-bold mb-2" style="color: var(--secondary-color);">¿Necesitas ayuda?</h5>
                    <p class="mb-2">Hemos enviado un email de confirmación a <strong><?php echo $order->get_billing_email(); ?></strong> con todos los detalles de tu pedido.</p>
                    <p class="mb-0 small text-muted">Si tienes alguna pregunta, no dudes en <a href="<?php echo esc_url(get_permalink(get_page_by_path('contacto'))); ?>" style="color: var(--primary-color); font-weight: 600;">contactarnos</a>.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
get_footer();
