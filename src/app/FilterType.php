<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FilterType extends Model
{
    protected $fillable = ['type', 'type_ru'];
    public $timestamps = false;

    public function filter_names() {
        return $this->hasMany('App\FilterName', 'type_id' , 'id' );
    }

    public function filter_values() {
        return $this->hasMany('App\FilterValue', 'type_id' , 'id' );
    }
}
