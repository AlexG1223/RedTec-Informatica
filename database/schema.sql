-- ==============================================================================
-- RedTec Informática - Esquema de Base de Datos Base
-- Motor: MySQL 8.0+ / MariaDB 10.4+ (InnoDB)
-- Charset: utf8mb4 (Collation: utf8mb4_unicode_ci)
-- ==============================================================================

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `gestioo_sync_logs`;
DROP TABLE IF EXISTS `service_packages`;
DROP TABLE IF EXISTS `services`;
DROP TABLE IF EXISTS `product_images`;
DROP TABLE IF EXISTS `products`;
DROP TABLE IF EXISTS `categories`;
DROP TABLE IF EXISTS `admins`;

SET FOREIGN_KEY_CHECKS = 1;

-- ------------------------------------------------------------------------------
-- Tabla: admins (Usuarios del Panel de Administración)
-- ------------------------------------------------------------------------------
CREATE TABLE `admins` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(150) NOT NULL,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_admins_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- Tabla: categories (Categorías de Productos)
-- ------------------------------------------------------------------------------
CREATE TABLE `categories` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `image_url` VARCHAR(255) DEFAULT NULL,
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  INDEX `idx_categories_name` (`name`),
  INDEX `idx_categories_active` (`active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ------------------------------------------------------------------------------
-- Tabla: products (Catálogo de Productos)
-- ------------------------------------------------------------------------------
CREATE TABLE `products` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `code` VARCHAR(50) NOT NULL UNIQUE,
  `name` VARCHAR(255) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `category_id` INT DEFAULT NULL,
  `price` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `stock` INT NOT NULL DEFAULT 0,
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT `fk_products_categories` 
    FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) 
    ON DELETE SET NULL ON UPDATE CASCADE,
  INDEX `idx_products_category_id` (`category_id`),
  INDEX `idx_products_active` (`active`),
  INDEX `idx_products_code` (`code`),
  INDEX `idx_products_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- Tabla: product_images (Imágenes Adicionales por Producto)
-- ------------------------------------------------------------------------------
CREATE TABLE `product_images` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `product_id` INT NOT NULL,
  `image_url` VARCHAR(255) NOT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  CONSTRAINT `fk_product_images_products` 
    FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) 
    ON DELETE CASCADE ON UPDATE CASCADE,
  INDEX `idx_product_images_product_id` (`product_id`),
  INDEX `idx_product_images_sort_order` (`product_id`, `sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- Tabla: services (Servicios Técnicos Institucionales)
-- ------------------------------------------------------------------------------
CREATE TABLE `services` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(150) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `image_url` VARCHAR(255) DEFAULT NULL,
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  INDEX `idx_services_active` (`active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- Tabla: service_packages (Planes de Soporte Mensual para Empresas)
-- ------------------------------------------------------------------------------
CREATE TABLE `service_packages` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `price` DECIMAL(10,2) DEFAULT NULL,
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  INDEX `idx_service_packages_active` (`active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- Tabla: gestioo_sync_logs (Historial de Sincronización Gestioo)
-- ------------------------------------------------------------------------------
CREATE TABLE `gestioo_sync_logs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `total_processed` INT NOT NULL DEFAULT 0,
  `total_updated` INT NOT NULL DEFAULT 0,
  `total_failed` INT NOT NULL DEFAULT 0,
  `synced_by` INT DEFAULT NULL,
  `synced_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_gestioo_sync_logs_admins` 
    FOREIGN KEY (`synced_by`) REFERENCES `admins` (`id`) 
    ON DELETE SET NULL ON UPDATE CASCADE,
  INDEX `idx_gestioo_sync_logs_synced_at` (`synced_at`),
  INDEX `idx_gestioo_sync_logs_synced_by` (`synced_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==============================================================================
-- DATOS DE PRUEBA INICIALES (SEMILLA / SEED DATA)
-- ==============================================================================

-- ------------------------------------------------------------------------------
-- Usuario Administrador de Prueba
-- Credenciales Desarrollo Local:
-- Email: admin@redtecinformatica.com
-- Contraseña en texto plano: Admin2026!
-- ------------------------------------------------------------------------------
INSERT INTO `admins` (`name`, `email`, `password_hash`, `created_at`) VALUES
('Administrador RedTec', 'admin@redtecinformatica.com', '$2y$10$qOTUL3cKfctmUi2ow8si0OmZBpVn5vbLET7Kpac38CgNBowgGrJdu', NOW());

-- ------------------------------------------------------------------------------
-- Categorías de Ejemplo (RedTec Atlántida)
-- ------------------------------------------------------------------------------
INSERT INTO `categories` (`id`, `name`, `image_url`, `active`) VALUES
(1, 'Equipos y Notebooks', '/assets/img/categories/notebooks.jpg', 1),
(2, 'Redes y Conectividad', '/assets/img/categories/redes.jpg', 1),
(3, 'Seguridad y Cámaras', '/assets/img/categories/camaras.jpg', 1),
(4, 'Accesorios', '/assets/img/categories/accesorios.jpg', 1);


-- ------------------------------------------------------------------------------
-- Productos de Ejemplo (RedTec Atlántida - 12 Productos)
-- ------------------------------------------------------------------------------
INSERT INTO `products` (`id`, `code`, `name`, `description`, `category_id`, `price`, `stock`, `active`) VALUES
(1, 'NOTE-LEN-V15', 'Notebook Lenovo V15 G3 IAP 15.6" Intel Core i5 8GB 256GB SSD', 'Computadora portátil ideal para trabajo corporativo y estudio. Procesador Intel Core i5 de 12a Gen, pantalla Full HD 15.6" antirreflejo y disco sólido NVMe de alta velocidad.', 1, 690.00, 5, 1),
(2, 'NOTE-HP-250', 'Notebook HP 250 G8 Celeron N4020 4GB 128GB SSD', 'Notebook económica y liviana para tareas de oficina y navegación. Pantalla de 15.6 pulgadas y teclado numérico incorporado.', 1, 380.00, 0, 1),
(3, 'MINI-ASUS-D5', 'Mini PC Asus ExpertCenter D500 Core i3 8GB 512GB SSD', 'Equipo compacto de alto rendimiento para escritorios de empresas. Diseño ultra reducido con gran conectividad USB y HDMI.', 1, 520.00, 3, 1),
(4, 'ROUT-TP-C6', 'Router TP-Link Archer C6 Dual Band AC1200 Gigabit', 'Router inalámbrico de doble banda (2.4 GHz y 5 GHz) con 4 antenas externas de alta ganancia. Tecnología MU-MIMO y puertos Gigabit.', 2, 49.00, 12, 1),
(5, 'SW-TP-G108', 'Switch TP-Link 8 Puertos Gigabit 10/100/1000 Mbps', 'Switch metálico no administrable de 8 puertos RJ45 Gigabit. Tecnología Plug and Play sin necesidad de configuración.', 2, 35.00, 18, 1),
(6, 'AP-UBI-U6L', 'Access Point Ubiquiti UniFi U6 Lite Wi-Fi 6', 'Punto de acceso Wi-Fi 6 compacto de alto rendimiento con cobertura empresarial. Gestión centralizada mediante UniFi Network Controller.', 2, 145.00, 4, 1),
(7, 'CAM-TAPO-C200', 'Cámaras Wi-Fi TP-Link Tapo C200 1080p Visión Nocturna', 'Cámara de seguridad hogar/oficina con movimiento 360 grados, audio bidireccional, visión nocturna infrarroja y alertas al celular.', 3, 39.00, 10, 1),
(8, 'CAM-HIK-2MP', 'Cámara IP Hikvision 2MP Exterior Full HD IR 30m', 'Cámara Bullet IP apta para exterior con intemperie IP67, resolución 1080p y visión nocturna de hasta 30 metros.', 3, 79.00, 8, 1),
(9, 'KIT-DVR-HIK4', 'Kit DVR Hikvision 4 Canales + 2 Cámaras Full HD 1080p', 'Kit completo de videovigilancia que incluye DVR de 4 canales, 2 cámaras exterior 1080p, fuentes de alimentación y cables de instalación.', 3, 189.00, 2, 1),
(10, 'MOUSE-LOG-M170', 'Mouse Inalámbrico Logitech M170 Negro', 'Mouse óptico inalámbrico de 2.4 GHz con receptor USB Nano. Batería de larga duración de hasta 12 meses.', 4, 15.00, 25, 1),
(11, 'TECL-LOG-K120', 'Teclado USB Logitech K120 Español', 'Teclado USB de perfil bajo con distribución en español latinoamericano. Diseño resistente a salpicaduras.', 4, 18.00, 0, 1),
(12, 'PAD-RED-ARCH', 'Mouse Pad Gamer Redragon Archelon L', 'Superficie de tela de alta densidad resistente al agua con bordes cosidos y base de goma antideslizante (400x300mm).', 4, 14.00, 15, 1);

-- ------------------------------------------------------------------------------
-- Imágenes Adicionales de Productos (Galería)
-- ------------------------------------------------------------------------------
INSERT INTO `product_images` (`product_id`, `image_url`, `sort_order`) VALUES
(1, '/assets/img/redtec.jpeg', 1),
(1, '/assets/img/Logotipo PNG.png', 2),
(4, '/assets/img/redtec.jpeg', 1),
(7, '/assets/img/redtec.jpeg', 1),
(8, '/assets/img/redtec.jpeg', 1),
(10, '/assets/img/redtec.jpeg', 1);


-- ------------------------------------------------------------------------------
-- Servicios Técnicos de Ejemplo
-- ------------------------------------------------------------------------------
INSERT INTO `services` (`id`, `name`, `description`, `image_url`, `active`) VALUES
(1, 'Instalación de Cámaras de Seguridad (CCTV)', 'Diseño e instalación de sistemas de videovigilancia IP y analógicas HD para empresas y residencias con monitoreo remoto en el celular.', '/assets/img/redtec.jpeg', 1),
(2, 'Armado y Configuración de Servidores', 'Implementación de servidores de archivos, Active Directory, virtualización y sistemas de respaldo automatizados en unidades NAS.', NULL, 1),
(3, 'Redes y Conectividad', 'Cableado estructurado Cat6, certificación de puntos de red, armado de racks y despliegue de redes Wi-Fi empresariales Mesh.', '/assets/img/redtec.jpeg', 1),
(4, 'Mantenimiento y Soporte Técnico In-Situ', 'Reparación de hardware, mantenimiento preventivo de equipamiento, limpieza técnica y asistencia a domicilio para empresas y particulares.', NULL, 1),
(5, 'Seguridad Informática y Resguardos', 'Implementación de firewalls de red, antivirus corporativo administrado y planes de copia de seguridad automatizada contra ransomware.', '/assets/img/redtec.jpeg', 1);

-- ------------------------------------------------------------------------------
-- Planes Corporativos de Ejemplo (precios en NULL para cotización personalizada)
-- ------------------------------------------------------------------------------
INSERT INTO `service_packages` (`id`, `name`, `description`, `price`, `active`) VALUES
(1, 'Esencial', 'Soporte técnico reactivo remoto y presencial con tiempo de respuesta estándar para pequeñas oficinas y negocios (hasta 5 equipos).', NULL, 1),
(2, 'Empresarial', 'Soporte prioritario, mantenimiento preventivo mensual, monitoreo de infraestructura y asistencia in-situ para PyMEs (hasta 15 equipos).', NULL, 1),
(3, 'Premium', 'Soporte prioritario 24/7, servidor y red monitoreados en tiempo real, tiempo de respuesta SLA garantizado y técnico dedicado.', NULL, 1);

