<?php
class inventariocontroller
{
    public function index(): void
    {
        if (empty($_SESSION['usuario'])) {
            header('Location: ' . BASE . '/login'); exit;
        }
        $titulo = 'Inventario';
        require __DIR__ . '/../views/layout/header.php';
        echo '<div style="padding:40px;color:#888;font-size:14px">Módulo de inventario — próximamente</div>';
        require __DIR__ . '/../views/layout/footer.php';
    }
}