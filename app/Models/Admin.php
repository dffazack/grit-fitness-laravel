<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Ganti ini
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable // Ganti ini
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Tidak perlu $casts untuk email_verified_at jika tidak ada
}