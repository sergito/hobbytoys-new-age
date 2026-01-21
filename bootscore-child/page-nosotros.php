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
                <div class="col-lg-7">
                    <h1 class="shop-title mb-3">Familia, Pasión y Juego</h1>
                    <p class="lead text-muted mb-0">Más que una juguetería, somos parte de las mejores memorias de miles de familias.</p>
                </div>
                <div class="col-lg-5 text-end d-none d-lg-block">
                    <div class="d-flex gap-3 justify-content-end flex-wrap">
                        <div class="benefit-card text-center px-4 py-3" style="flex: 0 0 auto;">
                            <h3 class="fw-bold mb-0" style="color: var(--primary-color);">38+</h3>
                            <small class="text-muted">Años</small>
                        </div>
                        <div class="benefit-card text-center px-4 py-3" style="flex: 0 0 auto;">
                            <h3 class="fw-bold mb-0" style="color: var(--secondary-color);">10k+</h3>
                            <small class="text-muted">Familias Felices</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Mobile -->
    <section class="py-3 d-lg-none" style="background: var(--light-bg);">
        <div class="container">
            <div class="row g-2 text-center">
                <div class="col-6">
                    <div class="benefit-card py-2">
                        <h4 class="fw-bold mb-0" style="color: var(--primary-color);">38+</h4>
                        <small class="text-muted">Años</small>
                    </div>
                </div>
                <div class="col-6">
                    <div class="benefit-card py-2">
                        <h4 class="fw-bold mb-0" style="color: var(--secondary-color);">10k+</h4>
                        <small class="text-muted">Familias Felices</small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Quiénes Somos - Diseño Visual -->
    <section class="py-5">
        <div class="container">
            <div class="row g-4 align-items-center">
                <div class="col-lg-6">
                    <div class="position-relative">
                        <div class="image-container mb-3">
                            <img src="https://images.unsplash.com/photo-1596461404969-9ae70f2830c1?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" alt="Tienda Hobby Toys" class="img-fluid">
                        </div>
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="image-container" style="height: 180px;">
                                    <img src="https://images.unsplash.com/photo-1503454537195-1dcabb73ffb9?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Innovación" class="img-fluid h-100 w-100" style="object-fit: cover;">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="image-container" style="height: 180px;">
                                    <img src="https://images.unsplash.com/photo-1581833971358-2c8b550f87b3?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Niños felices" class="img-fluid h-100 w-100" style="object-fit: cover;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <span class="badge bg-secondary px-3 py-2 mb-3">✨ Nuestra Esencia</span>
                    <h2 class="fw-bold mb-4" style="color: var(--text-dark); font-size: 2rem;">Familia, Pasión y Juego</h2>
                    <p class="mb-3" style="font-size: 1.05rem; line-height: 1.8;">
                        Hace más de cuarenta años, Luis y Elma dieron sus primeros pasos en el mundo del comercio con La Botica, un querido polirrubro de barrio ubicado en calle 37 entre 25 y 26 de La Plata. Aún con la gran variedad de productos que ofrecían, siempre hubo algo que los apasionó especialmente: los juguetes.
                    </p>
                    <p class="mb-4" style="font-size: 1.05rem; line-height: 1.8;">
                        Esa fascinación por el juego, la imaginación y las sonrisas de los chicos fue creciendo con el tiempo, hasta que en 1994 decidieron transformar ese entusiasmo en un nuevo proyecto: Hobby Toys, en calle 39 entre 24 y 25, donde hoy continúa escribiéndose esta historia.
                    </p>
                    <p class="mb-4" style="font-size: 1.05rem; line-height: 1.8;">
                        Lo que comenzó como un sueño familiar se convirtió en una empresa referente, reconocida por su cercanía, compromiso y la calidez que la caracteriza. Hoy más de 30 años después, Hobby Toys sigue evolucionando y adaptándose a los nuevos tiempos, sin perder la esencia que la vio nacer.
                    </p>
                    <p class="mb-4" style="font-size: 1.05rem; line-height: 1.8;">
                        En Hobby Toys, creemos que jugar es mucho más que divertirse: es imaginar, crear y aprender. Por eso seguimos creciendo, incorporando las mejores marcas y ofreciendo productos que inspiran y despiertan sonrisas en todo el país.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Evolución en Cards Horizontales -->
    <section class="py-5" style="background: linear-gradient(135deg, var(--light-bg), #fff);">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold" style="color: var(--text-dark); font-size: 2rem;">Nuestra Evolución</h2>
                <p class="text-muted">De La Botica a Hobby Toys</p>
            </div>

            <div class="row g-3">
                <div class="col-md-4">
                    <div class="shipping-card h-100" style="border-left: 4px solid var(--primary-color);">
                        <div class="d-flex align-items-start gap-3">
                            <div class="shipping-icon" style="width: 60px; height: 60px; font-size: 1.5rem; flex-shrink: 0;">
                                <i class="bi bi-lightbulb"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-2" style="color: var(--secondary-color);">Inicios</h5>
                                <p class="mb-0 small text-muted">La Botica, un polirrubro de barrio. Aunque ofrecía una gran variedad de productos, su verdadera pasión siempre fueron los juguetes y el juego como forma de estimular la creatividad y la imaginación infantil.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="shipping-card h-100" style="border-left: 4px solid var(--secondary-color);">
                        <div class="d-flex align-items-start gap-3">
                            <div class="shipping-icon" style="width: 60px; height: 60px; font-size: 1.5rem; flex-shrink: 0; background: linear-gradient(45deg, var(--secondary-color), var(--primary-color));">
                                <i class="bi bi-graph-up-arrow"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-2" style="color: var(--secondary-color);">Transformación</h5>
                                <p class="mb-0 small text-muted">En 1994 nace Hobby Toys. Un nuevo capítulo impulsado por la pasión por los juguetes y el deseo de seguir creciendo con la misma esencia familiar.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="shipping-card h-100" style="border-left: 4px solid var(--accent-color);">
                        <div class="d-flex align-items-start gap-3">
                            <div class="shipping-icon" style="width: 60px; height: 60px; font-size: 1.5rem; flex-shrink: 0; background: linear-gradient(45deg, var(--accent-color), gold);">
                                <i class="bi bi-globe"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-2" style="color: var(--secondary-color);">Nueva Etapa</h5>
                                <p class="mb-0 small text-muted">En 2020, Hobby Toys dio un nuevo paso con la apertura de su tienda online, llevando su pasión por el juego a cada rincón del país y conectando y llevando su pasión y compromiso con familias de toda Argentina.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Valores en Grid Compacto -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold" style="color: var(--text-dark); font-size: 2rem;">Lo Que Nos Mueve</h2>
                <p class="text-muted">Principios que guían cada decisión</p>
            </div>

            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="value-item">
                        <div class="value-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h5 class="fw-bold mb-3" style="color: var(--secondary-color);">Seguridad</h5>
                        <p class="text-muted small mb-0">Controles rigurosos y estándares internacionales en todos nuestros productos.</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="value-item">
                        <div class="value-icon">
                            <i class="bi bi-book"></i>
                        </div>
                        <h5 class="fw-bold mb-3" style="color: var(--secondary-color);">Educación</h5>
                        <p class="text-muted small mb-0">El juego como herramienta de aprendizaje y desarrollo integral.</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="value-item">
                        <div class="value-icon">
                            <i class="bi bi-heart"></i>
                        </div>
                        <h5 class="fw-bold mb-3" style="color: var(--secondary-color);">Familia</h5>
                        <p class="text-muted small mb-0">Fortaleciendo vínculos con momentos compartidos llenos de diversión.</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="value-item">
                        <div class="value-icon">
                            <i class="bi bi-emoji-smile"></i>
                        </div>
                        <h5 class="fw-bold mb-3" style="color: var(--secondary-color);">Sonrisas</h5>
                        <p class="text-muted small mb-0">Trabajamos para crear momentos felices y promover el juego como fuente de aprendizaje, creatividad y diversión.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Mejorado -->
    <section class="py-5 cta-section text-white">
        <div class="container">
            <div class="row align-items-center justify-content-between">
                <div class="col-lg-7 text-center text-lg-start mb-3 mb-lg-0">
                    <h3 class="fw-bold mb-2">¿Listo para crear aventuras?</h3>
                    <p class="mb-0">Descubre nuestro catálogo con todos nuestros productos seleccionados con amor.</p>
                </div>

                <div class="col-lg-4 text-center text-lg-end">
                    <a href="<?php echo esc_url( get_permalink(wc_get_page_id( 'shop' )) ); ?>" class="btn btn-custom btn-lg px-4 py-3 rounded-pill">
                        <i class="bi bi-shop me-2"></i>Explorar Tienda
                    </a>
                </div>
            </div>
        </div>
    </section>

<?php
get_footer();
