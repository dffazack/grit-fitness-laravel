<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomepageContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'section',
        'content',
        'is_active',
    ];

    protected $casts = [
        'content' => 'array',
        'is_active' => 'boolean',
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeSection($query, $section)
    {
        return $query->where('section', $section);
    }

    // Helpers
    public function getContentValue($key, $default = null)
    {
        return $this->content[$key] ?? $default;
    }

    public function setContentValue($key, $value)
    {
        $content = $this->content ?? [];
        $content[$key] = $value;
        $this->content = $content;
        return $this;
    }
}