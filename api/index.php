<?php

try {
    // 1. Definir la ruta permitida por Vercel
    $storagePath = '/tmp/storage';

    // 2. Crear carpetas temporales
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

    // 3. Cargar el motor
    require __DIR__ . '/../vendor/autoload.php';
    $app = require_once __DIR__ . '/../bootstrap/app.php';

    // 4. Forzar el uso de /tmp
    $app->useStoragePath($storagePath);

    // 5. Ejecutar la app
    $request = Illuminate\Http\Request::capture();
    $response = $app->handle($request);
    $response->send();
    $app->terminate();

} catch (\Throwable $e) {
    // ¡LA TRAMPA! Si algo falla, forzamos a PHP a imprimir el error sin usar las Vistas de Laravel.
    http_response_code(500);
    echo "<div style='font-family: sans-serif; padding: 20px; background: #ffebee; border: 2px solid #f44336; border-radius: 8px;'>";
    echo "<h2 style='color: #d32f2f;'>¡MÁSCARA QUITADA! ESTE ES EL ERROR REAL:</h2>";
    echo "<p><b>Mensaje:</b> " . $e->getMessage() . "</p>";
    echo "<p><b>Archivo:</b> " . $e->getFile() . " (Línea " . $e->getLine() . ")</p>";
    echo "</div>";
    echo "<pre style='background: #333; color: #fff; padding: 15px; margin-top: 15px; overflow-x: auto;'>" . $e->getTraceAsString() . "</pre>";
}