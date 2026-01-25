<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

/* Baris Maintenance (Jika ada) */
if (file_exists(__DIR__.'/si_core/storage/framework/maintenance.php')) {
    require __DIR__.'/si_core/storage/framework/maintenance.php';
}

/* Baris Autoload */
require __DIR__.'/si_core/vendor/autoload.php';

/* Baris Bootstrap */
$app = require_once __DIR__.'/si_core/bootstrap/app.php';