<?php
/**
 * Customer new account email - Hobby Toys Theme
 * 
 * @package HobbyToys/Templates/Emails
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

// Incluir funciones de email
include_once get_stylesheet_directory() . '/woocommerce/emails/email-functions.php';

/*
 * @hooked hobbytoys_email_header() - Salida del header del email
 */
do_action('woocommerce_email_header', $email_heading, $email);

$site_name = get_bloginfo('name');
?>

<!-- Introducción del email -->
<div class="email-introduction" style="margin-bottom: 2rem; padding: 2rem; background: linear-gradient(135deg, #f4efe8 0%, rgba(238, 40, 91, 0.1) 100%); border-radius: 1rem; text-align: center;">
    
    <!-- Bienvenida con emojis -->
    <div style="font-size: 3rem; margin-bottom: 1rem;">🎉🎊✨</div>
    
    <!-- Saludo personalizado -->
    <h2 style="color: #EE285B; font-size: 2rem; margin: 0 0 1rem 0; font-weight: 900;">
        <?php printf(__('¡Bienvenido/a a %s!', 'woocommerce'), esc_html($site_name)); ?>
    </h2>
    
    <p style="margin: 0 0 1rem 0; font-size: 1.2rem; font-weight: 700; color: #534fb5;">
        <?php 
        if (isset($user_login)) {
            printf(__('¡Hola %s!', 'woocommerce'), esc_html($user_login));
        } else {
            echo __('¡Hola!', 'woocommerce');
        }
        ?>
    </p>
    
    <p style="margin: 0; font-weight: 600; line-height: 1.6; font-size: 1.1rem; color: #2C3E50;">
        <?php printf(__('Tu cuenta ha sido creada exitosamente en %s. ¡Estamos emocionados de tenerte con nosotros!', 'woocommerce'), esc_html($site_name)); ?>
    </p>
</div>

<!-- Información de la cuenta -->
<div class="info-box" style="background-color: #e8f5e8; padding: 2rem; border-radius: 1rem; border-left: 4px solid #27ae60; margin: 2rem 0;">
    <h3 style="margin-top: 0; color: #27ae60; font-size: 1.3rem; font-weight: 900; text-align: center;">
        🔐 <?php _e('Detalles de tu Cuenta', 'woocommerce'); ?>
    </h3>
    
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-top: 1.5rem;">
        <div style="text-align: center;">
            <strong style="display: block; color: #534fb5; margin-bottom: 0.5rem;">
                <?php _e('👤 Usuario:', 'woocommerce'); ?>
            </strong>
            <span style="font-weight: 700; font-size: 1.1rem; color: #2C3E50;">
                <?php echo esc_html($user_login); ?>
            </span>
        </div>
        
        <div style="text-align: center;">
            <strong style="display: block; color: #534fb5; margin-bottom: 0.5rem;">
                <?php _e('📧 Email:', 'woocommerce'); ?>
            </strong>
            <span style="font-weight: 700; font-size: 1.1rem; color: #2C3E50;">
                <?php echo esc_html($user_email); ?>
            </span>
        </div>
    </div>
    
    <?php if ($password_generated) : ?>
        <div style="margin-top: 2rem; padding: 1.5rem; background-color: #fff3cd; border-radius: 0.5rem; border: 2px dashed #ffb900;">
            <h4 style="margin-top: 0; color: #e67e22; font-weight: 900; text-align: center;">
                🔑 <?php _e('Tu Contraseña Temporal', 'woocommerce'); ?>
            </h4>
            <p style="text-align: center; margin: 1rem 0; font-weight: 700; font-size: 1.2rem; font-family: monospace; background-color: white; padding: 1rem; border-radius: 0.5rem; border: 1px solid #e67e22;">
                <?php echo esc_html($user_pass); ?>
            </p>
            <p style="margin: 0; text-align: center; font-weight: 600; color: #e67e22; font-size: 0.9rem;">
                ⚠️ <?php _e('¡Importante! Te recomendamos cambiar esta contraseña en tu primer inicio de sesión.', 'woocommerce'); ?>
            </p>
        </div>
    <?php endif; ?>
</div>

<!-- Botones de acción -->
<div style="text-align: center; margin: 2rem 0;">
    <?php
    echo hobbytoys_get_action_button(
        __('🏠 Acceder a Mi Cuenta', 'woocommerce'),
        wc_get_page_permalink('myaccount'),
        'primary'
    );
    
    echo hobbytoys_get_action_button(
        __('🛍️ Empezar a Comprar', 'woocommerce'),
        wc_get_page_permalink('shop'),
        'secondary'
    );
    ?>
</div>

<!-- Beneficios de tener cuenta -->
<div style="margin: 2rem 0;">
    <h3 style="color: #534fb5; font-weight: 900; text-align: center; margin-bottom: 2rem; font-size: 1.4rem;">
        🌟 <?php _e('Beneficios de tu Cuenta', 'woocommerce'); ?>
    </h3>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
        
        <!-- Beneficio 1 -->
        <div style="background-color: #f8f9fa; padding: 1.5rem; border-radius: 1rem; text-align: center; border-left: 4px solid #EE285B;">
            <div style="font-size: 2rem; margin-bottom: 1rem;">🚀</div>
            <h4 style="margin: 0 0 0.5rem 0; color: #EE285B; font-weight: 900;">
                <?php _e('Compras Rápidas', 'woocommerce'); ?>
            </h4>
            <p style="margin: 0; font-weight: 600; font-size: 0.9rem; color: #6c757d;">
                <?php _e('Guarda tus direcciones y métodos de pago para comprar más rápido.', 'woocommerce'); ?>
            </p>
        </div>
        
        <!-- Beneficio 2 -->
        <div style="background-color: #f8f9fa; padding: 1.5rem; border-radius: 1rem; text-align: center; border-left: 4px solid #27ae60;">
            <div style="font-size: 2rem; margin-bottom: 1rem;">📦</div>
            <h4 style="margin: 0 0 0.5rem 0; color: #27ae60; font-weight: 900;">
                <?php _e('Historial de Pedidos', 'woocommerce'); ?>
            </h4>
            <p style="margin: 0; font-weight: 600; font-size: 0.9rem; color: #6c757d;">
                <?php _e('Revisa todos tus pedidos y descarga facturas cuando quieras.', 'woocommerce'); ?>
            </p>
        </div>
        
        <!-- Beneficio 3 -->
        <div style="background-color: #f8f9fa; padding: 1.5rem; border-radius: 1rem; text-align: center; border-left: 4px solid #ffb900;">
            <div style="font-size: 2rem; margin-bottom: 1rem;">⭐</div>
            <h4 style="margin: 0 0 0.5rem 0; color: #e67e22; font-weight: 900;">
                <?php _e('Puntos y Descuentos', 'woocommerce'); ?>
            </h4>
            <p style="margin: 0; font-weight: 600; font-size: 0.9rem; color: #6c757d;">
                <?php _e('Acumula puntos con cada compra y accede a ofertas exclusivas.', 'woocommerce'); ?>
            </p>
        </div>
        
        <!-- Beneficio 4 -->
        <div style="background-color: #f8f9fa; padding: 1.5rem; border-radius: 1rem; text-align: center; border-left: 4px solid #534fb5;">
            <div style="font-size: 2rem; margin-bottom: 1rem;">💝</div>
            <h4 style="margin: 0 0 0.5rem 0; color: #534fb5; font-weight: 900;">
                <?php _e('Lista de Deseos', 'woocommerce'); ?>
            </h4>
            <p style="margin: 0; font-weight: 600; font-size: 0.9rem; color: #6c757d;">
                <?php _e('Guarda tus productos favoritos para comprarlos después.', 'woocommerce'); ?>
            </p>
        </div>
    </div>
</div>

<!-- Ofertas de bienvenida -->
<div style="text-align: center; padding: 2rem; background: linear-gradient(135deg, #EE285B 0%, #534fb5 100%); border-radius: 1rem; margin: 2rem 0; color: white;">
    <h3 style="color: white; font-weight: 900; margin: 0 0 1rem 0; font-size: 1.5rem;">
        🎁 <?php _e('¡Regalo de Bienvenida!', 'woocommerce'); ?>
    </h3>
    <p style="margin: 0 0 1.5rem 0; font-weight: 600; color: rgba(255,255,255,0.9); font-size: 1.1rem;">
        <?php _e('Como nuevo miembro, tienes un 10% de descuento en tu primera compra.', 'woocommerce'); ?>
    </p>
    
    <!-- Código de descuento -->
    <div style="background-color: rgba(255,255,255,0.2); padding: 1rem; border-radius: 0.5rem; margin: 1rem 0; border: 2px dashed rgba(255,255,255,0.5);">
        <p style="margin: 0 0 0.5rem 0; color: rgba(255,255,255,0.8); font-weight: 600;">
            <?php _e('Usa el código:', 'woocommerce'); ?>
        </p>
        <span style="font-family: monospace; font-size: 1.5rem; font-weight: 900; background-color: white; color: #EE285B; padding: 0.5rem 1rem; border-radius: 0.5rem;">
            BIENVENIDO10
        </span>
    </div>
    
    <div style="margin-top: 1.5rem;">
        <?php
        echo hobbytoys_get_action_button(
            __('🛒 Usar mi Descuento', 'woocommerce'),
            wc_get_page_permalink('shop'),
            'secondary'
        );
        ?>
    </div>
</div>

<!-- Guía rápida -->
<div style="margin: 2rem 0;">
    <h3 style="color: #534fb5; font-weight: 900; text-align: center; margin-bottom: 1.5rem;">
        📋 <?php _e('Primeros Pasos', 'woocommerce'); ?>
    </h3>
    
    <div style="background-color: #f8f9fa; padding: 1.5rem; border-radius: 1rem;">
        <ol style="margin: 0; padding-left: 1.5rem; line-height: 2;">
            <li style="margin-bottom: 0.5rem; font-weight: 600;">
                <strong style="color: #EE285B;">🔐</strong> <?php _e('Inicia sesión en tu cuenta', 'woocommerce'); ?>
            </li>
            <li style="margin-bottom: 0.5rem; font-weight: 600;">
                <strong style="color: #534fb5;">👤</strong> <?php _e('Completa tu perfil con tus datos', 'woocommerce'); ?>
            </li>
            <li style="margin-bottom: 0.5rem; font-weight: 600;">
                <strong style="color: #27ae60;">🏠</strong> <?php _e('Agrega tu dirección de envío', 'woocommerce'); ?>
            </li>
            <li style="margin-bottom: 0.5rem; font-weight: 600;">
                <strong style="color: #ffb900;">🛍️</strong> <?php _e('¡Disfruta comprando!', 'woocommerce'); ?>
            </li>
        </ol>
    </div>
</div>

<!-- Contacto y soporte -->
<div class="info-box" style="background-color: #f4efe8; padding: 1.5rem; border-radius: 1rem; border-left: 4px solid #EE285B; margin: 2rem 0; text-align: center;">
    <h3 style="margin-top: 0; color: #EE285B; font-weight: 900;">
        🤝 <?php _e('¿Necesitas Ayuda?', 'woocommerce'); ?>
    </h3>
    <p style="margin: 0 0 1rem 0; font-weight: 600;">
        <?php _e('Nuestro equipo está aquí para ayudarte. No dudes en contactarnos si tienes alguna pregunta.', 'woocommerce'); ?>
    </p>
    
    <div style="display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap; margin-top: 1rem;">
        <?php
        echo hobbytoys_get_action_button(
            __('💬 Chat en Vivo', 'woocommerce'),
            get_home_url() . '/contacto',
            'outline'
        );
        
        echo hobbytoys_get_action_button(
            __('📧 Email', 'woocommerce'),
            'mailto:' . get_option('admin_email'),
            'outline'
        );
        ?>
    </div>
</div>

<!-- Contenido adicional personalizado -->
<?php if ($additional_content) : ?>
<div class="email-additional-content" style="background-color: #f4efe8; padding: 1.5rem; border-radius: 1rem; margin: 2rem 0; border: 1px dashed #EE285B;">
    <h3 style="margin-top: 0; color: #EE285B; font-weight: 900;">
        💬 <?php _e('Información Adicional', 'woocommerce'); ?>
    </h3>
    <?php echo wp_kses_post(wpautop(wptexturize($additional_content))); ?>
</div>
<?php endif; ?>

<!-- Mensaje final -->
<div style="text-align: center; padding: 2rem; background: linear-gradient(135deg, #f4efe8 0%, rgba(238, 40, 91, 0.05) 100%); border-radius: 1rem; margin: 2rem 0;">
    <h3 style="color: #EE285B; font-weight: 900; margin: 0 0 1rem 0;">
        🚀 <?php _e('¡Comienza tu Aventura!', 'woocommerce'); ?>
    </h3>
    <p style="margin: 0; font-weight: 600; color: #534fb5;">
        <?php printf(__('¡Bienvenido/a a la familia %s! Estamos aquí para hacer que cada compra sea especial.', 'woocommerce'), esc_html($site_name)); ?>
    </p>
</div>

<?php
/*
 * @hooked hobbytoys_email_footer() - Salida del footer del email
 */
do_action('woocommerce_email_footer', $email);
?>