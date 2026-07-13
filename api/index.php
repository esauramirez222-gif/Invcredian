<?php

$tmpDir = '/tmp/laravel';

// 1. Crear la estructura completa de carpetas permitidas
$dirs = [
    $tmpDir . '/storage/framework/cache/data',
    $tmpDir . '/storage/framework/sessions',
    $tmpDir . '/storage/framework/testing',
    $tmpDir . '/storage/framework/views',
    $tmpDir . '/storage/logs',
    $tmpDir . '/bootstrap/cache',
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// 2. ¡LA MAGIA! Obligar a Laravel a usar /tmp para TODOS sus archivos críticos
putenv('APP_SERVICES_CACHE=' . $tmpDir . '/bootstrap/cache/services.php');
putenv('APP_PACKAGES_CACHE=' . $tmpDir . '/bootstrap/cache/packages.php');
putenv('APP_CONFIG_CACHE=' . $tmpDir . '/bootstrap/cache/config.php');
putenv('APP_ROUTES_CACHE=' . $tmpDir . '/bootstrap/cache/routes.php');
putenv('APP_EVENTS_CACHE=' . $tmpDir . '/bootstrap/cache/events.php');
putenv('VIEW_COMPILED_PATH=' . $tmpDir . '/storage/framework/views');

$_ENV['APP_SERVICES_CACHE'] = $tmpDir . '/bootstrap/cache/services.php';
$_ENV['APP_PACKAGES_CACHE'] = $tmpDir . '/bootstrap/cache/packages.php';
$_ENV['APP_CONFIG_CACHE']   = $tmpDir . '/bootstrap/cache/config.php';
$_ENV['APP_ROUTES_CACHE']   = $tmpDir . '/bootstrap/cache/routes.php';
$_ENV['APP_EVENTS_CACHE']   = $tmpDir . '/bootstrap/cache/events.php';
$_ENV['VIEW_COMPILED_PATH'] = $tmpDir . '/storage/framework/views';

// 3. Cargar el motor principal
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

// 4. Mover la carpeta de almacenamiento final
$app->useStoragePath($tmpDir . '/storage');

// 5. Encender y ejecutar la aplicación (Sintaxis nativa de Laravel 11)
$app->handleRequest(Illuminate\Http\Request::capture());