/**
 * Búsqueda Ajax Avanzada con Autocompletado
 * Sistema de búsqueda en tiempo real con sugerencias y categorías
 */

(function($) {
    'use strict';

    // Datos de ejemplo de productos (en producción vendría de WooCommerce via Ajax)
    const searchDatabase = [
        { id: 1, name: 'Set LEGO Casa de Santa Claus', category: 'Bloques y Ladrillos', price: 84.49, image: 'https://via.placeholder.com/80x80/FFD700/333333?text=Lego', stock: 15, tags: ['lego', 'construcción', 'navidad', 'santa'] },
        { id: 2, name: 'Peluche Reno Rudolph Gigante', category: 'Peluches', price: 49.99, image: 'https://via.placeholder.com/80x80/C41E3A/FFFFFF?text=Reno', stock: 8, tags: ['peluche', 'reno', 'navidad', 'suave'] },
        { id: 3, name: 'Tren Navideño Eléctrico Deluxe', category: 'Vehículos', price: 149.99, image: 'https://via.placeholder.com/80x80/165B33/FFFFFF?text=Tren', stock: 5, tags: ['tren', 'eléctrico', 'navidad', 'vías'] },
        { id: 4, name: 'Barbie Edición Navidad 2024', category: 'Muñecas', price: 69.99, image: 'https://via.placeholder.com/80x80/FFD700/333333?text=Barbie', stock: 12, tags: ['barbie', 'muñeca', 'navidad', 'colección'] },
        { id: 5, name: 'Drone Navideño con Luces LED', category: 'Aire Libre', price: 119.99, image: 'https://via.placeholder.com/80x80/C41E3A/FFFFFF?text=Drone', stock: 7, tags: ['drone', 'led', 'luces', 'volador'] },
        { id: 6, name: 'Pista de Carreras Navideña XL', category: 'Vehículos', price: 54.99, image: 'https://via.placeholder.com/80x80/165B33/FFFFFF?text=Pista', stock: 10, tags: ['pista', 'carreras', 'autos', 'velocidad'] },
        { id: 7, name: 'Robot Programable Educativo', category: 'Didácticos', price: 129.99, image: 'https://via.placeholder.com/80x80/FFD700/333333?text=Robot', stock: 6, tags: ['robot', 'programable', 'educativo', 'stem'] },
        { id: 8, name: 'Cocina de Juguete Deluxe', category: 'Juguetes de Rol', price: 111.99, image: 'https://via.placeholder.com/80x80/C41E3A/FFFFFF?text=Cocina', stock: 9, tags: ['cocina', 'juego', 'rol', 'chef'] },
        { id: 9, name: 'Puzzle 3D Villa Navideña', category: 'Didácticos', price: 44.99, image: 'https://via.placeholder.com/80x80/165B33/FFFFFF?text=Puzzle', stock: 14, tags: ['puzzle', '3d', 'armar', 'navidad'] },
        { id: 10, name: 'Nintendo Switch OLED Navidad', category: 'Videojuegos', price: 139.99, image: 'https://via.placeholder.com/80x80/FFD700/333333?text=Switch', stock: 3, tags: ['nintendo', 'switch', 'consola', 'videojuegos'] },
        { id: 11, name: 'Tablet para Niños 10" HD', category: 'Electrónica', price: 89.99, image: 'https://via.placeholder.com/80x80/C41E3A/FFFFFF?text=Tablet', stock: 11, tags: ['tablet', 'educativa', 'niños', 'hd'] },
        { id: 12, name: 'Patinete Eléctrico Junior', category: 'Aire Libre', price: 89.99, image: 'https://via.placeholder.com/80x80/165B33/FFFFFF?text=Patinete', stock: 4, tags: ['patinete', 'eléctrico', 'scooter', 'outdoor'] },
        { id: 13, name: 'Bicicleta Frozen 16"', category: 'Rodados', price: 62.99, image: 'https://via.placeholder.com/80x80/FFD700/333333?text=Bici', stock: 8, tags: ['bicicleta', 'frozen', 'disney', 'rodado'] },
        { id: 14, name: 'Sistema Karaoke con Luces', category: 'Música', price: 64.99, image: 'https://via.placeholder.com/80x80/C41E3A/FFFFFF?text=Karaoke', stock: 6, tags: ['karaoke', 'música', 'micrófono', 'cantar'] },
        { id: 15, name: 'Castillo Medieval Playmobil', category: 'Bloques y Ladrillos', price: 179.99, image: 'https://via.placeholder.com/80x80/165B33/FFFFFF?text=Castillo', stock: 5, tags: ['playmobil', 'castillo', 'medieval', 'caballeros'] }
    ];

    // Categorías populares
    const popularCategories = [
        'Bloques y Ladrillos',
        'Muñecas',
        'Vehículos',
        'Aire Libre',
        'Didácticos',
        'Peluches'
    ];

    // Búsquedas recientes
    let recentSearches = [];
    const RECENT_SEARCHES_KEY = 'hobbytoys_recent_searches';

    let searchTimeout;
    const SEARCH_DELAY = 300; // ms

    // Inicializar búsqueda avanzada
    function initAjaxSearch() {
        // Cargar búsquedas recientes
        loadRecentSearches();

        // Crear contenedor de resultados
        createSearchContainer();

        // Event listeners
        $(document).on('input', '.search-input, input[type="search"]', handleSearchInput);
        $(document).on('focus', '.search-input, input[type="search"]', handleSearchFocus);
        $(document).on('click', '.search-result-item', handleResultClick);
        $(document).on('click', '.search-category-filter', handleCategoryFilter);
        $(document).on('click', '.search-clear-btn', clearSearch);
        $(document).on('click', '.recent-search-item', handleRecentSearchClick);

        // Cerrar resultados al hacer click fuera
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.search-container, .search-input, input[type="search"]').length) {
                $('.search-results-container').fadeOut(200);
            }
        });

        // Navegación con teclado
        $(document).on('keydown', '.search-input, input[type="search"]', handleKeyboardNavigation);
    }

    // Cargar búsquedas recientes
    function loadRecentSearches() {
        try {
            const stored = localStorage.getItem(RECENT_SEARCHES_KEY);
            recentSearches = stored ? JSON.parse(stored) : [];
        } catch (error) {
            console.error('Error al cargar búsquedas recientes:', error);
            recentSearches = [];
        }
    }

    // Guardar búsquedas recientes
    function saveRecentSearches() {
        try {
            // Limitar a 10 búsquedas recientes
            recentSearches = recentSearches.slice(0, 10);
            localStorage.setItem(RECENT_SEARCHES_KEY, JSON.stringify(recentSearches));
        } catch (error) {
            console.error('Error al guardar búsquedas recientes:', error);
        }
    }

    // Crear contenedor de resultados
    function createSearchContainer() {
        // Envolver input de búsqueda en contenedor si no está envuelto
        $('input[type="search"], .search-input').each(function() {
            const $input = $(this);

            // Evitar duplicados
            if ($input.closest('.search-container').length > 0) return;

            // Crear contenedor
            const $container = $('<div class="search-container position-relative"></div>');
            $input.wrap($container);

            // Agregar icono de búsqueda y botón de limpiar
            const $parent = $input.parent();
            $parent.css('position', 'relative');

            // Botón de limpiar
            $parent.append(`
                <button class="search-clear-btn position-absolute"
                        style="right: 50px; top: 50%; transform: translateY(-50%);
                               background: none; border: none; color: #999;
                               cursor: pointer; display: none; z-index: 10;
                               transition: color 0.3s ease;">
                    <i class="bi bi-x-circle-fill" style="font-size: 1.2rem;"></i>
                </button>
            `);

            // Contenedor de resultados
            $parent.append(`
                <div class="search-results-container position-absolute w-100"
                     style="top: calc(100% + 10px); left: 0; right: 0;
                            background: white; border-radius: 15px;
                            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
                            display: none; z-index: 1000; max-height: 600px;
                            overflow-y: auto;">

                    <!-- Loader -->
                    <div class="search-loader text-center py-4" style="display: none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Buscando...</span>
                        </div>
                        <p class="mt-2 text-muted small mb-0">Buscando productos...</p>
                    </div>

                    <!-- Header con filtros -->
                    <div class="search-results-header p-3" style="border-bottom: 2px solid #f4efe8;">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-bold" style="color: #534fb5;">
                                <i class="bi bi-search me-1"></i>Resultados de búsqueda
                            </span>
                            <span class="search-count-badge badge"
                                  style="background: #EE285B; color: white;">
                                0 productos
                            </span>
                        </div>
                        <div class="search-category-filters d-flex gap-2 flex-wrap" style="display: none !important;">
                            <!-- Filtros de categoría se agregarán aquí -->
                        </div>
                    </div>

                    <!-- Búsquedas recientes -->
                    <div class="search-recent p-3" style="border-bottom: 1px solid #f4efe8;">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="small fw-semibold text-muted">
                                <i class="bi bi-clock-history me-1"></i>Búsquedas recientes
                            </span>
                        </div>
                        <div class="recent-searches-list">
                            <!-- Las búsquedas recientes se mostrarán aquí -->
                        </div>
                    </div>

                    <!-- Categorías populares -->
                    <div class="search-popular-categories p-3">
                        <span class="small fw-semibold text-muted d-block mb-2">
                            <i class="bi bi-tags me-1"></i>Categorías populares
                        </span>
                        <div class="d-flex gap-2 flex-wrap">
                            ${popularCategories.map(cat => `
                                <a href="#" class="badge bg-light text-dark search-category-badge"
                                   data-category="${cat}"
                                   style="text-decoration: none; padding: 8px 15px;
                                          border-radius: 20px; transition: all 0.3s ease;
                                          border: 1px solid #e0e0e0;">
                                    ${cat}
                                </a>
                            `).join('')}
                        </div>
                    </div>

                    <!-- Lista de resultados -->
                    <div class="search-results-list">
                        <!-- Los resultados se cargarán aquí -->
                    </div>

                    <!-- Sin resultados -->
                    <div class="search-no-results text-center py-5" style="display: none;">
                        <i class="bi bi-search" style="font-size: 3rem; color: #e0e0e0;"></i>
                        <h5 class="mt-3 text-muted">No se encontraron productos</h5>
                        <p class="text-muted small">Intenta con otros términos de búsqueda</p>
                    </div>
                </div>
            `);

            // Mostrar búsquedas recientes al inicio
            renderRecentSearches($parent);

            // Hover effects
            $parent.find('.search-category-badge').hover(
                function() {
                    $(this).css({
                        'background': '#EE285B',
                        'color': 'white',
                        'border-color': '#EE285B',
                        'transform': 'translateY(-2px)'
                    });
                },
                function() {
                    $(this).css({
                        'background': '#f8f9fa',
                        'color': '#2C3E50',
                        'border-color': '#e0e0e0',
                        'transform': 'translateY(0)'
                    });
                }
            );
        });
    }

    // Renderizar búsquedas recientes
    function renderRecentSearches($container) {
        const $recentList = $container.find('.recent-searches-list');

        if (recentSearches.length === 0) {
            $container.find('.search-recent').hide();
            return;
        }

        $container.find('.search-recent').show();
        $recentList.empty();

        recentSearches.forEach(search => {
            $recentList.append(`
                <div class="recent-search-item d-flex align-items-center justify-content-between py-2 px-3 mb-1"
                     data-search="${search}"
                     style="cursor: pointer; border-radius: 8px; transition: all 0.3s ease;">
                    <span class="small">
                        <i class="bi bi-clock me-2" style="color: #999;"></i>${search}
                    </span>
                    <i class="bi bi-arrow-up-left" style="color: #999; font-size: 0.9rem;"></i>
                </div>
            `);
        });

        // Hover effect
        $recentList.find('.recent-search-item').hover(
            function() {
                $(this).css('background', '#f4efe8');
            },
            function() {
                $(this).css('background', 'transparent');
            }
        );
    }

    // Manejar input de búsqueda
    function handleSearchInput(e) {
        const $input = $(this);
        const query = $input.val().trim();
        const $container = $input.closest('.search-container');
        const $results = $container.find('.search-results-container');
        const $clearBtn = $container.find('.search-clear-btn');

        // Mostrar/ocultar botón de limpiar
        if (query.length > 0) {
            $clearBtn.fadeIn(200);
        } else {
            $clearBtn.fadeOut(200);
        }

        // Cancelar búsqueda anterior
        clearTimeout(searchTimeout);

        // Si la consulta está vacía, mostrar búsquedas recientes
        if (query.length === 0) {
            $container.find('.search-results-list').empty();
            $container.find('.search-no-results').hide();
            $container.find('.search-recent, .search-popular-categories').show();
            $container.find('.search-results-header').hide();
            return;
        }

        // Ocultar secciones iniciales
        $container.find('.search-recent, .search-popular-categories').hide();
        $container.find('.search-results-header').show();

        // Esperar antes de buscar (debounce)
        searchTimeout = setTimeout(() => {
            performSearch(query, $container);
        }, SEARCH_DELAY);
    }

    // Realizar búsqueda
    function performSearch(query, $container) {
        const $loader = $container.find('.search-loader');
        const $resultsList = $container.find('.search-results-list');
        const $noResults = $container.find('.search-no-results');

        // Mostrar loader
        $loader.show();
        $resultsList.empty();
        $noResults.hide();

        // Simular delay de Ajax (en producción sería una llamada Ajax real)
        setTimeout(() => {
            // Buscar productos
            const results = searchProducts(query);

            // Ocultar loader
            $loader.hide();

            // Actualizar contador
            $container.find('.search-count-badge').text(`${results.length} producto${results.length !== 1 ? 's' : ''}`);

            // Mostrar resultados
            if (results.length === 0) {
                $noResults.show();
                return;
            }

            renderSearchResults(results, $resultsList);

        }, 500);
    }

    // Buscar productos en la base de datos
    function searchProducts(query) {
        const lowerQuery = query.toLowerCase();

        return searchDatabase.filter(product => {
            // Buscar en nombre
            if (product.name.toLowerCase().includes(lowerQuery)) return true;

            // Buscar en categoría
            if (product.category.toLowerCase().includes(lowerQuery)) return true;

            // Buscar en tags
            if (product.tags.some(tag => tag.includes(lowerQuery))) return true;

            return false;
        });
    }

    // Renderizar resultados de búsqueda
    function renderSearchResults(results, $container) {
        $container.empty();

        results.forEach(product => {
            const stockBadge = product.stock > 0
                ? `<span class="badge bg-success small">En stock</span>`
                : `<span class="badge bg-danger small">Agotado</span>`;

            const resultHTML = `
                <div class="search-result-item d-flex align-items-center p-3 animate__animated animate__fadeIn"
                     data-product-id="${product.id}"
                     style="cursor: pointer; transition: all 0.3s ease; border-bottom: 1px solid #f4efe8;">
                    <div class="flex-shrink-0 me-3">
                        <img src="${product.image}" alt="${product.name}"
                             style="width: 60px; height: 60px; object-fit: cover; border-radius: 10px;">
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-1 fw-bold" style="color: #2C3E50; font-size: 0.95rem;">
                            ${highlightQuery(product.name, $('input[type="search"]').val())}
                        </h6>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="badge bg-light text-dark small">${product.category}</span>
                            ${stockBadge}
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold" style="color: #EE285B; font-size: 1.1rem;">
                                $${product.price.toFixed(2)}
                            </span>
                            <button class="btn btn-sm btn-primary"
                                    style="background: #EE285B; border: none; border-radius: 20px; padding: 5px 15px;">
                                <i class="bi bi-cart-plus me-1"></i>Agregar
                            </button>
                        </div>
                    </div>
                </div>
            `;

            $container.append(resultHTML);
        });

        // Hover effect
        $('.search-result-item').hover(
            function() {
                $(this).css({
                    'background': '#f8f9fa',
                    'transform': 'translateX(5px)'
                });
            },
            function() {
                $(this).css({
                    'background': 'white',
                    'transform': 'translateX(0)'
                });
            }
        );
    }

    // Resaltar términos de búsqueda
    function highlightQuery(text, query) {
        if (!query) return text;

        const regex = new RegExp(`(${query})`, 'gi');
        return text.replace(regex, '<mark style="background: #FFB900; padding: 2px 4px; border-radius: 3px;">$1</mark>');
    }

    // Manejar focus en input
    function handleSearchFocus() {
        const $container = $(this).closest('.search-container');
        const $results = $container.find('.search-results-container');

        $results.fadeIn(200);
    }

    // Manejar click en resultado
    function handleResultClick() {
        const productId = $(this).data('product-id');
        const product = searchDatabase.find(p => p.id === productId);

        if (product) {
            // Guardar en búsquedas recientes
            addToRecentSearches(product.name);

            // Redirigir a página de producto o abrir quick view
            console.log('Ver producto:', product);
            showNotification(`Abriendo: ${product.name}`);

            // Cerrar resultados
            $('.search-results-container').fadeOut(200);

            // Limpiar búsqueda
            $('input[type="search"]').val('');
        }
    }

    // Agregar a búsquedas recientes
    function addToRecentSearches(query) {
        // Eliminar si ya existe
        recentSearches = recentSearches.filter(s => s !== query);

        // Agregar al inicio
        recentSearches.unshift(query);

        // Guardar
        saveRecentSearches();
    }

    // Manejar click en búsqueda reciente
    function handleRecentSearchClick() {
        const search = $(this).data('search');
        $('input[type="search"]').val(search).trigger('input');
    }

    // Filtrar por categoría
    function handleCategoryFilter(e) {
        e.preventDefault();
        const category = $(this).data('category');

        $('input[type="search"]').val(category).trigger('input');
    }

    // Limpiar búsqueda
    function clearSearch() {
        const $container = $(this).closest('.search-container');
        $container.find('input[type="search"]').val('').trigger('input').focus();
        $(this).fadeOut(200);
    }

    // Navegación con teclado
    function handleKeyboardNavigation(e) {
        const $results = $(this).closest('.search-container').find('.search-result-item');

        if ($results.length === 0) return;

        const $current = $results.filter('.keyboard-selected');
        let $next;

        switch(e.keyCode) {
            case 40: // Arrow down
                e.preventDefault();
                if ($current.length === 0) {
                    $next = $results.first();
                } else {
                    $next = $current.next('.search-result-item');
                    if ($next.length === 0) $next = $results.first();
                }
                $results.removeClass('keyboard-selected');
                $next.addClass('keyboard-selected').css('background', '#f4efe8');
                break;

            case 38: // Arrow up
                e.preventDefault();
                if ($current.length === 0) {
                    $next = $results.last();
                } else {
                    $next = $current.prev('.search-result-item');
                    if ($next.length === 0) $next = $results.last();
                }
                $results.removeClass('keyboard-selected');
                $next.addClass('keyboard-selected').css('background', '#f4efe8');
                break;

            case 13: // Enter
                e.preventDefault();
                if ($current.length > 0) {
                    $current.click();
                }
                break;

            case 27: // Escape
                e.preventDefault();
                $('.search-results-container').fadeOut(200);
                $(this).blur();
                break;
        }
    }

    // Mostrar notificación
    function showNotification(message) {
        if ($('#searchNotification').length === 0) {
            $('body').append(`
                <div id="searchNotification"
                     style="position: fixed; top: 20px; right: 20px; z-index: 9999;
                            max-width: 350px; display: none;">
                </div>
            `);
        }

        const notificationHTML = `
            <div class="alert alert-info d-flex align-items-center shadow-lg animate__animated animate__fadeInRight"
                 style="border-radius: 15px; border: none;">
                <i class="bi bi-info-circle-fill me-2" style="font-size: 1.5rem;"></i>
                <span>${message}</span>
            </div>
        `;

        $('#searchNotification').html(notificationHTML).fadeIn(300).delay(2000).fadeOut(300);
    }

    // Inicializar cuando el DOM esté listo
    $(document).ready(function() {
        initAjaxSearch();
    });

})(jQuery);
