<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'description',
        'price',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function productImages()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function productVariants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function favCustomer()
    {
        return $this->belongsToMany(Customer::class, 'customer_fav_products', 'product_id', 'customer_id')->withTimestamps();
    }

    public function saveCustomer()
    {
        return $this->belongsToMany(Customer::class, 'customer_save_products', 'product_id', 'customer_id')->withTimestamps();
    }

    public function ratingCustomer()
    {
        return $this->belongsToMany(Customer::class,'customer_rating_products','product_id','customer_id')->withTimestamps();
    }
}
