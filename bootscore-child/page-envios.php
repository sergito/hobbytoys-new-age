<?php
/**
 * The template for displaying all pages
 *
 * @package Bootscore
 * @version 6.1.0
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

get_header();
?>

    <!-- Header Compacto -->
    <section class="shop-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="shop-title mb-3">Información de Envíos</h1>
                    <p class="lead text-muted mb-3">Llevamos la diversión hasta tu puerta con Correo Argentino</p>
                    <div class="d-flex gap-2 flex-wrap">
                        <span class="badge" style="background: var(--secondary-color); color: #fff; padding: .6rem 1.2rem;">
                            <i class="bi bi-truck me-1"></i> Todo el país
                        </span>
                        <span class="badge" style="background: var(--accent-color); color: var(--text-dark); padding: .6rem 1.2rem;">
                            <i class="bi bi-gift me-1"></i> Gratis desde $50K (LP) / $90K (Nacional)
                        </span>
                    </div>
                </div>
                <div class="col-lg-4 text-end d-none d-lg-block">
                    <div class="benefit-card text-center p-4">
                        <i class="bi bi-box-seam" style="font-size: 3rem; color: var(--primary-color);"></i>
                        <h5 class="fw-bold mt-3 mb-2" style="color: var(--secondary-color);">Envíos Seguros</h5>
                        <p class="text-muted small mb-0">Garantizamos que tus productos lleguen en perfectas condiciones</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Envío Gratis Destacado -->
    <section class="py-4" style="background: linear-gradient(135deg, var(--accent-color), gold);">
        <div class="container">
            <div class="row align-items-center text-center">
                <div class="col-md-6">
                    <h4 class="fw-bold mb-2" style="color: var(--text-dark);">
                        <i class="bi bi-truck me-2"></i>Envío Gratis La Plata
                    </h4>
                    <p class="mb-0 fw-bold">Compras desde $50.000</p>
                </div>
                <div class="col-md-6 mt-3 mt-md-0">
                    <h4 class="fw-bold mb-2" style="color: var(--text-dark);">
                        <i class="bi bi-map me-2"></i>Envío Gratis Nacional
                    </h4>
                    <p class="mb-0 fw-bold">Compras desde $90.000</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Zona La Plata - Compacto -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-4">
                <h2 class="fw-bold" style="color: var(--text-dark); font-size: 2rem;">La Plata y Zona Metropolitana</h2>
                <p class="text-muted">Entregas rápidas en 24-48hs</p>
                <p class="fw-bold" style="color: var(--primary-color); font-size: 1.1rem;">
                    <i class="bi bi-lightning-fill me-1"></i>
                    Envíos en el día a La Plata comprando antes de las 13hs
                </p>
            </div>

            <div class="row g-3">
                <div class="col-lg-8">
                    <div class="shipping-card" style="border-left: 4px solid var(--primary-color);">
                        <div class="row align-items-center">
                            <div class="col-md-4 text-center text-md-start">
                                <i class="bi bi-geo-alt-fill" style="font-size: 2.5rem; color: var(--primary-color);"></i>
                                <h5 class="fw-bold mt-2 mb-0" style="color: var(--secondary-color);">La Plata Centro</h5>
                            </div>
                            <div class="col-md-4 text-center mt-3 mt-md-0">
                                <p class="small text-muted mb-1">Tiempo de entrega</p>
                                <span class="time-badge">24-48hs hábiles</span>
                            </div>
                            <div class="col-md-4 text-center mt-3 mt-md-0">
                                <p class="small text-muted mb-1">Costo de envío</p>
                                <span class="price-highlight">$6.500</span>
                                <p class="small text-muted mt-1 mb-0">Gratis desde $50.000</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="shipping-card text-center" style="border-left: 4px solid var(--accent-color);">
                        <i class="bi bi-calendar-week" style="font-size: 2rem; color: var(--primary-color);"></i>
                        <h6 class="fw-bold mt-2 mb-2" style="color: var(--secondary-color);">Zonas Especiales</h6>
                        <p class="small mb-2">Berisso, Ensenada, Sicardi</p>
                        <span class="time-badge">Sábados</span>
                        <p class="mt-2 mb-0"><span class="price-highlight">$6.500</span></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Envíos Nacionales - Grid Mejorado -->
    <section class="py-5" style="background: linear-gradient(135deg, var(--light-bg), #fff);">
        <div class="container">
            <div class="text-center mb-4">
                <h2 class="fw-bold" style="color: var(--text-dark); font-size: 2rem;">Envíos a Todo el País</h2>
                <p class="text-muted">Llegamos a todos los rincones de Argentina</p>
            </div>

            <div class="row g-3">
                <div class="col-lg-6">
                    <div class="zone-card">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <h6 class="fw-bold mb-2" style="color: var(--secondary-color);">
                                    <i class="bi bi-building me-2"></i>Buenos Aires y CABA
                                </h6>
                                <span class="time-badge">3-6 días hábiles</span>
                            </div>
                            <span class="price-highlight">$8.000</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="zone-card">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <h6 class="fw-bold mb-2" style="color: var(--secondary-color);">
                                    <i class="bi bi-geo me-2"></i>Zona Centro
                                </h6>
                                <p class="small text-muted mb-2">Córdoba, Entre Ríos, La Pampa, Santa Fe</p>
                                <span class="time-badge">4-7 días hábiles</span>
                            </div>
                            <span class="price-highlight">$10.000</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="zone-card">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <h6 class="fw-bold mb-2" style="color: var(--secondary-color);">
                                    <i class="bi bi-geo me-2"></i>Norte y Cuyo
                                </h6>
                                <p class="small text-muted mb-2">Catamarca, Chaco, Corrientes, Formosa, La Rioja, Mendoza, Misiones, Neuquén, Río Negro, San Juan, San Luis, Santiago del Estero, Tucumán</p>
                                <span class="time-badge">4-7 días hábiles</span>
                            </div>
                            <span class="price-highlight">$11.900</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="zone-card">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <h6 class="fw-bold mb-2" style="color: var(--secondary-color);">
                                    <i class="bi bi-geo me-2"></i>Patagonia y Norte Extremo
                                </h6>
                                <p class="small text-muted mb-2">Chubut, Jujuy, Salta, Santa Cruz, Tierra del Fuego</p>
                                <span class="time-badge">4-7 días hábiles</span>
                            </div>
                            <span class="price-highlight">$12.900</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Información Importante - Compacto -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-4">
                <h2 class="fw-bold" style="color: var(--text-dark); font-size: 2rem;">¿Cómo Funcionan los Envíos?</h2>
            </div>

            <div class="row g-3">
                <div class="col-md-4">
                    <div class="benefit-card text-center p-4">
                        <div class="shipping-icon mx-auto" style="width: 70px; height: 70px; font-size: 1.8rem;">
                            <i class="bi bi-cart-check"></i>
                        </div>
                        <h6 class="fw-bold mt-3 mb-2" style="color: var(--secondary-color);">1. Realizá tu Pedido</h6>
                        <p class="text-muted small mb-0">Elegí tus productos y completá la compra online</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="benefit-card text-center p-4">
                        <div class="shipping-icon mx-auto" style="width: 70px; height: 70px; font-size: 1.8rem;">
                            <i class="bi bi-box-seam"></i>
                        </div>
                        <h6 class="fw-bold mt-3 mb-2" style="color: var(--secondary-color);">2. Preparamos tu Envío</h6>
                        <p class="text-muted small mb-0">Empaquetamos con cuidado y despachamos</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="benefit-card text-center p-4">
                        <div class="shipping-icon mx-auto" style="width: 70px; height: 70px; font-size: 1.8rem;">
                            <i class="bi bi-house-door"></i>
                        </div>
                        <h6 class="fw-bold mt-3 mb-2" style="color: var(--secondary-color);">3. Recibís en tu Casa</h6>
                        <p class="text-muted small mb-0">Correo Argentino lo entrega en tu domicilio</p>
                    </div>
                </div>
            </div>

            <!-- Info adicional -->
            <div class="row g-3 mt-3">
                <div class="col-md-6">
                    <div class="info-box">
                        <h6 class="fw-bold mb-2" style="color: var(--secondary-color);">
                            <i class="bi bi-shield-check me-2"></i>Envíos Seguros
                        </h6>
                        <p class="mb-0 small">Todos nuestros envíos están asegurados por Correo Argentino. Empaquetamos cada producto con materiales de protección para garantizar que llegue en perfectas condiciones.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-box">
                        <h6 class="fw-bold mb-2" style="color: var(--secondary-color);">
                            <i class="bi bi-clock me-2"></i>Seguimiento Online
                        </h6>
                        <p class="mb-0 small">Una vez despachado tu pedido, recibirás un código de seguimiento para rastrear tu envío en tiempo real a través del sitio web de Correo Argentino.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="py-5 cta-section text-white">
        <div class="container">
            <div class="row align-items-center justify-content-between">
                <div class="col-lg-7 text-center text-lg-start mb-3 mb-lg-0">
                    <h3 class="fw-bold mb-2">¿Dudas sobre tu envío?</h3>
                    <p class="mb-0">Nuestro equipo está listo para ayudarte con cualquier consulta</p>
                </div>
                <div class="col-lg-4 text-center text-lg-end">
                    <a href="<?php echo esc_url(home_url('/contacto')); ?>" class="btn btn-custom btn-lg px-4 py-3 rounded-pill">
                        <i class="bi bi-chat-dots me-2"></i>Contactanos
                    </a>
                </div>
            </div>
        </div>
    </section>

<?php
get_footer();