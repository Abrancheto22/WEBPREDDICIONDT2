<?php

// 1. Preparar directorios de escritura temporales en /tmp para Vercel Serverless
$storageDirs = [
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/logs',
    '/tmp/bootstrap/cache'
];

foreach ($storageDirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// 2. Redirigir rutas de almacenamiento dinámico de Laravel hacia /tmp
putenv('VIEW_COMPILED_PATH=/tmp/storage/framework/views');
putenv('APP_STORAGE=/tmp/storage');

// 3. Cargar el frontend / backend de Laravel
require __DIR__ . '/../public/index.php';
