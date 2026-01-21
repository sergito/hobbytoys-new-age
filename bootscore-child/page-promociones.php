<?php
/**
 * Template Name: Promociones
 *
 * @package Bootscore
 * @version 6.1.0
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

get_header();
?>
<style>
  .shiping-card {
    height:0!important;
  }
</style>
<!-- Header Compacto -->
<section class="shop-header">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <div class="mb-3">
                    <img src="https://hobbytoys.com.ar/wp-content/uploads/2024/02/Hobby-Toys-_-emoji-lineal-rojo-e1719435735562.jpg" 
                        alt="Promos felices"
                        class="rounded-circle"
                        style="width: 80px; height: 80px; object-fit: cover; box-shadow: 0 4px 12px rgba(238,40,91,0.15);">
                </div>
                <h1 class="shop-title mb-3">Promos Felices</h1>
                <p class="lead text-muted mb-0">Aprovechá nuestras promociones y pagá menos por tus juguetes favoritos</p>
            </div>
        </div>
    </div>
</section>

<!-- Promociones Principales -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                
                <!-- Cuotas sin Interés -->
                <div class="shipping-card mb-3" style="background: linear-gradient(to right, rgba(83,79,181,0.05), #fff); box-shadow: -5px 0 0 0 var(--secondary-color) inset;">
                    <div class="row align-items-center">
                        <div class="col-md-2 text-center mb-3 mb-md-0">
                            <i class="bi bi-credit-card-2-front" style="font-size: 2.5rem; color: var(--secondary-color);"></i>
                        </div>
                        <div class="col-md-7">
                            <h4 class="fw-bold mb-2" style="color: var(--secondary-color);">Hasta 6 Cuotas sin Interés</h4>
                            <p class="mb-1">Con <strong>Visa</strong> y <strong>Mastercard bancarias</strong></p>
                            <p class="text-muted small mb-0">Solo para compras a través de nuestra web</p>
                        </div>
                        <div class="col-md-3 text-center mt-3 mt-md-0">
                            <span class="badge" style="background: var(--accent-color); color: var(--text-dark); font-size: .95rem; padding: .7rem 1.2rem;">
                                Exclusivo Online
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Banco Provincia -->
                <div class="shipping-card mb-3" style="background: linear-gradient(to right, rgba(238,40,91,0.05), #fff); box-shadow: -5px 0 0 0 var(--primary-color) inset;">
                    <div class="row align-items-center">
                        <div class="col-md-2 text-center mb-3 mb-md-0">
                            <img src="https://hobbytoys.com.ar/wp-content/uploads/2020/10/logo_provincia.png" 
                                alt="Banco Provincia" 
                                style="max-width: 80px; height: auto;">
                        </div>
                        <div class="col-md-7">
                            <h5 class="fw-bold mb-2" style="color: var(--primary-color);">4 Cuotas sin Interés + 10% Reintegro</h5>
                            <p class="mb-1">Con tarjetas <strong>Banco Provincia</strong></p>
                            <p class="text-muted small mb-0">Válido hasta el <strong>30/09/2025</strong></p>
                        </div>
                        <div class="col-md-3 text-center mt-3 mt-md-0">
                            <span class="badge bg-success" style="font-size: .9rem; padding: .6rem 1rem;">
                                <i class="bi bi-infinity me-1"></i>Sin Tope
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Transferencia Bancaria -->
                <div class="shipping-card mb-3" style="background: linear-gradient(to right, rgba(255,185,0,0.05), #fff); box-shadow: -5px 0 0 0 var(--accent-color) inset;">
                    <div class="row align-items-center">
                        <div class="col-md-2 text-center mb-3 mb-md-0">
                            <i class="bi bi-bank" style="font-size: 2.5rem; color: var(--accent-color);"></i>
                        </div>
                        <div class="col-md-10">
                            <h5 class="fw-bold mb-2" style="color: var(--secondary-color);">Transferencia Bancaria</h5>
                            <p class="mb-1">Recibirás los datos bancarios luego de tu compra</p>
                            <p class="text-muted small mb-0">Para retirar o procesar tu envío, el pago debe estar acreditado</p>
                        </div>
                    </div>
                </div>

                <!-- Envíos Gratis -->
                <div class="shipping-card" style="background: linear-gradient(135deg, rgba(238,40,91,0.08), rgba(255,185,0,0.08)); box-shadow: 0 8px 24px rgba(238,40,91,0.12);">
                    <div class="text-center mb-3">
                        <i class="bi bi-truck" style="font-size: 3rem; color: var(--primary-color);"></i>
                        <h4 class="fw-bold mt-2 mb-3" style="color: var(--primary-color);">Envíos Gratis</h4>
                    </div>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="text-center p-3 rounded" style="background: rgba(255,255,255,0.8); box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
                                <span class="badge bg-primary mb-2" style="font-size: .95rem; padding: .6rem 1.2rem;">
                                    La Plata y Alrededores
                                </span>
                                <p class="mb-1 fw-bold" style="color: var(--secondary-color); font-size: 1.3rem;">$50.000</p>
                                <p class="text-muted small mb-0">Envío en 24-48hs hábiles</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-center p-3 rounded" style="background: rgba(255,255,255,0.8); box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
                                <span class="badge bg-success mb-2" style="font-size: .95rem; padding: .6rem 1.2rem;">
                                    Todo el País
                                </span>
                                <p class="mb-1 fw-bold" style="color: var(--secondary-color); font-size: 1.3rem;">$90.000</p>
                                <p class="text-muted small mb-0">Sin cargo a cualquier punto</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center mt-3">
                        <a href="<?php echo esc_url(home_url('/envios')); ?>" class="btn btn-sm rounded-pill" style="background: var(--primary-color); color: #fff;">
                            <i class="bi bi-info-circle me-1"></i>Ver Política de Envíos
                        </a>
                    </div>
                </div>

                <!-- Alerta Final -->
                <div class="alert text-center mt-4 mb-0" style="background: var(--secondary-color); color: #fff; border: none; border-radius: 1rem; box-shadow: 0 4px 16px rgba(83,79,181,0.2);">
                    <i class="bi bi-gift-fill me-2"></i>
                    <strong>Consultá otras promos bancarias vigentes en nuestra tienda</strong>
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
                        <h3 class="fw-bold mb-2">¿Listo para Aprovechar?</h3>
                        <p class="mb-0">Explorá nuestros productos y aplicá estas promociones</p>
                    </div>
                    <div class="col-lg-4 text-center text-lg-end">
                        <a href="<?php echo esc_url( get_permalink(wc_get_page_id( 'shop' )) ); ?>" class="btn btn-custom btn-lg px-4 py-3 rounded-pill">
                            <i class="bi bi-shop me-2"></i>Ver Tienda
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
get_footer();