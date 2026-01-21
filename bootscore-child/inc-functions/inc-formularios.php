<?php
/**
 * Sistema de Formularios AJAX - Hobby Toys
 * Maneja Contacto, Newsletter y sus funcionalidades
 * 
 * @package HobbyToys
 * @version 2.0.0
 */

// Exit if accessed directly
defined('ABSPATH') || exit;


// ============================================
// 1. CREAR TABLA PARA CONTACTOS
// ============================================
function ht_create_contact_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'ht_contacts';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        nombre varchar(100) NOT NULL,
        apellido varchar(100) NOT NULL,
        email varchar(100) NOT NULL,
        telefono varchar(50),
        asunto varchar(200) NOT NULL,
        mensaje text NOT NULL,
        fecha datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        leido tinyint(1) DEFAULT 0,
        PRIMARY KEY (id),
        KEY email (email),
        KEY fecha (fecha),
        KEY leido (leido)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
add_action('after_setup_theme', 'ht_create_contact_table');


// ============================================
// 2. ENQUEUE SCRIPTS Y LOCALIZAR VARIABLES
// ============================================
function ht_enqueue_forms_scripts() {
    // Cargar script solo si no está ya enqueued
    if (!wp_script_is('ht-ajax-forms', 'enqueued')) {
        wp_enqueue_script(
            'ht-ajax-forms',
            get_stylesheet_directory_uri() . '/assets/js/ajax-forms.js',
            array('jquery'),
            filemtime(get_stylesheet_directory() . '/assets/js/ajax-forms.js'),
            true
        );

        // Localizar variables
        wp_localize_script('ht-ajax-forms', 'htAjax', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'contact_nonce' => wp_create_nonce('ht_contact_nonce'),
            'mayorista_nonce' => wp_create_nonce('mayorista_nonce'),
            'newsletter_nonce' => wp_create_nonce('newsletter_nonce')
        ));
    }
}
add_action('wp_enqueue_scripts', 'ht_enqueue_forms_scripts');


// ============================================
// 3. AJAX - FORMULARIO DE CONTACTO
// ============================================
function ht_process_contact_form() {
    // Verificar nonce
    check_ajax_referer('ht_contact_nonce', 'nonce');

    global $wpdb;
    $table_name = $wpdb->prefix . 'ht_contacts';

    // Sanitizar y validar datos
    $nombre = sanitize_text_field($_POST['nombre']);
    $apellido = sanitize_text_field($_POST['apellido']);
    $email = sanitize_email($_POST['email']);
    $telefono = sanitize_text_field($_POST['telefono']);
    $asunto = sanitize_text_field($_POST['asunto']);
    $mensaje = sanitize_textarea_field($_POST['mensaje']);

    // Validaciones
    if (empty($nombre) || empty($apellido) || empty($email) || empty($asunto) || empty($mensaje)) {
        wp_send_json_error(array(
            'message' => 'Por favor, completá todos los campos requeridos.'
        ));
        return;
    }

    if (!is_email($email)) {
        wp_send_json_error(array(
            'message' => 'El email ingresado no es válido.'
        ));
        return;
    }

    // Insertar en base de datos
    $inserted = $wpdb->insert(
        $table_name,
        array(
            'nombre' => $nombre,
            'apellido' => $apellido,
            'email' => $email,
            'telefono' => $telefono,
            'asunto' => $asunto,
            'mensaje' => $mensaje,
            'leido' => 0
        ),
        array('%s', '%s', '%s', '%s', '%s', '%s', '%d')
    );

    if ($inserted) {
        // Email al admin
        $admin_email = get_option('admin_email');
        $subject = 'Nuevo mensaje de contacto - Hobby Toys';
        
        $body = sprintf(
            "<div style='font-family: Arial, sans-serif; max-width: 600px;'>
                <h2 style='color: #EE285B;'>Nuevo mensaje de contacto</h2>
                <table style='width: 100%%; border-collapse: collapse;'>
                    <tr><td style='padding: 10px; border-bottom: 1px solid #eee;'><strong>Nombre:</strong></td><td style='padding: 10px; border-bottom: 1px solid #eee;'>%s %s</td></tr>
                    <tr><td style='padding: 10px; border-bottom: 1px solid #eee;'><strong>Email:</strong></td><td style='padding: 10px; border-bottom: 1px solid #eee;'>%s</td></tr>
                    <tr><td style='padding: 10px; border-bottom: 1px solid #eee;'><strong>Teléfono:</strong></td><td style='padding: 10px; border-bottom: 1px solid #eee;'>%s</td></tr>
                    <tr><td style='padding: 10px; border-bottom: 1px solid #eee;'><strong>Asunto:</strong></td><td style='padding: 10px; border-bottom: 1px solid #eee;'>%s</td></tr>
                </table>
                <h3 style='color: #534fb5; margin-top: 20px;'>Mensaje:</h3>
                <p style='padding: 15px; background: #f8f9fa; border-left: 4px solid #EE285B;'>%s</p>
                <p style='margin-top: 20px;'><a href='%s' style='background: #EE285B; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Ver en el Admin</a></p>
            </div>",
            esc_html($nombre),
            esc_html($apellido),
            esc_html($email),
            esc_html($telefono),
            esc_html($asunto),
            nl2br(esc_html($mensaje)),
            admin_url('admin.php?page=ht-contacts')
        );
        
        $headers = array('Content-Type: text/html; charset=UTF-8');
        wp_mail($admin_email, $subject, $body, $headers);

        // Email de confirmación al usuario
        $user_subject = '¡Recibimos tu mensaje! - Hobby Toys';
        $user_body = sprintf(
            "<div style='font-family: Arial, sans-serif; max-width: 600px;'>
                <div style='background: linear-gradient(135deg, #EE285B 0%%, #534fb5 100%%); padding: 30px; text-align: center;'>
                    <h1 style='color: white; margin: 0;'>¡Gracias por contactarnos!</h1>
                </div>
                <div style='padding: 30px; background: #f9f9fa;'>
                    <h2 style='color: #EE285B;'>Hola %s,</h2>
                    <p style='font-size: 16px; line-height: 1.6;'>
                        Recibimos tu mensaje correctamente y nuestro equipo lo revisará a la brevedad.
                    </p>
                    <p style='font-size: 16px; line-height: 1.6;'>
                        Te responderemos en menos de 24 horas hábiles al email: <strong>%s</strong>
                    </p>
                    <div style='background: white; padding: 20px; border-radius: 10px; margin: 20px 0;'>
                        <h3 style='color: #534fb5; margin-top: 0;'>Resumen de tu consulta:</h3>
                        <p><strong>Asunto:</strong> %s</p>
                        <p><strong>Mensaje:</strong> %s</p>
                    </div>
                    <p style='text-align: center; margin-top: 30px;'>
                        <a href='%s' style='background: #FFB900; color: #2C3E50; padding: 15px 30px; text-decoration: none; border-radius: 25px; font-weight: bold; display: inline-block;'>
                            Volver a la Tienda
                        </a>
                    </p>
                </div>
                <div style='background: #2C3E50; padding: 20px; text-align: center; color: white; font-size: 12px;'>
                    <p style='margin: 0;'>© %s Hobby Toys - Calle 39 Nro 1466, La Plata, Buenos Aires</p>
                </div>
            </div>",
            esc_html($nombre),
            esc_html($email),
            esc_html($asunto),
            nl2br(esc_html($mensaje)),
            home_url('/tienda'),
            date('Y')
        );
        
        wp_mail($email, $user_subject, $user_body, $headers);

        // Respuesta exitosa
        wp_send_json_success(array(
            'message' => '¡Mensaje enviado con éxito! Te responderemos pronto.'
        ));
    } else {
        wp_send_json_error(array(
            'message' => 'Error al enviar el mensaje. Por favor, intentá nuevamente.'
        ));
    }
}
add_action('wp_ajax_ht_contact_form', 'ht_process_contact_form');
add_action('wp_ajax_nopriv_ht_contact_form', 'ht_process_contact_form');


// ============================================
// 4. AJAX - NEWSLETTER
// ============================================
function ht_process_newsletter_subscription() {
    // Verificar nonce
    check_ajax_referer('newsletter_nonce', 'newsletter_nonce_field');

    global $wpdb;
    $table_name = $wpdb->prefix . 'ht_contacts';

    // Sanitizar email
    $email = sanitize_email($_POST['newsletter_email']);

    // Validar email
    if (!is_email($email)) {
        wp_send_json_error(array(
            'message' => 'Por favor ingresá un email válido.'
        ));
        return;
    }

    // Verificar si ya está suscrito
    $existing = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT * FROM $table_name WHERE email = %s AND asunto = 'Newsletter'",
            $email
        )
    );

    if ($existing) {
        wp_send_json_error(array(
            'message' => 'Este email ya está suscrito a nuestro newsletter.'
        ));
        return;
    }

    // Verificar si existe como usuario
    $user_exists = email_exists($email);

    if (!$user_exists) {
        // Crear usuario suscriptor
        $username = sanitize_user($email);
        $random_password = wp_generate_password(12, true);
        
        $user_id = wp_create_user($username, $random_password, $email);

        if (!is_wp_error($user_id)) {
            $user = new WP_User($user_id);
            $user->set_role('subscriber');
            
            update_user_meta($user_id, 'ht_newsletter_subscriber', true);
            update_user_meta($user_id, 'ht_newsletter_date', current_time('mysql'));
        }
    } else {
        $user_id = $user_exists;
        update_user_meta($user_id, 'ht_newsletter_subscriber', true);
        update_user_meta($user_id, 'ht_newsletter_date', current_time('mysql'));
    }

    // Guardar en tabla de contactos
    $inserted = $wpdb->insert(
        $table_name,
        array(
            'nombre' => 'Suscriptor',
            'apellido' => 'Newsletter',
            'email' => $email,
            'telefono' => '',
            'asunto' => 'Newsletter',
            'mensaje' => 'Suscripción al newsletter',
            'leido' => 0
        ),
        array('%s', '%s', '%s', '%s', '%s', '%s', '%d')
    );

    if ($inserted) {
        // Email de bienvenida
        $subject = '¡Bienvenido al Newsletter de Hobby Toys!';
        $body = sprintf(
            "<div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                <div style='background: linear-gradient(135deg, #EE285B 0%%, #534fb5 100%%); padding: 30px; text-align: center;'>
                    <h1 style='color: white; margin: 0;'>¡Gracias por suscribirte!</h1>
                </div>
                <div style='padding: 30px; background: #f9f9f9;'>
                    <h2 style='color: #EE285B;'>¡Bienvenido a la familia Hobby Toys!</h2>
                    <p style='font-size: 16px; line-height: 1.6;'>
                        A partir de ahora recibirás:
                    </p>
                    <ul style='font-size: 16px; line-height: 1.8;'>
                        <li>🎁 Ofertas exclusivas solo para suscriptores</li>
                        <li>🚀 Lanzamientos antes que nadie</li>
                        <li>💰 Descuentos especiales en fechas importantes</li>
                        <li>🎯 Recomendaciones personalizadas</li>
                    </ul>
                    <div style='text-align: center; margin: 30px 0;'>
                        <a href='%s' 
                           style='background: #FFB900; color: #2C3E50; padding: 15px 30px; text-decoration: none; border-radius: 25px; font-weight: bold; display: inline-block;'>
                            Explorar Tienda
                        </a>
                    </div>
                </div>
                <div style='background: #2C3E50; padding: 20px; text-align: center; color: white; font-size: 12px;'>
                    <p style='margin: 0;'>© %s Hobby Toys - Calle 39 Nro 1466, La Plata, Buenos Aires</p>
                </div>
            </div>",
            home_url('/tienda'),
            date('Y')
        );
        
        $headers = array('Content-Type: text/html; charset=UTF-8');
        wp_mail($email, $subject, $body, $headers);

        // Notificar al admin
        $admin_email = get_option('admin_email');
        wp_mail(
            $admin_email,
            'Nueva suscripción al newsletter',
            sprintf('Nueva suscripción: %s en %s', $email, current_time('d/m/Y H:i')),
            $headers
        );

        wp_send_json_success(array(
            'message' => '¡Suscripción exitosa! Revisá tu email para confirmar.'
        ));
    } else {
        wp_send_json_error(array(
            'message' => 'Error al procesar la suscripción. Intentá nuevamente.'
        ));
    }
}
add_action('wp_ajax_newsletter_subscribe', 'ht_process_newsletter_subscription');
add_action('wp_ajax_nopriv_newsletter_subscribe', 'ht_process_newsletter_subscription');


// ============================================
// 5. PÁGINA ADMIN - CONTACTOS
// ============================================
function ht_contacts_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'ht_contacts';

    // Acciones
    if (isset($_GET['action']) && isset($_GET['id'])) {
        $id = intval($_GET['id']);
        
        if ($_GET['action'] === 'mark_read') {
            $wpdb->update($table_name, array('leido' => 1), array('id' => $id), array('%d'), array('%d'));
            echo '<div class="notice notice-success"><p>Mensaje marcado como leído.</p></div>';
        }
        
        if ($_GET['action'] === 'delete') {
            $wpdb->delete($table_name, array('id' => $id), array('%d'));
            echo '<div class="notice notice-success"><p>Mensaje eliminado.</p></div>';
        }
    }

    $contacts = $wpdb->get_results("SELECT * FROM $table_name ORDER BY fecha DESC");
    ?>
    <div class="wrap">
        <h1 class="wp-heading-inline">Mensajes de Contacto</h1>
        
        <?php if (empty($contacts)): ?>
            <div class="notice notice-info">
                <p>No hay mensajes de contacto aún.</p>
            </div>
        <?php else: ?>
            <table class="wp-list-table widefat fixed striped" id="contacts-table">
                <thead>
                    <tr>
                        <th width="150">Fecha</th>
                        <th width="200">Nombre</th>
                        <th width="200">Email</th>
                        <th width="150">Asunto</th>
                        <th>Mensaje</th>
                        <th width="100">Estado</th>
                        <th width="150">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($contacts as $contact): ?>
                    <tr <?php echo !$contact->leido ? 'style="background: #fff3cd;"' : ''; ?>>
                        <td><?php echo date('d/m/Y H:i', strtotime($contact->fecha)); ?></td>
                        <td><strong><?php echo esc_html($contact->nombre . ' ' . $contact->apellido); ?></strong></td>
                        <td><a href="mailto:<?php echo esc_attr($contact->email); ?>"><?php echo esc_html($contact->email); ?></a></td>
                        <td><?php echo esc_html($contact->asunto); ?></td>
                        <td>
                            <?php 
                            $mensaje = esc_html($contact->mensaje);
                            echo strlen($mensaje) > 100 ? substr($mensaje, 0, 100) . '...' : $mensaje; 
                            ?>
                        </td>
                        <td>
                            <?php if ($contact->leido): ?>
                                <span class="dashicons dashicons-yes-alt" style="color: green;"></span> Leído
                            <?php else: ?>
                                <strong style="color: #d63638;">No leído</strong>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (!$contact->leido): ?>
                            <a href="?page=ht-contacts&action=mark_read&id=<?php echo $contact->id; ?>" 
                               class="button button-small">
                                Marcar leído
                            </a>
                            <?php endif; ?>
                            <a href="?page=ht-contacts&action=delete&id=<?php echo $contact->id; ?>" 
                               class="button button-small button-link-delete" 
                               onclick="return confirm('¿Eliminar este mensaje?')">
                                Eliminar
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <script>
    jQuery(document).ready(function($) {
        if ($('#contacts-table').length) {
            $('#contacts-table').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
                },
                order: [[0, 'desc']],
                pageLength: 25
            });
        }
    });
    </script>
    <?php
}


// ============================================
// 6. COLUMNA NEWSLETTER EN USUARIOS
// ============================================
function ht_add_newsletter_column($columns) {
    $columns['newsletter'] = 'Newsletter';
    return $columns;
}
add_filter('manage_users_columns', 'ht_add_newsletter_column');

function ht_show_newsletter_column_content($value, $column_name, $user_id) {
    if ($column_name === 'newsletter') {
        $is_subscriber = get_user_meta($user_id, 'ht_newsletter_subscriber', true);
        if ($is_subscriber) {
            $date = get_user_meta($user_id, 'ht_newsletter_date', true);
            $formatted_date = $date ? date('d/m/Y', strtotime($date)) : 'N/A';
            return '<span style="color: green;">✓ Suscrito</span><br><small>' . $formatted_date . '</small>';
        }
        return '<span style="color: #999;">No suscrito</span>';
    }
    return $value;
}
add_action('manage_users_custom_column', 'ht_show_newsletter_column_content', 10, 3);


// ============================================
// 7. EXPORTAR NEWSLETTER
// ============================================
function ht_export_newsletter_emails() {
    if (!current_user_can('manage_options')) {
        wp_die('No tienes permisos');
    }
    
    global $wpdb;
    
    $emails = $wpdb->get_col(
        "SELECT DISTINCT u.user_email 
         FROM {$wpdb->users} u
         INNER JOIN {$wpdb->usermeta} um ON u.ID = um.user_id
         WHERE um.meta_key = 'ht_newsletter_subscriber' 
         AND um.meta_value = '1'"
    );
    
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=newsletter-emails-' . date('Y-m-d') . '.csv');
    
    $output = fopen('php://output', 'w');
    fputcsv($output, array('Email', 'Fecha Suscripción'));
    
    foreach ($emails as $email) {
        $user = get_user_by('email', $email);
        if ($user) {
            $date = get_user_meta($user->ID, 'ht_newsletter_date', true);
            $formatted_date = $date ? date('d/m/Y', strtotime($date)) : 'N/A';
            fputcsv($output, array($email, $formatted_date));
        }
    }
    
    fclose($output);
    exit;
}
add_action('admin_post_export_newsletter', 'ht_export_newsletter_emails');


// ============================================
// 8. AJAX - REGISTRO MAYORISTA (desde inc-mayorista.php)
// ============================================
function ht_process_mayorista_registration() {
    check_ajax_referer('mayorista_nonce', 'nonce');

    global $wpdb;
    $table_name = $wpdb->prefix . 'ht_mayorista_requests';

    // Sanitizar datos
    $razon_social = sanitize_text_field($_POST['razon_social']);
    $cuit = sanitize_text_field($_POST['cuit']);
    $tipo_negocio = sanitize_text_field($_POST['tipo_negocio']);
    $email = sanitize_email($_POST['email']);
    $telefono = sanitize_text_field($_POST['telefono']);
    $provincia = sanitize_text_field($_POST['provincia']);
    $localidad = sanitize_text_field($_POST['localidad']);
    $comentarios = sanitize_textarea_field($_POST['comentarios']);

    // Validar email
    if (!is_email($email)) {
        wp_send_json_error(array('message' => 'Email inválido'));
        return;
    }

    // Verificar si el email ya existe
    if (email_exists($email)) {
        wp_send_json_error(array('message' => 'Este email ya está registrado'));
        return;
    }

    // Crear usuario con rol cliente
    $username = sanitize_user($email);
    $random_password = wp_generate_password(12, true);
    
    $user_id = wp_create_user($username, $random_password, $email);

    if (is_wp_error($user_id)) {
        wp_send_json_error(array('message' => 'Error al crear el usuario'));
        return;
    }

    // Establecer rol de cliente
    $user = new WP_User($user_id);
    $user->set_role('customer');

    // Guardar datos adicionales en user meta
    update_user_meta($user_id, 'first_name', $razon_social);
    update_user_meta($user_id, 'billing_company', $razon_social);
    update_user_meta($user_id, 'billing_phone', $telefono);
    update_user_meta($user_id, 'billing_state', $provincia);
    update_user_meta($user_id, 'billing_city', $localidad);
    update_user_meta($user_id, 'ht_cuit', $cuit);
    update_user_meta($user_id, 'ht_tipo_negocio', $tipo_negocio);
    update_user_meta($user_id, 'ht_mayorista_pending', true);

    // Insertar solicitud en tabla
    $inserted = $wpdb->insert(
        $table_name,
        array(
            'user_id' => $user_id,
            'razon_social' => $razon_social,
            'cuit' => $cuit,
            'tipo_negocio' => $tipo_negocio,
            'email' => $email,
            'telefono' => $telefono,
            'provincia' => $provincia,
            'localidad' => $localidad,
            'comentarios' => $comentarios,
            'estado' => 'pendiente'
        ),
        array('%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')
    );

    if ($inserted) {
        // Email al admin
        $admin_email = get_option('admin_email');
        $subject = 'Nueva solicitud de registro mayorista - Hobby Toys';
        $admin_url = admin_url('admin.php?page=ht-mayorista-requests');
        
        $body = sprintf(
            "<h2>Nueva solicitud de registro mayorista</h2>
            <p><strong>Razón Social:</strong> %s</p>
            <p><strong>CUIT:</strong> %s</p>
            <p><strong>Email:</strong> %s</p>
            <p><strong>Teléfono:</strong> %s</p>
            <p><strong>Tipo de Negocio:</strong> %s</p>
            <p><strong>Provincia:</strong> %s</p>
            <p><strong>Localidad:</strong> %s</p>
            <p><strong>Comentarios:</strong> %s</p>
            <br>
            <p><a href='%s'>Ver solicitud en el admin</a></p>",
            esc_html($razon_social),
            esc_html($cuit),
            esc_html($email),
            esc_html($telefono),
            esc_html($tipo_negocio),
            esc_html($provincia),
            esc_html($localidad),
            esc_html($comentarios),
            $admin_url
        );
        
        $headers = array('Content-Type: text/html; charset=UTF-8');
        wp_mail($admin_email, $subject, $body, $headers);

        // Email al solicitante
        $user_subject = 'Solicitud de registro mayorista recibida - Hobby Toys';
        $user_body = sprintf(
            "<h2>Hola %s,</h2>
            <p>Recibimos tu solicitud de registro como cliente mayorista.</p>
            <p>Tu cuenta ha sido creada y está pendiente de aprobación. Te notificaremos por email cuando sea aprobada.</p>
            <p><strong>Usuario:</strong> %s</p>
            <p><strong>Contraseña temporal:</strong> %s</p>
            <p>Por seguridad, te recomendamos cambiar tu contraseña al ingresar.</p>
            <br>
            <p>Saludos,<br>Equipo Hobby Toys</p>",
            esc_html($razon_social),
            esc_html($username),
            esc_html($random_password)
        );
        
        wp_mail($email, $user_subject, $user_body, $headers);

        wp_send_json_success(array(
            'message' => '¡Solicitud enviada con éxito! Recibirás un email con tus credenciales. Tu cuenta será revisada en 24-48 horas.'
        ));
    } else {
        // Si falla, eliminar el usuario creado
        wp_delete_user($user_id);
        wp_send_json_error(array('message' => 'Error al procesar la solicitud'));
    }
}
add_action('wp_ajax_registro_mayorista', 'ht_process_mayorista_registration');
add_action('wp_ajax_nopriv_registro_mayorista', 'ht_process_mayorista_registration');