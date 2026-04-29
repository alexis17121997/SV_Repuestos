<?php
class calendariocontroller
{
    public function index(): void
    {
        if (empty($_SESSION['usuario'])) {
            header('Location: ' . BASE . '/login'); exit;
        }
        $titulo = 'Calendario';
        require __DIR__ . '/../views/layout/header.php';
        echo '<div style="padding:40px;color:#888;font-size:14px">Módulo de calendario — próximamente</div>';
        require __DIR__ . '/../views/layout/footer.php';
    }
}