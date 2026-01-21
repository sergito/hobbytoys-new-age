<?php
/**
 * Product Card Functions
 * Ultra professional product cards with extreme SEO optimization
 *
 * @package HobbyToys_Pro
 */

if (!defined('ABSPATH')) exit;

/**
 * =============================================================================
 * PRODUCT CARD STRUCTURE
 * =============================================================================
 */

/**
 * Remove default WooCommerce hooks for product loop
 */
remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10);
remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);
remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);
remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);
remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);
remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);

/**
 * Add custom product card structure
 */
add_action('woocommerce_before_shop_loop_item', 'ht_product_card_wrapper_start', 5);
add_action('woocommerce_before_shop_loop_item', 'ht_product_card_image_wrapper_start', 10);
add_action('woocommerce_before_shop_loop_item_title', 'ht_product_card_badges', 5);
add_action('woocommerce_before_shop_loop_item_title', 'ht_product_card_image', 10);
add_action('woocommerce_before_shop_loop_item_title', 'ht_product_card_quick_actions', 15);
add_action('woocommerce_before_shop_loop_item_title', 'ht_product_card_image_wrapper_end', 20);
add_action('woocommerce_shop_loop_item_title', 'ht_product_card_content_wrapper_start', 5);
add_action('woocommerce_shop_loop_item_title', 'ht_product_card_category', 7);
add_action('woocommerce_shop_loop_item_title', 'ht_product_card_title', 10);
add_action('woocommerce_after_shop_loop_item_title', 'ht_product_card_rating', 5);
add_action('woocommerce_after_shop_loop_item_title', 'ht_product_card_short_description', 7);
add_action('woocommerce_after_shop_loop_item_title', 'ht_product_card_features', 8);
add_action('woocommerce_after_shop_loop_item_title', 'ht_product_card_price', 10);
add_action('woocommerce_after_shop_loop_item_title', 'ht_product_card_payment_info', 12);
add_action('woocommerce_after_shop_loop_item', 'ht_product_card_add_to_cart', 10);
add_action('woocommerce_after_shop_loop_item', 'ht_product_card_content_wrapper_end', 15);
add_action('woocommerce_after_shop_loop_item', 'ht_product_card_wrapper_end', 20);

/**
 * Card wrapper start
 */
function ht_product_card_wrapper_start() {
    global $product;
    $product_id = $product->get_id();

    echo '<div class="ht-product-card" data-product-id="' . esc_attr($product_id) . '" itemscope itemtype="https://schema.org/Product">';
}

/**
 * Card wrapper end
 */
function ht_product_card_wrapper_end() {
    echo '</div><!-- .ht-product-card -->';
}

/**
 * Image wrapper start
 */
function ht_product_card_image_wrapper_start() {
    echo '<div class="ht-product-card__image-wrapper">';
}

/**
 * Image wrapper end
 */
function ht_product_card_image_wrapper_end() {
    echo '</div><!-- .ht-product-card__image-wrapper -->';
}

/**
 * Content wrapper start
 */
function ht_product_card_content_wrapper_start() {
    echo '<div class="ht-product-card__content">';
}

/**
 * Content wrapper end
 */
function ht_product_card_content_wrapper_end() {
    echo '</div><!-- .ht-product-card__content -->';
}

/**
 * =============================================================================
 * PRODUCT CARD COMPONENTS
 * =============================================================================
 */

/**
 * Product badges (category, age, sale)
 */
function ht_product_card_badges() {
    global $product;

    echo '<div class="ht-product-card__badges">';

    // Sale badge
    if ($product->is_on_sale()) {
        $regular_price = (float) $product->get_regular_price();
        $sale_price = (float) $product->get_sale_price();

        if ($regular_price > 0 && $sale_price > 0) {
            $percentage = round((($regular_price - $sale_price) / $regular_price) * 100);
            echo '<span class="badge badge-sale"><i class="bi bi-tag-fill me-1"></i>' . $percentage . '% OFF</span>';
        }
    }

    // Category badge
    $categories = get_the_terms($product->get_id(), 'product_cat');
    if ($categories && !is_wp_error($categories)) {
        // Exclude generic categories
        $excluded = ['jugueteria', 'sin-categorizar', 'uncategorized'];

        foreach ($categories as $category) {
            if (!in_array($category->slug, $excluded)) {
                $cat_data = ht_get_category_data($category->slug);
                echo '<span class="badge badge-category" style="background-color: ' . esc_attr($cat_data['color']) . ';">';
                echo '<i class="bi ' . esc_attr($cat_data['icon']) . ' me-1"></i>';
                echo esc_html($cat_data['name']);
                echo '</span>';
                break; // Only show first valid category
            }
        }
    }

    // Age badge
    $ages = $product->get_attribute('pa_edades');
    if ($ages) {
        $age_terms = explode(', ', $ages);
        foreach ($age_terms as $age_term) {
            $age_slug = sanitize_title($age_term);
            $age_color = ht_get_age_color($age_slug);
            $age_label = str_replace('-', ' ', ucwords($age_term, '-'));

            echo '<span class="badge badge-age" style="background-color: ' . esc_attr($age_color) . ';">';
            echo '<i class="bi bi-person-fill me-1"></i>' . esc_html($age_label);
            echo '</span>';
        }
    }

    // Free shipping badge
    $cart_total = 90000; // Default minimum
    $free_shipping_min = get_theme_mod('ht_free_shipping_min', 90000);

    if ($product->get_price() >= $free_shipping_min) {
        echo '<span class="badge badge-shipping"><i class="bi bi-truck me-1"></i>Envío Gratis</span>';
    }

    echo '</div><!-- .ht-product-card__badges -->';
}

/**
 * Product image with SEO
 */
function ht_product_card_image() {
    global $product;

    $image_id = $product->get_image_id();
    $product_name = $product->get_name();
    $permalink = get_permalink($product->get_id());

    echo '<a href="' . esc_url($permalink) . '" class="ht-product-card__image-link" aria-label="' . esc_attr($product_name) . '">';

    if ($image_id) {
        $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
        if (empty($image_alt)) {
            $image_alt = $product_name . ' - Juguete de calidad en HobbyToys';
        }

        echo wp_get_attachment_image(
            $image_id,
            'hobbytoys-product-card',
            false,
            [
                'class'    => 'ht-product-card__image',
                'alt'      => $image_alt,
                'itemprop' => 'image',
                'loading'  => 'lazy',
            ]
        );

        // Schema.org image
        $image_url = wp_get_attachment_image_url($image_id, 'hobbytoys-product-card');
        echo '<meta itemprop="image" content="' . esc_url($image_url) . '">';
    } else {
        echo wc_placeholder_img('hobbytoys-product-card', 'ht-product-card__image');
    }

    echo '</a>';
}

/**
 * Quick actions (wishlist, quick view)
 */
function ht_product_card_quick_actions() {
    global $product;
    $product_id = $product->get_id();

    echo '<div class="ht-product-card__quick-actions">';

    // Wishlist button
    echo '<button type="button" class="btn-quick-action btn-wishlist" data-product-id="' . esc_attr($product_id) . '" aria-label="Agregar a lista de deseos">';
    echo '<i class="bi bi-heart"></i>';
    echo '</button>';

    // Quick view button (for future implementation)
    // echo '<button type="button" class="btn-quick-action btn-quick-view" data-product-id="' . esc_attr($product_id) . '" aria-label="Vista rápida">';
    // echo '<i class="bi bi-eye"></i>';
    // echo '</button>';

    echo '</div>';
}

/**
 * Product category link
 */
function ht_product_card_category() {
    global $product;

    $categories = get_the_terms($product->get_id(), 'product_cat');
    if ($categories && !is_wp_error($categories)) {
        $excluded = ['jugueteria', 'sin-categorizar', 'uncategorized'];

        foreach ($categories as $category) {
            if (!in_array($category->slug, $excluded)) {
                echo '<a href="' . esc_url(get_term_link($category)) . '" class="ht-product-card__category" itemprop="category">';
                echo esc_html($category->name);
                echo '</a>';
                break;
            }
        }
    }
}

/**
 * Product title with SEO
 */
function ht_product_card_title() {
    global $product;

    $title = $product->get_name();
    $permalink = get_permalink($product->get_id());

    // Convert to title case
    $title = ucwords(strtolower($title));

    echo '<h2 class="ht-product-card__title" itemprop="name">';
    echo '<a href="' . esc_url($permalink) . '" class="ht-product-card__title-link">';
    echo esc_html($title);
    echo '</a>';
    echo '</h2>';

    // Schema.org URL
    echo '<meta itemprop="url" content="' . esc_url($permalink) . '">';
}

/**
 * Product rating
 */
function ht_product_card_rating() {
    global $product;

    if (!wc_review_ratings_enabled()) {
        return;
    }

    $rating_count = $product->get_rating_count();
    $average = $product->get_average_rating();

    if ($rating_count > 0) {
        echo '<div class="ht-product-card__rating" itemprop="aggregateRating" itemscope itemtype="https://schema.org/AggregateRating">';
        echo '<div class="star-rating" role="img" aria-label="Calificación: ' . esc_attr($average) . ' de 5 estrellas">';
        echo '<span style="width:' . (($average / 5) * 100) . '%">';
        echo '<strong itemprop="ratingValue">' . esc_html($average) . '</strong> de <span itemprop="bestRating">5</span>';
        echo '</span>';
        echo '</div>';
        echo '<span class="rating-count">(<span itemprop="reviewCount">' . esc_html($rating_count) . '</span>)</span>';
        echo '</div>';
    }
}

/**
 * Product short description
 */
function ht_product_card_short_description() {
    global $product;

    $short_desc = $product->get_short_description();

    if ($short_desc) {
        // Limit to 80 characters
        $short_desc = wp_trim_words($short_desc, 15, '...');
        echo '<p class="ht-product-card__description" itemprop="description">' . wp_kses_post($short_desc) . '</p>';
    }
}

/**
 * Product key features
 */
function ht_product_card_features() {
    global $product;

    // Get custom meta features (you'll need to add these in admin)
    $feature_1 = get_post_meta($product->get_id(), '_ht_feature_1', true);
    $feature_2 = get_post_meta($product->get_id(), '_ht_feature_2', true);

    $features = array_filter([$feature_1, $feature_2]);

    if (!empty($features)) {
        echo '<ul class="ht-product-card__features">';
        foreach ($features as $feature) {
            echo '<li><i class="bi bi-check-circle-fill text-success me-1"></i>' . esc_html($feature) . '</li>';
        }
        echo '</ul>';
    }
}

/**
 * Product price with SEO
 */
function ht_product_card_price() {
    global $product;

    echo '<div class="ht-product-card__price" itemprop="offers" itemscope itemtype="https://schema.org/Offer">';
    echo $product->get_price_html();

    // Schema.org price data
    echo '<meta itemprop="price" content="' . esc_attr($product->get_price()) . '">';
    echo '<meta itemprop="priceCurrency" content="ARS">';

    // Availability
    $availability = $product->is_in_stock() ? 'InStock' : 'OutOfStock';
    echo '<link itemprop="availability" href="https://schema.org/' . $availability . '">';

    // Seller
    echo '<div itemprop="seller" itemscope itemtype="https://schema.org/Organization">';
    echo '<meta itemprop="name" content="HobbyToys">';
    echo '</div>';

    echo '</div>';
}

/**
 * Payment info (installments, discounts)
 */
function ht_product_card_payment_info() {
    global $product;

    $price = $product->get_price();
    if (empty($price)) return;

    echo '<div class="ht-product-card__payment-info">';

    // Installments (6 cuotas sin interés)
    $installment_amount = round($price / 6, 2);
    echo '<div class="payment-installments">';
    echo '<i class="bi bi-credit-card me-1"></i>';
    echo '<span class="fw-semibold">6 cuotas</span> de <span class="fw-bold text-primary">$' . number_format($installment_amount, 0, ',', '.') . '</span>';
    echo '</div>';

    // Bank discount (Banco Provincia: 10% + 4 cuotas)
    echo '<div class="payment-bank">';
    echo '<i class="bi bi-star-fill me-1 text-warning"></i>';
    echo '<small>Banco Provincia: <strong>10% OFF + 4 cuotas</strong></small>';
    echo '</div>';

    echo '</div>';
}

/**
 * Add to cart button
 */
function ht_product_card_add_to_cart() {
    global $product;

    echo '<div class="ht-product-card__actions">';

    woocommerce_template_loop_add_to_cart();

    echo '</div>';
}

/**
 * =============================================================================
 * SEO ENHANCEMENTS
 * =============================================================================
 */

/**
 * Add structured data for product
 */
add_action('woocommerce_after_shop_loop_item', 'ht_product_card_structured_data', 25);
function ht_product_card_structured_data() {
    global $product;

    $schema = [
        '@context'    => 'https://schema.org/',
        '@type'       => 'Product',
        'name'        => $product->get_name(),
        'image'       => wp_get_attachment_image_url($product->get_image_id(), 'full'),
        'description' => wp_strip_all_tags($product->get_short_description()),
        'sku'         => $product->get_sku(),
        'brand'       => [
            '@type' => 'Brand',
            'name'  => 'HobbyToys'
        ],
        'offers'      => [
            '@type'         => 'Offer',
            'url'           => get_permalink($product->get_id()),
            'priceCurrency' => 'ARS',
            'price'         => $product->get_price(),
            'availability'  => $product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
            'seller'        => [
                '@type' => 'Organization',
                'name'  => 'HobbyToys'
            ]
        ]
    ];

    // Add rating if available
    if ($product->get_average_rating() > 0) {
        $schema['aggregateRating'] = [
            '@type'       => 'AggregateRating',
            'ratingValue' => $product->get_average_rating(),
            'reviewCount' => $product->get_rating_count(),
            'bestRating'  => 5
        ];
    }

    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
}
