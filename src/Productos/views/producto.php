<?php
/**
 * RedTec Informática - Vista de Ficha Individual de Producto
 * 
 * @var array $product Datos del producto e imágenes asociadas
 */

$content = function() use ($product) {
    $pId          = (int)$product['id'];
    $pCode        = htmlspecialchars($product['code']);
    $pName        = htmlspecialchars($product['name']);
    $pCategory    = htmlspecialchars($product['category_name'] ?? 'General');
    $pCatId       = (int)($product['category_id'] ?? 0);
    $pDescription = !empty($product['description']) ? nl2br(htmlspecialchars($product['description'])) : 'Sin descripción disponible para este producto.';
    $pPriceNum    = (float)$product['price'];
    $pPrice       = number_format($pPriceNum, 2, '.', ',');
    $pStock       = (int)$product['stock'];
    $inStock      = ($pStock > 0);
    $images       = $product['images'] ?? [];

    $mainImg = !empty($images[0]['image_url']) ? htmlspecialchars($images[0]['image_url']) : null;
    
    // Mensaje pre-armado para consulta por WhatsApp
    $waText = urlencode("Hola RedTec, quiero consultar por el producto {$product['name']} (Código: {$product['code']})");
    $waUrl  = "https://wa.me/59899000000?text={$waText}";
?>
  <!-- Migas de Pan / Navegación -->
  <div style="background: #FFFFFF; border-bottom: 1px solid var(--color-border-light); padding: 0.85rem 0;">
    <div class="container" style="font-size: 0.875rem; color: var(--color-text-secondary);">
      <a href="/index.php" style="color: var(--color-text-secondary);">Inicio</a>
      <span style="margin: 0 0.4rem;">&rsaquo;</span>
      <a href="/tienda" style="color: var(--color-text-secondary);">Tienda</a>
      <?php if ($pCatId > 0): ?>
        <span style="margin: 0 0.4rem;">&rsaquo;</span>
        <a href="/tienda?categoria=<?= $pCatId ?>" style="color: var(--color-text-secondary);"><?= $pCategory ?></a>
      <?php endif; ?>
      <span style="margin: 0 0.4rem;">&rsaquo;</span>
      <strong style="color: var(--color-dark);"><?= $pName ?></strong>
    </div>
  </div>

  <div class="container section-padding">
    
    <div style="background: #FFFFFF; border-radius: var(--radius-lg); border: 1px solid var(--color-border-light); box-shadow: var(--shadow-sm); padding: 2rem;">
      
      <div class="grid grid-2" style="gap: 3rem; align-items: start;">
        
        <!-- COLUMNA IZQUIERDA: GALERÍA DE IMÁGENES -->
        <div>
          <!-- Imagen Principal -->
          <div style="width: 100%; height: 380px; background: #FFFFFF; border: 1px solid var(--color-border-light); border-radius: var(--radius-md); overflow: hidden; display: flex; align-items: center; justify-content: center; position: relative; margin-bottom: 1rem;">
            <?php if ($mainImg): ?>
              <img id="mainProductImg" 
                   src="<?= $mainImg ?>" 
                   alt="<?= $pName ?>" 
                   style="max-width: 100%; max-height: 100%; object-fit: contain; padding: 1.5rem; transition: transform 0.2s;"
                   onerror="this.style.display='none'; document.getElementById('mainImgPlaceholder').style.display='flex';">
              <div id="mainImgPlaceholder" style="display: none; width: 100%; height: 100%; align-items: center; justify-content: center; background: #F8F9FA; color: var(--color-text-muted); font-size: 0.9rem; font-weight: 600; flex-direction: column; gap: 0.5rem;">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                <span>Sin imagen disponible</span>
              </div>
            <?php else: ?>
              <div style="display: flex; width: 100%; height: 100%; align-items: center; justify-content: center; background: #F8F9FA; color: var(--color-text-muted); font-size: 0.9rem; font-weight: 600; flex-direction: column; gap: 0.5rem;">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                <span>Sin imagen disponible</span>
              </div>
            <?php endif; ?>
          </div>

          <!-- Miniaturas Galería (si hay más de 1 imagen) -->
          <?php if (count($images) > 1): ?>
            <div style="display: flex; gap: 0.75rem; overflow-x: auto; padding-bottom: 0.5rem;">
              <?php foreach ($images as $idx => $img): ?>
                <?php $imgUrl = htmlspecialchars($img['image_url']); ?>
                <button type="button" 
                        onclick="cambiarImagenPrincipal('<?= $imgUrl ?>')"
                        style="width: 70px; height: 70px; border: 2px solid <?= $idx === 0 ? 'var(--color-primary)' : 'var(--color-border-light)' ?>; border-radius: var(--radius-sm); overflow: hidden; background: #FFFFFF; cursor: pointer; padding: 2px; flex-shrink: 0;"
                        onfocus="this.style.borderColor='var(--color-primary)';"
                        class="thumb-btn">
                  <img src="<?= $imgUrl ?>" alt="" style="width: 100%; height: 100%; object-fit: contain;">
                </button>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>

        <!-- COLUMNA DERECHA: INFORMACIÓN Y ACCIONES -->
        <div style="display: flex; flex-direction: column; height: 100%;">
          
          <div style="margin-bottom: 0.75rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.5rem;">
            <a href="/tienda?categoria=<?= $pCatId ?>" style="font-size: 0.8rem; font-weight: 700; color: var(--color-primary); text-transform: uppercase; letter-spacing: 0.05em;">
              <?= $pCategory ?>
            </a>
            <span style="font-size: 0.8rem; font-weight: 600; color: var(--color-text-muted); background: #F1F3F5; padding: 0.2rem 0.5rem; border-radius: var(--radius-sm);">
              Código: <?= $pCode ?>
            </span>
          </div>

          <h1 style="font-size: clamp(1.5rem, 3vw, 2rem); margin-bottom: 1rem; color: var(--color-dark); font-weight: 700;">
            <?= $pName ?>
          </h1>

          <div style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 1rem;">
            <?php if ($inStock): ?>
              <span class="badge-stock in-stock">En Stock (<?= $pStock ?> unidad<?= $pStock > 1 ? 'es' : '' ?>)</span>
            <?php else: ?>
              <span class="badge-stock out-of-stock">Sin Stock Disponible</span>
            <?php endif; ?>
          </div>

          <!-- Precio -->
          <div style="background: var(--color-bg); padding: 1.25rem; border-radius: var(--radius-md); border: 1px solid var(--color-border-light); margin-bottom: 1.75rem;">
            <div style="font-size: 0.85rem; color: var(--color-text-secondary); font-weight: 600;">Precio de Lista:</div>
            <div style="font-family: var(--font-heading); font-size: 2.2rem; font-weight: 800; color: var(--color-primary); line-height: 1;">
              <span style="font-size: 1.1rem; color: var(--color-text-main); font-weight: 700; margin-right: 0.25rem;">USD $</span><?= $pPrice ?>
            </div>
            <div style="font-size: 0.78rem; color: var(--color-text-muted); margin-top: 0.35rem;">
              IVA Incluido &bull; Pago contado o transferencia bancaria
            </div>
          </div>

          <!-- Descripción -->
          <div style="margin-bottom: 2rem;">
            <h4 style="font-size: 1rem; margin-bottom: 0.5rem; color: var(--color-dark);">Descripción del Producto</h4>
            <div style="font-size: 0.95rem; color: var(--color-text-secondary); line-height: 1.6;">
              <?= $pDescription ?>
            </div>
          </div>

          <!-- Selector de Cantidad y Botones de Acción -->
          <div style="margin-top: auto; padding-top: 1.5rem; border-top: 1px solid var(--color-border-light);">
            
            <div style="display: flex; flex-wrap: wrap; gap: 1rem; align-items: center; margin-bottom: 1.25rem;">
              <label for="cantidad" style="font-size: 0.9rem; font-weight: 600; color: var(--color-dark);">Cantidad:</label>
              <input type="number" 
                     id="cantidad" 
                     name="cantidad" 
                     value="1" 
                     min="1" 
                     max="<?= max(1, $pStock) ?>" 
                     <?= !$inStock ? 'disabled' : '' ?>
                     style="width: 80px; padding: 0.5rem; border: 1px solid var(--color-border-metallic); border-radius: var(--radius-md); font-family: var(--font-heading); font-size: 1rem; font-weight: 700; text-align: center;">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
              <!-- Botón Agregar al Carrito -->
              <button type="button" 
                      class="btn btn-primary btn-lg" 
                      <?= !$inStock ? 'disabled' : '' ?>
                      data-id="<?= $pId ?>"
                      data-code="<?= $pCode ?>"
                      data-name="<?= $pName ?>"
                      data-price="<?= $pPriceNum ?>"
                      data-image="<?= $mainImg ?? '' ?>"
                      data-stock="<?= $pStock ?>"
                      onclick="agregarAlCarritoFicha(this)">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                <?= $inStock ? 'Agregar al carrito' : 'Sin Stock' ?>
              </button>

              <!-- Botón Consulta por WhatsApp -->
              <a href="<?= $waUrl ?>" 
                 target="_blank" 
                 rel="noopener noreferrer" 
                 class="btn btn-secondary btn-lg"
                 style="background-color: var(--color-whatsapp); border-color: var(--color-whatsapp);">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.335-1.662a11.87 11.87 0 005.71 1.455h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413"/></svg>
                Consulta directa
              </a>
            </div>

          </div>

        </div>

      </div>

    </div>

  </div>

  <script>
  function cambiarImagenPrincipal(src) {
    const mainImg = document.getElementById('mainProductImg');
    if (mainImg) {
      mainImg.src = src;
    }
  }

  function agregarAlCarritoFicha(btn) {
    if (!window.CartService || !window.CartUI) return;
    const ds = btn.dataset;
    const qtyInput = document.getElementById('cantidad');
    const quantity = qtyInput ? parseInt(qtyInput.value, 10) || 1 : 1;

    const product = {
      id: ds.id,
      code: ds.code,
      name: ds.name,
      price: ds.price,
      image_url: ds.image,
      stock: parseInt(ds.stock, 10) || 0
    };

    const res = window.CartService.addItem(product, quantity);
    if (res.success) {
      window.CartUI.showToast(res.message, res.capped ? 'warning' : 'success');
    } else {
      window.CartUI.showToast(res.message, 'error');
    }
  }
  </script>
<?php
};

require __DIR__ . '/../../../shared/Layout/layout.php';
