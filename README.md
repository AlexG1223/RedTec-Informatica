# RedTec Informática — E-Commerce & Portal de Servicios Tecnológicos

Sitio web institucional, e-commerce de productos informáticos, catálogo de servicios técnicos/corporativos y panel de administración personalizado desarrollado para **RedTec Informática** (Atlántida, Canelones, Uruguay).

---

## 🚀 Características Principales

- **Tienda Pública & Catálogo**: Búsqueda en tiempo real y filtrado por categorías.
- **Carrito de Compras Frontend**: Persistencia local en `localStorage` con panel deslizante (`CartDrawer`) sin requerir registro de usuario.
- **Checkout directo a WhatsApp**: Conversión del carrito a mensaje formateado para coordinación de pago y entrega en Uruguay.
- **Servicios Técnicos & Planes Corporativos**: Fichas institucionales con botón de consulta directa por WhatsApp.
- **Panel de Administración (`/admin`)**:
  - Autenticación segura con bcrypt y protección CSRF.
  - CRUD completo de productos con galería de imágenes y baja lógica.
  - CRUD de categorías con prevención de borrado con productos vinculados.
  - **Ajuste rápido de stock en línea** (vía AJAX) con alertas visuales de **stock bajo** (`LOW_STOCK_THRESHOLD = 5`).
  - **Importación masiva de productos vía CSV** con previsualización de cambios y log de auditoría.
  - **Exportación de catálogo a CSV** compatible con MS Excel.
- **SEO & GEO (Generative Engine Optimization)**:
  - Metadatos canónicos, OpenGraph y Twitter Cards.
  - Datos estructurados JSON-LD Schema.org (`LocalBusiness`, `Product`, `BreadcrumbList`, `FAQPage`).
  - Generación dinámica de `sitemap.xml`, `robots.txt` y `llms.txt` para asistentes de IA.

---

## ⚠️ IMPORTANTE: Credenciales de Desarrollo & Producción

El esquema inicial de la base de datos incorpora un usuario administrador de prueba para desarrollo local:

- **Email por defecto**: `admin@redtecinformatica.com`
- **Contraseña por defecto**: `Admin2026!`

> 🔴 **ATENCIÓN SEGURIDAD ANTES DE PUBLICAR EN PRODUCCIÓN**:
> Antes de considerar el sitio en producción, **debés cambiar la contraseña de este usuario administrador desde la base de datos o crear un usuario nuevo y desactivar/eliminar el usuario de prueba**. NUNCA dejes las credenciales por defecto activas en el servidor público.

---

## 📁 Estructura del Proyecto (Screaming Architecture)

```
RedTec/
├── .htaccess                   # Protección raíz y redirección a /public
├── .gitignore                  # Exclusión de credenciales y logs
├── README.md                   # Documentación principal
├── DEPLOY.md                   # Guía paso a paso para despliegue en one.com
├── config/
│   ├── database.example.php    # Plantilla de conexión a BD
│   └── site.php                # Configuración de entorno y constantes globales
├── database/
│   └── schema.sql              # Esquema SQL e inserción inicial
├── logs/                       # Logs de errores (fuera de web root)
├── public/                     # ÚNICO DIRECTORIO ACCESIBLE VÍA HTTP
│   ├── .htaccess               # Router, caché de 1 mes y forzado HTTPS/sin-www
│   ├── index.php               # Front Controller
│   ├── robots.txt              # Directivas para buscadores
│   ├── llms.txt                # Contexto para asistentes de IA (GEO)
│   ├── sitemap.xml             # Mapa de sitio generado por PHP
│   └── assets/                 # CSS, JS e imágenes
└── src/                        # Lógica de dominio PHP
    ├── Admin/                  # Panel de administración, Auth y CRUDs
    ├── Categorias/             # Repositorio de categorías
    ├── Checkout/               # Lógica de checkout
    ├── Home/                   # Vista principal de inicio
    ├── Productos/              # Repositorio y catálogo de productos
    ├── SEO/                    # Generadores de Schema.org y Sitemap
    ├── ServiciosCorporativos/  # Repositorio de planes PyME
    └── ServiciosTecnicos/      # Repositorio de servicios técnicos
```

---

## 🛠️ Requisitos e Instalación Local

- **PHP**: 8.0 o superior (extensiones `pdo`, `pdo_mysql`, `fileinfo`, `mbstring`).
- **MySQL / MariaDB**: 10.4 o superior.
- **Servidor Web**: Apache con `mod_rewrite` habilitado (XAMPP / WAMP).

### Pasos de Instalación Local:

1. Clonar el repositorio en tu servidor local (`c:\xampp\htdocs\RedTec`).
2. Crear la base de datos `c064ao1q8_redtec` e importar `database/schema.sql`.
3. Copiar `config/database.example.php` a `config/database.php` y ajustar tus credenciales de MySQL.
4. Abrir la aplicación en el navegador: `http://localhost/RedTec/public/`.
