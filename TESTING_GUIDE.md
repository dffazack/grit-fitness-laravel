# Panduan Testing Aplikasi Grit Fitness

Dokumen ini menjelaskan serangkaian *feature tests* yang telah dibuat untuk memastikan fungsionalitas utama aplikasi Grit Fitness berjalan sesuai harapan. Tes ini ditulis menggunakan framework PHPUnit yang terintegrasi dengan Laravel.

## Cara Menjalankan Tes

Untuk menjalankan semua tes yang telah dibuat, gunakan perintah Artisan berikut dari direktori root proyek:

```bash
php artisan test
```

Perintah ini akan menjalankan semua file tes yang ada di dalam direktori `tests/Feature` dan `tests/Unit`.

---

## Rangkuman Tes

Berikut adalah rincian tes yang telah diimplementasikan, dikelompokkan berdasarkan fungsionalitasnya.

### 1. Tes Halaman Publik

Tes ini memastikan bahwa halaman yang dapat diakses oleh publik berfungsi dengan benar.

#### `tests/Feature/ClassPageTest.php`

-   **`test_classes_page_loads_successfully()`**
    -   **Tujuan:** Memverifikasi bahwa halaman daftar kelas (`/classes`) dapat diakses oleh semua pengunjung tanpa perlu login.
    -   **Cara Kerja:** Melakukan permintaan `GET` ke rute `/classes` dan memastikan server memberikan respons `200 OK`, yang menandakan halaman berhasil dimuat.
    -   **Pentingnya:** Menjamin salah satu halaman utama yang dilihat calon anggota dapat diakses tanpa error.

---

### 2. Tes Fungsionalitas Admin

Tes ini mencakup semua fitur yang ada di dalam panel admin, mulai dari autentikasi hingga manajemen data.

#### `tests/Feature/AdminDashboardTest.php`

Fokus pada pengujian akses ke dasbor admin.

-   **`test_unauthenticated_user_is_redirected_from_admin_dashboard()`**
    -   **Tujuan:** Memastikan hanya admin yang sudah login yang dapat mengakses dasbor admin.
    -   **Cara Kerja:** Melakukan permintaan `GET` ke `/admin/dashboard` tanpa autentikasi dan memastikan pengguna dialihkan ke halaman login admin (`/admin/login`).
    -   **Pentingnya:** Melindungi area sensitif aplikasi dari akses yang tidak sah.

-   **`test_authenticated_admin_can_access_dashboard()`**
    -   **Tujuan:** Memverifikasi bahwa admin yang sudah login dapat mengakses dasbornya.
    -   **Cara Kerja:** Membuat sebuah *mock* admin, melakukan login sebagai admin tersebut, lalu mengakses `/admin/dashboard`. Tes memastikan respons server adalah `200 OK`.
    -   **Pentingnya:** Mengonfirmasi bahwa proses login dan akses untuk admin berfungsi normal.

#### `tests/Feature/AdminMemberTest.php`

Fokus pada pengujian fitur melihat dan menghapus data anggota oleh admin.

-   **`test_admin_can_view_members_page()`**
    -   **Tujuan:** Memastikan admin dapat melihat halaman daftar anggota.
    -   **Cara Kerja:** Login sebagai admin dan mengakses `/admin/members`. Tes memastikan halaman berhasil dimuat.
    -   **Pentingnya:** Menjamin fungsionalitas dasar manajemen anggota dapat diakses.

-   **`test_admin_can_delete_a_member()`**
    -   **Tujuan:** Menguji fungsionalitas penghapusan data anggota.
    -   **Cara Kerja:** Membuat admin dan anggota, login sebagai admin, mengirim permintaan `DELETE` ke `/admin/members/{id}`, dan memastikan data anggota tersebut telah ditandai sebagai *soft deleted* di database.
    -   **Pentingnya:** Memastikan admin dapat menghapus pengguna dari sistem tanpa menghapus data secara permanen.

#### `tests/Feature/AdminScheduleTest.php`

Fokus pada pengujian fitur CRUD untuk jadwal kelas.

-   **`test_admin_can_view_schedules_index_page()`**
    -   **Tujuan:** Memastikan admin dapat mengakses halaman utama manajemen jadwal.
    -   **Cara Kerja:** Login sebagai admin dan mengakses `/admin/schedules`.
    -   **Pentingnya:** Memvalidasi bahwa halaman dasar untuk fitur jadwal dapat diakses.

-   **`test_admin_can_store_a_new_schedule()`**
    -   **Tujuan:** Memverifikasi admin dapat menambahkan jadwal kelas baru.
    -   **Cara Kerja:** Mengirim data jadwal baru melalui permintaan `POST` dan memastikan data tersimpan di database.
    -   **Pentingnya:** Menguji fungsionalitas inti dari pembuatan data jadwal.

-   **`test_admin_can_update_a_schedule()`**
    -   **Tujuan:** Memverifikasi admin dapat memperbarui jadwal kelas yang ada.
    -   **Cara Kerja:** Mengirim data yang telah diubah melalui permintaan `PUT` untuk jadwal yang sudah ada dan memastikan data di database telah diperbarui.
    -   **Pentingnya:** Memastikan admin dapat mengelola dan mengubah jadwal jika diperlukan.

-   **`test_admin_can_delete_a_schedule()`**
    -   **Tujuan:** Memverifikasi admin dapat menghapus jadwal kelas.
    -   **Cara Kerja:** Mengirim permintaan `DELETE` untuk jadwal tertentu dan memastikan jadwal tersebut telah di-*soft delete*.
    -   **Pentingnya:** Memastikan admin dapat menonaktifkan atau menghapus jadwal yang tidak lagi relevan.

#### `tests/Feature/AdminMembershipPackageTest.php`

Fokus pada pengujian fitur CRUD untuk paket keanggotaan.

-   **`test_admin_can_view_packages_index_page()`**
    -   **Tujuan:** Memastikan admin dapat mengakses halaman utama manajemen paket keanggotaan.
    -   **Cara Kerja:** Login sebagai admin dan mengakses `/admin/memberships`.
    -   **Pentingnya:** Memvalidasi bahwa halaman dasar untuk fitur paket keanggotaan dapat diakses.

-   **`test_admin_can_store_a_new_package()`**
    -   **Tujuan:** Memverifikasi admin dapat menambahkan paket baru.
    -   **Cara Kerja:** Mengirim data paket baru melalui permintaan `POST` dan memastikan data tersimpan di database.
    -   **Pentingnya:** Menguji fungsionalitas inti dari pembuatan data paket.

-   **`test_admin_can_update_a_package()`**
    -   **Tujuan:** Memverifikasi admin dapat memperbarui paket yang ada.
    -   **Cara Kerja:** Mengirim data yang telah diubah melalui permintaan `PUT` untuk paket yang sudah ada dan memastikan data di database telah diperbarui.
    -   **Pentingnya:** Memastikan admin dapat mengelola dan mengubah detail paket.

-   **`test_admin_can_delete_a_package()`**
    -   **Tujuan:** Memverifikasi admin dapat menghapus paket.
    -   **Cara Kerja:** Mengirim permintaan `DELETE` untuk paket tertentu dan memastikan data terhapus dari database.
    -   **Pentingnya:** Memastikan admin dapat menghapus paket yang sudah tidak berlaku.

#### `tests/Feature/AdminFacilityTest.php`

Fokus pada pengujian fitur CRUD untuk fasilitas.

-   **`test_admin_can_view_facilities_index_page()`**
    -   **Tujuan:** Memastikan admin dapat mengakses halaman manajemen fasilitas.
    -   **Cara Kerja:** Login sebagai admin dan mengakses `/admin/facilities`.
    -   **Pentingnya:** Memvalidasi akses ke halaman utama fitur fasilitas.

-   **`test_admin_can_store_a_new_facility()`**
    -   **Tujuan:** Memverifikasi admin dapat menambahkan fasilitas baru, termasuk upload gambar.
    -   **Cara Kerja:** Mengirim data fasilitas baru beserta file gambar melalui `POST` dan memastikan data serta file tersimpan dengan benar.
    -   **Pentingnya:** Menguji fungsionalitas pembuatan data fasilitas.

-   **`test_admin_can_update_a_facility()`**
    -   **Tujuan:** Memverifikasi admin dapat memperbarui fasilitas yang ada.
    -   **Cara Kerja:** Mengirim data yang telah diubah melalui `PUT` dan memastikan data di database telah diperbarui.
    -   **Pentingnya:** Memastikan admin dapat mengelola data fasilitas.

-   **`test_admin_can_delete_a_facility()`**
    -   **Tujuan:** Memverifikasi admin dapat menghapus fasilitas.
    -   **Cara Kerja:** Mengirim permintaan `DELETE` dan memastikan data terhapus dari database.
    -   **Pentingnya:** Memastikan admin dapat menghapus fasilitas yang tidak lagi digunakan.

#### `tests/Feature/AdminTrainerTest.php`

Fokus pada pengujian fitur CRUD untuk data pelatih (trainer).

-   **`test_admin_can_view_trainers_index_page()`**
    -   **Tujuan:** Memastikan admin dapat mengakses halaman manajemen pelatih.
    -   **Cara Kerja:** Login sebagai admin dan mengakses `/admin/trainers`.
    -   **Pentingnya:** Memvalidasi akses ke halaman utama fitur pelatih.

-   **`test_admin_can_store_a_new_trainer()`**
    -   **Tujuan:** Memverifikasi admin dapat menambahkan pelatih baru.
    -   **Cara Kerja:** Mengirim data pelatih baru melalui `POST` dan memastikan data tersimpan di database.
    -   **Pentingnya:** Menguji fungsionalitas pembuatan data pelatih.

-   **`test_admin_can_update_a_trainer()`**
    -   **Tujuan:** Memverifikasi admin dapat memperbarui data pelatih.
    -   **Cara Kerja:** Mengirim data yang telah diubah melalui `PUT` dan memastikan data di database telah diperbarui.
    -   **Pentingnya:** Memastikan admin dapat mengelola data pelatih.

-   **`test_admin_can_delete_a_trainer()`**
    -   **Tujuan:** Memverifikasi admin dapat menghapus data pelatih.
    -   **Cara Kerja:** Mengirim permintaan `DELETE` dan memastikan data telah di-*soft delete*.
    -   **Pentingnya:** Memastikan admin dapat menonaktifkan data pelatih.

#### `tests/Feature/AdminHomepageTest.php`

Fokus pada pengujian manajemen konten halaman depan (homepage).

-   **`test_admin_can_view_homepage_edit_page()`**
    -   **Tujuan:** Memastikan admin dapat mengakses halaman untuk mengedit konten homepage.
    -   **Cara Kerja:** Login sebagai admin dan mengakses `/admin/homepage/edit`.
    -   **Pentingnya:** Memvalidasi akses ke fitur manajemen homepage.

-   **`test_admin_can_update_hero_section()`**
    -   **Tujuan:** Memverifikasi admin dapat memperbarui konten di bagian "Hero".
    -   **Cara Kerja:** Mengirim data baru melalui `PUT` ke rute `admin.homepage.hero` dan memastikan data di database telah diperbarui.
    -   **Pentingnya:** Menguji kemampuan admin mengubah bagian utama homepage.

-   **`test_admin_can_update_stats_section()`**
    -   **Tujuan:** Memverifikasi admin dapat memperbarui konten di bagian "Stats".
    -   **Cara Kerja:** Mengirim data baru melalui `PUT` ke rute `admin.homepage.stats` dan memastikan data di database telah diperbarui.
    -   **Pentingnya:** Menguji kemampuan admin mengubah data statistik di homepage.

#### `tests/Feature/AdminNotificationTest.php`

Fokus pada pengujian fitur CRUD untuk notifikasi.

-   **`test_admin_can_view_notifications_index_page()`**
    -   **Tujuan:** Memastikan admin dapat mengakses halaman manajemen notifikasi.
    -   **Cara Kerja:** Login sebagai admin dan mengakses `/admin/notifications`.
    -   **Pentingnya:** Memvalidasi akses ke halaman utama fitur notifikasi.

-   **`test_admin_can_store_a_new_notification()`**
    -   **Tujuan:** Memverifikasi admin dapat membuat notifikasi baru.
    -   **Cara Kerja:** Mengirim data notifikasi baru melalui `POST` dan memastikan data tersimpan di database.
    -   **Pentingnya:** Menguji fungsionalitas pembuatan notifikasi.

-   **`test_admin_can_update_a_notification()`**
    -   **Tujuan:** Memverifikasi admin dapat memperbarui notifikasi.
    -   **Cara Kerja:** Mengirim data yang telah diubah melalui `PUT` dan memastikan data di database telah diperbarui.
    -   **Pentingnya:** Memastikan admin dapat mengelola konten notifikasi.

-   **`test_admin_can_delete_a_notification()`**
    -   **Tujuan:** Memverifikasi admin dapat menghapus notifikasi.
    -   **Cara Kerja:** Mengirim permintaan `DELETE` dan memastikan data terhapus dari database.
    -   **Pentingnya:** Memastikan admin dapat menghapus notifikasi yang sudah tidak relevan.

-   **`test_admin_can_toggle_notification_status()`**
    -   **Tujuan:** Memverifikasi admin dapat mengaktifkan atau menonaktifkan notifikasi.
    -   **Cara Kerja:** Mengirim permintaan `POST` ke rute `toggle` dan memastikan status `is_active` di database berubah.
    -   **Pentingnya:** Memberikan cara cepat untuk mengelola visibilitas notifikasi.

---

### 3. Tes Fungsionalitas Member

Tes ini mencakup fitur-fitur yang tersedia untuk pengguna dengan peran sebagai anggota.

#### `tests/Feature/MemberScheduleTest.php`

Fokus pada pengujian fitur yang berhubungan dengan jadwal dan pemesanan kelas.

-   **`test_authenticated_member_can_view_schedule_page()`**
    -   **Tujuan:** Memastikan anggota yang sudah login dapat melihat halaman jadwal kelas.
    -   **Cara Kerja:** Login sebagai anggota dan mengakses `/member/schedule`. Tes memastikan halaman berhasil dimuat.
    -   **Pentingnya:** Menjamin anggota dapat mengakses fitur inti untuk melihat jadwal.

-   **`test_member_can_book_a_class()`**
    -   **Tujuan:** Memverifikasi bahwa anggota dapat memesan kelas.
    -   **Cara Kerja:** Login sebagai anggota (dengan status keanggotaan aktif), mengirim permintaan `POST` untuk memesan jadwal kelas tertentu, dan memastikan
    -
    -    data pemesanan (`booking`) tersimpan di database.
    -   **Pentingnya:** Menguji alur utama dari fungsionalitas pemesanan kelas.

-   **`test_member_can_cancel_a_booking()`**
    -   **Tujuan:** Memverifikasi bahwa anggota dapat membatalkan pemesanan kelas.
    -   **Cara Kerja:** Membuat anggota dan pemesanan, login sebagai anggota, mengirim permintaan `POST` untuk membatalkan pemesanan, dan memastikan data pemesanan tersebut terhapus dari database.
    -   **Pentingnya:** Memastikan anggota memiliki fleksibilitas untuk mengelola jadwal mereka.

---

### Perbaikan Bug Selama Testing

Selama proses pembuatan tes, beberapa bug berhasil diidentifikasi dan diperbaiki, yang menunjukkan nilai penting dari proses testing itu sendiri:
1.  **Migrasi Database Ganda:** Terdapat beberapa file migrasi yang membuat kolom yang sama berulang kali, menyebabkan error saat tes dijalankan. File migrasi yang berlebihan telah dihapus.
2.  **Pengalihan Login Admin:** Pengguna yang tidak terautentikasi salah dialihkan ke halaman login member, bukan halaman login admin. Ini diperbaiki dengan mengonfigurasi *exception handler* di `bootstrap/app.php`.
3.  **Missing `HasFactory` Trait:** Beberapa model tidak memiliki trait `HasFactory`, sehingga tidak dapat digunakan untuk membuat data palsu dalam tes. Trait ini telah ditambahkan pada model yang relevan.
4.  **Metode Controller Tidak Ada:** Beberapa rute mengarah ke metode yang belum dibuat di dalam controller, yang menyebabkan *server error*. Metode yang hilang telah ditambahkan.

Ini menunjukkan bahwa proses testing tidak hanya memvalidasi fungsionalitas, tetapi juga membantu menemukan dan memperbaiki masalah tersembunyi dalam aplikasi.


