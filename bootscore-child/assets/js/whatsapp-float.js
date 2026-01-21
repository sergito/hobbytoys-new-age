/**
 * WhatsApp Flotante Animado
 * Botón fixed con animación y chat popup
 */

(function($) {
    'use strict';

    // Configuración
    const config = {
        phoneNumber: '5492215608027', // Número de WhatsApp de Hobby Toys (con código de país)
        defaultMessage: '¡Hola Hobby Toys! Me gustaría consultar sobre sus juguetes.',
        agentName: 'Hobby Toys',
        agentImage: 'https://ui-avatars.com/api/?name=Hobby+Toys&background=EE285B&color=fff&size=80',
        position: 'bottom-right', // bottom-right, bottom-left
        showDelay: 0, // Sin delay - mostrar inmediatamente
        pulseInterval: 8000, // Intervalo de pulso (ms)
        chatBox: true, // Mostrar chatbox antes de abrir WhatsApp
        workingHours: {
            enabled: true, // Activar horario de atención
            timezone: 'America/Argentina/Buenos_Aires',
            schedule: {
                monday: { open: '09:00', close: '20:00' },
                tuesday: { open: '09:00', close: '20:00' },
                wednesday: { open: '09:00', close: '20:00' },
                thursday: { open: '09:00', close: '20:00' },
                friday: { open: '09:00', close: '20:00' },
                saturday: { open: '09:00', close: '20:00' },
                sunday: null // Cerrado
            }
        }
    };

    let pulseIntervalId;
    let isOpen = false;

    // Inicializar WhatsApp flotante
    function initWhatsAppFloat() {
        // Crear elementos HTML
        createWhatsAppButton();

        // Mostrar después del delay
        setTimeout(() => {
            $('#whatsappFloat').addClass('show').css({
                'opacity': '1',
                'transform': 'scale(1)'
            });
            startPulseAnimation();
        }, config.showDelay);

        // Event listeners
        $(document).on('click', '#whatsappFloat', toggleChatBox);
        $(document).on('click', '.wa-close-btn', closeChatBox);
        $(document).on('click', '.wa-send-btn', openWhatsApp);
        $(document).on('click', '.wa-quick-message', handleQuickMessage);
        $(document).on('click', function(e) {
            if (isOpen && !$(e.target).closest('#whatsappFloat, #whatsappChatBox').length) {
                closeChatBox();
            }
        });
    }

    // Crear botón flotante de WhatsApp
    function createWhatsAppButton() {
        const positionClass = config.position === 'bottom-left' ? 'left: 20px;' : 'right: 20px;';

        const buttonHTML = `
        <!-- Botón Flotante de WhatsApp -->
        <div id="whatsappFloat"
             style="position: fixed; bottom: 20px; ${positionClass} z-index: 999;
                    width: 65px; height: 65px; border-radius: 50%;
                    background: linear-gradient(135deg, #25D366, #128C7E);
                    box-shadow: 0 4px 20px rgba(37, 211, 102, 0.4);
                    display: flex; align-items: center; justify-content: center;
                    cursor: pointer; transition: all 0.3s ease;
                    opacity: 0; transform: scale(0);">

            <!-- Icono de WhatsApp -->
            <i class="bi bi-whatsapp" style="font-size: 2rem; color: white;"></i>

            <!-- Badge de notificación -->
            <span class="wa-notification-badge position-absolute"
                  style="top: 0; right: 0; width: 20px; height: 20px;
                         background: #EE285B; border-radius: 50%;
                         border: 2px solid white; animation: pulse 2s infinite;">
            </span>

            <!-- Tooltip -->
            <div class="wa-tooltip position-absolute"
                 style="${config.position === 'bottom-left' ? 'left: 75px;' : 'right: 75px;'}
                        bottom: 10px; background: white; color: #2C3E50;
                        padding: 10px 15px; border-radius: 10px;
                        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                        white-space: nowrap; opacity: 0; pointer-events: none;
                        transition: all 0.3s ease; font-size: 0.9rem;
                        font-weight: 600;">
                ¿Necesitas ayuda? Chatea con nosotros
                <div style="position: absolute; ${config.position === 'bottom-left' ? 'left: -8px;' : 'right: -8px;'}
                            top: 50%; transform: translateY(-50%);
                            width: 0; height: 0;
                            border-top: 8px solid transparent;
                            border-bottom: 8px solid transparent;
                            ${config.position === 'bottom-left' ? 'border-right: 8px solid white;' : 'border-left: 8px solid white;'}">
                </div>
            </div>
        </div>
        `;

        $('body').append(buttonHTML);

        // Si chatBox está habilitado, crear el chatbox
        if (config.chatBox) {
            createChatBox();
        }

        // Hover effects
        $('#whatsappFloat').hover(
            function() {
                $(this).css({
                    'transform': 'scale(1.1)',
                    'box-shadow': '0 6px 25px rgba(37, 211, 102, 0.5)'
                });
                $('.wa-tooltip').css('opacity', '1');
            },
            function() {
                if (!isOpen) {
                    $(this).css({
                        'transform': 'scale(1)',
                        'box-shadow': '0 4px 20px rgba(37, 211, 102, 0.4)'
                    });
                }
                $('.wa-tooltip').css('opacity', '0');
            }
        );
    }

    // Crear chatbox
    function createChatBox() {
        const positionClass = config.position === 'bottom-left' ? 'left: 20px;' : 'right: 20px;';
        const workingStatus = isWorkingHours();

        const chatBoxHTML = `
        <div id="whatsappChatBox"
             style="position: fixed; bottom: 100px; ${positionClass} z-index: 998;
                    width: 350px; background: white; border-radius: 20px;
                    box-shadow: 0 10px 40px rgba(0,0,0,0.2);
                    display: none; overflow: hidden;
                    animation: slideUp 0.3s ease;">

            <!-- Header -->
            <div class="wa-chat-header"
                 style="background: linear-gradient(135deg, #25D366, #128C7E);
                        color: white; padding: 20px; position: relative;">
                <button class="wa-close-btn position-absolute"
                        style="top: 15px; right: 15px; background: rgba(255,255,255,0.2);
                               border: none; color: white; border-radius: 50%;
                               width: 35px; height: 35px; cursor: pointer;
                               transition: all 0.3s ease;">
                    <i class="bi bi-x" style="font-size: 1.5rem;"></i>
                </button>

                <div class="d-flex align-items-center">
                    <img src="${config.agentImage}" alt="Agent"
                         style="width: 50px; height: 50px; border-radius: 50%;
                                border: 3px solid rgba(255,255,255,0.3); margin-right: 12px;">
                    <div>
                        <h6 class="mb-0 fw-bold">${config.agentName}</h6>
                        <small class="wa-status" style="opacity: 0.9;">
                            <i class="bi bi-circle-fill me-1" style="font-size: 0.5rem;"></i>
                            ${workingStatus ? 'En línea' : 'Fuera de horario'}
                        </small>
                    </div>
                </div>
            </div>

            <!-- Body -->
            <div class="wa-chat-body" style="padding: 20px; background: #f4efe8;">
                <!-- Mensaje de bienvenida -->
                <div class="wa-message-bubble animate__animated animate__fadeInUp"
                     style="background: white; padding: 15px; border-radius: 15px;
                            box-shadow: 0 2px 8px rgba(0,0,0,0.08); margin-bottom: 15px;
                            position: relative;">
                    <div class="mb-2">
                        <strong style="color: #25D366; font-size: 0.9rem;">
                            ${config.agentName}
                        </strong>
                        <span class="text-muted small ms-2">Ahora</span>
                    </div>
                    <p class="mb-0" style="color: #2C3E50; line-height: 1.5;">
                        ¡Hola! 👋 Bienvenido a Hobby Toys. ¿En qué podemos ayudarte hoy?
                    </p>
                </div>

                <!-- Mensajes rápidos -->
                <div class="wa-quick-messages">
                    <p class="small text-muted mb-2 fw-semibold">
                        <i class="bi bi-lightning-fill me-1"></i>Mensajes rápidos:
                    </p>
                    <button class="wa-quick-message btn btn-sm btn-outline-secondary d-block w-100 mb-2"
                            data-message="Quiero consultar sobre un producto"
                            style="border-radius: 20px; text-align: left; padding: 10px 15px;
                                   transition: all 0.3s ease; border-color: #e0e0e0;">
                        <i class="bi bi-box me-2"></i>Consultar producto
                    </button>
                    <button class="wa-quick-message btn btn-sm btn-outline-secondary d-block w-100 mb-2"
                            data-message="¿Cuál es el costo de envío?"
                            style="border-radius: 20px; text-align: left; padding: 10px 15px;
                                   transition: all 0.3s ease; border-color: #e0e0e0;">
                        <i class="bi bi-truck me-2"></i>Costo de envío
                    </button>
                    <button class="wa-quick-message btn btn-sm btn-outline-secondary d-block w-100 mb-2"
                            data-message="Quiero hacer un pedido"
                            style="border-radius: 20px; text-align: left; padding: 10px 15px;
                                   transition: all 0.3s ease; border-color: #e0e0e0;">
                        <i class="bi bi-cart me-2"></i>Hacer pedido
                    </button>
                    <button class="wa-quick-message btn btn-sm btn-outline-secondary d-block w-100"
                            data-message="Tengo una consulta general"
                            style="border-radius: 20px; text-align: left; padding: 10px 15px;
                                   transition: all 0.3s ease; border-color: #e0e0e0;">
                        <i class="bi bi-chat-dots me-2"></i>Consulta general
                    </button>
                </div>

                <!-- Mensaje personalizado -->
                <div class="mt-3">
                    <textarea class="form-control wa-custom-message"
                              placeholder="Escribe tu mensaje aquí..."
                              rows="3"
                              style="border-radius: 15px; border: 2px solid #e0e0e0;
                                     resize: none; font-size: 0.9rem;"></textarea>
                </div>
            </div>

            <!-- Footer -->
            <div class="wa-chat-footer"
                 style="padding: 15px 20px; background: white;
                        border-top: 1px solid #e0e0e0;">
                <button class="wa-send-btn btn w-100"
                        style="background: linear-gradient(135deg, #25D366, #128C7E);
                               color: white; border: none; border-radius: 25px;
                               padding: 12px; font-weight: 600; font-size: 1rem;
                               transition: all 0.3s ease;">
                    <i class="bi bi-whatsapp me-2"></i>Iniciar Chat
                </button>
                <p class="text-center text-muted small mb-0 mt-2">
                    Responderemos lo antes posible
                </p>
            </div>
        </div>

        <style>
            @keyframes slideUp {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @keyframes pulse {
                0%, 100% {
                    transform: scale(1);
                    opacity: 1;
                }
                50% {
                    transform: scale(1.1);
                    opacity: 0.8;
                }
            }

            .wa-quick-message:hover {
                background: #25D366 !important;
                color: white !important;
                border-color: #25D366 !important;
                transform: translateX(5px);
            }

            .wa-send-btn:hover {
                box-shadow: 0 5px 15px rgba(37, 211, 102, 0.4);
                transform: translateY(-2px);
            }

            .wa-close-btn:hover {
                background: rgba(255,255,255,0.3) !important;
                transform: rotate(90deg);
            }

            @media (max-width: 480px) {
                #whatsappChatBox {
                    width: calc(100% - 40px) !important;
                    left: 20px !important;
                    right: 20px !important;
                }
            }
        </style>
        `;

        $('body').append(chatBoxHTML);
    }

    // Toggle chatbox
    function toggleChatBox() {
        const $chatBox = $('#whatsappChatBox');

        if (config.chatBox && $chatBox.length > 0) {
            if (isOpen) {
                closeChatBox();
            } else {
                openChatBox();
            }
        } else {
            // Si no hay chatbox, abrir WhatsApp directamente
            openWhatsApp();
        }
    }

    // Abrir chatbox
    function openChatBox() {
        const $button = $('#whatsappFloat');
        const $chatBox = $('#whatsappChatBox');

        isOpen = true;

        // Animar botón
        $button.css({
            'transform': 'scale(0.9)',
            'box-shadow': '0 2px 10px rgba(37, 211, 102, 0.3)'
        });

        // Mostrar chatbox
        $chatBox.fadeIn(300);

        // Detener pulso
        stopPulseAnimation();

        // Focus en textarea
        setTimeout(() => {
            $('.wa-custom-message').focus();
        }, 400);
    }

    // Cerrar chatbox
    function closeChatBox() {
        const $button = $('#whatsappFloat');
        const $chatBox = $('#whatsappChatBox');

        isOpen = false;

        // Animar botón
        $button.css({
            'transform': 'scale(1)',
            'box-shadow': '0 4px 20px rgba(37, 211, 102, 0.4)'
        });

        // Ocultar chatbox
        $chatBox.fadeOut(300);

        // Reiniciar pulso
        startPulseAnimation();
    }

    // Abrir WhatsApp
    function openWhatsApp() {
        let message = $('.wa-custom-message').val().trim();

        if (!message) {
            message = config.defaultMessage;
        }

        const url = `https://wa.me/${config.phoneNumber}?text=${encodeURIComponent(message)}`;

        // Abrir en nueva ventana
        window.open(url, '_blank');

        // Cerrar chatbox
        closeChatBox();

        // Limpiar mensaje
        $('.wa-custom-message').val('');
    }

    // Manejar mensajes rápidos
    function handleQuickMessage() {
        const message = $(this).data('message');
        $('.wa-custom-message').val(message);

        // Highlight del textarea
        $('.wa-custom-message').addClass('animate__animated animate__pulse');
        setTimeout(() => {
            $('.wa-custom-message').removeClass('animate__animated animate__pulse');
        }, 1000);
    }

    // Iniciar animación de pulso
    function startPulseAnimation() {
        pulseIntervalId = setInterval(() => {
            $('#whatsappFloat').addClass('animate__animated animate__pulse');
            setTimeout(() => {
                $('#whatsappFloat').removeClass('animate__animated animate__pulse');
            }, 1000);
        }, config.pulseInterval);
    }

    // Detener animación de pulso
    function stopPulseAnimation() {
        if (pulseIntervalId) {
            clearInterval(pulseIntervalId);
        }
    }

    // Verificar horario de atención
    function isWorkingHours() {
        if (!config.workingHours.enabled) return true;

        const now = new Date();
        const days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        const currentDay = days[now.getDay()];

        const schedule = config.workingHours.schedule[currentDay];

        if (!schedule) return false; // Día cerrado

        const currentTime = now.getHours() * 60 + now.getMinutes();
        const [openHour, openMin] = schedule.open.split(':').map(Number);
        const [closeHour, closeMin] = schedule.close.split(':').map(Number);

        const openTime = openHour * 60 + openMin;
        const closeTime = closeHour * 60 + closeMin;

        return currentTime >= openTime && currentTime <= closeTime;
    }

    // Inicializar cuando el DOM esté listo
    $(document).ready(function() {
        initWhatsAppFloat();
    });

})(jQuery);
