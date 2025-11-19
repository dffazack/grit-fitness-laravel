<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // Tambahkan ini jika belum ada
use Illuminate\Database\Eloquent\SoftDeletes; // Tambahkan ini karena migrasi Anda ada softDeletes()
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
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
        'membership_package_id',
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

    public function membership(): BelongsTo
    {
        return $this->belongsTo(MembershipPackage::class, 'membership_package_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
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

