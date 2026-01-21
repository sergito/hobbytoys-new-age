<?php
/**
 * Template Name: Lista de Deseos
 * Description: Página de lista de deseos (wishlist)
 *
 * @package Bootscore
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

get_header();
?>

<style>
.wishlist-page-header {
    background: linear-gradient(135deg, var(--primary-color, #EE285B), var(--secondary-color, #534fb5));
    color: #fff;
    padding: 3rem 0;
    text-align: center;
    margin-bottom: 3rem;
}

.wishlist-page-header h1 {
    font-size: 2.5rem;
    font-weight: 800;
    margin: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
}

.wishlist-page-header p {
    margin: 0.5rem 0 0;
    opacity: 0.95;
    font-size: 1.1rem;
}

.wishlist-container {
    min-height: 60vh;
}

.wishlist-empty {
    text-align: center;
    padding: 4rem 2rem;
    background: white;
    border-radius: 1rem;
    box-shadow: 0 2px 15px rgba(0,0,0,0.05);
}

.wishlist-empty i {
    font-size: 5rem;
    color: #ddd;
    margin-bottom: 1.5rem;
}

.wishlist-empty h2 {
    font-size: 2rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 1rem;
}

.wishlist-empty p {
    color: #6c757d;
    font-size: 1.1rem;
    margin-bottom: 2rem;
}

.wishlist-items {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 2rem;
}

.wishlist-product-card {
    background: white;
    border-radius: 1rem;
    overflow: hidden;
    box-shadow: 0 2px 15px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    position: relative;
}

.wishlist-product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.wishlist-product-image {
    position: relative;
    padding-top: 100%;
    overflow: hidden;
    background: #f8f9fa;
}

.wishlist-product-image img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.wishlist-remove-btn {
    position: absolute;
    top: 10px;
    right: 10px;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: white;
    border: none;
    color: #dc3545;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    transition: all 0.3s ease;
    z-index: 10;
}

.wishlist-remove-btn:hover {
    background: #dc3545;
    color: white;
    transform: rotate(90deg);
}

.wishlist-product-info {
    padding: 1.5rem;
}

.wishlist-product-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 0.75rem;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.wishlist-product-title a {
    color: inherit;
    text-decoration: none;
    transition: color 0.3s ease;
}

.wishlist-product-title a:hover {
    color: var(--primary-color, #EE285B);
}

.wishlist-product-price {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--primary-color, #EE285B);
    margin-bottom: 1rem;
}

.wishlist-actions {
    display: flex;
    gap: 0.75rem;
}

.wishlist-actions .btn {
    flex: 1;
    border-radius: 0.75rem;
    font-weight: 600;
    padding: 0.75rem 1rem;
    transition: all 0.3s ease;
}

.wishlist-share-section {
    background: white;
    border-radius: 1rem;
    padding: 2rem;
    margin-top: 3rem;
    box-shadow: 0 2px 15px rgba(0,0,0,0.05);
    text-align: center;
}

.wishlist-share-section h3 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 1rem;
}

.wishlist-share-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.wishlist-share-buttons .btn {
    padding: 0.75rem 1.5rem;
    border-radius: 0.75rem;
    font-weight: 600;
}

.wishlist-count-badge {
    background: rgba(255,255,255,0.2);
    padding: 0.25rem 0.75rem;
    border-radius: 50px;
    font-size: 1.1rem;
    font-weight: 700;
}

@media (max-width: 768px) {
    .wishlist-page-header h1 {
        font-size: 2rem;
    }

    .wishlist-items {
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 1rem;
    }

    .wishlist-product-info {
        padding: 1rem;
    }

    .wishlist-product-title {
        font-size: 0.95rem;
    }

    .wishlist-product-price {
        font-size: 1.25rem;
    }
}
</style>

<!-- Header de la página -->
<div class="wishlist-page-header">
    <div class="container">
        <h1>
            <i class="bi bi-heart-fill"></i>
            Mi Lista de Deseos
            <span class="wishlist-count-badge" id="wishlistPageCount">0</span>
        </h1>
        <p>Guardá tus productos favoritos para comprarlos más tarde</p>
    </div>
</div>

<!-- Contenedor principal -->
<div class="container wishlist-container mb-5">
    <!-- Wishlist vacía -->
    <div id="wishlistEmptyState" class="wishlist-empty" style="display: none;">
        <i class="bi bi-heart"></i>
        <h2>Tu lista de deseos está vacía</h2>
        <p>¡Explorá nuestra tienda y agregá productos que te gusten!</p>
        <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="btn btn-primary btn-lg">
            <i class="bi bi-shop me-2"></i>Ir a la Tienda
        </a>
    </div>

    <!-- Grid de productos -->
    <div id="wishlistProductsGrid" class="wishlist-items"></div>

    <!-- Sección para compartir -->
    <div id="wishlistShareSection" class="wishlist-share-section" style="display: none;">
        <h3><i class="bi bi-share me-2"></i>Compartir mi lista de deseos</h3>
        <div class="wishlist-share-buttons">
            <button class="btn btn-success" id="shareWhatsApp">
                <i class="bi bi-whatsapp me-2"></i>Compartir por WhatsApp
            </button>
            <button class="btn btn-primary" id="copyLink">
                <i class="bi bi-link-45deg me-2"></i>Copiar enlace
            </button>
        </div>
    </div>
</div>

<script>
(function($) {
    'use strict';

    const STORAGE_KEY = 'hobbytoys_wishlist';

    // Cargar wishlist desde localStorage
    function loadWishlist() {
        const saved = localStorage.getItem(STORAGE_KEY);
        return saved ? JSON.parse(saved) : [];
    }

    // Guardar wishlist en localStorage
    function saveWishlist(wishlist) {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(wishlist));
    }

    // Actualizar contador
    function updateCount(count) {
        $('#wishlistPageCount').text(count);
        $('.wishlist-count').text(count); // También actualizar el botón flotante
    }

    // Renderizar productos
    function renderWishlist() {
        const wishlist = loadWishlist();
        const count = wishlist.length;

        updateCount(count);

        if (count === 0) {
            $('#wishlistEmptyState').show();
            $('#wishlistProductsGrid').hide();
            $('#wishlistShareSection').hide();
        } else {
            $('#wishlistEmptyState').hide();
            $('#wishlistProductsGrid').show();
            $('#wishlistShareSection').show();

            let html = '';
            wishlist.forEach(item => {
                html += `
                <div class="wishlist-product-card" data-product-id="${item.id}">
                    <div class="wishlist-product-image">
                        <img src="${item.image}" alt="${item.name}" loading="lazy">
                        <button class="wishlist-remove-btn" data-product-id="${item.id}">
                            <i class="bi bi-trash" style="font-size: 1.2rem;"></i>
                        </button>
                    </div>
                    <div class="wishlist-product-info">
                        <h3 class="wishlist-product-title">
                            <a href="${item.url}">${item.name}</a>
                        </h3>
                        <div class="wishlist-product-price">${item.price}</div>
                        <div class="wishlist-actions">
                            <a href="${item.url}" class="btn btn-primary">
                                <i class="bi bi-eye me-1"></i>Ver
                            </a>
                        </div>
                    </div>
                </div>
                `;
            });

            $('#wishlistProductsGrid').html(html);
        }
    }

    // Remover producto
    function removeFromWishlist(productId) {
        let wishlist = loadWishlist();
        wishlist = wishlist.filter(item => item.id !== productId);
        saveWishlist(wishlist);

        // Actualizar UI con animación
        $(`.wishlist-product-card[data-product-id="${productId}"]`).fadeOut(300, function() {
            $(this).remove();
            renderWishlist();
        });

        // Actualizar botones de corazón en toda la página
        $(`.wishlist-heart-btn[data-product-id="${productId}"]`).each(function() {
            $(this).find('i').removeClass('bi-heart-fill').addClass('bi-heart').css('color', '#999');
        });

        // Mostrar notificación
        showNotification('Producto eliminado de tu lista de deseos');
    }

    // Compartir por WhatsApp
    function shareWhatsApp() {
        const wishlist = loadWishlist();
        if (wishlist.length === 0) return;

        let message = '¡Mirá mi lista de deseos de Hobby Toys!\n\n';
        wishlist.forEach((item, index) => {
            message += `${index + 1}. ${item.name} - ${item.price}\n${item.url}\n\n`;
        });

        const whatsappUrl = `https://wa.me/?text=${encodeURIComponent(message)}`;
        window.open(whatsappUrl, '_blank');
    }

    // Copiar enlace
    function copyLink() {
        const url = window.location.href;
        navigator.clipboard.writeText(url).then(() => {
            showNotification('¡Enlace copiado al portapapeles!');
        }).catch(() => {
            showNotification('No se pudo copiar el enlace', 'error');
        });
    }

    // Mostrar notificación
    function showNotification(message, type = 'success') {
        if ($('#wishlistPageNotification').length === 0) {
            $('body').append(`
                <div id="wishlistPageNotification"
                     style="position: fixed; top: 20px; right: 20px; z-index: 9999;
                            max-width: 350px; display: none;">
                    <div class="alert alert-${type} d-flex align-items-center shadow-lg"
                         style="border-radius: 15px; border: none;">
                        <i class="bi bi-check-circle-fill me-2" style="font-size: 1.5rem;"></i>
                        <span class="notification-message"></span>
                    </div>
                </div>
            `);
        }

        $('#wishlistPageNotification .notification-message').text(message);
        $('#wishlistPageNotification .alert').removeClass('alert-success alert-danger').addClass(`alert-${type}`);
        $('#wishlistPageNotification').fadeIn(300).delay(2500).fadeOut(300);
    }

    // Inicializar
    $(document).ready(function() {
        renderWishlist();

        // Event listeners
        $(document).on('click', '.wishlist-remove-btn', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const productId = $(this).data('product-id');
            removeFromWishlist(productId);
        });

        $('#shareWhatsApp').on('click', shareWhatsApp);
        $('#copyLink').on('click', copyLink);

        // Escuchar cambios en localStorage desde otras pestañas
        $(window).on('storage', function(e) {
            if (e.originalEvent.key === STORAGE_KEY) {
                renderWishlist();
            }
        });
    });

})(jQuery);
</script>

<?php
get_footer();
?>
