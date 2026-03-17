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
        return \Illuminate\Support\Facades\Storage::disk('public')->url($this->image_path);
    }
}
