# Guía de Despliegue en Producción — Hosting one.com

Esta guía detalla el procedimiento paso a paso para publicar el sitio web de **RedTec Informática** en el servicio de hosting compartido de **one.com**.

---

## 📋 Requisitos Previos en one.com

- Dominio activo (ej: `redtecinformatica.com`).
- Plan de hosting con soporte PHP 8.0+ y Base de Datos MySQL/MariaDB.
- Acceso al Panel de Control de one.com y cliente FTP/SFTP (FileZilla o Cyberduck).

---

## 🔒 PASO 1: Creación de la Base de Datos MySQL

1. Ingresá al **Panel de Control de one.com**.
2. Dirigite a **Archivos y Base de datos** &rarr; **Base de datos MySQL**.
3. Creá una nueva base de datos (anotá el nombre de la BD, usuario MySQL, contraseña y el Host, que en one.com suele ser `redtecinformatica.com.mysql` o `localhost`).

---

## 🗄️ PASO 2: Importación del Esquema SQL (`schema.sql`)

1. Desde el panel de one.com, abrí **phpMyAdmin**.
2. Seleccioná la base de datos recién creada.
3. Hacé clic en la pestaña **Importar**.
4. Seleccioná el archivo `database/schema.sql` de tu proyecto y ejecutá la importación.
5. Verificá que se hayan creado las 7 tablas de la aplicación (`admins`, `categories`, `products`, `product_images`, `services`, `service_packages`, `import_logs`).

---

## 📁 PASO 3: Subida de Archivos por FTP/SFTP

1. Conectate a tu servidor a través de tu cliente FTP/SFTP.
2. Subí **todos los archivos y carpetas del repositorio** al directorio raíz de tu alojamiento (usualmente `/httpdocs` o `/www` o el directorio principal del dominio).

> ⚠️ **IMPORTANTE**: Asegurate de subir el archivo `.htaccess` de la raíz y el archivo `public/.htaccess` (recordá habilitar la opción de "Mostrar archivos ocultos" en tu cliente FTP).

---

## ⚙️ PASO 4: Configuración de Credenciales de Producción (`config/database.php`)

NUNCA subas credenciales reales al repositorio Git. En el servidor de producción:

1. Ingresá a la carpeta `/config/` en el servidor.
2. Duplicá o renombrá `database.example.php` a `database.php`.
3. Editá `config/database.php` e ingresá las credenciales reales generadas en el PASO 1:

```php
<?php

return [
    'host'     => 'TU_HOST_MYSQL.one.com', // ej: redtecinformatica.com.mysql
    'db_name'  => 'TU_NOMBRE_BASE_DATOS',
    'username' => 'TU_USUARIO_MYSQL',
    'password' => 'TU_CONTRASEÑA_SEGURA',
    'charset'  => 'utf8mb4',
    'port'     => 3306,
];
```

4. Editá `config/site.php` y verificá que el número de WhatsApp de producción esté configurado:
```php
define('REDTEC_WHATSAPP_NUMBER', '59891633699');

```

---

## 🛡️ PASO 5: Protección de Archivos Internos y Document Root

Para garantizar que el código fuente (`/src`), las configuraciones (`/config`), los scripts de base de datos (`/database`) y los logs (`/logs`) NO sean accesibles públicamente vía HTTP:

### Opción A (Recomendada en one.com):
Si el panel de one.com permite cambiar el **Document Root** del dominio, apuntalo directamente a la carpeta `/public`. De este modo, solo los archivos dentro de `/public` serán servibles por HTTP, manteniendo todo el código PHP fuera del alcance del navegador.

### Opción B (Con root `.htaccess`):
Si one.com no permite modificar el Document Root, el proyecto ya incluye un archivo `.htaccess` en la raíz que redirige automáticamente todas las peticiones a la carpeta `/public` y bloquea el acceso directo a cualquier archivo oculto o directorio interno.

---

## 🔑 PASO 6: Cambio de Credenciales de Administrador (Obligatorio)

1. Ingresá al panel de administración en `https://redtecinformatica.com/admin/login`.
2. Iniciá sesión con las credenciales temporales (`admin@redtecinformatica.com` / `Admin2026!`).
3. Creá un usuario administrador propio con una contraseña fuerte o actualizá la contraseña directamente en la BD usando `password_hash()` en PHP.

---

## 🔍 PASO 7: Verificación Final en Producción

1. Navegá por las secciones públicas: Inicio, Tienda, Servicios y Checkout.
2. Verificá que la URL forzada sea `https://redtecinformatica.com/` (sin `www` y con `https://`).
3. Comprobá el funcionamiento del carrito y el envío del pedido formateado a WhatsApp.
4. Probá ingresar a `https://redtecinformatica.com/sitemap.xml` para validar la generación del mapa de sitio.
5. Ingresá al panel `/admin` y probá realizar un ajuste rápido de stock y una importación CSV de prueba.
