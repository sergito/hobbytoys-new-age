<?php
/**
 * Template Name: cambios y devoluciones
 *
 * @package HobbyToys
 * @version 2.0.0
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

get_header();
?>

<!-- Header Compacto -->
<section class="shop-header">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <div class="mb-3">
                    <img src="https://hobbytoys.com.ar/wp-content/uploads/2024/02/Hobby-Toys-_-emoji-relleno-rojo-e1749040199665.jpg" 
                        alt="Cambios y devoluciones"
                        class="rounded-circle"
                        style="width: 80px; height: 80px; object-fit: cover; box-shadow: 0 4px 12px rgba(238,40,91,0.15);">
                </div>
                <h1 class="shop-title mb-3">Cambios y Devoluciones</h1>
                <p class="lead text-muted mb-0">¿Regalo repetido o algún inconveniente? ¡No hay problema!</p>
            </div>
        </div>
    </div>
</section>

<!-- Información Principal -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                
                <!-- Destacado -->
                <div class="shipping-card mb-4 text-center" style="background: linear-gradient(135deg, rgba(238,40,91,0.08), rgba(255,185,0,0.08)); box-shadow: 0 8px 24px rgba(238,40,91,0.12);">
                    <i class="bi bi-arrow-repeat" style="font-size: 3rem; color: var(--primary-color);"></i>
                    <h3 class="fw-bold mt-3 mb-3" style="color: var(--primary-color);">Todos los Productos Tienen Cambio</h3>
                    <p class="mb-0" style="font-size: 1.1rem;">Solo necesitamos que el <strong>packaging esté en buen estado</strong></p>
                </div>

                <!-- Requisitos -->
                <div class="shipping-card mb-3">
                    <div class="row align-items-center">
                        <div class="col-md-2 text-center mb-3 mb-md-0">
                            <i class="bi bi-calendar-check" style="font-size: 2.5rem; color: var(--secondary-color);"></i>
                        </div>
                        <div class="col-md-10">
                            <h5 class="fw-bold mb-2" style="color: var(--secondary-color);">Plazo de Cambio</h5>
                            <p class="mb-0">Tenés <strong>20 días desde la fecha de compra</strong> para realizar el cambio</p>
                        </div>
                    </div>
                </div>

                <div class="shipping-card mb-3">
                    <div class="row align-items-center">
                        <div class="col-md-2 text-center mb-3 mb-md-0">
                            <i class="bi bi-box-seam" style="font-size: 2.5rem; color: var(--accent-color);"></i>
                        </div>
                        <div class="col-md-10">
                            <h5 class="fw-bold mb-2" style="color: var(--secondary-color);">Condición del Producto</h5>
                            <p class="mb-0">El <strong>embalaje debe estar en buen estado</strong>. Al abrir el paquete, hacelo con cuidado para preservar el packaging original</p>
                        </div>
                    </div>
                </div>

                <div class="shipping-card mb-3">
                    <div class="row align-items-center">
                        <div class="col-md-2 text-center mb-3 mb-md-0">
                            <i class="bi bi-info-circle" style="font-size: 2.5rem; color: var(--primary-color);"></i>
                        </div>
                        <div class="col-md-10">
                            <h5 class="fw-bold mb-2" style="color: var(--secondary-color);">Importante</h5>
                            <p class="mb-0">Los productos <strong>no tienen garantía por uso</strong>, solo por defectos de fábrica</p>
                        </div>
                    </div>
                </div>

                <!-- Cómo Realizar el Cambio -->
                <div class="mt-5 mb-4 text-center">
                    <h3 class="fw-bold" style="color: var(--text-dark);">¿Cómo Realizar el Cambio?</h3>
                    <p class="text-muted">Elegí la opción que más te convenga</p>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="benefit-card text-center p-4 h-100">
                            <i class="bi bi-shop" style="font-size: 2.5rem; color: var(--primary-color);"></i>
                            <h6 class="fw-bold mt-3 mb-2" style="color: var(--secondary-color);">En el Local</h6>
                            <p class="small text-muted mb-2">Calle 39 Nro 1466<br>La Plata, Buenos Aires</p>
                            <a href="https://goo.gl/maps/uc65vobjeXTKT8zb8" target="_blank" class="btn btn-sm rounded-pill mt-2" style="background: var(--primary-color); color: #fff;">
                                <i class="bi bi-geo-alt me-1"></i>Ver Mapa
                            </a>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="benefit-card text-center p-4 h-100">
                            <i class="bi bi-whatsapp" style="font-size: 2.5rem; color: #25D366;"></i>
                            <h6 class="fw-bold mt-3 mb-2" style="color: var(--secondary-color);">Por WhatsApp</h6>
                            <p class="small text-muted mb-2">Chateá con nosotros<br>221-5608027</p>
                            <a href="https://wa.me/5492215608027" target="_blank" class="btn btn-sm rounded-pill mt-2" style="background: #25D366; color: #fff;">
                                <i class="bi bi-whatsapp me-1"></i>Chatear
                            </a>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="benefit-card text-center p-4 h-100">
                            <i class="bi bi-envelope" style="font-size: 2.5rem; color: var(--accent-color);"></i>
                            <h6 class="fw-bold mt-3 mb-2" style="color: var(--secondary-color);">Por Email</h6>
                            <p class="small text-muted mb-2">Escribinos a<br>ventas@hobbytoys.com.ar</p>
                            <a href="mailto:ventas@hobbytoys.com.ar" class="btn btn-sm rounded-pill mt-2" style="background: var(--accent-color); color: var(--text-dark);">
                                <i class="bi bi-envelope me-1"></i>Enviar Email
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Tips -->
                <div class="alert text-center" style="background: var(--light-bg); border: 2px solid var(--accent-color); border-radius: 1rem; color: var(--text-dark);">
                    <i class="bi bi-lightbulb-fill me-2" style="color: var(--accent-color);"></i>
                    <strong>Tip:</strong> Al abrir tu producto, hacelo con cuidado para conservar el embalaje en buen estado y facilitar cualquier cambio futuro
                </div>

            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="py-5 cta-section text-white">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="row align-items-center">
                    <div class="col-lg-8 text-center text-lg-start mb-3 mb-lg-0">
                        <h3 class="fw-bold mb-2">¿Necesitás Ayuda?</h3>
                        <p class="mb-0">Nuestro equipo está listo para asistirte con tu cambio</p>
                    </div>
                    <div class="col-lg-4 text-center text-lg-end">
                        <a href="/contacto" class="btn btn-custom btn-lg px-4 py-3 rounded-pill">
                            <i class="bi bi-chat-dots me-2"></i>Contactanos
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>