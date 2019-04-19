<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OneClickOrder extends Model
{
    protected $fillable = [
        'phone_number',
        'product_id',
        'user_id',
    ];

    public function user() {
        return $this->belongsTo('App\User','user_id');
    }

    public function product() {
        return $this->belongsTo('App\Product','product_id');
    }
}
