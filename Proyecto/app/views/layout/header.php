<?php
if (empty($_SESSION['usuario'])) {
    header('Location: ' . BASE . '/login');
    exit;
}

$usuario = $_SESSION['usuario'];

$rutaActual = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$rutaActual = str_replace(BASE, '', $rutaActual);
$rutaActual = rtrim($rutaActual, '/') ?: '/';

function esActivo(string $ruta, string $actual): string {
    return str_starts_with($actual, $ruta) ? 'active' : '';
}

$partes    = explode(' ', trim($usuario['nombre']));
$iniciales = strtoupper(
    substr($partes[0], 0, 1) .
    (isset($partes[1]) ? substr($partes[1], 0, 1) : '')
);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($titulo ?? 'Dashboard') ?> — Repuestos</title>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: system-ui, -apple-system, sans-serif;
      background: #f4f5f7;
      display: flex;
      min-height: 100vh;
    }

    /* ===================== SIDEBAR ===================== */
    .sidebar {
      width: 215px;
      flex-shrink: 0;
      background: #0C447C;
      display: flex;
      flex-direction: column;
      position: fixed;
      top: 0; left: 0;
      height: 100vh;
      z-index: 100;
      overflow-y: auto;
    }

    .sidebar-brand {
      padding: 20px 16px 16px;
      border-bottom: 1px solid rgba(255,255,255,.1);
      flex-shrink: 0;
    }
    .sidebar-brand-name {
      font-size: 15px; font-weight: 700;
      color: #fff; letter-spacing: .3px;
    }
    .sidebar-brand-sub {
      font-size: 11px;
      color: rgba(255,255,255,.45);
      margin-top: 3px;
    }

    .nav-section {
      font-size: 10px; font-weight: 600;
      color: rgba(255,255,255,.3);
      letter-spacing: .09em;
      text-transform: uppercase;
      padding: 18px 16px 5px;
    }

    .nav-item {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 9px 16px;
      font-size: 13px;
      color: rgba(255,255,255,.65);
      text-decoration: none;
      border-left: 3px solid transparent;
      transition: background .15s, color .15s;
    }
    .nav-item:hover {
      background: rgba(255,255,255,.07);
      color: #fff;
    }
    .nav-item.active {
      background: rgba(255,255,255,.13);
      color: #fff;
      border-left-color: #9FE1CB;
    }

    .nav-icon {
      width: 16px; height: 16px;
      flex-shrink: 0;
      opacity: .6;
    }
    .nav-item.active .nav-icon { opacity: 1; }

    .sidebar-bottom {
      margin-top: auto;
      padding: 14px 16px;
      border-top: 1px solid rgba(255,255,255,.1);
      flex-shrink: 0;
    }
    .user-chip { display: flex; align-items: center; gap: 9px; }
    .user-avatar {
      width: 32px; height: 32px;
      border-radius: 50%;
      background: #378ADD;
      display: flex; align-items: center; justify-content: center;
      font-size: 12px; font-weight: 700;
      color: #fff; flex-shrink: 0;
    }
    .user-chip-name {
      font-size: 12px;
      color: rgba(255,255,255,.9);
      font-weight: 500;
      line-height: 1.3;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    .user-chip-rol {
      font-size: 10px;
      color: rgba(255,255,255,.4);
    }

    /* ===================== MAIN ===================== */
    .main-wrap {
      margin-left: 215px;
      flex: 1;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    /* ===================== TOPBAR ===================== */
    .topbar {
      background: #fff;
      border-bottom: 1px solid #e8eaed;
      height: 58px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 28px;
      position: sticky;
      top: 0;
      z-index: 90;
    }
    .topbar-title {
      font-size: 15px;
      font-weight: 600;
      color: #1a1a2e;
    }
    .topbar-right {
      display: flex;
      align-items: center;
      gap: 16px;
    }
    .topbar-user { text-align: right; }
    .topbar-user-name {
      font-size: 13px;
      font-weight: 600;
      color: #1a1a2e;
    }
    .topbar-user-meta {
      font-size: 11px;
      color: #999;
    }
    .btn-logout {
      height: 34px;
      padding: 0 16px;
      border: 1px solid #e2e4e8;
      border-radius: 8px;
      background: transparent;
      font-size: 12px;
      font-weight: 500;
      color: #666;
      cursor: pointer;
      transition: all .15s;
      white-space: nowrap;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
    }
    .btn-logout:hover {
      background: #fef2f2;
      color: #dc2626;
      border-color: #fecaca;
    }

    /* ===================== CONTENIDO ===================== */
    .page-content {
      padding: 26px 28px;
      flex: 1;
    }
  </style>
</head>
<body>

<!-- ======== SIDEBAR ======== -->
<div class="sidebar">

  <div class="sidebar-brand">
    <div class="sidebar-brand-name">&#9881; Repuestos</div>
    <div class="sidebar-brand-sub"><?= htmlspecialchars($usuario['sucursal'] ?? 'Sede Central') ?></div>
  </div>

  <div class="nav-section">Principal</div>

  <a href="<?= BASE ?>/dashboard" class="nav-item <?= esActivo('/dashboard', $rutaActual) ?>">
    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
      <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
      <rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/>
    </svg>
    Inicio
  </a>

  <a href="<?= BASE ?>/productos" class="nav-item <?= esActivo('/productos', $rutaActual) ?>">
    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
      <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
    </svg>
    Productos
  </a>

  <a href="<?= BASE ?>/inventario" class="nav-item <?= esActivo('/inventario', $rutaActual) ?>">
    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
      <line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/>
      <line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/>
      <line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/>
    </svg>
    Inventario
  </a>

  <a href="<?= BASE ?>/personal" class="nav-item <?= esActivo('/personal', $rutaActual) ?>">
    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
      <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
      <circle cx="9" cy="7" r="4"/>
      <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
    </svg>
    Personal
  </a>

  <a href="<?= BASE ?>/sucursales" class="nav-item <?= esActivo('/sucursales', $rutaActual) ?>">
    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
      <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
      <polyline points="9 22 9 12 15 12 15 22"/>
    </svg>
    Sucursales
  </a>

  <div class="nav-section">Análisis</div>

  <a href="<?= BASE ?>/reportes" class="nav-item <?= esActivo('/reportes', $rutaActual) ?>">
    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
      <line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/>
      <line x1="6" y1="20" x2="6" y2="14"/>
    </svg>
    Reportes
  </a>

  <a href="<?= BASE ?>/calendario" class="nav-item <?= esActivo('/calendario', $rutaActual) ?>">
    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
      <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/>
      <line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
    </svg>
    Calendario
  </a>

  <!-- Usuario en sidebar inferior -->
  <div class="sidebar-bottom">
    <div class="user-chip">
      <div class="user-avatar"><?= $iniciales ?></div>
      <div style="overflow:hidden">
        <div class="user-chip-name"><?= htmlspecialchars($usuario['nombre']) ?></div>
        <div class="user-chip-rol"><?= htmlspecialchars($usuario['rol']) ?></div>
      </div>
    </div>
  </div>

</div>
<!-- ======== FIN SIDEBAR ======== -->

<!-- ======== MAIN ======== -->
<div class="main-wrap">

  <!-- TOPBAR -->
  <div class="topbar">
    <div class="topbar-title"><?= htmlspecialchars($titulo ?? 'Dashboard') ?></div>
    <div class="topbar-right">
      <div class="topbar-user">
        <div class="topbar-user-name"><?= htmlspecialchars($usuario['nombre']) ?></div>
        <div class="topbar-user-meta">
          <?= htmlspecialchars($usuario['rol']) ?>
          <?php if (!empty($usuario['sucursal'])): ?>
            &middot; <?= htmlspecialchars($usuario['sucursal']) ?>
          <?php endif; ?>
        </div>
      </div>
      <a href="<?= BASE ?>/logout" class="btn-logout">Cerrar sesión</a>
    </div>
  </div>
  <!-- FIN TOPBAR -->

  <div class="page-content">