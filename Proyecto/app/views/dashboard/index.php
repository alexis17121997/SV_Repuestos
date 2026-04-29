<style>
  .cards-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 24px;
  }
  .metric-card {
    background: #fff;
    border: 1px solid #e8eaed;
    border-radius: 12px;
    padding: 18px 20px;
  }
  .metric-label {
    font-size: 12px;
    color: #888;
    margin-bottom: 8px;
    font-weight: 500;
  }
  .metric-value {
    font-size: 30px;
    font-weight: 700;
    color: #1a1a2e;
    line-height: 1;
  }
  .metric-sub {
    font-size: 11px;
    color: #bbb;
    margin-top: 6px;
  }
  .metric-warn  { color: #d97706; }
  .metric-danger { color: #dc2626; }
  .metric-ok    { color: #15803d; }

  .panels-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
  }
  .panel {
    background: #fff;
    border: 1px solid #e8eaed;
    border-radius: 12px;
    padding: 18px 20px;
  }
  .panel-title {
    font-size: 13px;
    font-weight: 600;
    color: #1a1a2e;
    margin-bottom: 14px;
    padding-bottom: 12px;
    border-bottom: 1px solid #f0f0f0;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }
  .panel-count {
    font-size: 11px;
    font-weight: 500;
    background: #f4f5f7;
    color: #888;
    padding: 2px 8px;
    border-radius: 10px;
  }

  .mini-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    font-size: 12px;
    border-bottom: 1px solid #f8f8f8;
  }
  .mini-row:last-child { border: none; }
  .mini-label {
    color: #444;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 200px;
  }
  .mini-right {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-shrink: 0;
  }
  .mini-meta { font-size: 11px; color: #bbb; }

  .badge {
    font-size: 10px;
    padding: 2px 9px;
    border-radius: 10px;
    font-weight: 600;
    white-space: nowrap;
  }
  .badge-ok     { background: #dcfce7; color: #15803d; }
  .badge-warn   { background: #fef3c7; color: #d97706; }
  .badge-danger { background: #fee2e2; color: #dc2626; }

  .mov-tipo {
    font-size: 10px;
    padding: 2px 8px;
    border-radius: 8px;
    font-weight: 600;
  }
  .mov-entrada    { background: #dcfce7; color: #15803d; }
  .mov-salida     { background: #fee2e2; color: #dc2626; }
  .mov-ajuste     { background: #f3f4f6; color: #6b7280; }
  .mov-transferencia { background: #ede9fe; color: #7c3aed; }

  .empty-state {
    text-align: center;
    padding: 32px 20px;
    color: #bbb;
    font-size: 13px;
  }
  .empty-icon {
    font-size: 28px;
    margin-bottom: 8px;
    display: block;
  }

  .page-greeting {
    margin-bottom: 22px;
  }
  .page-greeting h2 {
    font-size: 20px;
    font-weight: 700;
    color: #1a1a2e;
    margin-bottom: 4px;
  }
  .page-greeting p {
    font-size: 13px;
    color: #888;
  }
</style>

<?php
$hora = (int) date('H');
$saludo = match(true) {
    $hora < 12 => 'Buenos días',
    $hora < 19 => 'Buenas tardes',
    default    => 'Buenas noches',
};
?>

<!-- Saludo -->
<div class="page-greeting">
  <h2><?= $saludo ?>, <?= htmlspecialchars(explode(' ', $usuario['nombre'])[0]) ?> 👋</h2>
  <p><?= date('l d \d\e F \d\e Y') ?> &middot; <?= htmlspecialchars($usuario['sucursal'] ?? 'Sede Central') ?></p>
</div>

<!-- Tarjetas de métricas -->
<div class="cards-grid">

  <div class="metric-card">
    <div class="metric-label">Productos activos</div>
    <div class="metric-value"><?= (int)$metricas['productos_activos'] ?></div>
    <div class="metric-sub">en catálogo</div>
  </div>

  <div class="metric-card">
    <div class="metric-label">Stock bajo / crítico</div>
    <div class="metric-value <?= (int)$metricas['stock_bajo'] > 0 ? 'metric-warn' : 'metric-ok' ?>">
      <?= (int)$metricas['stock_bajo'] ?>
    </div>
    <div class="metric-sub">productos bajo mínimo</div>
  </div>

  <div class="metric-card">
    <div class="metric-label">Personal activo</div>
    <div class="metric-value"><?= (int)$metricas['personal_activo'] ?></div>
    <div class="metric-sub">empleados</div>
  </div>

  <div class="metric-card">
    <div class="metric-label">Movimientos hoy</div>
    <div class="metric-value"><?= (int)$metricas['movimientos_hoy'] ?></div>
    <div class="metric-sub">entradas y salidas</div>
  </div>

</div>

<!-- Paneles inferiores -->
<div class="panels-row">

  <!-- Stock bajo -->
  <div class="panel">
    <div class="panel-title">
      Productos con stock bajo
      <span class="panel-count"><?= count($stockBajo) ?></span>
    </div>

    <?php if (empty($stockBajo)): ?>
      <div class="empty-state">
        <span class="empty-icon">&#10003;</span>
        Todo el stock está en niveles normales
      </div>
    <?php else: ?>
      <?php foreach ($stockBajo as $item): ?>
        <?php
          $pct   = $item['stock_minimo'] > 0
                   ? ($item['stock_actual'] / $item['stock_minimo'])
                   : 1;
          $clase = $pct <= 0   ? 'badge-danger'
                 : ($pct <= .5  ? 'badge-warn'
                 : 'badge-ok');
        ?>
        <div class="mini-row">
          <span class="mini-label"><?= htmlspecialchars($item['nombre']) ?></span>
          <div class="mini-right">
            <span class="mini-meta"><?= htmlspecialchars($item['sucursal']) ?></span>
            <span class="badge <?= $clase ?>">
              <?= (int)$item['stock_actual'] ?> ud.
            </span>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>

  <!-- Últimos movimientos -->
  <div class="panel">
    <div class="panel-title">
      Últimos movimientos
      <span class="panel-count"><?= count($ultimosMovimientos) ?></span>
    </div>

    <?php if (empty($ultimosMovimientos)): ?>
      <div class="empty-state">
        <span class="empty-icon">&#128230;</span>
        Sin movimientos registrados aún
      </div>
    <?php else: ?>
      <?php foreach ($ultimosMovimientos as $mov): ?>
        <?php
          $clasesMov = [
            'entrada'      => 'mov-entrada',
            'salida'       => 'mov-salida',
            'ajuste'       => 'mov-ajuste',
            'transferencia'=> 'mov-transferencia',
          ];
          $claseMov = $clasesMov[$mov['tipo']] ?? 'mov-ajuste';
          $signo    = $mov['tipo'] === 'entrada' ? '+' : ($mov['tipo'] === 'salida' ? '-' : '±');
        ?>
        <div class="mini-row">
          <span class="mini-label">
            <?= $signo ?><?= (int)$mov['cantidad'] ?>
            &nbsp;—&nbsp;<?= htmlspecialchars($mov['producto']) ?>
          </span>
          <div class="mini-right">
            <span class="mini-meta">
              <?= date('d/m H:i', strtotime($mov['fecha'])) ?>
            </span>
            <span class="mov-tipo <?= $claseMov ?>">
              <?= htmlspecialchars($mov['tipo']) ?>
            </span>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>

</div>