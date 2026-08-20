<?php
/**
 * RedTec Informática - Vista del Catálogo de Productos (Tienda)
 * 
 * @var array $products Lista de productos filtrados traídos del repositorio.
 * @var array $categories Lista de categorías activas para el filtro.
 * @var array|null $activeCategory Categoría actualmente seleccionada (si aplica).
 * @var string $buscar Término de búsqueda (si aplica).
 */

$content = function() use ($products, $categories, $activeCategory, $buscar) {
    $fallbackImg = url('/assets/img/redtec.jpeg');
?>
  <!-- CABECERA DE SECCIÓN Y BREADCRUMB -->
  <div style="background-color: var(--color-dark); color: #FFFFFF; padding: 2.5rem 0; border-bottom: 3px solid var(--color-primary);">
    <div class="container">
      <div style="display: flex; flex-direction: column; gap: 0.5rem;">
        <div style="font-size: 0.85rem; color: #B0B0B0; display: flex; align-items: center; gap: 0.5rem;">
          <a href="<?= url('/') ?>" style="color: #B0B0B0;">Inicio</a> &rarr; 
          <a href="<?= url('/tienda') ?>" style="color: #B0B0B0;">Tienda</a>
          <?php if ($activeCategory): ?>
            &rarr; <span style="color: var(--color-primary); font-weight: 700;"><?= htmlspecialchars($activeCategory['name']) ?></span>
          <?php endif; ?>
        </div>

        <h1 style="color: #FFFFFF; margin-bottom: 0; font-weight: 800;">
          <?= $activeCategory ? htmlspecialchars($activeCategory['name']) : 'Catálogo de Productos' ?>
        </h1>
        <p style="color: #D2D2D2; margin-bottom: 0; font-size: 0.95rem;">
          Explorá equipamiento informático, notebooks, redes y cámaras de seguridad al mejor precio.
        </p>
      </div>
    </div>
  </div>

  <!-- SECCIÓN PRINCIPAL CON FILTROS Y GRILLA -->
  <section class="section-padding">
    <div class="container">
      
      <!-- BARRA DE BÚSQUEDA Y FILTROS RÁPIDOS -->
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem; background: #FFFFFF; padding: 1.25rem; border-radius: var(--radius-lg); border: 1px solid var(--color-border-light); box-shadow: var(--shadow-sm);">
        
        <!-- Formulario de Búsqueda -->
        <form action="<?= url('/tienda') ?>" method="GET" style="display: flex; gap: 0.5rem; flex: 1 1 300px;">
          <?php if ($activeCategory): ?>
            <input type="hidden" name="categoria" value="<?= (int)$activeCategory['id'] ?>">
          <?php endif; ?>
          <div style="position: relative; width: 100%;">
            <input type="text" 
                   name="buscar" 
                   value="<?= htmlspecialchars($buscar) ?>" 
                   placeholder="Buscar en el catálogo..." 
                   style="width: 100%; padding: 0.65rem 1rem 0.65rem 2.4rem; border: 1px solid var(--color-border-metallic); border-radius: var(--radius-md); font-family: var(--font-body); font-size: 0.9rem;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: var(--color-text-muted);"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          </div>
          <button type="submit" class="btn btn-primary btn-sm">Buscar</button>
        </form>

        <!-- Contador de Resultados -->
        <div style="font-size: 0.88rem; color: var(--color-text-secondary); font-weight: 600;">
          Mostrando <span style="color: var(--color-dark); font-weight: 700;"><?= count($products) ?></span> producto(s)
        </div>
      </div>

      <div style="display: grid; grid-template-columns: 260px 1fr; gap: 2rem;" class="grid-layout-catalogo">
        
        <!-- FILTROS LATERALES POR CATEGORÍA -->
        <aside style="background: #FFFFFF; border: 1px solid var(--color-border-light); border-radius: var(--radius-lg); padding: 1.5rem; height: fit-content; box-shadow: var(--shadow-sm);">
          <h3 style="font-size: 1.1rem; margin-bottom: 1.25rem; border-bottom: 2px solid var(--color-bg); padding-bottom: 0.5rem;">
            Categorías
          </h3>
          
          <ul style="display: flex; flex-direction: column; gap: 0.5rem;">
            <li>
              <a href="<?= url('/tienda') ?>" 
                 style="display: flex; justify-content: space-between; align-items: center; padding: 0.6rem 0.85rem; border-radius: var(--radius-md); font-weight: <?= !$activeCategory ? '700' : '500' ?>; color: <?= !$activeCategory ? 'var(--color-primary)' : 'var(--color-text-main)' ?>; background-color: <?= !$activeCategory ? 'var(--color-primary-light)' : 'transparent' ?>; text-decoration: none;">
                <span>Todas las categorías</span>
              </a>
            </li>
            <?php foreach ($categories as $cat): ?>
              <?php $isSelected = $activeCategory && (int)$activeCategory['id'] === (int)$cat['id']; ?>
              <li>
                <a href="<?= url('/tienda?categoria=' . (int)$cat['id']) ?>" 
                   style="display: flex; justify-content: space-between; align-items: center; padding: 0.6rem 0.85rem; border-radius: var(--radius-md); font-weight: <?= $isSelected ? '700' : '500' ?>; color: <?= $isSelected ? 'var(--color-primary)' : 'var(--color-text-main)' ?>; background-color: <?= $isSelected ? 'var(--color-primary-light)' : 'transparent' ?>; text-decoration: none;">
                  <span><?= htmlspecialchars($cat['name']) ?></span>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
        </aside>

        <!-- GRILLA DE PRODUCTOS -->
        <div>
          <?php if (empty($products)): ?>
            <div style="background: #FFFFFF; border: 1px solid var(--color-border-light); border-radius: var(--radius-lg); padding: 3rem; text-align: center;">
              <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="color: var(--color-text-muted); margin-bottom: 1rem;"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
              <h3 style="margin-bottom: 0.5rem; color: var(--color-dark);">No se encontraron productos</h3>
              <p style="color: var(--color-text-secondary); margin-bottom: 1.5rem;">Intenta con otros términos de búsqueda o selecciona otra categoría.</p>
              <a href="<?= url('/tienda') ?>" class="btn btn-outline-dark btn-sm">Ver Todo el Catálogo</a>
            </div>
          <?php else: ?>
            <div class="grid grid-3" style="gap: 1.5rem;">
              <?php foreach ($products as $p): ?>
                <?php 
                  $pId        = (int)$p['id'];
                  $pCode      = htmlspecialchars($p['code'] ?? '');
                  $pName      = htmlspecialchars($p['name']);
                  $pCat       = htmlspecialchars($p['category_name'] ?? 'Sin categoría');
                  $pPrice     = number_format((float)$p['price'], 2, '.', ',');
                  $inStock    = ((int)($p['stock'] ?? 0)) > 0;
                  
                  $rawImg     = !empty($p['images'][0]['image_url']) ? $p['images'][0]['image_url'] : null;
                  $pImg       = $rawImg ? (strpos($rawImg, 'http') === 0 ? htmlspecialchars($rawImg) : url($rawImg)) : $fallbackImg;
                  $pLink      = url('/producto/' . $pId);
                ?>
                <div class="product-card">
                  <div class="product-card-img-wrap">
                    <span class="product-card-code"><?= $pCode ?></span>
                    <a href="<?= $pLink ?>">
                      <img src="<?= $pImg ?>" alt="<?= $pName ?>" loading="lazy" onerror="this.src='<?= $fallbackImg ?>';">
                    </a>
                  </div>

                  <div class="product-card-body">
                    <div class="product-card-category"><?= $pCat ?></div>
                    <h3 class="product-card-title">
                      <a href="<?= $pLink ?>"><?= $pName ?></a>
                    </h3>

                    <div class="product-card-price-wrap">
                      <div>
                        <span class="product-card-currency">USD</span>
                        <span class="product-card-price">$<?= $pPrice ?></span>
                      </div>
                      <?php if ($inStock): ?>
                        <span class="badge-stock in-stock">En Stock</span>
                      <?php else: ?>
                        <span class="badge-stock out-of-stock">Agotado</span>
                      <?php endif; ?>
                    </div>

                    <div class="product-card-actions">
                      <a href="<?= $pLink ?>" class="btn btn-outline-dark btn-sm">Detalles</a>
                      <button type="button" 
                              class="btn btn-primary btn-sm btn-add-cart" 
                              data-id="<?= $pId ?>" 
                              data-code="<?= $pCode ?>" 
                              data-name="<?= $pName ?>" 
                              data-price="<?= (float)$p['price'] ?>" 
                              data-image="<?= $pImg ?>"
                              <?= !$inStock ? 'disabled' : '' ?>>
                        <?= $inStock ? '+ Agregar' : 'Sin Stock' ?>
                      </button>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>

      </div>

    </div>
  </section>

  <style>
  @media (max-width: 991px) {
    .grid-layout-catalogo {
      grid-template-columns: 1fr !important;
    }
  }
  </style>
<?php
};

require __DIR__ . '/../../../shared/Layout/layout.php';
