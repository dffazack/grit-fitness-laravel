<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Trainer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'specialization',
        'experience',
        'clients',
        'bio',
        'image',
        'email',
        'phone',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function classSchedules()
    {
        return $this->hasMany(ClassSchedule::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Helpers
    public function getImageUrl()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return asset('images/default-trainer.jpg');
    }

    public function getTotalClasses()
    {
        return $this->classSchedules()->count();
    }

    public function getActiveClasses()
    {
        return $this->classSchedules()->active()->count();
    }
}

