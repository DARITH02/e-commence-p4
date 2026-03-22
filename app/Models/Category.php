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
        'name',
        'slug',
        'description',
        'image',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $appends = ['image_url', 'full_path', 'depth'];

    public function getDepthAttribute()
    {
        $depth = 0;
        $curr = $this;
        $visited = [$this->id];
        
        // Take the first parent as the primary path for depth
        $parent = $curr->parents()->first();
        while ($parent && $depth < 10) {
            if (in_array($parent->id, $visited)) break;
            $visited[] = $parent->id;
            $depth++;
            $parent = $parent->parents()->first();
        }
        return $depth;
    }

    public function getFullPathAttribute()
    {
        $path = $this->name;
        $parent = $this->parents()->first();
        $depth = 0;
        $visited = [$this->id];
        
        while ($parent && $depth < 5) {
            if (in_array($parent->id, $visited)) break;
            $visited[] = $parent->id;
            $path = $parent->name . ' > ' . $path;
            $parent = $parent->parents()->first();
            $depth++;
        }
        
        return $path;
    }

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

    public function children(): BelongsToMany
    {
        return $this->children_many();
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(CategoryImage::class)->orderBy('sort_order');
    }

    public function parents(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'parent_category', 'category_id', 'parent_id')->withTimestamps();
    }

    public function children_many(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'parent_category', 'parent_id', 'category_id')->withTimestamps();
    }
    public function toArray()
    {
        return parent::toArray();
    }
}
