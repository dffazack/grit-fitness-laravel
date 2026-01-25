<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Cek Maintenance
if (file_exists(__DIR__.'/si_core/storage/framework/maintenance.php')) {
    require __DIR__.'/si_core/storage/framework/maintenance.php';
}

// Register Auto Loader
require __DIR__.'/si_core/vendor/autoload.php';

// Run The Application
(require_once __DIR__.'/si_core/bootstrap/app.php')
    ->handleRequest(Request::capture());