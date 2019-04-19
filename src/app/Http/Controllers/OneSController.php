<?php

namespace App\Http\Controllers;

use App\Http\Traits\SlugTrait;
use App\Product;
use App\ProductInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Excel,File,Config;
use App\Http\Traits\CartTrait;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;


class OneSController extends Controller
{
    use SlugTrait;


    public function getImport(){

        $cart_count = $this->countProductsInCart();

        return view('admin.ones.import',  compact('cart_count'));
    }

    public function Slug($product){

        return $this->addSlugToProduct($product);
    }

    public static function postImport(){

        $dir = storage_path('import');
        $repository = storage_path('app');
    // Open a directory, and read its contents
        if (is_dir($dir)){

            if ($dh = opendir($dir)){

                while (($file = readdir($dh)) !== false){
                    if ($file == '.' || $file == '..') {
                        continue;
                    }

                    if (!copy($dir.'/'.$file, $repository.'/'.time().'.csv')) {
                        continue;
                    }

                    $actual_pruduct_id_list = [];
                    $actual_pruduct_size_list = [];

                    Excel::load($dir.'/'.$file ,function ($reader) use (&$actual_pruduct_id_list, &$actual_pruduct_size_list) {
                        $reader->each(function ($sheet) use (&$actual_pruduct_id_list , &$actual_pruduct_size_list) {
                            $products = $sheet->toArray();
                            $product_id = Product::where('product_sku' ,$products['art'])->value('id');

                            if(isset($products['color'])){
                                $color = DB::table('colors_table')->where('name' , $products['color'])->value('color');
                            }


                            if(!empty($product_id)){

                                $actual_pruduct_id_list [] = $product_id;
                                $actual_pruduct_size_list [$product_id][] = $products['size'];

                                $check_product_quantity = DB::table('product_information')
                                    ->where('product_id' , $product_id)
                                    ->where('size' , $products['size'])
                                    ->value('quantity');
                                    
                                Product::where("id",$product_id)->update( ["price"=> $products['price'] ]);    

                                if($check_product_quantity != null ){
                                     
                                    if($products['count'] != 0){
                                        DB::table('product_information')
                                            ->where('product_id' , $product_id)
                                            ->where('size' , $products['size'])
                                            ->update(['quantity' => $products['count'] ]  );
                                    }else{
                                        DB::table('product_information')
                                            ->where('product_id' , $product_id)
                                            ->where('size' , $products['size'])
                                            ->delete();
                                    }

                                }else {
                                         if($products['count'] != 0){

                                        DB::table('product_information')->insert(
                                            [
                                                'product_id' => $product_id,
                                                'color' => $color ? $color : 'Null',
                                                'size' => $products['size'],
                                                'quantity' => $products['count'],

                                            ]
                                        );
                                         }


                                }

                            }else{

                                $product = Product::create([
                                    'product_name' => $products['name'],
                                    'code' => $products['code'],
                                    'product_sku' => $products['art'],
                                    'price' => $products['price'],
                                    'reduced_price' => '',
                                    'brand_id' => '1',
                                    'featured' => '0',
                                    'description' => '',
                                    'product_spec' => '',
                                ]);

                                $actual_pruduct_id_list [] = $product->id;
                                $actual_pruduct_size_list [$product->id][] = $products['size'];

                                $product->product_material()->create([]);


                                // TODO generate slug
                                DB::table('product_information')->insert(
                                    [
                                        'product_id' => $product->id,
                                        'color' => $color ? $color : 'Null',
                                        'size' => $products['size'],
                                        'quantity' => $products['count'],
                                    ]
                                );
                            }

                        });



                    });

                    if ($file == "tovar.csv") {

                        //dd($actual_pruduct_size_list);

//                        $actual_collection = collect($actual_pruduct_id_list)->unique();
//
//                        $current_collection = ProductInformation::all()->lists('product_id')->unique();
//
//                        $delete_items_collection = $current_collection->diff($actual_collection);

                        $actual_collection = ProductInformation::whereIn('product_id', $actual_pruduct_id_list)->get()->filter(function ($value, $key) use ($actual_pruduct_size_list) {

                            return in_array ($value->size , $actual_pruduct_size_list [$value->product_id]);
                        })->lists('id')->toArray();


                        $current_collection = ProductInformation::all()->lists('id')->unique();

                        $delete_items_collection = $current_collection->diff($actual_collection);

                        $delete_model = ProductInformation::whereIn('id', $delete_items_collection)->delete();

                    }

                    unlink($dir.'/'.$file);//////////////////////////////////////////////////////////////////////////////////////////////////
                }

                closedir($dh);
            }
        }


        flash()->overlay('Успіх', 'Довар додано!','success');

        return redirect("/");
    }
}
