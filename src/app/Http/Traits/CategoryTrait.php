<?php
namespace App\Http\Traits;

use App\Category;
use Illuminate\Support\Facades\DB;

trait CategoryTrait {


    public function categoryAll() {
        return Category::whereNull('parent_id')->orderBy('sequence')->with(array('children' => function($query) {$query->orderBy('sequence');}))->get();
    }
    

    /**
     * Return only the Parent Categories.
     * ( Used to populate Category drop-down )
     *
     * @return mixed
     */
    public function parentCategory() {
        return Category::whereNull('parent_id')->get();
    }


}