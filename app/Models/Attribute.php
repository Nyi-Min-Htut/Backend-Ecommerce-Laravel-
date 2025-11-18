<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name','description','type'
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class,'category_attribute');
    }

    public function productVariants()
    {
        return $this->belongsToMany(ProductVariant::class,'product_variant_attribute')->withPivot('value');
    }
}
