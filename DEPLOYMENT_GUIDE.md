# Panduan Deployment & Sinkronisasi Hosting (InfinityFree)

Masalah yang Anda alami ("update di local tapi tidak masuk ke hosting") terjadi karena **Database Local (Laptop) dan Database Hosting (InfinityFree) adalah dua hal yang TERPISAH dan TIDAK TERHUBUNG otomatis.**

Saat Anda mengubah konten (Jadwal, Hero Banner) di Local, data itu hanya tersimpan di laptop Anda. Untuk memunculkannya di Hosting, Anda harus melakukan salah satu dari dua cara ini:

## Solusi 1: Input Ulang (Manual) - REKOMENDASI UTAMA
Ini adalah cara yang paling aman dan benar untuk pengelolaan website jangka panjang.
1. Buka browser dan akses website Anda yang sudah online (contoh: `grit-fitness.infinityfreeapp.com/admin/login`).
2. Login sebagai Admin di sana.
3. Lakukan input data (edit Banner, tambah Jadwal) **langsung di website hosting**, sama seperti Anda melakukannya di local.
4. **Alasannya:** Website live harus memiliki data sendiri. Anda tidak seharusnya me-reset database live setiap kali ada perubahan kecil di local.

## Solusi 2: Migrasi Full Database (Hanya untuk Setup Awal/Reset Total)
Jika Anda ingin **memindahkan SELURUH data** dari local ke hosting (menimpa semua data yang ada di hosting), lakukan langkah ini:

### Langkah A: Export Database Local
1. Buka **HeidiSQL** atau **phpMyAdmin** di Laragon.
2. Klik kanan pada database local (`grit-fitness-laravel`).
3. Pilih **Export database as SQL**.
4. Pastikan opsi 'Create' dan 'Insert' dicentang. Simpan file sebagai `backup_local.sql`.

### Langkah B: Import ke Hosting
1. Login ke Panel InfinityFree -> Masuk ke **phpMyAdmin**.
2. Pilih database hosting Anda.
3. (Opsional) Hapus semua tabel jika ingin bersih total (Drop all tables), atau biarkan jika Anda yakin akan ditimpa.
4. Klik tab **Import**.
5. Upload file `backup_local.sql` yang tadi Anda simpan.
6. Klik **Go/Kirim**.

> **PERINGATAN:** Cara ini akan MENGHAPUS data member baru yang mungkin sudah daftar di website asli Anda! Gunakan hati-hati.

---

## Masalah File Gambar (Hero Banner / Foto Profil)

Jika Anda mengupdate Hero Banner, itu melibatkan **file gambar**. Database hanya menyimpan *nama file*-nya (contoh: `banner.jpg`), tapi file aslinya ada di folder penyimpanan.
Walaupun Anda mengcopy database, gambarnya tidak akan muncul jika filenya belum diupload.

### Cara Upload Gambar ke Hosting
Gunakan **FileZilla** untuk mengupload file gambar yang baru Anda tambahkan:

1. Buka FileZilla dan konek ke FTP InfinityFree.
2. Di panel **Kiri (Local)**, buka folder project Anda:
   `C:\laragon\www\grit-fitness-laravel\storage\app\public`
   *(Catatan: Gambar biasanya ada di sini)*
3. Di panel **Kanan (Server)**, buka folder:
   `/htdocs/storage` (Jika Anda mengikuti struktur standard)
   *ATAU jika di hosting struktur folder `public` disatukan dengan `htdocs`:*
   `/htdocs/storage`
4. **Upload** file gambar baru dari Kiri ke Kanan.

### Troubleshooting Gambar Tidak Muncul (404)
Di hosting InfinityFree (Shared Hosting), seringkali `symlink` (jalur pintas) dari folder `public/storage` ke `storage/app/public` tidak jalan atau hilang.

**Solusi Manual:**
1. Di FileZilla (Sisi Server/Kanan), cek apakah ada folder bernama `storage` di dalam `/htdocs`.
2. Pastikan isi folder tersebut adalah file-file gambar Anda.
3. Jika gambar tidak muncul, coba copy isi folder `storage/app/public` dari Local langsung ke `/htdocs/storage` di Server.

---

## Ringkasan Workflow (Cara Kerja Sehari-hari)

1. **Coding / Fitur Baru:** Kerjakan di Local. Jika sudah selesai, upload file `.php` yang berubah via FileZilla (Controller, View, dll).
2. **Konten / Data (Jadwal, Berita, Banner):** Langsung login ke **Admin Panel di Website Hosting** dan update di sana. Jangan update di local lalu berharap sinkron.
3. **Database Structure (Kolom Baru):** Jika Anda mengubah struktur coding (misal nambah kolom `instagram_link` di tabel), Anda perlu menjalankan query SQL manual di phpMyAdmin hosting atau Import ulang struktur database.
