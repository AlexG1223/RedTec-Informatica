<?php

namespace RedTec\Checkout;

use Throwable;

/**
 * Servicio de Envío de Notificaciones por Correo Electrónico tras Compras Aprobadas
 */
class MailService
{
    /**
     * Envía la confirmación de compra al cliente y al administrador del sitio.
     *
     * @param array $pedido Datos del pedido completo obtenido de OrderRepository
     * @return bool
     */
    public function enviarConfirmacionCompra(array $pedido): bool
    {
        try {
            $orderId     = sprintf('#%05d', $pedido['id']);
            $clientName  = htmlspecialchars($pedido['client_name']);
            $clientEmail = filter_var($pedido['client_email'], FILTER_VALIDATE_EMAIL);
            $clientPhone = htmlspecialchars($pedido['client_phone']);
            $address     = htmlspecialchars($pedido['client_address']);
            $total       = number_format((float)$pedido['total_amount'], 2, ',', '.');
            $fecha       = date('d/m/Y H:i', strtotime($pedido['created_at']));

            $itemsHtml = '';
            foreach ($pedido['items'] as $item) {
                $pName    = htmlspecialchars($item['product_name']);
                $qty      = (int)$item['quantity'];
                $price    = number_format((float)$item['price'], 2, ',', '.');
                $subtotal = number_format((float)$item['subtotal'], 2, ',', '.');

                $itemsHtml .= "
                <tr>
                  <td style='padding: 8px 12px; border-bottom: 1px solid #EEEEEE;'>{$pName}</td>
                  <td style='padding: 8px 12px; border-bottom: 1px solid #EEEEEE; text-align: center;'>{$qty}</td>
                  <td style='padding: 8px 12px; border-bottom: 1px solid #EEEEEE; text-align: right;'>\$U {$price}</td>
                  <td style='padding: 8px 12px; border-bottom: 1px solid #EEEEEE; text-align: right;'><strong>\$U {$subtotal}</strong></td>
                </tr>";
            }

            $bodyHtml = "
            <!DOCTYPE html>
            <html>
            <head>
              <meta charset='UTF-8'>
              <title>Confirmación de Compra — RedTec Informática</title>
            </head>
            <body style='font-family: Arial, sans-serif; background-color: #F4F4F5; margin: 0; padding: 20px;'>
              <div style='max-width: 600px; margin: 0 auto; background: #FFFFFF; border-radius: 8px; overflow: hidden; border: 1px solid #E4E4E7;'>
                <div style='background-color: #0F172A; color: #FFFFFF; padding: 20px; text-align: center; border-bottom: 4px solid #E11D48;'>
                  <h2 style='margin: 0; font-size: 24px;'>RedTec Informática</h2>
                  <p style='margin: 5px 0 0 0; font-size: 14px; color: #94A3B8;'>¡Gracias por tu compra!</p>
                </div>
                
                <div style='padding: 24px;'>
                  <h3 style='color: #0F172A; margin-top: 0;'>Pedido {$orderId} Confirmado</h3>
                  <p style='color: #475569; font-size: 14px; line-height: 1.5;'>
                    Hola <strong>{$clientName}</strong>, confirmamos que hemos recibido tu pago a través de Mercado Pago y tu pedido está siendo procesado por nuestro equipo.
                  </p>
                  
                  <div style='background: #F8FAFC; padding: 15px; border-radius: 6px; margin-bottom: 20px; font-size: 13px;'>
                    <p style='margin: 3px 0;'><strong>Cliente:</strong> {$clientName}</p>
                    <p style='margin: 3px 0;'><strong>Teléfono:</strong> {$clientPhone}</p>
                    <p style='margin: 3px 0;'><strong>Dirección de Entrega:</strong> {$address}</p>
                    <p style='margin: 3px 0;'><strong>Fecha:</strong> {$fecha}</p>
                    <p style='margin: 3px 0;'><strong>Método de Pago:</strong> Mercado Pago (Aprobado)</p>
                  </div>

                  <table style='width: 100%; border-collapse: collapse; font-size: 13px; margin-bottom: 20px;'>
                    <thead>
                      <tr style='background: #F1F5F9; color: #334155; text-align: left;'>
                        <th style='padding: 8px 12px;'>Producto</th>
                        <th style='padding: 8px 12px; text-align: center;'>Cant.</th>
                        <th style='padding: 8px 12px; text-align: right;'>P. Unit</th>
                        <th style='padding: 8px 12px; text-align: right;'>Subtotal</th>
                      </tr>
                    </thead>
                    <tbody>
                      {$itemsHtml}
                    </tbody>
                  </table>

                  <div style='text-align: right; font-size: 18px; color: #0F172A; border-top: 2px solid #E2E8F0; padding-top: 12px;'>
                    <strong>Total Aprobado: \$U {$total}</strong>
                  </div>

                  <hr style='border: none; border-top: 1px solid #E2E8F0; margin: 25px 0;'>

                  <p style='color: #64748B; font-size: 12px; text-align: center; margin-bottom: 0;'>
                    RedTec Informática — Atlántida, Canelones, Uruguay.<br>
                    Ante cualquier duda podés comunicarte vía WhatsApp al <a href='https://wa.me/59899372649' style='color: #E11D48;'>099 372 649</a>.
                  </p>
                </div>
              </div>
            </body>
            </html>
            ";

            $headers   = [];
            $headers[] = 'MIME-Version: 1.0';
            $headers[] = 'Content-type: text/html; charset=utf-8';
            $headers[] = 'From: RedTec Informática <ventas@redtecinformatica.com>';
            $headers[] = 'Reply-To: ventas@redtecinformatica.com';

            $adminEmail = 'admin@redtecinformatica.com';
            $subject    = "Confirmación de Compra {$orderId} — RedTec Informática";

            // 1. Enviar al administrador
            @mail($adminEmail, "NUEVO PEDIDO PAGADO {$orderId} — {$clientName}", $bodyHtml, implode("\r\n", $headers));

            // 2. Enviar al cliente si ingresó email
            if ($clientEmail) {
                @mail($clientEmail, $subject, $bodyHtml, implode("\r\n", $headers));
            }

            return true;
        } catch (Throwable $e) {
            error_log("Error al enviar email de confirmación de pedido: " . $e->getMessage());
            return false;
        }
    }
}
