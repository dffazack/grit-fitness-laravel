<?php
fwrite(STDERR, "Starting script...\n");

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

fwrite(STDERR, "Bootstrapping...\n");
$kernel->bootstrap();

fwrite(STDERR, "Environment: " . env('APP_ENV') . "\n");
fwrite(STDERR, "DB_DATABASE: " . env('DB_DATABASE') . "\n");
fwrite(STDERR, "DB_USERNAME: " . env('DB_USERNAME') . "\n");

try {
    $pdo = Illuminate\Support\Facades\DB::connection()->getPdo();
    fwrite(STDERR, "PDO Connected to: " . $pdo->getAttribute(PDO::ATTR_DRIVER_NAME) . "\n");
} catch (\Exception $e) {
    fwrite(STDERR, "PDO Connection Failed: " . $e->getMessage() . "\n");
}

use App\Models\User;
$count = User::count();
fwrite(STDERR, "User Count: $count\n");

fwrite(STDERR, "Script finished.\n");
