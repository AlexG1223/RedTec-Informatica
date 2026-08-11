/**
 * RedTec Informática - Generador de Mensajes de WhatsApp para Checkout
 */
(function(window) {
  'use strict';

  const WhatsAppMessageBuilder = {
    /**
     * Construye el texto formateado del pedido para WhatsApp.
     * @param {Object} customerData Objeto con {name, phone, address}
     * @param {Array} cartItems Lista de items del carrito
     * @param {number} subtotal Subtotal total del pedido
     * @returns {string}
     */
    build: function(customerData, cartItems, subtotal) {
      let msg = "¡Hola RedTec! Quiero realizar el siguiente pedido:\n\n";

      cartItems.forEach(function(item) {
        const itemSubtotal = (parseFloat(item.price) * parseInt(item.quantity, 10)).toFixed(2);
        const itemCode = item.code ? ` (${item.code})` : '';
        msg += `• ${item.quantity}x ${item.name}${itemCode} — USD $${parseFloat(item.price).toFixed(2)} c/u = USD $${itemSubtotal}\n`;
      });

      msg += `\n*Total Estimado: USD $${parseFloat(subtotal).toFixed(2)}*\n\n`;
      msg += "📋 *Datos del Comprador:*\n";
      msg += `• *Nombre:* ${customerData.name}\n`;
      msg += `• *Teléfono:* ${customerData.phone}\n`;

      const addressStr = (customerData.address && customerData.address.trim() !== '') 
        ? customerData.address.trim() 
        : 'Retira en el local (Atlántida)';
        
      msg += `• *Dirección de Entrega:* ${addressStr}\n`;

      return msg;
    }
  };

  window.WhatsAppMessageBuilder = WhatsAppMessageBuilder;

})(window);
