<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Product;
use App\Category;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Traits\BrandAllTrait;
use App\Http\Traits\CategoryTrait;
use App\Http\Traits\SearchTrait;
use App\Http\Traits\CartTrait;
use DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;


class OrderByController extends ProductsController
{

    use BrandAllTrait, CategoryTrait, SearchTrait, CartTrait;


    /****************** Order By for Category Section *****************************************************************/


    /**
     * @param Product $product
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function productsPriceHighest($id, Product $product)
    {

//        // Get the Category ID
//        $categories = Category::where('id', '=', $id)->get();
//
//
//        // From Traits/SearchTrait.php
//        // ( Enables capabilities search to be preformed on this view )
//        $search = $this->search();
//
//        // Order Products by price highest, where the category id = the URl route ID
//        $products = Product::orderBy('price', 'desc')->where('cat_id', '=', $id)->where('active', '=', '1')->paginate(18);
//
//        // Count the Products
//        $count = $products->count();
//
//        // From Traits/CartTrait.php
//        // ( Count how many items in Cart for signed in user )
//        $cart_count = $this->countProductsInCart();
//
//        //get discount from DB table shop_settings
//        $discount = DB::table('shop_settings')->first();
//
//        $categoryId = $id;
//
//        // From Traits/CategoryTrait.php
//        // ( Show Categories in side-nav )
//        $categoryAll = $this->categoryAll();
//
//        // From Traits/BrandAll.php
//        // Get all the Brands
//        $brandsAll = $this->brandsAll();
//        //Get all sizes unique  from bd product_information
//        $sizes = DB::table('product_information')->select('size')->distinct()->get();
//        //Get all colors unique  from bd table_colors
//        $colors = DB::table('colors_table')->select('color')->distinct()->get();
//        return view('category.show', ['products' => $products], compact('categories', 'categoryAll', 'brandsAll', 'search', 'count', 'cart_count', 'categoryId', 'discount', 'colors','sizes'));
        $categories = Category::where('id', $id)->get();

        $categories_find = Category::find($id);


        // If no category exists with that particular ID, then redirect back to Home page.
        if (!$categories_find) {
            return redirect('/');
        }

        //if current category is parent , we get all child categories
        if($categories_find->parent_id == null){
            $cat_id = Category::where('parent_id', $categories_find->id)->pluck('id');
        }else{
            $cat_id = [$id];
        }

        $shop_settings =  DB::table('shop_settings')->get();
        if(count($shop_settings)>0){$shop_settings = $shop_settings[0];}

        // From Traits/CategoryTrait.php
        // ( Show Categories in side-nav )
        $categoryAll = $this->categoryAll();

        // From Traits/BrandAll.php
        // Get all the Brands
        $brands = $this->brandsAll();
        //Need to main navbar(need fix)
        $brandsAll = $this->brandsAll();

        // From Traits/SearchTrait.php
        // ( Enables capabilities search to be preformed on this view )
        $search = $this->search();


        $showAll = Input::get('showAll');
        if($showAll != null) {
            $products = Product::with(['category'])
                ->select(DB ::raw('*','DISTINCT(id)'))
                ->whereHas('category',function ($query) use ($cat_id){
                    $query->whereIn('id',$cat_id);
                })
                ->where('created_at' ,'>', Carbon::now()->subSeconds($shop_settings->time_new))
                ->where('active', '1')
                ->orderBy('price', 'desc')
                ->get();
            $productCount = count($products);
            $products = Product::with(['category'])
                ->select(DB ::raw('*','DISTINCT(id)'))
                ->whereHas('category',function ($query) use ($cat_id){
                    $query->whereIn('id',$cat_id);
                })
                ->where('created_at' ,'>', Carbon::now()->subSeconds($shop_settings->time_new))
                ->where('active', '1')
                ->orderBy('price', 'desc')
                ->paginate($productCount);
        }else{

            $products = Product::with(['category'])
                ->select(DB ::raw('*','DISTINCT(id)'))
                ->whereHas('category',function ($query) use ($cat_id){
                    $query->whereIn('id',$cat_id);
                })
                ->where('active', '1')
                ->orderBy('price', 'desc')
                ->paginate(18);
        }


        $breadcrumbs = [
            (object) ['page' => trans('messages.mainPage'), "link" => url('/')]
        ];

        $locale = App::getLocale();

        if ($locale != "ua") {
            $category = "category_".$locale;
        }else{
            $category = "category";
        }

        if (!is_null($categories_find->parent)){
            array_push($breadcrumbs,(object) ['page' => $categories_find->parent->$category, "link" => route('category.showAll',[$categories_find->parent->id,$categories_find->parent->slug])]);
            array_push($breadcrumbs,(object) ['page' => $categories_find->$category, "link" => route('category.showAll',[$categories_find->id,$categories_find->slug])]);
        }else {
            array_push($breadcrumbs,(object) ['page' => $categories_find->$category, "link" => route('category.showAll',[$categories_find->id,$categories_find->slug])]);
        }


        // Count the products under a certain category
        $count = $products->count();

        // From Traits/CartTrait.php
        // ( Count how many items in Cart for signed in user )
        $cart_count = $this->countProductsInCart();

        //get discount from DB table shop_settings
        $discount = DB::table('shop_settings')->first();

        $products_id = $products->pluck('id');
        //Get all sizes unique  from bd product_information
        $sizes = DB::table('product_information')->select('size')->whereIn('product_id' , $products_id)->distinct()->orderBy('size', 'asc')->get();
        //Get all colors unique  from bd table_colors
        $colors = DB::table('product_information')->select('color')->whereIn('product_id' , $products_id)->whereNotNull('color')->distinct()->get();

        $metaTags = $this->metaTags($categories_find);
        return view('category.show', compact('shop_settings','products','categories','brands', 'categoryAll', 'search', 'cart_count','discount','brandsAll','sizes','colors','breadcrumbs','metaTags'))->with('count', $count)->with('categoryId',$id);

    }

    /**
     * @param Product $product
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    //  I create  this method very fast because deadline :(
    //please not judge me
    // this method makes  filters products (size,colors)
    public function productsSize($id, Request $request)
    {
        //dd($request->all());

        $range_price = explode(';', $request->range );

        $categories_find = Category::find($id);

        $items_per_page = Input::get('items');

        if ( is_null($items_per_page) ) {
            $items_per_page = Session::get('items_per_page') ? Session::get('items_per_page') : 12;
        }

        $shop_settings =  DB::table('shop_settings')->get();
        if(count($shop_settings)>0){$shop_settings = $shop_settings[0];}

        // If no category exists with that particular ID, then redirect back to Home page.
        if (!$categories_find) {
            return redirect('/');
        }

        //if current categoruy is parent , we get all child categories
        if($categories_find->parent_id == null){
            $cat_id = Category::where('parent_id', $categories_find->id)->pluck('id')->toArray();
        }else{
            $cat_id = [$id];
        }

        $products = Product::whereHas('category',function ($query) use ($cat_id){
            $query->whereIn('id',$cat_id);
        })
            ->whereHas('product_information',function ($query) use ($request){
                if (isset($request->color)) {
                    $query
                        ->whereIn('color',$request->color);
                }
                if (isset($request->size)) {
                    $query
                        ->whereIn('size',$request->size);
                }

            })
            ->where('active', '1')
            ->where(function($q) use ($range_price) {
                $q->whereBetween('price', $range_price)->orWhereBetween('reduced_price', $range_price);
            })
            ->paginate($items_per_page);


        $showAllButton = true;

        if ($products->total() <= $products->count()) {
            $showAllButton = false;
        }


        $bag = true;
        $categories = Category::where('id', $id)->get();

        $breadcrumbs = [
            (object) ['page' => trans('messages.mainPage'), "link" => url('/')]
        ];

        $locale = App::getLocale();

        if ($locale != "ua") {
            $category = "category_".$locale;
        }else{
            $category = "category";
        }

        if (!is_null($categories_find->parent)){
            array_push($breadcrumbs,(object) ['page' => $categories_find->parent->$category, "link" => route('category.showAll',[$categories_find->parent->id,$categories_find->parent->slug])]);
            array_push($breadcrumbs,(object) ['page' => $categories_find->$category, "link" => route('category.showAll',[$categories_find->id,$categories_find->slug])]);
        }else {
            array_push($breadcrumbs,(object) ['page' => $categories_find->$category, "link" => route('category.showAll',[$categories_find->id,$categories_find->slug])]);
        }

        // From Traits/SearchTrait.php
        // ( Enables capabilities search to be preformed on this view )
        $search = $this->search();

        // Count the Products
        $count = $products->count();

        // From Traits/CartTrait.php
        // ( Count how many items in Cart for signed in user )
        $cart_count = $this->countProductsInCart();

        //get discount from DB table shop_settings
        $discount = DB::table('shop_settings')->first();

        $categoryId = $id;

        // From Traits/CategoryTrait.php
        // ( Show Categories in side-nav )
        $categoryAll = $this->categoryAll();

        // From Traits/BrandAll.php
        // Get all the Brands
        $brandsAll = $this->brandsAll();



        $products_id = Product::with(['category'])
            ->select(DB ::raw('*','DISTINCT(id)'))
            ->whereHas('category',function ($query) use ($cat_id){
                $query->whereIn('id',$cat_id);
            })
            ->where('active', '1')
            ->latest('created_at')
            ->pluck('id')
            ->toArray();

        //Get all sizes unique  from bd product_information
        $sizes = DB::table('product_information')->select('size')->whereIn('product_id' , $products_id)->distinct()->orderBy('size', 'asc')->get();
        //Get all colors unique  from bd table_colors
        $colors = DB::table('product_information')->select('color')->whereIn('product_id' , $products_id)->whereNotNull('color')->distinct()->get();

//        return view('category.show', ['products' => $products], compact('shop_settings','categories', 'showAllButton','categoryAll','breadcrumbs', 'brandsAll', 'search', 'count', 'cart_count', 'categoryId', 'discount', 'bag', 'sizes', 'colors'));
        return view('category.show', compact('shop_settings','showAllButton','products','categories','brands', 'categoryAll', 'search', 'cart_count','discount','brandsAll','sizes','colors','breadcrumbs','metaTags'))->with('count', $count)->with('categoryId',$id);
    }


    /**
     * @param Product $product
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function productsPriceLowest($id, Product $product)
    {
        $categories = Category::where('id', $id)->get();

        $categories_find = Category::find($id);


        // If no category exists with that particular ID, then redirect back to Home page.
        if (!$categories_find) {
            return redirect('/');
        }

        //if current category is parent , we get all child categories
        if($categories_find->parent_id == null){
            $cat_id = Category::where('parent_id', $categories_find->id)->pluck('id');
        }else{
            $cat_id = [$id];
        }

        $shop_settings =  DB::table('shop_settings')->get();
        if(count($shop_settings)>0){$shop_settings = $shop_settings[0];}

        // From Traits/CategoryTrait.php
        // ( Show Categories in side-nav )
        $categoryAll = $this->categoryAll();

        // From Traits/BrandAll.php
        // Get all the Brands
        $brands = $this->brandsAll();
        //Need to main navbar(need fix)
        $brandsAll = $this->brandsAll();

        // From Traits/SearchTrait.php
        // ( Enables capabilities search to be preformed on this view )
        $search = $this->search();


        $showAll = Input::get('showAll');
        if($showAll != null) {
            $products = Product::with(['category'])
                ->select(DB ::raw('*','DISTINCT(id)'))
                ->whereHas('category',function ($query) use ($cat_id){
                    $query->whereIn('id',$cat_id);
                })
                ->where('created_at' ,'>', Carbon::now()->subSeconds($shop_settings->time_new))
                ->where('active', '1')
                ->orderBy('price', 'asc')
                ->get();
            $productCount = count($products);
            $products = Product::with(['category'])
                ->select(DB ::raw('*','DISTINCT(id)'))
                ->whereHas('category',function ($query) use ($cat_id){
                    $query->whereIn('id',$cat_id);
                })
                ->where('created_at' ,'>', Carbon::now()->subSeconds($shop_settings->time_new))
                ->where('active', '1')
                ->orderBy('price', 'asc')
                ->paginate($productCount);
        }else{

            $products = Product::with(['category'])
                ->select(DB ::raw('*','DISTINCT(id)'))
                ->whereHas('category',function ($query) use ($cat_id){
                    $query->whereIn('id',$cat_id);
                })
                ->where('active', '1')
                ->orderBy('price', 'asc')
                ->paginate(18);
        }


        $breadcrumbs = [
            (object) ['page' => trans('messages.mainPage'), "link" => url('/')]
        ];

        $locale = App::getLocale();

        if ($locale != "ua") {
            $category = "category_".$locale;
        }else{
            $category = "category";
        }

        if (!is_null($categories_find->parent)){
            array_push($breadcrumbs,(object) ['page' => $categories_find->parent->$category, "link" => route('category.showAll',[$categories_find->parent->id,$categories_find->parent->slug])]);
            array_push($breadcrumbs,(object) ['page' => $categories_find->$category, "link" => route('category.showAll',[$categories_find->id,$categories_find->slug])]);
        }else {
            array_push($breadcrumbs,(object) ['page' => $categories_find->$category, "link" => route('category.showAll',[$categories_find->id,$categories_find->slug])]);
        }


        // Count the products under a certain category
        $count = $products->count();

        // From Traits/CartTrait.php
        // ( Count how many items in Cart for signed in user )
        $cart_count = $this->countProductsInCart();

        //get discount from DB table shop_settings
        $discount = DB::table('shop_settings')->first();

        $products_id = $products->pluck('id');
        //Get all sizes unique  from bd product_information
        $sizes = DB::table('product_information')->select('size')->whereIn('product_id' , $products_id)->distinct()->orderBy('size', 'asc')->get();
        //Get all colors unique  from bd table_colors
        $colors = DB::table('product_information')->select('color')->whereIn('product_id' , $products_id)->whereNotNull('color')->distinct()->get();

        $metaTags = $this->metaTags($categories_find);
        return view('category.show', compact('shop_settings','products','categories','brands', 'categoryAll', 'search', 'cart_count','discount','brandsAll','sizes','colors','breadcrumbs','metaTags'))->with('count', $count)->with('categoryId',$id);

    }


    /**
     * @param Product $product
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function productsAlphaHighest($id, Product $product)
    {

        // Get the Category ID
        $categories = Category::where('id', '=', $id)->get();

        // From Traits/SearchTrait.php
        // ( Enables capabilities search to be preformed on this view )
        $search = $this->search();

        // Order Products by Alphabet Descending, where the category id = the URl route ID
        $products = Product::orderBy('product_name', 'desc')->where('active', '=', '1')->where('cat_id', '=', $id)->paginate(18);

        // Count the Products
        $count = $products->count();

        // From Traits/CartTrait.php
        // ( Count how many items in Cart for signed in user )
        $cart_count = $this->countProductsInCart();
        //get discount from DB table shop_settings
        $discount = DB::table('shop_settings')->first();

        $shop_settings =  DB::table('shop_settings')->get();
        if(count($shop_settings)>0){$shop_settings = $shop_settings[0];}

        $categoryId = $id;

        // From Traits/CategoryTrait.php
        // ( Show Categories in side-nav )
        $categoryAll = $this->categoryAll();

        // From Traits/BrandAll.php
        // Get all the Brands
        $brandsAll = $this->brandsAll();
        //Get all sizes unique  from bd product_information
        $sizes = DB::table('product_information')->select('size')->distinct()->get();
        //Get all colors unique  from bd table_colors
        $colors = DB::table('colors_table')->select('color')->distinct()->get();
        return view('category.show', ['products' => $products], compact('shop_settings','categories', 'categoryAll', 'brandsAll', 'search', 'count', 'cart_count', 'categoryId', 'discount', 'sizes', 'colors'));
    }


    /**
     * @param Product $product
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function productsAlphaLowest($id, Product $product)
    {

        // Get the Category ID
        $categories = Category::where('id', $id)->get();


        // From Traits/SearchTrait.php
        // ( Enables capabilities search to be preformed on this view )
        $search = $this->search();

        // Order Products by Alphabet Ascending, where the category id = the URl route ID
        $products = Product::orderBy('product_name', 'asc')->where('active', '=', '1')->where('cat_id', '=', $id)->paginate(18);

        // Count the Products
        $count = $products->count();

        // From Traits/CartTrait.php
        // ( Count how many items in Cart for signed in user )
        $cart_count = $this->countProductsInCart();
        //get discount from DB table shop_settings
        $discount = DB::table('shop_settings')->first();

        $shop_settings =  DB::table('shop_settings')->get();
        if(count($shop_settings)>0){$shop_settings = $shop_settings[0];}

        $categoryId = $id;

        // From Traits/CategoryTrait.php
        // ( Show Categories in side-nav )
        $categoryAll = $this->categoryAll();

        // From Traits/BrandAll.php
        // Get all the Brands
        $brandsAll = $this->brandsAll();
        //Get all sizes unique  from bd product_information
        $sizes = DB::table('product_information')->select('size')->distinct()->get();
        //Get all colors unique  from bd table_colors
        $colors = DB::table('colors_table')->select('color')->distinct()->get();
        return view('category.show', ['products' => $products], compact('shop_settings','categories', 'categoryAll', 'brandsAll', 'search', 'count', 'cart_count', 'categoryId', 'discount', 'sizes', 'colors'));
    }


    /**
     * @param Product $product
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function productsNewest($id)
    {
        $categories = Category::where('id', $id)->get();

        $categories_find = Category::find($id);


        // If no category exists with that particular ID, then redirect back to Home page.
        if (!$categories_find) {
            return redirect('/');
        }

        //if current category is parent , we get all child categories
        if($categories_find->parent_id == null){
            $cat_id = Category::where('parent_id', $categories_find->id)->pluck('id');
        }else{
            $cat_id = [$id];
        }

        $shop_settings =  DB::table('shop_settings')->get();
        if(count($shop_settings)>0){$shop_settings = $shop_settings[0];}

        // From Traits/CategoryTrait.php
        // ( Show Categories in side-nav )
        $categoryAll = $this->categoryAll();

        // From Traits/BrandAll.php
        // Get all the Brands
        $brands = $this->brandsAll();
        //Need to main navbar(need fix)
        $brandsAll = $this->brandsAll();

        // From Traits/SearchTrait.php
        // ( Enables capabilities search to be preformed on this view )
        $search = $this->search();

        $showAll = Input::get('showAll');
        if($showAll != null) {
            $products = Product::with(['category'])
                ->select(DB ::raw('*','DISTINCT(id)'))
                ->whereHas('category',function ($query) use ($cat_id){
                    $query->whereIn('id',$cat_id);
                })
                ->where('created_at' ,'>', Carbon::now()->subSeconds($shop_settings->time_new))
                ->where('active', '1')
                ->latest('created_at')
                ->get();
            $productCount = count($products);
            $products = Product::with(['category'])
                ->select(DB ::raw('*','DISTINCT(id)'))
                ->whereHas('category',function ($query) use ($cat_id){
                    $query->whereIn('id',$cat_id);
                })
                ->where('created_at' ,'>', Carbon::now()->subSeconds($shop_settings->time_new))
                ->where('active', '1')
                ->latest('created_at')
                ->paginate($productCount);
        }else{

            $products = Product::with(['category'])
                ->select(DB ::raw('*','DISTINCT(id)'))
                ->whereHas('category',function ($query) use ($cat_id){
                    $query->whereIn('id',$cat_id);
                })
                ->where('active', '1')
                ->latest('created_at')
                ->paginate(18);
        }


        $breadcrumbs = [
            (object) ['page' => trans('messages.mainPage'), "link" => url('/')]
        ];

        $locale = App::getLocale();

        if ($locale != "ua") {
            $category = "category_".$locale;
        }else{
            $category = "category";
        }

        if (!is_null($categories_find->parent)){
            array_push($breadcrumbs,(object) ['page' => $categories_find->parent->$category, "link" => route('category.showAll',[$categories_find->parent->id,$categories_find->parent->slug])]);
            array_push($breadcrumbs,(object) ['page' => $categories_find->$category, "link" => route('category.showAll',[$categories_find->id,$categories_find->slug])]);
        }else {
            array_push($breadcrumbs,(object) ['page' => $categories_find->$category, "link" => route('category.showAll',[$categories_find->id,$categories_find->slug])]);
        }


        // Count the products under a certain category
        $count = $products->count();

        // From Traits/CartTrait.php
        // ( Count how many items in Cart for signed in user )
        $cart_count = $this->countProductsInCart();

        //get discount from DB table shop_settings
        $discount = DB::table('shop_settings')->first();

        $products_id = $products->pluck('id');
        //Get all sizes unique  from bd product_information
        $sizes = DB::table('product_information')->select('size')->whereIn('product_id' , $products_id)->distinct()->orderBy('size', 'asc')->get();
        //Get all colors unique  from bd table_colors
        $colors = DB::table('product_information')->select('color')->whereIn('product_id' , $products_id)->whereNotNull('color')->distinct()->get();

        $metaTags = $this->metaTags($categories_find);
        return view('category.show', compact('shop_settings','products','categories','brands', 'categoryAll', 'search', 'cart_count','discount','brandsAll','sizes','colors','breadcrumbs','metaTags'))->with('count', $count)->with('categoryId',$id);

    }




    /****************** Order By for Brands Section *******************************************************************/


    /**
     * @param Product $product
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function productsPriceHighestBrand($id, Product $product)
    {

        // Get the Brand ID
        $brands = Brand::where('id', '=', $id)->get();

        // From Traits/CategoryTrait.php
        // ( Show Categories in side-nav )
        $categoryAll = $this->categoryAll();

        // From Traits/BrandAll.php
        // Get all the Brands
        $brand = $this->brandsAll();

        // From Traits/SearchTrait.php
        // ( Enables capabilities search to be preformed on this view )
        $search = $this->search();

        $products = Product::orderBy('price', 'desc')->where('active', '=', '1')->where('brand_id', '=', $id)->paginate(18);

        // Count the products
        $count = $products->count();

        // From Traits/CartTrait.php
        // ( Count how many items in Cart for signed in user )
        $cart_count = $this->countProductsInCart();

        $brandId = $id;
        // Get all the Brands in NavBar template
        $brandsAll = $this->brandsAll();

        return view('brand.show', ['products' => $products], compact('brands', 'brand', 'categoryAll', 'search', 'count', 'cart_count', 'brandId', 'brandsAll'));
    }


    /**
     * @param Product $product
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function productsPriceLowestBrand($id, Product $product)
    {


        // Get the Brand ID
        $brands = Brand::where('id', '=', $id)->get();

        // From Traits/CategoryTrait.php
        // ( Show Categories in side-nav )
        $categoryAll = $this->categoryAll();

        // From Traits/BrandAll.php
        // Get all the Brands
        $brand = $this->brandsAll();

        // From Traits/SearchTrait.php
        // ( Enables capabilities search to be preformed on this view )
        $search = $this->search();

        $products = Product::orderBy('price', 'asc')->where('active', '=', '1')->where('brand_id', '=', $id)->paginate(18);

        // Count the products
        $count = $products->count();

        // From Traits/CartTrait.php
        // ( Count how many items in Cart for signed in user )
        $cart_count = $this->countProductsInCart();

        $brandId = $id;
        // Get all the Brands in NavBar template
        $brandsAll = $this->brandsAll();

        return view('brand.show', ['products' => $products], compact('brands', 'brand', 'categoryAll', 'search', 'count', 'cart_count', 'brandId', 'brandsAll'));
    }


    /**
     * @param Product $product
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function productsAlphaHighestBrand($id, Product $product)
    {

        // Get the Brand ID
        $brands = Brand::where('id', '=', $id)->get();

        // From Traits/CategoryTrait.php
        // ( Show Categories in side-nav )
        $categoryAll = $this->categoryAll();

        // From Traits/BrandAll.php
        // Get all the Brands
        $brand = $this->brandsAll();

        // From Traits/SearchTrait.php
        // ( Enables capabilities search to be preformed on this view )
        $search = $this->search();

        $products = Product::orderBy('product_name', 'desc')->where('active', '=', '1')->where('brand_id', '=', $id)->paginate(18);

        // Count the products
        $count = $products->count();

        // From Traits/CartTrait.php
        // ( Count how many items in Cart for signed in user )
        $cart_count = $this->countProductsInCart();
        $brandId = $id;
        // Get all the Brands in NavBar template
        $brandsAll = $this->brandsAll();

        return view('brand.show', ['products' => $products], compact('brands', 'brand', 'categoryAll', 'search', 'count', 'cart_count', 'brandId', 'brandsAll'));
    }


    /**
     * @param Product $product
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function productsAlphaLowestBrand($id, Product $product)
    {

        // Get the Brand ID
        $brands = Brand::where('id', '=', $id)->get();

        // From Traits/CategoryTrait.php
        // ( Show Categories in side-nav )
        $categoryAll = $this->categoryAll();

        // From Traits/BrandAll.php
        // Get all the Brands
        $brand = $this->brandsAll();

        // From Traits/SearchTrait.php
        // ( Enables capabilities search to be preformed on this view )
        $search = $this->search();

        $products = Product::orderBy('product_name', 'asc')->where('active', '=', '1')->where('brand_id', '=', $id)->paginate(18);

        // Count the products
        $count = $products->count();

        // From Traits/CartTrait.php
        // ( Count how many items in Cart for signed in user )
        $cart_count = $this->countProductsInCart();

        $brandId = $id;
        // Get all the Brands in NavBar template
        $brandsAll = $this->brandsAll();

        return view('brand.show', ['products' => $products], compact('brands', 'brand', 'categoryAll', 'search', 'count', 'cart_count', 'brandId', 'brandsAll'));
    }


    /**
     * @param Product $product
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function productsNewestBrand($id, Product $product)
    {

        // Get the Brand ID
        $brands = Brand::where('id', '=', $id)->get();

        // From Traits/CategoryTrait.php
        // ( Show Categories in side-nav )
        $categoryAll = $this->categoryAll();

        // From Traits/BrandAll.php
        // Get all the Brands
        $brand = $this->brandsAll();

        // From Traits/SearchTrait.php
        // ( Enables capabilities search to be preformed on this view )
        $search = $this->search();

        $products = Product::orderBy('created_at', 'desc')->where('active', '=', '1')->where('brand_id', '=', $id)->paginate(18);

        // Count the products
        $count = $products->count();

        // From Traits/CartTrait.php
        // ( Count how many items in Cart for signed in user )
        $cart_count = $this->countProductsInCart();

        $brandId = $id;
        // Get all the Brands in NavBar template
        $brandsAll = $this->brandsAll();

        return view('brand.show', ['products' => $products], compact('brands', 'brand', 'categoryAll', 'search', 'count', 'cart_count', 'brandId', 'brandsAll'));
    }


}