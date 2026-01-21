/**
 * Scroll to Top Button
 * Botón redondo con flecha hacia arriba
 */

(function($) {
    'use strict';

    // Crear botón de scroll to top
    function createScrollToTopButton() {
        const buttonHTML = `
        <button id="scrollToTopBtn"
                style="position: fixed; bottom: 180px; right: 20px; z-index: 997;
                       width: 50px; height: 50px; border-radius: 50%;
                       background: linear-gradient(135deg, #534fb5, #4239a3);
                       border: none; box-shadow: 0 4px 15px rgba(83, 79, 181, 0.4);
                       display: none; align-items: center; justify-content: center;
                       cursor: pointer; transition: all 0.3s ease;
                       opacity: 0; transform: scale(0);">
            <i class="bi bi-arrow-up" style="font-size: 1.4rem; color: white;"></i>
        </button>
        `;

        $('body').append(buttonHTML);
    }

    // Mostrar/ocultar botón según scroll
    function handleScrollVisibility() {
        const $btn = $('#scrollToTopBtn');

        if ($(window).scrollTop() > 300) {
            if (!$btn.is(':visible')) {
                $btn.css({
                    display: 'flex',
                    opacity: '1',
                    transform: 'scale(1)'
                });
            }
        } else {
            if ($btn.is(':visible')) {
                $btn.css({
                    opacity: '0',
                    transform: 'scale(0)'
                });
                setTimeout(() => {
                    $btn.hide();
                }, 300);
            }
        }
    }

    // Scroll suave hacia arriba
    function scrollToTop() {
        $('html, body').animate({
            scrollTop: 0
        }, 600, 'swing');
    }

    // Inicializar
    $(document).ready(function() {
        createScrollToTopButton();

        // Event listener para scroll
        $(window).on('scroll', handleScrollVisibility);

        // Event listener para click
        $(document).on('click', '#scrollToTopBtn', function(e) {
            e.preventDefault();
            scrollToTop();
        });

        // Efecto hover
        $(document).on('mouseenter', '#scrollToTopBtn', function() {
            $(this).css({
                'transform': 'scale(1.1)',
                'box-shadow': '0 6px 20px rgba(83, 79, 181, 0.6)'
            });
        });

        $(document).on('mouseleave', '#scrollToTopBtn', function() {
            $(this).css({
                'transform': 'scale(1)',
                'box-shadow': '0 4px 15px rgba(83, 79, 181, 0.4)'
            });
        });
    });

})(jQuery);
