<?php
// Script Ringan - Tanpa Load Laravel (Anti 500/502 Error)
// Akses: http://domain.com/simple_debug.php

header('Content-Type: text/html; charset=utf-8');

echo "<h1>🔍 Simple File Inspector</h1>";
echo "<p>Script Location: <code>" . __DIR__ . "</code></p>";

// 1. Cek Struktur Folder di Root
echo "<h2>📂 Directory List (Current Folder)</h2>";
$files = scandir(__DIR__);
echo "<ul>";
foreach ($files as $file) {
    if ($file == '.' || $file == '..')
        continue;
    $type = is_dir($file) ? '[DIR]' : '[FILE]';
    echo "<li>$type $file</li>";
}
echo "</ul>";

// 2. Cek Keberadaan Folder Project 'si_core'
$projectFolder = 'si_core'; // Ganti jika nama folder projectnya beda
if (is_dir(__DIR__ . '/' . $projectFolder)) {
    echo "<p style='color:green'>✅ Folder Project found: <code>$projectFolder</code></p>";

    // 3. Cek Controller Target
    $controllerPath = __DIR__ . '/' . $projectFolder . '/app/Http/Controllers/HomeController.php';
    echo "<h2>📄 Checking Controller File</h2>";
    echo "Target: <code>$controllerPath</code><br>";

    if (file_exists($controllerPath)) {
        echo "<p style='color:green'>✅ File Exists!</p>";
        echo "Last Modified: " . date("Y-m-d H:i:s", filemtime($controllerPath)) . "<br>";

        // Baca isi file
        $content = file_get_contents($controllerPath);

        // Cari tanda update
        $marker = "HomepageContent::all()";
        if (strpos($content, $marker) !== false) {
            echo "<h3 style='color:green; border:2px solid green; padding:10px;'>✅ STATUS: UPDATED (Code Terbaru)</h3>";
            echo "<p>File ini sudah berisi logika logic database.</p>";
        } else {
            echo "<h3 style='color:red; border:2px solid red; padding:10px;'>❌ STATUS: OUTDATED (Masih Code Lama)</h3>";
            echo "<p>File ini belum memiliki code <code>HomepageContent::all()</code>.</p>";
        }
    } else {
        echo "<p style='color:red'>❌ File Controller TIDAK ditemukan di path tersebut.</p>";
    }

} else {
    echo "<h3 style='color:red'>❌ Folder Project '$projectFolder' TIDAK ditemukan di sini.</h3>";
    echo "<p>Apakah Anda mengupload folder project dengan nama berbeda? Atau apakah file project berantakan di root?</p>";
}
