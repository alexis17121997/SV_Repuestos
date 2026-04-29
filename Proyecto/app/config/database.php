<?php

class database
{
    private static ?PDO $conexion = null;

    public static function conectar(): PDO
    {
        if (self::$conexion === null) {
            $env = parse_ini_file(__DIR__ . '/../../.env');

            $dsn = sprintf(
                'pgsql:host=%s;port=%s;dbname=%s',
                $env['DB_HOST'],
                $env['DB_PORT'],
                $env['DB_NAME']
            );

            try {
                self::$conexion = new PDO($dsn, $env['DB_USER'], $env['DB_PASS'], [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]);
            } catch (PDOException $e) {
                die(json_encode(['error' => 'Error de conexión: ' . $e->getMessage()]));
            }
        }

        return self::$conexion;
    }
}