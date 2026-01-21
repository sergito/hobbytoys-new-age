<?php
/**
 * Customer reset password email - Hobby Toys Theme
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
<div class="email-introduction" style="margin-bottom: 2rem; padding: 2rem; background: linear-gradient(135deg, #f8d7da 0%, rgba(220, 53, 69, 0.1) 100%); border-radius: 1rem; border-left: 4px solid #dc3545; text-align: center;">
    
    <!-- Icono de seguridad -->
    <div style="font-size: 3rem; margin-bottom: 1rem;">🔐🔑</div>
    
    <!-- Saludo personalizado -->
    <p style="margin: 0 0 1rem 0; font-size: 1.1rem; font-weight: 700;">
        <?php _e('¡Hola!', 'woocommerce'); ?>
    </p>
    
    <!-- Título principal -->
    <h2 style="color: #dc3545; font-size: 1.8rem; margin: 0 0 1rem 0; font-weight: 900;">
        🔄 <?php _e('Solicitud de Nueva Contraseña', 'woocommerce'); ?>
    </h2>
    
    <p style="margin: 0; font-weight: 600; line-height: 1.6; font-size: 1.1rem; color: #2C3E50;">
        <?php printf(__('Hemos recibido una solicitud para restablecer la contraseña de tu cuenta en %s.', 'woocommerce'), esc_html($site_name)); ?>
    </p>
</div>

<!-- Información de seguridad -->
<div class="info-box" style="background-color: #fff3cd; padding: 1.5rem; border-radius: 1rem; border-left: 4px solid #ffb900; margin: 2rem 0;">
    <h3 style="margin-top: 0; color: #e67e22; font-size: 1.2rem; font-weight: 900;">
        ⚠️ <?php _e('Importante: Seguridad de tu Cuenta', 'woocommerce'); ?>
    </h3>
    
    <ul style="margin: 1rem 0; padding-left: 1.5rem; line-height: 1.8;">
        <li style="margin-bottom: 0.5rem; font-weight: 600;">
            <strong style="color: #e67e22;">🔒</strong> <?php _e('Si NO solicitaste este cambio, ignora este email', 'woocommerce'); ?>
        </li>
        <li style="margin-bottom: 0.5rem; font-weight: 600;">
            <strong style="color: #e67e22;">⏰</strong> <?php _e('Este enlace expira en 24 horas por seguridad', 'woocommerce'); ?>
        </li>
        <li style="margin-bottom: 0.5rem; font-weight: 600;">
            <strong style="color: #e67e22;">🔐</strong> <?php _e('Solo funciona una vez y es único para tu cuenta', 'woocommerce'); ?>
        </li>
    </ul>
</div>

<!-- Instrucciones paso a paso -->
<div style="margin: 2rem 0;">
    <h3 style="color: #534fb5; font-weight: 900; text-align: center; margin-bottom: 1.5rem;">
        📋 <?php _e('Cómo Restablecer tu Contraseña', 'woocommerce'); ?>
    </h3>
    
    <div style="background-color: #f8f9fa; padding: 2rem; border-radius: 1rem;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 2rem;">
            
            <!-- Paso 1 -->
            <div style="text-align: center; padding: 1rem;">
                <div style="width: 60px; height: 60px; background-color: #EE285B; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem auto; font-weight: 900; font-size: 1.5rem;">1</div>
                <h4 style="margin: 0 0 0.5rem 0; color: #EE285B; font-weight: 900;">
                    <?php _e('Haz Clic', 'woocommerce'); ?>
                </h4>
                <p style="margin: 0; font-weight: 600; font-size: 0.9rem; color: #6c757d;">
                    <?php _e('Presiona el botón "Restablecer Contraseña"', 'woocommerce'); ?>
                </p>
            </div>
            
            <!-- Paso 2 -->
            <div style="text-align: center; padding: 1rem;">
                <div style="width: 60px; height: 60px; background-color: #534fb5; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem auto; font-weight: 900; font-size: 1.5rem;">2</div>
                <h4 style="margin: 0 0 0.5rem 0; color: #534fb5; font-weight: 900;">
                    <?php _e('Crea Nueva', 'woocommerce'); ?>
                </h4>
                <p style="margin: 0; font-weight: 600; font-size: 0.9rem; color: #6c757d;">
                    <?php _e('Introduce tu nueva contraseña segura', 'woocommerce'); ?>
                </p>
            </div>
            
            <!-- Paso 3 -->
            <div style="text-align: center; padding: 1rem;">
                <div style="width: 60px; height: 60px; background-color: #27ae60; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem auto; font-weight: 900; font-size: 1.5rem;">3</div>
                <h4 style="margin: 0 0 0.5rem 0; color: #27ae60; font-weight: 900;">
                    <?php _e('¡Listo!', 'woocommerce'); ?>
                </h4>
                <p style="margin: 0; font-weight: 600; font-size: 0.9rem; color: #6c757d;">
                    <?php _e('Inicia sesión con tu nueva contraseña', 'woocommerce'); ?>
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Botón principal de acción -->
<div style="text-align: center; margin: 3rem 0;">
    <div style="background-color: #f4efe8; padding: 2rem; border-radius: 1rem; margin-bottom: 2rem;">
        <h3 style="margin: 0 0 1.5rem 0; color: #EE285B; font-weight: 900;">
            🔑 <?php _e('Restablecer mi Contraseña', 'woocommerce'); ?>
        </h3>
        
        <?php
        echo hobbytoys_get_action_button(
            __('🔐 Crear Nueva Contraseña', 'woocommerce'),
            esc_url(add_query_arg(array('key' => $reset_key, 'id' => $user_id), wc_get_endpoint_url('lost-password', '', wc_get_page_permalink('myaccount')))),
            'primary'
        );
        ?>
        
        <p style="margin: 1rem 0 0 0; font-size: 0.9rem; color: #6c757d; font-weight: 600;">
            <?php _e('O copia y pega este enlace en tu navegador:', 'woocommerce'); ?>
        </p>
        
        <!-- Enlace copiable -->
        <div style="background-color: #f8f9fa; padding: 1rem; border-radius: 0.5rem; margin-top: 1rem; border: 2px dashed #534fb5; font-family: monospace; word-break: break-all; font-size: 0.9rem;">
            <?php echo esc_url(add_query_arg(array('key' => $reset_key, 'id' => $user_id), wc_get_endpoint_url('lost-password', '', wc_get_page_permalink('myaccount')))); ?>
        </div>
    </div>
</div>

<!-- Detalles de la solicitud -->
<div class="info-box" style="background-color: #d1ecf1; padding: 1.5rem; border-radius: 1rem; border-left: 4px solid #17a2b8; margin: 2rem 0;">
    <h3 style="margin-top: 0; color: #17a2b8; font-size: 1.1rem; font-weight: 900;">
        📊 <?php _e('Detalles de la Solicitud', 'woocommerce'); ?>
    </h3>
    
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-top: 1rem;">
        <div>
            <strong style="color: #534fb5;"><?php _e('👤 Usuario:', 'woocommerce'); ?></strong><br>
            <span style="font-weight: 600;"><?php echo esc_html($user_login); ?></span>
        </div>
        <div>
            <strong style="color: #534fb5;"><?php _e('📅 Fecha:', 'woocommerce'); ?></strong><br>
            <span style="font-weight: 600;"><?php echo hobbytoys_format_date(new DateTime(), 'd/m/Y H:i'); ?></span>
        </div>
        <div>
            <strong style="color: #534fb5;"><?php _e('🌐 IP:', 'woocommerce'); ?></strong><br>
            <span style="font-weight: 600; font-family: monospace; font-size: 0.9rem;">
                <?php echo esc_html($_SERVER['REMOTE_ADDR'] ?? __('No disponible', 'woocommerce')); ?>
            </span>
        </div>
        <div>
            <strong style="color: #534fb5;"><?php _e('⏰ Válido hasta:', 'woocommerce'); ?></strong><br>
            <span style="font-weight: 600;">
                <?php echo hobbytoys_format_date(new DateTime('+24 hours'), 'd/m/Y H:i'); ?>
            </span>
        </div>
    </div>
</div>

<!-- Consejos de seguridad -->
<div style="margin: 2rem 0;">
    <h3 style="color: #534fb5; font-weight: 900; text-align: center; margin-bottom: 1.5rem;">
        🛡️ <?php _e('Consejos para una Contraseña Segura', 'woocommerce'); ?>
    </h3>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
        
        <!-- Consejo 1 -->
        <div style="background-color: #e8f5e8; padding: 1rem; border-radius: 0.5rem; border-left: 3px solid #27ae60;">
            <h4 style="margin: 0 0 0.5rem 0; color: #27ae60; font-weight: 900; font-size: 0.9rem;">
                🔢 <?php _e('LONGITUD', 'woocommerce'); ?>
            </h4>
            <p style="margin: 0; font-size: 0.8rem; font-weight: 600; color: #6c757d;">
                <?php _e('Mínimo 8 caracteres, mejor si son 12 o más.', 'woocommerce'); ?>
            </p>
        </div>
        
        <!-- Consejo 2 -->
        <div style="background-color: #f4efe8; padding: 1rem; border-radius: 0.5rem; border-left: 3px solid #EE285B;">
            <h4 style="margin: 0 0 0.5rem 0; color: #EE285B; font-weight: 900; font-size: 0.9rem;">
                🔤 <?php _e('VARIEDAD', 'woocommerce'); ?>
            </h4>
            <p style="margin: 0; font-size: 0.8rem; font-weight: 600; color: #6c757d;">
                <?php _e('Combina mayúsculas, minúsculas, números y símbolos.', 'woocommerce'); ?>
            </p>
        </div>
        
        <!-- Consejo 3 -->
        <div style="background-color: #fff3cd; padding: 1rem; border-radius: 0.5rem; border-left: 3px solid #ffb900;">
            <h4 style="margin: 0 0 0.5rem 0; color: #e67e22; font-weight: 900; font-size: 0.9rem;">
                🚫 <?php _e('EVITA', 'woocommerce'); ?>
            </h4>
            <p style="margin: 0; font-size: 0.8rem; font-weight: 600; color: #6c757d;">
                <?php _e('Datos personales, palabras del diccionario o patrones simples.', 'woocommerce'); ?>
            </p>
        </div>
        
        <!-- Consejo 4 -->
        <div style="background-color: #d4edda; padding: 1rem; border-radius: 0.5rem; border-left: 3px solid #27ae60;">
            <h4 style="margin: 0 0 0.5rem 0; color: #27ae60; font-weight: 900; font-size: 0.9rem;">
                🔒 <?php _e('ÚNICA', 'woocommerce'); ?>
            </h4>
            <p style="margin: 0; font-size: 0.8rem; font-weight: 600; color: #6c757d;">
                <?php _e('No uses la misma contraseña en otros sitios web.', 'woocommerce'); ?>
            </p>
        </div>
    </div>
</div>

<!-- Qué hacer si no fuiste tú -->
<div class="info-box" style="background-color: #f8d7da; padding: 1.5rem; border-radius: 1rem; border-left: 4px solid #dc3545; margin: 2rem 0;">
    <h3 style="margin-top: 0; color: #dc3545; font-size: 1.1rem; font-weight: 900;">
        🚨 <?php _e('¿No solicitaste este cambio?', 'woocommerce'); ?>
    </h3>
    <p style="margin: 0 0 1rem 0; font-weight: 600;">
        <?php _e('Si no solicitaste restablecer tu contraseña, puedes:', 'woocommerce'); ?>
    </p>
    
    <ul style="margin: 1rem 0; padding-left: 1.5rem; line-height: 1.6;">
        <li style="margin-bottom: 0.5rem; font-weight: 600;">
            <strong style="color: #dc3545;">✉️</strong> <?php _e('Simplemente ignorar este email', 'woocommerce'); ?>
        </li>
        <li style="margin-bottom: 0.5rem; font-weight: 600;">
            <strong style="color: #dc3545;">🔒</strong> <?php _e('Tu contraseña actual seguirá siendo válida', 'woocommerce'); ?>
        </li>
        <li style="margin-bottom: 0.5rem; font-weight: 600;">
            <strong style="color: #dc3545;">📞</strong> <?php _e('Contactarnos si sospechas actividad sospechosa', 'woocommerce'); ?>
        </li>
    </ul>
    
    <div style="text-align: center; margin-top: 1rem;">
        <?php
        echo hobbytoys_get_action_button(
            __('🛡️ Reportar Actividad Sospechosa', 'woocommerce'),
            'mailto:' . get_option('admin_email') . '?subject=' . urlencode(__('Actividad Sospechosa en mi Cuenta', 'woocommerce')),
            'outline'
        );
        ?>
    </div>
</div>

<!-- Enlaces útiles adicionales -->
<div style="text-align: center; margin: 2rem 0;">
    <h3 style="color: #534fb5; font-weight: 900; margin-bottom: 1rem;">
        🔗 <?php _e('Enlaces Útiles', 'woocommerce'); ?>
    </h3>
    
    <div style="display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
        <?php
        echo hobbytoys_get_action_button(
            __('🏠 Ir al Inicio', 'woocommerce'),
            get_home_url(),
            'outline'
        );
        
        echo hobbytoys_get_action_button(
            __('🛍️ Ver Tienda', 'woocommerce'),
            wc_get_page_permalink('shop'),
            'outline'
        );
        
        echo hobbytoys_get_action_button(
            __('📞 Contacto', 'woocommerce'),
            get_home_url() . '/contacto',
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

<!-- Mensaje final de seguridad -->
<div style="text-align: center; padding: 2rem; background: linear-gradient(135deg, #f4efe8 0%, rgba(238, 40, 91, 0.05) 100%); border-radius: 1rem; margin: 2rem 0;">
    <h3 style="color: #EE285B; font-weight: 900; margin: 0 0 1rem 0;">
        🔐 <?php _e('Tu Seguridad es Nuestra Prioridad', 'woocommerce'); ?>
    </h3>
    <p style="margin: 0; font-weight: 600; color: #534fb5; line-height: 1.6;">
        <?php printf(__('En %s nos tomamos muy en serio la seguridad de tu cuenta. Si tienes alguna duda sobre este proceso, no dudes en contactarnos.', 'woocommerce'), esc_html($site_name)); ?>
    </p>
</div>

<?php
/*
 * @hooked hobbytoys_email_footer() - Salida del footer del email
 */
do_action('woocommerce_email_footer', $email);
?>