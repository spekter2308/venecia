<?php
namespace App\Http\Traits;

use App\Category;
use App\Product;
use Illuminate\Support\Facades\Input;

trait SlugTrait {


    /**
     * Perform search capabilities for search bar
     *
     * @return mixed
     */

    public function translitSlug($string){
        $slug = function_exists('mb_strtolower') ? mb_strtolower($string) : strtolower($string);
        $slug = strtr($slug, array('а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','ё'=>'e','ж'=>'j','з'=>'z','и'=>'i','й'=>'y','к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u','ф'=>'f','х'=>'h','ц'=>'c','ч'=>'ch','ш'=>'sh','щ'=>'shch','ы'=>'y','э'=>'e','ю'=>'yu','я'=>'ya','ъ'=>'','ь'=>'','і'=> 'i'));
        $slug = preg_replace("/[^0-9a-z-_ ]/i", "", $slug);
        $slug = str_replace(" ", "-", $slug);

        return $slug;
    }

    public function addSlugToProduct($product) {


       $product_name = $this->translitSlug($product->product_name);
       $art = str_replace(" ", "-", $product->product_sku);
       // add slug to product (product name + art)
       Product::where('id',$product->id)->update(['slug'=>$product_name.'-'.$art]);

        return true;

    }

    public function addSlugToCategory($category) {
        $replace = ['а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','ё'=>'e','ж'=>'j','з'=>'z','и'=>'y','й'=>'y','к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u','ф'=>'f','х'=>'h','ц'=>'c','ч'=>'ch','ш'=>'sh','щ'=>'shch','ы'=>'y','э'=>'e','ю'=>'yu','я'=>'ya','ъ'=>'','ь'=>'','і'=> 'i'];

        $category_name = function_exists('mb_strtolower') ? mb_strtolower($category->category) : strtolower($category->category); // переводим строку в нижний регистр (иногда надо задать локаль)
        $category_name = strtr($category_name, $replace);
        $category_name = preg_replace("/[^0-9a-z-_ ]/i", "", $category_name);
        $category_name = str_replace(" ", "-", $category_name);

        //$category_name = str_replace(" ", "-", $category->category);
        Category::where('id',$category->id)->update(['slug'=>$category_name]);

        return true;

    }


}