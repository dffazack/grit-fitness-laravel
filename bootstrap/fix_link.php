<?php
// Tentukan lokasi target (Gudang asli di dalam si_core)
$target = __DIR__ . '/si_core/storage/app/public';

// Tentukan lokasi link (Etalase di htdocs)
$link = __DIR__ . '/storage';

echo "<h2>Memperbaiki Symlink...</h2>";
echo "Target Asli: " . $target . "<br>";
echo "Link Publik: " . $link . "<br><br>";

// Hapus link lama jika masih bandel
if(file_exists($link)) {
    // Coba hapus sebagai file (symlink dianggap file)
    @unlink($link); 
    // Coba hapus sebagai folder (jika itu folder beneran)
    @rmdir($link);
}

// Buat link baru
if (symlink($target, $link)) {
    echo "<h1>✅ BERHASIL!</h1>";
    echo "Sekarang folder 'storage' di htdocs sudah nyambung ke 'si_core'.";
    echo "<br>Coba refresh halaman trainermu.";
} else {
    echo "<h1>❌ GAGAL</h1>";
    echo "Coba cek apakah folder 'si_core/storage/app/public' benar-benar ada?";
}
?>
