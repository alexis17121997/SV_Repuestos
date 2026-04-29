<?php

require_once __DIR__ . '/../models/usuariomodel.php';

class authcontroller
{
    private usuariomodel $modelo;

    public function __construct()
    {
        $this->modelo = new usuariomodel();
    }

    public function login(): void
    {
        // 🔒 Si ya está logueado → ir al dashboard
        if (!empty($_SESSION['usuario'])) {
            header('Location: ' . BASE . '/index.php/dashboard');
            exit;
        }

        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';

            if ($username === '' || $password === '') {
                $error = 'Completá todos los campos.';
            } else {

                $usuario = $this->modelo->buscarPorUsername($username);

                // 🔥 VALIDACIÓN LOGIN
                if ($usuario && password_verify($password, $usuario['password_hash'])) {

                    $_SESSION['usuario'] = [
                        'id'       => $usuario['id'],
                        'username' => $usuario['username'],
                        'nombre'   => $usuario['nombre'] . ' ' . $usuario['apellido'],
                        'rol'      => $usuario['rol'],
                        'sucursal' => $usuario['sucursal'],
                    ];

                    $this->modelo->actualizarUltimoLogin($usuario['id']);

                    // 🔥 REDIRECCIÓN CORRECTA
                    header('Location: ' . BASE . '/index.php/dashboard');
                    exit;

                } else {
                    $error = 'Usuario o contraseña incorrectos.';
                }
            }
        }

        require __DIR__ . '/../views/login/index.php';
    }

    public function logout(): void
    {
        $_SESSION = [];
        session_destroy();

        // 🔥 REDIRECCIÓN CORRECTA
        header('Location: ' . BASE . '/index.php/login');
        exit;
    }
}