<?php
// Letakkan file ini di folder 'htdocs' (sebelah index.php)
// Akses: http://domain-anda.com/force_clear.php

echo "<h1>🧹 Force Clear Cache (Manual Delete)</h1>";

// Sesuaikan path ke folder project
$baseDir = __DIR__ . '/si_core';

$targets = [
    $baseDir . '/bootstrap/cache',
    $baseDir . '/storage/framework/views',
    $baseDir . '/storage/framework/cache/data', // Jika ada
];

foreach ($targets as $target) {
    if (!is_dir($target)) {
        echo "<p>Skipping $target (Not a directory)</p>";
        continue;
    }

    echo "<h3>Cleaning $target ...</h3>";
    $files = glob("$target/*.php"); // Hapus file .php (cache)
    foreach ($files as $file) {
        if (is_file($file)) {
            // Jangan hapus .gitignore
            if (basename($file) == '.gitignore')
                continue;

            if (unlink($file)) {
                echo "Deleted: " . basename($file) . "<br>";
            } else {
                echo "<span style='color:red'>Failed to delete: " . basename($file) . "</span><br>";
            }
        }
    }
}

    }
}

// 2. Reset OPcache (Server-side Memory Cache)
if (function_exists('opcache_reset')) {
    if (opcache_reset()) {
        echo "<h3 style='color:green'>✅ OPcache Reset Successfully</h3>";
    } else {
        echo "<h3 style='color:orange'>⚠️ OPcache Reset Failed (Might be disabled or restricted)</h3>";
    }
} else {
    echo "<p>ℹ️ OPcache function not available.</p>";
}

// 3. Inspeksi Isi Route File (Proof of File)
$routeFile = $baseDir . '/routes/web.php';
echo "<h2>🧐 Inspeksi File Route</h2>";
if (file_exists($routeFile)) {
    $content = file_get_contents($routeFile);
    if (strpos($content, '/debug/clear-cache') !== false) {
        echo "<h3 style='color:green'>✅ File Route MEMILIKI kode '/debug/clear-cache'</h3>";
        echo "<pre style='background: #eee; padding: 10px;'>" . htmlspecialchars(substr($content, -500)) . "</pre>";
    } else {
        echo "<h3 style='color:red'>❌ File Route TIDAK memiliki kode tersebut. (Masih Versi Lama?)</h3>";
    }
} else {
    echo "<h3 style='color:red'>❌ File Route tidak ditemukan di: $routeFile</h3>";
}

echo "<hr>";
echo "<h2>✅ Selesai. Coba akses website Anda lagi.</h2>";
echo "<p>Jika di atas tertulis ✅ MEMILIKI kode, tapi saat diakses masih 404, maka masalahnya ada di **Konfigurasi Server** (bukan kode Anda).</p>";
echo "<a href='/'>Kembali ke Home</a>";
