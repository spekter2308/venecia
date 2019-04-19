<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FilterName extends Model
{
    protected $fillable = ['type_id', 'name', 'name_ru'];
    public $timestamps = false;

    public function filter_type() {
        return $this->belongsTo('App\FilterType', 'type_id', 'id');
    }

    public function filter_values() {
        return $this->hasMany('App\FilterValue', 'name_id' , 'id' );
    }
}
