# Nuevas Funcionalidades - Hobby Toys

Este documento describe las 4 nuevas funcionalidades implementadas para mejorar la experiencia de usuario en la tienda de juguetes Hobby Toys.

## 📦 Funcionalidades Implementadas

### 1. 👁️ Quick View de Productos
Modal rápido para ver productos sin salir de la página actual.

**Características:**
- Vista rápida con información completa del producto
- Galería de imágenes con miniaturas
- Sistema de rating con estrellas
- Selector de cantidad
- Botón de agregar al carrito
- Integración con wishlist
- Compartir en redes sociales
- Animaciones suaves

**Archivos:**
- `assets/js/quick-view.js`

**Uso:**
- Los botones de "Vista Rápida" se agregan automáticamente a todas las tarjetas `.product-card`
- Aparecen al hacer hover sobre el producto
- Click para abrir el modal con información completa

### 2. ❤️ Sistema de Wishlist/Lista de Deseos
Guardar productos favoritos con iconos de corazón y almacenamiento local.

**Características:**
- Botones de corazón en cada producto
- Almacenamiento en LocalStorage (persistente)
- Botón flotante animado con contador
- Modal de lista completa
- Compartir lista por WhatsApp
- Animaciones de entrada/salida
- Notificaciones visuales

**Archivos:**
- `assets/js/wishlist.js`

**Uso:**
- Click en el corazón de cualquier producto para agregarlo/quitarlo
- Click en el botón flotante rojo para ver la lista completa
- Los datos persisten entre sesiones del navegador

**LocalStorage:**
- Key: `hobbytoys_wishlist`
- Formato: Array de objetos con id, name, price, image, addedAt

### 3. 🔍 Búsqueda Ajax Avanzada
Sistema de búsqueda en tiempo real con autocompletado y sugerencias.

**Características:**
- Búsqueda en tiempo real (debounce de 300ms)
- Autocompletado inteligente
- Búsqueda en nombre, categoría y tags
- Historial de búsquedas recientes (LocalStorage)
- Categorías populares
- Resaltado de términos de búsqueda
- Navegación con teclado (flechas arriba/abajo, Enter, Escape)
- Loader animado
- Resultados con imágenes y precios
- Filtros por categoría

**Archivos:**
- `assets/js/ajax-search.js`

**Uso:**
- Se activa automáticamente en todos los `<input type="search">` y `.search-input`
- Escribe para ver resultados en tiempo real
- Usa las flechas del teclado para navegar
- Enter para seleccionar
- Escape para cerrar

**LocalStorage:**
- Key: `hobbytoys_recent_searches`
- Límite: 10 búsquedas más recientes

**Integración con WooCommerce:**
Para usar con productos reales de WooCommerce, reemplaza la función `searchProducts()` con una llamada Ajax:

```javascript
function performSearch(query, $container) {
    $.ajax({
        url: ajaxurl, // URL de admin-ajax.php
        type: 'POST',
        data: {
            action: 'search_products',
            query: query
        },
        success: function(response) {
            renderSearchResults(response.products, $resultsList);
        }
    });
}
```

### 4. 💬 WhatsApp Flotante Animado
Botón fixed con animación y chatbox interactivo.

**Características:**
- Botón flotante con animación de pulso
- Chatbox con mensajes de bienvenida
- Mensajes rápidos predefinidos
- Campo de mensaje personalizado
- Tooltip informativo
- Soporte para horario de atención
- Posicionamiento configurable (derecha/izquierda)
- Responsive design
- Badge de notificación

**Archivos:**
- `assets/js/whatsapp-float.js`

**Configuración:**
Edita las siguientes variables al inicio del archivo:

```javascript
const config = {
    phoneNumber: '5492214567890', // Tu número de WhatsApp
    defaultMessage: '¡Hola! Me gustaría consultar sobre sus productos.',
    agentName: 'Equipo Hobby Toys',
    agentImage: 'URL_de_la_imagen',
    position: 'bottom-right', // o 'bottom-left'
    showDelay: 2000, // Delay antes de mostrar (ms)
    pulseInterval: 5000, // Intervalo de pulso (ms)
    chatBox: true, // Mostrar chatbox antes de abrir WhatsApp
    workingHours: {
        enabled: false, // Activar horario de atención
        // ... más configuraciones
    }
};
```

**Uso:**
- El botón aparece automáticamente después del delay configurado
- Click para abrir chatbox (si está habilitado)
- Selecciona mensaje rápido o escribe uno personalizado
- Click en "Iniciar Chat" para abrir WhatsApp

## 🚀 Instalación

### Paso 1: Copiar archivos JavaScript
Copia los siguientes archivos a tu carpeta de assets:

```
bootscore-child/
├── assets/
│   └── js/
│       ├── quick-view.js
│       ├── wishlist.js
│       ├── ajax-search.js
│       └── whatsapp-float.js
```

### Paso 2: Incluir scripts en WordPress
Agrega los scripts a tu `functions.php`:

```php
function hobbytoys_enqueue_scripts() {
    // Quick View
    wp_enqueue_script(
        'hobbytoys-quick-view',
        get_stylesheet_directory_uri() . '/assets/js/quick-view.js',
        array('jquery', 'bootstrap'),
        '1.0.0',
        true
    );

    // Wishlist
    wp_enqueue_script(
        'hobbytoys-wishlist',
        get_stylesheet_directory_uri() . '/assets/js/wishlist.js',
        array('jquery', 'bootstrap'),
        '1.0.0',
        true
    );

    // Ajax Search
    wp_enqueue_script(
        'hobbytoys-ajax-search',
        get_stylesheet_directory_uri() . '/assets/js/ajax-search.js',
        array('jquery'),
        '1.0.0',
        true
    );

    // WhatsApp Float
    wp_enqueue_script(
        'hobbytoys-whatsapp-float',
        get_stylesheet_directory_uri() . '/assets/js/whatsapp-float.js',
        array('jquery'),
        '1.0.0',
        true
    );
}
add_action('wp_enqueue_scripts', 'hobbytoys_enqueue_scripts');
```

### Paso 3: Asegurar dependencias
Verifica que estén disponibles:

- ✅ jQuery
- ✅ Bootstrap 5 (CSS y JS)
- ✅ Bootstrap Icons
- ✅ Animate.css (opcional, mejora las animaciones)

## 📄 Demo

Abre el archivo `demo-features.html` en tu navegador para ver todas las funcionalidades en acción.

```
http://localhost/hobbytoys-prod/bootscore-child/demo-features.html
```

**Instrucciones de la demo:**
1. **Quick View:** Haz hover sobre un producto y click en "Vista Rápida"
2. **Wishlist:** Click en el corazón de cualquier producto
3. **Búsqueda:** Escribe en el buscador del header (ej: "lego", "drone")
4. **WhatsApp:** Observa el botón verde en la esquina inferior derecha

## 🎨 Personalización

### Colores
Las funcionalidades usan las variables CSS del tema:

```css
:root {
    --primary-color: #EE285B;
    --secondary-color: #534fb5;
    --accent-color: #ffb900;
    --light-bg: #f4efe8;
    --text-dark: #2C3E50;
    --text-light: #7F8C8D;
}
```

### Quick View - Personalizar datos
En `quick-view.js`, edita el objeto `productData` para adaptarlo a tus productos reales de WooCommerce.

### Wishlist - Cambiar almacenamiento
Por defecto usa LocalStorage. Para usar una base de datos, modifica las funciones `loadWishlist()` y `saveWishlist()`.

### Búsqueda - Conectar con WooCommerce
Reemplaza `searchDatabase` con una llamada Ajax real a tu backend de WordPress.

### WhatsApp - Personalizar mensajes
Edita el objeto `config` al inicio de `whatsapp-float.js`.

## 📱 Responsive Design

Todas las funcionalidades están optimizadas para móviles:

- ✅ Quick View: Modal adaptable
- ✅ Wishlist: Botón flotante responsive
- ✅ Búsqueda: Dropdown de ancho completo en móvil
- ✅ WhatsApp: ChatBox de ancho completo en móvil

## 🔧 Compatibilidad

- **Navegadores:** Chrome, Firefox, Safari, Edge (últimas 2 versiones)
- **WordPress:** 5.0+
- **WooCommerce:** 5.0+
- **PHP:** 7.4+
- **jQuery:** 3.0+
- **Bootstrap:** 5.0+

## 📊 Performance

- **Quick View:** ~15KB (minificado)
- **Wishlist:** ~12KB (minificado)
- **Ajax Search:** ~18KB (minificado)
- **WhatsApp Float:** ~10KB (minificado)

**Total:** ~55KB de JavaScript adicional

**Recomendaciones:**
- Minifica los archivos para producción
- Combina en un solo archivo si es posible
- Usa carga diferida (defer) para mejorar performance

## 🐛 Troubleshooting

### Quick View no aparece
- Verifica que las tarjetas tengan la clase `.product-card`
- Asegúrate de que Bootstrap esté cargado
- Revisa la consola del navegador por errores

### Wishlist no persiste
- Verifica que LocalStorage esté habilitado
- Revisa la configuración de privacidad del navegador
- Comprueba que no haya errores en la consola

### Búsqueda no funciona
- Verifica que el input tenga `type="search"` o clase `.search-input`
- Asegúrate de que jQuery esté cargado
- Revisa la estructura del `searchDatabase`

### WhatsApp no abre
- Verifica el formato del número de teléfono (con código de país)
- Comprueba que el navegador permita pop-ups
- Revisa la configuración del objeto `config`

## 📝 Notas Adicionales

### Seguridad
- Los datos del wishlist se almacenan localmente, no en servidor
- Sanitiza siempre los inputs antes de enviar a WhatsApp
- Valida números de teléfono antes de usar

### SEO
- Quick View no afecta el SEO (contenido accesible)
- La búsqueda Ajax es complementaria (mantén búsqueda tradicional)
- WhatsApp flotante tiene `aria-label` para accesibilidad

### Analytics
Puedes trackear eventos con Google Analytics:

```javascript
// Ejemplo para Quick View
gtag('event', 'quick_view', {
    'product_id': productId,
    'product_name': productName
});
```

## 🤝 Soporte

Para dudas o problemas:
- Revisa la consola del navegador
- Verifica las dependencias
- Contacta al equipo de desarrollo

## 📜 Licencia

Desarrollado para Hobby Toys - Todos los derechos reservados © 2025
