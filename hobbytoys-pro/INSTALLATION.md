# 📦 Instalación de HobbyToys Pro

## ⚠️ IMPORTANTE: Compilar CSS

Este tema usa **SCSS** para los estilos. Antes de usar el tema, necesitás compilar el CSS:

### Opción 1: Compilar localmente (Recomendado)

```bash
# 1. Navegar a la carpeta del tema
cd /Users/sergio/Local\ Sites/hobbytoys/app/public/wp-content/themes/hobbytoys-pro

# 2. Instalar Sass (solo la primera vez)
npm install -g sass

# 3. Compilar SCSS a CSS
sass assets/scss/main.scss assets/css/main.css --style compressed

# 4. (Opcional) Modo watch para auto-compilar mientras desarrollás
sass --watch assets/scss/main.scss:assets/css/main.css
```

### Opción 2: Usar Online SCSS Compiler

Si no querés instalar nada:

1. Ir a: https://www.sassmeister.com/
2. Copiar el contenido de `assets/scss/_variables.scss`
3. Copiar el contenido de `assets/scss/main.scss` debajo
4. Click en "Compile"
5. Copiar el CSS resultante
6. Guardar en `assets/css/main.css`

---

## 📋 Pasos de Instalación Completos

### 1. Preparar el Tema

```bash
# En tu Mac, en la carpeta themes
cd /Users/sergio/Local\ Sites/hobbytoys/app/public/wp-content/themes

# Compilar SCSS
cd hobbytoys-pro
sass assets/scss/main.scss assets/css/main.css --style compressed
```

### 2. Activar el Tema

1. Ir a WordPress Admin: `http://hobbytoys.local/wp-admin`
2. **Apariencia > Temas**
3. Activar **HobbyToys Pro**

### 3. Configuración Inicial

#### A. HobbyToys Settings
1. **Apariencia > Personalizar**
2. Ir a **HobbyToys Pro Settings**
3. Configurar:
   - Monto Envío Gratis: `90000`
   - Número WhatsApp: `5492215608027`

#### B. Menús
1. **Apariencia > Menús**
2. Crear 3 menús:
   - **Menú Principal** → Asignar a ubicación "Menú Principal"
   - **Categorías** → Asignar a ubicación "Menú Categorías"
   - **Footer** → Asignar a ubicación "Menú Footer"

#### C. Widgets
1. **Apariencia > Widgets**
2. Configurar:
   - **Footer Columna 1-4:** Agregar widgets (texto, menú, etc.)
   - **Sidebar Tienda:** Agregar filtros de WooCommerce

### 4. Configurar Productos

Para que las **product cards** se vean completas, agregá a cada producto:

#### Campos Estándar:
- ✅ **Imagen destacada** (800x800px recomendado)
- ✅ **Descripción corta** (máx. 80 caracteres para cards)
- ✅ **Precio**
- ✅ **Categoría** (evitar "JUGUETERIA" genérico)
- ✅ **Atributo "Edades"** (`pa_edades`)

#### Campos Personalizados (Meta Boxes):
Agregar en cada producto:

1. **_ht_feature_1:** Primera característica
   - Ejemplo: "Material no tóxico"

2. **_ht_feature_2:** Segunda característica
   - Ejemplo: "Estimula la creatividad"

3. **_ht_feature_3:** Tercera característica
   - Ejemplo: "Fácil de limpiar"

**Cómo agregar:**
```php
// En functions.php (ya incluido) o via plugin ACF
update_post_meta($product_id, '_ht_feature_1', 'Tu característica 1');
update_post_meta($product_id, '_ht_feature_2', 'Tu característica 2');
update_post_meta($product_id, '_ht_feature_3', 'Tu característica 3');
```

O usa **Advanced Custom Fields** para crearlos en el admin.

---

## 🎨 Regenerar Miniaturas

Si tenés productos existentes, regenerá las imágenes:

1. Instalar plugin: **Regenerate Thumbnails**
2. **Herramientas > Regen. Thumbnails**
3. Click en **Regenerate All Thumbnails**

---

## ✅ Verificar Instalación

### Checklist:

- [ ] Tema **Bootscore** instalado (padre)
- [ ] Tema **HobbyToys Pro** activado
- [ ] CSS compilado (`assets/css/main.css` existe)
- [ ] WooCommerce instalado y configurado
- [ ] Al menos 1 producto de prueba creado
- [ ] Categorías configuradas (con iconos en código)
- [ ] Menús asignados
- [ ] Settings de HobbyToys configurados

### Probar:

1. **Homepage:** Ver si carga sin errores
2. **Tienda:** Ver cards de productos con badges
3. **Producto individual:** Ver layout completo
4. **Checkout:** Ver 4 pasos funcionando
5. **Responsive:** Probar en mobile

---

## 🐛 Solución de Problemas

### CSS no se aplica

**Problema:** El tema se ve sin estilos

**Solución:**
```bash
# Verificar que exista el archivo
ls -la assets/css/main.css

# Si no existe, compilar:
sass assets/scss/main.scss assets/css/main.css --style compressed

# Limpiar caché de WordPress
# Plugins > WP Rocket > Clear Cache (si lo usás)
```

### JavaScript no funciona

**Problema:** Wishlist o checkout no responde

**Solución:**
1. Abrir consola del navegador (F12)
2. Ver si hay errores de JavaScript
3. Verificar que jQuery esté cargado
4. Limpiar caché del navegador (Ctrl+Shift+R)

### Badges no aparecen

**Problema:** Product cards sin badges

**Solución:**
1. Verificar que el producto tenga **categoría** asignada
2. La categoría debe estar en `ht_get_category_config()`
3. Para edad: producto debe tener atributo `pa_edades`

### Checkout no avanza

**Problema:** Botón "Siguiente" no funciona

**Solución:**
1. Verificar que JavaScript esté cargado
2. Ver consola del navegador (errores)
3. Completar todos los campos requeridos
4. Verificar AJAX URL en consola

---

## 📞 Soporte

Si tenés problemas:

1. **Revisar** la consola del navegador (F12)
2. **Ver** `/wp-content/debug.log`
3. **Activar** `WP_DEBUG` en `wp-config.php`
4. **Contactar** soporte: soporte@hobbytoys.com.ar

---

## 🚀 Próximos Pasos

Después de instalar:

1. **Agregar contenido** (productos, categorías, páginas)
2. **Optimizar imágenes** (compression, WebP)
3. **Configurar SEO** (Yoast/RankMath)
4. **Instalar plugins** de performance (WP Rocket, Smush)
5. **Probar checkout** completo
6. **Configurar pasarelas de pago**
7. **Configurar envíos**

---

**¡Listo para vender! 🎉**
