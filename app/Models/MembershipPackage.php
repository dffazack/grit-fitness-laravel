<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MembershipPackage extends Model
{
    protected $fillable = [
        'type',
        'name',
        'price',
        'duration_months',
        'features',
        'description',
        'is_active',
    ];

    protected $casts = [
        'features' => 'array',
        'price' => 'decimal:2',
        'is_active' => 'boolean',
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

    public function isBasic()
    {
        return $this->type === 'basic';
    }

    public function isPremium()
    {
        return $this->type === 'premium';
    }

    public function isVip()
    {
        return $this->type === 'vip';
    }
}
