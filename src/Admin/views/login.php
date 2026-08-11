<?php
/**
 * RedTec Informática - Vista de Login del Panel de Administración
 * 
 * @var string|null $error Mensaje de error de autenticación si existe.
 * @var string $csrfToken Token CSRF de seguridad.
 */

$logoUrl = url('/assets/img/Logotipo PNG.png');
$isoUrl  = url('/assets/img/Iso PNG.png');
?>
<!DOCTYPE html>
<html lang="es-UY">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Acceso al Panel de Administración — RedTec Informática</title>
  <link rel="icon" type="image/png" href="<?= $isoUrl ?>">
  
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Montserrat:wght@600;700;800&display=swap" rel="stylesheet">
  
  <link rel="stylesheet" href="<?= url('/assets/css/variables.css') ?>">
  <link rel="stylesheet" href="<?= url('/assets/css/base.css') ?>">
  
  <style>
    body {
      background-color: var(--color-dark);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1.5rem;
      color: #FFFFFF;
    }
    
    .login-card {
      background: #2B1C17;
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: var(--radius-lg);
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.4);
      width: 100%;
      max-width: 420px;
      padding: 2.5rem;
      border-top: 4px solid var(--color-primary);
    }
    
    .login-header {
      text-align: center;
      margin-bottom: 2rem;
    }

    .login-header img {
      max-width: 170px;
      height: auto;
      margin-bottom: 1rem;
    }

    .login-header h1 {
      font-size: 1.25rem;
      color: #FFFFFF;
      margin-bottom: 0.25rem;
    }

    .login-header p {
      font-size: 0.85rem;
      color: #B0B0B0;
      margin-bottom: 0;
    }

    .form-group {
      margin-bottom: 1.25rem;
    }

    .form-group label {
      display: block;
      font-family: var(--font-heading);
      font-size: 0.85rem;
      font-weight: 600;
      color: #D2D2D2;
      margin-bottom: 0.4rem;
    }

    .form-control {
      width: 100%;
      padding: 0.75rem 1rem;
      background-color: #1F130F;
      border: 1px solid rgba(255, 255, 255, 0.15);
      border-radius: var(--radius-md);
      color: #FFFFFF;
      font-family: var(--font-body);
      font-size: 0.95rem;
      outline: none;
      transition: border-color 0.2s;
    }

    .form-control:focus {
      border-color: var(--color-primary);
    }

    .alert-error {
      background-color: rgba(227, 69, 73, 0.15);
      border: 1px solid var(--color-primary);
      color: #FF8E91;
      padding: 0.75rem 1rem;
      border-radius: var(--radius-md);
      font-size: 0.85rem;
      margin-bottom: 1.5rem;
      text-align: center;
    }

    .btn-login {
      width: 100%;
      padding: 0.85rem;
      background-color: var(--color-primary);
      color: #FFFFFF;
      border: none;
      border-radius: var(--radius-md);
      font-family: var(--font-heading);
      font-size: 0.95rem;
      font-weight: 700;
      cursor: pointer;
      transition: background-color 0.2s;
    }

    .btn-login:hover {
      background-color: var(--color-primary-hover);
    }

    .login-footer {
      margin-top: 1.75rem;
      text-align: center;
      font-size: 0.8rem;
      color: #888888;
    }

    .login-footer a {
      color: var(--color-primary);
      text-decoration: none;
    }
  </style>
</head>
<body>

  <div class="login-card">
    
    <div class="login-header">
      <img src="<?= $logoUrl ?>" alt="RedTec Informática Logo">
      <h1>Panel de Administración</h1>
      <p>Ingrese sus credenciales de acceso</p>
    </div>

    <?php if (!empty($error)): ?>
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
               placeholder="admin@redtecinformatica.com" 
               required 
               autofocus 
               class="form-control">
      </div>

      <div class="form-group">
        <label for="password">Contraseña</label>
        <input type="password" 
               id="password" 
               name="password" 
               placeholder="••••••••" 
               required 
               class="form-control">
      </div>

      <button type="submit" class="btn-login">Ingresar al Panel</button>
    </form>

    <div class="login-footer">
      &copy; <?= date('Y') ?> <strong>RedTec Informática</strong> &bull; <a href="<?= url('/') ?>">Volver al sitio público</a>
    </div>

  </div>

</body>
</html>
