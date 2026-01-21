<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Bootscore
 * @version 6.1.0
 */

// Exit if accessed directly
defined('ABSPATH') || exit;
?>


<?php do_action( 'bootscore_before_footer' ); ?>

<footer id="footer" class="bootscore-footer">

  <?php if (is_active_sidebar('footer-top')) : ?>
    <div class="<?= apply_filters('bootscore/class/footer/top', 'bg-body-tertiary border-bottom py-5'); ?> bootscore-footer-top">
      <div class="<?= apply_filters('bootscore/class/container', 'container', 'footer-top'); ?>">  
        <?php dynamic_sidebar('footer-top'); ?>
      </div>
    </div>
  <?php endif; ?>
  
  <div class="<?= apply_filters('bootscore/class/footer/columns', 'bg-body-tertiary pt-5 pb-4'); ?> bootscore-footer-columns">
    
    <?php do_action( 'bootscore_footer_columns_before_container' ); ?>
    
    <div class="<?= apply_filters('bootscore/class/container', 'container', 'footer-columns'); ?>">  
      
      <?php do_action( 'bootscore_footer_columns_after_container_open' ); ?>

      <div class="row">

        <div class="<?= apply_filters('bootscore/class/footer/col', 'col-6 col-lg-3', 'footer-1'); ?>">
          <?php if (is_active_sidebar('footer-1')) : ?>
            <?php dynamic_sidebar('footer-1'); ?>
          <?php endif; ?>
        </div>

        <div class="<?= apply_filters('bootscore/class/footer/col', 'col-6 col-lg-3', 'footer-2'); ?>">
          <?php if (is_active_sidebar('footer-2')) : ?>
            <?php dynamic_sidebar('footer-2'); ?>
          <?php endif; ?>
        </div>
        
         <div class="<?= apply_filters('bootscore/class/footer/col', 'col-6 col-lg-3', 'footer-4'); ?>">
          <!-- Newsletter Form -->
          <div class="newsletter-widget">
            <h5 class="widget-title mb-3">Newsletter</h5>
            <p class="small text-white mb-4">Suscribite para recibir ofertas exclusivas</p>
            <form id="newsletter-form" class="newsletter-form" method="post" action="<?php echo esc_url(admin_url('admin-ajax.php')); ?>">
              <div class="input-group">
                <input type="email" 
                       class="form-control" 
                       placeholder="Tu email" 
                       name="newsletter_email" 
                       id="newsletter_email" 
                       required>
                <button class="btn btn-primary" type="submit" style="background-color: #EE285B; border-color: #EE285B;">
                  <i class="bi bi-envelope-fill"></i>
                </button>
              </div>
              <input type="hidden" name="action" value="newsletter_subscribe">
              <?php wp_nonce_field('newsletter_nonce', 'newsletter_nonce_field'); ?>
            </form>
            <div id="newsletter-message" class="mt-2 small"></div>
          </div>
        </div>
        
        <div class="<?= apply_filters('bootscore/class/footer/col', 'col-6 col-lg-3', 'footer-4'); ?>">
          <?php if (is_active_sidebar('footer-4')) : ?>
            <?php dynamic_sidebar('footer-4'); ?>
          <?php endif; ?>
        </div>

      </div>
      
      <?php do_action( 'bootscore_footer_columns_before_footer_menu' ); ?>

      <?php do_action( 'bootscore_footer_columns_before_container_close' ); ?>
      
    </div>
    
    <?php do_action( 'bootscore_footer_columns_after_container' ); ?>
    
  </div>

  <div class="<?= apply_filters('bootscore/class/footer/info', 'bg-secondary text-white border-top py-2 text-center'); ?> bootscore-footer-info">
    <div class="<?= apply_filters('bootscore/class/container', 'container', 'footer-info'); ?>">
      
      <?php do_action( 'bootscore_footer_info_after_container_open' ); ?>

       <!-- Bootstrap 5 Nav Walker Footer Menu -->
      <?php //get_template_part('template-parts/footer/footer-menu'); ?>
      
      <?php if (is_active_sidebar('footer-info')) : ?>
        <?php dynamic_sidebar('footer-info'); ?>
      <?php endif; ?>
      <div class="small bootscore-copyright text-left"><span class="cr-symbol">&copy;</span>&nbsp;<?= date('Y'); ?> <?php bloginfo('name'); ?> - Todos los derechos reservados.</div>
    </div>
  </div>

</footer>

<!-- To top button -->
<a href="#" class="<?= apply_filters('bootscore/class/footer/to_top_button', 'btn btn-primary shadow'); ?> position-fixed zi-1000 top-button" aria-label="<?php esc_attr_e('Return to top', 'bootscore' ); ?>"><?= apply_filters('bootscore/icon/chevron-up', '<i class="fa-solid fa-chevron-up"></i>'); ?><span class="visually-hidden-focusable">To top</span></a-->

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>