<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'seller_id',
        'productname',
        'description',
        'productprice',
        'productquantity',
        'is_available',
        'status',
        'category_id',
    ];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    public function cart(){
        return $this->hasMany(cart::class);
    }

}
