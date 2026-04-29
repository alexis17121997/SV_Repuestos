<?php

require_once __DIR__ . '/../models/dashboardmodel.php';

class dashboardcontroller
{
    private dashboardmodel $modelo;

    public function __construct()
    {
        $this->modelo = new dashboardmodel();
    }

    public function index(): void
    {
        if (empty($_SESSION['usuario'])) {
            header('Location: ' . BASE . '/login');
            exit;
        }

        $titulo   = 'Inicio — Panel principal';
        $metricas = $this->modelo->obtenerMetricas();
        $stockBajo        = $this->modelo->productosStockBajo();
        $ultimosMovimientos = $this->modelo->ultimosMovimientos();

        require __DIR__ . '/../views/layout/header.php';
        require __DIR__ . '/../views/dashboard/index.php';
        require __DIR__ . '/../views/layout/footer.php';
    }
}