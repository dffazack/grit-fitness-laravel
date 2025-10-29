<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassSchedule extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
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
}