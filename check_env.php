<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "APP_URL: " . env('APP_URL') . "\n";
echo "SESSION_DOMAIN: " . env('SESSION_DOMAIN') . "\n";
echo "SESSION_SECURE_COOKIE: " . env('SESSION_SECURE_COOKIE') . "\n";
echo "SESSION_DRIVER: " . env('SESSION_DRIVER') . "\n";
