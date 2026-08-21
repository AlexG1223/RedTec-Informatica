/**
 * RedTec Informática - Servicio de Carrito de Compras (CartService)
 * Almacenamiento y gestión del estado del carrito en localStorage ('redtec_cart')
 */
(function(window) {
  'use strict';

  const STORAGE_KEY = 'redtec_cart';

  const CartService = {
    /**
     * Obtiene los elementos almacenados en el carrito.
     * @returns {Array}
     */
    getItems: function() {
      try {
        const raw = localStorage.getItem(STORAGE_KEY);
        return raw ? JSON.parse(raw) : [];
      } catch (e) {
        console.error('Error leyendo carrito desde localStorage:', e);
        return [];
      }
    },

    /**
     * Guarda la lista de items en localStorage y notifica cambios.
     * @param {Array} items 
     */
    _saveItems: function(items) {
      try {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(items));
        this._notifyUpdate();
      } catch (e) {
        console.error('Error guardando carrito en localStorage:', e);
      }
    },

    /**
     * Emite un evento personalizado en el DOM para actualizar la interfaz.
     */
    _notifyUpdate: function() {
      const event = new CustomEvent('cart:updated', {
        detail: {
          items: this.getItems(),
          totalItems: this.getTotalItems(),
          subtotal: this.getSubtotal()
        }
      });
      window.dispatchEvent(event);
    },

    /**
     * Agrega un producto al carrito o incrementa su cantidad.
     * @param {Object} product Objeto con {id, code, name, price, image_url, stock}
     * @param {number} quantity Cantidad a agregar (por defecto 1)
     * @returns {Object} Resultado {success: boolean, message: string, capped: boolean}
     */
    addItem: function(product, quantity) {
      let stock = parseInt(product.stock, 10);
      if (isNaN(stock)) {
        stock = 999;
      }

      if (stock <= 0) {
        return { success: false, message: 'Producto sin stock disponible.', capped: false };
      }

      const items = this.getItems();
      const existingIndex = items.findIndex(item => String(item.id) === String(product.id));

      let isCapped = false;
      let message = '¡Producto agregado al carrito!';

      if (existingIndex > -1) {
        const currentQty = items[existingIndex].quantity;
        let newQty = currentQty + quantity;

        if (newQty > stock) {
          newQty = stock;
          isCapped = true;
          message = `Alcanzado el límite de stock disponible (${stock} u.)`;
        }

        items[existingIndex].quantity = newQty;
        // Actualizar datos por si cambiaron
        items[existingIndex].price = parseFloat(product.price);
        items[existingIndex].stock = stock;
      } else {
        let finalQty = quantity;
        if (finalQty > stock) {
          finalQty = stock;
          isCapped = true;
          message = `Alcanzado el límite de stock disponible (${stock} u.)`;
        }

        items.push({
          id: product.id,
          code: product.code || '',
          name: product.name || 'Producto',
          price: parseFloat(product.price) || 0,
          image_url: product.image_url || '',
          stock: stock,
          quantity: finalQty
        });
      }

      this._saveItems(items);
      return { success: true, message: message, capped: isCapped };
    },

    /**
     * Actualiza la cantidad de un producto específico.
     * @param {number|string} productId 
     * @param {number} quantity 
     */
    updateQuantity: function(productId, quantity) {
      quantity = parseInt(quantity, 10);
      if (isNaN(quantity) || quantity <= 0) {
        this.removeItem(productId);
        return;
      }

      const items = this.getItems();
      const index = items.findIndex(item => String(item.id) === String(productId));

      if (index > -1) {
        const maxStock = items[index].stock || 999;
        if (quantity > maxStock) {
          quantity = maxStock;
        }
        items[index].quantity = quantity;
        this._saveItems(items);
      }
    },

    /**
     * Elimina un producto del carrito.
     * @param {number|string} productId 
     */
    removeItem: function(productId) {
      let items = this.getItems();
      items = items.filter(item => String(item.id) !== String(productId));
      this._saveItems(items);
    },

    /**
     * Calcula la cantidad total de artículos en el carrito.
     * @returns {number}
     */
    getTotalItems: function() {
      const items = this.getItems();
      return items.reduce((sum, item) => sum + (parseInt(item.quantity, 10) || 0), 0);
    },

    /**
     * Calcula el subtotal monetario acumulado del carrito.
     * @returns {number}
     */
    getSubtotal: function() {
      const items = this.getItems();
      return items.reduce((sum, item) => {
        const price = parseFloat(item.price) || 0;
        const qty = parseInt(item.quantity, 10) || 0;
        return sum + (price * qty);
      }, 0);
    },

    /**
     * Vacía completamente el carrito de compras.
     */
    clear: function() {
      localStorage.removeItem(STORAGE_KEY);
      this._notifyUpdate();
    }
  };

  window.CartService = CartService;

})(window);
