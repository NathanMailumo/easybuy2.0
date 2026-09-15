<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seller extends Model
{
    protected $table = 'sellers';

    protected $fillable = [
        'user_id',          
        'store_name',
        'store_slug',
        // 'phone_number',
        'store_address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
