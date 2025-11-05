<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // Tambahkan ini jika belum ada
use Illuminate\Database\Eloquent\SoftDeletes; // Tambahkan ini karena migrasi Anda ada softDeletes()
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany; // <-- TAMBAHKAN INI
use Illuminate\Database\Eloquent\Relations\BelongsTo; // <-- TAMBAHKAN INI

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes; // Tambahkan HasFactory dan SoftDeletes

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'profile_photo',
        'role',
        'membership_status',
        'membership_package', // <-- Kunci relasi (string)
        'membership_expiry',
        'remaining_sessions',
        'joined_date',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'membership_expiry' => 'date',
        'joined_date' => 'date',
        'password' => 'hashed',
    ];

    // Relationships
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    // ==============================================================
    //  TAMBAHKAN FUNGSI INI (INI YANG MEMPERBAIKI ERROR ANDA)
    // ==============================================================
    /**
     * Mendapatkan paket membership yang dimiliki user.
     * Relasi ini mencocokkan kolom string 'membership_package' di tabel 'users'
     * dengan kolom string 'name' di tabel 'membership_packages'.
     */
    public function membership(): BelongsTo
    {
        // Pastikan nama modelnya 'MembershipPackage'
        return $this->belongsTo(MembershipPackage::class, 'membership_package', 'name');
    }
    // ==============================================================


    // Helper methods (Digunakan di LoginController dan Middleware)
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    // ... (sisa method Anda tidak perlu diubah) ...
    public function isMember()
    {
        return $this->role === 'member';
    }

    public function isGuest()
    {
        return $this->role === 'guest';
    }

    public function hasActiveMembership()
    {
        return $this->membership_status === 'active';
    }

    public function isPending()
    {
        return $this->membership_status === 'pending';
    }
    
    public function getMembershipStatusBadgeClass()
    {
        return match($this->membership_status) {
            'active' => 'bg-success',
            'pending' => 'bg-warning',
            'expired' => 'bg-danger',
            default => 'bg-secondary',
        };
    }
}

