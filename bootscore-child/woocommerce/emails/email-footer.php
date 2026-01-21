<?php
/**
 * Email Footer Template - Hobby Toys Theme
 * 
 * @package HobbyToys/Templates/Emails
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Obtener configuraciones del tema
$site_name = get_bloginfo('name');
$site_url = home_url();
$admin_email = get_option('admin_email');
$phone = get_theme_mod('contact_phone', '');
$address = get_theme_mod('contact_address', '');
$facebook = get_theme_mod('social_facebook', '');
$instagram = get_theme_mod('social_instagram', '');
$twitter = get_theme_mod('social_twitter', '');

?>
        </div> <!-- Fin del contenido -->
        
        <!-- Footer -->
        <div class="email-footer" style="background-color: #2C3E50; color: #ffffff; padding: 2rem; text-align: center;">
            
            <!-- Redes sociales -->
            <?php if ($facebook || $instagram || $twitter) : ?>
                <div class="social-links" style="margin: 1rem 0;">
                    <?php if ($facebook) : ?>
                        <a href="<?php echo esc_url($facebook); ?>" style="display: inline-block; margin: 0 0.5rem; padding: 0.5rem; background-color: #EE285B; color: #ffffff; border-radius: 50%; text-decoration: none; width: 40px; height: 40px; line-height: 30px;">
                            <span style="font-size: 16px;">📘</span>
                        </a>
                    <?php endif; ?>
                    
                    <?php if ($instagram) : ?>
                        <a href="<?php echo esc_url($instagram); ?>" style="display: inline-block; margin: 0 0.5rem; padding: 0.5rem; background-color: #EE285B; color: #ffffff; border-radius: 50%; text-decoration: none; width: 40px; height: 40px; line-height: 30px;">
                            <span style="font-size: 16px;">📷</span>
                        </a>
                    <?php endif; ?>
                    
                    <?php if ($twitter) : ?>
                        <a href="<?php echo esc_url($twitter); ?>" style="display: inline-block; margin: 0 0.5rem; padding: 0.5rem; background-color: #EE285B; color: #ffffff; border-radius: 50%; text-decoration: none; width: 40px; height: 40px; line-height: 30px;">
                            <span style="font-size: 16px;">🐦</span>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <!-- Información de contacto -->
            <div style="margin: 1.5rem 0;">
                <p style="margin: 0.5rem 0; font-weight: 400; font-size: 0.9rem;">
                    <strong><?php echo esc_html($site_name); ?></strong>
                </p>
                
                <?php if ($address) : ?>
                    <p style="margin: 0.5rem 0; font-weight: 400; font-size: 0.9rem;">
                        📍 <?php echo esc_html($address); ?>
                    </p>
                <?php endif; ?>
                
                <?php if ($phone) : ?>
                    <p style="margin: 0.5rem 0; font-weight: 400; font-size: 0.9rem;">
                        📞 <?php echo esc_html($phone); ?>
                    </p>
                <?php endif; ?>
                
                <p style="margin: 0.5rem 0; font-weight: 400; font-size: 0.9rem;">
                    ✉️ <a href="mailto:<?php echo esc_attr($admin_email); ?>" style="color: #ffb900; text-decoration: none;">
                        <?php echo esc_html($admin_email); ?>
                    </a>
                </p>
                
                <p style="margin: 0.5rem 0; font-weight: 400; font-size: 0.9rem;">
                    🌐 <a href="<?php echo esc_url($site_url); ?>" style="color: #ffb900; text-decoration: none;">
                        <?php echo esc_html(str_replace(['http://', 'https://'], '', $site_url)); ?>
                    </a>
                </p>
            </div>
            
            <!-- Enlaces útiles -->
            <div style="margin: 1.5rem 0; padding: 1rem 0; border-top: 1px solid rgba(255,255,255,0.2);">
                <p style="margin: 0.5rem 0; font-size: 0.9rem;">
                    <a href="<?php echo esc_url($site_url); ?>/mi-cuenta" style="color: #ffb900; text-decoration: none; margin: 0 1rem;">
                        Mi Cuenta
                    </a>
                    <a href="<?php echo esc_url($site_url); ?>/contacto" style="color: #ffb900; text-decoration: none; margin: 0 1rem;">
                        Contacto
                    </a>
                    <a href="<?php echo esc_url($site_url); ?>/politica-privacidad" style="color: #ffb900; text-decoration: none; margin: 0 1rem;">
                        Privacidad
                    </a>
                </p>
            </div>
            
            <!-- Copyright y mensaje final -->
            <div style="margin-top: 2rem; padding-top: 1rem; border-top: 1px solid rgba(255,255,255,0.2); font-size: 0.8rem; color: rgba(255,255,255,0.7);">
                <p style="margin: 0.5rem 0;">
                    © <?php echo date('Y'); ?> <?php echo esc_html($site_name); ?>. <?php _e('Todos los derechos reservados.', 'woocommerce'); ?>
                </p>
                <p style="margin: 0.5rem 0;">
                    <?php _e('¡Gracias por confiar en nosotros! 🎉', 'woocommerce'); ?>
                </p>
                
                <!-- Mensaje anti-spam -->
                <p style="margin: 1rem 0 0 0; font-size: 0.7rem;">
                    <?php _e('Has recibido este email porque realizaste una compra o te registraste en nuestro sitio.', 'woocommerce'); ?>
                </p>
            </div>
        </div>
        
    </div> <!-- Fin del contenedor del email -->
</body>
</html><?php