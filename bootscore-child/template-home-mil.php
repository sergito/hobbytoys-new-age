<?php
/**
 * Template Name: template home mil
 *
 * @package Bootscore Child
 * @version 1.0.0
*/

// Exit if accessed directly
defined('ABSPATH') || exit;

get_header();
?>

<?php the_post(); ?>

        <section class="hero-slider-section position-relative overflow-hidden" style="height: 600px;">
            <div id="mainHeroSlider" class="carousel slide carousel-fade h-100" data-bs-ride="carousel" data-bs-interval="6000">
                <div class="carousel-inner h-100">
                    


                    <!-- Slide 2: Banco Provincia - 20% OFF -->
                    <div class="carousel-item active h-100" style="background: #0066CC;">
                        <div class="container h-100 d-flex align-items-center position-relative" style="z-index: 2;">
                            <div class="row align-items-center w-100">
                                <div class="col-lg-6">
                                    <span class="badge px-4 py-2 mb-3" 
                                          style="background: #ffffff; color: #0066CC; font-weight: 700; font-size: 1rem; border-radius: 8px;">
                                        💳 PROMOCIÓN BANCARIA
                                    </span>
                                    <h1 class="display-2 fw-black text-white mb-4" style="line-height: 1.1;">
                                        10% OFF<br>
                                        <span style="color: #FFD700;">Hasta 4 Cuotas Sin Interés</span>
                                    </h1>
                                    <p class="lead text-white mb-4" style="font-weight: 500; font-size: 1.3rem;">
                                        La promoción es válida para compras<br>en el local y en la web
                                    </p>
                                    <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" 
                                       class="btn btn-lg px-5 py-3" 
                                       style="background: #ffffff; color: #0066CC; font-weight: 800; font-size: 1.2rem; border-radius: 12px; border: none;">
                                        🏪 Ver Tienda
                                    </a>
                                </div>
                                <div class="col-lg-6 text-center">
                                    <div class="hero-product-image" style="padding: 2rem;">
                                        <!-- Aquí va el logo del Banco Provincia que generarás -->
                                        <img src="https://hobbytoys.com.ar/wp-content/uploads/2025/12/banco-provincia-logo-sora-1.png" 
                                             alt="Banco Provincia Logo" 
                                             style="max-width: 100%; height: auto; max-height: 450px; object-fit: contain;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Slide 3: Envío El Mismo Día -->
                    <div class="carousel-item h-100" style="background: #10B981;">
                        <div class="container h-100 d-flex align-items-center position-relative" style="z-index: 2;">
                            <div class="row align-items-center w-100">
                                <div class="col-lg-6">
                                    <span class="badge px-4 py-2 mb-3" 
                                          style="background: #ffffff; color: #10B981; font-weight: 700; font-size: 1rem; border-radius: 8px;">
                                        🚚 ENVÍO RÁPIDO
                                    </span>
                                    <h1 class="display-2 fw-black text-white mb-4" style="line-height: 1.1;">
                                        ¡Recibilo<br>
                                        <span style="color: #FCD34D;">El Mismo Día!</span>
                                    </h1>
                                    <p class="lead text-white mb-4" style="font-weight: 500; font-size: 1.3rem;">
                                        Si sos de La Plata y comprás antes de las 13hs,<br>¡te lo enviamos el mismo día!
                                    </p>
                                    <a href="<?php echo esc_url(home_url('/envios')); ?>" 
                                       class="btn btn-lg px-5 py-3" 
                                       style="background: #FCD34D; color: #065F46; font-weight: 800; font-size: 1.2rem; border-radius: 12px; border: none;">
                                        📦 Ver Info de Envíos
                                    </a>
                                </div>
                                <div class="col-lg-6 text-center">
                                    <div class="hero-product-image" style="padding: 2rem;">
                                        <!-- Aquí va la ilustración del camión que generarás -->
                                        <img src="https://hobbytoys.com.ar/wp-content/uploads/2025/12/hero-camion-envio-2.png" 
                                             alt="Envío rápido La Plata" 
                                             style="max-width: 100%; height: auto; max-height: 450px; object-fit: contain;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Controles del slider -->
                <button class="carousel-control-prev" type="button" data-bs-target="#mainHeroSlider" data-bs-slide="prev" 
                        style="left: 20px; width: 50px; height: 50px; top: 50%; transform: translateY(-50%); border-radius: 50%; background: #ffffff; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                    <span class="carousel-control-prev-icon" style="filter: invert(1);"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#mainHeroSlider" data-bs-slide="next"
                        style="right: 20px; width: 50px; height: 50px; top: 50%; transform: translateY(-50%); border-radius: 50%; background: #ffffff; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                    <span class="carousel-control-next-icon" style="filter: invert(1);"></span>
                </button>

                <!-- Indicadores -->
                <div class="carousel-indicators" style="bottom: 30px;">
        
                    <button type="button" data-bs-target="#mainHeroSlider" data-bs-slide-to="1" class="active" 
                            style="width: 50px; height: 6px; border-radius: 3px; background: #ffffff; border: none; margin: 0 5px;"></button>
                    <button type="button" data-bs-target="#mainHeroSlider" data-bs-slide-to="2" 
                            style="width: 50px; height: 6px; border-radius: 3px; background: #ffffff; border: none; margin: 0 5px;"></button>
                </div>
            </div>
        </section>

        <!-- Sección ¿Por qué elegir Hobby Toys? -->
        <section class="py-5" style="background:#fff;">
            <div class="container">
                <div class="text-center mb-4">
                    <h2 class="fw-black" style="color: var(--primary-color); font-size: 2rem;">
                        ¿Por qué elegir Hobby Toys?
                    </h2>
                </div>

                <div class="row text-center g-4 justify-content-center">

                    <div class="col-6 col-md-3">
                        <div class="benefit-card p-4 h-100 rounded-4 shadow-sm bg-light">
                            <i class="bi bi-truck fs-1 mb-2" style="color:var(--primary-color);"></i>
                            <h6 class="fw-bold mb-2" style="color:var(--primary-color);">Envíos Gratis</h6>
                            <p class="small mb-0 text-muted">La Plata desde $50.000.<br>Resto del país desde $90.000.</p>
                        </div>
                    </div>

                    <div class="col-6 col-md-3">
                        <div class="benefit-card p-4 h-100 rounded-4 shadow-sm bg-light">
                            <i class="bi bi-lightning-charge fs-1 mb-2" style="color:var(--secondary-color);"></i>
                            <h6 class="fw-bold mb-2" style="color:var(--secondary-color);">Envíos en el día</h6>
                            <p class="small mb-0 text-muted">Zona La Plata y alrededores<br>comprando antes de las 13hs.</p>
                        </div>
                    </div>

                    <div class="col-6 col-md-3">
                        <div class="benefit-card p-4 h-100 rounded-4 shadow-sm bg-light">
                            <i class="bi bi-credit-card fs-1 mb-2" style="color:var(--accent-color);"></i>
                            <h6 class="fw-bold mb-2" style="color:var(--accent-color);">Cuotas sin interés</h6>
                            <p class="small mb-0 text-muted">Hasta 6 con Visa y Mastercard<br>bancarias desde la web.</p>
                        </div>
                    </div>

                    <div class="col-6 col-md-3">
                        <div class="benefit-card p-4 h-100 rounded-4 shadow-sm bg-light">
                            <i class="bi bi-whatsapp fs-1 mb-2" style="color:#25D366;"></i>
                            <h6 class="fw-bold mb-2" style="color:#25D366;">Atención personalizada</h6>
                            <p class="small mb-0 text-muted">Consultanos por WhatsApp<br>cualquier duda o producto.</p>
                        </div>
                    </div>

                </div>
            </div>
        </section>
  

  
        <!-- Sección Categorías por Edad -->
        <section class="py-5" style="background: var(--light-bg);">
            <div class="container">
                <div class="text-center mb-5">
                    <div class="age-emoji-header" style="font-size: 3rem; margin-bottom: 1rem;">👶🧒👦👧</div>
                    <h2 class="fw-black mb-3" style="color: var(--primary-color); font-size: 2.5rem; letter-spacing: 1px;">
                        Elegí por Edad
                    </h2>
                    <p class="lead" style="color: var(--text-dark); font-weight: 600;">Cada etapa merece juguetes especiales</p>
                </div>
                
                <div class="row g-4">
                    <!-- 0-3 años -->
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="age-category-card text-center p-4 rounded-4 h-100" 
                             style="background: linear-gradient(135deg, #FFE5EC 0%, #FFF0F5 100%); border: 3px solid #FFB6C1; transition: all 0.3s; cursor: pointer;">
                            <div class="age-icon mb-3" style="font-size: 4rem;">👶</div>
                            <h3 class="fw-black mb-2" style="color: var(--primary-color);">0-3 años</h3>
                            <p class="mb-3" style="color: var(--text-dark); font-weight: 600;">Primeras exploraciones</p>
                            <span class="badge rounded-pill px-3 py-2" style="background: var(--primary-color); color: white; font-weight: 700;">
                                Ver juguetes 👶
                            </span>
                        </div>
                    </div>
                    
                    <!-- 3-6 años -->
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="age-category-card text-center p-4 rounded-4 h-100" 
                             style="background: linear-gradient(135deg, #E8E5FF 0%, #F0EFFF 100%); border: 3px solid #C4B5FD; transition: all 0.3s; cursor: pointer;">
                            <div class="age-icon mb-3" style="font-size: 4rem;">🧒</div>
                            <h3 class="fw-black mb-2" style="color: var(--secondary-color);">3-6 años</h3>
                            <p class="mb-3" style="color: var(--text-dark); font-weight: 600;">Imaginación sin límites</p>
                            <span class="badge rounded-pill px-3 py-2" style="background: var(--secondary-color); color: white; font-weight: 700;">
                                Ver juguetes 🧒
                            </span>
                        </div>
                    </div>
                    
                    <!-- 7-12 años -->
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="age-category-card text-center p-4 rounded-4 h-100" 
                             style="background: linear-gradient(135deg, #FFF3D6 0%, #FFFAEB 100%); border: 3px solid #FBBF24; transition: all 0.3s; cursor: pointer;">
                            <div class="age-icon mb-3" style="font-size: 4rem;">👦</div>
                            <h3 class="fw-black mb-2" style="color: #D97706;">7-12 años</h3>
                            <p class="mb-3" style="color: var(--text-dark); font-weight: 600;">Aventuras y desafíos</p>
                            <span class="badge rounded-pill px-3 py-2" style="background: var(--accent-color); color: var(--text-dark); font-weight: 700;">
                                Ver juguetes 👦
                            </span>
                        </div>
                    </div>
                    
                    <!-- 12+ años -->
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="age-category-card text-center p-4 rounded-4 h-100" 
                             style="background: linear-gradient(135deg, #D1FAE5 0%, #ECFDF5 100%); border: 3px solid #34D399; transition: all 0.3s; cursor: pointer;">
                            <div class="age-icon mb-3" style="font-size: 4rem;">👧</div>
                            <h3 class="fw-black mb-2" style="color: #059669;">12+ años</h3>
                            <p class="mb-3" style="color: var(--text-dark); font-weight: 600;">Creatividad avanzada</p>
                            <span class="badge rounded-pill px-3 py-2" style="background: #059669; color: white; font-weight: 700;">
                                Ver juguetes 👧
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </section>



<!-- ===========================
     PRODUCTOS DESTACADOS (AIRE LIBRE)
=========================== -->
<section class="carrusel-seccion py-5 bs-gray-200">
  <div class="container">
     <div class="text-center mb-5">
            <div class="products-emoji-header" style="font-size: 3rem; margin-bottom: 1rem;">⭐🎯🔥✨</div>
            <h2 class="fw-black mb-3" style="color: var(--primary-color); font-size: 2.5rem; letter-spacing: 1px;">
                Productos Destacados
            </h2>
            <p class="lead" style="color: var(--text-dark); font-weight: 600;">¡A jugar al aire libre!</p>
     </div>

      <?php
      // Obtener productos de la categoría "aire-libre"
      $args = [
          'post_type'      => 'product',
          'posts_per_page' => 18,
          'orderby'        => 'rand',
          'post_status'    => 'publish',
          'fields'         => 'ids',
          'tax_query'      => [
              [
                  'taxonomy' => 'product_cat',
                  'field'    => 'slug',
                  'terms'    => 'aire-libre',
              ]
          ]
      ];
      
      $productosCarrusel2 = get_posts($args);
      ?>
      
      <div class="swiper productsCarruselHome5 woocommerce">
        <div class="swiper-wrapper">
          <?php foreach ( $productosCarrusel2 as $idProducto ) : ?>

            <?php
              $post_object = get_post( $idProducto );

              setup_postdata( $GLOBALS['post'] =& $post_object ); 

              wc_get_template_part( 'content', 'productCarrusel');
            ?>

          <?php endforeach; ?>
        </div>

        <!-- Flechas por defecto de Swiper -->
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
      </div>

      <div class="text-center mt-5">
            <a href="<?php echo esc_url(get_term_link('aire-libre', 'product_cat')); ?>" 
               class="btn btn-lg rounded-pill px-5 py-3" 
               style="background: var(--primary-color); color: white; font-weight: 900; font-size: 1.1rem;">
                <i class="bi bi-grid-3x3-gap-fill me-2"></i> Ver todos los productos de Aire Libre
            </a>
        </div>

    </div>
  </div>

</section>


<!-- SECCIÓN STICKERS COLLECTION (Banner Izquierdo + Carousel Derecho) -->
<section class="shop-section carrusel-seccion stickers-section py-5 bg-light">
    <div class="container-fluid">
        <div class="inner-container position-relative">
            <!-- Banner lateral izquierdo -->
            <div class="ads-block22 position-absolute text-center" 
                 style="left: auto; top: 0; width: 430px; background:#ee295b; padding: 60px 40px; min-height: 618px; overflow: hidden; z-index: 2000; border-radius:2rem">
                
                <div class="position-absolute top-0 start-0 w-100 h-100" 
                     style="background: url('https://hobbytoys.com.ar/wp-content/uploads/2025/12/juegos-mesa.jpg') center/cover; opacity: 0.15; z-index: 0; border-radius: var(--border-radius-xl);">
                </div>
                
                <div class="position-relative" style="z-index: 2;">
                    <span class="badge rounded-pill px-3 py-2 mb-3" 
                          style="background: rgba(255,185,0,0.9); color: var(--text-dark); font-weight: 700; text-transform: uppercase;">
                        Arte Creativo
                    </span>
                    <h2 class="fw-black text-white mb-3" style="font-size: 2.5rem; letter-spacing: 1px;">
                        Para los pequeños artistas 
                    </h2>
                    <h3 class="text-white mb-4" style="font-size: 1.8rem; font-weight: 700;">
                        <span style="color: var(--accent-color);">Desde</span> $599
                    </h3>
                    <a href="https://hobbytoys.com.ar/tienda/?product-cat=arte-y-manualidades" class="btn btn-lg rounded-pill px-4 py-3" 
                       style="background: var(--accent-color); color: var(--text-dark); font-weight: 900; border: 2px solid rgba(255,255,255,0.2);">
                        <i class="bi bi-palette me-2"></i>Crear Arte
                    </a>
                </div>
            </div>
            
            <!-- Contenido del carousel -->
            <div class="content-box" style="padding-left: 460px; z-index: 10; position: relative;">
                <div class="row">
                    <div class="col-12">
                        
                        <!-- INICIO Carousel de productos dinámicos -->
                        <div class="arts-carousel-container overflow-hidden">
                            <div class="swiper productsCarruselHome3 woocommerce">
                                <div class="swiper-wrapper">
                                    <?php 
                                    $args = [
                                        'post_type'      => 'product',
                                        'posts_per_page' => 8,
                                        'orderby'        => 'rand',
                                        'post_status'    => 'publish',
                                        'fields'         => 'ids'
                                    ];

                                    $productosCarrusel2 = get_posts($args);
                                    foreach ( $productosCarrusel2 as $idProducto ) : ?>

                                        <?php
                                        $post_object = get_post( $idProducto );

                                        setup_postdata( $GLOBALS['post'] =& $post_object ); 

                                        wc_get_template_part( 'content', 'productCarrusel');
                                        ?>

                                    <?php endforeach; ?>
                                </div>

                                <!-- Flechas por defecto de Swiper -->
                                <div class="swiper-button-next"></div>
                                <div class="swiper-button-prev"></div>
                            </div>

                        </div>
                        <!-- FIN Carousel de productos dinámicos -->
                    </div>
                </div>
            </div><!-- FIN /.content-box -->
        </div>
    </div><!-- FIN /.container-fluid -->    
</section>


<!-- SECCIÓN VEHÍCULOS Y RC (Banner Derecho + Carousel Izquierdo) -->
<section class="shop-section vehicles-section py-5 carrusel-seccion bg-white">
    <div class="container-fluid">
        <div class="inner-container position-relative">
            <!-- Banner lateral derecho -->
            <div class="ads-block22 position-absolute text-center" 
                 style="right: 19px; left:auto; top: 0; width: 430px; background:#484a99; padding: 60px 40px; min-height: 626px; overflow: hidden; z-index: 2000; border-radius:2rem">
                
                <div class="position-absolute top-0 start-0 w-100 h-100" 
                     style="background: url('https://hobbytoys.com.ar/wp-content/uploads/2025/12/velocidad.jpg') center/cover; opacity: 0.15; z-index: 0; border-radius: var(--border-radius-xl);"></div>
                
                <div class="position-relative" style="z-index: 2;">
                    <span class="badge rounded-pill px-3 py-2 mb-3" 
                          style="background: rgba(255,185,0,0.9); color: var(--text-dark); font-weight: 700; text-transform: uppercase;">
                        AUTOS, CAMIONETAS Y CAMIONES
                    </span>
                    <h2 class="fw-black text-white mb-3" style="font-size: 2.5rem; letter-spacing: 1px;">
                        Mundo sobre ruedas 
                    </h2>
                    <h3 class="text-white mb-4" style="font-size: 1.8rem; font-weight: 700;">
                        <span style="color: var(--accent-color);">Desde</span> $3.999
                    </h3>
                    <a href="https://hobbytoys.com.ar/producto-categoria/pistas-y-vehiculos/autos-camionetas-y-camiones/" class="btn btn-lg rounded-pill px-4 py-3" 
                       style="background: var(--accent-color); color: var(--text-dark); font-weight: 900; border: 2px solid rgba(255,255,255,0.2);">
                        <i class="bi bi-speedometer2 me-2"></i>Ver Autos, camionetas y camiones
                    </a>
                </div>
            </div>
            
            <!-- Contenido del carousel -->
            <div class="content-box" style="padding-right: 460px; z-index: 10; position: relative;">
                <div class="row">
                    <div class="col-12">
                    
                        <!-- INICIO Carousel de productos dinámicos -->
                        <div class="vehicles-carousel-container overflow-hidden">
                            <?php
                               $args = [
                                    'post_type'      => 'product',
                                    'posts_per_page' => 8,
                                    'post_status'    => 'publish',
                                    'fields'         => 'ids',
                                    'tax_query'      => [
                                        [
                                            'taxonomy' => 'product_cat',
                                            'field'    => 'slug',
                                            'terms'    => 'autos-camionetas-y-camiones'
                                        ]
                                    ]
                                ];

                                $productosCarrusel2 = get_posts($args);
                            ?>
                            <div class="swiper productsCarruselHome3 woocommerce">
                                <div class="swiper-wrapper">
                                    <?php foreach ( $productosCarrusel2 as $idProducto ) : ?>

                                        <?php
                                        $post_object = get_post( $idProducto );

                                        setup_postdata( $GLOBALS['post'] =& $post_object ); 

                                        wc_get_template_part( 'content', 'productCarrusel');
                                        ?>

                                    <?php endforeach; ?>
                                </div>

                                <!-- Flechas por defecto de Swiper -->
                                <div class="swiper-button-next"></div>
                                <div class="swiper-button-prev"></div>
                            </div>

                        </div>
                        <!-- FIN Carousel de productos dinámicos -->

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<?php
get_footer();