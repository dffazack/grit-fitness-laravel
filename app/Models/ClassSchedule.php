<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
<<<<<<< HEAD
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassSchedule extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
=======
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes; // Tambahkan ini jika Anda punya kolom deleted_at

class ClassSchedule extends Model
{
    use HasFactory, SoftDeletes; // Tambahkan SoftDeletes jika Anda punya kolom deleted_at
     public const DAYS = [
        'Senin', 
        'Selasa', 
        'Rabu', 
        'Kamis', 
        'Jumat', 
        'Sabtu', 
        'Minggu'
    ];

     public const CLASS_TYPES = [
        'Cardio',
        'Strength',
        'Yoga',
        'HIIT',
        'Pilates',
        'Boxing',
        'Zumba',
        'Other' // Selalu siapkan 'Other' untuk jaga-jaga
    ];
    /**
     * Nama tabel yang terkait dengan model ini.
     */
    protected $table = 'class_schedules';

    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     * (Sesuaikan dengan kolom 'name' dari tabel Anda)
     */
    protected $fillable = [
        'name', // Pastikan Anda punya kolom 'name', bukan 'idnameday...'
>>>>>>> origin/admin-features
        'day',
        'start_time',
        'end_time',
        'trainer_id',
        'quota',
        'max_quota',
        'type',
        'description',
        'is_active',
    ];

<<<<<<< HEAD
    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function trainer()
    {
        return $this->belongsTo(Trainer::class);
    }

    public function participants()
    {
        return $this->hasMany(ClassParticipant::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeDay($query, $day)
    {
        return $query->where('day', $day);
    }

    // Helpers
    public function getFormattedTime()
    {
        return $this->start_time->format('H:i') . ' - ' . $this->end_time->format('H:i');
    }

    public function isFull()
    {
        return $this->quota >= $this->max_quota;
    }

    public function hasAvailableSlots()
    {
        return $this->quota < $this->max_quota;
    }

    public function getAvailableSlots()
    {
        return $this->max_quota - $this->quota;
    }

    public function getQuotaPercentage()
    {
        if ($this->max_quota == 0) return 0;
        return round(($this->quota / $this->max_quota) * 100);
    }
=======
    /**
     * Atribut yang harus di-cast ke tipe data tertentu.
     */
    protected $casts = [
        'start_time' => 'datetime:H:i', // Format Jam:Menit
        'end_time' => 'datetime:H:i',   // Format Jam:Menit
        'is_active' => 'boolean',       // tinyint(1) akan jadi true/false
        'quota' => 'integer',
        'max_quota' => 'integer',
    ];

    // --- RELASI ---

    /**
     * Mendapatkan semua booking untuk jadwal kelas ini.
     * (INI YANG MEMPERBAIKI ERROR ANDA)
     */
    public function bookings(): HasMany
    {
        // Satu ClassSchedule 'hasMany' (memiliki banyak) Booking
        return $this->hasMany(Booking::class);
    }

    /**
     * Mendapatkan trainer yang mengajar kelas ini.
     * (Ini diperlukan oleh ->with('trainer') di controller)
     */
    public function trainer(): BelongsTo
    {
        // Satu ClassSchedule 'belongsTo' (dimiliki oleh) satu Trainer
        return $this->belongsTo(Trainer::class);
    }
>>>>>>> origin/admin-features
}