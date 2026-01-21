/**
 * Quick View Modal - Vista rápida de productos
 * Muestra un modal con información del producto sin salir de la página actual
 */

(function($) {
    'use strict';

    // Datos de ejemplo de productos (en producción vendría de WooCommerce)
    const productData = {
        1: {
            id: 1,
            name: 'Set LEGO Casa de Santa Claus',
            price: 84.49,
            oldPrice: 129.99,
            image: 'https://via.placeholder.com/400x400/FFD700/333333?text=Lego+Santa',
            images: [
                'https://via.placeholder.com/400x400/FFD700/333333?text=Lego+Santa',
                'https://via.placeholder.com/400x400/FFD700/333333?text=Lego+Santa+2',
                'https://via.placeholder.com/400x400/FFD700/333333?text=Lego+Santa+3'
            ],
            description: 'Set completo de construcción LEGO con la casa de Santa Claus. Incluye 500 piezas, figuras y accesorios navideños.',
            category: 'Bloques y Ladrillos',
            stock: 15,
            badge: '-35%',
            rating: 4.5,
            reviews: 24
        },
        2: {
            id: 2,
            name: 'Peluche Reno Rudolph Gigante',
            price: 49.99,
            image: 'https://via.placeholder.com/400x400/C41E3A/FFFFFF?text=Peluche+Reno',
            images: [
                'https://via.placeholder.com/400x400/C41E3A/FFFFFF?text=Peluche+Reno',
                'https://via.placeholder.com/400x400/C41E3A/FFFFFF?text=Peluche+Reno+2'
            ],
            description: 'Peluche suave y adorable del reno Rudolph. Tamaño gigante (60cm), ideal para abraazar y decorar.',
            category: 'Peluches',
            stock: 8,
            badge: '¡Nuevo!',
            rating: 5,
            reviews: 12
        },
        3: {
            id: 3,
            name: 'Tren Navideño Eléctrico Deluxe',
            price: 149.99,
            oldPrice: 199.99,
            image: 'https://via.placeholder.com/400x400/165B33/FFFFFF?text=Tren+Navidad',
            images: [
                'https://via.placeholder.com/400x400/165B33/FFFFFF?text=Tren+Navidad',
                'https://via.placeholder.com/400x400/165B33/FFFFFF?text=Tren+Navidad+2',
                'https://via.placeholder.com/400x400/165B33/FFFFFF?text=Tren+Navidad+3',
                'https://via.placeholder.com/400x400/165B33/FFFFFF?text=Tren+Navidad+4'
            ],
            description: 'Tren eléctrico navideño con luces y sonidos. Incluye locomotora, 3 vagones, vías y decoración navideña.',
            category: 'Vehículos',
            stock: 5,
            badge: 'Regalo',
            rating: 4.8,
            reviews: 18
        }
    };

    // Inicializar Quick View
    function initQuickView() {
        // Crear modal HTML si no existe
        if ($('#quickViewModal').length === 0) {
            createQuickViewModal();
        }

        // Agregar botones de Quick View a las tarjetas de productos
        addQuickViewButtons();

        // Event listeners
        $(document).on('click', '.quick-view-btn', handleQuickView);
        $(document).on('click', '.qv-thumb', handleThumbnailClick);
        $(document).on('click', '.qv-qty-btn', handleQuantityChange);
        $(document).on('click', '.qv-add-to-cart', handleAddToCart);
    }

    // Crear HTML del modal
    function createQuickViewModal() {
        const modalHTML = `
        <div class="modal fade" id="quickViewModal" tabindex="-1" aria-labelledby="quickViewModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content" style="border-radius: 20px; border: none;">
                    <div class="modal-header" style="border-bottom: 1px solid #f4efe8;">
                        <h5 class="modal-title fw-bold" id="quickViewModalLabel" style="color: #534fb5;">
                            <i class="bi bi-eye-fill me-2" style="color: #EE285B;"></i>Vista Rápida
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="row g-4">
                            <!-- Imagen del producto -->
                            <div class="col-md-5">
                                <div class="qv-image-container position-relative">
                                    <div class="qv-badge position-absolute top-0 start-0 m-2"></div>
                                    <img src="" alt="" class="qv-main-image img-fluid rounded-3" style="width: 100%; object-fit: contain; max-height: 350px; background: #f8f9fa;">

                                    <!-- Miniaturas -->
                                    <div class="qv-thumbnails d-flex gap-2 mt-3 justify-content-center"></div>
                                </div>
                            </div>

                            <!-- Información del producto -->
                            <div class="col-md-7">
                                <div class="qv-category mb-2">
                                    <span class="badge" style="background: #f4efe8; color: #534fb5; font-weight: 600;"></span>
                                </div>

                                <h3 class="qv-title fw-bold mb-3" style="color: #2C3E50; font-size: 1.5rem;"></h3>

                                <div class="qv-rating mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="stars me-2"></div>
                                        <span class="text-muted small">(<span class="qv-reviews"></span> opiniones)</span>
                                    </div>
                                </div>

                                <div class="qv-price mb-3">
                                    <span class="qv-old-price text-muted text-decoration-line-through me-2"></span>
                                    <span class="qv-current-price fw-bold" style="font-size: 2rem; color: #EE285B;"></span>
                                </div>

                                <div class="qv-stock mb-3">
                                    <i class="bi bi-check-circle-fill text-success me-1"></i>
                                    <span class="fw-semibold text-success">En stock (<span class="qv-stock-count"></span> disponibles)</span>
                                </div>

                                <div class="qv-description mb-4">
                                    <p class="text-muted"></p>
                                </div>

                                <!-- Cantidad y botones -->
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div class="input-group" style="width: 130px;">
                                        <button class="btn btn-outline-secondary qv-qty-btn" type="button" data-action="decrease">
                                            <i class="bi bi-dash"></i>
                                        </button>
                                        <input type="number" class="form-control text-center qv-quantity" value="1" min="1" max="99">
                                        <button class="btn btn-outline-secondary qv-qty-btn" type="button" data-action="increase">
                                            <i class="bi bi-plus"></i>
                                        </button>
                                    </div>

                                    <button class="btn btn-lg qv-add-to-cart flex-grow-1" style="background: #EE285B; color: white; border-radius: 25px; font-weight: 600;">
                                        <i class="bi bi-cart-plus me-2"></i>Agregar al Carrito
                                    </button>

                                    <button class="btn btn-lg btn-outline-secondary wishlist-toggle-btn" style="border-radius: 50%; width: 50px; height: 50px;">
                                        <i class="bi bi-heart"></i>
                                    </button>
                                </div>

                                <!-- Compartir -->
                                <div class="qv-share">
                                    <span class="text-muted small me-2">Compartir:</span>
                                    <a href="#" class="btn btn-sm btn-outline-success rounded-circle me-1" title="WhatsApp">
                                        <i class="bi bi-whatsapp"></i>
                                    </a>
                                    <a href="#" class="btn btn-sm btn-outline-primary rounded-circle me-1" title="Facebook">
                                        <i class="bi bi-facebook"></i>
                                    </a>
                                    <a href="#" class="btn btn-sm btn-outline-info rounded-circle" title="Twitter">
                                        <i class="bi bi-twitter"></i>
                                    </a>
                                </div>

                                <!-- Link a página completa -->
                                <div class="mt-4 pt-3" style="border-top: 1px solid #f4efe8;">
                                    <a href="#" class="qv-full-link text-decoration-none" style="color: #534fb5; font-weight: 600;">
                                        Ver detalles completos <i class="bi bi-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        `;

        $('body').append(modalHTML);
    }

    // Agregar botones Quick View a productos
    function addQuickViewButtons() {
        $('.product-card').each(function(index) {
            const $card = $(this);

            // Evitar duplicados
            if ($card.find('.quick-view-btn').length > 0) return;

            // Crear botón
            const productId = index + 1; // En producción esto vendría del atributo data-product-id
            const $quickViewBtn = $(`
                <button class="btn btn-sm quick-view-btn position-absolute"
                        data-product-id="${productId}"
                        style="bottom: 10px; left: 50%; transform: translateX(-50%);
                               background: white; color: #EE285B; border: 2px solid #EE285B;
                               border-radius: 25px; padding: 8px 20px; font-weight: 600;
                               opacity: 0; transition: all 0.3s ease; z-index: 10;
                               box-shadow: 0 4px 12px rgba(238, 40, 91, 0.2);">
                    <i class="bi bi-eye me-1"></i>Vista Rápida
                </button>
            `);

            // Hacer el contenedor relativo
            $card.css('position', 'relative');

            // Agregar botón
            $card.find('.product-image-container').append($quickViewBtn);

            // Mostrar/ocultar en hover
            $card.hover(
                function() {
                    $(this).find('.quick-view-btn').css('opacity', '1');
                },
                function() {
                    $(this).find('.quick-view-btn').css('opacity', '0');
                }
            );
        });
    }

    // Manejar click en Quick View
    function handleQuickView(e) {
        e.preventDefault();
        e.stopPropagation();

        const productId = $(this).data('product-id');
        const product = productData[productId];

        if (!product) {
            console.error('Producto no encontrado');
            return;
        }

        populateModal(product);

        // Mostrar modal con animación
        const modal = new bootstrap.Modal(document.getElementById('quickViewModal'));
        modal.show();
    }

    // Poblar modal con datos del producto
    function populateModal(product) {
        const $modal = $('#quickViewModal');

        // Badge
        if (product.badge) {
            let badgeClass = 'bg-danger';
            if (product.badge === '¡Nuevo!') badgeClass = 'bg-success';
            if (product.badge === 'Regalo') badgeClass = 'bg-warning text-dark';

            $modal.find('.qv-badge').html(`
                <span class="badge ${badgeClass}">${product.badge}</span>
            `);
        } else {
            $modal.find('.qv-badge').empty();
        }

        // Imágenes
        $modal.find('.qv-main-image').attr('src', product.images[0]).attr('alt', product.name);

        // Miniaturas
        const $thumbContainer = $modal.find('.qv-thumbnails');
        $thumbContainer.empty();
        product.images.forEach((img, index) => {
            const activeClass = index === 0 ? 'active' : '';
            $thumbContainer.append(`
                <img src="${img}"
                     alt="Miniatura ${index + 1}"
                     class="qv-thumb ${activeClass}"
                     style="width: 60px; height: 60px; object-fit: cover; cursor: pointer;
                            border-radius: 8px; border: 2px solid ${index === 0 ? '#EE285B' : '#e0e0e0'};
                            transition: all 0.3s ease;">
            `);
        });

        // Información
        $modal.find('.qv-category span').text(product.category);
        $modal.find('.qv-title').text(product.name);
        $modal.find('.qv-description p').text(product.description);

        // Rating
        const starsHTML = generateStars(product.rating);
        $modal.find('.qv-rating .stars').html(starsHTML);
        $modal.find('.qv-reviews').text(product.reviews);

        // Precios
        if (product.oldPrice) {
            $modal.find('.qv-old-price').text('$' + product.oldPrice.toFixed(2)).show();
        } else {
            $modal.find('.qv-old-price').hide();
        }
        $modal.find('.qv-current-price').text('$' + product.price.toFixed(2));

        // Stock
        $modal.find('.qv-stock-count').text(product.stock);

        // Resetear cantidad
        $modal.find('.qv-quantity').val(1).attr('max', product.stock);

        // Guardar ID del producto
        $modal.data('product-id', product.id);
    }

    // Generar estrellas de rating
    function generateStars(rating) {
        let starsHTML = '';
        const fullStars = Math.floor(rating);
        const hasHalfStar = rating % 1 !== 0;

        for (let i = 0; i < fullStars; i++) {
            starsHTML += '<i class="bi bi-star-fill" style="color: #FFD700;"></i>';
        }

        if (hasHalfStar) {
            starsHTML += '<i class="bi bi-star-half" style="color: #FFD700;"></i>';
        }

        const emptyStars = 5 - Math.ceil(rating);
        for (let i = 0; i < emptyStars; i++) {
            starsHTML += '<i class="bi bi-star" style="color: #FFD700;"></i>';
        }

        return starsHTML;
    }

    // Manejar click en miniatura
    function handleThumbnailClick() {
        const $thumb = $(this);
        const newSrc = $thumb.attr('src');

        // Actualizar imagen principal
        $('.qv-main-image').attr('src', newSrc);

        // Actualizar estados activos
        $('.qv-thumb').css('border-color', '#e0e0e0').removeClass('active');
        $thumb.css('border-color', '#EE285B').addClass('active');
    }

    // Manejar cambios de cantidad
    function handleQuantityChange() {
        const action = $(this).data('action');
        const $input = $('.qv-quantity');
        let value = parseInt($input.val());
        const max = parseInt($input.attr('max'));

        if (action === 'increase' && value < max) {
            value++;
        } else if (action === 'decrease' && value > 1) {
            value--;
        }

        $input.val(value);
    }

    // Manejar agregar al carrito
    function handleAddToCart() {
        const productId = $('#quickViewModal').data('product-id');
        const quantity = parseInt($('.qv-quantity').val());
        const product = productData[productId];

        // Mostrar notificación
        showNotification(`¡${product.name} agregado al carrito! (${quantity} unidad${quantity > 1 ? 'es' : ''})`);

        // Cerrar modal
        bootstrap.Modal.getInstance(document.getElementById('quickViewModal')).hide();

        // Aquí iría la lógica real de WooCommerce para agregar al carrito
        console.log('Agregando al carrito:', { productId, quantity });
    }

    // Mostrar notificación
    function showNotification(message) {
        // Crear notificación si no existe
        if ($('#quickViewNotification').length === 0) {
            $('body').append(`
                <div id="quickViewNotification"
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

        // Mostrar notificación
        $('#quickViewNotification .notification-message').text(message);
        $('#quickViewNotification').fadeIn(300).delay(3000).fadeOut(300);
    }

    // Inicializar cuando el DOM esté listo
    $(document).ready(function() {
        initQuickView();
    });

})(jQuery);
