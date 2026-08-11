<?php
/**
 * RedTec Informática - Styleguide & Vista Previa del Sistema de Diseño
 */

$pageTitle       = "Guía de Estilos & Sistema de Diseño | RedTec Informática";
$pageDescription = "Muestra interactiva de paleta de colores, tipografías, botones, badges y cards de producto de RedTec.";
$currentPage     = "styleguide";
$cartCount       = 3;

$content = function() {
?>
  <!-- Banner Héroe Styleguide -->
  <section style="background-color: var(--color-dark); color: #FFFFFF; padding: 3rem 0; border-bottom: 4px solid var(--color-primary);">
    <div class="container">
      <span style="font-family: var(--font-heading); font-size: 0.85rem; font-weight: 700; color: var(--color-primary); text-transform: uppercase; letter-spacing: 0.08em;">Fase 2 — Sistema de Diseño</span>
      <h1 style="color: #FFFFFF; margin-top: 0.5rem; margin-bottom: 0.5rem;">Manual de Estilos & Componentes Base</h1>
      <p style="color: #B0B0B0; max-width: 680px; margin-bottom: 0;">
        Demostración visual de tokens, tipografías, botones, badges de stock y tarjetas de catálogo para la plataforma web de RedTec Informática.
      </p>
    </div>
  </section>

  <div class="container section-padding">
    
    <!-- SECCIÓN 1: PALETA DE COLORES -->
    <section style="margin-bottom: 4rem;">
      <h2>1. Paleta de Colores de Marca</h2>
      <p>Variables CSS centralizadas en <code>variables.css</code> como fuente única de verdad.</p>
      
      <div class="grid grid-4" style="margin-top: 1.5rem;">
        
        <!-- Primario -->
        <div style="background: var(--color-card-bg); border: 1px solid var(--color-border-light); border-radius: var(--radius-md); overflow: hidden; box-shadow: var(--shadow-sm);">
          <div style="height: 100px; background-color: var(--color-primary);"></div>
          <div style="padding: 1rem;">
            <strong>Primario (Rojo)</strong>
            <div style="font-size: 0.85rem; color: var(--color-text-secondary);">#E34549</div>
            <code style="font-size: 0.75rem;">--color-primary</code>
          </div>
        </div>

        <!-- Oscuro -->
        <div style="background: var(--color-card-bg); border: 1px solid var(--color-border-light); border-radius: var(--radius-md); overflow: hidden; box-shadow: var(--shadow-sm);">
          <div style="height: 100px; background-color: var(--color-dark);"></div>
          <div style="padding: 1rem;">
            <strong>Oscuro Base</strong>
            <div style="font-size: 0.85rem; color: var(--color-text-secondary);">#1F130F</div>
            <code style="font-size: 0.75rem;">--color-dark</code>
          </div>
        </div>

        <!-- Fondo Claro -->
        <div style="background: var(--color-card-bg); border: 1px solid var(--color-border-light); border-radius: var(--radius-md); overflow: hidden; box-shadow: var(--shadow-sm);">
          <div style="height: 100px; background-color: var(--color-bg); border-bottom: 1px solid var(--color-border-light);"></div>
          <div style="padding: 1rem;">
            <strong>Fondo Claro</strong>
            <div style="font-size: 0.85rem; color: var(--color-text-secondary);">#F8F9FA</div>
            <code style="font-size: 0.75rem;">--color-bg</code>
          </div>
        </div>

        <!-- Texto Principal -->
        <div style="background: var(--color-card-bg); border: 1px solid var(--color-border-light); border-radius: var(--radius-md); overflow: hidden; box-shadow: var(--shadow-sm);">
          <div style="height: 100px; background-color: var(--color-text-main);"></div>
          <div style="padding: 1rem;">
            <strong>Texto Principal</strong>
            <div style="font-size: 0.85rem; color: var(--color-text-secondary);">#222222</div>
            <code style="font-size: 0.75rem;">--color-text-main</code>
          </div>
        </div>

        <!-- Gris Metálico 1 -->
        <div style="background: var(--color-card-bg); border: 1px solid var(--color-border-light); border-radius: var(--radius-md); overflow: hidden; box-shadow: var(--shadow-sm);">
          <div style="height: 100px; background-color: var(--color-border-metallic);"></div>
          <div style="padding: 1rem;">
            <strong>Gris Metálico (Bordes)</strong>
            <div style="font-size: 0.85rem; color: var(--color-text-secondary);">#B0B0B0</div>
            <code style="font-size: 0.75rem;">--color-border-metallic</code>
          </div>
        </div>

        <!-- Gris Metálico 2 -->
        <div style="background: var(--color-card-bg); border: 1px solid var(--color-border-light); border-radius: var(--radius-md); overflow: hidden; box-shadow: var(--shadow-sm);">
          <div style="height: 100px; background-color: var(--color-border-light);"></div>
          <div style="padding: 1rem;">
            <strong>Gris Claro (Líneas)</strong>
            <div style="font-size: 0.85rem; color: var(--color-text-secondary);">#E2E2E2</div>
            <code style="font-size: 0.75rem;">--color-border-light</code>
          </div>
        </div>

        <!-- Stock Disponible -->
        <div style="background: var(--color-card-bg); border: 1px solid var(--color-border-light); border-radius: var(--radius-md); overflow: hidden; box-shadow: var(--shadow-sm);">
          <div style="height: 100px; background-color: var(--color-stock-in);"></div>
          <div style="padding: 1rem;">
            <strong>Verde Stock</strong>
            <div style="font-size: 0.85rem; color: var(--color-text-secondary);">#28A745</div>
            <code style="font-size: 0.75rem;">--color-stock-in</code>
          </div>
        </div>

        <!-- WhatsApp -->
        <div style="background: var(--color-card-bg); border: 1px solid var(--color-border-light); border-radius: var(--radius-md); overflow: hidden; box-shadow: var(--shadow-sm);">
          <div style="height: 100px; background-color: var(--color-whatsapp);"></div>
          <div style="padding: 1rem;">
            <strong>Verde WhatsApp</strong>
            <div style="font-size: 0.85rem; color: var(--color-text-secondary);">#25D366</div>
            <code style="font-size: 0.75rem;">--color-whatsapp</code>
          </div>
        </div>

      </div>
    </section>

    <!-- SECCIÓN 2: TIPOGRAFÍA -->
    <section style="margin-bottom: 4rem;">
      <h2>2. Jerarquía Tipográfica</h2>
      <p>Titulares en <strong>Montserrat</strong> (Semi-Bold/Bold) y cuerpo de texto en <strong>Inter</strong>.</p>
      
      <div style="background: #FFFFFF; padding: 2rem; border-radius: var(--radius-lg); border: 1px solid var(--color-border-light); box-shadow: var(--shadow-sm);">
        <h1>H1 — Encabezado Principal de Sección (Montserrat 800)</h1>
        <h2>H2 — Título de Sección Secundaria (Montserrat 700)</h2>
        <h3>H3 — Título de Módulo o Producto (Montserrat 600)</h3>
        <h4>H4 — Subtítulo de Componentes o Tarjetas (Montserrat 600)</h4>
        <p style="font-size: 1.05rem;">
          <strong>Texto Destacado (Inter):</strong> RedTec Informática ofrece soluciones integrales para empresas y particulares. Equipamiento de redes, cableado estructurado Cat6, servidores de datos y sistemas de cámaras IP con soporte local.
        </p>
        <p style="font-size: 0.9375rem;">
          <strong>Cuerpo Estándar (Inter):</strong> Los componentes están construidos con unidades de medida relativas (rem/em) garantizando adaptabilidad en todos los dispositivos desde smartphones hasta pantallas de alta resolución.
        </p>
      </div>
    </section>

    <!-- SECCIÓN 3: BOTONES Y BADGES -->
    <section style="margin-bottom: 4rem;">
      <h2>3. Botones y Badges de Stock</h2>
      
      <div style="background: #FFFFFF; padding: 2rem; border-radius: var(--radius-lg); border: 1px solid var(--color-border-light); box-shadow: var(--shadow-sm); display: flex; flex-direction: column; gap: 2rem;">
        
        <div>
          <h4 style="margin-bottom: 1rem;">Estilos de Botones</h4>
          <div style="display: flex; flex-wrap: wrap; gap: 1rem; align-items: center;">
            <button class="btn btn-primary">Botón Primario</button>
            <button class="btn btn-secondary">Botón Secundario</button>
            <button class="btn btn-outline">Botón Outline</button>
            <button class="btn btn-outline-dark">Outline Oscuro</button>
            <button class="btn btn-primary" disabled>Deshabilitado</button>
          </div>
        </div>

        <div>
          <h4 style="margin-bottom: 1rem;">Tamaños de Botones</h4>
          <div style="display: flex; flex-wrap: wrap; gap: 1rem; align-items: center;">
            <button class="btn btn-primary btn-sm">Botón Pequeño (SM)</button>
            <button class="btn btn-primary">Botón Normal</button>
            <button class="btn btn-primary btn-lg">Botón Grande (LG)</button>
          </div>
        </div>

        <div>
          <h4 style="margin-bottom: 1rem;">Badges de Estado de Stock</h4>
          <div style="display: flex; gap: 1.5rem; align-items: center;">
            <span class="badge-stock in-stock">En Stock</span>
            <span class="badge-stock out-of-stock">Sin Stock</span>
          </div>
        </div>

      </div>
    </section>

    <!-- SECCIÓN 4: TARJETAS DE PRODUCTO DE EJEMPLO -->
    <section style="margin-bottom: 4rem;">
      <h2>4. Cards de Producto (Catálogo)</h2>
      <p>Tarjetas interactivas con sombra hover, tag de categoría, estado de stock y acciones.</p>

      <div class="grid grid-3" style="margin-top: 1.5rem;">
        
        <!-- Card 1: En Stock -->
        <div class="product-card">
          <div class="product-card-img-wrap">
            <span class="product-card-badge">
              <span class="badge-stock in-stock">En Stock</span>
            </span>
            <span class="product-card-code">SW-TP-G108</span>
            <img src="/assets/img/redtec.jpeg" alt="Switch TP-Link 8 Puertos Gigabit">
          </div>
          <div class="product-card-body">
            <div class="product-card-category">Redes y Servidores</div>
            <h3 class="product-card-title">
              <a href="#">Switch TP-Link 8 Puertos Gigabit 10/100/1000 Mbps</a>
            </h3>
            <div class="product-card-price-wrap">
              <span class="product-card-currency">USD</span>
              <span class="product-card-price">45.00</span>
            </div>
            <div class="product-card-actions">
              <button class="btn btn-outline btn-sm">Ver Detalle</button>
              <button class="btn btn-primary btn-sm">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                Agregar
              </button>
            </div>
          </div>
        </div>

        <!-- Card 2: En Stock -->
        <div class="product-card">
          <div class="product-card-img-wrap">
            <span class="product-card-badge">
              <span class="badge-stock in-stock">En Stock</span>
            </span>
            <span class="product-card-code">CAM-HIK-2MP</span>
            <img src="/assets/img/redtec.jpeg" alt="Cámara IP Hikvision 2MP Exterior">
          </div>
          <div class="product-card-body">
            <div class="product-card-category">Cámaras y Seguridad</div>
            <h3 class="product-card-title">
              <a href="#">Cámara IP Hikvision 2MP Exterior Full HD IR 30m</a>
            </h3>
            <div class="product-card-price-wrap">
              <span class="product-card-currency">USD</span>
              <span class="product-card-price">79.00</span>
            </div>
            <div class="product-card-actions">
              <button class="btn btn-outline btn-sm">Ver Detalle</button>
              <button class="btn btn-primary btn-sm">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                Agregar
              </button>
            </div>
          </div>
        </div>

        <!-- Card 3: Sin Stock -->
        <div class="product-card">
          <div class="product-card-img-wrap">
            <span class="product-card-badge">
              <span class="badge-stock out-of-stock">Sin Stock</span>
            </span>
            <span class="product-card-code">TECL-LOG-K120</span>
            <img src="/assets/img/redtec.jpeg" alt="Teclado USB Logitech K120">
          </div>
          <div class="product-card-body">
            <div class="product-card-category">Periféricos</div>
            <h3 class="product-card-title">
              <a href="#">Teclado USB Logitech K120 Español</a>
            </h3>
            <div class="product-card-price-wrap">
              <span class="product-card-currency">USD</span>
              <span class="product-card-price">18.00</span>
            </div>
            <div class="product-card-actions">
              <button class="btn btn-outline btn-sm">Ver Detalle</button>
              <button class="btn btn-primary btn-sm" disabled>Agotado</button>
            </div>
          </div>
        </div>

      </div>
    </section>

  </div>
<?php
};

require __DIR__ . '/../shared/Layout/layout.php';
