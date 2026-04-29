<?php if (!empty($error)): ?>
<script>document.addEventListener('DOMContentLoaded',()=>document.getElementById('error-msg').style.display='block')</script>
<?php endif; ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Iniciar sesión — Repuestos Sistema</title>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: system-ui, -apple-system, sans-serif;
      background: #f4f5f7;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .card {
      background: #fff;
      border: 1px solid #e2e4e8;
      border-radius: 14px;
      padding: 36px 32px;
      width: 100%;
      max-width: 380px;
    }
    .logo {
      width: 46px; height: 46px;
      background: #185FA5;
      border-radius: 12px;
      display: flex; align-items: center; justify-content: center;
      margin: 0 auto 18px;
    }
    .logo svg {
      width: 24px; height: 24px;
      fill: none; stroke: #fff;
      stroke-width: 2; stroke-linecap: round;
    }
    h1 {
      text-align: center;
      font-size: 18px; font-weight: 600;
      color: #1a1a2e; margin-bottom: 4px;
    }
    .subtitle {
      text-align: center; font-size: 13px;
      color: #888; margin-bottom: 28px;
    }
    .form-group { margin-bottom: 16px; }
    label {
      display: block; font-size: 12px;
      font-weight: 500; color: #555; margin-bottom: 6px;
    }
    input[type="text"],
    input[type="password"] {
      width: 100%; height: 40px;
      border: 1px solid #dde1e7;
      border-radius: 8px;
      padding: 0 14px;
      font-size: 14px; color: #1a1a2e;
      background: #fff;
      transition: border-color .2s;
    }
    input:focus {
      outline: none;
      border-color: #185FA5;
      box-shadow: 0 0 0 3px rgba(24,95,165,.1);
    }
    .btn {
      width: 100%; height: 42px;
      background: #185FA5; color: #fff;
      border: none; border-radius: 8px;
      font-size: 14px; font-weight: 600;
      cursor: pointer; margin-top: 8px;
      transition: background .2s;
    }
    .btn:hover { background: #0C447C; }
    .alert {
      display: none;
      background: #fef2f2;
      border: 1px solid #fecaca;
      color: #dc2626;
      border-radius: 8px;
      padding: 10px 14px;
      font-size: 13px;
      margin-bottom: 16px;
    }
    .footer {
      text-align: center;
      font-size: 11px; color: #aaa;
      margin-top: 22px;
    }
  </style>
</head>
<body>
<div class="card">
  <div class="logo">
    <svg viewBox="0 0 24 24">
      <path d="M12 2L2 7l10 5 10-5-10-5z"/>
      <path d="M2 17l10 5 10-5"/>
      <path d="M2 12l10 5 10-5"/>
    </svg>
  </div>
  <h1>Repuestos Sistema</h1>
  <p class="subtitle">Ingresá tus credenciales para continuar</p>

  <div class="alert" id="error-msg">
    <?= htmlspecialchars($error ?? '') ?>
  </div>

  <form method="POST" action="<?= BASE ?>/login">
    <div class="form-group">
      <label for="username">Usuario</label>
      <input
        type="text"
        id="username"
        name="username"
        value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
        autocomplete="username"
        placeholder="Ej: admin"
        required
      >
    </div>
    <div class="form-group">
      <label for="password">Contraseña</label>
      <input
        type="password"
        id="password"
        name="password"
        autocomplete="current-password"
        placeholder="••••••••"
        required
      >
    </div>
    <button class="btn" type="submit">Iniciar sesión</button>
  </form>

  <div class="footer">Sistema de gestión de repuestos v1.0</div>
</div>
</body>
</html>