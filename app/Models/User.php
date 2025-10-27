<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'profile_photo',
        'role',
        'membership_status',
        'membership_package',
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
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // Helper methods
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

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
