<?php
// Load Laravel
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\HomepageContent;

$hero = HomepageContent::where('section', 'hero')->first();

echo "<h1>Debug Hero Content</h1>";
if ($hero) {
    echo "<pre>";
    print_r($hero->toArray());
    echo "</pre>";

    $content = $hero->content;
    if (isset($content['image'])) {
        echo "<p>Image Path in DB: " . $content['image'] . "</p>";
        $fullPath = public_path($content['image']);
        echo "<p>Public Path Check: " . $fullPath . "</p>";
        echo "<p>File Exists: " . (file_exists($fullPath) ? 'YES' : 'NO') . "</p>";
    }
} else {
    echo "No hero content found.";
}
