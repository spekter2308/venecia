<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FilterValue extends Model
{
    protected $fillable = ['product_id', 'type_id', 'name_id'];
    public $timestamps = false;

    public function product() {
        return $this->belongsTo('App\Product');
    }

    public function filter_type() {
        return $this->belongsTo('App\FilterType');
    }
}
