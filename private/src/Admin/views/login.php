<?php
/**
 * RedTec Informática - Vista de Autenticación / Login al Panel de Administración
 * 
 * @var string|null $error Mensaje de error si la validación falla.
 * @var string $csrfToken Token CSRF para el formulario.
 */

$error    = $error ?? null;
$csrfToken = $csrfToken ?? \RedTec\Admin\AdminGuard::csrfToken();
$logoUrl  = url('/assets/img/Logotipo PNG.png');
$isoUrl   = url('/assets/img/Iso PNG.png');
?>
<!DOCTYPE html>
<html lang="es-UY">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Acceso al Panel de Administración — RedTec Informática</title>
  <meta name="robots" content="noindex, nofollow">
  <link rel="icon" type="image/png" href="<?= $isoUrl ?>">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Montserrat:wght@600;700;800&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="<?= url('/assets/css/variables.css') ?>">
  <link rel="stylesheet" href="<?= url('/assets/css/base.css') ?>">
  <link rel="stylesheet" href="<?= url('/assets/css/components.css') ?>">

  <style>
    body {
      background: linear-gradient(135deg, var(--color-dark) 0%, #2A1712 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1.5rem;
    }

    .login-card {
      background: #FFFFFF;
      width: 100%;
      max-width: 420px;
      border-radius: var(--radius-lg);
      box-shadow: 0 16px 40px rgba(0, 0, 0, 0.4);
      padding: 2.5rem;
      border-top: 4px solid var(--color-primary);
    }

    .login-brand {
      text-align: center;
      margin-bottom: 2rem;
    }

    .login-brand img {
      height: 52px;
      width: auto;
      margin: 0 auto 1rem auto;
      object-fit: contain;
    }

    .login-brand h1 {
      font-size: 1.3rem;
      color: var(--color-dark);
      margin-bottom: 0.25rem;
      font-weight: 800;
    }

    .login-brand p {
      font-size: 0.85rem;
      color: var(--color-text-secondary);
      margin-bottom: 0;
    }

    .form-group {
      margin-bottom: 1.25rem;
    }

    .form-group label {
      display: block;
      font-family: var(--font-heading);
      font-size: 0.85rem;
      font-weight: 700;
      color: var(--color-dark);
      margin-bottom: 0.35rem;
    }

    .form-group input {
      width: 100%;
      padding: 0.75rem 1rem;
      border: 1px solid var(--color-border-metallic);
      border-radius: var(--radius-md);
      font-family: var(--font-body);
      font-size: 0.95rem;
      transition: border-color var(--transition-fast);
    }

    .form-group input:focus {
      outline: none;
      border-color: var(--color-primary);
      box-shadow: 0 0 0 3px var(--color-primary-glow);
    }

    .alert-error {
      background-color: #FEE2E2;
      color: #991B1B;
      padding: 0.75rem 1rem;
      border-radius: var(--radius-md);
      font-size: 0.875rem;
      font-weight: 600;
      margin-bottom: 1.5rem;
      border: 1px solid #FCA5A5;
      text-align: center;
    }
  </style>
</head>
<body>

<div class="login-card">
  
  <div class="login-brand">
    <img src="<?= $logoUrl ?>" alt="RedTec Informática">
    <h1>Panel de Administración</h1>
    <p>Ingresá tus credenciales para gestionar el sitio</p>
  </div>

  <?php if ($error): ?>
    <div class="alert-error">
      <?= htmlspecialchars($error) ?>
    </div>
  <?php endif; ?>

  <form action="<?= url('/admin/login') ?>" method="POST">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

    <div class="form-group">
      <label for="email">Correo Electrónico</label>
      <input type="email" 
             id="email" 
             name="email" 
             required 
             placeholder="admin@redtecinformatica.com" 
             value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
             autocomplete="email">
    </div>

    <div class="form-group" style="margin-bottom: 1.75rem;">
      <label for="password">Contraseña</label>
      <input type="password" 
             id="password" 
             name="password" 
             required 
             placeholder="••••••••" 
             autocomplete="current-password">
    </div>

    <button type="submit" class="btn btn-primary btn-lg btn-block">
      Iniciar Sesión
    </button>
  </form>

  <div style="margin-top: 1.5rem; text-align: center;">
    <a href="<?= url('/') ?>" style="font-size: 0.85rem; color: var(--color-text-muted); text-decoration: none;">
      &larr; Volver al sitio público
    </a>
  </div>

</div>

</body>
</html>
