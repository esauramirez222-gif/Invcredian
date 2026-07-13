<?php

// 1. Definir la única ruta donde Vercel nos permite escribir archivos
$storagePath = '/tmp/storage';

// 2. Crear toda la estructura de carpetas internas que Laravel exige para funcionar
$directories = [
    $storagePath . '/app/public',
    $storagePath . '/framework/cache/data',
    $storagePath . '/framework/sessions',
    $storagePath . '/framework/testing',
    $storagePath . '/framework/views',
    $storagePath . '/logs',
    $storagePath . '/bootstrap/cache',
];

foreach ($directories as $directory) {
    if (!is_dir($directory)) {
        mkdir($directory, 0755, true);
    }
}

// 3. Cargar el motor principal de Laravel
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

// 4. ¡LA MAGIA! Ordenarle a Laravel que abandone su carpeta bloqueada y use la carpeta /tmp
$app->useStoragePath($storagePath);

// 5. Encender y ejecutar la aplicación de forma segura
$request = Illuminate\Http\Request::capture();
$response = $app->handle($request);
$response->send();
$app->terminate();