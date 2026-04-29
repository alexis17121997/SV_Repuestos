<?php

require_once __DIR__ . '/../config/database.php';

class usuariomodel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = database::conectar();
    }

    public function buscarPorUsername(string $username): array|false
    {
        $stmt = $this->db->prepare("
            SELECT u.id, u.username, u.password_hash, u.activo,
                   p.nombre, p.apellido, p.email,
                   r.nombre AS rol,
                   s.nombre AS sucursal
            FROM usuarios u
            INNER JOIN personal p  ON p.id = u.personal_id
            INNER JOIN roles r     ON r.id = p.rol_id
            LEFT JOIN  sucursales s ON s.id = p.sucursal_id
            WHERE u.username = :username
              AND u.activo = TRUE
        ");
        $stmt->execute([':username' => $username]);
        return $stmt->fetch();
    }

    public function actualizarUltimoLogin(int $id): void
    {
        $stmt = $this->db->prepare(
            "UPDATE usuarios SET ultimo_login = NOW() WHERE id = :id"
        );
        $stmt->execute([':id' => $id]);
    }
}