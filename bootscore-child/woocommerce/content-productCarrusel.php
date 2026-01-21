<?php

/**
 * The template for displaying product content within carousel/swiper loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 9.4.0
 */

defined('ABSPATH') || exit;

global $product;

// Check if the product is a valid WooCommerce product and ensure its visibility before proceeding.
if ( ! is_a( $product, WC_Product::class ) || ! $product->is_visible() ) {
	return;
}

// Configuración de Bootstrap Icons y colores únicos por categoría (usando colores SCSS del tema)
$category_config = [
    // Categorías principales con Bootstrap Icons y colores únicos
    'aire-libre' => ['icon' => 'bi-sun-fill', 'color' => '#0dcaf0'], // cyan
    'arte-manualidades' => ['icon' => 'bi-palette-fill', 'color' => '#EE285B'], // red
    'arte-y-manualidades' => ['icon' => 'bi-palette-fill', 'color' => '#EE285B'], // red (alias)
    'bebes' => ['icon' => 'bi-heart-fill', 'color' => '#d63384'], // pink
    'bloques-y-ladrillos' => ['icon' => 'bi-grid-3x3-gap-fill', 'color' => '#fd7e14'], // orange
    'bloques-construccion' => ['icon' => 'bi-grid-3x3-gap-fill', 'color' => '#fd7e14'], // orange (alias)
    'casas-carpas-y-peloteros' => ['icon' => 'bi-house-door-fill', 'color' => '#20c997'], // teal
    'instrumentos-y-microfonos' => ['icon' => 'bi-music-note-beamed', 'color' => '#534fb5'], // purple
    'musica-sonido' => ['icon' => 'bi-speaker-fill', 'color' => '#534fb5'], // purple (alias)
    'juegos-de-mesa' => ['icon' => 'bi-dice-5-fill', 'color' => '#ffb900'], // yellow
    'juegos-de-rol' => ['icon' => 'bi-people-fill', 'color' => '#6610f2'], // indigo
    'munecas-munecos-y-accesorios' => ['icon' => 'bi-person-hearts', 'color' => '#FF6B9D'], // pink claro
    'munecas-accesorios' => ['icon' => 'bi-person-hearts', 'color' => '#FF6B9D'], // pink claro (alias)
    'pistas-y-vehiculos' => ['icon' => 'bi-truck-front-fill', 'color' => '#0d6efd'], // blue
    'vehiculos' => ['icon' => 'bi-car-front-fill', 'color' => '#0d6efd'], // blue (alias)
    'vehiculos-rc' => ['icon' => 'bi-car-front-fill', 'color' => '#0d6efd'], // blue (alias)
    'pistolas-y-espadas' => ['icon' => 'bi-shield-fill-exclamation', 'color' => '#198754'], // green
    'rodados' => ['icon' => 'bi-bicycle', 'color' => '#4DB6AC'], // teal claro
    'peluches' => ['icon' => 'bi-balloon-heart-fill', 'color' => '#FF5722'], // deep orange
    'juguetes-didacticos' => ['icon' => 'bi-puzzle-fill', 'color' => '#4CAF50'], // green claro
    'figuras-accion' => ['icon' => 'bi-robot', 'color' => '#9C27B0'], // deep purple
    'deportes' => ['icon' => 'bi-trophy-fill', 'color' => '#00BCD4'], // light blue
];

// Configuración de colores por edad (usando colores SCSS del tema) - SLUGS REALES DE WORDPRESS
$age_colors = [
    '0-a-3-anos' => '#EE285B',      // 0 a 3 años - rojo
    '4-a-6-anos' => '#0dcaf0',      // 4 a 6 años - cyan
    '7-a-12-anos' => '#ffb900',     // 7 a 12 años - amarillo
    '12adultos' => '#198754',       // +12 y adultos - verde
];

// Obtener categorías del producto
$product_cats = get_the_terms($product->get_id(), 'product_cat');
$first_category = null;
$category_data = null;

if ($product_cats && !is_wp_error($product_cats)) {
    foreach ($product_cats as $cat) {
        // Excluir categoría "JUGUETERIA"
        if (strtoupper($cat->name) !== 'JUGUETERIA' && strtoupper($cat->name) !== 'SIN CATEGORIZAR') {
            if (isset($category_config[$cat->slug])) {
                $first_category = $cat;
                $category_data = $category_config[$cat->slug];
                break;
            }
        }
    }
    // Si no encontró coincidencia, usar la primera categoría con valores por defecto
    // DEBUG: Mostrar el slug real para poder agregarlo a la configuración
    if (!$first_category && !empty($product_cats)) {
        foreach ($product_cats as $cat) {
            if (strtoupper($cat->name) !== 'JUGUETERIA' && strtoupper($cat->name) !== 'SIN CATEGORIZAR') {
                $first_category = $cat;
                // Generar color único basado en el slug para evitar mezclas
                $hash = crc32($cat->slug);
                $colors = ['#EE285B', '#0dcaf0', '#ffb900', '#534fb5', '#198754', '#fd7e14', '#d63384', '#6610f2', '#20c997', '#FF6B9D', '#0d6efd', '#4CAF50', '#9C27B0', '#00BCD4', '#FF5722', '#4DB6AC'];
                $category_data = [
                    'icon' => 'bi-box-seam',
                    'color' => $colors[abs($hash) % count($colors)]
                ];
                break;
            }
        }
    }
}

// Obtener TODAS las edades del producto (atributo pa_edades) - puede tener múltiples
$edad_terms = get_the_terms($product->get_id(), 'pa_edades');
$edades = []; // Array para guardar todas las edades

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

// Calcular cuotas sin interés para TODOS los productos
$precio = floatval($product->get_price());
$cuota_sin_interes = $precio / 6;

// Detectar envío gratis según zona
$envio_gratis_laplata = $precio >= 50000;
$envio_gratis_nacional = $precio >= 90000;

// Detectar si es un producto nuevo (últimos 30 días)
$product_date = strtotime($product->get_date_created());
$dias_desde_creacion = (time() - $product_date) / (60 * 60 * 24);
$es_nuevo = $dias_desde_creacion <= 30;
?>

<div class="swiper-slide">
  <div <?php wc_product_class( apply_filters( 'bootscore/class/woocommerce/product/card', 'card h-100 position-relative product-card-improved' ), $product ); ?>>

    <!-- Badges de Edad - Esquina Superior Izquierda (pueden ser múltiples) -->
    <?php if (!empty($edades)): ?>
    <div class="product-badge-age position-absolute top-0 start-0 p-2 d-flex flex-column gap-2" style="z-index: 15;">
      <?php foreach ($edades as $edad): ?>
        <a href="<?php echo esc_url($edad['link']); ?>" class="badge rounded-pill text-decoration-none"
           style="background-color: <?php echo esc_attr($edad['color']); ?> !important; color: white !important; font-weight: 700; font-size: 0.65rem; padding: 0.4rem 0.6rem; box-shadow: 0 2px 8px rgba(0,0,0,0.2); transition: all 0.2s;">
          <i class="bi bi-person-fill" style="font-size: 0.7rem; margin-right: 3px;"></i><?php echo esc_html($edad['display']); ?>
        </a>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Badges superiores derecha (Oferta, Nuevo) -->
    <div class="product-badges-top position-absolute top-0 end-0 p-2 d-flex flex-column align-items-end gap-2" style="z-index: 10;">
      <?php if ($product->is_on_sale() && $product->is_type('simple')):
        $regular = (float) $product->get_regular_price();
        $sale = (float) $product->get_sale_price();
        if ($regular > 0 && $sale > 0 && $sale < $regular):
          $percentage = round((($regular - $sale) / $regular) * 100);
      ?>
        <span class="badge bg-danger text-white fw-bold shadow-sm" style="font-size: 0.85rem; padding: 0.5rem 0.75rem;">
          -<?php echo $percentage; ?>% OFF
        </span>
      <?php endif; endif; ?>

      <?php if ($es_nuevo): ?>
        <span class="badge bg-success text-white fw-bold shadow-sm" style="font-size: 0.75rem; padding: 0.4rem 0.7rem;">
          ¡NUEVO!
        </span>
      <?php endif; ?>
    </div>

    <?php
    /**
     * Hook: woocommerce_before_shop_loop_item.
     *
     * @hooked woocommerce_template_loop_product_link_open - 10
     */
    do_action('woocommerce_before_shop_loop_item');

    /**
     * Hook: woocommerce_before_shop_loop_item_title.
     *
     * @hooked woocommerce_show_product_loop_sale_flash - 10
     * @hooked woocommerce_template_loop_product_thumbnail - 10
     */
    do_action('woocommerce_before_shop_loop_item_title');

    ?>

    <!-- Línea separadora entre imagen y cuerpo -->
    <div class="card-image-separator" style="height: 1px; background: linear-gradient(90deg, transparent, #dee2e6 20%, #dee2e6 80%, transparent); margin: 0;"></div>

    <div class="<?= apply_filters('bootscore/class/woocommerce/product/card/card-body', 'card-body d-flex flex-column'); ?>">

      <!-- Badge de categoría -->
      <?php if ($first_category && $category_data): ?>
      <div class="product-meta-badges d-flex flex-wrap gap-2 mb-3">
        <a href="<?php echo esc_url(get_term_link($first_category)); ?>" class="badge rounded-pill text-decoration-none d-flex align-items-center gap-1"
           style="background-color: <?php echo esc_attr($category_data['color']); ?> !important; color: white !important; font-weight: 700; font-size: 0.75rem; padding: 0.4rem 0.8rem; box-shadow: 0 2px 6px rgba(0,0,0,0.12); transition: all 0.2s; width: fit-content;">
          <i class="<?php echo esc_attr($category_data['icon']); ?>" style="font-size: 0.85rem;"></i>
          <span><?php echo esc_html($first_category->name); ?></span>
        </a>
      </div>
      <?php endif; ?>

      <?php
      /**
       * Hook: woocommerce_shop_loop_item_title.
       *
       * @hooked woocommerce_template_loop_product_title - 10
       */
      do_action('woocommerce_shop_loop_item_title');

      /**
       * Hook: woocommerce_after_shop_loop_item_title.
       *
       * @hooked woocommerce_template_loop_rating - 5
       * @hooked woocommerce_template_loop_price - 10
       */
      do_action('woocommerce_after_shop_loop_item_title');

      // Mostrar precio sin IVA
      if ($precio > 0) {
        $precio_sin_iva = $precio / 1.21;
        echo '<div class="precio-sin-iva mt-2 mb-3" style="font-size: 0.85rem; color: #666; font-weight: 500;">';
        echo '<i class="bi bi-receipt" style="font-size: 0.8rem; margin-right: 4px;"></i>';
        echo 'Sin IVA: <span style="font-weight: 700; color: #EE285B;">$' . number_format($precio_sin_iva, 2, ',', '.') . '</span>';
        echo '</div>';
      }

      // Mostrar cuotas sin interés para TODOS los productos (en una línea compacta)
      if ($precio > 0) {
        echo '<div class="cuotas-sin-interes mb-2 px-2 py-1 rounded d-inline-block" style="background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); border: 1px solid #0dcaf0; font-size: 0.8rem;">';
        echo '<i class="bi bi-credit-card text-info" style="font-size: 0.75rem; margin-right: 3px;"></i>';
        echo '<span style="font-weight: 600; color: #0369a1;">6 cuotas de $' . number_format($cuota_sin_interes, 2, ',', '.') . '</span>';
        echo '</div>';
      }

      // Mostrar badge de envío gratis debajo de las cuotas
      /* if ($envio_gratis_nacional || $envio_gratis_laplata) {
        echo '<div class="mb-3">';
        if ($envio_gratis_nacional) {
          echo '<span class="badge text-white fw-bold d-inline-block" style="background-color: #198754 !important; color: white !important; font-size: 0.75rem; padding: 0.4rem 0.7rem;">';
          echo '<i class="bi bi-truck" style="font-size: 0.75rem; margin-right: 3px;"></i>ENVÍO GRATIS';
          echo '</span>';
        } elseif ($envio_gratis_laplata) {
          echo '<span class="badge text-white fw-bold d-inline-block" style="background-color: #198754 !important; color: white !important; font-size: 0.75rem; padding: 0.4rem 0.7rem;">';
          echo '<i class="bi bi-truck" style="font-size: 0.75rem; margin-right: 3px;"></i>GRATIS LP';
          echo '</span>';
        }
        echo '</div>';
      } */

      /**
       * Hook: woocommerce_after_shop_loop_item.
       *
       * @hooked woocommerce_template_loop_product_link_close - 5
       * @hooked woocommerce_template_loop_add_to_cart - 10
       */
      do_action('woocommerce_after_shop_loop_item');
      ?>
    </div>
  </div>
</div>
