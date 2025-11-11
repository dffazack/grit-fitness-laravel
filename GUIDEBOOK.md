# Panduan Lengkap Proyek Laravel: Grit Fitness

Dokumen ini adalah panduan lengkap untuk memahami, memelihara, dan mengembangkan proyek **Grit Fitness** yang dibangun menggunakan Framework Laravel.

---

## Daftar Isi
1.  [Struktur Folder Proyek](#1-struktur-folder-proyek)
2.  [Alur Kerja & Routing](#2-alur-kerja--routing)
3.  [Controller: Logika Aplikasi](#3-controller-logika-aplikasi)
4.  [Database & Migration](#4-database--migration)
5.  [Model & Eloquent ORM](#5-model--eloquent-orm)
6.  [Blade: Template Engine](#6-blade-template-engine)
7.  [Blade Components](#7-blade-components)
8.  [Database Seeder & Factory](#8-database-seeder--factory)
9.  [Pagination](#9-pagination)

---

## 1. Struktur Folder Proyek

Laravel memiliki struktur folder yang terorganisir untuk memudahkan pengembangan. Berikut adalah direktori-direktori paling penting dalam proyek Grit Fitness:

-   `app/`: Ini adalah jantung dari aplikasi Anda.
    -   `Http/Controllers/`: Berisi semua controller yang menangani logika untuk setiap permintaan HTTP. Contoh: `MembershipController.php`, `Admin/DashboardController.php`.
    -   `Models/`: Berisi semua model Eloquent yang merepresentasikan tabel dalam database Anda. Contoh: `User.php`, `Trainer.php`, `MembershipPackage.php`.
    -   `Providers/`: Tempat mendaftarkan service-service penting untuk aplikasi.
    -   `Http/Middleware/`: Berisi class-class yang dapat memfilter permintaan HTTP, seperti otentikasi dan otorisasi. Contoh: `CheckRole.php`.

-   `config/`: Berisi semua file konfigurasi aplikasi, seperti koneksi database (`database.php`), aplikasi (`app.php`), dll.

-   `database/`: Semua yang berkaitan dengan database ada di sini.
    -   `migrations/`: Berisi file-file migrasi untuk membangun dan memodifikasi skema database Anda secara bertahap.
    -   `seeders/`: Berisi class untuk mengisi data awal (dummy data) ke dalam database.
    -   `factories/`: Berisi "pabrik" model untuk menghasilkan data palsu dalam jumlah besar, biasanya digunakan dalam seeder atau testing.

-   `public/`: Ini adalah satu-satunya direktori yang dapat diakses secara publik dari browser. Berisi file seperti `index.php` (titik masuk semua permintaan), aset (CSS, JS, gambar), dll.

-   `resources/`: Berisi seluruh aset "mentah" yang akan dikompilasi.
    -   `views/`: Berisi semua file Blade template (tampilan HTML).
    -   `css/` & `js/`: Berisi file CSS dan JavaScript mentah sebelum diproses oleh Vite/Mix.

-   `routes/`: Berisi semua definisi rute (URL) aplikasi.
    -   `web.php`: Untuk rute berbasis web (yang Anda lihat di browser).
    -   `api.php`: Untuk rute API.

-   `storage/`: Berisi file-file yang dihasilkan oleh framework, seperti cache, session, log, dan file yang di-upload oleh pengguna.

-   `vendor/`: Berisi semua dependensi (paket) dari Composer. Anda tidak seharusnya mengubah apapun di direktori ini.

---

## 2. Alur Kerja & Routing

Routing adalah proses mendefinisikan bagaimana aplikasi merespons permintaan URL dari pengguna. Semua rute web didefinisikan di `routes/web.php`.

**Contoh Dasar Route:**

```php
// routes/web.php

use App\Http\Controllers\HomeController;

// Menampilkan halaman utama
Route::get('/', [HomeController::class, 'index']);
```

**Route dengan Middleware:**

Middleware digunakan untuk memproteksi rute. Misalnya, hanya user yang sudah login dan memiliki role 'admin' yang bisa mengakses dashboard admin.

```php
// routes/web.php

use App\Http\Controllers\Admin\DashboardController;

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
});
```

-   `middleware(['auth', 'role:admin'])`: Memastikan hanya user terotentikasi dengan peran 'admin' yang bisa lewat.
-   `group()`: Mengelompokkan beberapa rute di bawah satu aturan middleware yang sama.
-   `name('admin.dashboard')`: Memberi nama pada rute agar mudah dipanggil di view atau controller.

---

## 3. Controller: Logika Aplikasi

Controller bertugas untuk menerima permintaan dari route, berinteraksi dengan Model untuk mengambil data, dan mengirimkan data tersebut ke View.

**Contoh Controller (`MembershipController.php`):**

```php
// app/Http/Controllers/MembershipController.php

namespace App\Http\Controllers;

use App\Models\MembershipPackage;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    /**
     * Menampilkan daftar semua paket membership.
     */
    public function index()
    {
        // 1. Mengambil semua data dari model MembershipPackage
        $packages = MembershipPackage::where('active', true)->get();

        // 2. Mengirim data 'packages' ke view
        return view('membership.index', [
            'packages' => $packages
        ]);
    }
}
```

**Penjelasan:**
1.  Method `index()` dipanggil oleh route yang sesuai.
2.  `MembershipPackage::where('active', true)->get()` adalah query Eloquent untuk mengambil semua paket yang aktif dari database.
3.  `return view('membership.index', ['packages' => $packages]);` merender file `resources/views/membership/index.blade.php` dan menyisipkan variabel `$packages` ke dalamnya.

---

## 4. Database & Migration

Migration adalah sistem kontrol versi untuk database Anda. Ini memungkinkan Anda untuk mendefinisikan dan memodifikasi skema database dalam file PHP.

**Contoh File Migrasi:**

Ini adalah contoh file migrasi untuk membuat tabel `trainers`.
`database/migrations/2025_10_27_122700_create_trainers_table.php`

```php
// database/migrations/xxxx_xx_xx_xxxxxx_create_trainers_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trainers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('specialization');
            $table->text('description')->nullable();
            $table->string('photo_path')->nullable();
            $table->timestamps(); // Membuat kolom created_at dan updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trainers');
    }
};
```
-   Method `up()`: Berisi logika untuk **membuat** atau **mengubah** tabel.
-   `Schema::create('trainers', ...)`: Perintah untuk membuat tabel baru bernama `trainers`.
-   Method `down()`: Berisi logika untuk **membatalkan** apa yang dilakukan di `up()`. Ini digunakan saat rollback.

**Perintah Artisan untuk Migrasi:**
-   `php artisan migrate`: Menjalankan semua migrasi yang belum dijalankan.
-   `php artisan migrate:rollback`: Membatalkan migrasi terakhir.
-   `php artisan make:migration create_nama_tabel_table`: Membuat file migrasi baru.

---

## 5. Model & Eloquent ORM

Model Eloquent adalah representasi dari sebuah tabel di database. Melalui model, Anda bisa berinteraksi dengan data di tabel tersebut menggunakan sintaks PHP yang ekspresif.

**Contoh Model (`Trainer.php`):**

```php
// app/Models/Trainer.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trainer extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model ini.
     * Jika nama model adalah 'Trainer', Laravel akan otomatis mencari tabel 'trainers'.
     */
    // protected $table = 'my_trainers'; // Tidak perlu jika nama tabel sudah sesuai standar

    /**
     * Kolom yang boleh diisi secara massal (mass assignment).
     */
    protected $fillable = [
        'name',
        'specialization',
        'description',
        'photo_path',
    ];
}
```

**Operasi Dasar Eloquent:**
```php
// Mengambil semua data trainer
$trainers = Trainer::all();

// Mencari trainer dengan ID = 1
$trainer = Trainer::find(1);

// Mencari trainer dengan spesialisasi 'Yoga'
$yogaTrainers = Trainer::where('specialization', 'Yoga')->get();

// Membuat data baru
$newTrainer = Trainer::create([
    'name' => 'Budi',
    'specialization' => 'Kardio',
]);

// Mengupdate data
$trainer = Trainer::find(1);
$trainer->name = 'Budi Santoso';
$trainer->save();

// Menghapus data
$trainer = Trainer::find(2);
$trainer->delete();
```

### Relasi Eloquent
Eloquent memungkinkan Anda mendefinisikan hubungan antar model.

**Contoh: `User` dan `MembershipPackage` (Relasi One-to-Many - Invers)**

Seorang `User` memiliki satu `MembershipPackage`.
```php
// app/Models/User.php

public function membershipPackage()
{
    // User ini 'milik' sebuah MembershipPackage
    return $this->belongsTo(MembershipPackage::class, 'membership_package_id');
}
```
Sebuah `MembershipPackage` bisa dimiliki oleh banyak `User`.
```php
// app/Models/MembershipPackage.php

public function users()
{
    // MembershipPackage ini 'memiliki banyak' User
    return $this->hasMany(User::class, 'membership_package_id');
}
```
*Catatan: Contoh ini mengasumsikan Anda telah memperbaiki skema database untuk menggunakan `membership_package_id` sebagai foreign key.*

---

## 6. Blade: Template Engine

Blade adalah template engine dari Laravel. Blade memungkinkan Anda menggunakan sintaks PHP di dalam HTML dengan lebih rapi dan menyediakan fitur-fitur seperti *template inheritance* dan *components*.

**Dasar-dasar Blade:**
-   **Menampilkan Data:** Gunakan kurung kurawal ganda. Sintaks ini sudah otomatis melindungi dari serangan XSS.
    ```blade
    <h1>Halo, {{ $user->name }}</h1>
    ```

-   **Layout & Section:** Anda bisa membuat satu file layout utama dan view lain bisa "mewarisi" layout tersebut.

    **File Layout (`resources/views/layouts/admin.blade.php`):**
    ```blade
    <!DOCTYPE html>
    <html>
    <head>
        <title>Grit Fitness Admin - @yield('title')</title>
    </head>
    <body>
        @include('components.admin-sidebar')

        <div class="content">
            @yield('content')
        </div>
    </body>
    </html>
    ```
    -   `@yield('title')` dan `@yield('content')` adalah "slot" yang akan diisi oleh child view.
    -   `@include('components.admin-sidebar')` menyisipkan view lain (parsial).

    **File Child View (`resources/views/admin/dashboard.blade.php`):**
    ```blade
    @extends('layouts.admin')

    @section('title', 'Dashboard')

    @section('content')
        <h1>Selamat Datang di Dashboard Admin</h1>
        <p>Ini adalah halaman utama panel admin.</p>
    @endsection
    ```
    -   `@extends('layouts.admin')`: Memberitahu Blade untuk menggunakan layout `admin`.
    -   `@section('title', 'Dashboard')`: Mengisi `@yield('title')` di layout.
    -   `@section('content') ... @endsection`: Mengisi `@yield('content')` di layout.

-   **Struktur Kontrol:**
    ```blade
    @if($user->isAdmin())
        <p>Anda adalah admin.</p>
    @else
        <p>Anda adalah member.</p>
    @endif

    <ul>
        @foreach($trainers as $trainer)
            <li>{{ $trainer->name }} - {{ $trainer->specialization }}</li>
        @endforeach
    </ul>
    ```

---

## 7. Blade Components

Komponen adalah bagian dari view yang dapat digunakan kembali, seperti tombol, input form, atau card. Proyek ini sudah memiliki beberapa komponen di `resources/views/components/`.

**Contoh Komponen (`form-input.blade.php`):**

Proyek Anda memiliki komponen `form-input` yang kemungkinan digunakan untuk membuat elemen input form standar.

**Cara Menggunakan Komponen:**

Untuk menggunakan komponen, Anda bisa memanggilnya dengan tag `x-` diikuti nama komponen.

```blade
{{-- di dalam sebuah form --}}

<form method="POST" action="/register">
    @csrf

    {{-- Menggunakan komponen form-input --}}
    <x-form-input
        name="name"
        label="Nama Lengkap"
        type="text"
        placeholder="Masukkan nama Anda"
        required="true"
    />

    <x-form-input
        name="email"
        label="Alamat Email"
        type="email"
        placeholder="email@example.com"
        required="true"
    />

    <x-primary-button>
        Daftar
    </x-primary-button>
</form>
```
Dengan cara ini, Anda tidak perlu menulis kode HTML untuk input form berulang kali. Cukup panggil komponennya.

---

## 8. Database Seeder & Factory

Seeder digunakan untuk mengisi database dengan data awal. Factory digunakan oleh seeder untuk menghasilkan data palsu dalam jumlah besar.

**Contoh Factory (`UserFactory.php`):**
```php
// database/factories/UserFactory.php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'role' => 'member', // Default role adalah member
            'membership_package_id' => \App\Models\MembershipPackage::inRandomOrder()->first()->id, // Contoh
            'remember_token' => Str::random(10),
        ];
    }
}
```
-   `definition()`: Mendefinisikan atribut-atribut dari model `User` dengan data palsu (`fake()`).

**Contoh Seeder (`UserSeeder.php`):**
```php
// database/seeders/UserSeeder.php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Membuat 10 user palsu menggunakan factory
        User::factory(10)->create();
    }
}
```

Untuk menjalankan seeder, gunakan perintah: `php artisan db:seed` atau `php artisan db:seed --class=UserSeeder`.

---

## 9. Pagination

Pagination sangat penting saat menampilkan data dalam jumlah besar agar halaman tidak lambat. Laravel membuat ini sangat mudah.

**Di Controller:**

Ganti method `get()` atau `all()` dengan `paginate()`.

```php
// app/Http/Controllers/Admin/MembersController.php (Contoh)

public function index()
{
    // Ambil semua user dengan role 'member' dan tampilkan 15 per halaman
    $members = User::where('role', 'member')->paginate(15);

    return view('admin.members.index', ['members' => $members]);
}
```

**Di View Blade:**

Untuk menampilkan link pagination (e.g., "1, 2, 3, Next"), cukup tambahkan ini di bawah perulangan data Anda.

```blade
{{-- resources/views/admin/members/index.blade.php --}}

<table>
    {{-- THeader --}}
    <thead>
        <tr>
            <th>Nama</th>
            <th>Email</th>
        </tr>
    </thead>
    {{-- TBody --}}
    <tbody>
        @foreach($members as $member)
            <tr>
                <td>{{ $member->name }}</td>
                <td>{{ $member->email }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

{{-- Menampilkan link pagination --}}
<div class="mt-4">
    {{ $members->links() }}
</div>
```
Laravel akan otomatis menghasilkan HTML untuk navigasi halaman berdasarkan data `$members`.

---
## Kesimpulan

Panduan ini mencakup dasar-dasar dan konsep inti dari proyek Laravel "Grit Fitness" Anda. Dengan pemahaman ini, Anda seharusnya dapat menavigasi codebase, memperbaiki bug, dan menambahkan fitur baru dengan lebih percaya diri. Selalu rujuk ke [Dokumentasi Resmi Laravel](https://laravel.com/docs) untuk penjelasan yang lebih mendalam.
