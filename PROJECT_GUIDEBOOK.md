# 📖 Buku Panduan Proyek: Grit Fitness (Laravel)

## Pendahuluan

Selamat datang di buku panduan teknis untuk proyek **Grit Fitness**. Dokumen ini bertujuan untuk memberikan pemahaman yang mendalam tentang arsitektur, komponen, dan alur kerja dari aplikasi web ini. Panduan ini dirancang untuk developer baru maupun yang sudah ada agar dapat memahami dan melanjutkan pengembangan proyek dengan lebih mudah dan efisien.

Aplikasi ini dibangun menggunakan framework **Laravel** dan berfungsi sebagai sistem manajemen untuk pusat kebugaran, mencakup fungsionalitas untuk publik, anggota (member), dan administrator.

---

## 1. Konfigurasi Proyek

Konfigurasi utama proyek Laravel berada di dalam direktori `config` dan file `.env` di root proyek.

### File `.env`

File ini berisi konfigurasi yang spesifik untuk lingkungan (environment) tempat aplikasi berjalan. Ini adalah tempat untuk menyimpan kredensial, kunci API, dan pengaturan lainnya yang sensitif atau bervariasi antar lingkungan (development, staging, production).

**Pengaturan Kunci:**
- `APP_NAME`: Nama aplikasi (Contoh: "Grit Fitness").
- `APP_URL`: URL utama aplikasi (Contoh: `http://localhost:8000`).
- `DB_CONNECTION`: Jenis database yang digunakan (Contoh: `mysql`).
- `DB_HOST`: Alamat host database (Contoh: `127.0.0.1`).
- `DB_PORT`: Port database (Contoh: `3306`).
- `DB_DATABASE`: Nama database (Contoh: `grit_fitness_db`).
- `DB_USERNAME`: Username untuk koneksi database (Contoh: `root`).
- `DB_PASSWORD`: Password untuk koneksi database.

### File Konfigurasi Penting

- **`config/app.php`**: Berisi konfigurasi inti aplikasi seperti `timezone` (zona waktu, misal: `Asia/Jakarta`) dan `locale` (bahasa, misal: `id`).
- **`config/database.php`**: Mendefinisikan semua koneksi database yang bisa digunakan oleh aplikasi. Pengaturan di file `.env` akan menimpa nilai default di sini.
- **`config/auth.php`**: Mengatur sistem autentikasi. Di proyek ini, kita mendefinisikan dua **guards**:
    - `web`: Untuk autentikasi pengguna/member biasa (menggunakan model `App\Models\User`).
    - `admin`: Untuk autentikasi administrator (menggunakan model `App\Models\Admin`).

---

## 2. Struktur Folder

Proyek ini mengikuti struktur folder standar Laravel dengan beberapa penyesuaian untuk kebutuhan aplikasi.

- **`app/Http/Controllers`**: Berisi semua controller yang menangani request HTTP.
    - **`Admin/`**: Sub-folder khusus untuk controller yang menangani logika di panel admin (misal: `Admin/TrainerController.php`).
    - **`Member/`**: Sub-folder untuk controller yang menangani logika di dashboard member (misal: `Member/DashboardController.php`).
    - **`Auth/`**: Controller untuk proses autentikasi (login, register), termasuk `AdminLoginController.php` yang terpisah.

- **`app/Models`**: Berisi semua kelas [Eloquent ORM](#5-model--eloquent-orm) yang merepresentasikan tabel di database (misal: `User.php`, `Trainer.php`, `ClassSchedule.php`).

- **`database/migrations`**: Berisi file-file migrasi yang mendefinisikan skema (struktur) database. Setiap file merepresentasikan satu perubahan pada database.

- **`database/seeders`**: Berisi kelas-kelas untuk mengisi data awal (dummy data) ke dalam database, sangat berguna untuk development.

- **`resources/views`**: Berisi semua file [Blade](#3-blade-templating-engine) (template HTML).
    - **`admin/`**: Tampilan khusus untuk panel admin.
    - **`member/`**: Tampilan khusus untuk dashboard member.
    - **`layouts/`**: Template layout utama (`app.blade.php` untuk publik & member, `admin.blade.php` untuk admin).
    - **`components/`**: Potongan-potongan UI yang dapat digunakan kembali (reusable) seperti `navbar.blade.php` dan `footer.blade.php`.

- **`routes/web.php`**: File utama untuk mendefinisikan semua URL (rute) aplikasi web.

---

## 3. Blade Templating Engine

Blade adalah templating engine bawaan Laravel yang simpel namun powerful.

### Layouts
Layouts adalah template dasar dari sebuah halaman. Proyek ini menggunakan dua layout utama:
1.  **`resources/views/layouts/app.blade.php`**: Layout untuk halaman publik dan dashboard member.
2.  **`resources/views/layouts/admin.blade.php`**: Layout untuk semua halaman di panel admin.

Sebuah view dapat "mewarisi" sebuah layout menggunakan direktif `@extends`.
```blade
{{-- Contoh di resources/views/classes/index.blade.php --}}
@extends('layouts.app')
```

### Sections & Yield
Konten spesifik dari sebuah halaman didefinisikan di dalam `@section` dan disisipkan ke dalam layout di posisi `@yield`.

**Di Layout (`layouts/app.blade.php`):**
```blade
<main>
    @yield('content')
</main>
```

**Di View Halaman (`classes/index.blade.php`):**
```blade
@section('content')
    {{-- Seluruh konten HTML halaman ini ada di sini --}}
    <h1>Jadwal Kelas & Trainer</h1>
    ...
@endsection
```

### Components
Komponen adalah file Blade yang dapat disisipkan ke dalam view lain menggunakan `@include`. Ini membantu menjaga kode tetap DRY (Don't Repeat Yourself).
```blade
{{-- Contoh di layouts/app.blade.php --}}
@include('components.navbar')
...
@include('components.footer')
```

---

## 4. Menampilkan Data di View

Data dari database atau logika bisnis diproses di **Controller** dan kemudian dikirim ke **View** untuk ditampilkan.

**Contoh Alur:**
1.  **Controller (`app/Http/Controllers/ClassController.php`)** mengambil data dari database.
    ```php
    public function index()
    {
        $schedules = ClassSchedule::with('trainer')->get();
        $trainers = Trainer::where('is_active', true)->get();
        
        // Mengirim variabel $schedules dan $trainers ke view
        return view('classes.index', compact('schedules', 'trainers'));
    }
    ```

2.  **View (`resources/views/classes/index.blade.php`)** menerima data tersebut dan menampilkannya menggunakan sintaks Blade.
    ```blade
    @forelse($schedules as $day => $daySchedules)
        <h3>{{ $day }}</h3>
        @foreach($daySchedules as $schedule)
            <h5>{{ $schedule->name }}</h5>
            <p>Trainer: {{ $schedule->trainer->name ?? 'N/A' }}</p>
        @endforeach
    @empty
        <p>Belum ada jadwal kelas.</p>
    @endforelse
    ```

---

## 5. Model & Eloquent ORM

Eloquent adalah Object-Relational Mapper (ORM) bawaan Laravel. Setiap tabel database memiliki "Model" yang digunakan untuk berinteraksi dengan tabel tersebut.

- **Lokasi Model**: `app/Models/`
- **Konvensi**: Nama model dalam bentuk tunggal (singular) dan PascalCase (misal: `Trainer`), sedangkan nama tabel dalam bentuk jamak (plural) dan snake_case (misal: `trainers`).

### Contoh Model: `Trainer.php`
```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trainer extends Model
{
    // Atribut yang boleh diisi secara massal
    protected $fillable = [
        'name',
        'specialization',
        'experience',
        'bio',
        'image',
        'email',
        'is_active',
    ];

    // Mengubah tipe data atribut secara otomatis
    protected $casts = [
        'certifications' => 'array', // Kolom JSON akan di-cast menjadi array PHP
        'is_active' => 'boolean',    // Kolom tinyint(1) akan di-cast menjadi true/false
    ];

    // Mendefinisikan relasi
    public function classSchedules()
    {
        return $this->hasMany(ClassSchedule::class);
    }
}
```

---

## 6. Database & Migrations

Migrations berfungsi seperti sistem kontrol versi (version control) untuk database Anda. Mereka memungkinkan tim untuk mendefinisikan dan membagikan skema database aplikasi.

- **Lokasi Migrations**: `database/migrations/`
- **Perintah Artisan**:
    - `php artisan migrate`: Menjalankan semua migrasi yang belum dijalankan.
    - `php artisan make:migration create_nama_tabel_table`: Membuat file migrasi baru.

### Contoh Migrasi: `create_trainers_table.php`
File ini mendefinisikan struktur tabel `trainers`.
```php
public function up(): void
{
    Schema::create('trainers', function (Blueprint $table) {
        $table->id(); // Primary key auto-increment
        $table->string('name');
        $table->string('specialization');
        $table->json('certifications'); // Kolom untuk menyimpan data JSON
        $table->text('bio');
        $table->string('image')->nullable(); // Kolom boleh kosong
        $table->boolean('is_active')->default(true); // Default value true
        $table->timestamps(); // Membuat kolom created_at dan updated_at
        $table->softDeletes(); // Membuat kolom deleted_at untuk soft delete
    });
}
```

---

## 7. Eloquent Relationships

Eloquent memungkinkan Anda untuk mendefinisikan relasi antar model, membuat query menjadi lebih mudah dan intuitif.

### One-to-Many (Satu ke Banyak)
Satu `Trainer` dapat memiliki banyak `ClassSchedule`.
- **Di Model `Trainer.php`:**
  ```php
  public function classSchedules()
  {
      return $this->hasMany(ClassSchedule::class);
  }
  ```

### Many-to-One (Banyak ke Satu / `belongsTo`)
Satu `ClassSchedule` dimiliki oleh satu `Trainer`.
- **Di Model `ClassSchedule.php`:**
  ```php
  public function trainer(): BelongsTo
  {
      return $this->belongsTo(Trainer::class);
  }
  ```
- **Penggunaan**:
  ```php
  // Mengambil jadwal beserta data trainernya dalam satu query (Eager Loading)
  $schedule = ClassSchedule::with('trainer')->find(1);
  echo $schedule->trainer->name;
  ```

---

## 8. Database Seeding

Seeder digunakan untuk mengisi database dengan data awal atau data dummy. Ini sangat penting untuk proses development dan testing.

- **Lokasi Seeder**: `database/seeders/`
- **File Utama**: `DatabaseSeeder.php` adalah file utama yang akan dieksekusi. File ini memanggil seeder-seeder lain.
  ```php
  // Di DatabaseSeeder.php
  public function run(): void
  {
      $this->call([
          UserSeeder::class,
          TrainerSeeder::class,
          ClassScheduleSeeder::class,
          // ... seeder lainnya
      ]);
  }
  ```
- **Perintah Artisan**: `php artisan db:seed`

### Contoh Seeder: `TrainerSeeder.php`
```php
// Di TrainerSeeder.php
public function run(): void
{
    Trainer::create([
        'name' => 'Michael Chen',
        'specialization' => 'Strength & Conditioning',
        'certifications' => ['NSCA-CPT', 'ACE Certified'],
        // ... data lainnya
    ]);
    // ... data trainer lainnya
}
```

---

## 9. Pagination

Laravel menyediakan cara yang sangat mudah untuk membagi kumpulan data yang besar menjadi beberapa halaman.

1.  **Di Controller**: Gunakan method `paginate()` daripada `get()`.
    ```php
    // Di Admin/TrainerController.php
    public function index()
    {
        // Mengambil data trainer, 9 data per halaman
        $trainers = Trainer::latest()->paginate(9);
        return view('admin.trainers.index', compact('trainers'));
    }
    ```

2.  **Di View (Blade)**: Tampilkan link navigasi paginasi dengan method `links()`.
    ```blade
    {{-- Di admin/trainers/index.blade.php --}}
    <div class="card-body">
        {{-- ... loop untuk menampilkan data trainer ... --}}
    </div>
    <div class="card-footer">
        {{ $trainers->links() }}
    </div>
    ```

---

## 10. Logika Alur Aplikasi

Memahami alur dari sebuah request sangat penting.

1.  **Routing (`routes/web.php`)**
    URL yang diakses pengguna pertama kali dicocokkan di file ini. Rute mendefinisikan Controller dan method mana yang akan menangani request tersebut. Proyek ini menggunakan **Route Grouping** secara ekstensif.
    ```php
    // Grup untuk semua rute admin
    Route::prefix('admin')->name('admin.')->group(function () {
        // Rute di dalam sini akan memiliki URL berawalan /admin
        // dan nama berawalan admin.
        // Contoh: Route::get('/dashboard', ...) -> URL: /admin/dashboard, Nama: admin.dashboard
    });
    ```

2.  **Middleware**
    Middleware adalah "filter" yang dijalankan sebelum request mencapai Controller. Ini digunakan untuk proteksi dan validasi.
    - `auth`: Memastikan pengguna sudah login.
    - `auth:admin`: Memastikan pengguna sudah login menggunakan *guard* 'admin'.
    - `role:member`: Middleware kustom (`app/Http/Middleware/CheckRole.php`) yang memeriksa apakah pengguna memiliki peran 'member'.
    ```php
    // Rute ini hanya bisa diakses oleh pengguna yang login dengan guard 'admin'
    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    });
    ```

3.  **Controller**
    Setelah melewati middleware, request ditangani oleh method di dalam Controller. Controller berisi logika utama, berinteraksi dengan Model, dan akhirnya mengembalikan sebuah View.

4.  **View**
    Controller mengembalikan View yang sudah diisi dengan data yang diperlukan. Blade engine akan me-render HTML final yang dikirimkan ke browser pengguna.

### Alur Autentikasi Ganda (User & Admin)
Proyek ini memiliki dua sistem login yang terpisah:
- **Login Member**: Di-handle oleh `Auth/LoginController.php`, menggunakan guard `web` dan model `User`.
- **Login Admin**: Di-handle oleh `Auth/AdminLoginController.php`, menggunakan guard `admin` dan model `Admin`.

Pemisahan ini memberikan lapisan keamanan yang lebih baik karena tabel dan logika untuk pengguna biasa dan administrator sepenuhnya terisolasi.
```

Saya akan menulis file ini sekarang.