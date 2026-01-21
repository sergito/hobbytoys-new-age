# HobbyToys Pro - Tema WordPress Profesional

Tema ultra profesional para tienda de juguetes con enfoque en SEO, conversiones y experiencia de usuario.

**Versión:** 1.0.0
**Autor:** HobbyToys Team
**Requiere:** WordPress 6.0+, WooCommerce 8.0+
**Tema Padre:** Bootscore

---

## 📋 Tabla de Contenidos

- [Características](#características)
- [Instalación](#instalación)
- [Configuración](#configuración)
- [Estructura del Tema](#estructura-del-tema)
- [Personalización](#personalización)
- [SEO](#seo)
- [Funcionalidades](#funcionalidades)
- [Soporte](#soporte)

---

## ✨ Características

### 🎨 Diseño Ultra Profesional
- **Product Cards** con información completa y badges dinámicos
- **Single Product** layout reorganizado para máximas conversiones
- **Checkout en 4 pasos** con campos mínimos
- **Diseño responsive** optimizado para todos los dispositivos

### 🚀 SEO Extremo
- **Schema.org markup** completo para productos, breadcrumbs, organización
- **Open Graph** y Twitter Cards optimizados
- **Meta tags** personalizados por tipo de página
- **Canonical URLs** automáticos
- **Alt tags** optimizados en todas las imágenes
- **Lazy loading** de imágenes para mejor performance

### 🛍️ Product Cards Avanzadas
Cada card de producto incluye:
- **Imagen** optimizada con hover effect
- **Badges**: Categoría, edad, descuento, envío gratis
- **Rating** con conteo de valoraciones
- **Descripción corta** (2 líneas)
- **Features** (hasta 2 características)
- **Precio** destacado con precio sin IVA
- **Opciones de pago**: 6 cuotas, Banco Provincia, transferencia
- **Botón wishlist** con persistencia en localStorage
- **Schema markup** completo

### 📦 Single Product Optimizado
Layout profesional con:
1. Breadcrumb
2. Badges de categoría y edad
3. Título H1 optimizado para SEO
4. Rating con link a valoraciones
5. Precio con precio sin IVA
6. Descripción corta
7. 3 características clave
8. Selector de cantidad + Agregar al carrito
9. Formas de pago destacadas
10. Calculadora de envío
11. Garantías (pago seguro, envío rápido, 100% original)
12. Productos relacionados por categoría

### 💳 Checkout en 4 Pasos
Flujo optimizado:
- **Paso 1:** Carrito - Revisión de productos
- **Paso 2:** Envío - Datos de entrega (campos mínimos)
- **Paso 3:** Pago - Método de pago
- **Paso 4:** Confirmación - Revisión final

Campos reducidos a lo esencial:
- Nombre, Apellido
- Email, Teléfono
- Dirección, Ciudad, Provincia, CP

### 🎯 Características Técnicas
- **Arquitectura modular** - Código organizado en `/inc/`
- **Bootstrap 5** - Framework CSS moderno
- **Bootstrap Icons** - 100+ iconos vectoriales
- **Google Fonts** - Poppins e Inter
- **SCSS** con variables personalizables
- **JavaScript** modular y eficiente
- **AJAX** para interacciones dinámicas
- **localStorage** para wishlist persistente

---

## 📥 Instalación

### 1. Requisitos Previos
```
- WordPress 6.0 o superior
- WooCommerce 8.0 o superior
- Tema padre Bootscore instalado
- PHP 7.4 o superior
```

### 2. Instalación del Tema

#### Opción A: Via WordPress Admin
1. Descargar `hobbytoys-pro.zip`
2. Ir a **Apariencia > Temas > Añadir nuevo**
3. Click en **Subir tema**
4. Seleccionar el archivo `.zip`
5. Click en **Instalar ahora**
6. **Activar** el tema

#### Opción B: Via FTP
1. Descomprimir `hobbytoys-pro.zip`
2. Subir la carpeta `hobbytoys-pro` a `/wp-content/themes/`
3. Ir a **Apariencia > Temas**
4. **Activar** HobbyToys Pro

### 3. Instalación de Tema Padre
Si no tenés Bootscore instalado:
1. Descargar desde https://bootscore.me/
2. Instalar y NO activar (solo necesita estar presente)

---

## ⚙️ Configuración

### 1. Configuración Inicial

#### Después de activar el tema:

1. **Ir a Apariencia > Personalizar > HobbyToys Pro Settings**
   - Configurar monto de envío gratis (default: $90,000)
   - Agregar número de WhatsApp

2. **Configurar Menús** (Apariencia > Menús)
   - Crear y asignar:
     - Menú Principal
     - Menú Categorías
     - Menú Footer

3. **Configurar Widgets** (Apariencia > Widgets)
   - Footer Columna 1-4
   - Sidebar Tienda

### 2. Configuración de WooCommerce

#### Tamaños de Imagen Recomendados:
```
Productos: 800x800px (cuadrado)
Miniaturas: 400x400px
Categorías: 150x150px
```

Para regenerar miniaturas existentes, usar plugin:
`Regenerate Thumbnails`

#### Ajustes de Producto:
1. **Productos > Agregar nuevo**
2. **Completar campos SEO:**
   - Descripción corta (máx. 80 caracteres para cards)
   - Descripción completa
   - Categoría (evitar "JUGUETERIA" genérico)
   - Atributo "Edades" (pa_edades)

3. **Campos personalizados** (Meta boxes):
   - `_ht_feature_1`: Primera característica
   - `_ht_feature_2`: Segunda característica
   - `_ht_feature_3`: Tercera característica

### 3. Compilar SCSS (Opcional)

Si querés personalizar estilos:

```bash
# Instalar dependencias
npm install sass --save-dev

# Compilar SCSS
sass assets/scss/main.scss assets/css/main.css --style compressed

# Watch mode (auto-compilar)
sass --watch assets/scss/main.scss:assets/css/main.css
```

---

## 📁 Estructura del Tema

```
hobbytoys-pro/
├── assets/
│   ├── css/
│   │   └── main.css               # CSS compilado
│   ├── js/
│   │   ├── main.js                # JavaScript principal
│   │   └── woocommerce.js         # WooCommerce específico
│   ├── scss/
│   │   ├── _variables.scss        # Variables del tema
│   │   └── main.scss              # SCSS principal
│   ├── images/                    # Imágenes del tema
│   └── fonts/                     # Tipografías custom
├── inc/
│   ├── theme-setup.php            # Configuración base
│   ├── customizer.php             # Customizer WordPress
│   ├── woocommerce-setup.php      # Setup WooCommerce
│   ├── woocommerce-product-card.php   # Product cards
│   ├── woocommerce-single-product.php # Single product
│   ├── woocommerce-checkout.php   # Checkout 4 pasos
│   └── woocommerce-seo.php        # Optimizaciones SEO
├── woocommerce/                   # Templates WooCommerce
│   ├── loop/                      # Loop de productos
│   ├── single-product/            # Producto individual
│   ├── checkout/                  # Checkout
│   └── cart/                      # Carrito
├── template-parts/                # Componentes reutilizables
│   ├── header/
│   ├── footer/
│   └── product/
├── functions.php                  # Funciones principales
├── style.css                      # Metadata del tema
└── README.md                      # Esta documentación
```

---

## 🎨 Personalización

### Colores del Tema

Los colores están definidos en `/assets/scss/_variables.scss`:

```scss
$ht-primary:   #EE285B  // Rosa principal
$ht-secondary: #534fb5  // Púrpura
$ht-accent:    #FFB900  // Amarillo
$ht-cyan:      #0dcaf0
$ht-green:     #198754
```

**Para cambiar colores:**
1. Editar `_variables.scss`
2. Recompilar: `sass assets/scss/main.scss assets/css/main.css`
3. Limpiar caché del navegador

### Tipografía

Fuentes configuradas:
- **Poppins:** Títulos (weights: 300-900)
- **Inter:** Texto body (weights: 300-700)

**Cambiar fuente:**
```scss
// En _variables.scss
$font-primary: 'Tu-Fuente', sans-serif;
```

No olvides agregar la fuente en `functions.php`:
```php
wp_enqueue_style('custom-font', 'URL-DE-TU-FUENTE');
```

### Agregar Categorías con Iconos

Editar `/inc/woocommerce-setup.php`, función `ht_get_category_config()`:

```php
'tu-categoria-slug' => [
    'icon'  => 'bi-tu-icono',      // Ver: https://icons.getbootstrap.com/
    'color' => '#FF0000',          // Color hex
    'name'  => 'Nombre Categoría'
],
```

---

## 🔍 SEO

### Schema Markup Incluido

El tema genera automáticamente:

1. **Product Schema** en cada producto:
   - Precio, disponibilidad, SKU
   - Valoraciones y reviews
   - Imagen, descripción, marca

2. **Breadcrumb Schema** en:
   - Páginas de producto
   - Categorías
   - Tienda

3. **Organization Schema** en homepage:
   - Datos de la empresa
   - Logo, contacto
   - Redes sociales

4. **WebSite Schema** con SearchAction

### Meta Tags

Generados automáticamente:
- **Open Graph** (Facebook)
- **Twitter Cards**
- **Canonical URLs**
- **Meta description** optimizada
- **Meta keywords** (categorías + producto)

### Optimización de Imágenes

- **Alt tags** automáticos: `"[Producto] - Juguete de calidad - HobbyToys"`
- **Lazy loading** nativo en todas las imágenes
- **Sizes** correctos para responsive

### Sitemap (con Yoast/RankMath)

Productos sin stock se excluyen automáticamente del sitemap.

---

## 🛠️ Funcionalidades

### Wishlist (Lista de Deseos)

**Características:**
- Guardado en `localStorage` del navegador
- Persistente entre sesiones
- Botón corazón en cada producto
- Animación al agregar/quitar
- Notificaciones toast

**Uso:**
```html
<button class="btn-wishlist" data-product-id="123">
    <i class="bi bi-heart"></i>
</button>
```

### Multi-Step Checkout

**Navegación:**
- Botones "Anterior" y "Siguiente"
- Indicador visual de progreso
- Click en step para volver atrás
- Validación por paso

**AJAX Endpoints:**
```php
ht_checkout_next_step    // Ir al siguiente paso
ht_checkout_prev_step    // Volver atrás
ht_checkout_goto_step    // Ir a paso específico
```

### Calculadora de Envío

**Ubicación:** Single product

**Uso:**
```html
<input type="text" id="shipping-zip" placeholder="CP">
<button id="calculate-shipping">Calcular</button>
<div id="shipping-results"></div>
```

**Personalizar cálculo:**
Editar `assets/js/main.js`, función `calculate-shipping`

### Product Badges

**Tipos de badges:**

1. **Sale:** Descuento porcentual
2. **Category:** Color e icono único por categoría
3. **Age:** Color según rango de edad
4. **Shipping:** Envío gratis si supera mínimo

**Configuración:**
- Categorías: `/inc/woocommerce-setup.php` → `ht_get_category_config()`
- Edades: `/inc/woocommerce-setup.php` → `ht_get_age_colors()`

---

## 🎯 Hooks y Filtros

### Filtros Disponibles

```php
// Cambiar productos por página
add_filter('loop_shop_per_page', function() {
    return 24; // Tu valor
});

// Cambiar columnas de productos
add_filter('loop_shop_columns', function() {
    return 5; // Tu valor
});

// Personalizar título productos relacionados
add_filter('woocommerce_product_related_products_heading', function() {
    return 'Tu título personalizado';
});
```

### Acciones Disponibles

```php
// Después de badges de producto
do_action('ht_after_product_badges');

// Antes del precio
do_action('ht_before_product_price');

// Después de características
do_action('ht_after_product_features');
```

---

## 📱 Responsive

### Breakpoints

```scss
xs:  0-575px    // Mobile
sm:  576-767px  // Mobile landscape
md:  768-991px  // Tablet
lg:  992-1199px // Desktop
xl:  1200-1399px // Large desktop
xxl: 1400px+    // Extra large
```

### Optimizaciones Mobile

- Cards apiladas en 2 columnas
- Quick actions siempre visibles
- Navegación simplificada
- Forms fullwidth
- Touch-friendly buttons (min 44x44px)

---

## ⚡ Performance

### Optimizaciones Incluidas

1. **CSS/JS minificados** en producción
2. **Lazy loading** de imágenes
3. **Preconnect** a Google Fonts
4. **DNS prefetch** para recursos externos
5. **Sin emojis** de WordPress (ahorro de HTTP requests)
6. **Fragments** de WooCommerce optimizados

### Plugins Recomendados

- **WP Rocket** - Caché y optimización
- **Smush** - Compresión de imágenes
- **Autoptimize** - Minificación avanzada
- **Cloudflare** - CDN y optimización global

---

## 🐛 Debugging

### Habilitar Debug

En `wp-config.php`:
```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

### Función Debug del Tema

```php
// En cualquier template
ht_debug($variable);

// Con exit
ht_debug($variable, true);
```

Los logs se guardan en: `/wp-content/debug.log`

---

## 📝 Changelog

### Version 1.0.0 (2026-01-21)
- ✨ Release inicial
- 🎨 Product cards ultra profesionales
- 📦 Single product optimizado
- 💳 Checkout en 4 pasos
- 🔍 SEO extremo con Schema markup
- 📱 100% responsive
- ⚡ Performance optimizado

---

## 🤝 Soporte

### Documentación
- **Tema Padre Bootscore:** https://bootscore.me/documentation/
- **WooCommerce:** https://woocommerce.com/documentation/
- **Bootstrap 5:** https://getbootstrap.com/docs/5.3/

### Contacto
- **Email:** soporte@hobbytoys.com.ar
- **Web:** https://hobbytoys.com.ar

---

## 📜 Licencia

MIT License - Libre para uso comercial y personal.

---

## 🙏 Créditos

- **Tema Padre:** Bootscore by Bootscore.me
- **Framework CSS:** Bootstrap 5
- **Iconos:** Bootstrap Icons
- **Fuentes:** Google Fonts (Poppins, Inter)

---

**Desarrollado con ❤️ por HobbyToys Team**

*Para mejores resultados, combinar con contenido de calidad y estrategia SEO sólida.*
