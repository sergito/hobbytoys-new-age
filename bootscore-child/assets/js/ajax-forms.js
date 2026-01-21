/**
 * Sistema de Formularios AJAX - Hobby Toys
 * Maneja Newsletter, Contacto y Registro Mayorista
 * 
 * @package HobbyToys
 * @version 2.0.0
 */

(function($) {
    'use strict';

    // Verificar que htAjax esté disponible
    if (typeof htAjax === 'undefined') {
        console.error('htAjax no está definido. Verificá que el script esté correctamente localizado.');
        return;
    }

    console.log('✓ Sistema de formularios cargado');
    console.log('✓ reCAPTCHA Site Key:', htAjax.recaptcha_site_key);


    // ============================================
    // NEWSLETTER - Formulario del Footer
    // ============================================
    $('#newsletter-form').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const submitBtn = form.find('button[type="submit"]');
        const emailInput = $('#newsletter_email');
        const messageDiv = $('#newsletter-message');
        const email = emailInput.val().trim();
        const originalBtnHtml = submitBtn.html();
        
        // Validación básica
        if (!email || !isValidEmail(email)) {
            showMessage(messageDiv, 'Por favor ingresá un email válido', 'danger');
            emailInput.addClass('is-invalid').focus();
            return;
        }
        
        // Limpiar estados
        emailInput.removeClass('is-invalid');
        
        // Deshabilitar botón y mostrar loading
        submitBtn.prop('disabled', true);
        submitBtn.html('<span class="spinner-border spinner-border-sm"></span>');
        messageDiv.removeClass('text-success text-danger').html('');
        
        // Preparar datos
        const formData = new FormData(this);
        
        // Enviar AJAX
        $.ajax({
            url: htAjax.ajaxurl,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    // Éxito
                    showMessage(messageDiv, response.data.message, 'success');
                    form[0].reset();
                    
                    // Confetti (opcional)
                    if (typeof confetti !== 'undefined') {
                        confetti({
                            particleCount: 100,
                            spread: 70,
                            origin: { y: 0.6 }
                        });
                    }
                } else {
                    // Error del servidor
                    showMessage(messageDiv, response.data.message, 'danger');
                    emailInput.addClass('is-invalid');
                }
            },
            error: function(xhr, status, error) {
                console.error('Newsletter Error:', error);
                showMessage(messageDiv, 'Hubo un error. Por favor intentá nuevamente.', 'danger');
            },
            complete: function() {
                // Restaurar botón
                submitBtn.prop('disabled', false);
                submitBtn.html(originalBtnHtml);
            }
        });
    });


    // ============================================
    // CONTACTO - Formulario de Contacto
    // ============================================
    $('#contactForm').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const submitBtn = form.find('button[type="submit"]');
        const originalBtnHtml = submitBtn.html();
        
        // Validación
        if (!validateContactForm(form)) {
            return;
        }
        
        // Deshabilitar botón
        submitBtn.prop('disabled', true);
        submitBtn.html('<span class="spinner-border spinner-border-sm me-2"></span>Enviando...');
        
        // Preparar datos
        const formData = {
            action: 'ht_contact_form',
            nonce: htAjax.contact_nonce,
            nombre: $('#nombre').val().trim(),
            apellido: $('#apellido').val().trim(),
            email: $('#email').val().trim(),
            telefono: $('#telefono').val().trim(),
            asunto: $('#asunto').val(),
            mensaje: $('#mensaje').val().trim()
        };
        
        $.ajax({
            url: htAjax.ajaxurl,
            type: 'POST',
            data: formData,
            success: function(response) {
                // Remover mensajes anteriores
                form.find('.alert').remove();
                
                const alertDiv = $('<div class="alert alert-dismissible fade show" role="alert"></div>');
                alertDiv.append('<button type="button" class="btn-close" data-bs-dismiss="alert"></button>');
                
                if (response.success) {
                    alertDiv.addClass('alert-success');
                    alertDiv.prepend('<i class="bi bi-check-circle-fill me-2"></i>' + response.data.message);
                    form[0].reset();
                    
                    // Scroll al mensaje
                    setTimeout(function() {
                        $('html, body').animate({
                            scrollTop: alertDiv.offset().top - 100
                        }, 500);
                    }, 100);
                    
                    // Auto-cerrar después de 8 segundos
                    setTimeout(function() {
                        alertDiv.fadeOut(function() {
                            $(this).remove();
                        });
                    }, 8000);
                } else {
                    alertDiv.addClass('alert-danger');
                    alertDiv.prepend('<i class="bi bi-exclamation-triangle-fill me-2"></i>' + response.data.message);
                }
                
                form.prepend(alertDiv);
            },
            error: function(xhr, status, error) {
                console.error('Contact Form Error:', error);
                
                const alertDiv = $('<div class="alert alert-danger alert-dismissible fade show" role="alert"></div>');
                alertDiv.append('<button type="button" class="btn-close" data-bs-dismiss="alert"></button>');
                alertDiv.prepend('<i class="bi bi-exclamation-triangle-fill me-2"></i>Error al enviar el mensaje. Intentá nuevamente.');
                form.prepend(alertDiv);
            },
            complete: function() {
                submitBtn.prop('disabled', false);
                submitBtn.html(originalBtnHtml);
            }
        });
    });


    // ============================================
    // MAYORISTA - Formulario de Registro
    // ============================================
    $('#formularioMayorista').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const submitBtn = form.find('button[type="submit"]');
        const originalBtnHtml = submitBtn.html();
        
        // Validación
        if (!validateMayoristaForm(form)) {
            return;
        }
        
        // Confirmar envío
        if (!confirm('¿Estás seguro de enviar la solicitud de registro mayorista?')) {
            return;
        }
        
        // Deshabilitar botón
        submitBtn.prop('disabled', true);
        submitBtn.html('<span class="spinner-border spinner-border-sm me-2"></span>Procesando...');
        
        // Preparar datos
        const formData = {
            action: 'registro_mayorista',
            nonce: htAjax.mayorista_nonce,
            razon_social: $('#razonSocial').val().trim(),
            cuit: $('#cuit').val().trim(),
            tipo_negocio: $('#tipoNegocio').val(),
            email: $('#email').val().trim(),
            telefono: $('#telefono').val().trim(),
            provincia: $('#provincia').val(),
            localidad: $('#localidad').val().trim(),
            comentarios: $('#comentarios').val().trim()
        };
        
        $.ajax({
            url: htAjax.ajaxurl,
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    // Mostrar modal de éxito
                    showSuccessModal(
                        'Solicitud Enviada',
                        response.data.message,
                        function() {
                            window.location.href = '/mi-cuenta';
                        }
                    );
                } else {
                    // Mostrar error
                    const alertDiv = $('<div class="alert alert-danger alert-dismissible fade show" role="alert"></div>');
                    alertDiv.append('<button type="button" class="btn-close" data-bs-dismiss="alert"></button>');
                    alertDiv.prepend('<i class="bi bi-exclamation-triangle-fill me-2"></i>' + response.data.message);
                    form.prepend(alertDiv);
                    
                    $('html, body').animate({
                        scrollTop: alertDiv.offset().top - 100
                    }, 500);
                }
            },
            error: function(xhr, status, error) {
                console.error('Mayorista Form Error:', error);
                
                const alertDiv = $('<div class="alert alert-danger alert-dismissible fade show" role="alert"></div>');
                alertDiv.append('<button type="button" class="btn-close" data-bs-dismiss="alert"></button>');
                alertDiv.prepend('<i class="bi bi-exclamation-triangle-fill me-2"></i>Error al procesar la solicitud. Intentá nuevamente.');
                form.prepend(alertDiv);
            },
            complete: function() {
                submitBtn.prop('disabled', false);
                submitBtn.html(originalBtnHtml);
            }
        });
    });


    // ============================================
    // FUNCIONES AUXILIARES
    // ============================================
    
    /**
     * Validar email
     */
    function isValidEmail(email) {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    }
    
    /**
     * Validar CUIT (formato argentino)
     */
    function isValidCUIT(cuit) {
        const regex = /^\d{2}-\d{8}-\d{1}$/;
        return regex.test(cuit);
    }
    
    /**
     * Mostrar mensaje en un div
     */
    function showMessage(container, message, type) {
        const iconClass = type === 'success' ? 'check-circle-fill' : 'exclamation-triangle-fill';
        const textClass = type === 'success' ? 'text-success' : 'text-danger';
        
        container
            .removeClass('text-success text-danger')
            .addClass(textClass)
            .html('<i class="bi bi-' + iconClass + ' me-1"></i>' + message)
            .fadeIn();
        
        // Auto-ocultar después de 8 segundos si es éxito
        if (type === 'success') {
            setTimeout(function() {
                container.fadeOut();
            }, 8000);
        }
    }
    
    /**
     * Validar formulario de contacto
     */
    function validateContactForm(form) {
        let isValid = true;
        
        // Limpiar errores previos
        form.find('.is-invalid').removeClass('is-invalid');
        form.find('.alert').remove();
        
        // Validar campos requeridos
        const requiredFields = [
            { id: '#nombre', name: 'Nombre' },
            { id: '#apellido', name: 'Apellido' },
            { id: '#email', name: 'Email' },
            { id: '#asunto', name: 'Asunto' },
            { id: '#mensaje', name: 'Mensaje' }
        ];
        
        requiredFields.forEach(function(field) {
            const input = $(field.id);
            if (!input.val().trim()) {
                input.addClass('is-invalid');
                isValid = false;
            }
        });
        
        // Validar email específicamente
        const email = $('#email').val();
        if (email && !isValidEmail(email)) {
            $('#email').addClass('is-invalid');
            isValid = false;
        }
        
        // Validar checkbox de privacidad
        if (!$('#privacidad').is(':checked')) {
            const alertDiv = $('<div class="alert alert-warning alert-dismissible fade show" role="alert"></div>');
            alertDiv.append('<button type="button" class="btn-close" data-bs-dismiss="alert"></button>');
            alertDiv.prepend('<i class="bi bi-exclamation-triangle-fill me-2"></i>Debés aceptar la política de privacidad para continuar.');
            form.prepend(alertDiv);
            isValid = false;
        }
        
        if (!isValid) {
            const alertDiv = $('<div class="alert alert-danger alert-dismissible fade show" role="alert"></div>');
            alertDiv.append('<button type="button" class="btn-close" data-bs-dismiss="alert"></button>');
            alertDiv.prepend('<i class="bi bi-exclamation-triangle-fill me-2"></i>Por favor completá todos los campos requeridos correctamente.');
            form.prepend(alertDiv);
            
            // Scroll al primer error
            $('html, body').animate({
                scrollTop: form.find('.is-invalid').first().offset().top - 150
            }, 500);
        }
        
        return isValid;
    }
    
    /**
     * Validar formulario mayorista
     */
    function validateMayoristaForm(form) {
        let isValid = true;
        
        // Limpiar errores previos
        form.find('.is-invalid').removeClass('is-invalid');
        form.find('.alert').remove();
        
        // Validar campos requeridos
        const requiredFields = [
            { id: '#razonSocial', name: 'Razón Social' },
            { id: '#cuit', name: 'CUIT' },
            { id: '#tipoNegocio', name: 'Tipo de Negocio' },
            { id: '#email', name: 'Email' },
            { id: '#telefono', name: 'Teléfono' },
            { id: '#provincia', name: 'Provincia' },
            { id: '#localidad', name: 'Localidad' }
        ];
        
        requiredFields.forEach(function(field) {
            const input = $(field.id);
            if (!input.val() || !input.val().trim()) {
                input.addClass('is-invalid');
                isValid = false;
            }
        });
        
        // Validar email específicamente
        const email = $('#email').val();
        if (email && !isValidEmail(email)) {
            $('#email').addClass('is-invalid');
            isValid = false;
        }
        
        // Validar CUIT específicamente
        const cuit = $('#cuit').val();
        if (cuit && !isValidCUIT(cuit)) {
            $('#cuit').addClass('is-invalid');
            
            const alertDiv = $('<div class="alert alert-warning alert-dismissible fade show" role="alert"></div>');
            alertDiv.append('<button type="button" class="btn-close" data-bs-dismiss="alert"></button>');
            alertDiv.prepend('<i class="bi bi-info-circle-fill me-2"></i>El CUIT debe tener el formato: 20-12345678-9');
            form.prepend(alertDiv);
            
            isValid = false;
        }
        
        if (!isValid) {
            const alertDiv = $('<div class="alert alert-danger alert-dismissible fade show" role="alert"></div>');
            alertDiv.append('<button type="button" class="btn-close" data-bs-dismiss="alert"></button>');
            alertDiv.prepend('<i class="bi bi-exclamation-triangle-fill me-2"></i>Por favor completá todos los campos requeridos correctamente.');
            form.prepend(alertDiv);
            
            // Scroll al primer error
            $('html, body').animate({
                scrollTop: form.find('.is-invalid').first().offset().top - 150
            }, 500);
        }
        
        return isValid;
    }
    
    /**
     * Mostrar modal de éxito con Bootstrap 5
     */
    function showSuccessModal(title, message, callback) {
        // Crear modal
        const modalHtml = `
            <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header" style="background: linear-gradient(135deg, #28a745, #20c997); color: white;">
                            <h5 class="modal-title" id="successModalLabel">
                                <i class="bi bi-check-circle-fill me-2"></i>${title}
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center py-4">
                            <div class="mb-3">
                                <i class="bi bi-check-circle" style="font-size: 4rem; color: #28a745;"></i>
                            </div>
                            <p class="lead mb-0">${message}</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success btn-lg" data-bs-dismiss="modal">
                                <i class="bi bi-check me-2"></i>Aceptar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Agregar al body
        $('body').append(modalHtml);
        
        // Inicializar y mostrar
        const modalEl = document.getElementById('successModal');
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
        
        // Callback al cerrar
        modalEl.addEventListener('hidden.bs.modal', function() {
            $(this).remove();
            if (callback && typeof callback === 'function') {
                callback();
            }
        });
    }
    
    /**
     * Limpiar errores al escribir
     */
    $(document).on('input change', '.is-invalid', function() {
        $(this).removeClass('is-invalid');
    });
    
    /**
     * Auto-formato CUIT mientras se escribe
     */
    $('#cuit').on('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        
        if (value.length <= 11) {
            // Formato: 20-12345678-9
            if (value.length > 2) {
                value = value.substring(0, 2) + '-' + value.substring(2);
            }
            if (value.length > 11) {
                value = value.substring(0, 11) + '-' + value.substring(11);
            }
            e.target.value = value;
        }
    });
    
    /**
     * Validación en tiempo real del email
     */
    $('#email, #newsletter_email').on('blur', function() {
        const email = $(this).val().trim();
        if (email && !isValidEmail(email)) {
            $(this).addClass('is-invalid');
        } else {
            $(this).removeClass('is-invalid');
        }
    });


    // ============================================
    // LOG DE INICIALIZACIÓN
    // ============================================
    console.log('✓ Formulario de Contacto:', $('#contactForm').length ? 'Encontrado' : 'No encontrado');
    console.log('✓ Formulario Newsletter:', $('#newsletter-form').length ? 'Encontrado' : 'No encontrado');
    console.log('✓ Formulario Mayorista:', $('#formularioMayorista').length ? 'Encontrado' : 'No encontrado');
    console.log('✓ htAjax.ajaxurl:', htAjax.ajaxurl);
    console.log('✓ Sistema de formularios AJAX iniciado correctamente');

})(jQuery);