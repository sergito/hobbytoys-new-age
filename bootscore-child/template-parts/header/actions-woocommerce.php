<?php
/**
 * Template part for displaying the header-actions if WooCommerce if installed
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Bootscore
 * @version 6.1.0
*/

// Exit if accessed directly
defined('ABSPATH') || exit;
?>

<a class="<?= apply_filters('bootscore/class/header/button', 'btn btn-outline-primary wl-icon', ''); ?> ms-1 ms-md-1" href="<?php echo esc_url( get_page_link(46157) ); ?>">
    <?= apply_filters('bootscore/icon/search', '<i class="bi bi-heart"></i>'); ?>
</a>

<!-- Search toggler -->
<?php if (is_active_sidebar('top-nav-search')) : ?>
  <button class="<?= apply_filters('bootscore/class/header/button', 'btn btn-outline-primary', 'search-toggler'); ?> <?= apply_filters('bootscore/class/header/action/spacer', 'ms-1 ms-md-1', 'search-toggler'); ?> search-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-search" aria-expanded="false" aria-controls="collapse-search" aria-label="<?php esc_attr_e( 'Search toggler', 'bootscore' ); ?>">
    <?= apply_filters('bootscore/icon/search', '<i class="linearicons-magnifier"></i>'); ?> <span class="visually-hidden-focusable">Search</span>
  </button>
<?php endif; ?>

<!-- User toggler -->
<?php
if (apply_filters('bootscore/enable_account', true)) {
  if ( is_account_page() || is_checkout() ) {
  // Do nothing
  } else { ?>
    <button class="<?= apply_filters('bootscore/class/header/button', 'btn btn-outline-primary', 'account-toggler'); ?> <?= apply_filters('bootscore/class/header/action/spacer', 'ms-1 ms-md-1', 'account-toggler'); ?> account-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas-user" aria-controls="offcanvas-user" aria-label="<?php esc_attr_e( 'Account toggler', 'bootscore' ); ?>">
      <?= apply_filters('bootscore/icon/user', '<i class="bi bi-person-circle"></i>'); ?> <span class="visually-hidden-focusable">Account</span>
    </button>
  <?php } 
 }
?>



<!-- Mini cart toggler -->
<?php
if ( is_cart() ) {
 // Do nothing
} elseif ( is_checkout() ) { ?>
  <!-- Add a back-to-cart button -->
  <?php
  // Check the filter and AJAX cart option
  $skip_cart_filter = apply_filters('bootscore/skip_cart', true);
  $ajax_cart_en = 'yes' === get_option('woocommerce_enable_ajax_add_to_cart');

 if ($skip_cart_filter && $ajax_cart_en) {
    $back_to_cart_url = get_permalink(wc_get_page_id('shop'));
  } else {
    $back_to_cart_url = wc_get_cart_url();
  }

  ?>
  <a class="<?= apply_filters('bootscore/class/header/button', 'btn btn-outline-primary', 'cart-toggler'); ?> <?= apply_filters('bootscore/class/header/action/spacer', 'ms-1 ms-md-2', 'cart-toggler'); ?> back-to-cart" href="<?= esc_url($back_to_cart_url); ?>">
    <?= apply_filters('bootscore/icon/arrow-left', '<i class="fa-solid fa-arrow-left d-none d-md-inline me-2"></i>'); ?><?= apply_filters('bootscore/icon/cart', '<i class="bi bi-cart3"></i>'); ?><span class="visually-hidden-focusable">Return to <?= ($back_to_cart_url == wc_get_cart_url()) ? 'Cart' : 'Shop'; ?></span>
  </a>
<?php } else { ?>
  <!-- Add mini-cart toggler -->
  <button class="<?= apply_filters('bootscore/class/header/button', 'btn btn-outline-primary', 'cart-toggler'); ?> <?= apply_filters('bootscore/class/header/action/spacer', 'ms-1 ms-md-1', 'cart-toggler'); ?> position-relative cart-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas-cart" aria-controls="offcanvas-cart" aria-label="<?php esc_attr_e( 'Cart toggler', 'bootscore' ); ?>">
    <div class="d-inline-flex align-items-center">
      <?= apply_filters('bootscore/icon/cart', '<i class="bi bi-cart3"></i>'); ?> <span class="visually-hidden-focusable">Cart</span>
      <?php if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
        $count = WC()->cart->cart_contents_count;
        ?>
        <span class="cart-content cart_count">
          <?php if ($count > 0) { ?>
            <?= esc_html($count); ?>
          <?php } ?>
        </span>
      <?php } ?>
    </div>
  </button>
<?php } ?>
