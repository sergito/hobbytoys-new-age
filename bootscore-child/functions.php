<?php
/**
 * @package Bootscore Child
 *
 * @version 6.0.0
 */

// Exit if accessed directly
defined('ABSPATH') || exit;
define('THEME_DIR', get_template_directory());
define('THEME_URL', get_template_directory_uri());
/**
 * void debug ( mixed Var [, bool Exit] )
*/
if (!function_exists("debug")) {
  function debug($var, $exit = false) {
      echo "\n<pre>";

      if (is_array($var) || is_object($var)) {
          echo htmlentities(print_r($var, true));
      }
      elseif (is_string($var)) {
          echo "string(" . strlen($var) . ") \"" . htmlentities($var) . "\"\n";
      }
      else {
          var_dump($var);
      }
      echo "</pre>";

      if ($exit) {
          exit;
      }
  }
}


// REMOVE WP EMOJI
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );


/**
 * Enable cart page
 */
add_filter('bootscore/skip_cart', '__return_false');

/*************************************************
## Theme Setup
*************************************************/ 

if ( ! isset( $content_width ) ) $content_width = 960;

function shopwise_theme_setup() {
	
	add_theme_support( 'title-tag' );
	remove_theme_support( 'automatic-feed-links' );
	add_theme_support( 'post-thumbnails' );
  add_theme_support( 'custom-background' );
	//add_theme_support( 'post-formats', array('gallery', 'audio', 'video'));
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
	add_theme_support( 'woocommerce', array('gallery_thumbnail_image_width' => 99,'thumbnail_image_width' => 90,) );
	
	remove_theme_support( 'widgets-block-editor' );

}
add_action( 'after_setup_theme', 'shopwise_theme_setup' );


/*************************************************
## Styles and Scripts
*************************************************/ 
define('SHOPWISE_INDEX_ASSETS', get_stylesheet_directory_uri()  . '/assets/');

add_action('wp_enqueue_scripts', 'bootscore_child_enqueue_styles');
function bootscore_child_enqueue_styles() {

  // styles
  wp_enqueue_style( 'animete-css','https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css', false, '1.0');
  wp_enqueue_style(
    'bootstrap-icons',
    'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css',
    array(),
    '1.11.3'
  );

  // Verificar que WooCommerce est activo
  if (function_exists('is_woocommerce')) {
        // Si no estamos en una página WooCommerce
        if ( !is_woocommerce() ) {
            wp_enqueue_style(
                'internas-estilos',
                SHOPWISE_INDEX_ASSETS . '/css/internas/generales.css',
                array('animete-css','bootstrap-icons'),
                '1.0'
            );
            if( is_home() || is_front_page() ) {
                wp_enqueue_style(
                    'internas-home',
                    SHOPWISE_INDEX_ASSETS . '/css/internas/home.css',
                    array('animete-css','bootstrap-icons','internas-estilos'),
                    '1.0'
                );
            }
        }
  }

  // En la función bootscore_child_enqueue_styles(), agregar:
  if (is_product()) {
     wp_enqueue_script(
        'ht-shipping-calculator-compact',
        get_stylesheet_directory_uri() . '/assets/js/shipping-calculator-compact.js',
        array('jquery'),
        '1.0.0',
        true
     );
    
     wp_localize_script('ht-shipping-calculator-compact', 'htShipping', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('ht_calc_shipping')
     ));
  }
    
  // Compiled main.css
  $modified_bootscoreChildCss = date('YmdHi', filemtime(get_stylesheet_directory() . '/assets/css/main.css'));
  wp_enqueue_style('main', get_stylesheet_directory_uri() . '/assets/css/main.css', array('parent-style','animete-css','bootstrap-icons'), $modified_bootscoreChildCss);

  // style.css
  wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
  
  
  // custom.js
  // Get modification time. Enqueue file with modification date to prevent browser from loading cached scripts when file content changes. 
  $modificated_CustomJS = date('YmdHi', filemtime(get_stylesheet_directory() . '/assets/js/custom.js'));
  wp_enqueue_script('custom-js', get_stylesheet_directory_uri() . '/assets/js/custom.js', array('jquery'), $modificated_CustomJS, false, true);

  wp_enqueue_script('iconitos-menus-js', get_stylesheet_directory_uri() . '/assets/js/iconitos-menus.js', array('jquery','custom-js'), false, true);

  if( is_home() || is_front_page() ) {
      wp_enqueue_script('home-js', get_stylesheet_directory_uri() . '/assets/js/home.js', array('jquery'), false, true);
  }

  // ============================================
  // Nuevas Funcionalidades UX
  // ============================================

  // Quick View - DESHABILITADO: Requiere integración backend compleja con WooCommerce
  /* wp_enqueue_script(
      'hobbytoys-quick-view',
      get_stylesheet_directory_uri() . '/assets/js/quick-view.js',
      array('jquery'),
      '1.0.0',
      true
  ); */

  // Wishlist - FUNCIONAL ✅
  wp_enqueue_script(
      'hobbytoys-wishlist',
      get_stylesheet_directory_uri() . '/assets/js/wishlist.js',
      array('jquery'),
      '1.0.0',
      true
  );

  // Ajax Search - DESHABILITADO: Requiere integración backend con WooCommerce
  /* wp_enqueue_script(
      'hobbytoys-ajax-search',
      get_stylesheet_directory_uri() . '/assets/js/ajax-search.js',
      array('jquery'),
      '1.0.0',
      true
  ); */

  // WhatsApp Float - FUNCIONAL ✅
  wp_enqueue_script(
      'hobbytoys-whatsapp-float',
      get_stylesheet_directory_uri() . '/assets/js/whatsapp-float.js',
      array('jquery'),
      '1.0.0',
      true
  );
  
}



/*************************************************
## Shopwise Register Menu 
*************************************************/
function shopwise_register_menus() {
	 register_nav_menus( array( 'categorias-sidebar-menu'  => esc_html__('Categorias Sidebar Menu','shopwise')) );
     register_nav_menus( array( 'categorias-desplegable'  => esc_html__('Categorias Desplegable Menu','shopwise')) );
}
add_action('init', 'shopwise_register_menus');


class Categoria_Imagen_Walker extends Walker_Nav_Menu {

    function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
        $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

        $output .= '<li' . $class_names . '>';

        $term = get_term_by( 'name', $item->title, 'product_cat' );

        $image = '';
        if ( $term && ! is_wp_error( $term ) ) {
            $thumb_id = get_term_meta( $term->term_id, 'thumbnail_id', true );
            $image_url = wp_get_attachment_image_src( $thumb_id, 'thumbnail' );
            if ( $image_url ) {
                $image = '<img src="' . esc_url( $image_url[0] ) . '" alt="' . esc_attr( $term->name ) . '" class="menu-cat-thumb" style="width:25px;height:auto;">';
            }
        }

        $attributes  = ! empty( $item->attr_title ) ? ' title="' . esc_attr( $item->attr_title ) . '"' : '';
        $attributes .= ! empty( $item->url ) ? ' href="' . esc_url( $item->url ) . '"' : '';
        $attributes .= ' class="menu-link"';

        $item_output = '<a' . $attributes . ' data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="'. apply_filters( 'the_title', strtolower( $item->title ), $item->ID ) .'">' . $image . apply_filters( 'the_title', strtolower( $item->title ), $item->ID ) . '</a>';

        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }
}


/* add_filter( 'woocommerce_product_add_to_cart_text', 'custom_add_to_cart_text_shop' );
function custom_add_to_cart_text_shop() {
    return 'Agregar'; // Cambiá por lo que quieras
}*/


// Cambiar cantidad y columnas de productos relacionados
/*add_filter( 'woocommerce_output_related_products_args', 'custom_woocommerce_related_products', 20 );
function custom_woocommerce_related_products( $args ) {
    $args['posts_per_page'] = 8; // Número de productos a mostrar
    $args['columns'] = 4;        // Número de columnas
    return $args;
}*/

/**
 * Productos Relacionados por Categoría
 * Muestra productos de la misma categoría del producto actual
*/

// Reemplazar la función actual
remove_filter('woocommerce_output_related_products_args', 'custom_woocommerce_related_products', 20);

add_filter('woocommerce_output_related_products_args', 'ht_related_products_by_category', 20);
function ht_related_products_by_category($args) {
    global $product;
    
    if (!$product) {
        return $args;
    }
    
    // Obtener categorías del producto actual
    $product_cats = wp_get_post_terms($product->get_id(), 'product_cat', ['fields' => 'ids']);
    
    if (empty($product_cats)) {
        return $args;
    }
    
    // Configurar argumentos para filtrar por categoría
    $args = [
        'posts_per_page' => 8,
        'columns' => 4,
        'orderby' => 'rand', // Aleatorio dentro de la categoría
        'order' => 'desc',
    ];
    
    return $args;
}

/**
 * Filtrar productos relacionados para que sean de la misma categoría
 * Sobreescribe la query de WooCommerce
 */
add_filter('woocommerce_related_products', 'ht_filter_related_products_by_category', 10, 3);
function ht_filter_related_products_by_category($related_posts, $product_id, $args) {
    // Obtener producto actual
    $product = wc_get_product($product_id);
    
    if (!$product) {
        return $related_posts;
    }
    
    // Obtener categorías del producto
    $product_cats = wp_get_post_terms($product_id, 'product_cat', ['fields' => 'ids']);
    
    if (empty($product_cats)) {
        return $related_posts;
    }
    
    // Query para obtener productos de la misma categoría
    $related_products_query = new WP_Query([
        'post_type' => 'product',
        'posts_per_page' => 12, // Obtener más para tener variedad
        'post__not_in' => [$product_id], // Excluir el producto actual
        'orderby' => 'rand',
        'tax_query' => [
            [
                'taxonomy' => 'product_cat',
                'field' => 'term_id',
                'terms' => $product_cats,
                'operator' => 'IN'
            ]
        ],
        'meta_query' => [
            [
                'key' => '_stock_status',
                'value' => 'instock',
                'compare' => '='
            ]
        ]
    ]);
    
    $related_post_ids = [];
    
    if ($related_products_query->have_posts()) {
        while ($related_products_query->have_posts()) {
            $related_products_query->the_post();
            $related_post_ids[] = get_the_ID();
        }
        wp_reset_postdata();
    }
    
    // Si no hay suficientes productos en la categoría, completar con productos relacionados normales
    if (count($related_post_ids) < 8) {
        $additional_products = wc_get_related_products($product_id, 8 - count($related_post_ids));
        $related_post_ids = array_merge($related_post_ids, $additional_products);
    }
    
    return array_unique($related_post_ids);
}


/**
 * Cambiar título de la sección de productos relacionados
*/
add_filter('woocommerce_product_related_products_heading', 'ht_related_products_heading');
function ht_related_products_heading() {
    global $product;
    
    if (!$product) {
        return 'Productos Relacionados';
    }
    
    $product_cats = wp_get_post_terms($product->get_id(), 'product_cat', ['fields' => 'names']);
    
    if (!empty($product_cats)) {
        $cat_name = $product_cats[0];
        return 'Más productos en ' . $cat_name;
    }
    
    return 'Productos Relacionados';
}

add_filter( 'woocommerce_order_button_html', 'personalizar_boton_finalizar_compra' );

function personalizar_boton_finalizar_compra( $button_html ) {
    // Creamos nuestro botón con clases personalizadas
    $nuevo_boton = '<button type="submit" class="btn-lg btn btn-primary" id="place_order" name="woocommerce_checkout_place_order" value="Iniciar el pago">
    <i class="bi bi-bag-check me-2"></i><span>Iniciar el pago</span></button>';
    return $nuevo_boton;
}



// --- FUNCIONALIDAD FILTROS EN MOBILE ESTILO TIENDA NUBE ---
if ( ! function_exists( 'woocommerce_result_count' ) ) {
    function woocommerce_result_count() {
        global $wp_query;
        $total = $wp_query->found_posts;

        echo '<div class="d-flex justify-content-between align-items-center woocommerce-result-count-wrapper">';

        // Texto del contador
        echo '<div class="woocommerce-result-count mb-0 d-lg-block d-none">';
        echo esc_html( $total ) . ' productos';
        echo '</div>';

        // Botón Filtrar
        echo '<button class="' . apply_filters(
            'bootscore/class/sidebar/button',
            'd-lg-none btn btn-outline-primary mb-0 d-flex justify-content-between align-items-center'
        ) . '" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar" aria-controls="sidebar">';
        echo '<i class="bi bi-sliders"></i> ' . esc_html__( 'Filtrar', 'bootscore' );
        echo '</button>';

        echo '</div>';
    }
}

// Mover debajo del breadcrumb
//remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
add_action( 'woocommerce_before_main_content', 'woocommerce_result_count', 25 );


add_filter( 'woocommerce_product_single_add_to_cart_text', function() {
    return __( 'Agregar', 'bootscore' );
});



// Mostrar badge de categoría "Edades"
function mostrar_badge_edades() {
    global $product;

    if ( ! $product ) return;

    $etiquetas = get_the_terms( $product->get_id(), 'product_tag' );

    if ( $etiquetas && ! is_wp_error( $etiquetas ) ) {
        foreach ( $etiquetas as $etiqueta ) {
            echo '<span class="badge mb-3 d-inline-block">' . esc_html( $etiqueta->name ) . '</span>';
            break; // Solo la primera
        }
    }
}
add_action( 'woocommerce_shop_loop_item_title', 'mostrar_badge_edades', 5 );
add_action( 'woocommerce_single_product_summary', 'mostrar_badge_edades', 4 );



// --- SINGLE PRODUCT ---
// Quitar el "¡Oferta!" default
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );

// Agregar nuestro sale flash con porcentaje
add_action( 'woocommerce_before_single_product_summary', 'mi_sale_flash_custom', 10 );

// --- SHOP (loop de productos) ---
// Quitar el "¡Oferta!" default
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );

// Agregar nuestro sale flash con porcentaje
add_action( 'woocommerce_before_shop_loop_item_title', 'mi_sale_flash_custom', 10 );

// --- Función única para ambos casos ---
function mi_sale_flash_custom() {
    global $product;

    if ( ! $product || ! $product->is_on_sale() ) {
        return;
    }

    // Solo productos simples
    if ( $product->is_type( 'simple' ) ) {
        $regular = (float) $product->get_regular_price();
        $sale    = (float) $product->get_sale_price();

        if ( $regular > 0 && $sale > 0 && $sale < $regular ) {
            $percentage = round( ( ( $regular - $sale ) / $regular ) * 100 );
            echo '<span class="onsale badge mb-1 d-inline-block">' . $percentage . '% OFF</span>';
        }
    }
}


add_action('admin_menu', function() {
    remove_meta_box('tagsdiv-product_tag', 'product', 'side');
});


// ============================================
// BARRA SUPERIOR CON PROGRESO DE ENVÍO GRATIS
// ============================================

// Registrar widget area para la barra superior
function ht_register_topbar_widget() {
    register_sidebar(array(
        'name'          => 'Barra Superior Envíos',
        'id'            => 'topbar-shipping',
        'description'   => 'Barra superior con información de envíos y progreso',
        'before_widget' => '<div class="topbar-shipping-widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="topbar-title">',
        'after_title'   => '</h3>',
    ));
}
add_action('widgets_init', 'ht_register_topbar_widget');


// Función para calcular el progreso del envío gratis
function ht_get_free_shipping_progress() {
    if (!class_exists('WooCommerce')) {
        return array(
            'cart_total' => 0,
            'free_shipping_min' => 90000,
            'remaining' => 90000,
            'percentage' => 0,
            'is_free' => false
        );
    }
    
    $cart_total = WC()->cart->get_subtotal();
    $free_shipping_min = 90000; // Cambiar según tus necesidades
    $remaining = max(0, $free_shipping_min - $cart_total);
    $percentage = min(100, ($cart_total / $free_shipping_min) * 100);
    
    return array(
        'cart_total' => $cart_total,
        'free_shipping_min' => $free_shipping_min,
        'remaining' => $remaining,
        'percentage' => $percentage,
        'is_free' => $cart_total >= $free_shipping_min
    );
}

// AJAX para actualizar el progreso del carrito
add_action('wp_ajax_get_shipping_progress', 'ht_ajax_get_shipping_progress');
add_action('wp_ajax_nopriv_get_shipping_progress', 'ht_ajax_get_shipping_progress');

function ht_ajax_get_shipping_progress() {
    $progress = ht_get_free_shipping_progress();
    wp_send_json_success($progress);
}



// Filtrar opciones de ordenamiento - Solo por precio
/*add_filter('woocommerce_catalog_orderby', 'custom_woocommerce_catalog_orderby');
function custom_woocommerce_catalog_orderby($orderby) {
    // Crear array con solo las opciones de precio
    $orderby = array(
        'price'      => __('Precio: Menor a Mayor', 'woocommerce'),
        'price-desc' => __('Precio: Mayor a Menor', 'woocommerce')
    );
    
    return $orderby;
}*/

// Establecer orden por defecto (precio menor a mayor)
add_filter('woocommerce_default_catalog_orderby', 'custom_default_catalog_orderby');
function custom_default_catalog_orderby() {
    return 'price'; // Precio de menor a mayor por defecto
}


function ht_enqueue_checkout_scripts() {
    if (is_checkout()) {
        wp_enqueue_script(
            'ht-checkout',
            get_stylesheet_directory_uri() . '/assets/js/checkout.js',
            array('jquery'),
            '1.0',
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'ht_enqueue_checkout_scripts');


function ht_enqueue_cart_scripts() {
    if (is_cart()) {
        wp_enqueue_script(
            'ht-cart',
            get_stylesheet_directory_uri() . '/assets/js/cart.js',
            array('jquery'),
            '1.0',
            true
        );
        
        wp_localize_script('ht-cart', 'htCart', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('ht_cart_nonce')
        ));
    }
}
add_action('wp_enqueue_scripts', 'ht_enqueue_cart_scripts');



// AJAX para obtener contador del carrito
function ht_get_cart_count() {
    echo WC()->cart->get_cart_contents_count();
    wp_die();
}
add_action('wp_ajax_get_cart_count', 'ht_get_cart_count');
add_action('wp_ajax_nopriv_get_cart_count', 'ht_get_cart_count');


/**
 * Incluir archivos necesarios
*/
require_once get_stylesheet_directory() . '/inc-functions/inc-productos.php';
require_once get_stylesheet_directory() . '/inc-functions/inc-formularios.php';
//require_once get_stylesheet_directory() . '/inc-functions/inc-mayorista.php';
//require_once THEME_DIR . '/inc/inc-envios.php';
//require_once get_stylesheet_directory() . '/inc-functions/inc-envios.php';
require_once get_stylesheet_directory() . '/inc-functions/inc-checkout.php';


/**
 * Configuración ACF PRO - Carruseles de Productos Dinámicos
 * Agregar a functions.php del child theme
*/

if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
    'key' => 'group_carruseles_productos',
    'title' => 'Carruseles de Productos',
    'fields' => array(
        array(
            'key' => 'field_carruseles_flexible',
            'label' => 'Secciones de Carruseles',
            'name' => 'carruseles_productos',
            'type' => 'flexible_content',
            'instructions' => 'Agregá tantos carruseles como necesites. Cada uno puede tener su propia configuración.',
            'button_label' => 'Agregar Carrusel',
            'layouts' => array(
                'layout_carrusel_completo' => array(
                    'key' => 'layout_carrusel_completo',
                    'name' => 'carrusel_completo',
                    'label' => 'Carrusel con Banner',
                    'display' => 'block',
                    'sub_fields' => array(
                        
                        // ============================================
                        // TAB: CONTENIDO
                        // ============================================
                        array(
                            'key' => 'field_tab_contenido',
                            'label' => 'Contenido',
                            'type' => 'tab',
                            'placement' => 'top',
                        ),
                        
                        array(
                            'key' => 'field_titulo_carrusel',
                            'label' => 'Título Principal',
                            'name' => 'titulo',
                            'type' => 'text',
                            'default_value' => 'Productos Destacados',
                            'placeholder' => 'Ej: Velocidad y Diversión',
                            'wrapper' => array(
                                'width' => '50',
                            ),
                        ),
                        
                        array(
                            'key' => 'field_subtitulo_carrusel',
                            'label' => 'Subtítulo',
                            'name' => 'subtitulo',
                            'type' => 'text',
                            'placeholder' => 'Ej: Los mejores autos RC',
                            'wrapper' => array(
                                'width' => '50',
                            ),
                        ),
                        
                        array(
                            'key' => 'field_productos_carrusel',
                            'label' => 'Seleccionar Productos',
                            'name' => 'productos',
                            'type' => 'post_object',
                            'post_type' => array('product'),
                            'multiple' => 1,
                            'return_format' => 'id',
                            'ui' => 1,
                            'instructions' => 'Seleccioná los productos que querés mostrar en este carrusel (mínimo 4 recomendado)',
                        ),
                        
                        // ============================================
                        // TAB: BANNER LATERAL
                        // ============================================
                        array(
                            'key' => 'field_tab_banner',
                            'label' => 'Banner Lateral',
                            'type' => 'tab',
                        ),
                        
                        array(
                            'key' => 'field_mostrar_banner',
                            'label' => 'Mostrar Banner Lateral',
                            'name' => 'mostrar_banner',
                            'type' => 'true_false',
                            'default_value' => 1,
                            'ui' => 1,
                            'message' => 'Activar banner lateral promocional',
                        ),
                        
                        array(
                            'key' => 'field_banner_posicion',
                            'label' => 'Posición del Banner',
                            'name' => 'banner_posicion',
                            'type' => 'select',
                            'choices' => array(
                                'left' => 'Izquierda',
                                'right' => 'Derecha',
                            ),
                            'default_value' => 'left',
                            'conditional_logic' => array(
                                array(
                                    array(
                                        'field' => 'field_mostrar_banner',
                                        'operator' => '==',
                                        'value' => '1',
                                    ),
                                ),
                            ),
                            'wrapper' => array(
                                'width' => '33',
                            ),
                        ),
                        
                        array(
                            'key' => 'field_banner_titulo',
                            'label' => 'Título del Banner',
                            'name' => 'banner_titulo',
                            'type' => 'text',
                            'placeholder' => 'Ej: Arte y Creatividad',
                            'conditional_logic' => array(
                                array(
                                    array(
                                        'field' => 'field_mostrar_banner',
                                        'operator' => '==',
                                        'value' => '1',
                                    ),
                                ),
                            ),
                            'wrapper' => array(
                                'width' => '33',
                            ),
                        ),
                        
                        array(
                            'key' => 'field_banner_precio_desde',
                            'label' => 'Precio Desde',
                            'name' => 'banner_precio',
                            'type' => 'text',
                            'placeholder' => 'Ej: $599',
                            'conditional_logic' => array(
                                array(
                                    array(
                                        'field' => 'field_mostrar_banner',
                                        'operator' => '==',
                                        'value' => '1',
                                    ),
                                ),
                            ),
                            'wrapper' => array(
                                'width' => '33',
                            ),
                        ),
                        
                        array(
                            'key' => 'field_banner_badge',
                            'label' => 'Texto del Badge',
                            'name' => 'banner_badge',
                            'type' => 'text',
                            'placeholder' => 'Ej: Arte Creativo',
                            'conditional_logic' => array(
                                array(
                                    array(
                                        'field' => 'field_mostrar_banner',
                                        'operator' => '==',
                                        'value' => '1',
                                    ),
                                ),
                            ),
                            'wrapper' => array(
                                'width' => '50',
                            ),
                        ),
                        
                        array(
                            'key' => 'field_banner_imagen_fondo',
                            'label' => 'Imagen de Fondo del Banner',
                            'name' => 'banner_imagen_fondo',
                            'type' => 'image',
                            'return_format' => 'url',
                            'preview_size' => 'medium',
                            'instructions' => 'Imagen de fondo para el banner (opcional)',
                            'conditional_logic' => array(
                                array(
                                    array(
                                        'field' => 'field_mostrar_banner',
                                        'operator' => '==',
                                        'value' => '1',
                                    ),
                                ),
                            ),
                            'wrapper' => array(
                                'width' => '50',
                            ),
                        ),
                        
                        // ============================================
                        // TAB: DISEÑO
                        // ============================================
                        array(
                            'key' => 'field_tab_diseno',
                            'label' => 'Diseño y Colores',
                            'type' => 'tab',
                        ),
                        
                        array(
                            'key' => 'field_color_fondo',
                            'label' => 'Color de Fondo Principal',
                            'name' => 'color_fondo',
                            'type' => 'select',
                            'choices' => array(
                                'light' => 'Claro (#f8f9fa)',
                                'white' => 'Blanco',
                                'primary' => 'Rosa Primario (#EE285B)',
                                'secondary' => 'Púrpura Secundario (#534FB5)',
                                'gradient-primary' => 'Gradiente Rosa-Púrpura',
                                'gradient-secondary' => 'Gradiente Púrpura-Rosa',
                                'custom' => 'Color Personalizado',
                            ),
                            'default_value' => 'light',
                            'wrapper' => array(
                                'width' => '50',
                            ),
                        ),
                        
                        array(
                            'key' => 'field_color_fondo_custom',
                            'label' => 'Color Personalizado',
                            'name' => 'color_fondo_custom',
                            'type' => 'color_picker',
                            'default_value' => '#f8f9fa',
                            'conditional_logic' => array(
                                array(
                                    array(
                                        'field' => 'field_color_fondo',
                                        'operator' => '==',
                                        'value' => 'custom',
                                    ),
                                ),
                            ),
                            'wrapper' => array(
                                'width' => '50',
                            ),
                        ),
                        
                        array(
                            'key' => 'field_mostrar_patron',
                            'label' => 'Mostrar Patrón de Fondo',
                            'name' => 'mostrar_patron',
                            'type' => 'true_false',
                            'default_value' => 0,
                            'ui' => 1,
                            'message' => 'Activar patrón decorativo en el fondo',
                            'wrapper' => array(
                                'width' => '33',
                            ),
                        ),
                        
                        array(
                            'key' => 'field_color_patron',
                            'label' => 'Color del Patrón',
                            'name' => 'color_patron',
                            'type' => 'color_picker',
                            'default_value' => '#EE285B',
                            'conditional_logic' => array(
                                array(
                                    array(
                                        'field' => 'field_mostrar_patron',
                                        'operator' => '==',
                                        'value' => '1',
                                    ),
                                ),
                            ),
                            'wrapper' => array(
                                'width' => '33',
                            ),
                        ),
                        
                        array(
                            'key' => 'field_opacidad_patron',
                            'label' => 'Opacidad del Patrón',
                            'name' => 'opacidad_patron',
                            'type' => 'range',
                            'default_value' => 4,
                            'min' => 1,
                            'max' => 10,
                            'step' => 1,
                            'append' => '%',
                            'conditional_logic' => array(
                                array(
                                    array(
                                        'field' => 'field_mostrar_patron',
                                        'operator' => '==',
                                        'value' => '1',
                                    ),
                                ),
                            ),
                            'wrapper' => array(
                                'width' => '33',
                            ),
                        ),
                        
                        array(
                            'key' => 'field_banner_color_gradiente',
                            'label' => 'Color de Gradiente del Banner',
                            'name' => 'banner_color_gradiente',
                            'type' => 'select',
                            'choices' => array(
                                'gradient-primary' => 'Rosa a Púrpura',
                                'gradient-secondary' => 'Púrpura a Rosa',
                                'gradient-purple' => 'Púrpura a Morado',
                                'gradient-orange' => 'Naranja a Amarillo',
                                'gradient-blue' => 'Azul a Celeste',
                                'gradient-green' => 'Verde a Esmeralda',
                            ),
                            'default_value' => 'gradient-primary',
                            'conditional_logic' => array(
                                array(
                                    array(
                                        'field' => 'field_mostrar_banner',
                                        'operator' => '==',
                                        'value' => '1',
                                    ),
                                ),
                            ),
                            'wrapper' => array(
                                'width' => '50',
                            ),
                        ),
                        
                        // ============================================
                        // TAB: BOTÓN
                        // ============================================
                        array(
                            'key' => 'field_tab_boton',
                            'label' => 'Botón del Banner',
                            'type' => 'tab',
                        ),
                        
                        array(
                            'key' => 'field_boton_texto',
                            'label' => 'Texto del Botón',
                            'name' => 'boton_texto',
                            'type' => 'text',
                            'default_value' => 'Ver Productos',
                            'conditional_logic' => array(
                                array(
                                    array(
                                        'field' => 'field_mostrar_banner',
                                        'operator' => '==',
                                        'value' => '1',
                                    ),
                                ),
                            ),
                            'wrapper' => array(
                                'width' => '33',
                            ),
                        ),
                        
                        array(
                            'key' => 'field_boton_link',
                            'label' => 'Link del Botón',
                            'name' => 'boton_link',
                            'type' => 'link',
                            'return_format' => 'array',
                            'conditional_logic' => array(
                                array(
                                    array(
                                        'field' => 'field_mostrar_banner',
                                        'operator' => '==',
                                        'value' => '1',
                                    ),
                                ),
                            ),
                            'wrapper' => array(
                                'width' => '33',
                            ),
                        ),
                        
                        array(
                            'key' => 'field_boton_icono',
                            'label' => 'Icono del Botón (Bootstrap Icons)',
                            'name' => 'boton_icono',
                            'type' => 'text',
                            'placeholder' => 'Ej: bi-palette',
                            'instructions' => 'Nombre de la clase del icono de Bootstrap Icons (sin el "bi-")',
                            'conditional_logic' => array(
                                array(
                                    array(
                                        'field' => 'field_mostrar_banner',
                                        'operator' => '==',
                                        'value' => '1',
                                    ),
                                ),
                            ),
                            'wrapper' => array(
                                'width' => '33',
                            ),
                        ),
                        
                        array(
                            'key' => 'field_boton_color',
                            'label' => 'Color del Botón',
                            'name' => 'boton_color',
                            'type' => 'select',
                            'choices' => array(
                                'accent' => 'Amarillo Acento (#FFB900)',
                                'white' => 'Blanco',
                                'primary' => 'Rosa Primario',
                                'secondary' => 'Púrpura Secundario',
                                'custom' => 'Personalizado',
                            ),
                            'default_value' => 'accent',
                            'conditional_logic' => array(
                                array(
                                    array(
                                        'field' => 'field_mostrar_banner',
                                        'operator' => '==',
                                        'value' => '1',
                                    ),
                                ),
                            ),
                            'wrapper' => array(
                                'width' => '50',
                            ),
                        ),
                        
                        array(
                            'key' => 'field_boton_color_custom',
                            'label' => 'Color Personalizado del Botón',
                            'name' => 'boton_color_custom',
                            'type' => 'color_picker',
                            'default_value' => '#FFB900',
                            'conditional_logic' => array(
                                array(
                                    array(
                                        'field' => 'field_mostrar_banner',
                                        'operator' => '==',
                                        'value' => '1',
                                    ),
                                    array(
                                        'field' => 'field_boton_color',
                                        'operator' => '==',
                                        'value' => 'custom',
                                    ),
                                ),
                            ),
                            'wrapper' => array(
                                'width' => '50',
                            ),
                        ),
                        
                    ),
                ),
            ),
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'page_template',
                'operator' => '==',
                'value' => 'page-home.php',
            ),
        ),
    ),
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
));

endif;


/**
 * Agregar botón de envío gratis al menú
 */
 /*
add_action('wp_nav_menu_items', 'ht_add_shipping_button_to_menu', 10, 2);
function ht_add_shipping_button_to_menu($items, $args) {
    if ($args->theme_location === 'main-menu') {
        $items .= '<li class="nav-item">';
        $items .= '<button type="button" class="btn ht-open-shipping-calculator" data-bs-toggle="modal" data-bs-target="#htShippingCalculatorModal">';
        $items .= '<i class="bi bi-truck"></i> ';
        $items .= '<span class="d-none d-lg-inline">tú Código Postal</span>';
        $items .= '</button>';
        $items .= '</li>';
    }
    return $items;
}
*/


/**
 * CSS personalizado para precios más grandes y en negrita
 */
add_action('wp_head', 'ht_custom_price_styles');
function ht_custom_price_styles() {
    ?>
    <style>
        /* Precios de productos más grandes y en negrita */
        .woocommerce ul.products li.product .price,
        .woocommerce div.product p.price,
        .woocommerce div.product span.price {
            font-weight: 900 !important;
            font-size: 1.4rem !important;
        }

        /* Precio tachado (old price) */
        .woocommerce ul.products li.product .price del,
        .woocommerce div.product p.price del,
        .woocommerce div.product span.price del {
            font-size: 1rem !important;
            font-weight: 600 !important;
        }
    </style>
    <?php
}

/**
 * Actualizar texto del footer automáticamente
 * Cambia "38 años" por "30 años" en todos los widgets de texto
 */
add_filter('widget_text', 'ht_update_footer_years_text');
function ht_update_footer_years_text($text) {
    // Reemplazar "38 años" por "30 años"
    $text = str_replace('38 años', '30 años', $text);
    $text = str_replace('más de 38', 'más de 30', $text);

    return $text;
}

/**
 * CSS para corregir imágenes en productos relacionados y recomendaciones
 */
add_action('wp_head', 'ht_fix_product_images_size');
function ht_fix_product_images_size() {
    ?>
    <style>
        /* Corregir tamaño de imágenes en productos relacionados */
        .related.products .product .woocommerce-loop-product__link img,
        .upsells.products .product .woocommerce-loop-product__link img,
        .up-sells.products .product .woocommerce-loop-product__link img {
            max-width: 100%;
            height: auto;
            max-height: 200px;
            object-fit: contain;
            display: block;
            margin: 0 auto;
        }

        /* Wrapper de imagen con altura fija */
        .related.products .swiper-slide .woocommerce-loop-product__link > *:first-child,
        .upsells.products .woocommerce-loop-product__link > *:first-child,
        .up-sells.products .woocommerce-loop-product__link > *:first-child {
            min-height: 180px;
            max-height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        /* Título de producto más grande en productos relacionados */
        .related.products .woocommerce-loop-product__title,
        .upsells.products .woocommerce-loop-product__title,
        .up-sells.products .woocommerce-loop-product__title {
            font-size: 1.1rem !important;
            font-weight: 600 !important;
            line-height: 1.3 !important;
            margin-bottom: 0.75rem !important;
        }

        /* Badges en productos relacionados - igualar con loop principal */
        .related.products .badge-categoria,
        .related.products .badge-edad,
        .upsells.products .badge-categoria,
        .upsells.products .badge-edad {
            display: inline-flex !important;
            align-items: center;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
            margin-bottom: 0.5rem;
        }

        .related.products .badge-categoria:hover,
        .upsells.products .badge-categoria:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        /* Responsive - Mobile */
        @media (max-width: 767.98px) {
            .related.products .woocommerce-loop-product__title,
            .upsells.products .woocommerce-loop-product__title {
                font-size: 0.95rem !important;
            }
        }

        /* ============================================
           SINGLE PRODUCT - LAYOUT REORGANIZADO
        ============================================ */

        /* Título del producto */
        /*
        .single-product .product_title {
            font-size: 1.75rem !important;
            font-weight: 700 !important;
            line-height: 1.3 !important;
            margin-bottom: 1rem !important;
            color: var(--text-dark, #2c3e50) !important;
        }
            /*

        /* Precio más destacado */
        /*
        .single-product .summary .price {
            font-size: 2rem !important;
            font-weight: 900 !important;
            color: var(--primary-color, #EE285B) !important;
            margin: 1rem 0 !important;
        }
            */

        /* Precio sin IVA */
        .single-product .precio-sin-iva-container {
            padding: 0.75rem 1rem !important;
            background: linear-gradient(135deg, rgba(238, 40, 91, 0.05), rgba(238, 40, 91, 0.02)) !important;
            border-left: 3px solid var(--primary-color, #EE285B) !important;
            border-radius: 0.5rem !important;
            margin-bottom: 1.5rem !important;
        }

        /* Descripción del producto */
        .single-product .woocommerce-product-details__short-description {
            font-size: 1rem !important;
            line-height: 1.6 !important;
            color: #555 !important;
            margin-bottom: 1.5rem !important;
            padding: 1rem !important;
            background: #f8f9fa !important;
            border-radius: 0.5rem !important;
        }

        /* Botón agregar al carrito */
        /*
        .single-product .cart button.single_add_to_cart_button {
            font-size: 1.1rem !important;
            padding: 1rem 2rem !important;
            font-weight: 700 !important;
            border-radius: 0.75rem !important;
            min-height: 50px !important;
        }
            */

        /* Espaciado entre secciones */
        /*
        .single-product .summary > * {
            margin-bottom: 1.5rem;
        }
            */

        /* Categorías y tags */
        .single-product .product-categories a.badge,
        .single-product .product-tags a.badge {
            transition: all 0.3s ease;
        }

        .single-product .product-categories a.badge:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(238, 40, 91, 0.3);
        }

        .single-product .product-tags a.badge:hover {
            border-color: var(--primary-color);
            background: rgba(238, 40, 91, 0.1);
        }

        /* Responsive - Mobile */
        @media (max-width: 767.98px) {
            .single-product .product_title {
                font-size: 1.4rem !important;
            }

            .single-product .summary .price {
                font-size: 1.6rem !important;
            }

            .single-product .guarantees-section .row {
                margin: 0 -0.25rem !important;
            }

            .single-product .guarantees-section .col-6 {
                padding: 0 0.25rem !important;
            }
        }
    </style>
    <?php
}

/**
 * Quitar categoría "JUGUETERIA" del breadcrumb
 */
add_filter('woocommerce_get_breadcrumb', 'ht_remove_jugueteria_from_breadcrumb', 10, 2);
function ht_remove_jugueteria_from_breadcrumb($breadcrumb, $breadcrumb_class) {
    $filtered_breadcrumb = [];

    foreach ($breadcrumb as $key => $crumb) {
        // Verificar si el nombre es "JUGUETERIA" (case insensitive)
        if (isset($crumb[0]) && strtoupper($crumb[0]) !== 'JUGUETERIA') {
            $filtered_breadcrumb[] = $crumb;
        }
    }

    return $filtered_breadcrumb;
}

/**
 * CSS mejorado para breadcrumb - diseño estilo badges con gradientes suaves
*/


//add_action('wp_head', 'ht_breadcrumb_badge_styles');
function ht_breadcrumb_badge_styles() {
    ?>
    <style>
        /* Breadcrumb estilo badges - gradientes suaves y colores armoniosos */
        .wc-breadcrumb {
            background: linear-gradient(135deg, rgba(238, 40, 91, 0.05), rgba(238, 40, 91, 0.02)) !important;
            border: none !important;
            border-radius: 0.75rem !important;
            padding: 1rem !important;
            margin-bottom: 1.5rem !important;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .wc-breadcrumb .breadcrumb {
            margin-bottom: 0;
            font-size: 0.85rem;
            background: transparent;
            padding: 0;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .wc-breadcrumb .breadcrumb-item {
            display: inline-flex;
            align-items: center;
        }

        /* Separador entre items */
        .wc-breadcrumb .breadcrumb-item + .breadcrumb-item::before {
            content: '›';
            color: #EE285B;
            font-weight: 700;
            font-size: 1rem;
            padding: 0 0.4rem;
            opacity: 0.6;
        }

        /* Estilos para enlaces - badge clicable */
        .wc-breadcrumb .breadcrumb-item a {
            background: linear-gradient(135deg, rgba(83, 79, 181, 0.08), rgba(83, 79, 181, 0.04));
            border: 2px solid rgba(83, 79, 181, 0.2);
            color: #534fb5;
            text-decoration: none;
            padding: 0.35rem 0.8rem;
            border-radius: 0.75rem;
            font-weight: 600;
            font-size: 0.8rem;
            transition: all 0.2s ease;
            display: inline-block;
        }

        .wc-breadcrumb .breadcrumb-item a:hover {
            background: linear-gradient(135deg, rgba(238, 40, 91, 0.12), rgba(238, 40, 91, 0.08));
            border-color: rgba(238, 40, 91, 0.3);
            color: #EE285B;
            transform: translateY(-1px);
            box-shadow: 0 3px 10px rgba(238, 40, 91, 0.15);
        }

        /* Item actual (último) - badge sin click */
        .wc-breadcrumb .breadcrumb-item:last-child {
            background: linear-gradient(135deg, rgba(255, 185, 0, 0.15), rgba(255, 185, 0, 0.08));
            border: 2px solid rgba(255, 185, 0, 0.3);
            color: #d49700;
            padding: 0.35rem 0.8rem;
            border-radius: 0.75rem;
            font-weight: 700;
            font-size: 0.8rem;
            display: inline-block;
        }

        /* Icono de casa (Bootstrap Icons) */
        .wc-breadcrumb .breadcrumb-item i.bi-house-door-fill {
            color: #534fb5;
            font-size: 0.9rem;
            margin-right: 0.15rem;
        }

        .wc-breadcrumb .breadcrumb-item a i.bi-house-door-fill {
            color: #534fb5;
        }

        .wc-breadcrumb .breadcrumb-item a:hover i.bi-house-door-fill {
            color: #EE285B;
        }

        /* Responsive para móviles */
        @media (max-width: 767.98px) {
            .wc-breadcrumb {
                padding: 0.75rem !important;
                gap: 0.35rem;
            }

            .wc-breadcrumb .breadcrumb {
                font-size: 0.75rem;
                gap: 0.35rem;
            }

            .wc-breadcrumb .breadcrumb-item a {
                padding: 0.25rem 0.6rem;
                font-size: 0.7rem;
            }

            .wc-breadcrumb .breadcrumb-item:last-child {
                padding: 0.25rem 0.6rem;
                font-size: 0.7rem;
            }

            .wc-breadcrumb .breadcrumb-item + .breadcrumb-item::before {
                padding: 0 0.25rem;
                font-size: 0.85rem;
            }

            .wc-breadcrumb .breadcrumb-item i.bi-house-door-fill {
                font-size: 0.75rem;
            }
        }
    </style>
    <?php
}


/**
 * Mostrar badges de edad en single producto
*/
//add_action('woocommerce_single_product_summary', 'ht_display_age_badges_single_product', 7);
function ht_display_age_badges_single_product() {
    global $product;

    if (!$product) {
        return;
    }

    // Obtener TODAS las edades del producto (atributo pa_edades)
    $edad_terms = get_the_terms($product->get_id(), 'pa_edades');
    $edades = [];

    if ($edad_terms && !is_wp_error($edad_terms)) {
        // Mapear el slug a texto legible - SLUGS REALES DE WORDPRESS
        $edad_labels = [
            '0-a-3-anos' => '0 a 3 años',
            '4-a-6-anos' => '4 a 6 años',
            '7-a-12-anos' => '7 a 12 años',
            '12adultos' => '+12 y adultos',
        ];

        // Mapear colores - SLUGS REALES DE WORDPRESS
        $color_map = [
            '0-a-3-anos' => '#EE285B',   // rojo
            '4-a-6-anos' => '#0dcaf0',   // cyan
            '7-a-12-anos' => '#ffb900',  // amarillo
            '12adultos' => '#198754',    // verde
        ];

        foreach ($edad_terms as $edad_term) {
            $edad_slug = $edad_term->slug;
            $edad_display = isset($edad_labels[$edad_slug]) ? $edad_labels[$edad_slug] : $edad_term->name;
            $edad_color = isset($color_map[$edad_slug]) ? $color_map[$edad_slug] : '#999';
            $edad_link = get_term_link($edad_term);

            $edades[] = [
                'display' => $edad_display,
                'color' => $edad_color,
                'link' => $edad_link
            ];
        }
    }

    // Mostrar badges si hay edades
    if (!empty($edades)) {
        echo '<div class="product-age-badges-single d-flex flex-wrap gap-2 mb-3">';
        foreach ($edades as $edad) {
            echo '<a href="' . esc_url($edad['link']) . '" class="badge rounded-pill text-decoration-none d-inline-flex align-items-center gap-1" ';
            echo 'style="background-color: ' . esc_attr($edad['color']) . ' !important; color: white !important; font-weight: 700; font-size: 0.85rem; padding: 0.5rem 1rem; box-shadow: 0 2px 8px rgba(0,0,0,0.2); transition: all 0.2s;">';
            echo '<i class="bi bi-person-fill" style="font-size: 0.8rem;"></i>';
            echo '<span>' . esc_html($edad['display']) . '</span>';
            echo '</a>';
        }
        echo '</div>';
    }
}


/**
 * Mostrar opciones de pago en single producto
*/
add_action('woocommerce_single_product_summary', 'ht_display_payment_options_single_product', 11);
function ht_display_payment_options_single_product() {
    global $product;

    if (!$product) {
        return;
    }

    $precio = floatval($product->get_price());

    if ($precio <= 0) {
        return;
    }

    // Calcular cuotas e importes
    $cuota_6 = $precio / 6;
    $precio_transferencia = $precio * 0.95; // 5% menos
    $cuota_banco_provincia = $precio / 4;
    $reintegro_10_porciento = $precio * 0.10;

    echo '<div class="product-payment-options mt-3 mb-3">';

    // 6 cuotas sin interés
    echo '<div class="payment-option mb-2 px-3 py-2 rounded d-flex align-items-center gap-2" style="background: linear-gradient(135deg, rgba(83, 79, 181, 0.08), rgba(83, 79, 181, 0.04)); border: 2px solid rgba(83, 79, 181, 0.15);">';
    echo '<i class="bi bi-credit-card" style="font-size: 1.1rem; color: #534fb5;"></i>';
    echo '<div>';
    echo '<div style="font-weight: 600; color: #534fb5; font-size: 0.85rem;">6 cuotas sin interés</div>';
    echo '<div style="font-weight: 700; color: #292b2c; font-size: 0.95rem;">6 x $' . number_format($cuota_6, 2, ',', '.') . '</div>';
    echo '</div>';
    echo '</div>';

    // Precio por transferencia
    echo '<div class="payment-option mb-2 px-3 py-2 rounded d-flex align-items-center gap-2" style="background: linear-gradient(135deg, rgba(238, 40, 91, 0.08), rgba(238, 40, 91, 0.04)); border: 2px solid rgba(238, 40, 91, 0.15);">';
    echo '<i class="bi bi-cash-stack" style="font-size: 1.1rem; color: #EE285B;"></i>';
    echo '<div>';
    echo '<div style="font-weight: 600; color: #EE285B; font-size: 0.85rem;">Transferencia bancaria (5% OFF)</div>';
    echo '<div style="font-weight: 700; color: #292b2c; font-size: 0.95rem;">$' . number_format($precio_transferencia, 2, ',', '.') . '</div>';
    echo '</div>';
    echo '</div>';

    // Banco Provincia promoción
    echo '<div class="payment-option mb-2 px-3 py-2 rounded d-flex align-items-center gap-2" style="background: linear-gradient(135deg, rgba(255, 185, 0, 0.12), rgba(255, 185, 0, 0.06)); border: 2px solid rgba(255, 185, 0, 0.25);">';
    echo '<i class="bi bi-star-fill" style="font-size: 1.1rem; color: #ffb900;"></i>';
    echo '<div>';
    echo '<div style="font-weight: 600; color: #d49700; font-size: 0.85rem;">Banco Provincia - 4 cuotas + 10% de reintegro</div>';
    echo '<div style="font-weight: 700; color: #292b2c; font-size: 0.95rem;">4 x $' . number_format($cuota_banco_provincia, 2, ',', '.') . ' <span style="font-size: 0.8rem; color: #198754;">(Reintegro: $' . number_format($reintegro_10_porciento, 2, ',', '.') . ')</span></div>';
    echo '</div>';
    echo '</div>';

    echo '</div>';
}


/**
 * Mostrar características del producto debajo de la descripción
 */
add_action('woocommerce_single_product_summary', 'ht_display_product_features_single', 21);
function ht_display_product_features_single() {
    global $product;

    if (!$product) {
        return;
    }

    // Obtener las características guardadas
    $feature_1 = get_post_meta($product->get_id(), '_ht_feature_1', true);
    $feature_2 = get_post_meta($product->get_id(), '_ht_feature_2', true);
    $feature_3 = get_post_meta($product->get_id(), '_ht_feature_3', true);

    // Si no hay características, no mostrar nada
    if (empty($feature_1) && empty($feature_2) && empty($feature_3)) {
        return;
    }

    echo '<div class="product-features-section mt-4 mb-3">';
    echo '<h5 style="font-weight: 700; color: #292b2c; margin-bottom: 1rem; font-size: 1.1rem;">Características destacadas</h5>';
    echo '<ul class="product-features-list" style="list-style: none; padding: 0; margin: 0;">';

    if (!empty($feature_1)) {
        echo '<li class="feature-item mb-2 d-flex align-items-start gap-2">';
        echo '<i class="bi bi-check-circle-fill" style="color: #198754; font-size: 1.2rem; margin-top: 2px;"></i>';
        echo '<span style="color: #687188; font-size: 0.95rem; line-height: 1.6;">' . esc_html($feature_1) . '</span>';
        echo '</li>';
    }

    if (!empty($feature_2)) {
        echo '<li class="feature-item mb-2 d-flex align-items-start gap-2">';
        echo '<i class="bi bi-check-circle-fill" style="color: #198754; font-size: 1.2rem; margin-top: 2px;"></i>';
        echo '<span style="color: #687188; font-size: 0.95rem; line-height: 1.6;">' . esc_html($feature_2) . '</span>';
        echo '</li>';
    }

    if (!empty($feature_3)) {
        echo '<li class="feature-item mb-2 d-flex align-items-start gap-2">';
        echo '<i class="bi bi-check-circle-fill" style="color: #198754; font-size: 1.2rem; margin-top: 2px;"></i>';
        echo '<span style="color: #687188; font-size: 0.95rem; line-height: 1.6;">' . esc_html($feature_3) . '</span>';
        echo '</li>';
    }

    echo '</ul>';
    echo '</div>';
}


/**
 * Personalizar título de archivos de taxonomías, categorías y página de tienda
*/

remove_action('woocommerce_shop_loop_header', 'woocommerce_product_taxonomy_archive_header', 10);
add_action('woocommerce_shop_loop_header', 'ht_custom_taxonomy_archive_header', 10);

function ht_custom_taxonomy_archive_header() {
    // Valores por defecto
    $icon = 'bi-shop';
    $color_bg = 'rgba(83, 79, 181, 0.08)';
    $color_border = 'rgba(83, 79, 181, 0.2)';
    $color_text = '#534fb5';
    $term_name = '';
    $term_description = '';
    $term_count = 0;

    // Si es la página de tienda principal
    if (is_shop() && !is_product_category() && !is_product_tag() && !is_tax()) {
        $term_name = 'Tienda';
        $icon = 'bi-shop';
        $color_bg = 'rgba(83, 79, 181, 0.08)';
        $color_border = 'rgba(83, 79, 181, 0.2)';
        $color_text = '#534fb5';

        // Contar todos los productos publicados
        $args = array(
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'fields' => 'ids'
        );
        $products = get_posts($args);
        $term_count = count($products);
        $term_description = get_option('woocommerce_shop_page_description', '');
    }
    // Si es una taxonomía, categoría o etiqueta
    elseif (is_tax() || is_product_category() || is_product_tag()) {
        $term = get_queried_object();

        if (!$term) {
            return;
        }

        $term_name = $term->name;
        $term_description = term_description();
        $term_count = $term->count;

        // Determinar icono y color según taxonomía
        if ($term->taxonomy === 'product_cat') {
            $icon = 'bi-grid-fill';
            $color_bg = 'rgba(238, 40, 91, 0.08)';
            $color_border = 'rgba(238, 40, 91, 0.2)';
            $color_text = '#EE285B';
        } elseif ($term->taxonomy === 'product_tag') {
            $icon = 'bi-person-fill';
            $color_bg = 'rgba(255, 185, 0, 0.1)';
            $color_border = 'rgba(255, 185, 0, 0.25)';
            $color_text = '#d49700';
        } elseif ($term->taxonomy === 'pa_marca') {
            $icon = 'bi-tag-fill';
            $color_bg = 'rgba(13, 202, 240, 0.08)';
            $color_border = 'rgba(13, 202, 240, 0.2)';
            $color_text = '#0dcaf0';
        } else {
            $icon = 'bi-box-seam';
        }
    } else {
        // No es ninguna página que debamos personalizar
        return;
    }

    ?>
    <div class="ht-taxonomy-archive-header mb-4 p-3 rounded" style="background: linear-gradient(135deg, <?php echo $color_bg; ?>, <?php echo $color_bg; ?>); border: 2px solid <?php echo $color_border; ?>;">
        <div class="d-flex align-items-center gap-3 mb-0">
            <div class="taxonomy-icon" style="width: 60px; height: 60px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                <i class="<?php echo $icon; ?>" style="font-size: 1.8rem; color: <?php echo $color_text; ?>;"></i>
            </div>
            <div class="taxonomy-info flex-grow-1">
                <h1 class="taxonomy-title mb-0" style="font-size: 2rem; font-weight: 900; color: #292b2c; margin: 0;">
                    <?php echo esc_html($term_name); ?>
                </h1>
                <!--
                <div class="taxonomy-meta" style="color: #687188; font-size: 0.95rem;">
                    <i class="bi bi-box2" style="margin-right: 5px;"></i>
                    <strong><?php echo $term_count; ?></strong> productos disponibles
                </div>
                -->
            </div>
        </div>

        <?php if (!empty($term_description)): ?>
        <div class="taxonomy-description" style="color: #687188; line-height: 1.6; padding-left: 75px; font-size: 0.95rem;">
            <?php echo $term_description; ?>
        </div>
        <?php endif; ?>
    </div>
    <?php
}


/**
 * CSS para títulos de archivo de taxonomías y tienda
*/
/*
add_action('wp_head', 'ht_taxonomy_archive_header_styles');
function ht_taxonomy_archive_header_styles() {
    if (!is_shop() && !is_tax() && !is_product_category() && !is_product_tag()) {
        return;
    }
    ?>
    <style>
        .ht-taxonomy-archive-header {
            transition: all 0.3s ease;
        }

        .ht-taxonomy-archive-header:hover {
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .taxonomy-icon {
            transition: all 0.3s ease;
        }

        .ht-taxonomy-archive-header:hover .taxonomy-icon {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
        }

        .taxonomy-title {
            position: relative;
        }

        .taxonomy-title::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 60px;
            height: 4px;
            background: currentColor;
            opacity: 0.3;
            border-radius: 2px;
        }
 
        @media (max-width: 767.98px) {
            .ht-taxonomy-archive-header {
                padding: 1.5rem !important;
            }

            .taxonomy-icon {
                width: 50px !important;
                height: 50px !important;
            }

            .taxonomy-icon i {
                font-size: 1.5rem !important;
            }

            .taxonomy-title {
                font-size: 1.5rem !important;
            }

            .taxonomy-description {
                padding-left: 0 !important;
                margin-top: 1rem;
            }
        }
    </style>
    <?php
}
*/
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 50 );
