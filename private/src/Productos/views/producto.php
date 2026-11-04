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
              <span style="font-size: 0.9rem; font-weight: 800; color: var(--color-text-secondary); margin-right: 0.25rem;">$</span>
              <span style="font-family: var(--font-heading); font-size: 2.2rem; font-weight: 900; color: var(--color-primary); line-height: 1;">
                $<?= $pPrice ?>
              </span>
            </div>

            <?php if ($inStock): ?>
              <div>
                <span class="badge-stock in-stock" style="font-size: 0.85rem; padding: 0.4rem 0.85rem;">En Stock Disponible</span>
              </div>
            <?php endif; ?>
          </div>

          <!-- SELECTOR DE CANTIDAD Y BOTÓN AGREGAR / CONSULTAR STOCK -->
          <div style="display: flex; gap: 1rem; margin-bottom: 2rem; align-items: center; flex-wrap: wrap;">
            <?php if ($inStock): ?>
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
                      style="flex: 1 1 220px;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                Agregar al Carrito
              </button>
            <?php else: ?>
              <?php
                $waMsg = "Hola RedTec, quisiera consultar stock del producto: " . $product['name'] . (!empty($product['code']) ? " (Código: " . $product['code'] . ")" : "");
                $waLink = REDTEC_WHATSAPP_LINK . '?text=' . urlencode($waMsg);
              ?>
              <a href="<?= $waLink ?>" 
                 target="_blank" 
                 rel="noopener noreferrer" 
                 class="btn btn-primary btn-lg"
                 style="flex: 1 1 220px; background-color: var(--color-whatsapp); border-color: var(--color-whatsapp);">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.335-1.662a11.87 11.87 0 005.71 1.455h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413"/></svg>
                Consultar Stock
              </a>
            <?php endif; ?>
          </div>

          <!-- DESCRIPCIÓN DETALLADA -->
            <div style="font-size: 0.95rem; color: var(--color-text-main); line-height: 1.7;">
              <?= $pDesc ?>
            </div>

            <!-- ENLAZADO INTERNO SEO CONTEXTUAL -->
            <div style="margin-top: 1.75rem; padding-top: 1.25rem; border-top: 1px dashed var(--color-border-light); font-size: 0.88rem; color: var(--color-text-secondary);">
              <strong>Categorías & Repuestos Relacionados:</strong>
              <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; margin-top: 0.5rem;">
                <a href="<?= url('/tienda') ?>" class="btn btn-outline-dark btn-sm" style="font-size: 0.78rem; padding: 0.25rem 0.6rem;">Memorias RAM y Repuestos</a>
                <a href="<?= url('/guias/cartuchos-de-impresora') ?>" class="btn btn-outline-dark btn-sm" style="font-size: 0.78rem; padding: 0.25rem 0.6rem;">Cartuchos de Impresora</a>
                <a href="<?= url('/guias/notebook-lenta-que-hacer') ?>" class="btn btn-outline-dark btn-sm" style="font-size: 0.78rem; padding: 0.25rem 0.6rem;">Optimización SSD & RAM</a>
                <a href="<?= url('/servicios') ?>" class="btn btn-outline-dark btn-sm" style="font-size: 0.78rem; padding: 0.25rem 0.6rem;">Service de PC Atlántida</a>
              </div>
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
