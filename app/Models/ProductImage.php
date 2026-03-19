<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'image_path',
        'is_primary',
    ];

    protected $appends = ['image_url'];

    public function product() { return $this->belongsTo(Product::class); }

    public function getImageUrlAttribute()
    {
        if (!$this->image_path) return null;

        if (filter_var($this->image_path, FILTER_VALIDATE_URL)) {
            return $this->image_path;
        }

        $cloudName = env('CLOUDINARY_CLOUD_NAME', 'dnrblpkal');
        $version = config('cloudinary.asset_version', 'v1773906173');
        $prefix = config('cloudinary.upload_folder', '');
        
        $url = "https://res.cloudinary.com/{$cloudName}/image/upload/{$version}";
        
        if (!empty($prefix)) {
            $url .= "/{$prefix}";
        }

        return "{$url}/{$this->image_path}";
    }
}
