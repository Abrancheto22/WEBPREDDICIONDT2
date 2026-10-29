<?php

// Laravel debe escribir datos temporales en /tmp en funciones serverless.
$storagePath = '/tmp/storage';
$cachePath = '/tmp/bootstrap/cache';

$storageDirs = [
    $storagePath . '/framework/views',
    $storagePath . '/framework/cache/data',
    $storagePath . '/framework/sessions',
    $storagePath . '/logs',
    $cachePath,
];

foreach ($storageDirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// Laravel reconoce estas variables al resolver sus rutas de storage/cache.
$temporaryPaths = [
    'LARAVEL_STORAGE_PATH' => $storagePath,
    'VIEW_COMPILED_PATH' => $storagePath . '/framework/views',
    'APP_CONFIG_CACHE' => $cachePath . '/config.php',
    'APP_EVENTS_CACHE' => $cachePath . '/events.php',
    'APP_PACKAGES_CACHE' => $cachePath . '/packages.php',
    'APP_ROUTES_CACHE' => $cachePath . '/routes-v7.php',
    'APP_SERVICES_CACHE' => $cachePath . '/services.php',
];

foreach ($temporaryPaths as $key => $value) {
    putenv($key . '=' . $value);
    $_ENV[$key] = $value;
    $_SERVER[$key] = $value;
}

// Cargar Laravel.
require __DIR__ . '/../public/index.php';
