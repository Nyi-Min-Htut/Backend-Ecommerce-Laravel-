<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'image_url',
        'image_path',
        'product_variant_id'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
