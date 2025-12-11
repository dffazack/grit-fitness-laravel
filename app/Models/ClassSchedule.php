<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassSchedule extends Model
{
    use HasFactory, SoftDeletes;

    public const DAYS = [
        'Senin',
        'Selasa',
        'Rabu',
        'Kamis',
        'Jumat',
        'Sabtu',
        'Minggu',
    ];

    public const CLASS_TYPES = [
        'Cardio',
        'Strength',
        'Yoga',
        'HIIT',
        'Pilates',
        'Boxing',
        'Zumba',
    ];

    protected $table = 'class_schedules';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        // 'name' DIHAPUS karena migrasi Anda menghapusnya.
        'class_list_id',
        'custom_class_name',
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

    /**
     * Atribut yang harus di-cast ke tipe data tertentu.
     *
     * @var array<string, string>
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
     * Mendapatkan daftar kelas (ClassList) yang terkait dengan jadwal ini.
     */
    public function classList(): BelongsTo
    {
        return $this->belongsTo(ClassList::class);
    }

    /**
     * Mendapatkan semua booking untuk jadwal kelas ini.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Mendapatkan trainer yang mengajar kelas ini.
     */
    public function trainer(): BelongsTo
    {
        return $this->belongsTo(Trainer::class);
    }

    // --- SCOPES ---

    /**
     * Scope untuk mengambil hanya jadwal yang aktif.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope untuk mengambil jadwal berdasarkan hari.
     */
    public function scopeDay($query, $day)
    {
        return $query->where('day', $day);
    }

    // --- HELPERS ---

    /**
     * Mendapatkan format waktu H:i - H:i
     */
    public function getFormattedTime()
    {
        return $this->start_time->format('H:i').' - '.$this->end_time->format('H:i');
    }

    /**
     * Cek apakah kelas sudah penuh.
     */
    public function isFull()
    {
        return $this->quota >= $this->max_quota;
    }

    /**
     * Cek apakah masih ada slot.
     */
    public function hasAvailableSlots()
    {
        return $this->quota < $this->max_quota;
    }

    /**
     * Dapatkan jumlah slot yang tersedia.
     */
    public function getAvailableSlots()
    {
        return $this->max_quota - $this->quota;
    }

    /**
     * Dapatkan persentase kuota.
     */
    public function getQuotaPercentage()
    {
        if ($this->max_quota == 0) {
            return 0;
        }

        return round(($this->quota / $this->max_quota) * 100);
    }

    // DUPLIKAT FUNGSI DAN KOMENTAR SISA MERGE DIHAPUS DARI SINI
}
