/**
 * RedTec Informática - Controlador de Interfaz del Carrito (CartUI)
 * Sincroniza el badge del header, panel lateral (drawer) y toasts interactivos.
 */
(function(window) {
  'use strict';

  const CartUI = {
    init: function() {
      this.cacheDOM();
      this.bindEvents();
      this.render();
    },

    cacheDOM: function() {
      this.badge          = document.getElementById('cartCountBadge');
      this.backdrop       = document.getElementById('cartDrawerBackdrop');
      this.drawer         = document.getElementById('cartDrawer');
      this.closeBtn       = document.getElementById('cartDrawerClose');
      this.itemsContainer = document.getElementById('cartDrawerItems');
      this.emptyState     = document.getElementById('cartDrawerEmpty');
      this.footer         = document.getElementById('cartDrawerFooter');
      this.subtotalEl     = document.getElementById('cartDrawerSubtotal');
      this.triggers       = document.querySelectorAll('.cart-trigger');
      
      // Contenedor de Toasts
      this.toastContainer = document.getElementById('redtecToastContainer');
      if (!this.toastContainer) {
        this.toastContainer = document.createElement('div');
        this.toastContainer.id = 'redtecToastContainer';
        this.toastContainer.className = 'redtec-toast-container';
        document.body.appendChild(this.toastContainer);
      }
    },

    bindEvents: function() {
      const self = this;

      // Abrir Drawer desde cualquier disparador del header
      this.triggers.forEach(function(trigger) {
        trigger.addEventListener('click', function(e) {
          e.preventDefault();
          self.openDrawer();
        });
      });

      // Cerrar Drawer
      if (this.closeBtn) {
        this.closeBtn.addEventListener('click', function() {
          self.closeDrawer();
        });
      }

      if (this.backdrop) {
        this.backdrop.addEventListener('click', function() {
          self.closeDrawer();
        });
      }

      // Escuchar actualización del carrito
      window.addEventListener('cart:updated', function() {
        self.render();
      });

      // Cerrar con la tecla ESC
      document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && self.drawer && self.drawer.classList.contains('is-open')) {
          self.closeDrawer();
        }
      });
    },

    openDrawer: function() {
      if (this.drawer && this.backdrop) {
        this.drawer.classList.add('is-open');
        this.backdrop.classList.add('is-open');
        document.body.style.overflow = 'hidden';
      }
    },

    closeDrawer: function() {
      if (this.drawer && this.backdrop) {
        this.drawer.classList.remove('is-open');
        this.backdrop.classList.remove('is-open');
        document.body.style.overflow = '';
      }
    },

    render: function() {
      const items = window.CartService.getItems();
      const totalItems = window.CartService.getTotalItems();
      const subtotal = window.CartService.getSubtotal();

      // 1. Actualizar Badge del Header
      if (this.badge) {
        this.badge.textContent = totalItems;
        this.badge.style.display = totalItems > 0 ? 'flex' : 'none';
      }

      // 2. Actualizar Render del Drawer
      if (this.itemsContainer && this.emptyState && this.footer) {
        if (items.length === 0) {
          this.itemsContainer.innerHTML = '';
          this.emptyState.style.display = 'flex';
          this.footer.style.display = 'none';
        } else {
          this.emptyState.style.display = 'none';
          this.footer.style.display = 'block';

          if (this.subtotalEl) {
            this.subtotalEl.textContent = subtotal.toFixed(2);
          }

          this.renderItems(items);
        }
      }
    },

    renderItems: function(items) {
      const self = this;
      const baseUrl = window.REDTEC_BASE_URL || '';
      const fallbackImg = baseUrl + '/assets/img/redtec.jpeg';
      this.itemsContainer.innerHTML = '';

      items.forEach(function(item) {
        const itemEl = document.createElement('div');
        itemEl.className = 'cart-item';

        let imgSrc = fallbackImg;
        if (item.image_url) {
          imgSrc = item.image_url.indexOf('http') === 0 ? item.image_url : (baseUrl + (item.image_url.startsWith('/') ? '' : '/') + item.image_url);
        }

        itemEl.innerHTML = `
          <img src="${imgSrc}" alt="${self.escapeHtml(item.name)}" class="cart-item-img" onerror="this.src='${fallbackImg}';">
          <div class="cart-item-info">
            <div class="cart-item-title">${self.escapeHtml(item.name)}</div>
            <div class="cart-item-price">USD $${parseFloat(item.price).toFixed(2)}</div>
            <div class="cart-item-controls">
              <div class="cart-qty-wrapper">
                <button type="button" class="cart-qty-btn qty-minus" data-id="${item.id}">-</button>
                <input type="text" class="cart-qty-input" value="${item.quantity}" readonly>
                <button type="button" class="cart-qty-btn qty-plus" data-id="${item.id}" ${item.quantity >= item.stock ? 'disabled' : ''}>+</button>
              </div>
              <button type="button" class="cart-item-remove" data-id="${item.id}" title="Eliminar producto">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
              </button>
            </div>
          </div>
        `;


        // Eventos de botones (- / + / borrar)
        itemEl.querySelector('.qty-minus').addEventListener('click', function() {
          window.CartService.updateQuantity(item.id, item.quantity - 1);
        });

        itemEl.querySelector('.qty-plus').addEventListener('click', function() {
          if (item.quantity < item.stock) {
            window.CartService.updateQuantity(item.id, item.quantity + 1);
          } else {
            self.showToast(`Límite de stock alcanzado (${item.stock} u.)`, 'warning');
          }
        });

        itemEl.querySelector('.cart-item-remove').addEventListener('click', function() {
          window.CartService.removeItem(item.id);
          self.showToast('Producto eliminado del carrito.', 'info');
        });

        self.itemsContainer.appendChild(itemEl);
      });
    },

    /**
     * Muestra una notificación emergente (Toast).
     * @param {string} message 
     * @param {string} type 'success' | 'warning' | 'error' | 'info'
     */
    showToast: function(message, type) {
      type = type || 'success';
      const toast = document.createElement('div');
      toast.className = `redtec-toast ${type}`;

      let iconSvg = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>';
      if (type === 'warning' || type === 'error') {
        iconSvg = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>';
      }

      toast.innerHTML = `${iconSvg}<span>${this.escapeHtml(message)}</span>`;
      this.toastContainer.appendChild(toast);

      // Trigger de animación
      setTimeout(function() {
        toast.classList.add('show');
      }, 10);

      // Auto ocultar a los 3 segundos
      setTimeout(function() {
        toast.classList.remove('show');
        setTimeout(function() {
          if (toast.parentNode) {
            toast.parentNode.removeChild(toast);
          }
        }, 300);
      }, 3000);
    },

    escapeHtml: function(str) {
      return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
    }
  };

  window.CartUI = CartUI;

  document.addEventListener('DOMContentLoaded', function() {
    CartUI.init();
  });

})(window);
