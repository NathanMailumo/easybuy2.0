<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'carts';

    protected $fillable = [
        'buyer_id',
        'product_id',
        'quantity',
    ];

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function products()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }

    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }
}
