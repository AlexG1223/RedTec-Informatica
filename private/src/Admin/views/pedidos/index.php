<?php
/**
 * RedTec Informática - Vista del Panel de Administración: Gestión de Pedidos
 * 
 * @var array $pedidos Lista de pedidos registrados
 * @var string $csrfToken Token CSRF
 */

$content = function() use ($pedidos, $csrfToken) {
?>
  <!-- ENCABEZADO DE PÁGINA -->
  <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.75rem; flex-wrap: wrap; gap: 1rem;">
    <h1 style="font-size: 1.6rem; font-weight: 800; color: var(--color-dark); margin: 0; display: flex; align-items: center; gap: 0.5rem;">
      <span style="font-size: 1.8rem;">📦</span> Gestión de Pedidos
    </h1>
  </div>

  <!-- TABLA DE PEDIDOS (DISEÑO REPLICADO DE ADMINISTRACIÓN) -->
  <div style="background: #FFFFFF; border-radius: var(--radius-lg); border: 1px solid var(--color-border-light); box-shadow: var(--shadow-sm); overflow: hidden;">
    
    <div style="overflow-x: auto;">
      <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.9rem;">
        <thead>
          <tr style="background: #F9FAFB; border-bottom: 1px solid #E5E7EB; color: #4B5563; font-family: var(--font-heading); font-weight: 700;">
            <th style="padding: 1rem 1.25rem; width: 90px;">ID</th>
            <th style="padding: 1rem 1.25rem;">Cliente</th>
            <th style="padding: 1rem 1.25rem;">Total</th>
            <th style="padding: 1rem 1.25rem;">Método</th>
            <th style="padding: 1rem 1.25rem;">Estado</th>
            <th style="padding: 1rem 1.25rem;">Fecha</th>
            <th style="padding: 1rem 1.25rem; text-align: center;">Acción</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($pedidos)): ?>
            <tr>
              <td colspan="7" style="padding: 3rem; text-align: center; color: var(--color-text-muted);">
                <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">📥</div>
                <strong>No se encontraron pedidos registrados.</strong>
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($pedidos as $p): 
              $orderCode = sprintf('#%05d', $p['id']);
              $totalFormatted = '$U ' . number_format((float)$p['total_amount'], 0, ',', '.');
              
              // Formateo de fecha
              $timestamp = strtotime($p['created_at']);
              $fechaFormateada = date('j/n/Y, g:i:s a', $timestamp);
              $fechaFormateada = str_replace(['am', 'pm'], ['a. m.', 'p. m.'], $fechaFormateada);

              // Insignia de estado
              $status = strtolower($p['status']);
              $statusLabel = 'PENDIENTE';
              $statusBg = '#FEF3C7';
              $statusColor = '#92400E';

              if ($status === 'pagado') {
                  $statusLabel = 'PAGADO';
                  $statusBg = '#D1FAE5';
                  $statusColor = '#065F46';
              } elseif ($status === 'cancelado') {
                  $statusLabel = 'CANCELADO';
                  $statusBg = '#E5E7EB';
                  $statusColor = '#374151';
              } elseif ($status === 'fallido') {
                  $statusLabel = 'FALLIDO';
                  $statusBg = '#FEE2E2';
                  $statusColor = '#991B1B';
              }

              // Método de pago
              $method = strtolower($p['payment_method']);
              $isMP = ($method === 'mercadopago');
            ?>
              <tr style="border-bottom: 1px solid #F3F4F6; transition: background 0.15s;" onmouseover="this.style.background='#F9FAFB'" onmouseout="this.style.background='transparent'">
                
                <!-- ID -->
                <td style="padding: 1.1rem 1.25rem; font-weight: 800; color: #D97706; font-family: var(--font-heading);">
                  <?= $orderCode ?>
                </td>

                <!-- CLIENTE -->
                <td style="padding: 1.1rem 1.25rem;">
                  <div style="font-weight: 700; color: var(--color-dark); font-size: 0.95rem;">
                    <?= htmlspecialchars($p['client_name']) ?>
                  </div>
                  <div style="color: #4B5563; font-size: 0.85rem; margin-top: 2px;">
                    <?= htmlspecialchars($p['client_phone']) ?>
                  </div>
                  <?php if (!empty($p['client_email'])): ?>
                    <div style="color: #6B7280; font-size: 0.8rem; margin-top: 1px;">
                      <?= htmlspecialchars($p['client_email']) ?>
                    </div>
                  <?php endif; ?>
                </td>

                <!-- TOTAL -->
                <td style="padding: 1.1rem 1.25rem; font-weight: 800; color: var(--color-dark); font-size: 1rem; font-family: var(--font-heading);">
                  <?= $totalFormatted ?>
                </td>

                <!-- MÉTODO DE PAGO -->
                <td style="padding: 1.1rem 1.25rem;">
                  <?php if ($isMP): ?>
                    <div style="display: flex; align-items: center; gap: 0.4rem; color: #1E293B; font-weight: 600; font-size: 0.85rem;">
                      <span style="color: #009EE3;">💳</span> Mercado Pago
                    </div>
                  <?php else: ?>
                    <div style="display: flex; align-items: center; gap: 0.4rem; color: #1E293B; font-weight: 600; font-size: 0.85rem;">
                      <span style="color: #25D366;">💬</span> WhatsApp
                    </div>
                  <?php endif; ?>
                </td>

                <!-- ESTADO -->
                <td style="padding: 1.1rem 1.25rem;">
                  <span style="background-color: <?= $statusBg ?>; color: <?= $statusColor ?>; padding: 0.3rem 0.75rem; border-radius: 20px; font-weight: 800; font-size: 0.75rem; letter-spacing: 0.5px; display: inline-block;">
                    <?= $statusLabel ?>
                  </span>
                </td>

                <!-- FECHA -->
                <td style="padding: 1.1rem 1.25rem; color: #4B5563; font-size: 0.85rem; white-space: nowrap;">
                  <?= $fechaFormateada ?>
                </td>

                <!-- ACCIONES -->
                <td style="padding: 1.1rem 1.25rem; text-align: center;">
                  <div style="display: flex; align-items: center; justify-content: center; gap: 0.4rem; flex-wrap: wrap;">
                    
                    <!-- Botón Ver Detalle -->
                    <button type="button" 
                            onclick="openOrderDetail(<?= htmlspecialchars(json_encode($p, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP)) ?>)"
                            style="background: #111827; color: #FFFFFF; border: none; border-radius: 6px; padding: 0.35rem 0.65rem; font-size: 0.8rem; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 0.25rem; transition: opacity 0.2s;"
                            onmouseover="this.style.opacity='0.85'" onmouseout="this.style.opacity='1'">
                      👁 Detalle
                    </button>

                    <!-- Botón Finalizar / Restablecer -->
                    <?php if ($status !== 'pagado'): ?>
                      <form action="<?= url('/admin/pedidos/' . $p['id'] . '/estado') ?>" method="POST" style="margin:0;">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                        <input type="hidden" name="order_id" value="<?= $p['id'] ?>">
                        <input type="hidden" name="status" value="pagado">
                        <button type="submit" 
                                style="background: #FFFFFF; color: #374151; border: 1px solid #D1D5DB; border-radius: 6px; padding: 0.35rem 0.65rem; font-size: 0.8rem; font-weight: 600; cursor: pointer; transition: background 0.2s;"
                                onmouseover="this.style.background='#F3F4F6'" onmouseout="this.style.background='#FFFFFF'">
                          Finalizar
                        </button>
                      </form>
                    <?php else: ?>
                      <form action="<?= url('/admin/pedidos/' . $p['id'] . '/estado') ?>" method="POST" style="margin:0;">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                        <input type="hidden" name="order_id" value="<?= $p['id'] ?>">
                        <input type="hidden" name="status" value="pendiente_pago">
                        <button type="submit" 
                                style="background: #FFFFFF; color: #374151; border: 1px solid #D1D5DB; border-radius: 6px; padding: 0.35rem 0.65rem; font-size: 0.8rem; font-weight: 600; cursor: pointer; transition: background 0.2s;"
                                onmouseover="this.style.background='#F3F4F6'" onmouseout="this.style.background='#FFFFFF'">
                          Restablecer
                        </button>
                      </form>
                    <?php endif; ?>

                    <!-- Botón Cancelar (si no está cancelado ni fallido) -->
                    <?php if ($status !== 'cancelado' && $status !== 'fallido'): ?>
                      <form action="<?= url('/admin/pedidos/' . $p['id'] . '/estado') ?>" method="POST" style="margin:0;" onsubmit="return confirm('¿Desea cancelar este pedido? Se liberará cualquier reserva de stock.');">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                        <input type="hidden" name="order_id" value="<?= $p['id'] ?>">
                        <input type="hidden" name="status" value="cancelado">
                        <button type="submit" 
                                style="background: #FFFFFF; color: #374151; border: 1px solid #D1D5DB; border-radius: 6px; padding: 0.35rem 0.65rem; font-size: 0.8rem; font-weight: 600; cursor: pointer; transition: background 0.2s;"
                                onmouseover="this.style.background='#F3F4F6'" onmouseout="this.style.background='#FFFFFF'">
                          Cancelar
                        </button>
                      </form>
                    <?php endif; ?>

                    <!-- Botón Eliminar -->
                    <form action="<?= url('/admin/pedidos/' . $p['id'] . '/eliminar') ?>" method="POST" style="margin:0;" onsubmit="return confirm('¿Está seguro de eliminar permanentemente el pedido #<?= sprintf('%05d', $p['id']) ?>?');">
                      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                      <input type="hidden" name="order_id" value="<?= $p['id'] ?>">
                      <button type="submit" 
                              style="background: #FFFFFF; color: #DC2626; border: 1px solid #FCA5A5; border-radius: 6px; padding: 0.35rem 0.65rem; font-size: 0.8rem; font-weight: 600; cursor: pointer; transition: background 0.2s;"
                              onmouseover="this.style.background='#FEE2E2'" onmouseout="this.style.background='#FFFFFF'">
                        Eliminar
                      </button>
                    </form>

                  </div>
                </td>

              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

  </div>

  <!-- MODAL DE DETALLE DE PEDIDO -->
  <div id="orderDetailModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 9999; align-items: center; justify-content: center; padding: 1rem;">
    <div style="background: #FFFFFF; border-radius: var(--radius-lg); max-width: 650px; width: 100%; max-height: 90vh; overflow-y: auto; box-shadow: var(--shadow-lg); padding: 2rem; position: relative;">
      
      <button type="button" onclick="closeOrderDetail()" style="position: absolute; top: 1.25rem; right: 1.25rem; background: transparent; border: none; font-size: 1.5rem; cursor: pointer; color: #6B7280;">✕</button>

      <h2 id="modalOrderCode" style="font-size: 1.35rem; margin-top: 0; margin-bottom: 1rem; color: var(--color-dark); border-bottom: 2px solid var(--color-bg); padding-bottom: 0.75rem;">
        Detalle del Pedido
      </h2>

      <div id="modalOrderBody">
        <!-- Se llena por JavaScript -->
      </div>

      <div style="margin-top: 1.5rem; text-align: right;">
        <button type="button" onclick="closeOrderDetail()" class="btn btn-outline-dark btn-sm">Cerrar</button>
      </div>

    </div>
  </div>

  <script>
  function openOrderDetail(order) {
    const modal = document.getElementById('orderDetailModal');
    const codeEl = document.getElementById('modalOrderCode');
    const bodyEl = document.getElementById('modalOrderBody');

    const formattedId = '#' + String(order.id).padStart(5, '0');
    codeEl.textContent = 'Detalle del Pedido ' + formattedId;

    let itemsHtml = '';
    if (order.items && order.items.length > 0) {
      order.items.forEach(it => {
        itemsHtml += `
          <tr>
            <td style="padding: 8px; border-bottom: 1px solid #E5E7EB;">
              <strong>${it.product_name}</strong><br>
              <small style="color: #6B7280;">Código: ${it.product_code}</small>
            </td>
            <td style="padding: 8px; border-bottom: 1px solid #E5E7EB; text-align: center;">${it.quantity}</td>
            <td style="padding: 8px; border-bottom: 1px solid #E5E7EB; text-align: right;">$U ${parseFloat(it.price).toFixed(2)}</td>
            <td style="padding: 8px; border-bottom: 1px solid #E5E7EB; text-align: right; font-weight: 700;">$U ${parseFloat(it.subtotal).toFixed(2)}</td>
          </tr>
        `;
      });
    }

    bodyEl.innerHTML = `
      <div style="background: #F9FAFB; padding: 1rem; border-radius: 8px; margin-bottom: 1.25rem; font-size: 0.9rem;">
        <p style="margin: 4px 0;"><strong>Cliente:</strong> ${order.client_name}</p>
        <p style="margin: 4px 0;"><strong>Teléfono:</strong> <a href="tel:${order.client_phone}">${order.client_phone}</a></p>
        <p style="margin: 4px 0;"><strong>Email:</strong> ${order.client_email || 'No especificado'}</p>
        <p style="margin: 4px 0;"><strong>Dirección:</strong> ${order.client_address}</p>
        <p style="margin: 4px 0;"><strong>Notas:</strong> ${order.notes || 'Sin notas adicionles'}</p>
        <p style="margin: 4px 0;"><strong>Método de Pago:</strong> ${order.payment_method === 'mercadopago' ? 'Mercado Pago' : 'WhatsApp'}</p>
        <p style="margin: 4px 0;"><strong>Estado:</strong> <strong style="text-transform: uppercase;">${order.status}</strong></p>
        ${order.mp_payment_id ? `<p style="margin: 4px 0;"><strong>ID de Pago Mercado Pago:</strong> ${order.mp_payment_id}</p>` : ''}
      </div>

      <h4 style="margin-bottom: 0.5rem; font-size: 1rem; color: var(--color-dark);">Productos Comprados</h4>
      <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem; margin-bottom: 1rem;">
        <thead>
          <tr style="background: #F3F4F6; text-align: left;">
            <th style="padding: 8px;">Producto</th>
            <th style="padding: 8px; text-align: center;">Cant</th>
            <th style="padding: 8px; text-align: right;">P. Unit</th>
            <th style="padding: 8px; text-align: right;">Subtotal</th>
          </tr>
        </thead>
        <tbody>
          ${itemsHtml}
        </tbody>
      </table>

      <div style="text-align: right; font-size: 1.1rem; font-weight: 800; color: var(--color-primary);">
        Total: $U ${parseFloat(order.total_amount).toFixed(2)}
      </div>
    `;

    modal.style.display = 'flex';
  }

  function closeOrderDetail() {
    document.getElementById('orderDetailModal').style.display = 'none';
  }
  </script>
<?php
};

require REDTEC_SHARED_DIR . '/Layout/admin-layout.php';
