/**
 * Wishlist Simple - Lista de Deseos
 * Versión funcional integrada con WooCommerce
 */

(function($) {
    'use strict';

    let wishlist = [];
    const STORAGE_KEY = 'hobbytoys_wishlist';

    // Inicializar wishlist
    function initWishlist() {
        loadWishlist();
        createWishlistButton();
        addHeartButtons();

        // Event listeners
        $(document).on('click', '.wishlist-heart-btn', toggleWishlist);
        $(document).on('click', '#wishlistFloatBtn', goToWishlistPage);
    }

    // Cargar wishlist desde LocalStorage
    function loadWishlist() {
        const saved = localStorage.getItem(STORAGE_KEY);
        wishlist = saved ? JSON.parse(saved) : [];
    }

    // Guardar wishlist en LocalStorage
    function saveWishlist() {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(wishlist));
    }

    // Crear botón flotante de wishlist
    function createWishlistButton() {
        const buttonHTML = `
        <div id="wishlistFloatBtn"
             style="position: fixed; bottom: 100px; right: 20px; z-index: 998;
                    width: 60px; height: 60px; border-radius: 50%;
                    background: linear-gradient(135deg, #EE285B, #C41E3A);
                    box-shadow: 0 4px 20px rgba(238, 40, 91, 0.4);
                    display: flex; align-items: center; justify-content: center;
                    cursor: pointer; transition: all 0.3s ease;">
            <i class="bi bi-heart-fill" style="font-size: 1.6rem; color: white;"></i>
            <span class="wishlist-count badge bg-light text-dark position-absolute"
                  style="top: -5px; right: -5px; min-width: 24px; height: 24px;
                         border-radius: 12px; font-size: 0.75rem; font-weight: 700;
                         display: flex; align-items: center; justify-content: center;">0</span>
        </div>
        `;

        $('body').append(buttonHTML);
        updateWishlistCount();
    }

    // Agregar botones de corazón a todos los productos
    function addHeartButtons() {
        // Agregar a productos de WooCommerce
        $('.products .product, .product-card').each(function() {
            const $product = $(this);

            // Evitar duplicados
            if ($product.find('.wishlist-heart-btn').length > 0) {
                return;
            }

            // Obtener datos del producto
            const $link = $product.find('.woocommerce-loop-product__link, .product-link');
            const productUrl = $link.attr('href') || '#';
            const productId = extractProductId(productUrl);
            const productName = $product.find('.woocommerce-loop-product__title, .product-title, h2, h3').first().text().trim();
            const productImage = $product.find('img').first().attr('src') || '';
            const priceText = $product.find('.price, .product-price').first().text().trim();

            // Crear botón de corazón (más pequeño, inline)
            const isInWishlist = wishlist.some(item => item.id === productId);
            const heartClass = isInWishlist ? 'bi-heart-fill' : 'bi-heart';
            const heartColor = isInWishlist ? '#EE285B' : '#999';

            const heartButton = `
            <button class="wishlist-heart-btn btn btn-outline-secondary"
                    data-product-id="${productId}"
                    data-product-name="${productName}"
                    data-product-image="${productImage}"
                    data-product-price="${priceText}"
                    data-product-url="${productUrl}"
                    title="Agregar a favoritos"
                    style="padding: 0.5rem 0.75rem; border-radius: 0.5rem;">
                <i class="bi ${heartClass}" style="font-size: 1.1rem; color: ${heartColor};"></i>
            </button>
            `;

            // Buscar el botón "Agregar al carrito"
            const $addToCartBtn = $product.find('.add_to_cart_button, .product_type_simple, .product_type_variable, .button.product_type_simple, a.button');

            if ($addToCartBtn.length > 0) {
                // Cambiar texto del botón a solo "Agregar" con icono
                const originalHref = $addToCartBtn.attr('href');
                const classes = $addToCartBtn.attr('class');
                const dataAttributes = $addToCartBtn.get(0) ? Array.from($addToCartBtn.get(0).attributes)
                    .filter(attr => attr.name.startsWith('data-'))
                    .map(attr => `${attr.name}="${attr.value}"`)
                    .join(' ') : '';

                // Crear contenedor flex para ambos botones
                const buttonContainer = `
                <div class="product-buttons d-flex gap-2" style="margin-top: 0.75rem;">
                    <a href="${originalHref}" class="${classes}" ${dataAttributes} style="flex: 1;">
                        <i class="bi bi-cart-plus me-1"></i>Agregar
                    </a>
                    ${heartButton}
                </div>
                `;

                // Reemplazar el botón original con el contenedor
                $addToCartBtn.replaceWith(buttonContainer);
            } else {
                // Si no hay botón de agregar al carrito, agregar solo el corazón al final del producto
                $product.append(`<div class="d-flex justify-content-end mt-2">${heartButton}</div>`);
            }
        });
    }

    // Extraer ID del producto de la URL
    function extractProductId(url) {
        // Intentar extraer del parámetro ?p= o del slug
        const match = url.match(/[\?&]p=(\d+)/);
        if (match) {
            return match[1];
        }
        // Usar la URL completa como ID si no se encuentra parámetro
        return url.replace(/[^a-zA-Z0-9]/g, '');
    }

    // Toggle producto en wishlist
    function toggleWishlist(e) {
        e.preventDefault();
        e.stopPropagation();

        const $btn = $(this);
        const $icon = $btn.find('i');

        const product = {
            id: $btn.data('product-id'),
            name: $btn.data('product-name'),
            image: $btn.data('product-image'),
            price: $btn.data('product-price'),
            url: $btn.data('product-url'),
            addedAt: new Date().toISOString()
        };

        const index = wishlist.findIndex(item => item.id === product.id);

        if (index > -1) {
            // Remover de wishlist
            wishlist.splice(index, 1);
            $icon.removeClass('bi-heart-fill').addClass('bi-heart');
            $icon.css('color', '#999');
            showNotification('Removido de tu lista de deseos');
        } else {
            // Agregar a wishlist
            wishlist.push(product);
            $icon.removeClass('bi-heart').addClass('bi-heart-fill');
            $icon.css('color', '#EE285B');

            // Animación
            $btn.addClass('animate__animated animate__heartBeat');
            setTimeout(() => $btn.removeClass('animate__animated animate__heartBeat'), 1000);

            showNotification('¡Agregado a tu lista de deseos!');
        }

        saveWishlist();
        updateWishlistCount();
    }

    // Actualizar contador de wishlist
    function updateWishlistCount() {
        const count = wishlist.length;
        $('.wishlist-count').text(count);

        if (count > 0) {
            $('#wishlistFloatBtn').addClass('animate__animated animate__pulse');
            setTimeout(() => $('#wishlistFloatBtn').removeClass('animate__animated animate__pulse'), 1000);
        }
    }

    // Ir a la página de wishlist
    function goToWishlistPage() {
        // Buscar la página de wishlist (usando el slug)
        window.location.href = '/lista-de-deseos/';
    }

    // Mostrar notificación
    function showNotification(message) {
        if ($('#wishlistNotification').length === 0) {
            $('body').append(`
                <div id="wishlistNotification"
                     style="position: fixed; top: 20px; right: 20px; z-index: 9999;
                            max-width: 350px; display: none;">
                    <div class="alert alert-success d-flex align-items-center shadow-lg"
                         style="border-radius: 15px; border: none;">
                        <i class="bi bi-check-circle-fill me-2" style="font-size: 1.5rem;"></i>
                        <span class="notification-message"></span>
                    </div>
                </div>
            `);
        }

        $('#wishlistNotification .notification-message').text(message);
        $('#wishlistNotification').fadeIn(300).delay(2500).fadeOut(300);
    }

    // Inicializar cuando el DOM esté listo
    $(document).ready(function() {
        initWishlist();

        // Re-agregar botones cuando se carguen productos dinámicamente usando MutationObserver
        const targetNode = document.body;
        const config = { childList: true, subtree: true };
        let debounceTimer = null;

        const callback = function(mutationsList, observer) {
            for (let mutation of mutationsList) {
                if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                    // Verificar si se agregaron productos
                    let hasNewProducts = false;
                    mutation.addedNodes.forEach(node => {
                        if (node.nodeType === 1) { // Solo elementos HTML
                            if ($(node).hasClass('product') || $(node).find('.product').length > 0) {
                                // Verificar que el producto NO tenga ya un botón de wishlist
                                if (!$(node).find('.wishlist-heart-btn').length) {
                                    hasNewProducts = true;
                                }
                            }
                        }
                    });

                    if (hasNewProducts) {
                        // Debounce para evitar múltiples ejecuciones
                        clearTimeout(debounceTimer);
                        debounceTimer = setTimeout(() => {
                            addHeartButtons();
                        }, 300);
                    }
                }
            }
        };

        const observer = new MutationObserver(callback);
        observer.observe(targetNode, config);
    });

})(jQuery);
