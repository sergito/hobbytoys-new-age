jQuery(document).ready(function($) {
    
    // Animación de pasos al completar secciones
    function updateCheckoutSteps() {
        let billing_complete = $('#billing_first_name').val() && 
                             $('#billing_email').val() && 
                             $('#billing_phone').val();
        
        if (billing_complete) {
            $('.step-item').eq(0).addClass('active');
            $('.step-item').eq(1).addClass('active');
        }
    }

    // Actualizar pasos cuando cambian los campos
    $('input, select').on('change blur', function() {
        updateCheckoutSteps();
    });

    // Smooth scroll al primer error
    $(document.body).on('checkout_error', function() {
        $('html, body').animate({
            scrollTop: $('.woocommerce-error').offset().top - 100
        }, 500);
    });

    // Mejorar UX del botón de pago
    $('form.checkout').on('checkout_place_order', function() {
        $('#place_order').html('<span class="spinner-border spinner-border-sm me-2"></span>Procesando pago...');
    });

    // Validación visual de campos requeridos
    $('input[required], select[required]').on('blur', function() {
        if ($(this).val() === '') {
            $(this).addClass('is-invalid');
        } else {
            $(this).removeClass('is-invalid').addClass('is-valid');
        }
    });

    // Tooltip para campos con ayuda
    $('[data-bs-toggle="tooltip"]').tooltip();

    // Confirmación antes de salir si hay datos ingresados
    let formChanged = false;
    $('form.checkout input, form.checkout select').on('change', function() {
        formChanged = true;
    });

    $(window).on('beforeunload', function() {
        if (formChanged && !$('form.checkout').hasClass('processing')) {
            return '¿Estás seguro de que querés salir? Los datos ingresados se perderán.';
        }
    });

    // Remover warning después de enviar
    $('form.checkout').on('submit', function() {
        formChanged = false;
    });
});