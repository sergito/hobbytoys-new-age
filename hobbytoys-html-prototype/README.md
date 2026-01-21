# 🎮 HobbyToys HTML Prototype

Prototipo HTML/CSS/JS puro de las **product cards ultra profesionales** para HobbyToys.

---

## 📁 Estructura

```
hobbytoys-html-prototype/
├── index.html              # Product cards (shop page)
├── css/
│   └── styles.css          # CSS completo con design system
├── js/
│   └── main.js             # JavaScript interactivo
└── README.md               # Esta documentación
```

---

## 🚀 Cómo Ver el Prototipo

### **Opción 1: Abrir directamente**
```bash
# Navegar a la carpeta
cd /Users/sergio/Local\ Sites/hobbytoys/app/public/wp-content/themes/hobbytoys-new-age/hobbytoys-html-prototype

# Abrir index.html en el navegador
open index.html
```

### **Opción 2: Servidor local (recomendado)**
```bash
# Con Python 3
cd hobbytoys-html-prototype
python3 -m http.server 8000

# Abrir en navegador:
# http://localhost:8000
```

### **Opción 3: Live Server (VSCode)**
1. Abrir carpeta en VSCode
2. Click derecho en `index.html`
3. "Open with Live Server"

---

## ✨ Características Implementadas

### **Product Cards Completas:**
✅ **Imagen** con hover zoom effect
✅ **Badges dinámicos:**
   - Sale (% de descuento con animación pulse)
   - Categoría (con icono Bootstrap)
   - Edad (con código de colores)
   - Envío Gratis
✅ **Botón Wishlist** (corazón) con:
   - LocalStorage persistente
   - Animación heartBeat al agregar
   - Estado activo visual
   - Contador en header
✅ **Rating** con estrellas y contador
✅ **Descripción corta** (2 líneas)
✅ **Features** (2 características con iconos)
✅ **Precio destacado** con precio anterior tachado
✅ **Opciones de pago:**
   - 6 cuotas sin interés
   - Banco Provincia (10% OFF + 4 cuotas)
✅ **Botón "Agregar al Carrito"** con:
   - Animación pulse al click
   - Contador en header
   - Notificación toast

### **Interactividad JavaScript:**
✅ Wishlist con localStorage
✅ Add to cart con contador
✅ Notificaciones toast (success/error/info)
✅ Scroll reveal animations
✅ Product title formatting (Title Case)
✅ Smooth scroll
✅ Header con shadow al scroll

### **Diseño & UX:**
✅ **100% Responsive** (mobile-first)
✅ **Bootstrap 5.3** (grid, utilities)
✅ **Bootstrap Icons 1.11.3**
✅ **Google Fonts** (Poppins, Inter)
✅ **Animate.css** para animaciones
✅ **Design System** completo con CSS Variables
✅ **Hover effects** suaves en cards
✅ **Shadow elevation** al hover
✅ **Fade in animations** al cargar

---

## 🎨 Paleta de Colores

```css
--ht-primary:   #EE285B  /* Rosa principal */
--ht-secondary: #534fb5  /* Púrpura */
--ht-accent:    #FFB900  /* Amarillo */
--ht-cyan:      #0dcaf0  /* Info */
--ht-green:     #198754  /* Success */
--ht-orange:    #fd7e14  /* Disfraces */
--ht-red:       #dc3545  /* Sale */
```

---

## 🧩 Componentes

### **Badge Colors por Categoría:**
- **Arte:** #EE285B (Rosa)
- **Disfraces:** #fd7e14 (Naranja)
- **Juegos Mesa:** #534fb5 (Púrpura)
- **Sale:** #dc3545 (Rojo con pulse animation)
- **Envío Gratis:** #198754 (Verde)

### **Badge Colors por Edad:**
- **0-3 años:** #EE285B (Rosa - bebés)
- **4-6 años:** #0dcaf0 (Cyan - preescolar)
- **7-12 años:** #FFB900 (Amarillo - escolar)
- **12+ años:** #198754 (Verde - adolescentes)

---

## 📱 Responsive Breakpoints

```
xs:  0-575px    - Mobile
sm:  576-767px  - Mobile landscape
md:  768-991px  - Tablet
lg:  992-1199px - Desktop
xl:  1200px+    - Large desktop
```

### **Ajustes Mobile:**
- Cards apiladas 1-2 columnas
- Quick actions siempre visibles
- Badges más pequeños
- Texto reducido en payment info
- Notificaciones fullwidth

---

## 🔧 Personalización

### **Cambiar colores:**
```css
/* En css/styles.css línea 10-20 */
:root {
    --ht-primary: #TU_COLOR;
    --ht-secondary: #TU_COLOR;
    /* ... */
}
```

### **Agregar nueva categoría:**
```html
<!-- En index.html, dentro de badges -->
<span class="badge badge-category" style="background-color: #COLOR;">
    <i class="bi bi-TU-ICONO me-1"></i>Nombre
</span>
```

Ver iconos disponibles: https://icons.getbootstrap.com/

### **Modificar animaciones:**
```css
/* En css/styles.css línea 700+ */
@keyframes fadeInUp {
    /* Tu animación */
}
```

---

## 📦 Próximos Pasos

### **Para integrar a WordPress:**

1. **Convertir HTML a PHP:**
   ```php
   <?php
   // index.html → woocommerce/content-product.php
   // Reemplazar contenido estático por:

   $product = wc_get_product(get_the_ID());
   echo $product->get_name();
   echo $product->get_price_html();
   // etc.
   ```

2. **Copiar CSS a tema:**
   ```bash
   cp css/styles.css ../hobbytoys-pro/assets/css/product-cards.css
   ```

3. **Copiar JavaScript:**
   ```bash
   cp js/main.js ../hobbytoys-pro/assets/js/product-cards.js
   ```

4. **Enqueue en functions.php:**
   ```php
   wp_enqueue_style('ht-product-cards', ...);
   wp_enqueue_script('ht-product-cards', ...);
   ```

---

## 🎯 Funcionalidades a Agregar

Ideas para siguientes iteraciones:

- [ ] Quick View modal
- [ ] Compare products
- [ ] Stock indicator
- [ ] Countdown timer para ofertas
- [ ] Image gallery hover
- [ ] Variations preview
- [ ] Recently viewed
- [ ] Related products slider

---

## 📸 Screenshots

### Desktop:
- Grid 4 columnas
- Hover effects completos
- Badges visibles
- Spacing optimizado

### Mobile:
- Grid 2 columnas
- Touch-friendly buttons
- Quick actions siempre visibles
- Notificaciones adaptadas

---

## 🐛 Testing

### **Checklist:**
- [x] Cards se ven correctamente
- [x] Hover effects funcionan
- [x] Wishlist guarda en localStorage
- [x] Add to cart actualiza contador
- [x] Notificaciones aparecen y desaparecen
- [x] Animaciones suaves
- [x] Responsive en mobile
- [x] Badges con colores correctos
- [x] Tipografías cargan bien
- [x] No hay errores en consola

---

## 🚀 Performance

### **Optimizaciones incluidas:**
✅ CSS Variables para theming
✅ Transitions con `transform` (GPU)
✅ Lazy animations con IntersectionObserver
✅ LocalStorage en lugar de AJAX
✅ Event delegation
✅ Debounced scroll handlers
✅ CDN para Bootstrap e íconos
✅ Minified libraries

---

## 📝 Notas de Desarrollo

### **Por qué este enfoque:**
1. **Más rápido** - Ver cambios instantáneos
2. **Más limpio** - Sin limitaciones de PHP/WP
3. **Más fácil** - Iterar diseño sin backend
4. **Portable** - Fácil migrar a cualquier plataforma

### **Diferencias con WordPress:**
- En WP: `$product->get_name()`
- Aquí: HTML estático
- En WP: Shortcodes, hooks, filters
- Aquí: JavaScript vanilla

### **Para producción:**
- Compilar SCSS si usás Sass
- Minificar CSS/JS
- Optimizar imágenes
- Agregar meta tags SEO
- Configurar cache headers

---

## 🎨 Design System

Todo el diseño usa **CSS Custom Properties** para fácil personalización:

```css
/* Colores */
var(--ht-primary)
var(--ht-secondary)

/* Spacing */
var(--spacing-sm)
var(--spacing-md)

/* Shadows */
var(--shadow-sm)
var(--shadow-lg)

/* Transitions */
var(--transition-base)
```

---

## 💡 Tips

1. **Para debugging:** Abrir DevTools (F12)
2. **Para mobile:** DevTools → Toggle device toolbar (Ctrl+Shift+M)
3. **Para performance:** Lighthouse en DevTools
4. **Para accessibility:** axe DevTools extension

---

**Desarrollado con ❤️ para HobbyToys**

*Diseño limpio, código limpio, resultados profesionales.*
