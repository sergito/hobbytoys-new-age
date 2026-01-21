<?php
/**
 * Template Name: Registro Mayorista
 * Formulario para registro de clientes mayoristas
 *
 * @package HobbyToys
 * @version 2.0.0
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

get_header();
?>

<!-- CSS ESPECÍFICO -->
<style>
.mayorista-section {
  padding: 60px 0;
  background: linear-gradient(135deg, var(--light-bg) 0%, #fff 100%);
}

.form-mayorista {
  background: #fff;
  border-radius: 2rem;
  box-shadow: 0 20px 60px rgba(238,40,91,0.08);
  padding: 3rem;
  border: 2px solid rgba(238,40,91,0.1);
}

.form-mayorista .form-label {
  font-weight: 700;
  color: var(--text-dark);
  margin-bottom: 0.5rem;
  font-size: 0.95rem;
}

.form-mayorista .form-control,
.form-mayorista .form-select {
  border: 2px solid #e9ecef;
  border-radius: 1rem;
  padding: 0.75rem 1rem;
  font-weight: 500;
  transition: all 0.3s ease;
}

.form-mayorista .form-control:focus,
.form-mayorista .form-select:focus {
  border-color: var(--primary-color);
  box-shadow: 0 0 0 0.2rem rgba(238,40,91,0.15);
}

.required::after {
  content: " *";
  color: var(--primary-color);
  font-weight: bold;
}

.btn-mayorista {
  background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
  color: white;
  border: none;
  border-radius: 1.5rem;
  padding: 1rem 3rem;
  font-weight: 700;
  font-size: 1.1rem;
  transition: all 0.3s ease;
  box-shadow: 0 8px 25px rgba(238,40,91,0.25);
}

.btn-mayorista:hover {
  transform: translateY(-2px);
  box-shadow: 0 12px 35px rgba(238,40,91,0.35);
  background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
}

@media (max-width: 768px) {
  .form-mayorista {
    padding: 2rem 1.5rem;
    border-radius: 1.5rem;
  }
  
  .btn-mayorista {
    width: 100%;
  }
}
</style>

    <!-- Header Compacto -->
    <section class="shop-header">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <div class="mb-3">
                        <i class="bi bi-shop" style="font-size: 4rem; color: var(--primary-color);"></i>
                    </div>
                    <h1 class="shop-title mb-3">Registro Mayorista</h1>
                    <p class="lead text-muted mb-0">Accedé a precios especiales completando este formulario</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Beneficios Compactos -->
    <section class="py-4" style="background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="row g-2 text-white text-center">
                        <div class="col-md-3 col-6">
                            <i class="bi bi-percent" style="font-size: 1.8rem;"></i>
                            <p class="mb-0 small fw-bold mt-1">Condiciones especiales</p>
                        </div>
                        <div class="col-md-3 col-6">
                            <i class="bi bi-truck" style="font-size: 1.8rem;"></i>
                            <p class="mb-0 small fw-bold mt-1">Envíos a todo el país</p>
                        </div>
                        <div class="col-md-3 col-6">
                            <i class="bi bi-person-badge" style="font-size: 1.8rem;"></i>
                            <p class="mb-0 small fw-bold mt-1">Asesor personalizado</p>
                        </div>
                        <div class="col-md-3 col-6">
                            <i class="bi bi-clock-history" style="font-size: 1.8rem;"></i>
                            <p class="mb-0 small fw-bold mt-1">Armado de pedidos en 24-48hs</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Formulario Simplificado -->
    <section class="mayorista-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="form-mayorista">
                        
                        <!-- Alerta Info -->
                        <div class="alert text-center mb-4" style="background: rgba(83,79,181,0.1); border: 1px solid rgba(83,79,181,0.2); color: var(--secondary-color); border-radius: 1rem;">
                            <i class="bi bi-info-circle-fill me-2"></i>
                            <strong>Tu solicitud será revisada en 24-48 horas hábiles</strong>
                        </div>

                        <!-- Formulario -->
                        <form id="formularioMayorista" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" novalidate>
                            <?php wp_nonce_field('registro_mayorista_nonce', 'mayorista_nonce'); ?>
                            <input type="hidden" name="action" value="registro_mayorista">
                            
                            <div class="row g-3">
                                <!-- Razón Social / Nombre -->
                                <div class="col-12">
                                    <label for="razonSocial" class="form-label required">Razón Social / Nombre del Negocio</label>
                                    <input type="text" class="form-control" id="razonSocial" name="razon_social" placeholder="Juguetería ABC S.A." required>
                                </div>

                                <!-- CUIT -->
                                <div class="col-md-6">
                                    <label for="cuit" class="form-label required">CUIT</label>
                                    <input type="text" class="form-control" id="cuit" name="cuit" placeholder="20-12345678-9" pattern="[0-9]{2}-[0-9]{8}-[0-9]{1}" required>
                                </div>

                                <!-- Tipo de Negocio -->
                                <div class="col-md-6">
                                    <label for="tipoNegocio" class="form-label required">Tipo de Negocio</label>
                                    <select class="form-select" id="tipoNegocio" name="tipo_negocio" required>
                                        <option value="">Seleccionar...</option>
                                        <option value="jugueteria">Juguetería</option>
                                        <option value="libreria">Librería</option>
                                        <option value="bazar">Bazar</option>
                                        <option value="supermercado">Supermercado</option>
                                        <option value="distribuidor">Distribuidor</option>
                                        <option value="venta_online">Venta Online</option>
                                        <option value="otro">Otro</option>
                                    </select>
                                </div>

                                <!-- Email -->
                                <div class="col-md-6">
                                    <label for="email" class="form-label required">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="contacto@ejemplo.com" required>
                                </div>

                                <!-- Teléfono -->
                                <div class="col-md-6">
                                    <label for="telefono" class="form-label required">Teléfono</label>
                                    <input type="tel" class="form-control" id="telefono" name="telefono" placeholder="0221 123-4567" required>
                                </div>

                                <!-- Provincia -->
                                <div class="col-md-6">
                                    <label for="provincia" class="form-label required">Provincia</label>
                                    <select class="form-select" id="provincia" name="provincia" required>
                                        <option value="">Seleccionar...</option>
                                        <option value="Buenos Aires">Buenos Aires</option>
                                        <option value="CABA">CABA</option>
                                        <option value="Catamarca">Catamarca</option>
                                        <option value="Chaco">Chaco</option>
                                        <option value="Chubut">Chubut</option>
                                        <option value="Córdoba">Córdoba</option>
                                        <option value="Corrientes">Corrientes</option>
                                        <option value="Entre Ríos">Entre Ríos</option>
                                        <option value="Formosa">Formosa</option>
                                        <option value="Jujuy">Jujuy</option>
                                        <option value="La Pampa">La Pampa</option>
                                        <option value="La Rioja">La Rioja</option>
                                        <option value="Mendoza">Mendoza</option>
                                        <option value="Misiones">Misiones</option>
                                        <option value="Neuquén">Neuquén</option>
                                        <option value="Río Negro">Río Negro</option>
                                        <option value="Salta">Salta</option>
                                        <option value="San Juan">San Juan</option>
                                        <option value="San Luis">San Luis</option>
                                        <option value="Santa Cruz">Santa Cruz</option>
                                        <option value="Santa Fe">Santa Fe</option>
                                        <option value="Santiago del Estero">Santiago del Estero</option>
                                        <option value="Tierra del Fuego">Tierra del Fuego</option>
                                        <option value="Tucumán">Tucumán</option>
                                    </select>
                                </div>

                                <!-- Localidad -->
                                <div class="col-md-6">
                                    <label for="localidad" class="form-label required">Localidad</label>
                                    <input type="text" class="form-control" id="localidad" name="localidad" placeholder="La Plata" required>
                                </div>

                                <!-- Comentarios -->
                                <div class="col-12">
                                    <label for="comentarios" class="form-label">Comentarios Adicionales</label>
                                    <textarea class="form-control" id="comentarios" name="comentarios" rows="3" placeholder="Contanos más sobre tu negocio, qué productos te interesan, etc. (opcional)"></textarea>
                                </div>

                                <div class="col-12">
                                    <div class="d-flex justify-content-center">
                                        <div class="g-recaptcha" data-sitekey="6LfpHSAsAAAAACDaJd4ObBBwCJddclvoDpsq9Fd6"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Botón de Envío -->
                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-mayorista">
                                    <i class="bi bi-send-fill me-2"></i>
                                    Enviar Solicitud
                                </button>
                                <div class="mt-3">
                                    <small class="text-muted">
                                        <i class="bi bi-shield-check"></i>
                                        Al enviar aceptás nuestros términos y condiciones. Tus datos están protegidos.
                                    </small>
                                </div>
                            </div>
                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

<!-- JavaScript de Validación -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formularioMayorista');
    const cuitInput = document.getElementById('cuit');
    
    // Validación y formato de CUIT
    cuitInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        
        if (value.length <= 11) {
            if (value.length > 2) {
                value = value.substring(0, 2) + '-' + value.substring(2);
            }
            if (value.length > 11) {
                value = value.substring(0, 11) + '-' + value.substring(11);
            }
            e.target.value = value;
        }
    });
    
    // Validación del formulario
    form.addEventListener('submit', function(e) {
        let isValid = true;
        const requiredFields = form.querySelectorAll('[required]');
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });
        
        // Validar email
        const email = document.getElementById('email');
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email.value)) {
            email.classList.add('is-invalid');
            isValid = false;
        }
        
        // Validar CUIT
        const cuit = document.getElementById('cuit');
        const cuitRegex = /^\d{2}-\d{8}-\d{1}$/;
        if (cuit.value && !cuitRegex.test(cuit.value)) {
            cuit.classList.add('is-invalid');
            isValid = false;
        }
        
        if (!isValid) {
            e.preventDefault();
            alert('Por favor, completá todos los campos requeridos correctamente.');
        }
    });
    
    // Limpiar errores al escribir
    form.addEventListener('input', function(e) {
        if (e.target.classList.contains('is-invalid')) {
            e.target.classList.remove('is-invalid');
        }
    });
});
</script>

<?php get_footer(); ?>