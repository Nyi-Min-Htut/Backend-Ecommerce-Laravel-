<?php

namespace App\Models;

use App\Models\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;
    protected $fillable = [
        'name','product_id','price','stock','description'
    ];

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class,'product_variant_attribute')->withPivot('value');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function productImages()
    {
        return $this->hasMany(ProductImage::class, 'product_variant_id');
    }
}
