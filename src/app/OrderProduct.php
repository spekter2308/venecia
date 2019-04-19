<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    protected $table = 'order_products';

    protected $fillable = ['full_name','payment_method','product_qty','city_id','postDepartment','email','size'];


    public function user() {
        return $this->belongsTo('App\User','user_id');
    }

    public function product() {
        return $this->belongsTo('App\Product','product_id');
    }

    public function city(){
        return $this->belongsTo('App\Cities','city_id');
    }
}
