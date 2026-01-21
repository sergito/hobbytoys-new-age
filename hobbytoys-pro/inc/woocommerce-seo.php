<?php
/**
 * WooCommerce SEO Optimizations
 * Extreme SEO optimization for products and pages
 *
 * @package HobbyToys_Pro
 */

if (!defined('ABSPATH')) exit;

/**
 * =============================================================================
 * META TAGS OPTIMIZATION
 * =============================================================================
 */

/**
 * Add custom meta tags for products
 */
add_action('wp_head', 'ht_product_meta_tags', 1);
function ht_product_meta_tags() {
    if (!is_product()) return;

    global $product;

    $product_name = $product->get_name();
    $description = wp_strip_all_tags($product->get_short_description());
    $image_url = wp_get_attachment_image_url($product->get_image_id(), 'full');
    $price = $product->get_price();
    $availability = $product->is_in_stock() ? 'in stock' : 'out of stock';

    // Truncate description for meta
    if (strlen($description) > 155) {
        $description = substr($description, 0, 152) . '...';
    }

    // Open Graph tags
    echo '<meta property="og:type" content="product">' . "\n";
    echo '<meta property="og:title" content="' . esc_attr($product_name) . ' - HobbyToys">' . "\n";
    echo '<meta property="og:description" content="' . esc_attr($description) . '">' . "\n";
    echo '<meta property="og:image" content="' . esc_url($image_url) . '">' . "\n";
    echo '<meta property="og:url" content="' . esc_url(get_permalink()) . '">' . "\n";
    echo '<meta property="og:site_name" content="HobbyToys">' . "\n";
    echo '<meta property="product:price:amount" content="' . esc_attr($price) . '">' . "\n";
    echo '<meta property="product:price:currency" content="ARS">' . "\n";
    echo '<meta property="product:availability" content="' . esc_attr($availability) . '">' . "\n";

    // Twitter Card
    echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
    echo '<meta name="twitter:title" content="' . esc_attr($product_name) . ' - HobbyToys">' . "\n";
    echo '<meta name="twitter:description" content="' . esc_attr($description) . '">' . "\n";
    echo '<meta name="twitter:image" content="' . esc_url($image_url) . '">' . "\n";

    // Additional SEO meta
    echo '<meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">' . "\n";

    // Get categories for keywords
    $categories = get_the_terms($product->get_id(), 'product_cat');
    if ($categories && !is_wp_error($categories)) {
        $cat_names = wp_list_pluck($categories, 'name');
        $keywords = implode(', ', $cat_names) . ', juguetes, HobbyToys, ' . $product_name;
        echo '<meta name="keywords" content="' . esc_attr($keywords) . '">' . "\n";
    }
}

/**
 * Add custom meta tags for shop pages
 */
add_action('wp_head', 'ht_shop_meta_tags', 1);
function ht_shop_meta_tags() {
    if (!is_shop() && !is_product_category() && !is_product_tag()) return;

    $title = '';
    $description = '';

    if (is_shop()) {
        $title = 'Tienda de Juguetes Online - HobbyToys';
        $description = 'Comprá los mejores juguetes en HobbyToys. Envíos a todo el país. Gran variedad de juguetes educativos, didácticos y de entretenimiento para todas las edades.';
    } elseif (is_product_category()) {
        $category = get_queried_object();
        $title = 'Juguetes de ' . $category->name . ' - HobbyToys';
        $description = $category->description ? wp_strip_all_tags($category->description) : 'Comprá juguetes de ' . $category->name . ' en HobbyToys. Envíos a todo el país.';
    } elseif (is_product_tag()) {
        $tag = get_queried_object();
        $title = $tag->name . ' - Juguetes HobbyToys';
        $description = 'Explorá nuestra selección de juguetes ' . $tag->name . '. Calidad garantizada y envíos a todo el país.';
    }

    // Open Graph
    echo '<meta property="og:type" content="website">' . "\n";
    echo '<meta property="og:title" content="' . esc_attr($title) . '">' . "\n";
    echo '<meta property="og:description" content="' . esc_attr($description) . '">' . "\n";
    echo '<meta property="og:url" content="' . esc_url(get_permalink()) . '">' . "\n";
    echo '<meta property="og:site_name" content="HobbyToys">' . "\n";

    // Twitter Card
    echo '<meta name="twitter:card" content="summary">' . "\n";
    echo '<meta name="twitter:title" content="' . esc_attr($title) . '">' . "\n";
    echo '<meta name="twitter:description" content="' . esc_attr($description) . '">' . "\n";

    echo '<meta name="robots" content="index, follow">' . "\n";
}

/**
 * =============================================================================
 * STRUCTURED DATA (SCHEMA.ORG)
 * =============================================================================
 */

/**
 * Add breadcrumb schema
 */
add_action('wp_footer', 'ht_breadcrumb_schema');
function ht_breadcrumb_schema() {
    if (!is_product() && !is_product_category() && !is_shop()) return;

    $breadcrumbs = [];
    $position = 1;

    // Home
    $breadcrumbs[] = [
        '@type'    => 'ListItem',
        'position' => $position++,
        'name'     => 'Inicio',
        'item'     => home_url('/')
    ];

    // Shop
    if (is_product() || is_product_category()) {
        $breadcrumbs[] = [
            '@type'    => 'ListItem',
            'position' => $position++,
            'name'     => 'Tienda',
            'item'     => get_permalink(wc_get_page_id('shop'))
        ];
    }

    // Category
    if (is_product()) {
        global $product;
        $categories = get_the_terms($product->get_id(), 'product_cat');
        if ($categories && !is_wp_error($categories)) {
            foreach ($categories as $category) {
                if ($category->slug !== 'jugueteria' && $category->slug !== 'sin-categorizar') {
                    $breadcrumbs[] = [
                        '@type'    => 'ListItem',
                        'position' => $position++,
                        'name'     => $category->name,
                        'item'     => get_term_link($category)
                    ];
                    break;
                }
            }
        }

        // Product
        $breadcrumbs[] = [
            '@type'    => 'ListItem',
            'position' => $position,
            'name'     => $product->get_name(),
            'item'     => get_permalink()
        ];
    } elseif (is_product_category()) {
        $category = get_queried_object();
        $breadcrumbs[] = [
            '@type'    => 'ListItem',
            'position' => $position,
            'name'     => $category->name,
            'item'     => get_term_link($category)
        ];
    }

    $schema = [
        '@context'        => 'https://schema.org',
        '@type'           => 'BreadcrumbList',
        'itemListElement' => $breadcrumbs
    ];

    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
}

/**
 * Add organization schema
 */
add_action('wp_footer', 'ht_organization_schema');
function ht_organization_schema() {
    if (!is_front_page()) return;

    $schema = [
        '@context' => 'https://schema.org',
        '@type'    => 'Organization',
        'name'     => 'HobbyToys',
        'url'      => home_url('/'),
        'logo'     => get_theme_mod('custom_logo') ? wp_get_attachment_image_url(get_theme_mod('custom_logo'), 'full') : '',
        'sameAs'   => [
            // Add social media URLs here
            // 'https://www.facebook.com/hobbytoys',
            // 'https://www.instagram.com/hobbytoys',
        ],
        'contactPoint' => [
            '@type'       => 'ContactPoint',
            'telephone'   => '+54-221-560-8027',
            'contactType' => 'customer service',
            'areaServed'  => 'AR',
            'availableLanguage' => 'Spanish'
        ]
    ];

    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
}

/**
 * Add WebSite schema with search action
 */
add_action('wp_footer', 'ht_website_schema');
function ht_website_schema() {
    if (!is_front_page()) return;

    $schema = [
        '@context' => 'https://schema.org',
        '@type'    => 'WebSite',
        'name'     => 'HobbyToys',
        'url'      => home_url('/'),
        'potentialAction' => [
            '@type'       => 'SearchAction',
            'target'      => home_url('/?s={search_term_string}'),
            'query-input' => 'required name=search_term_string'
        ]
    ];

    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
}

/**
 * =============================================================================
 * SEO ENHANCEMENTS
 * =============================================================================
 */

/**
 * Optimize product titles for SEO
 */
add_filter('woocommerce_page_title', 'ht_custom_page_title');
function ht_custom_page_title($title) {
    if (is_shop()) {
        return 'Tienda de Juguetes Online';
    }

    if (is_product_category()) {
        $category = get_queried_object();
        return 'Juguetes de ' . $category->name;
    }

    return $title;
}

/**
 * Add canonical URL
 */
add_action('wp_head', 'ht_canonical_url', 1);
function ht_canonical_url() {
    if (is_singular('product')) {
        echo '<link rel="canonical" href="' . esc_url(get_permalink()) . '">' . "\n";
    } elseif (is_shop() || is_product_category() || is_product_tag()) {
        echo '<link rel="canonical" href="' . esc_url(get_permalink()) . '">' . "\n";
    }
}

/**
 * Optimize image alt tags for products
 */
add_filter('wp_get_attachment_image_attributes', 'ht_optimize_product_image_alt', 10, 3);
function ht_optimize_product_image_alt($attr, $attachment, $size) {
    // Only for product images
    if (!is_product() && !is_shop() && !is_product_category()) {
        return $attr;
    }

    // If alt is empty, generate SEO-friendly alt text
    if (empty($attr['alt'])) {
        $post_id = get_queried_object_id();
        if (is_product()) {
            $product = wc_get_product($post_id);
            if ($product) {
                $attr['alt'] = $product->get_name() . ' - Juguete de calidad - HobbyToys';
            }
        }
    }

    return $attr;
}

/**
 * =============================================================================
 * PERFORMANCE OPTIMIZATIONS
 * =============================================================================
 */

/**
 * Lazy load product images
 */
add_filter('wp_get_attachment_image_attributes', 'ht_lazy_load_product_images', 10, 3);
function ht_lazy_load_product_images($attr, $attachment, $size) {
    if (is_admin()) {
        return $attr;
    }

    // Add loading="lazy" for better performance
    if (!isset($attr['loading'])) {
        $attr['loading'] = 'lazy';
    }

    return $attr;
}

/**
 * Optimize product thumbnails
 */
add_filter('woocommerce_gallery_thumbnail_size', 'ht_gallery_thumbnail_size');
function ht_gallery_thumbnail_size($size) {
    return 'hobbytoys-product-card'; // Use our custom size
}

/**
 * =============================================================================
 * SITEMAP CUSTOMIZATIONS (if using Yoast or RankMath)
 * =============================================================================
 */

/**
 * Exclude out of stock products from sitemap
 */
add_filter('wpseo_sitemap_exclude_post_type', 'ht_exclude_out_of_stock_from_sitemap', 10, 2);
function ht_exclude_out_of_stock_from_sitemap($exclude, $post_type) {
    if ($post_type === 'product') {
        global $post;
        $product = wc_get_product($post->ID);

        if ($product && !$product->is_in_stock()) {
            return true; // Exclude from sitemap
        }
    }

    return $exclude;
}
