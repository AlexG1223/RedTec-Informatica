<?php
/**
 * RedTec Informática - Vista del Catálogo de Productos (Tienda)
 * 
 * @var array $products Lista de productos filtrados
 * @var array $categories Lista de categorías para el filtro
 * @var int $categoriaId ID de la categoría activa (0 si es 'Todas')
 * @var string $buscar Texto de búsqueda actual
 * @var array|null $activeCategory Objeto de la categoría seleccionada
 */

$content = function() use ($products, $categories, $categoriaId, $buscar, $activeCategory) {
    $fallbackImg = url('/assets/img/redtec.jpeg');
?>
  <!-- Banner Superior Tienda -->
  <section style="background-color: var(--color-dark); color: #FFFFFF; padding: 2.5rem 0; border-bottom: 4px solid var(--color-primary);">
    <div class="container">
      <span style="font-family: var(--font-heading); font-size: 0.8rem; font-weight: 700; color: var(--color-primary); text-transform: uppercase; letter-spacing: 0.08em;">Tienda Online</span>
      <h1 style="color: #FFFFFF; margin-top: 0.35rem; margin-bottom: 0.5rem; font-size: 2.2rem;">
        <?= $activeCategory ? htmlspecialchars($activeCategory['name']) : 'Catálogo de Productos' ?>
      </h1>
      <p style="color: #B0B0B0; margin-bottom: 0; max-width: 640px;">
        Explorá nuestro catálogo de equipamiento informático, insumos de red, videovigilancia y accesorios en Uruguay.
      </p>
    </div>
  </section>

  <div class="container section-padding">

    <!-- BARRA SUPERIOR DE BÚSQUEDA Y FILTROS -->
    <div style="background: #FFFFFF; padding: 1.5rem; border-radius: var(--radius-lg); border: 1px solid var(--color-border-light); box-shadow: var(--shadow-sm); margin-bottom: 2.5rem;">
      
      <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.25rem;">
        
        <!-- Formulario de Búsqueda -->
        <form action="<?= url('/tienda') ?>" method="GET" style="display: flex; gap: 0.5rem; flex: 1 1 300px; max-width: 450px;">
          <?php if ($categoriaId > 0): ?>
            <input type="hidden" name="categoria" value="<?= $categoriaId ?>">
          <?php endif; ?>
          <div style="position: relative; width: 100%;">
            <input type="text" 
                   name="buscar" 
                   value="<?= htmlspecialchars($buscar) ?>" 
                   placeholder="Buscar por nombre o código (ej: Router, Tapo, NOTE-LEN)..." 
                   style="width: 100%; padding: 0.65rem 1rem; padding-right: 2.5rem; border: 1px solid var(--color-border-metallic); border-radius: var(--radius-md); font-family: var(--font-body); font-size: 0.9375rem; outline: none;"
                   onfocus="this.style.borderColor='var(--color-primary)';"
                   onblur="this.style.borderColor='var(--color-border-metallic)';">
          </div>
          <button type="submit" class="btn btn-primary" title="Buscar productos">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            Buscar
          </button>
        </form>

        <!-- Indicador de Resultados -->
        <div style="font-size: 0.9rem; color: var(--color-text-secondary); font-weight: 500;">
          Mostrando <strong><?= count($products) ?></strong> producto<?= count($products) !== 1 ? 's' : '' ?>
        </div>

      </div>

      <!-- Pills de Filtro por Categoría -->
      <div style="margin-top: 1.25rem; padding-top: 1rem; border-top: 1px dashed var(--color-border-light); display: flex; flex-wrap: wrap; gap: 0.5rem; align-items: center;">
        <span style="font-size: 0.85rem; font-weight: 600; color: var(--color-dark); margin-right: 0.5rem;">Categorías:</span>
        
        <!-- Pill 'Todas' -->
        <a href="<?= url('/tienda' . (!empty($buscar) ? '?buscar=' . urlencode($buscar) : '')) ?>" 
           class="btn btn-sm <?= $categoriaId === 0 ? 'btn-primary' : 'btn-outline-dark' ?>"
           style="border-radius: var(--radius-full);">
          Todas
        </a>

        <?php foreach ($categories as $cat): ?>
          <?php 
            $catId   = (int)$cat['id'];
            $catName = htmlspecialchars($cat['name']);
            $isActive = ($categoriaId === $catId);
            $urlParams = ['categoria' => $catId];
            if (!empty($buscar)) {
                $urlParams['buscar'] = $buscar;
            }
            $urlCat = url('/tienda?' . http_build_query($urlParams));
          ?>
          <a href="<?= $urlCat ?>" 
             class="btn btn-sm <?= $isActive ? 'btn-primary' : 'btn-outline-dark' ?>"
             style="border-radius: var(--radius-full);">
            <?= $catName ?>
          </a>
        <?php endforeach; ?>
      </div>

    </div>

    <!-- GRILLA DE PRODUCTOS O ESTADO VACÍO -->
    <?php if (empty($products)): ?>
      
      <!-- Estado Vacío (Sin Resultados) -->
      <div style="background: #FFFFFF; padding: 4rem 2rem; border-radius: var(--radius-lg); border: 1px dashed var(--color-border-metallic); text-align: center; max-width: 650px; margin: 2rem auto;">
        <div style="width: 64px; height: 64px; background: var(--color-primary-light); color: var(--color-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem auto;">
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="8" y1="11" x2="14" y2="11"/></svg>
        </div>
        <h3 style="margin-bottom: 0.75rem;">No se encontraron productos</h3>
        <p style="color: var(--color-text-secondary); margin-bottom: 1.75rem;">
          No hay artículos que coincidan con la búsqueda o la categoría seleccionada. Probá borrando el texto de búsqueda o cambiando los filtros.
        </p>
        <a href="<?= url('/tienda') ?>" class="btn btn-primary">Restablecer Filtros</a>
      </div>

    <?php else: ?>

      <!-- Grilla de Cards de Producto -->
      <div class="grid grid-3">
        <?php foreach ($products as $p): ?>
          <?php 
            $pId        = (int)$p['id'];
            $pCode      = htmlspecialchars($p['code']);
            $pName      = htmlspecialchars($p['name']);
            $pCategory  = htmlspecialchars($p['category_name'] ?? 'General');
            $pPriceNum  = (float)$p['price'];
            $pPriceFormatted = number_format($pPriceNum, 2, '.', ',');
            $pStock     = (int)$p['stock'];
            $inStock    = ($pStock > 0);
            
            $rawImg     = !empty($p['primary_image']) ? $p['primary_image'] : null;
            $pImg       = $rawImg ? (strpos($rawImg, 'http') === 0 ? htmlspecialchars($rawImg) : url($rawImg)) : null;
            $productUrl = url('/producto/' . $pId);
          ?>

          <div class="product-card">
            <div class="product-card-img-wrap">
              <!-- Badge de Stock -->
              <span class="product-card-badge">
                <?php if ($inStock): ?>
                  <span class="badge-stock in-stock">En Stock</span>
                <?php else: ?>
                  <span class="badge-stock out-of-stock">Sin Stock</span>
                <?php endif; ?>
              </span>

              <!-- Código de Producto -->
              <span class="product-card-code"><?= $pCode ?></span>

              <!-- Imagen con Placeholder de Seguridad -->
              <?php if ($pImg): ?>
                <img src="<?= $pImg ?>" alt="<?= $pName ?>" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div style="display: none; width: 100%; height: 100%; align-items: center; justify-content: center; background: #F3F4F6; color: var(--color-text-muted); font-size: 0.8rem; font-weight: 600; text-align: center; padding: 1rem;">
                  <span>Sin imagen disponible</span>
                </div>
              <?php else: ?>
                <div style="display: flex; width: 100%; height: 100%; align-items: center; justify-content: center; background: #F3F4F6; color: var(--color-text-muted); font-size: 0.8rem; font-weight: 600; text-align: center; padding: 1rem; flex-direction: column; gap: 0.5rem;">
                  <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                  <span>Sin imagen disponible</span>
                </div>
              <?php endif; ?>
            </div>

            <div class="product-card-body">
              <div class="product-card-category"><?= $pCategory ?></div>
              
              <h3 class="product-card-title">
                <a href="<?= $productUrl ?>"><?= $pName ?></a>
              </h3>

              <div class="product-card-price-wrap">
                <span class="product-card-currency">USD</span>
                <span class="product-card-price"><?= $pPriceFormatted ?></span>
              </div>

              <div class="product-card-actions">
                <a href="<?= $productUrl ?>" class="btn btn-outline btn-sm">Ver Detalle</a>
                
                <button type="button" 
                        class="btn btn-primary btn-sm" 
                        <?= !$inStock ? 'disabled' : '' ?>
                        data-id="<?= $pId ?>"
                        data-code="<?= $pCode ?>"
                        data-name="<?= $pName ?>"
                        data-price="<?= $pPriceNum ?>"
                        data-image="<?= $pImg ?? '' ?>"
                        data-stock="<?= $pStock ?>"
                        onclick="agregarAlCarritoCat(this)">
                  <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                  <?= $inStock ? 'Agregar' : 'Agotado' ?>
                </button>
              </div>
            </div>
          </div>

        <?php endforeach; ?>
      </div>

    <?php endif; ?>

  </div>

  <script>
  function agregarAlCarritoCat(btn) {
    if (!window.CartService || !window.CartUI) return;
    
    const ds = btn.dataset;
    const product = {
      id: ds.id,
      code: ds.code,
      name: ds.name,
      price: ds.price,
      image_url: ds.image,
      stock: parseInt(ds.stock, 10) || 0
    };

    const res = window.CartService.addItem(product, 1);
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
