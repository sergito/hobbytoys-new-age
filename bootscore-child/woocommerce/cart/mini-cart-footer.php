<?php
/**
 * Mini-cart footer template
 *
 * @WooCommerce 9.4.0
 *
 * @package Bootscore
 * @version 6.2.0
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

?>

<div>

  <div class="woocommerce-mini-cart__total total h5 d-flex justify-content-between">
    <?php
      /**
       * Hook: woocommerce_widget_shopping_cart_total.
       *
       * @hooked woocommerce_widget_shopping_cart_subtotal - 10
       */
      do_action('woocommerce_widget_shopping_cart_total');
    ?>
  </div>

  <?php
  // Mini versión del calculador de envío - Solo mostrar el progreso
  if (class_exists('WooCommerce') && function_exists('ht_calculate_free_shipping_progress') && WC()->cart && !WC()->cart->is_empty()) :
    $subtotal = WC()->cart->get_subtotal();

    // Obtener zona guardada o usar La Plata por defecto
    $zona_guardada = WC()->session->get('ht_shipping_zone', 'laplata');
    $progress_data = ht_calculate_free_shipping_progress($zona_guardada);

    if ($progress_data) :
      $percentage = $progress_data['percentage'];
      $missing = $progress_data['missing'];
      $threshold = $progress_data['threshold'];
      $zone_name = $progress_data['zone_name'];

      // Determinar color según el progreso
      if ($percentage >= 100) {
        $bar_color = '#198754'; // verde
        $bg_color = '#d1e7dd';
      } elseif ($percentage >= 60) {
        $bar_color = '#ffb900'; // amarillo
        $bg_color = '#fff3cd';
      } else {
        $bar_color = '#0dcaf0'; // cyan
        $bg_color = '#cff4fc';
      }
  ?>
    <!-- div class="mini-cart-shipping-progress mt-3 mb-2"-- >
      <!-- div class="d-flex justify-content-between align-items-center mb-2" style="font-size: 0.8rem;">
        <span style="font-weight: 600; color: #666;"-->
          <!--i class="bi bi-truck" style="margin-right: 4px;"></i><?php /*echo esc_html($zone_name); ?>
        </span>
        <?php if ($percentage < 100) : ?>
          <span style="font-weight: 700; color: <?php echo esc_attr($bar_color); ?>; font-size: 0.75rem;">
            Faltan $<?php echo number_format($missing, 0, ',', '.'); ?>
          </span>
        <?php else : ?>
          <span style="font-weight: 700; color: <?php echo esc_attr($bar_color); ?>; font-size: 0.75rem;">
            <i class="bi bi-check-circle-fill"></i> ¡GRATIS!
          </span>
        <?php endif; ?>
      </div>

      <div class="progress" style="height: 8px; border-radius: 50rem; background-color: <?php echo esc_attr($bg_color); ?>;">
        <div class="progress-bar" role="progressbar"
             style="width: <?php echo min($percentage, 100); ?>%; background-color: <?php echo esc_attr($bar_color); ?>; border-radius: 50rem; transition: width 0.3s ease;"
             aria-valuenow="<?php echo esc_attr($percentage);*/ ?>"
             aria-valuemin="0"
             aria-valuemax="100">
        </div>
      </div>

      <div class="text-center mt-2">
        <button type="button" class="btn btn-link btn-sm p-0"
                data-bs-toggle="modal"
                data-bs-target="#shippingCalculatorModal"
                style="font-size: 0.7rem; color: #534fb5; text-decoration: none; font-weight: 600;">
          <i class="bi bi-calculator" style="font-size: 0.65rem;"></i> Cambiar zona
        </button>
      </div>
    </div -->
  <?php
    endif;
  endif;
  ?>

  <?php do_action('woocommerce_widget_shopping_cart_before_buttons'); ?>

    <div class="woocommerce-mini-cart__buttons buttons"><?php do_action('woocommerce_widget_shopping_cart_buttons'); ?></div>

  <?php do_action('woocommerce_widget_shopping_cart_after_buttons'); ?>

</div>
