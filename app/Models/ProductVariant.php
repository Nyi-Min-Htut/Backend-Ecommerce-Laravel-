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
        return $this->belongsToMany(Attribute::class);
    }
}
