<?php

namespace App;

use App\ProductPhoto;
use App\Brand;
use App\Category;
use Illuminate\Database\Eloquent\Model;

class Product extends Model {

    protected $table = 'products';

    protected $fillable = [
        'code',
        'active',
        'product_name',
        'product_name_ru',
        'product_qty',
        'product_sku',
        'main_category_id',
        'price',
        'discount',
        'reduced_price',
        'cat_id',
        'featured',
        'brand_id',
        'description',
        'description_ru',
        'seo_keywords',
        'seo_title',
        'seo_description',
    ];

    //protected $gaurded = ['id'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */

    public function product_material()
    {
        return $this->hasOne('App\Product_material');

    }

    /**
     * One Product can have one Category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function category() {
        return $this->belongsToMany('App\Category');
    }

    public function main_category() {
        return $this->belongsTo('App\Category', 'main_category_id', 'id');
    }

    public function product_information() {
        return $this->hasMany('App\ProductInformation');
    }

    public function filters() {
        return $this->hasMany('App\FilterValue');
    }


    // do same thing above for category() if you want to show what category a certain product is under in products page.

    /**
     * A Product Belongs To a Brand
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function brand() {
        return $this->belongsTo('App\Brand');
    }


    /**
     * Save a Product to the ProductPhoto instance.
     *
     * @param ProductPhoto $ProductPhoto
     * @return Model
     */
    public function addPhoto(ProductPhoto $ProductPhoto) {
        return $this->photos()->save($ProductPhoto);
    }


    /**
     * One Product can have many photos.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function photos() {
        return $this->hasMany('App\ProductPhoto');
    }


    /**
     * Return a product can have one featured photo where "featured" column = true (or 1)
     *
     * @return mixed
     */
    public function featuredPhoto() {
        return $this->hasOne('App\ProductPhoto')->whereFeatured(true);
    }


    /**
     * Show a product when clicked on (Admin side).
     *
     * @param $id
     * @return mixed
     */
    public static function LocatedAt($id) {
        return static::where(compact('id'))->firstOrFail();
    }


    /**
     * Show a Product when clicked on.
     *
     * @param $product_name
     * @return mixed
     */
    public static function ProductLocatedAt($product_name) {
        return static::where(compact('product_name'))->firstOrFail();
    }


//    public function cat(){
//        return $this->belongsTo('App\Category');
//    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public static function related ($collection, $product) {
        $sku_for_search = preg_replace("/ [a-zA-Z0-9]+$/", "", $product->product_sku);

        return $collection->reject(function($element) use ($sku_for_search, $product) {
            return mb_strpos($element->product_sku, $sku_for_search) === false || ( $element->id == $product->id );
        });
    }

}