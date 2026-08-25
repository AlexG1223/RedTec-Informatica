<?php
/**
 * RedTec Informática - Vista del Catálogo de Productos (Tienda)
 * Con Buscador en Tiempo Real por Categoría y Coincidencia Instantánea
 * 
 * @var array $products Lista de productos filtrados traídos del repositorio.
 * @var array $categories Lista de categorías activas para el filtro.
 * @var array|null $activeCategory Categoría actualmente seleccionada (si aplica).
 * @var string $buscar Término de búsqueda (si aplica).
 */

$content = function() use ($products, $categories, $featuredCategories, $activeCategory, $buscar) {
    $fallbackImg = url('/assets/img/redtec.jpeg');
    $displayFeatured = !empty($featuredCategories) ? $featuredCategories : $categories;
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
      
      <!-- GRILLA DE TARJETAS DE CATEGORÍAS CON OVERLAY -->
      <?php if (!$activeCategory && empty($buscar)): ?>
        <div style="margin-bottom: 2.5rem;">
          <h2 style="font-size: 1.3rem; font-family: var(--font-heading); font-weight: 800; color: var(--color-dark); margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem;">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--color-primary)" stroke-width="2.5"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/></svg>
            Explorar Categorías Destacadas
          </h2>

          <div class="category-overlay-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 1.25rem;">
            <?php foreach ($displayFeatured as $cat): ?>
              <?php 
                $catId   = (int)$cat['id'];
                $catName = mb_strtoupper(htmlspecialchars($cat['name']), 'UTF-8');
                
                $rawImg = !empty($cat['image_url']) ? $cat['image_url'] : null;
                if (!$rawImg) {
                    $lower = mb_strtolower($cat['name'], 'UTF-8');
                    if (strpos($lower, 'notebook') !== false || strpos($lower, 'equipo') !== false || strpos($lower, 'pc') !== false) {
                        $rawImg = '/assets/img/categories/notebooks.jpg';
                    } elseif (strpos($lower, 'red') !== false || strpos($lower, 'wifi') !== false || strpos($lower, 'wi-fi') !== false || strpos($lower, 'conectividad') !== false) {
                        $rawImg = '/assets/img/categories/redes.jpg';
                    } elseif (strpos($lower, 'cámara') !== false || strpos($lower, 'camara') !== false || strpos($lower, 'cctv') !== false || strpos($lower, 'seguridad') !== false) {
                        $rawImg = '/assets/img/categories/camaras.jpg';
                    } elseif (strpos($lower, 'accesorio') !== false) {
                        $rawImg = '/assets/img/categories/accesorios.jpg';
                    } else {
                        $rawImg = '/assets/img/categories/notebooks.jpg';
                    }
                }
                $catImg  = strpos($rawImg, 'http') === 0 ? htmlspecialchars($rawImg) : url($rawImg);
                $catLink = url('/tienda?categoria=' . $catId);
              ?>
              <a href="<?= $catLink ?>" class="category-overlay-card" style="height: 150px; border-radius: var(--radius-lg); overflow: hidden; position: relative; display: flex; align-items: flex-end; padding: 1.25rem; text-decoration: none; box-shadow: var(--shadow-md); transition: transform 0.25s ease, box-shadow 0.25s ease;">
                <img src="<?= $catImg ?>" alt="<?= $catName ?>" class="category-overlay-bg" style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; filter: brightness(0.65) contrast(1.15); transition: transform 0.3s ease;" onerror="this.src='<?= $fallbackImg ?>';">
                <div class="category-overlay-gradient" style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.3) 65%, transparent 100%);"></div>
                <div class="category-overlay-content" style="position: relative; z-index: 2; width: 100%;">
                  <h3 class="category-overlay-title" style="color: #FFFFFF; font-family: var(--font-heading); font-size: 1.05rem; font-weight: 900; margin-bottom: 0.25rem; text-shadow: 0 2px 4px rgba(0,0,0,0.7); line-height: 1.25; tracking: 0.02em;"><?= $catName ?></h3>
                  <span class="category-overlay-subtitle" style="color: var(--color-primary); font-size: 0.78rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.06em; display: inline-flex; align-items: center; gap: 0.25rem;">
                    VER EQUIPOS 
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="9 18 15 12 9 6"/></svg>
                  </span>
                </div>
              </a>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endif; ?>

      <!-- BARRA DE BÚSQUEDA EN TIEMPO REAL -->
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem; background: #FFFFFF; padding: 1.25rem; border-radius: var(--radius-lg); border: 1px solid var(--color-border-light); box-shadow: var(--shadow-sm);">
        
        <!-- Formulario e Input de Búsqueda Instantánea -->
        <form id="searchForm" action="<?= url('/tienda') ?>" method="GET" style="display: flex; gap: 0.5rem; flex: 1 1 300px; position: relative;">
          <?php if ($activeCategory): ?>
            <input type="hidden" name="categoria" value="<?= (int)$activeCategory['id'] ?>">
          <?php endif; ?>
          <div style="position: relative; width: 100%;">
            <input type="text" 
                   id="searchInput"
                   name="buscar" 
                   value="<?= htmlspecialchars($buscar) ?>" 
                   placeholder="Buscar en <?= $activeCategory ? htmlspecialchars($activeCategory['name']) : 'el catálogo' ?>..." 
                   autocomplete="off"
                   style="width: 100%; padding: 0.65rem 2.4rem 0.65rem 2.4rem; border: 1px solid var(--color-border-metallic); border-radius: var(--radius-md); font-family: var(--font-body); font-size: 0.9rem;">
            
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: var(--color-text-muted);"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            
            <button type="button" id="clearSearchBtn" style="display: none; position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: transparent; border: none; color: #999; cursor: pointer; font-size: 1.2rem; line-height: 1; padding: 2px 6px;" title="Limpiar búsqueda">&times;</button>
          </div>
          <button type="submit" class="btn btn-primary btn-sm">Buscar</button>
        </form>

        <!-- Contador Dinámico de Resultados -->
        <div style="font-size: 0.88rem; color: var(--color-text-secondary); font-weight: 600;">
          Mostrando <span id="productCountNumber" style="color: var(--color-dark); font-weight: 700;"><?= count($products) ?></span> producto(s)
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

        <!-- CONTENEDOR PRINCIPAL DE PRODUCTOS -->
        <div>
          <!-- Mensaje Sin Resultados en Tiempo Real -->
          <div id="noResultsMessage" style="display: <?= empty($products) ? 'block' : 'none' ?>; background: #FFFFFF; border: 1px solid var(--color-border-light); border-radius: var(--radius-lg); padding: 3rem; text-align: center;">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="color: var(--color-text-muted); margin-bottom: 1rem;"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <h3 style="margin-bottom: 0.5rem; color: var(--color-dark);">No se encontraron productos</h3>
            <p id="noResultsText" style="color: var(--color-text-secondary); margin-bottom: 1.5rem;">Intenta con otros términos de búsqueda o selecciona otra categoría.</p>
            <button type="button" id="resetSearchBtn" class="btn btn-outline-dark btn-sm">Ver Todo en <?= $activeCategory ? htmlspecialchars($activeCategory['name']) : 'el Catálogo' ?></button>
          </div>

          <?php if (!empty($products)): ?>
            <div id="productGridContainer" class="grid grid-3" style="gap: 1.5rem;">
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
                <div class="product-card product-card-item" 
                     data-name="<?= htmlspecialchars(mb_strtolower($p['name'], 'UTF-8')) ?>" 
                     data-code="<?= htmlspecialchars(mb_strtolower($p['code'] ?? '', 'UTF-8')) ?>" 
                     data-category="<?= htmlspecialchars(mb_strtolower($p['category_name'] ?? '', 'UTF-8')) ?>"
                     data-description="<?= htmlspecialchars(mb_strtolower(strip_tags($p['description'] ?? ''), 'UTF-8')) ?>">
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
                        <span class="product-card-currency">$</span>
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

  <!-- LÓGICA DE BÚSQUEDA EN TIEMPO REAL -->
  <script>
  document.addEventListener('DOMContentLoaded', function() {
    const searchInput    = document.getElementById('searchInput');
    const searchForm     = document.getElementById('searchForm');
    const clearBtn       = document.getElementById('clearSearchBtn');
    const countSpan      = document.getElementById('productCountNumber');
    const productGrid    = document.getElementById('productGridContainer');
    const noResultsDiv   = document.getElementById('noResultsMessage');
    const noResultsText  = document.getElementById('noResultsText');
    const resetBtn       = document.getElementById('resetSearchBtn');
    const productCards   = document.querySelectorAll('.product-card-item');

    function removeAccents(str) {
      return String(str || '')
        .normalize("NFD")
        .replace(/[\u0300-\u036f]/g, "")
        .toLowerCase()
        .trim();
    }

    function filterProducts() {
      const rawQuery = searchInput ? searchInput.value : '';
      const query = removeAccents(rawQuery);

      if (clearBtn) {
        clearBtn.style.display = query.length > 0 ? 'block' : 'none';
      }

      let visibleCount = 0;

      productCards.forEach(function(card) {
        const name        = removeAccents(card.dataset.name);
        const code        = removeAccents(card.dataset.code);
        const category    = removeAccents(card.dataset.category);
        const description = removeAccents(card.dataset.description);

        const matches = query === '' || 
                        name.includes(query) || 
                        code.includes(query) || 
                        category.includes(query) ||
                        description.includes(query);

        if (matches) {
          card.style.display = 'flex';
          visibleCount++;
        } else {
          card.style.display = 'none';
        }
      });

      if (countSpan) {
        countSpan.textContent = visibleCount;
      }

      if (noResultsDiv) {
        if (visibleCount === 0 && productCards.length > 0) {
          if (productGrid) productGrid.style.display = 'none';
          noResultsDiv.style.display = 'block';
          if (noResultsText) {
            noResultsText.textContent = `No se encontraron productos coincidentes para "${rawQuery}".`;
          }
        } else if (visibleCount > 0) {
          if (productGrid) productGrid.style.display = 'grid';
          noResultsDiv.style.display = 'none';
        }
      }
    }

    if (searchInput) {
      // Filtrado instantáneo en vivo en cada pulsación de tecla
      searchInput.addEventListener('input', filterProducts);
      searchInput.addEventListener('keyup', filterProducts);
      
      if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
          e.preventDefault();
          filterProducts();
        });
      }
    }

    if (clearBtn) {
      clearBtn.addEventListener('click', function() {
        if (searchInput) {
          searchInput.value = '';
          searchInput.focus();
          filterProducts();
        }
      });
    }

    if (resetBtn) {
      resetBtn.addEventListener('click', function() {
        if (searchInput) {
          searchInput.value = '';
          searchInput.focus();
          filterProducts();
        }
      });
    }

    // Ejecutar filtro inicial si el input ya contenía texto
    filterProducts();
  });
  </script>

  <style>
  @media (max-width: 991px) {
    .grid-layout-catalogo {
      grid-template-columns: 1fr !important;
    }
  }
  </style>
<?php
};

require REDTEC_SHARED_DIR . '/Layout/layout.php';
