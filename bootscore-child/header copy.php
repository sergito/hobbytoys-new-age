<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Bootscore
 * @version 6.2.0
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="profile" href="https://gmpg.org/xfn/11">

 <link rel="preconnect" href="https://fonts.googleapis.com">
 <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
 <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Nunito:ital,wght@0,200..1000;1,200..1000&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php wp_body_open(); ?>

<div id="page" class="site">
  
  <!-- Skip Links -->
  <a class="skip-link visually-hidden-focusable" href="#primary"><?php esc_html_e( 'Skip to content', 'bootscore' ); ?></a>
  <a class="skip-link visually-hidden-focusable" href="#footer"><?php esc_html_e( 'Skip to footer', 'bootscore' ); ?></a>

  <!-- Top Bar Widget -->
  <?php if (is_active_sidebar('top-bar')) : ?>
    <?php dynamic_sidebar('top-bar'); ?>
  <?php endif; ?>
  
  <?php do_action( 'bootscore_before_masthead' ); ?>

  <header id="masthead" class="<?= apply_filters('bootscore/class/header', 'bg-white'); ?> site-header">

    <?php do_action( 'bootscore_after_masthead_open' ); ?>
    
    <nav id="nav-main" class="navbar <?= apply_filters('bootscore/class/header/navbar/breakpoint', 'navbar-expand-lg'); ?>">

      <div class="<?= apply_filters('bootscore/class/container', 'container', 'header'); ?>">
        
        <?php do_action( 'bootscore_before_navbar_brand' ); ?>
        
        <!-- Navbar Brand -->
        <a class="<?= apply_filters('bootscore/class/header/navbar-brand', 'navbar-brand'); ?>" href="<?= esc_url(home_url()); ?>">
           <img width="140" src="<?= esc_url(apply_filters('bootscore/logo', get_stylesheet_directory_uri() . '/assets/images/logo-svg.svg', 'default')); ?>" alt="<?php bloginfo('name'); ?> Logo" class="logo">
        </a>  

        <div class="product_search_form radius_input search_form_btn">
            <?php echo do_shortcode('[fibosearch]'); ?>
        </div>
        
        <?php do_action( 'bootscore_after_navbar_brand' ); ?>

        <div class="header-actions <?= apply_filters('bootscore/class/header-actions', 'd-flex align-items-center'); ?>">

          <!-- Top Nav Widget -->
          <?php if (is_active_sidebar('top-nav')) : ?>
            <?php dynamic_sidebar('top-nav'); ?>
          <?php endif; ?>

          <?php
          if (class_exists('WooCommerce')) :
            get_template_part('template-parts/header/actions', 'woocommerce');
          else :
            get_template_part('template-parts/header/actions');
          endif;
          ?>

        </div><!-- .header-actions -->

      </div><!-- .container -->

    </nav><!-- .navbar -->


    <div class="bottom_header dark_skin main_menu_uppercase">
    	<div class="container">
            <div class="row align-items-center"> 
            	<div class="col-lg-3 col-md-4 col-sm-6 col-3">
                	<div class="categories_wrap">
                        <!-- Categorias mobile toggler -->
                        <button class="me-1 me-md-2 btn btn-primary d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas-navbar-categs" aria-controls="offcanvas-navbar-categs">
                            <i class="linearicons-menu"></i>
                        </button>

                        <button type="button" data-bs-toggle="collapse" data-bs-target="#navCatContent" aria-expanded="false" class="categories_btn categories_menu d-none d-md-block">
                            <span>Categorias </span><i class="linearicons-menu"></i>
                        </button>

                        <div id="navCatContent" class="navbar nav collapse">
                            <?php
                                wp_nav_menu ( array (
                                    'theme_location'    => 'categorias-desplegable',
                                    'depth'             => 1,
                                    'container_class'   => '',
                                    'menu_class'        => '',
                                    'fallback_cb'       => '__return_false',
                                    'walker'            => new Categoria_Imagen_Walker()
                                ));
                            ?>
                        </div>

                         <!-- Offcanvas Navbar -->
                        <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvas-navbar-categs">
                            <div class="offcanvas-header <?= apply_filters('bootscore/class/offcanvas/header', '', 'menu'); ?>">
                                <img width="75" src="<?= esc_url(apply_filters('bootscore/logo', get_stylesheet_directory_uri() . '/assets/images/logo-svg.svg', 'default')); ?>" alt="<?php bloginfo('name'); ?> Logo" class="logo">
                                <span class="h5 offcanvas-title">Menu de categorias</span>
                                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                            </div>

                            <div class="offcanvas-body align-items-center justify-content-between">
                                <div id="navCatContentMobile" class="navbar nav collapse show">
                                    <?php
                                        wp_nav_menu ( array (
                                            'theme_location'    => 'categorias-desplegable',
                                            'depth'             => 1,
                                            'container_class'   => '',
                                            'menu_class'        => '',
                                            'fallback_cb'       => '__return_false',
                                            'walker'            => new Categoria_Imagen_Walker()
                                        ));
                                    ?>
                                </div>
                            </div>
                        </div><!-- .offcanvas -->

                    </div>
                </div><!-- .col-3 -->

                <div class="col-lg-9 col-md-8 col-sm-6 col-9">
                    <nav class="navbar navbar-expand-lg bottom-nav">
                        <!-- Offcanvas Navbar -->
                        <div class="offcanvas offcanvas-<?= apply_filters('bootscore/class/header/offcanvas/direction', 'end', 'menu'); ?>" tabindex="-1" id="offcanvas-navbar">
                            <div class="offcanvas-header <?= apply_filters('bootscore/class/offcanvas/header', '', 'menu'); ?>">
                                <img width="75" src="<?= esc_url(apply_filters('bootscore/logo', get_stylesheet_directory_uri() . '/assets/images/logo-svg.svg', 'default')); ?>" alt="<?php bloginfo('name'); ?> Logo" class="logo">
                                <span class="h5 offcanvas-title"><?= apply_filters('bootscore/offcanvas/navbar/title', __('Menu', 'bootscore')); ?></span>
                                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                            </div>

                            <div class="offcanvas-body <?= apply_filters('bootscore/class/offcanvas/body', '', 'menu'); ?> align-items-center justify-content-between">

                                <!-- Bootstrap 5 Nav Walker Main Menu -->
                                <?php get_template_part('template-parts/header/main-menu'); ?>                        

                            </div>
                        </div><!-- .offcanvas -->

                        <div class="header-actions <?= apply_filters('bootscore/class/header-actions', 'd-flex align-items-center'); ?>">
                            <div class="contact_phone contact_support">
                                <i class="linearicons-phone-wave"></i>
                                <span>123-456-7689</span>
                            </div>

                            <div class="pr_search_icon">
                                <a href="javascript:;" class="nav-link pr_search_trigger d-lg-none"><i class="linearicons-magnifier"></i></a>
                            </div>  

                            <!-- Navbar Toggler -->
                            <button class="<?= apply_filters('bootscore/class/header/button', 'btn btn-outline-secondary', 'nav-toggler'); ?> <?= apply_filters('bootscore/class/header/navbar/toggler/breakpoint', 'd-lg-none'); ?> <?= apply_filters('bootscore/class/header/action/spacer', 'ms-0', 'nav-toggler'); ?> nav-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas-navbar" aria-controls="offcanvas-navbar" aria-label="<?php esc_attr_e( 'Toggle main menu', 'bootscore' ); ?>">
                                <?= apply_filters('bootscore/icon/menu', '<i class="fa-solid fa-bars"></i>'); ?> <span class="visually-hidden-focusable">Menu</span>
                            </button>
                            
                        </div><!-- .header-actions -->
                    </nav>

                </div><!-- .col-9 -->
            </div>
        </div><!-- .container-->
    </div>

    <?php
    if (class_exists('WooCommerce')) :
      get_template_part('template-parts/header/collapse-search', 'woocommerce');
    else :
      get_template_part('template-parts/header/collapse-search');
    endif;
    ?>

    <!-- Offcanvas User and Cart -->
    <?php
    if (class_exists('WooCommerce')) :
      get_template_part('template-parts/header/offcanvas', 'woocommerce');
    endif;
    ?>

    <?php do_action( 'bootscore_before_masthead_close' ); ?>


    <div class="header-sticky">
      <div class="header-container">
        <div class="main-header">
          
          <nav id="nav-main-sticky" class="navbar <?= apply_filters('bootscore/class/header/navbar/breakpoint', 'navbar-expand-lg'); ?>">

             <div class="container m-0 mx-auto">
             
                <div class="row m-0 w-100"> 
                    <div class="col-lg-3 col-md-4 col-sm-6 col-9">
                        <div class="categories_wrap">
                            <!-- Navbar Brand -->
                            <a class="<?= apply_filters('bootscore/class/header/navbar-brand', 'navbar-brand'); ?>" href="<?= esc_url(home_url()); ?>">
                              <img width="100" src="<?= esc_url(apply_filters('bootscore/logo', get_stylesheet_directory_uri() . '/assets/images/logo-svg.svg', 'default')); ?>" alt="<?php bloginfo('name'); ?> Logo" class="logo">
                            </a>  

                            <!-- Categorias mobile toggler -->
                            <button class="me-1 me-md-2 btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas-navbar-categs" aria-controls="offcanvas-navbar-categs">
                                <?= apply_filters('bootscore/icon/menu', '<i class="fa-solid fa-bars"></i>'); ?> <span class="visually-hidden-focusable">Categorias</span>
                            </button>
                         </div>
                    </div><!-- .col-3 -->

                    <div class="col-lg-9 col-md-8 col-sm-6 col-3 justify-content-end d-flex">
                        <nav class="navbar navbar-expand-lg bottom-nav p-0">
                            <!-- Offcanvas Navbar -->
                            <div class="offcanvas offcanvas-<?= apply_filters('bootscore/class/header/offcanvas/direction', 'end', 'menu'); ?>" tabindex="-1" id="offcanvas-navbar">
                                <div class="offcanvas-header <?= apply_filters('bootscore/class/offcanvas/header', '', 'menu'); ?>">
                                    <img width="75" src="<?= esc_url(apply_filters('bootscore/logo', get_stylesheet_directory_uri() . '/assets/images/logo-svg.svg', 'default')); ?>" alt="<?php bloginfo('name'); ?> Logo" class="logo">
                                    <span class="h5 offcanvas-title"><?= apply_filters('bootscore/offcanvas/navbar/title', __('Menu', 'bootscore')); ?></span>
                                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                </div>

                                <div class="offcanvas-body <?= apply_filters('bootscore/class/offcanvas/body', '', 'menu'); ?> align-items-center justify-content-between">

                                    <!-- Bootstrap 5 Nav Walker Main Menu -->
                                    <?php get_template_part('template-parts/header/main-menu'); ?>                        

                                </div>
                            </div><!-- .offcanvas -->

                            <div class="header-actions <?= apply_filters('bootscore/class/header-actions', 'd-flex align-items-center'); ?>">
              
                                <!-- Navbar Toggler -->
                                <button class="<?= apply_filters('bootscore/class/header/button', 'btn btn-outline-secondary', 'nav-toggler'); ?> <?= apply_filters('bootscore/class/header/navbar/toggler/breakpoint', 'd-lg-none'); ?> <?= apply_filters('bootscore/class/header/action/spacer', 'ms-3', 'nav-toggler'); ?> nav-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas-navbar" aria-controls="offcanvas-navbar" aria-label="<?php esc_attr_e( 'Toggle main menu', 'bootscore' ); ?>">
                                    <?= apply_filters('bootscore/icon/menu', '<i class="fa-solid fa-bars"></i>'); ?> <span class="visually-hidden-focusable">Menu</span>
                                </button>
                                
                            </div><!-- .header-actions -->
                        </nav>

                    </div><!-- .col-9 -->
                </div>
             </div><!-- .container-->

          </nav><!-- .navbar -->

        </div>
      </div>
    </div><!-- .header-sticky -->
    
  </header><!-- #masthead -->
  
  <?php do_action( 'bootscore_after_masthead' ); ?>