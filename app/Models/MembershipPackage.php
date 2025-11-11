<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MembershipPackage extends Model
{
    protected $table = 'membership_packages';

    public const TYPES = [
        'regular' => 'Regular',
        'student' => 'Student',
    ];
    
    protected $fillable = [
        'type',
        'name',
        'price',
        'duration_months',
        'features',
        'description',
        'is_active',
        'is_popular'
    ];

    protected $casts = [
        'features' => 'array',
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'is_popular' => 'boolean',
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Helpers
    public function getFormattedPrice()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

<<<<<<< HEAD
    public function isBasic()
    {
        return $this->type === 'basic';
    }

    public function isPremium()
    {
        return $this->type === 'premium';
=======
    public function isRegular()
    {
        return $this->type === 'regular';
    }

    public function isStudent()
    {
        return $this->type === 'student';
>>>>>>> origin/admin-features
    }

    public function isVip()
    {
        return $this->type === 'vip';
    }
}