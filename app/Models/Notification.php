<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Notification extends Model
{
    protected $fillable = [
        'title',
        'message',
        'type',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where('start_date', '<=', Carbon::now())
            ->where('end_date', '>=', Carbon::now());
    }

    public function scopeType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Helpers
    public function isCurrentlyActive()
    {
        if (!$this->is_active) return false;
        
        $now = Carbon::now();
        return $this->start_date <= $now && $this->end_date >= $now;
    }

    public function getTypeBadgeClass()
    {
        return match($this->type) {
            'promo' => 'bg-success',
            'event' => 'bg-info',
            'announcement' => 'bg-warning',
            default => 'bg-secondary',
        };
    }

    public function getTypeLabel()
    {
        return match($this->type) {
            'promo' => 'Promo',
            'event' => 'Event',
            'announcement' => 'Pengumuman',
            default => 'Lainnya',
        };
    }
}
