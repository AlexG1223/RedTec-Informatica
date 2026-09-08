<?php

namespace RedTec\Checkout;

use RedTec\Shared\Database;
use PDO;
use Throwable;

/**
 * Repositorio para la gestión de Pedidos y Detalles de Compra
 */
class OrderRepository
{
    private static bool $schemaChecked = false;

    /**
     * Asegura la existencia de las tablas orders y order_items.
     */
    private function ensureSchema(PDO $pdo): void
    {
        if (self::$schemaChecked) {
            return;
        }
        self::$schemaChecked = true;

        try {
            $pdo->exec("
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
            ");

            $pdo->exec("
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
            ");
        } catch (Throwable $e) {
            error_log("OrderRepository ensureSchema Error: " . $e->getMessage());
        }
    }

    /**
     * Crea un nuevo pedido con items y precios tomados estrictamente de la base de datos.
     *
     * @param array $cliente ['name' => string, 'email' => string, 'phone' => string, 'address' => string, 'notes' => string]
     * @param array $itemsCarrito Array de ['id' => int, 'quantity' => int]
     * @param string $paymentMethod 'mercadopago' | 'whatsapp'
     * @return array ['order_id' => int, 'total' => float, 'items' => array]
     */
    public function crearPedido(array $cliente, array $itemsCarrito, string $paymentMethod = 'mercadopago'): array
    {
        $pdo = Database::connect();
        $this->ensureSchema($pdo);
        $this->liberarReservasExpiradas();

        if (empty($itemsCarrito)) {
            throw new \InvalidArgumentException("El carrito está vacío.");
        }

        $pdo->beginTransaction();

        try {
            $totalAmount = 0.00;
            $validatedItems = [];

            // 1. Obtener y validar cada producto desde la base de datos MySQL
            $stmtProd = $pdo->prepare("SELECT id, code, name, price, stock, active FROM products WHERE id = :id FOR UPDATE");

            foreach ($itemsCarrito as $item) {
                $productId = (int)($item['id'] ?? 0);
                $qty       = (int)($item['quantity'] ?? 1);

                if ($productId <= 0 || $qty <= 0) {
                    continue;
                }

                $stmtProd->execute([':id' => $productId]);
                $product = $stmtProd->fetch(PDO::FETCH_ASSOC);

                if (!$product || !$product['active']) {
                    throw new \RuntimeException("El producto ID {$productId} ya no está disponible.");
                }

                // Calcular stock efectivo considerando reservas activas
                $stmtRes = $pdo->prepare("
                    SELECT SUM(oi.quantity) as reserved_qty
                    FROM order_items oi
                    JOIN orders o ON oi.order_id = o.id
                    WHERE oi.product_id = :product_id
                      AND o.status = 'pendiente_pago'
                      AND o.stock_reserved_until > NOW()
                      AND o.stock_released = 0
                ");
                $stmtRes->execute([':product_id' => $productId]);
                $resData = $stmtRes->fetch(PDO::FETCH_ASSOC);
                $reservedQty = (int)($resData['reserved_qty'] ?? 0);

                $availableStock = (int)$product['stock'] - $reservedQty;
                if ($qty > $availableStock) {
                    throw new \RuntimeException("Stock insuficiente para '{$product['name']}'. Disponible: " . max(0, $availableStock));
                }

                $unitPrice = (float)$product['price'];
                $subtotal  = $unitPrice * $qty;
                $totalAmount += $subtotal;

                $validatedItems[] = [
                    'product_id'   => (int)$product['id'],
                    'product_code' => $product['code'],
                    'product_name' => $product['name'],
                    'price'        => $unitPrice,
                    'quantity'     => $qty,
                    'subtotal'     => $subtotal
                ];
            }

            if (empty($validatedItems)) {
                throw new \InvalidArgumentException("No hay productos válidos en el pedido.");
            }

            // Reserva válida por 45 minutos
            $reservationMinutes = 45;
            $stockReservedUntil = date('Y-m-d H:i:s', strtotime("+{$reservationMinutes} minutes"));

            // 2. Insertar cabecera del Pedido
            $sqlOrder = "INSERT INTO orders 
                         (client_name, client_email, client_phone, client_address, notes, total_amount, status, payment_method, stock_reserved_until, stock_released, created_at)
                         VALUES (:name, :email, :phone, :address, :notes, :total, 'pendiente_pago', :method, :reserved_until, 0, NOW())";
            
            $stmtOrder = $pdo->prepare($sqlOrder);
            $stmtOrder->execute([
                ':name'           => trim($cliente['name'] ?? ''),
                ':email'          => !empty($cliente['email']) ? trim($cliente['email']) : null,
                ':phone'          => trim($cliente['phone'] ?? ''),
                ':address'        => trim($cliente['address'] ?? ''),
                ':notes'          => !empty($cliente['notes']) ? trim($cliente['notes']) : null,
                ':total'          => $totalAmount,
                ':method'         => $paymentMethod,
                ':reserved_until' => $stockReservedUntil
            ]);

            $orderId = (int)$pdo->lastInsertId();

            // 3. Insertar detalle de ítems (order_items)
            $sqlItem = "INSERT INTO order_items (order_id, product_id, product_code, product_name, price, quantity, subtotal)
                        VALUES (:order_id, :product_id, :product_code, :product_name, :price, :quantity, :subtotal)";
            $stmtItem = $pdo->prepare($sqlItem);

            foreach ($validatedItems as &$vi) {
                $vi['order_id'] = $orderId;
                $stmtItem->execute([
                    ':order_id'     => $orderId,
                    ':product_id'   => $vi['product_id'],
                    ':product_code' => $vi['product_code'],
                    ':product_name' => $vi['product_name'],
                    ':price'        => $vi['price'],
                    ':quantity'     => $vi['quantity'],
                    ':subtotal'     => $vi['subtotal'],
                ]);
            }

            $pdo->commit();

            return [
                'order_id' => $orderId,
                'total'    => $totalAmount,
                'items'    => $validatedItems
            ];
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }

    /**
     * Vincula el ID de preferencia generado por Mercado Pago al pedido.
     */
    public function vincularPreferencia(int $orderId, string $preferenceId): bool
    {
        $pdo = Database::connect();
        $this->ensureSchema($pdo);
        $stmt = $pdo->prepare("UPDATE orders SET mp_preference_id = :pref_id WHERE id = :id");
        return $stmt->execute([':pref_id' => $preferenceId, ':id' => $orderId]);
    }

    /**
     * Marca un pedido como PAGADO, descuenta el stock real de los productos y libera la reserva.
     */
    public function marcarComoPagado(int $orderId, string $paymentId, ?string $merchantOrderId = null): bool
    {
        $pdo = Database::connect();
        $this->ensureSchema($pdo);

        $order = $this->obtenerPorId($orderId);
        if (!$order) {
            return false;
        }

        // Si ya estaba pagado, no volver a descontar stock (Idempotencia)
        if ($order['status'] === 'pagado') {
            return true;
        }

        $pdo->beginTransaction();

        try {
            // 1. Descontar el stock real de cada producto
            $stmtStock = $pdo->prepare("UPDATE products SET stock = GREATEST(0, stock - :qty) WHERE id = :id");
            foreach ($order['items'] as $item) {
                $stmtStock->execute([
                    ':qty' => (int)$item['quantity'],
                    ':id'  => (int)$item['product_id']
                ]);
            }

            // 2. Actualizar estado del pedido
            $stmtUpdate = $pdo->prepare("
                UPDATE orders 
                SET status = 'pagado',
                    mp_payment_id = :payment_id,
                    mp_merchant_order_id = :merchant_order_id,
                    stock_released = 0,
                    updated_at = NOW()
                WHERE id = :id
            ");

            $stmtUpdate->execute([
                ':payment_id'        => $paymentId,
                ':merchant_order_id' => $merchantOrderId,
                ':id'                 => $orderId
            ]);

            $pdo->commit();
            return true;
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            error_log("Error al marcar pedido {$orderId} como pagado: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Marca un pedido como FALLIDO y libera cualquier marca de reserva de stock.
     */
    public function marcarComoFallido(int $orderId): bool
    {
        $pdo = Database::connect();
        $this->ensureSchema($pdo);
        $stmt = $pdo->prepare("UPDATE orders SET status = 'fallido', stock_released = 1, updated_at = NOW() WHERE id = :id");
        return $stmt->execute([':id' => $orderId]);
    }

    /**
     * Marca un pedido como CANCELADO y libera la reserva.
     */
    public function marcarComoCancelado(int $orderId): bool
    {
        $pdo = Database::connect();
        $this->ensureSchema($pdo);
        $stmt = $pdo->prepare("UPDATE orders SET status = 'cancelado', stock_released = 1, updated_at = NOW() WHERE id = :id");
        return $stmt->execute([':id' => $orderId]);
    }

    /**
     * Cambia el estado de un pedido (para el panel de administración).
     */
    public function cambiarEstado(int $orderId, string $nuevoEstado): bool
    {
        if ($nuevoEstado === 'pagado') {
            return $this->marcarComoPagado($orderId, 'MANUAL-ADMIN-' . time());
        }

        $pdo = Database::connect();
        $this->ensureSchema($pdo);

        $released = ($nuevoEstado === 'cancelado' || $nuevoEstado === 'fallido') ? 1 : 0;
        $stmt = $pdo->prepare("UPDATE orders SET status = :status, stock_released = :released, updated_at = NOW() WHERE id = :id");
        return $stmt->execute([
            ':status'   => $nuevoEstado,
            ':released' => $released,
            ':id'       => $orderId
        ]);
    }

    /**
     * Libera reservas temporales de pedidos no pagados expirados.
     */
    public function liberarReservasExpiradas(): int
    {
        try {
            $pdo = Database::connect();
            $this->ensureSchema($pdo);
            $stmt = $pdo->prepare("
                UPDATE orders 
                SET stock_released = 1 
                WHERE status = 'pendiente_pago' 
                  AND stock_reserved_until IS NOT NULL 
                  AND stock_reserved_until < NOW() 
                  AND stock_released = 0
            ");
            $stmt->execute();
            return $stmt->rowCount();
        } catch (Throwable $e) {
            return 0;
        }
    }

    /**
     * Obtiene un pedido completo por su ID (con sus ítems).
     */
    public function obtenerPorId(int $id): ?array
    {
        try {
            $pdo = Database::connect();
            $this->ensureSchema($pdo);

            $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = :id LIMIT 1");
            $stmt->execute([':id' => $id]);
            $order = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$order) {
                return null;
            }

            $stmtItems = $pdo->prepare("SELECT * FROM order_items WHERE order_id = :order_id ORDER BY id ASC");
            $stmtItems->execute([':order_id' => $id]);
            $order['items'] = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

            return $order;
        } catch (Throwable $e) {
            return null;
        }
    }

    /**
     * Obtiene un pedido por el ID de preferencia de Mercado Pago.
     */
    public function obtenerPorPreferencia(string $preferenceId): ?array
    {
        try {
            $pdo = Database::connect();
            $this->ensureSchema($pdo);

            $stmt = $pdo->prepare("SELECT id FROM orders WHERE mp_preference_id = :pref_id LIMIT 1");
            $stmt->execute([':pref_id' => $preferenceId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                return $this->obtenerPorId((int)$row['id']);
            }
            return null;
        } catch (Throwable $e) {
            return null;
        }
    }

    /**
     * Lista todos los pedidos para el Panel de Administración.
     */
    public function listarTodos(array $filtros = []): array
    {
        try {
            $pdo = Database::connect();
            $this->ensureSchema($pdo);

            $sql = "SELECT * FROM orders WHERE 1=1";
            $params = [];

            if (!empty($filtros['estado'])) {
                $sql .= " AND status = :estado";
                $params[':estado'] = $filtros['estado'];
            }

            if (!empty($filtros['metodo'])) {
                $sql .= " AND payment_method = :metodo";
                $params[':metodo'] = $filtros['metodo'];
            }

            if (!empty($filtros['buscar'])) {
                $sql .= " AND (client_name LIKE :buscar OR client_phone LIKE :buscar OR client_email LIKE :buscar OR id = :id_buscar)";
                $params[':buscar']    = '%' . trim($filtros['buscar']) . '%';
                $params[':id_buscar'] = (int)ltrim($filtros['buscar'], '#0');
            }

            $sql .= " ORDER BY id DESC";

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Cargar items de forma optimizada
            foreach ($orders as &$order) {
                $stmtItems = $pdo->prepare("SELECT * FROM order_items WHERE order_id = :order_id");
                $stmtItems->execute([':order_id' => $order['id']]);
                $order['items'] = $stmtItems->fetchAll(PDO::FETCH_ASSOC);
            }

            return $orders;
        } catch (Throwable $e) {
            return [];
        }
    }

    /**
     * Elimina permanentemente un pedido y sus detalles.
     */
    public function eliminarPedido(int $id): bool
    {
        try {
            $pdo = Database::connect();
            $this->ensureSchema($pdo);

            $pdo->prepare("DELETE FROM order_items WHERE order_id = :id")->execute([':id' => $id]);
            $stmt = $pdo->prepare("DELETE FROM orders WHERE id = :id");
            return $stmt->execute([':id' => $id]);
        } catch (Throwable $e) {
            error_log("Error al eliminar pedido {$id}: " . $e->getMessage());
            return false;
        }
    }
}
