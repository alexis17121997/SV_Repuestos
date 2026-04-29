<?php
class reportescontroller
{
    public function index(): void
    {
        if (empty($_SESSION['usuario'])) {
            header('Location: ' . BASE . '/login'); exit;
        }
        $titulo = 'Reportes';
        require __DIR__ . '/../views/layout/header.php';
        echo '<div style="padding:40px;color:#888;font-size:14px">Módulo de reportes — próximamente</div>';
        require __DIR__ . '/../views/layout/footer.php';
    }
}