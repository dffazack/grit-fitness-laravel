<?php
// Matikan error reporting biar kita lihat pesan manual aja
error_reporting(0);

$target = __DIR__ . '/si_core/storage/app/public';
$link   = __DIR__ . '/storage';

echo "<h3>Mencoba menghubungkan...</h3>";
echo "Dari: $target <br>";
echo "Ke: $link <br><br>";

// Langsung buat link
if (symlink($target, $link)) {
    echo "<h1>✅ BERHASIL!</h1>";
    echo "Symlink terbentuk. Coba refresh FileZilla, harusnya ada folder storage baru.";
} else {
    echo "<h1>❌ GAGAL</h1>";
    echo "Penyebab kemungkinan:<br>";
    echo "1. Fitur symlink dimatikan oleh InfinityFree (jarang terjadi)<br>";
    echo "2. Target folder 'si_core/storage/app/public' belum permission 777";
}
?>