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
-- Productos de Ejemplo (Para pruebas iniciales)
-- ------------------------------------------------------------------------------
INSERT INTO `products` (`id`, `code`, `name`, `description`, `category_id`, `price`, `stock`, `active`) VALUES
(1, 'SW-TP-G108', 'Switch TP-Link 8 Puertos Gigabit', 'Switch no administrable de 8 puertos 10/100/1000 Mbps para redes de alta velocidad.', 1, 45.00, 15, 1),
(2, 'CAM-HIK-2MP', 'Cámara IP Hikvision 2MP Exterior', 'Cámara Bullet IP Full HD 1080p con visión nocturna IR 30m e intemperie IP67.', 2, 79.00, 8, 1),
(3, 'TECL-LOG-K120', 'Teclado USB Logitech K120', 'Teclado USB resistente a salpicaduras con teclas de perfil bajo.', 3, 18.00, 25, 1);

-- ------------------------------------------------------------------------------
-- Servicios Técnicos de Ejemplo
-- ------------------------------------------------------------------------------
INSERT INTO `services` (`name`, `description`, `image_url`, `active`) VALUES
('Instalación de Cámaras de Seguridad (CCTV)', 'Diseño e instalación de sistemas de videovigilancia IP y analógicas HD para empresas y residencias.', '/assets/img/services/cctv.jpg', 1),
('Servidores y Almacenamiento NAS', 'Configuración de servidores de archivos, dominios Active Directory y respaldos automatizados.', '/assets/img/services/servers.jpg', 1),
('Cableado Estructurado y WiFi', 'Instalación de redes LAN Cat6, certificación de puntos y cobertura WiFi corporativa Mesh.', '/assets/img/services/networking.jpg', 1);

-- ------------------------------------------------------------------------------
-- Planes Corporativos de Ejemplo
-- ------------------------------------------------------------------------------
INSERT INTO `service_packages` (`name`, `description`, `price`, `active`) VALUES
('Plan Pyme Básica', 'Soporte técnico mensual remoto e in-situ hasta 5 equipamientos. Respuesta prioritaria 24h.', 150.00, 1),
('Plan Empresa Pro', 'Soporte integral hasta 15 equipos y servidores. Mantenimiento preventivo mensual y monitoreo de red.', 350.00, 1),
('Plan Corporativo Custom', 'Solución de mantenimiento a medida con personal técnico dedicado y SLA de 4 horas.', NULL, 1);
