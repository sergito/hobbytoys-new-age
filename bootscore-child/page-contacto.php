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
                    <h1 class="shop-title mb-3">¡Hablemos!</h1>
                    <p class="lead text-muted mb-3">¿Necesitás ayuda? Nuestro equipo está listo para asistirte con tus consultas y encontrar el juguete perfecto.</p>
                    <div class="d-flex gap-2 flex-wrap">
                        <span class="badge" style="background: var(--secondary-color); color: #fff; padding: .6rem 1.2rem;">
                            <i class="bi bi-clock me-1"></i> Respuesta en 24hs
                        </span>
                        <span class="badge" style="background: var(--accent-color); color: var(--text-dark); padding: .6rem 1.2rem;">
                            <i class="bi bi-headset me-1"></i> Atención personalizada
                        </span>
                    </div>
                </div>
                <div class="col-lg-5 text-end d-none d-lg-block">
                    <div class="benefit-card text-center p-4">
                        <i class="bi bi-chat-heart" style="font-size: 3rem; color: var(--primary-color);"></i>
                        <h5 class="fw-bold mt-3 mb-0" style="color: var(--secondary-color);">Estamos para Ayudarte</h5>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Métodos de Contacto - Compactos -->
    <section class="py-5" style="background: linear-gradient(135deg, var(--light-bg), #fff);">
        <div class="container">
            <div class="text-center mb-4">
                <h2 class="fw-bold" style="color: var(--text-dark); font-size: 2rem;">Contactanos Como Prefieras</h2>
            </div>

            <div class="row g-3">
                <div class="col-lg-4 col-md-6">
                    <div class="contact-card text-center">
                        <div class="contact-icon mx-auto">
                            <i class="bi bi-whatsapp"></i>
                        </div>
                        <h5 class="fw-bold mt-3 mb-2" style="color: var(--secondary-color);">WhatsApp</h5>
                        <p class="text-muted small mb-2">2215608027 (solo mensajes)</p>
                        <a href="https://wa.me/5492215608027" class="btn btn-success btn-sm rounded-pill" target="_blank">
                            <i class="bi bi-whatsapp me-1"></i>Enviar Mensaje
                        </a>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="contact-card text-center">
                        <div class="contact-icon mx-auto">
                            <i class="bi bi-telephone"></i>
                        </div>
                        <h5 class="fw-bold mt-3 mb-2" style="color: var(--secondary-color);">Teléfono</h5>
                        <p class="text-muted small mb-2">0221-4795282</p>
                        <span class="time-badge">Lunes a Sábados 9 a 20hs</span>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mx-auto">
                    <div class="contact-card text-center">
                        <div class="contact-icon mx-auto">
                            <i class="bi bi-envelope"></i>
                        </div>
                        <h5 class="fw-bold mt-3 mb-2" style="color: var(--secondary-color);">Email</h5>
                        <p class="text-muted small mb-2">ventas@hobbytoys.com.ar</p>
                        <span class="badge" style="background: var(--light-bg); color: var(--secondary-color); padding: .5rem 1rem;">Respuesta en 24hs</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Formulario de Contacto -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-4">
                <h2 class="fw-bold" style="color: var(--text-dark); font-size: 2rem;">Envianos un Mensaje</h2>
                <p class="text-muted">Completá el formulario y nos contactaremos con vos</p>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="form-container">
                        <div class="alert alert-custom mb-4" role="alert">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>¿Consulta urgente?</strong> Escribinos por WhatsApp para respuesta inmediata
                        </div>

                        <form id="contactForm">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="nombre" class="form-label">Nombre *</label>
                                    <input type="text" class="form-control" id="nombre" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="apellido" class="form-label">Apellido *</label>
                                    <input type="text" class="form-control" id="apellido" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" class="form-control" id="email" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="telefono" class="form-label">Teléfono</label>
                                    <input type="tel" class="form-control" id="telefono">
                                </div>
                                <div class="col-12">
                                    <label for="asunto" class="form-label">Asunto *</label>
                                    <select class="form-control" id="asunto" required>
                                        <option value="">Seleccioná un tema</option>
                                        <option value="consulta-producto">Consulta sobre productos</option>
                                        <option value="pedido">Estado de pedido</option>
                                        <option value="devolucion">Devolución o cambio</option>
                                        <option value="sugerencia">Sugerencia</option>
                                        <option value="otro">Otro</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label for="mensaje" class="form-label">Mensaje *</label>
                                    <textarea class="form-control" id="mensaje" rows="5" placeholder="Contanos cómo podemos ayudarte..." required></textarea>
                                </div>
                                <div class="col-12 mb-3">
                                    <div class="d-flex justify-content-center">
                                        <div class="g-recaptcha" data-sitekey="6LfpHSAsAAAAACDaJd4ObBBwCJddclvoDpsq9Fd6"></div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="privacidad" required>
                                        <label class="form-check-label" for="privacidad">
                                            Acepto la <a href="#" style="color: var(--primary-color);">política de privacidad</a> *
                                        </label>
                                    </div>
                                </div>
                                <div class="col-12 text-center">
                                    <button type="submit" class="btn btn-primary-custom">
                                        <i class="bi bi-send me-2"></i>Enviar Mensaje
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Visitanos - Diseño Divertido -->
    <section class="py-5" style="background: var(--light-bg);">
        <div class="container">
            <div class="text-center mb-4">
                <h2 class="fw-bold" style="color: var(--text-dark); font-size: 2rem;">Vení a Conocernos</h2>
                <p class="text-muted">Nuestro local está lleno de diversión esperándote</p>
            </div>

            <div class="row g-4 align-items-stretch">
                <!-- Info del Local -->
                <div class="col-lg-5">
                    <div class="store-card h-100" style="background: linear-gradient(135deg, #fff 0%, var(--light-bg) 100%); border: 3px solid var(--accent-color); position: relative; overflow: hidden;">
                        <!-- Decoración -->
                        <div style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: var(--accent-color); border-radius: 50%; opacity: 0.1;"></div>
                        <div style="position: absolute; bottom: -30px; left: -30px; width: 120px; height: 120px; background: var(--primary-color); border-radius: 50%; opacity: 0.1;"></div>
                        
                        <div style="position: relative; z-index: 2;">
                            <!-- Ícono grande -->
                            <div class="text-center mb-3">
                                <div class="contact-icon mx-auto" style="width: 90px; height: 90px; font-size: 2.5rem;">
                                    <i class="bi bi-shop"></i>
                                </div>
                            </div>

                            <h4 class="text-center fw-bold mb-4" style="color: var(--secondary-color);">
                                Hobby Toys La Plata
                            </h4>

                            <!-- Dirección -->
                            <div class="mb-3 p-3 rounded" style="background: rgba(255, 255, 255, 0.7);">
                                <div class="d-flex align-items-start gap-2">
                                    <i class="bi bi-geo-alt-fill" style="color: var(--primary-color); font-size: 1.5rem; flex-shrink: 0;"></i>
                                    <div>
                                        <p class="mb-1 fw-bold" style="color: var(--secondary-color);">Dirección</p>
                                        <p class="mb-0">Calle 39 Nro 1466<br>La Plata, Buenos Aires</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Teléfono -->
                            <div class="mb-3 p-3 rounded" style="background: rgba(255, 255, 255, 0.7);">
                                <div class="d-flex align-items-start gap-2">
                                    <i class="bi bi-telephone-fill" style="color: var(--primary-color); font-size: 1.5rem; flex-shrink: 0;"></i>
                                    <div>
                                        <p class="mb-1 fw-bold" style="color: var(--secondary-color);">Teléfono</p>
                                        <a href="tel:+5492215608027" class="text-decoration-none" style="color: var(--text-dark); font-size: 1.1rem;">
                                            0221-5608027
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="mb-3 p-3 rounded" style="background: rgba(255, 255, 255, 0.7);">
                                <div class="d-flex align-items-start gap-2">
                                    <i class="bi bi-envelope-fill" style="color: var(--primary-color); font-size: 1.5rem; flex-shrink: 0;"></i>
                                    <div style="overflow-wrap: break-word; word-break: break-all;">
                                        <p class="mb-1 fw-bold" style="color: var(--secondary-color);">Email</p>
                                        <a href="mailto:ventas@hobbytoys.com.ar" class="text-decoration-none" style="color: var(--text-dark);">
                                            ventas@hobbytoys.com.ar
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Horarios -->
                            <div class="text-center mt-4">
                                <span class="badge" style="background: var(--secondary-color); color: #fff; padding: .7rem 1.5rem; font-size: .95rem;">
                                    <i class="bi bi-clock me-1"></i> Lunes a Sábados 9 a 20hs
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mapa Integrado -->
                <div class="col-lg-7">
                    <div class="store-card h-100 p-0 d-flex flex-column" style="overflow: hidden;">
                        <div style="flex: 1; min-height: 350px;">
                            <iframe 
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3271.5628364776524!2d-57.95457908477283!3d-34.92049308037896!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x95a2e62b2c6b6b6b%3A0x6b6b6b6b6b6b6b6b!2sCalle%2039%201466%2C%20La%20Plata%2C%20Provincia%20de%20Buenos%20Aires!5e0!3m2!1ses-419!2sar!4v1234567890123!5m2!1ses-419!2sar" 
                                width="100%" 
                                height="100%" 
                                style="border:0;" 
                                allowfullscreen="" 
                                loading="lazy" 
                                referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </div>
                        
                        <!-- Botón debajo del mapa -->
                        <div class="text-center p-3" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));">
                            <a href="https://goo.gl/maps/uc65vobjeXTKT8zb8" target="_blank" class="btn btn-lg rounded-pill" style="background: var(--accent-color); color: var(--text-dark); font-weight: 600; box-shadow: 0 4px 12px rgba(0,0,0,0.3);">
                                <i class="bi bi-geo-alt me-2"></i>Abrir en Google Maps
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Redes Sociales -->
    <section class="py-5">
        <div class="container">
            <div class="text-center">
                <h3 class="fw-bold mb-3" style="color: var(--text-dark);">Seguinos en Redes</h3>
                <p class="text-muted mb-4">Conocé nuestras novedades, promociones y momentos mágicos</p>

                <div class="d-flex justify-content-center align-items-center gap-3 flex-wrap">
                    <a href="#" class="social-icon facebook">
                        <i class="bi bi-facebook"></i>
                    </a>
                    <a href="#" class="social-icon instagram">
                        <i class="bi bi-instagram"></i>
                    </a>
                    <a href="#" class="social-icon whatsapp">
                        <i class="bi bi-whatsapp"></i>
                    </a>
                </div>

                <p class="mt-4 text-muted small">
                    <i class="bi bi-hash"></i>Hobby toys - Compartí tus momentos con nosotros
                </p>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="py-5 cta-section text-white">
        <div class="container">
            <div class="row align-items-center justify-content-between">
                <div class="col-lg-7 text-center text-lg-start mb-3 mb-lg-0">
                    <h3 class="fw-bold mb-2">¿Listo para tu próxima aventura?</h3>
                    <p class="mb-0">Explorá nuestro catálogo y encontrá el juguete perfecto</p>
                </div>
                <div class="col-lg-4 text-center text-lg-end">
                    <a href="<?php echo esc_url( get_permalink(wc_get_page_id( 'shop' )) ); ?>" class="btn btn-custom btn-lg px-4 py-3 rounded-pill">
                        <i class="bi bi-shop me-2"></i>Ver Tienda
                    </a>
                </div>
            </div>
        </div>
    </section>

<?php
get_footer();