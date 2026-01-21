<?php
/**
 * Template Name: Seguimiento de Pedido
 * Description: Página para consultar el estado de un pedido
 *
 * @package Bootscore
 * @version 1.0.0
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

get_header();

$order = null;
$error_message = '';
$tracking_submitted = false;

// Procesar formulario de seguimiento
if (isset($_POST['track_order']) && wp_verify_nonce($_POST['tracking_nonce'], 'track_order_action')) {
    $tracking_submitted = true;
    $order_id = sanitize_text_field($_POST['order_id']);
    $order_email = sanitize_email($_POST['order_email']);

    // Intentar obtener el pedido
    $order = wc_get_order($order_id);

    // Verificar que el pedido existe y el email coincide
    if (!$order) {
        $error_message = 'No se encontró un pedido con ese número.';
    } elseif (strtolower($order->get_billing_email()) !== strtolower($order_email)) {
        $error_message = 'El email no coincide con el pedido.';
        $order = null;
    }
}
?>

<style>
.tracking-header {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: #fff;
    padding: 3.5rem 0 2.5rem;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.tracking-header::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.08) 1px, transparent 1px);
    background-size: 40px 40px;
}

.tracking-form-card {
    background: #fff;
    border-radius: 1.5rem;
    padding: 2.5rem;
    box-shadow: 0 10px 40px rgba(238, 40, 91, 0.12);
    margin-top: -3rem;
    position: relative;
    z-index: 10;
    border: 1px solid rgba(244, 239, 232, 0.5);
}

.form-floating-custom {
    position: relative;
    margin-bottom: 1.5rem;
}

.form-floating-custom input {
    border: 2px solid var(--light-bg);
    border-radius: 1rem;
    padding: 1.2rem 1rem 1.2rem 3rem;
    font-size: 1rem;
    transition: all 0.3s;
    width: 100%;
}

.form-floating-custom input:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.2rem rgba(238, 40, 91, 0.15);
    outline: none;
}

.form-floating-custom .input-icon {
    position: absolute;
    left: 1.2rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--secondary-color);
    font-size: 1.2rem;
    z-index: 5;
}

.tracking-timeline {
    position: relative;
    padding: 2rem 0;
}

.tracking-step {
    display: flex;
    align-items: flex-start;
    margin-bottom: 2.5rem;
    position: relative;
}

.tracking-step::before {
    content: '';
    position: absolute;
    left: 29px;
    top: 60px;
    width: 3px;
    height: calc(100% + 1rem);
    background: var(--light-bg);
    z-index: 0;
}

.tracking-step:last-child::before {
    display: none;
}

.step-icon {
    width: 60px;
    height: 60px;
    background: var(--light-bg);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: #999;
    flex-shrink: 0;
    z-index: 2;
    position: relative;
    transition: all 0.4s;
    border: 3px solid #fff;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
}

.tracking-step.active .step-icon {
    background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
    color: #fff;
    transform: scale(1.15);
    box-shadow: 0 5px 20px rgba(238, 40, 91, 0.3);
    animation: pulse 2s infinite;
}

.tracking-step.completed .step-icon {
    background: var(--secondary-color);
    color: #fff;
}

@keyframes pulse {
    0%, 100% {
        box-shadow: 0 5px 20px rgba(238, 40, 91, 0.3);
    }
    50% {
        box-shadow: 0 5px 30px rgba(238, 40, 91, 0.5);
    }
}

.step-content {
    flex: 1;
    padding-left: 1.5rem;
    padding-top: 0.5rem;
}

.step-title {
    font-weight: 700;
    font-size: 1.1rem;
    color: var(--text-dark);
    margin-bottom: 0.3rem;
}

.tracking-step.active .step-title {
    color: var(--primary-color);
}

.step-description {
    color: #888;
    font-size: 0.95rem;
    margin-bottom: 0.5rem;
}

.step-date {
    font-size: 0.85rem;
    color: #999;
    font-style: italic;
}

.tracking-step.active .step-date {
    color: var(--secondary-color);
    font-weight: 600;
}

.order-status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.7rem 1.5rem;
    border-radius: 2rem;
    font-weight: 600;
    font-size: 1rem;
    margin-bottom: 1rem;
}

.order-status-badge.pending {
    background: linear-gradient(135deg, #d1ecf1, #bee5eb);
    color: #0c5460;
}

.order-status-badge.processing {
    background: linear-gradient(135deg, #fff3cd, #ffeaa7);
    color: #856404;
}

.order-status-badge.on-hold {
    background: linear-gradient(135deg, #f8d7da, #f5c6cb);
    color: #721c24;
}

.order-status-badge.completed {
    background: linear-gradient(135deg, #d4edda, #c3e6cb);
    color: #155724;
}

.order-status-badge.cancelled,
.order-status-badge.refunded,
.order-status-badge.failed {
    background: linear-gradient(135deg, #f8d7da, #f5c6cb);
    color: #721c24;
}

.order-details-card {
    background: #fff;
    border-radius: 1.2rem;
    padding: 2rem;
    box-shadow: 0 6px 24px rgba(238, 40, 91, 0.08);
    margin-bottom: 1.5rem;
    border: 1px solid var(--light-bg);
    transition: all 0.3s;
}

.order-details-card:hover {
    box-shadow: 0 12px 32px rgba(238, 40, 91, 0.12);
    transform: translateY(-3px);
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
}

.info-item {
    display: flex;
    align-items: flex-start;
    gap: 0.8rem;
}

.info-icon {
    width: 40px;
    height: 40px;
    background: var(--light-bg);
    border-radius: 0.8rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    color: var(--secondary-color);
    flex-shrink: 0;
}

.product-tracking-item {
    display: flex;
    align-items: center;
    padding: 1.2rem;
    border-radius: 1rem;
    background: var(--light-bg);
    margin-bottom: 1rem;
    transition: all 0.3s;
}

.product-tracking-item:hover {
    background: #fff;
    box-shadow: 0 4px 16px rgba(238, 40, 91, 0.1);
    transform: translateX(5px);
}

.product-thumb {
    width: 70px;
    height: 70px;
    object-fit: contain;
    border-radius: 0.8rem;
    background: #fff;
    border: 1px solid rgba(0,0,0,0.05);
    margin-right: 1rem;
}

.help-section {
    background: linear-gradient(135deg, var(--light-bg), #fff);
    border-radius: 1.2rem;
    padding: 2rem;
    text-align: center;
    border: 2px dashed var(--primary-color);
}

@media (max-width: 768px) {
    .tracking-header {
        padding: 2.5rem 0 1.5rem;
    }

    .tracking-form-card {
        padding: 1.5rem;
        margin-top: -2rem;
    }

    .step-icon {
        width: 50px;
        height: 50px;
        font-size: 1.2rem;
    }

    .tracking-step::before {
        left: 24px;
    }

    .order-details-card {
        padding: 1.2rem;
    }

    .info-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }

    .product-thumb {
        width: 60px;
        height: 60px;
    }
}
</style>

<!-- Header -->
<section class="tracking-header">
    <div class="container">
        <i class="bi bi-truck fs-1 mb-3 d-block"></i>
        <h1 class="fw-bold mb-3">Seguimiento de Pedido</h1>
        <p class="lead mb-0">Consulta el estado de tu pedido en tiempo real</p>
    </div>
</section>

<section class="py-5">
    <div class="container">

        <?php if (!$order): ?>
            <!-- Formulario de Búsqueda -->
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <div class="tracking-form-card">
                        <div class="text-center mb-4">
                            <div class="d-inline-flex align-items-center justify-content-center mb-3"
                                 style="width: 80px; height: 80px; background: var(--light-bg); border-radius: 50%; font-size: 2rem; color: var(--primary-color);">
                                <i class="bi bi-search"></i>
                            </div>
                            <h3 class="fw-bold mb-2" style="color: var(--secondary-color);">Buscar Pedido</h3>
                            <p class="text-muted mb-0">Ingresa los datos de tu pedido para ver su estado</p>
                        </div>

                        <?php if ($tracking_submitted && $error_message): ?>
                            <div class="alert alert-danger border-0 d-flex align-items-center gap-2" role="alert">
                                <i class="bi bi-exclamation-triangle fs-5"></i>
                                <div><?php echo esc_html($error_message); ?></div>
                            </div>
                        <?php endif; ?>

                        <form method="post" action="">
                            <?php wp_nonce_field('track_order_action', 'tracking_nonce'); ?>

                            <div class="form-floating-custom">
                                <i class="bi bi-hash input-icon"></i>
                                <input type="text"
                                       name="order_id"
                                       id="order_id"
                                       placeholder="Número de Pedido"
                                       required
                                       value="<?php echo isset($_POST['order_id']) ? esc_attr($_POST['order_id']) : ''; ?>">
                            </div>

                            <div class="form-floating-custom">
                                <i class="bi bi-envelope input-icon"></i>
                                <input type="email"
                                       name="order_email"
                                       id="order_email"
                                       placeholder="Email de Facturación"
                                       required
                                       value="<?php echo isset($_POST['order_email']) ? esc_attr($_POST['order_email']) : ''; ?>">
                            </div>

                            <button type="submit" name="track_order" class="btn btn-custom btn-lg w-100">
                                <i class="bi bi-search me-2"></i>Rastrear Pedido
                            </button>
                        </form>

                        <hr class="my-4">

                        <div class="text-center">
                            <small class="text-muted">¿No tienes el número de pedido?</small><br>
                            <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>"
                               class="text-decoration-none fw-bold"
                               style="color: var(--primary-color);">
                                Ver mis pedidos <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Sección de Ayuda -->
                    <div class="help-section mt-4">
                        <i class="bi bi-question-circle fs-2 mb-3 d-block" style="color: var(--primary-color);"></i>
                        <h5 class="fw-bold mb-2" style="color: var(--secondary-color);">¿Necesitas ayuda?</h5>
                        <p class="text-muted mb-3">Encuentra tu número de pedido en el email de confirmación que te enviamos.</p>
                        <a href="<?php echo esc_url(get_permalink(get_page_by_path('contacto'))); ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-headset me-2"></i>Contactar Soporte
                        </a>
                    </div>
                </div>
            </div>

        <?php else: ?>
            <!-- Resultados del Seguimiento -->
            <div class="row">
                <div class="col-lg-8">

                    <!-- Estado Actual -->
                    <div class="order-details-card">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
                            <div>
                                <h4 class="fw-bold mb-2" style="color: var(--secondary-color);">
                                    Pedido #<?php echo $order->get_order_number(); ?>
                                </h4>
                                <?php
                                $status = $order->get_status();
                                $status_name = wc_get_order_status_name($status);
                                $status_icon = [
                                    'pending' => 'clock',
                                    'processing' => 'hourglass-split',
                                    'on-hold' => 'pause-circle',
                                    'completed' => 'check-circle',
                                    'cancelled' => 'x-circle',
                                    'refunded' => 'arrow-counterclockwise',
                                    'failed' => 'exclamation-circle'
                                ];
                                $icon = isset($status_icon[$status]) ? $status_icon[$status] : 'info-circle';
                                ?>
                                <span class="order-status-badge <?php echo esc_attr($status); ?>">
                                    <i class="bi bi-<?php echo $icon; ?>"></i>
                                    <?php echo esc_html($status_name); ?>
                                </span>
                            </div>
                            <button onclick="window.print()" class="btn btn-outline-secondary">
                                <i class="bi bi-printer me-2"></i>Imprimir
                            </button>
                        </div>

                        <!-- Información General -->
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="bi bi-calendar-check"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Fecha</small>
                                    <strong><?php echo $order->get_date_created()->date_i18n('d/m/Y'); ?></strong>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="bi bi-credit-card"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Pago</small>
                                    <strong><?php echo $order->get_payment_method_title(); ?></strong>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="bi bi-cash-coin"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Total</small>
                                    <strong style="color: var(--primary-color);"><?php echo $order->get_formatted_order_total(); ?></strong>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="bi bi-box-seam"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Productos</small>
                                    <strong><?php echo $order->get_item_count(); ?> artículo<?php echo $order->get_item_count() > 1 ? 's' : ''; ?></strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Timeline de Estado -->
                    <div class="order-details-card">
                        <h5 class="fw-bold mb-4" style="color: var(--secondary-color);">
                            <i class="bi bi-clock-history me-2"></i>Historial del Pedido
                        </h5>

                        <div class="tracking-timeline">
                            <?php
                            $statuses = [
                                'pending' => ['title' => 'Pedido Recibido', 'desc' => 'Tu pedido ha sido confirmado', 'icon' => 'cart-check'],
                                'processing' => ['title' => 'Procesando', 'desc' => 'Estamos preparando tu pedido', 'icon' => 'box-seam'],
                                'completed' => ['title' => 'Completado', 'desc' => 'Tu pedido ha sido enviado/entregado', 'icon' => 'check-circle']
                            ];

                            $current_status = $order->get_status();
                            $status_order = ['pending', 'processing', 'completed'];
                            $current_index = array_search($current_status, $status_order);
                            if ($current_index === false) $current_index = 0;

                            foreach ($statuses as $status_key => $status_info) {
                                $step_index = array_search($status_key, $status_order);
                                $step_class = '';

                                if ($step_index < $current_index) {
                                    $step_class = 'completed';
                                } elseif ($step_index === $current_index) {
                                    $step_class = 'active';
                                }
                                ?>
                                <div class="tracking-step <?php echo $step_class; ?>">
                                    <div class="step-icon">
                                        <i class="bi bi-<?php echo $status_info['icon']; ?>"></i>
                                    </div>
                                    <div class="step-content">
                                        <div class="step-title"><?php echo $status_info['title']; ?></div>
                                        <div class="step-description"><?php echo $status_info['desc']; ?></div>
                                        <?php if ($step_class === 'active' || $step_class === 'completed'): ?>
                                            <div class="step-date">
                                                <?php
                                                if ($step_class === 'active') {
                                                    echo 'Actualizado: ' . $order->get_date_modified()->date_i18n('d/m/Y H:i');
                                                }
                                                ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>

                    <!-- Productos del Pedido -->
                    <div class="order-details-card">
                        <h5 class="fw-bold mb-4" style="color: var(--secondary-color);">
                            <i class="bi bi-bag-check me-2"></i>Productos
                        </h5>

                        <?php
                        foreach ($order->get_items() as $item_id => $item) {
                            $product = $item->get_product();
                            $thumbnail = $product ? wp_get_attachment_image_url($product->get_image_id(), 'thumbnail') : '';
                            ?>
                            <div class="product-tracking-item">
                                <?php if ($thumbnail): ?>
                                    <img src="<?php echo esc_url($thumbnail); ?>" alt="<?php echo esc_attr($item->get_name()); ?>" class="product-thumb">
                                <?php else: ?>
                                    <div class="product-thumb d-flex align-items-center justify-content-center">
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

                <div class="col-lg-4">

                    <!-- Dirección de Envío -->
                    <div class="order-details-card">
                        <h5 class="fw-bold mb-3" style="color: var(--secondary-color);">
                            <i class="bi bi-geo-alt me-2"></i>Dirección de Envío
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

                    <!-- Contacto -->
                    <div class="order-details-card">
                        <h5 class="fw-bold mb-3" style="color: var(--secondary-color);">
                            <i class="bi bi-person-circle me-2"></i>Contacto
                        </h5>
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="bi bi-envelope" style="color: var(--primary-color);"></i>
                            <span class="text-muted small"><?php echo $order->get_billing_email(); ?></span>
                        </div>
                        <?php if ($order->get_billing_phone()): ?>
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-telephone" style="color: var(--primary-color);"></i>
                            <span class="text-muted small"><?php echo $order->get_billing_phone(); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Acciones -->
                    <div class="d-grid gap-2">
                        <a href="<?php echo esc_url($order->get_view_order_url()); ?>" class="btn btn-custom btn-lg">
                            <i class="bi bi-eye me-2"></i>Ver Detalles Completos
                        </a>
                        <a href="<?php echo esc_url(get_permalink()); ?>" class="btn btn-outline-secondary btn-lg">
                            <i class="bi bi-search me-2"></i>Rastrear Otro Pedido
                        </a>
                    </div>

                    <!-- Ayuda -->
                    <div class="order-details-card mt-3" style="background: var(--light-bg); border-left: 4px solid var(--primary-color);">
                        <h6 class="fw-bold mb-2" style="color: var(--secondary-color);">
                            <i class="bi bi-info-circle me-2"></i>¿Tienes preguntas?
                        </h6>
                        <p class="small text-muted mb-3">Estamos aquí para ayudarte con cualquier duda sobre tu pedido.</p>
                        <a href="<?php echo esc_url(get_permalink(get_page_by_path('contacto'))); ?>" class="btn btn-sm btn-outline-secondary w-100">
                            <i class="bi bi-headset me-2"></i>Contactar Soporte
                        </a>
                    </div>

                </div>
            </div>
        <?php endif; ?>

    </div>
</section>

<?php if (!$order): ?>
<!-- Información Adicional -->
<section class="py-4" style="background: var(--light-bg);">
    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-md-4">
                <div class="d-flex flex-column align-items-center">
                    <div class="mb-3" style="width: 60px; height: 60px; background: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; color: var(--primary-color);">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <h6 class="fw-bold mb-2" style="color: var(--secondary-color);">Seguimiento Seguro</h6>
                    <p class="text-muted small mb-0">Tus datos están protegidos y encriptados</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="d-flex flex-column align-items-center">
                    <div class="mb-3" style="width: 60px; height: 60px; background: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; color: var(--primary-color);">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <h6 class="fw-bold mb-2" style="color: var(--secondary-color);">Actualizaciones en Tiempo Real</h6>
                    <p class="text-muted small mb-0">Información actualizada automáticamente</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="d-flex flex-column align-items-center">
                    <div class="mb-3" style="width: 60px; height: 60px; background: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; color: var(--primary-color);">
                        <i class="bi bi-headset"></i>
                    </div>
                    <h6 class="fw-bold mb-2" style="color: var(--secondary-color);">Soporte 24/7</h6>
                    <p class="text-muted small mb-0">Estamos aquí para ayudarte siempre que lo necesites</p>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<?php
get_footer();
