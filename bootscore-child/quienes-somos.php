<?php

/**
 * Template Name: Quienes somos
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Bootscore
 * @version 6.1.0
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

get_header();
?>
   <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300;400;500;600;700&display=swap" rel="stylesheet">
     <style>
        :root {
            --primary-color: #EE285B;
            --secondary-color: #534fb5;
            --accent-color: #ffb900;
            --light-bg: #f4efe8;
            --text-dark: #2C3E50;
            --text-light: #7F8C8D;
        }

        body {
            font-family: 'Fredoka', sans-serif;
            line-height: 1.6;
            color: var(--text-dark);
            background: white;
        }

        .hero-section {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 80px 0;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="80" cy="40" r="3" fill="rgba(255,255,255,0.1)"/><circle cx="40" cy="80" r="2" fill="rgba(255,255,255,0.1)"/></svg>');
            animation: float 20s infinite linear;
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }

        .section-title {
            position: relative;
            margin-bottom: 3rem;
            color: var(--text-dark);
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 4px;
            background: var(--accent-color);
            border-radius: 2px;
        }

        .story-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(238, 40, 91, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            height: 100%;
        }

        .story-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(238, 40, 91, 0.15);
        }

        .story-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(45deg, var(--primary-color), var(--accent-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
            color: white;
        }

        .image-container {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(83, 79, 181, 0.15);
            transition: transform 0.3s ease;
        }

        .image-container:hover {
            transform: scale(1.05);
        }

        .image-container img {
            width: 100%;
            height: 300px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .values-section {
            background: linear-gradient(135deg, var(--light-bg), #fff);
            padding: 80px 0;
        }

        .value-item {
            text-align: center;
            padding: 2rem;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(83, 79, 181, 0.08);
            transition: all 0.3s ease;
            height: 100%;
            border: 1px solid rgba(244, 239, 232, 0.5);
        }

        .value-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(238, 40, 91, 0.15);
            border-color: var(--accent-color);
        }

        .value-icon {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .timeline {
            position: relative;
            padding: 2rem 0;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 50%;
            top: 0;
            bottom: 0;
            width: 4px;
            background: var(--secondary-color);
            transform: translateX(-50%);
        }

        .timeline-item {
            position: relative;
            margin-bottom: 3rem;
            width: 50%;
        }

        .timeline-item:nth-child(odd) {
            left: 0;
            padding-right: 3rem;
        }

        .timeline-item:nth-child(even) {
            left: 50%;
            padding-left: 3rem;
        }

        .timeline-content {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(83, 79, 181, 0.1);
            position: relative;
            border: 1px solid var(--light-bg);
        }

        .timeline-year {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 60px;
            height: 60px;
            background: var(--primary-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 0.9rem;
            border: 3px solid white;
            box-shadow: 0 3px 10px rgba(238, 40, 91, 0.3);
        }

        .timeline-item:nth-child(odd) .timeline-year {
            right: -3rem;
        }

        .timeline-item:nth-child(even) .timeline-year {
            left: -3rem;
        }

        .timeline-bg {
            background: var(--light-bg);
        }

        .custom-badge {
            background: rgba(255, 255, 255, 0.2) !important;
            color: white !important;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .cta-section {
            background: var(--primary-color) !important;
        }

        .btn-custom {
            background: white;
            color: var(--primary-color);
            border: 2px solid white;
            font-weight: 600;
        }

        .btn-custom:hover {
            background: var(--accent-color);
            color: white;
            border-color: var(--accent-color);
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .timeline::before {
                left: 2rem;
            }

            .timeline-item {
                width: 100%;
                left: 0 !important;
                padding-left: 4rem !important;
                padding-right: 1rem !important;
            }

            .timeline-year {
                left: -1.5rem !important;
                right: auto !important;
            }
        }
    </style>
  <div id="content" class="site-content <?= apply_filters('bootscore/class/container', 'container', 'page-sidebar-none'); ?> <?= apply_filters('bootscore/class/content/spacer', 'pt-4 pb-5', 'page-sidebar-none'); ?>">
    <div id="primary" class="content-area">
      
      <?php do_action( 'bootscore_after_primary_open', 'page-sidebar-none' ); ?>

      <main id="main" class="site-main">
    <!-- Hero Section -->
        <section class="hero-section">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <h1 class="display-4 fw-bold mb-4">Creando Sonrisas Desde 1985</h1>
                        <p class="lead mb-4">En Hobby toys, transformamos la imaginación en realidad a través de juguetes únicos que inspiran, educan y divierten a niños de todas las edades.</p>
                        <div class="d-flex gap-3 flex-wrap">
                            <span class="badge custom-badge px-3 py-2 rounded-pill">
                                <i class="bi bi-heart-fill me-1" style="color: var(--accent-color);"></i> +38 años de experiencia
                            </span>
                            <span class="badge custom-badge px-3 py-2 rounded-pill">
                                <i class="bi bi-star-fill me-1" style="color: var(--accent-color);"></i> Miles de familias felices
                            </span>
                        </div>
                    </div>
                    <div class="col-lg-6 text-center">
                        <div class="image-container">
                            <img src="https://images.unsplash.com/photo-1558877385-8c0c4e87cd4a?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" alt="Juguetes coloridos" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Nuestra Historia -->
        <section class="py-5">
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center mb-5">
                        <h2 class="display-5 fw-bold section-title">Nuestra Historia</h2>
                        <p class="lead text-muted">Un viaje lleno de magia, creatividad y diversión</p>
                    </div>
                </div>

                <div class="row g-4 mb-5">
                    <div class="col-lg-4">
                        <div class="story-card">
                            <div class="story-icon">
                                <i class="bi bi-lightbulb"></i>
                            </div>
                            <h4 class="text-center mb-3" style="color: var(--secondary-color);">El Comienzo</h4>
                            <p class="text-center text-muted">Todo comenzó en 1985 cuando María y Carlos Rodríguez, una joven pareja apasionada por la educación infantil, decidieron abrir una pequeña tienda de juguetes en el corazón de la ciudad. Su visión era simple pero poderosa: crear un espacio donde los niños pudieran descubrir juguetes que no solo los divirtieran, sino que también estimularan su creatividad y aprendizaje.</p>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="story-card">
                            <div class="story-icon">
                                <i class="bi bi-graph-up-arrow"></i>
                            </div>
                            <h4 class="text-center mb-3" style="color: var(--secondary-color);">Crecimiento</h4>
                            <p class="text-center text-muted">A lo largo de los años 90, Hobby toys se convirtió en el destino favorito de las familias. Expandimos nuestro catálogo incluyendo juguetes educativos, productos artesanales y las últimas tendencias del mercado. Nuestra reputación de calidad y servicio excepcional nos permitió abrir tres sucursales más y establecer nuestra presencia online.</p>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="story-card">
                            <div class="story-icon">
                                <i class="bi bi-globe"></i>
                            </div>
                            <h4 class="text-center mb-3" style="color: var(--secondary-color);">Innovación</h4>
                            <p class="text-center text-muted">Hoy, en 2024, somos una empresa líder en el sector juguetero, con presencia nacional y un catálogo de más de 5,000 productos. Seguimos siendo una empresa familiar, ahora dirigida por la segunda generación, manteniendo los mismos valores de calidad, seguridad y compromiso con el desarrollo infantil que nos caracterizaron desde el primer día.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Timeline -->
        <section class="py-5 timeline-bg">
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center mb-5">
                        <h2 class="display-5 fw-bold section-title">Momentos Clave</h2>
                    </div>
                </div>
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-content">
                            <h5 class="fw-bold mb-3" style="color: var(--secondary-color);">Fundación de Hobby toys</h5>
                            <p class="mb-0">María y Carlos abren la primera tienda con apenas 200 juguetes cuidadosamente seleccionados.</p>
                        </div>
                        <div class="timeline-year">1985</div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-content">
                            <h5 class="fw-bold mb-3" style="color: var(--secondary-color);">Primera Expansión</h5>
                            <p class="mb-0">Apertura de la segunda sucursal y lanzamiento de nuestra línea de juguetes educativos exclusivos.</p>
                        </div>
                        <div class="timeline-year">1995</div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-content">
                            <h5 class="fw-bold mb-3" style="color: var(--secondary-color);">Era Digital</h5>
                            <p class="mb-0">Lanzamiento de nuestra tienda online y sistema de delivery a domicilio en toda la región.</p>
                        </div>
                        <div class="timeline-year">2010</div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-content">
                            <h5 class="fw-bold mb-3" style="color: var(--secondary-color);">Presente</h5>
                            <p class="mb-0">Líder del mercado con 12 sucursales, presencia online consolidada y más de 50 empleados.</p>
                        </div>
                        <div class="timeline-year">2024</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Galería de Imágenes -->
        <section class="py-5">
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center mb-5">
                        <h2 class="display-5 fw-bold section-title">Nuestro Mundo</h2>
                        <p class="lead text-muted">Espacios diseñados para la diversión y el aprendizaje</p>
                    </div>
                </div>
                <div class="row g-4">
                    <div class="col-lg-4 col-md-6">
                        <div class="image-container">
                            <img src="https://images.unsplash.com/photo-1596461404969-9ae70f2830c1?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" alt="Tienda Hobby toys" class="img-fluid">
                        </div>
                        <div class="text-center mt-3">
                            <h5 class="fw-bold" style="color: var(--secondary-color);">Nuestras Tiendas</h5>
                            <p class="text-muted">Espacios mágicos donde cada rincón está diseñado para despertar la imaginación</p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="image-container">
                            <img src="https://images.unsplash.com/photo-1503454537195-1dcabb73ffb9?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" alt="Taller de juguetes" class="img-fluid">
                        </div>
                        <div class="text-center mt-3">
                            <h5 class="fw-bold" style="color: var(--secondary-color);">Centro de Innovación</h5>
                            <p class="text-muted">Donde desarrollamos y probamos nuestros juguetes exclusivos con la ayuda de expertos</p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mx-auto">
                        <div class="image-container">
                            <img src="https://images.unsplash.com/photo-1581833971358-2c8b550f87b3?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" alt="Niños jugando" class="img-fluid">
                        </div>
                        <div class="text-center mt-3">
                            <h5 class="fw-bold" style="color: var(--secondary-color);">Área de Juegos</h5>
                            <p class="text-muted">Espacios seguros donde los niños pueden probar los juguetes antes de llevarlos a casa</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Valores -->
        <section class="values-section">
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center mb-5">
                        <h2 class="display-5 fw-bold section-title">Nuestros Valores</h2>
                        <p class="lead text-muted">Los principios que guían cada decisión que tomamos</p>
                    </div>
                </div>
                <div class="row g-4">
                    <div class="col-lg-3 col-md-6">
                        <div class="value-item">
                            <div class="value-icon">
                                <i class="bi bi-shield-check"></i>
                            </div>
                            <h5 class="fw-bold mb-3" style="color: var(--secondary-color);">Seguridad</h5>
                            <p class="text-muted">Todos nuestros productos pasan por rigurosos controles de calidad y cumplen con los más altos estándares de seguridad internacional.</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="value-item">
                            <div class="value-icon">
                                <i class="bi bi-book"></i>
                            </div>
                            <h5 class="fw-bold mb-3" style="color: var(--secondary-color);">Educación</h5>
                            <p class="text-muted">Creemos en el poder del juego como herramienta de aprendizaje, seleccionando juguetes que estimulen el desarrollo cognitivo y emocional.</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="value-item">
                            <div class="value-icon">
                                <i class="bi bi-heart"></i>
                            </div>
                            <h5 class="fw-bold mb-3" style="color: var(--secondary-color);">Familia</h5>
                            <p class="text-muted">Entendemos la importancia de los momentos familiares y ofrecemos productos que fortalecen los vínculos entre padres e hijos.</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="value-item">
                            <div class="value-icon">
                                <i class="bi bi-recycle"></i>
                            </div>
                            <h5 class="fw-bold mb-3" style="color: var(--secondary-color);">Sostenibilidad</h5>
                            <p class="text-muted">Comprometidos con el medio ambiente, priorizamos juguetes eco-amigables y materiales sostenibles en nuestra selección.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-5 cta-section text-white">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h3 class="fw-bold mb-3">¿Listo para crear nuevas aventuras?</h3>
                        <p class="lead mb-0">Visita nuestras tiendas o explora nuestro catálogo online. En Hobby toys, cada día es una nueva oportunidad para jugar, aprender y crecer.</p>
                    </div>
                    <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                        <a href="#" class="btn btn-custom btn-lg px-4 py-3 rounded-pill">
                            <i class="bi bi-shop me-2"></i>Visitar Tienda
                        </a>
                    </div>
                </div>
            </div>
        </section>

      </main>

    </div>
  </div>
 <script>
        // Animación de aparición al hacer scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Aplicar animación a las tarjetas
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.story-card, .value-item, .timeline-content');
            cards.forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';
                card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                observer.observe(card);
            });
        });
    </script>
<?php
get_footer();