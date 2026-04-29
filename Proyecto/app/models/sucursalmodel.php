<?php

require_once __DIR__ . '/../Config/Database.php';

class UsuarioModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function obtenerTodos(): array
    {
        $stmt = $this->db->query('SELECT * FROM usuarios ORDER BY id');
        return $stmt->fetchAll();
    }

    public function obtenerPorId(int $id): array|false
    {
        $stmt = $this->db->prepare('SELECT * FROM usuarios WHERE id = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function crear(string $nombre, string $email): bool
    {
        $stmt = $this->db->prepare(
            'INSERT INTO usuarios (nombre, email) VALUES (:nombre, :email)'
        );
        return $stmt->execute([':nombre' => $nombre, ':email' => $email]);
    }
}