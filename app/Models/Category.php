<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'description',
        'image',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        if (!$this->image) return null;

        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }

        $cloudName = env('CLOUDINARY_CLOUD_NAME', 'dnrblpkal');
        $version = config('cloudinary.asset_version', 'v1773906173');
        $prefix = config('cloudinary.upload_folder', '');
        
        $url = "https://res.cloudinary.com/{$cloudName}/image/upload/{$version}";
        
        if (!empty($prefix)) {
            $url .= "/{$prefix}";
        }
        
        return "{$url}/{$this->image}";
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }
}
