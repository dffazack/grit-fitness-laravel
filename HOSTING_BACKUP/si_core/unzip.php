<?php
// Pastikan nama file zip sama persis (huruf besar/kecil berpengaruh)
$fileZip = 'core_data.zip';

// Lokasi ekstrak (titik artinya folder ini sendiri)
$path = __DIR__;

$zip = new ZipArchive;
$res = $zip->open($fileZip);

if ($res === TRUE) {
  // Ekstrak file
  $zip->extractTo($path);
  $zip->close();
  echo '<h1>✅ SUKSES!</h1>';
  echo 'File berhasil diekstrak ke: ' . $path;
} else {
  echo '<h1>❌ GAGAL</h1>';
  echo 'Kode Error: ' . $res;
}
?>
