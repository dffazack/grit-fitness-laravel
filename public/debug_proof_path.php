<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$transactions = \App\Models\Transaction::whereNotNull('proof_url')->limit(5)->get();

foreach ($transactions as $tx) {
    echo "ID: " . $tx->id . "\n";
    echo "Stored Path: " . $tx->proof_url . "\n";
    echo "Full URL: " . $tx->full_proof_url . "\n";
    echo "File Exists (Storage): " . (\Illuminate\Support\Facades\Storage::disk('public')->exists($tx->proof_url) ? 'YES' : 'NO') . "\n";
    echo "--------------------------------\n";
}
