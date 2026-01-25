<?php
echo "<h1>Diagnosa Server</h1>";

// 1. Cek Versi PHP
echo "<h3>1. Versi PHP: " . phpversion() . "</h3>";
if (version_compare(phpversion(), '8.1.0', '<')) {
    echo "<span style='color:red'>❌ BAHAYA: Laravel butuh PHP 8.1 ke atas! Server ini terlalu tua.</span>";
} else {
    echo "<span style='color:green'>✅ Aman.</span>";
}

// 2. Cek Folder Vendor
echo "<h3>2. Cek Vendor:</h3>";
// Sesuaikan path ini dengan struktur kamu (si_core di dalam htdocs)
$vendorPath = __DIR__ . '/si_core/vendor/autoload.php';

if (file_exists($vendorPath)) {
    echo "<span style='color:green'>✅ File autoload ditemukan di: $vendorPath</span>";
} else {
    echo "<span style='color:red'>❌ GAGAL: File tidak ditemukan di $vendorPath. <br>Cek lagi nama folder 'si_core' atau 'vendor'.</span>";
}
?>
