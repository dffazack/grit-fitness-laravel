<?php
// Tentukan lokasi target (Folder storage asli di dalam core)
$target = __DIR__ . '/si_core/storage/app/public';

// Tentukan lokasi link (Folder storage publik yang bisa diakses browser)
$link = __DIR__ . '/storage';

// Hapus link lama jika ada (biar tidak error)
if(file_exists($link)) {
    // Di Windows ini tidak jalan, tapi di Linux server hosting ini jalan
    unlink($link); 
}

// Buat link baru
if (symlink($target, $link)) {
    echo "<h1>Berhasil! Symlink dibuat.</h1>";
    echo "Target: " . $target . "<br>";
    echo "Link: " . $link;
} else {
    echo "<h1>Gagal :(</h1>";
    echo "Pastikan folder 'si_core/storage/app/public' benar-benar ada.";
}
?>
