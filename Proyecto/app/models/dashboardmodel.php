<?php

require_once __DIR__ . '/../config/database.php';

class dashboardmodel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = database::conectar();
    }

    public function obtenerMetricas(): array
    {
        $datos = [];

        $datos['productos_activos'] = $this->db
            ->query("SELECT COUNT(*) FROM productos WHERE activo = TRUE")
            ->fetchColumn();

        $datos['stock_bajo'] = $this->db
            ->query("
                SELECT COUNT(*) FROM inventario
                WHERE stock_actual <= stock_minimo
            ")
            ->fetchColumn();

        $datos['personal_activo'] = $this->db
            ->query("SELECT COUNT(*) FROM personal WHERE activo = TRUE")
            ->fetchColumn();

        $datos['movimientos_hoy'] = $this->db
            ->query("
                SELECT COUNT(*) FROM movimientos_inventario
                WHERE DATE(fecha) = CURRENT_DATE
            ")
            ->fetchColumn();

        return $datos;
    }

    public function productosStockBajo(): array
    {
        $stmt = $this->db->query("
            SELECT
                p.nombre,
                i.stock_actual,
                i.stock_minimo,
                s.nombre AS sucursal
            FROM inventario i
            INNER JOIN productos p  ON p.id = i.producto_id
            INNER JOIN sucursales s ON s.id = i.sucursal_id
            WHERE i.stock_actual <= i.stock_minimo
              AND p.activo = TRUE
            ORDER BY i.stock_actual ASC
            LIMIT 8
        ");
        return $stmt->fetchAll();
    }

    public function ultimosMovimientos(): array
    {
        $stmt = $this->db->query("
            SELECT
                m.tipo,
                m.cantidad,
                m.fecha,
                m.observacion,
                p.nombre  AS producto,
                pe.nombre AS empleado,
                pe.apellido
            FROM movimientos_inventario m
            INNER JOIN productos  p  ON p.id  = m.producto_id
            LEFT JOIN  personal   pe ON pe.id = m.personal_id
            ORDER BY m.fecha DESC
            LIMIT 8
        ");
        return $stmt->fetchAll();
    }
}