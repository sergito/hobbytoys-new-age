<?php
/**
 * Barra Superior de Envíos
 
*/

if (!defined('ABSPATH')) {
    exit;
}

$progress = ht_get_free_shipping_progress();
?>

<div class="topbar-shipping-banner" id="topbarShippingBanner">
    <div class="container-fluid">
        <div class="row align-items-center g-0">
            
            <!-- Mensaje rotativo izquierda (40%) -->
            <div class="col-lg-4 d-none d-lg-block">
                <div class="topbar-ticker">
                    <div class="ticker-content">
                        <span class="ticker-item">
                            <i class="bi bi-truck"></i> Envío gratis en La Plata desde $50.000
                        </span>
                        <span class="ticker-item">
                            <i class="bi bi-gift"></i> 10% reintegro con Banco Provincia
                        </span>
                        <span class="ticker-item">
                            <i class="bi bi-credit-card"></i> Hasta 6 cuotas sin interés
                        </span>
                        <span class="ticker-item">
                            <i class="bi bi-shop"></i> Retiro gratis en tienda
                        </span>
                    </div>
                </div>
            </div>

            <!-- Progress Bar Centro (20%) -->
            <div class="col-12 col-lg-4">
                <div class="topbar-progress-container">
                    <div class="shipping-progress-data" 
                         data-cart-total="<?php echo $progress['cart_total']; ?>"
                         data-remaining="<?php echo $progress['remaining']; ?>"
                         data-percentage="<?php echo round($progress['percentage']); ?>"
                         data-is-free="<?php echo $progress['is_free'] ? 'true' : 'false'; ?>">
                    </div>
                    
                    <?php if ($progress['is_free']): ?>
                        <div class="shipping-free-message">
                            <i class="bi bi-check-circle-fill"></i>
                            <span>¡Envío gratis desbloqueado!</span>
                        </div>
                    <?php else: ?>
                        <div class="shipping-progress-wrapper">
                            <div class="shipping-progress-text">
                                <small>Te faltan <strong>$<?php echo number_format($progress['remaining'], 0, ',', '.'); ?></strong> para envío gratis</small>
                            </div>
                            <div class="progress shipping-progress-bar">
                                <div class="progress-bar" 
                                     role="progressbar" 
                                     style="width: <?php echo round($progress['percentage']); ?>%"
                                     aria-valuenow="<?php echo round($progress['percentage']); ?>" 
                                     aria-valuemin="0" 
                                     aria-valuemax="100">
                                    <?php echo round($progress['percentage']); ?>%
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Mensaje rotativo derecha (40%) -->
            <div class="col-lg-4 d-none d-lg-block">
                <div class="topbar-ticker topbar-ticker-reverse">
                    <div class="ticker-content">
                        <span class="ticker-item">
                            <i class="bi bi-map"></i> Envío a todo el país desde $90.000
                        </span>
                        <span class="ticker-item">
                            <i class="bi bi-whatsapp"></i> Atención personalizada por WhatsApp
                        </span>
                        <span class="ticker-item">
                            <i class="bi bi-shield-check"></i> Compra 100% segura
                        </span>
                        <span class="ticker-item">
                            <i class="bi bi-star-fill"></i> +30 años de experiencia
                        </span>
                    </div>
                </div>
            </div>

        </div>
    </div>
    
    <!-- Botón cerrar (opcional) -->
    <button class="topbar-close" id="topbarClose" title="Cerrar">
        <i class="bi bi-x"></i>
    </button>
</div>