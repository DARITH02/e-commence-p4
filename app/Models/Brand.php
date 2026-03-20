<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'logo',
        'description',
        'website',
        'is_active',
        'sort_order',
    ];

    protected $appends = ['logo_url'];

    /**
     * Get the products for the brand.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function getLogoUrlAttribute()
    {
        if (!$this->logo) return null;

        if (filter_var($this->logo, FILTER_VALIDATE_URL)) {
            return $this->logo;
        }

        // Logic similar to ProductImage for Cloudinary fallback
        try {
            $cloudName = env('CLOUDINARY_CLOUD_NAME', 'dnrblpkal');
            $version   = config('cloudinary.asset_version', 'v1773906173');
            $prefix    = config('cloudinary.upload_folder', '');
            
            $url = "https://res.cloudinary.com/{$cloudName}/image/upload/{$version}";
            
            if (!empty($prefix)) {
                $url .= "/{$prefix}";
            }

            return "{$url}/{$this->logo}";
        } catch (\Exception $e) {
            return null;
        }
    }
}
