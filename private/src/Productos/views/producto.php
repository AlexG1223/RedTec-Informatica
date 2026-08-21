<?php
/**
 * RedTec Informática - Vista de la Ficha Individual de Producto
 * 
 * @var array $product Datos del producto traídos desde ProductoRepository
 */

$content = function() use ($product) {
    $fallbackImg = url('/assets/img/redtec.jpeg');
    $pId         = (int)$product['id'];
    $pCode       = htmlspecialchars($product['code'] ?? '');
    $pName       = htmlspecialchars($product['name']);
    $pDesc       = nl2br(htmlspecialchars($product['description'] ?? 'Sin descripción detallada disponible.'));
    $pCat        = htmlspecialchars($product['category_name'] ?? 'General');
    $pPrice      = number_format((float)$product['price'], 2, '.', ',');
    $inStock     = ((int)($product['stock'] ?? 0)) > 0;
    
    $images      = $product['images'] ?? [];
    $mainImgRaw  = !empty($images[0]['image_url']) ? $images[0]['image_url'] : null;
    $mainImg     = $mainImgRaw ? (strpos($mainImgRaw, 'http') === 0 ? htmlspecialchars($mainImgRaw) : url($mainImgRaw)) : $fallbackImg;
?>
  <!-- BREADCRUMB -->
  <div style="background-color: var(--color-dark); color: #FFFFFF; padding: 1.5rem 0; border-bottom: 3px solid var(--color-primary);">
    <div class="container">
      <div style="font-size: 0.85rem; color: #B0B0B0; display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
        <a href="<?= url('/') ?>" style="color: #B0B0B0;">Inicio</a> &rarr; 
        <a href="<?= url('/tienda') ?>" style="color: #B0B0B0;">Tienda</a> &rarr; 
        <?php if (!empty($product['category_id'])): ?>
          <a href="<?= url('/tienda?categoria=' . $product['category_id']) ?>" style="color: #B0B0B0;"><?= $pCat ?></a> &rarr; 
        <?php endif; ?>
        <span style="color: var(--color-primary); font-weight: 700;"><?= $pName ?></span>
      </div>
    </div>
  </div>

  <section class="section-padding">
    <div class="container">
      
      <div style="display: grid; grid-template-columns: 1fr 1.1fr; gap: 3rem; background: #FFFFFF; padding: 2.5rem; border-radius: var(--radius-lg); border: 1px solid var(--color-border-light); box-shadow: var(--shadow-sm);" class="grid-product-detail">
        
        <!-- GALERÍA DE IMÁGENES -->
        <div>
          <div style="border: 1px solid var(--color-border-light); border-radius: var(--radius-md); overflow: hidden; padding: 1.5rem; background: #FFFFFF; margin-bottom: 1rem; text-align: center;">
            <img src="<?= $mainImg ?>" 
                 alt="<?= $pName ?>" 
                 id="mainProductImage" 
                 style="max-height: 380px; width: auto; margin: 0 auto; object-fit: contain;" 
                 onerror="this.src='<?= $fallbackImg ?>';">
          </div>

          <?php if (count($images) > 1): ?>
            <div style="display: flex; gap: 0.75rem; overflow-x: auto; padding-bottom: 0.5rem;">
              <?php foreach ($images as $img): ?>
                <?php 
                  $thumbRaw = $img['image_url'];
                  $thumb    = strpos($thumbRaw, 'http') === 0 ? htmlspecialchars($thumbRaw) : url($thumbRaw);
                ?>
                <img src="<?= $thumb ?>" 
                     alt="" 
                     style="width: 70px; height: 70px; object-fit: contain; border: 1px solid var(--color-border-metallic); border-radius: var(--radius-sm); cursor: pointer; padding: 4px; background: #FFF;"
                     onclick="document.getElementById('mainProductImage').src = this.src;"
                     onerror="this.src='<?= $fallbackImg ?>';">
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>

        <!-- DETALLE DEL PRODUCTO -->
        <div style="display: flex; flex-direction: column;">
          
          <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.75rem; flex-wrap: wrap; gap: 0.5rem;">
            <span style="font-size: 0.8rem; font-weight: 800; text-transform: uppercase; color: var(--color-primary); letter-spacing: 0.05em; background: var(--color-primary-light); padding: 0.25rem 0.65rem; border-radius: var(--radius-sm);">
              <?= $pCat ?>
            </span>
            <span style="font-size: 0.85rem; font-weight: 600; color: var(--color-text-muted);">
              Código: <strong style="color: var(--color-dark);"><?= $pCode ?></strong>
            </span>
          </div>

          <h1 style="font-size: clamp(1.6rem, 3vw, 2.2rem); color: var(--color-dark); margin-bottom: 1rem; font-weight: 800; line-height: 1.2;">
            <?= $pName ?>
          </h1>

          <!-- PRECIO Y DISPONIBILIDAD -->
          <div style="background: var(--color-bg); padding: 1.25rem; border-radius: var(--radius-md); border: 1px solid var(--color-border-light); margin-bottom: 1.75rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
            <div>
              <span style="font-size: 0.85rem; color: var(--color-text-muted); display: block;">Precio al contado / transferencia:</span>
              <span style="font-size: 0.9rem; font-weight: 800; color: var(--color-text-secondary); margin-right: 0.25rem;">USD</span>
              <span style="font-family: var(--font-heading); font-size: 2.2rem; font-weight: 900; color: var(--color-primary); line-height: 1;">
                $<?= $pPrice ?>
              </span>
            </div>

            <div>
              <?php if ($inStock): ?>
                <span class="badge-stock in-stock" style="font-size: 0.85rem; padding: 0.4rem 0.85rem;">En Stock Disponible</span>
              <?php else: ?>
                <span class="badge-stock out-of-stock" style="font-size: 0.85rem; padding: 0.4rem 0.85rem;">Agotado</span>
              <?php endif; ?>
            </div>
          </div>

          <!-- SELECTOR DE CANTIDAD Y BOTÓN AGREGAR -->
          <div style="display: flex; gap: 1rem; margin-bottom: 2rem; align-items: center; flex-wrap: wrap;">
            <div style="display: flex; align-items: center; border: 1px solid var(--color-border-metallic); border-radius: var(--radius-md); overflow: hidden; background: #FFF;">
              <button type="button" onclick="const input = document.getElementById('prodQty'); if(input.value > 1) input.value--;" style="width: 40px; height: 44px; border: none; background: #F1F3F5; font-weight: bold; cursor: pointer;">-</button>
              <input type="number" id="prodQty" value="1" min="1" max="99" style="width: 50px; height: 44px; border: none; text-align: center; font-family: var(--font-heading); font-weight: bold; font-size: 1rem;">
              <button type="button" onclick="const input = document.getElementById('prodQty'); input.value++;" style="width: 40px; height: 44px; border: none; background: #F1F3F5; font-weight: bold; cursor: pointer;">+</button>
            </div>

            <button type="button" 
                    id="btnAddToCartDetail"
                    class="btn btn-primary btn-lg" 
                    data-id="<?= $pId ?>"
                    data-code="<?= $pCode ?>"
                    data-name="<?= $pName ?>"
                    data-price="<?= (float)$product['price'] ?>"
                    data-image="<?= $mainImg ?>"
                    data-stock="<?= (int)($product['stock'] ?? 99) ?>"
                    style="flex: 1 1 220px;"
                    <?= !$inStock ? 'disabled' : '' ?>>
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
              <?= $inStock ? 'Agregar al Carrito' : 'Agotado' ?>
            </button>
          </div>

          <!-- DESCRIPCIÓN DETALLADA -->
          <div style="border-top: 1px solid var(--color-border-light); padding-top: 1.5rem;">
            <h3 style="font-size: 1.1rem; margin-bottom: 0.75rem; color: var(--color-dark);">Especificaciones & Detalle</h3>
            <div style="font-size: 0.95rem; color: var(--color-text-main); line-height: 1.7;">
              <?= $pDesc ?>
            </div>
          </div>

        </div>

      </div>

    </div>
  </section>

  <script>
  document.addEventListener('DOMContentLoaded', function() {
    const btn = document.getElementById('btnAddToCartDetail');
    if (btn) {
      btn.addEventListener('click', function() {
        const qtyInput = document.getElementById('prodQty');
        const qty = qtyInput ? parseInt(qtyInput.value, 10) : 1;

        if (window.CartService) {
          window.CartService.addItem({
            id: <?= $pId ?>,
            code: "<?= $pCode ?>",
            name: "<?= $pName ?>",
            price: <?= (float)$product['price'] ?>,
            image: "<?= $mainImg ?>"
          }, qty);
          
          if (window.CartUI) {
            window.CartUI.openDrawer();
          }
        }
      });
    }
  });
  </script>

  <style>
  @media (max-width: 991px) {
    .grid-product-detail {
      grid-template-columns: 1fr !important;
    }
  }
  </style>
<?php
};

require REDTEC_SHARED_DIR . '/Layout/layout.php';
