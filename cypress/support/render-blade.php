<?php

// cypress/support/render-blade.php

// Bootstrap the Laravel application
require __DIR__.'/../../bootstrap/app.php';

use Illuminate\Support\Facades\Blade;
use Illuminate\Http\Request;

// Get the Blade string from the request body
$bladeContent = file_get_contents('php://input');

// Create a unique temporary file to store the Blade content
$viewPath = storage_path('framework/views/');
$tempFile = tempnam($viewPath, 'cy_');
file_put_contents($tempFile, $bladeContent);

// Get the relative path for the view name
$viewName = 'framework/views/' . basename($tempFile, '.php');

// Render the Blade view
try {
    $html = Blade::render($bladeContent, [], true);
    echo $html;
} catch (Exception $e) {
    // Output error message if rendering fails
    echo "Error rendering Blade component: " . $e->getMessage();
} finally {
    // Clean up the temporary file
    if (file_exists($tempFile)) {
        unlink($tempFile);
    }
}
