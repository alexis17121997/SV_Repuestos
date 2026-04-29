<?php

session_name('repuestos_session');
session_start();

// 🔥 BASE del proyecto
define('BASE', '/modalidadgrado1/Proyecto');

// 🔁 Autoload
spl_autoload_register(function (string $clase) {
    $directorios = [
        __DIR__ . '/app/controllers/' . $clase . '.php',
        __DIR__ . '/app/models/'      . $clase . '.php',
        __DIR__ . '/app/config/'      . $clase . '.php',
    ];

    foreach ($directorios as $ruta) {
        if (file_exists($ruta)) {
            require_once $ruta;
            return;
        }
    }
});

// 🔥 LIMPIEZA DE URL (CORREGIDO)
$request = $_SERVER['REQUEST_URI'];

// quitar parámetros GET
$request = strtok($request, '?');

// quitar BASE
$request = str_replace(BASE, '', $request);

// quitar index.php
$request = str_replace('/index.php', '', $request);

// limpiar
$request = trim($request, '/');

// convertir a formato /ruta
$uri = '/' . $request;

// corregir raíz
if ($uri === '//') {
    $uri = '/';
}

$metodo = $_SERVER['REQUEST_METHOD'];

// 🧭 ROUTER
match (true) {

    // Login
    $uri === '/' || $uri === '/login' =>
        (new authcontroller())->login(),

    // Logout
    $uri === '/logout' =>
        (new authcontroller())->logout(),

    // Dashboard
    $uri === '/dashboard' =>
        (new dashboardcontroller())->index(),

    // Módulos
    str_starts_with($uri, '/productos') =>
        (new productoscontroller())->index(),

    str_starts_with($uri, '/personal') =>
        (new personalcontroller())->index(),

    str_starts_with($uri, '/sucursales') =>
        (new sucursalescontroller())->index(),

    str_starts_with($uri, '/inventario') =>
        (new inventariocontroller())->index(),

    str_starts_with($uri, '/calendario') =>
        (new calendariocontroller())->index(),

    str_starts_with($uri, '/reportes') =>
        (new reportescontroller())->index(),

    // 404
    default => (function () {
        http_response_code(404);
        echo '<h2 style="font-family:sans-serif;padding:40px">404 — Página no encontrada</h2>';
    })()
};