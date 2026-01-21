<?php
/**
 * Single Product Functions
 * Professional single product layout with extreme SEO
 *
 * @package HobbyToys_Pro
 */

if (!defined('ABSPATH')) exit;

/**
 * =============================================================================
 * SINGLE PRODUCT LAYOUT REORGANIZATION
 * =============================================================================
 */

/**
 * Remove default hooks
 */
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50);
remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10);

/**
 * Add custom single product structure
 *
 * New order:
 * 1. Breadcrumb
 * 2. Category badges
 * 3. Title (H1 with SEO)
 * 4. Rating
 * 5. Price
 * 6. Short description
 * 7. Key features (3 bullets)
 * 8. Variations / Add to cart
 * 9. Payment methods
 * 10. Shipping calculator
 * 11. Guarantees
 * 12. Full description (tabs)
 */

add_action('woocommerce_before_single_product_summary', 'ht_single_product_sale_badge', 15);
add_action('woocommerce_single_product_summary', 'ht_single_product_category_badges', 2);
add_action('woocommerce_single_product_summary', 'ht_single_product_title', 5);
add_action('woocommerce_single_product_summary', 'ht_single_product_rating', 7);
add_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
add_action('woocommerce_single_product_summary', 'ht_single_product_price_no_tax', 11);
add_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 15);
add_action('woocommerce_single_product_summary', 'ht_single_product_features', 18);
// add_to_cart stays at 30 (default)
add_action('woocommerce_single_product_summary', 'ht_single_product_payment_methods', 35);
add_action('woocommerce_single_product_summary', 'ht_single_product_shipping_calculator', 37);
add_action('woocommerce_single_product_summary', 'ht_single_product_guarantees', 40);

/**
 * =============================================================================
 * SINGLE PRODUCT COMPONENTS
 * =============================================================================
 */

/**
 * Sale badge
 */
function ht_single_product_sale_badge() {
    global $product;

    if ($product->is_on_sale()) {
        $regular_price = (float) $product->get_regular_price();
        $sale_price = (float) $product->get_sale_price();

        if ($regular_price > 0 && $sale_price > 0) {
            $percentage = round((($regular_price - $sale_price) / $regular_price) * 100);
            echo '<div class="ht-single-sale-badge">';
            echo '<span class="badge badge-sale-large">';
            echo '<i class="bi bi-tag-fill me-2"></i>' . $percentage . '% OFF';
            echo '</span>';
            echo '</div>';
        }
    }
}

/**
 * Category and age badges
 */
function ht_single_product_category_badges() {
    global $product;

    echo '<div class="ht-single-badges mb-3">';

    // Category badge
    $categories = get_the_terms($product->get_id(), 'product_cat');
    if ($categories && !is_wp_error($categories)) {
        $excluded = ['jugueteria', 'sin-categorizar', 'uncategorized'];

        foreach ($categories as $category) {
            if (!in_array($category->slug, $excluded)) {
                $cat_data = ht_get_category_data($category->slug);
                echo '<a href="' . esc_url(get_term_link($category)) . '" class="badge badge-category-large me-2" style="background-color: ' . esc_attr($cat_data['color']) . ';">';
                echo '<i class="bi ' . esc_attr($cat_data['icon']) . ' me-2"></i>';
                echo esc_html($cat_data['name']);
                echo '</a>';
                break;
            }
        }
    }

    // Age badges
    $ages = $product->get_attribute('pa_edades');
    if ($ages) {
        $age_terms = explode(', ', $ages);
        foreach (array_slice($age_terms, 0, 2) as $age_term) { // Max 2 age badges
            $age_slug = sanitize_title($age_term);
            $age_color = ht_get_age_color($age_slug);
            $age_label = str_replace('-', ' ', ucwords($age_term, '-'));

            echo '<span class="badge badge-age-large me-2" style="background-color: ' . esc_attr($age_color) . ';">';
            echo '<i class="bi bi-person-fill me-2"></i>' . esc_html($age_label);
            echo '</span>';
        }
    }

    // Stock status badge
    if ($product->is_in_stock()) {
        echo '<span class="badge badge-stock bg-success"><i class="bi bi-check-circle-fill me-2"></i>En Stock</span>';
    } else {
        echo '<span class="badge badge-stock bg-danger"><i class="bi bi-x-circle-fill me-2"></i>Sin Stock</span>';
    }

    echo '</div>';
}

/**
 * SEO optimized product title
 */
function ht_single_product_title() {
    global $product;

    $title = $product->get_name();
    $title = ucwords(strtolower($title)); // Title case

    echo '<h1 class="ht-single-title" itemprop="name">' . esc_html($title) . '</h1>';
}

/**
 * Product rating with reviews count
 */
function ht_single_product_rating() {
    global $product;

    if (!wc_review_ratings_enabled()) {
        return;
    }

    $rating_count = $product->get_rating_count();
    $average = $product->get_average_rating();

    echo '<div class="ht-single-rating mb-3">';

    if ($rating_count > 0) {
        echo '<div class="rating-wrapper d-flex align-items-center">';
        woocommerce_template_single_rating();
        echo '<a href="#reviews" class="ms-2 text-muted review-link">(' . esc_html($rating_count) . ' ' . _n('valoración', 'valoraciones', $rating_count, 'hobbytoys-pro') . ')</a>';
        echo '</div>';
    } else {
        echo '<div class="no-rating text-muted">';
        echo '<i class="bi bi-star me-1"></i>';
        echo '<span>Sin valoraciones aún</span>';
        echo '</div>';
    }

    echo '</div>';
}

/**
 * Price without tax (IVA)
 */
function ht_single_product_price_no_tax() {
    global $product;

    $price = $product->get_price();
    if (empty($price)) return;

    $price_no_tax = round($price / 1.21, 2);

    echo '<div class="ht-price-no-tax">';
    echo '<div class="price-box">';
    echo '<i class="bi bi-receipt me-2"></i>';
    echo '<span class="label">Precio sin IVA:</span> ';
    echo '<span class="amount">$' . number_format($price_no_tax, 2, ',', '.') . '</span>';
    echo '</div>';
    echo '</div>';
}

/**
 * Product key features (3 bullets)
 */
function ht_single_product_features() {
    global $product;

    $feature_1 = get_post_meta($product->get_id(), '_ht_feature_1', true);
    $feature_2 = get_post_meta($product->get_id(), '_ht_feature_2', true);
    $feature_3 = get_post_meta($product->get_id(), '_ht_feature_3', true);

    $features = array_filter([$feature_1, $feature_2, $feature_3]);

    if (empty($features)) return;

    echo '<div class="ht-single-features">';
    echo '<ul class="features-list">';
    foreach ($features as $feature) {
        echo '<li><i class="bi bi-check-circle-fill text-success me-2"></i>' . esc_html($feature) . '</li>';
    }
    echo '</ul>';
    echo '</div>';
}

/**
 * Payment methods and installments
 */
function ht_single_product_payment_methods() {
    global $product;

    $price = $product->get_price();
    if (empty($price)) return;

    $installment_6 = round($price / 6, 2);
    $installment_4 = round($price / 4, 2);
    $price_with_discount = round($price * 0.95, 2); // 5% OFF

    echo '<div class="ht-payment-methods">';
    echo '<h3 class="payment-title h6 mb-3"><i class="bi bi-credit-card me-2"></i>Formas de Pago</h3>';

    echo '<div class="payment-options">';

    // 6 cuotas sin interés
    echo '<div class="payment-option">';
    echo '<i class="bi bi-credit-card-2-front text-primary me-2"></i>';
    echo '<span class="payment-text">';
    echo '<strong>6 cuotas sin interés</strong> de <strong class="text-primary">$' . number_format($installment_6, 0, ',', '.') . '</strong>';
    echo '</span>';
    echo '</div>';

    // Banco Provincia
    echo '<div class="payment-option highlight">';
    echo '<i class="bi bi-star-fill text-warning me-2"></i>';
    echo '<span class="payment-text">';
    echo '<strong>Banco Provincia:</strong> 10% OFF + 4 cuotas de <strong>$' . number_format($installment_4, 0, ',', '.') . '</strong>';
    echo '</span>';
    echo '</div>';

    // Transferencia
    echo '<div class="payment-option">';
    echo '<i class="bi bi-bank text-success me-2"></i>';
    echo '<span class="payment-text">';
    echo '<strong>Transferencia:</strong> 5% OFF - <strong class="text-success">$' . number_format($price_with_discount, 0, ',', '.') . '</strong>';
    echo '</span>';
    echo '</div>';

    echo '</div>'; // .payment-options
    echo '</div>'; // .ht-payment-methods
}

/**
 * Shipping calculator
 */
function ht_single_product_shipping_calculator() {
    echo '<div class="ht-shipping-calculator">';
    echo '<h3 class="shipping-title h6 mb-3"><i class="bi bi-truck me-2"></i>Calcular Envío</h3>';

    echo '<div class="shipping-form">';
    echo '<div class="input-group">';
    echo '<input type="text" class="form-control" placeholder="Ingresá tu código postal" id="shipping-zip" maxlength="4" aria-label="Código postal">';
    echo '<button class="btn btn-outline-primary" type="button" id="calculate-shipping"><i class="bi bi-geo-alt-fill me-1"></i>Calcular</button>';
    echo '</div>';
    echo '<div id="shipping-results" class="mt-2"></div>';
    echo '</div>';

    echo '</div>';
}

/**
 * Product guarantees
 */
function ht_single_product_guarantees() {
    echo '<div class="ht-guarantees">';
    echo '<div class="row g-3">';

    // Guarantee 1: Secure payment
    echo '<div class="col-6 col-md-3">';
    echo '<div class="guarantee-item text-center">';
    echo '<i class="bi bi-shield-check guarantee-icon text-success"></i>';
    echo '<p class="guarantee-text mb-0"><small>Pago Seguro</small></p>';
    echo '</div>';
    echo '</div>';

    // Guarantee 2: Fast shipping
    echo '<div class="col-6 col-md-3">';
    echo '<div class="guarantee-item text-center">';
    echo '<i class="bi bi-truck guarantee-icon text-primary"></i>';
    echo '<p class="guarantee-text mb-0"><small>Envío Rápido</small></p>';
    echo '</div>';
    echo '</div>';

    // Guarantee 3: Original products
    echo '<div class="col-6 col-md-3">';
    echo '<div class="guarantee-item text-center">';
    echo '<i class="bi bi-award guarantee-icon text-warning"></i>';
    echo '<p class="guarantee-text mb-0"><small>100% Original</small></p>';
    echo '</div>';
    echo '</div>';

    // Guarantee 4: Returns
    echo '<div class="col-6 col-md-3">';
    echo '<div class="guarantee-item text-center">';
    echo '<i class="bi bi-arrow-repeat guarantee-icon text-info"></i>';
    echo '<p class="guarantee-text mb-0"><small>Cambios y Devoluciones</small></p>';
    echo '</div>';
    echo '</div>';

    echo '</div>'; // .row
    echo '</div>'; // .ht-guarantees
}

/**
 * =============================================================================
 * RELATED PRODUCTS BY CATEGORY
 * =============================================================================
 */

/**
 * Custom related products query
 */
add_filter('woocommerce_output_related_products_args', 'ht_related_products_args');
function ht_related_products_args($args) {
    $args['posts_per_page'] = 8; // Show 8 related products
    $args['columns'] = 4;
    return $args;
}

/**
 * Filter related products by same category
 */
add_filter('woocommerce_related_products', 'ht_filter_related_by_category', 10, 3);
function ht_filter_related_by_category($related_posts, $product_id, $args) {
    $product = wc_get_product($product_id);
    $categories = $product->get_category_ids();

    if (empty($categories)) {
        return $related_posts;
    }

    // Get products from same category
    $query_args = [
        'post_type'      => 'product',
        'posts_per_page' => $args['limit'],
        'post__not_in'   => [$product_id],
        'orderby'        => 'rand',
        'tax_query'      => [
            [
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    => $categories,
            ],
        ],
        'meta_query'     => [
            [
                'key'     => '_stock_status',
                'value'   => 'instock',
                'compare' => '=',
            ],
        ],
    ];

    $query = new WP_Query($query_args);
    $related_posts = wp_list_pluck($query->posts, 'ID');

    return $related_posts;
}

/**
 * Custom related products title
 */
add_filter('woocommerce_product_related_products_heading', 'ht_related_products_heading');
function ht_related_products_heading() {
    global $product;

    $categories = get_the_terms($product->get_id(), 'product_cat');
    if ($categories && !is_wp_error($categories)) {
        $excluded = ['jugueteria', 'sin-categorizar', 'uncategorized'];

        foreach ($categories as $category) {
            if (!in_array($category->slug, $excluded)) {
                return 'Más productos en ' . $category->name;
            }
        }
    }

    return 'Productos Relacionados';
}

/**
 * =============================================================================
 * SEO SCHEMA MARKUP
 * =============================================================================
 */

/**
 * Add comprehensive structured data for single product
 */
add_action('wp_footer', 'ht_single_product_schema');
function ht_single_product_schema() {
    if (!is_product()) return;

    global $product;

    $schema = [
        '@context'    => 'https://schema.org/',
        '@type'       => 'Product',
        'name'        => $product->get_name(),
        'image'       => wp_get_attachment_image_url($product->get_image_id(), 'full'),
        'description' => wp_strip_all_tags($product->get_description()),
        'sku'         => $product->get_sku(),
        'mpn'         => $product->get_id(),
        'brand'       => [
            '@type' => 'Brand',
            'name'  => 'HobbyToys'
        ],
        'offers'      => [
            '@type'           => 'Offer',
            'url'             => get_permalink($product->get_id()),
            'priceCurrency'   => 'ARS',
            'price'           => $product->get_price(),
            'priceValidUntil' => date('Y-12-31'),
            'availability'    => $product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
            'itemCondition'   => 'https://schema.org/NewCondition',
            'seller'          => [
                '@type' => 'Organization',
                'name'  => 'HobbyToys',
                'url'   => home_url()
            ]
        ]
    ];

    // Add rating
    if ($product->get_average_rating() > 0) {
        $schema['aggregateRating'] = [
            '@type'       => 'AggregateRating',
            'ratingValue' => $product->get_average_rating(),
            'reviewCount' => $product->get_rating_count(),
            'bestRating'  => 5,
            'worstRating' => 1
        ];
    }

    // Add reviews
    $reviews = get_comments([
        'post_id' => $product->get_id(),
        'status'  => 'approve',
        'type'    => 'review',
        'number'  => 5,
    ]);

    if (!empty($reviews)) {
        $schema['review'] = [];
        foreach ($reviews as $review) {
            $rating = get_comment_meta($review->comment_ID, 'rating', true);
            if ($rating) {
                $schema['review'][] = [
                    '@type'         => 'Review',
                    'reviewRating'  => [
                        '@type'       => 'Rating',
                        'ratingValue' => $rating,
                        'bestRating'  => 5
                    ],
                    'author'        => [
                        '@type' => 'Person',
                        'name'  => $review->comment_author
                    ],
                    'datePublished' => date('Y-m-d', strtotime($review->comment_date)),
                    'reviewBody'    => $review->comment_content
                ];
            }
        }
    }

    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . '</script>';
}
