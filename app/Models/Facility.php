<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Facility extends Model
{
    protected $table = 'facilities';

    protected $fillable = [
        'name',
        'description',
        'icon',
        'image',
        'is_active',
        'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }

    /**
     * Helpers
     */

    /**
     * Get the full URL for the facility image
     *
     * @return string
     */
    public function getImageUrl()
    {
        if (empty($this->image)) {
            return asset('images/placeholder-facility.jpg'); // Default placeholder
        }

        // If image is a full URL, return it directly
        if (str_starts_with($this->image, 'http')) {
            return $this->image;
        }

        // If image contains a path (from file upload)
        if (str_contains($this->image, '/')) {
            if (Storage::disk('public')->exists($this->image)) {
                return Storage::disk('public')->url($this->image);
            }
        }
        // Otherwise, it's a simple filename (from seeder)
        else {
            $publicPath = 'images/facilities/'.$this->image;
            if (file_exists(public_path($publicPath))) {
                return asset($publicPath);
            }
        }

        // Fallback to placeholder if no image is found
        return asset('images/placeholder-facility.jpg');
    }

    /**
     * Get image attribute (accessor)
     * Auto-generates full URL when accessing image
     *
     * @return string
     */
    public function getImageUrlAttribute()
    {
        return $this->getImageUrl();
    }

    /**
     * Get description as array
     * Useful if description contains comma-separated features
     *
     * @return array
     */
    public function getDescriptionArray()
    {
        if (empty($this->description)) {
            return [];
        }

        return array_filter(
            array_map('trim', explode(',', $this->description)),
            fn ($item) => ! empty($item)
        );
    }

    /**
     * Get features attribute (accessor)
     * Auto-converts description to array
     *
     * @return array
     */
    public function getFeaturesAttribute()
    {
        return $this->getDescriptionArray();
    }
}
