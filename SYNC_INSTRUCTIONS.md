# Panduan Wajib Update File (Sync Manual)

Hasil tes 404 mengonfirmasi bahwa **File codingan di Hosting Anda KADALUARSA**.
Meskipun database Anda sudah berisi data baru, codingan di server tidak tahu harus mengambil data tersebut karena masih menggunakan versi lama.

Anda harus melakukan **OVERWRITE (Timpa)** folder-folder berikut via FileZilla.

## Target Direktori
Karena index.php Anda ada di `htdocs`, maka target upload Anda adalah:
`/htdocs`

(Atau jika folder project Anda namanya `si_core`, maka `/htdocs/si_core`)

## Folder yang WAJIB di-Upload Ulang:

1.  📁 **app**
    *   *Kenapa?* Berisi Logika Controller baru yang bisa baca database.
    *   *Lokasi Lokal:* `c:\laragon\www\grit-fitness-laravel\app`
    *   *Lokasi Server:* `/htdocs/app`

2.  📁 **routes**
    *   *Kenapa?* Berisi jalur `/debug/clear-cache` dan jalur update lainnya.
    *   *Lokasi Lokal:* `c:\laragon\www\grit-fitness-laravel\routes`
    *   *Lokasi Server:* `/htdocs/routes`

3.  📁 **resources**
    *   *Kenapa?* Berisi tampilan HTML (View) yang bisa menampilkan data dari database.
    *   *Lokasi Lokal:* `c:\laragon\www\grit-fitness-laravel\resources`
    *   *Lokasi Server:* `/htdocs/resources`

## Cara Melakukannya di FileZilla:
1.  Di panel **Kiri (Local)**: Klik kanan folder `app` -> Pilih **Upload**.
2.  Jika ditanya "Target file already exists", pilih **Overwrite** (Timpa). Centang "Always use this action" (Selalu lakukan ini).
3.  Ulangi untuk folder `routes` dan `resources`.

🛑 **JANGAN** upload folder `vendor` atau `storage` (kecuali gambar) karena ukurannya besar dan jarang berubah.

Setelah selesai upload 3 folder ini, coba akses lagi `/debug/clear-cache`. Jika sukses, website Anda akan normal.
