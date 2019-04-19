<?php

namespace App\Http\Controllers;

use App\Brand;

use App\Callback;
use App\Product;
use App\Category;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\Http\Traits\BrandAllTrait;
use App\Http\Traits\CategoryTrait;
use App\Http\Traits\CartTrait;
use Illuminate\Support\Facades\Lang;
use Validator;


class QueryController extends Controller {

    use BrandAllTrait, CategoryTrait, CartTrait;


    /**
     * Search for items in our e-commerce store
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function search() {

        // From Traits/CategoryTrait.php
        // ( Show Categories in side-nav )
        $categories = $this->categoryAll();

        $categoryAll = $this->categoryAll();
        // From Traits/BrandAll.php
        // Get all the Brands
        $brands = $this->brandsAll();

        // From Traits/CartTrait.php
        // ( Count how many items in Cart for signed in user )
        $cart_count = $this->countProductsInCart();

        // Gets the query string from our form submission
        $query = Input::get('search');

        // Returns an array of products that have the query string located somewhere within
        // our products product name. Paginate them so we can break up lots of search results.


        $shop_settings =  DB::table('shop_settings')->get();
        if(count($shop_settings)>0){$shop_settings = $shop_settings[0];}

        //$search = Product::where('product_sku', 'LIKE', '%' . $query . '%')->where('active','=','1')->paginate(6);

        $searchValues = preg_split('/\s+/', $query, -1, PREG_SPLIT_NO_EMPTY);

        $categorySearchKeys = [];

        $search = Product::whereHas('category', function ($query) use (&$searchValues, &$categorySearchKeys){
            foreach ($searchValues as $value) {

                if ( \App::getLocale() == 'ua' )
                {
                    $search = Category::where('category', 'like', '%'.$value.'%')->get();
                }
                else
                {
                    $search = Category::where('category_ru', 'like', '%'.$value.'%')->get();
                }

                if (!$search->isEmpty()) {
                    if (($key = array_search($value, $searchValues)) !== false) {
                        unset($searchValues[$key]);
                    }
                    $categorySearchKeys [] = $value;
                }
            }
            if ( \App::getLocale() == 'ua' )
            {
                if (!empty($categorySearchKeys)) {
                    $query->where(function ($q) use ($categorySearchKeys) {
                        foreach ($categorySearchKeys as $value) {
                            $q
                                ->where('category', 'like', "%{$value}%");
                        }
                    });
                } else {
                    $query->where(function ($q) use ($searchValues) {
                        foreach ($searchValues as $value) {
                            $q
                                ->orWhere('category', 'like', "%{$value}%");
                        }
                    });
                }
            }
            else
            {
                if (!empty($categorySearchKeys)) {
                    $query->where(function ($q) use ($categorySearchKeys) {
                        foreach ($categorySearchKeys as $value) {
                            $q
                                ->where('category_ru', 'like', '%'.$value.'%');
                        }
                    });
                } else {
                    $query->where(function ($q) use ($searchValues) {
                        foreach ($searchValues as $value) {
                            $q
                                ->orWhere('category_ru', 'like', '%'.$value.'%');
                        }
                    });
                }
            }
        })
            ->where(function ($q) use (&$searchValues, &$categorySearchKeys) {
                //dd($searchValues);
                if (!empty($categorySearchKeys)) {
                    foreach ($searchValues as $value) {
                        $q->orWhere('product_sku', 'like', "%{$value}%");
                    }
                }
            })
            ->orWhere(function ($q) use ($searchValues, $categorySearchKeys) {
                if (empty($categorySearchKeys)) {
                    foreach ($searchValues as $value) {
                        $q->where('product_sku', 'like', "%{$value}%");
                    }
                }
            })
            ->where('active','=','1')
            ->orderBy( "created_at" , "desc" )
            ->paginate(6);

        // If no results come up, flash info message with no results found message.
        if ($search->isEmpty()) {
            flash()->info('Не знайдено', 'Товар не знайдено.');
            return redirect('/');
        }


        //get discount from DB table shop_settings
        $discount = DB::table('shop_settings')->first();

        // Return a view and pass the view the list of products and the original query.
        return view('pages.search', compact('shop_settings','search', 'query', 'categories', 'brands', 'cart_count','discount','categoryAll'));

    }

    public function callback(Request $request ){

        $rules = array(
            'email' => 'required|email',

        );
        // Apply validation
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return response()->json(['status' => true],400);
        }

        Callback::create($request->all());

        return response()->json(['status' => true],200);
    }

    public function ajaxSearch() {

        // From Traits/CategoryTrait.php
        // ( Show Categories in side-nav )
        $categories = $this->categoryAll();

        $categoryAll = $this->categoryAll();
        // From Traits/BrandAll.php
        // Get all the Brands
        $brands = $this->brandsAll();

        // From Traits/CartTrait.php
        // ( Count how many items in Cart for signed in user )
        $cart_count = $this->countProductsInCart();

        // Gets the query string from our form submission
        $query = Input::get('search');

        // Returns an array of products that have the query string located somewhere within
        // our products product name. Paginate them so we can break up lots of search results.


        $shop_settings =  DB::table('shop_settings')->get();
        if(count($shop_settings)>0){$shop_settings = $shop_settings[0];}

        //$search = Product::where('product_sku', 'LIKE', '%' . $query . '%')->where('active','=','1')->paginate(6);

        $searchValues = preg_split('/\s+/', $query, -1, PREG_SPLIT_NO_EMPTY);

        $categorySearchKeys = [];

        $search = Product::whereHas('category', function ($query) use (&$searchValues, &$categorySearchKeys){
            foreach ($searchValues as $value) {

                if ( \App::getLocale() == 'ua' )
                {
                    $search = Category::where('category', 'like', '%'.$value.'%')->get();
                }
                else
                {
                    $search = Category::where('category_ru', 'like', '%'.$value.'%')->get();
                }

                if (!$search->isEmpty()) {
                    if (($key = array_search($value, $searchValues)) !== false) {
                        unset($searchValues[$key]);
                    }
                    $categorySearchKeys [] = $value;
                }
            }
            if ( \App::getLocale() == 'ua' )
            {
                if (!empty($categorySearchKeys)) {
                    $query->where(function ($q) use ($categorySearchKeys) {
                        foreach ($categorySearchKeys as $value) {
                            $q
                                ->where('category', 'like', "%{$value}%");
                        }
                    });
                } else {
                    $query->where(function ($q) use ($searchValues) {
                        foreach ($searchValues as $value) {
                            $q
                                ->orWhere('category', 'like', "%{$value}%");
                        }
                    });
                }
            }
            else
            {
                if (!empty($categorySearchKeys)) {
                    $query->where(function ($q) use ($categorySearchKeys) {
                        foreach ($categorySearchKeys as $value) {
                            $q
                                ->where('category_ru', 'like', '%'.$value.'%');
                        }
                    });
                } else {
                    $query->where(function ($q) use ($searchValues) {
                        foreach ($searchValues as $value) {
                            $q
                                ->orWhere('category_ru', 'like', '%'.$value.'%');
                        }
                    });
                }
            }
        })
            ->where(function ($q) use (&$searchValues, &$categorySearchKeys) {
                //dd($searchValues);
                if (!empty($categorySearchKeys)) {
                    foreach ($searchValues as $value) {
                        $q->orWhere('product_sku', 'like', "%{$value}%");
                    }
                }
            })
            ->orWhere(function ($q) use ($searchValues, $categorySearchKeys) {
                if (empty($categorySearchKeys)) {
                    foreach ($searchValues as $value) {
                        $q->where('product_sku', 'like', "%{$value}%");
                    }
                }
            })
            ->where('active','=','1')
            ->orderBy( "created_at" , "desc" )
            ->paginate(6);

        // If no results come up, flash info message with no results found message.
        if ($search->isEmpty())
        {
            return response()->json([
                'products' => [],
                'token' => csrf_token()
            ],404);
        }
        else
        {

            $result =[];

            foreach ($search as $product)
            {
                $name = (\App::getLocale() == 'ua' ? $product->product_name  : $product->product_name_ru) . ' Venezia ' . $product->product_sku ;
                $href = route('show.product', $product->slug);

                $result[] = [
                    "name" => $name,
                    "href" => $href
                ];
            }

            //{{App::getLocale() == 'ua' ? $product->product_name  : $product->product_name_ru}} Venezia {{$product->product_sku }}
            return response()->json([
                'products' => $result,
                'token' => csrf_token()
            ],200);
        }

    }


}