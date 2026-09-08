-- ==============================================================================
-- RedTec Informática - Tablas de Pedidos y Detalles de Compra
-- Motor: MySQL 8.0+ / MariaDB (InnoDB)
-- Charset: utf8mb4
-- ==============================================================================

CREATE TABLE IF NOT EXISTS `orders` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `client_name` VARCHAR(150) NOT NULL,
  `client_email` VARCHAR(150) DEFAULT NULL,
  `client_phone` VARCHAR(50) NOT NULL,
  `client_address` VARCHAR(255) NOT NULL,
  `notes` TEXT DEFAULT NULL,
  `total_amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `status` ENUM('pendiente_pago', 'pagado', 'fallido', 'cancelado') NOT NULL DEFAULT 'pendiente_pago',
  `payment_method` VARCHAR(50) NOT NULL DEFAULT 'mercadopago',
  `mp_preference_id` VARCHAR(100) DEFAULT NULL,
  `mp_payment_id` VARCHAR(100) DEFAULT NULL,
  `mp_merchant_order_id` VARCHAR(100) DEFAULT NULL,
  `stock_reserved_until` DATETIME DEFAULT NULL,
  `stock_released` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_orders_status` (`status`),
  INDEX `idx_orders_payment_method` (`payment_method`),
  INDEX `idx_orders_created_at` (`created_at`),
  INDEX `idx_orders_mp_preference_id` (`mp_preference_id`),
  INDEX `idx_orders_mp_payment_id` (`mp_payment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `order_items` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `product_code` VARCHAR(50) NOT NULL,
  `product_name` VARCHAR(255) NOT NULL,
  `price` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `quantity` INT NOT NULL DEFAULT 1,
  `subtotal` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  CONSTRAINT `fk_order_items_orders`
    FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  INDEX `idx_order_items_order_id` (`order_id`),
  INDEX `idx_order_items_product_id` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
