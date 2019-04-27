<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Brand;
//use App\Http\Requests\Request;
use App\FilterType;
use App\FilterValue;
use App\Http\Traits\SeoTrait;
use App\Page;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Product;
use App\Category;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Input;

use App\Http\Traits\BrandAllTrait;
use App\Http\Traits\CategoryTrait;
use App\Http\Traits\SearchTrait;
use App\Http\Traits\CartTrait;
use Illuminate\Support\Facades\URL;
use Session;


class PagesController extends Controller {

    use BrandAllTrait, CategoryTrait, SearchTrait, CartTrait, SeoTrait;


    /**
     * Display things for main index home page.
     *
     * @return $this
     */
    public function index() {


        // From Traits/CategoryTrait.php
        // ( Show Categories in side-nav )
        $categories = $this->categoryAll();
        //Need to fast fox bug (for all pages render bar with categories);
        $categoryAll =  $this->categoryAll();
        // From Traits/BrandAll.php
        // Get all the Brands
        $brands = $this->brandsAll();

        // From Traits/SearchTrait.php
        // ( Enables capabilities search to be preformed on this view )
        $search = $this->search();

        // From Traits/CartTrait.php
        // ( Count how many items in Cart for signed in user )
        //$cart_count = $this->countProductsInCart();

        // Select all products where featured = 1,
        // order by random rows, and only take 4 rows from table so we can display them on the homepage.
        //$products = Product::where('featured', '=', 1)->orderByRaw('RAND()')->take(4)->get();

        //Our version response featured products on the homepage
        $products = Product::where('featured', 1)->orderByRaw('RAND()')->get();

        $categoryId = null;

        $rand_brands = Brand::orderByRaw('RAND()')->take(6)->get();

        //Select slider images
        $mainPageImages = DB::table('main_page_images')
                ->join('categories', 'main_page_images.category_id' , '=' , 'categories.id')
                ->get([
                    'option',
                    'path',
                    'category_id',
                    'slug'
                ]);
        //Select  static images
        $mainPageImagesStatic = DB::table('main_static_page_images')->get();
        // Select all products with the newest one first, and where featured = 0,
        // order by random rows, and only take 8 rows from table so we can display them on the New Product section in the homepage.
        $new = Product::orderBy('created_at', 'desc')->where('featured', '=', 0)->orderByRaw('RAND()')->take(4)->get();
        //get discount from DB table shop_settings
        $discount = DB::table('shop_settings')->first();

        $mainSeo =  DB::table('seo')->where('page','main')->first();

        $metaTags = $this->metaTags($mainSeo);

        return view('pages.index', compact('mainSeo','categoryId','products', 'brands', 'search', 'new', 'rand_brands','mainPageImages', 'mainPageImagesStatic','discount','categoryAll','metaTags'))->with('categories', $categories);
    }


    /**
     * Display Products by Category.
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function displayProducts($id, Request $request=null, $slug=null)
    {

        // Get the Category ID , so we can display the category name under each list view
        $categories = Category::where('id', $id)->get();

        $categories_find = Category::with('children')->find($id);

        // If no category exists with that particular ID, then redirect back to Home page.
        if (!$categories_find) {
            return redirect('/');
        }

        //dd($categories);
        if (is_null($slug)) {
            return redirect("category/{$id}/{$categories_find->slug}", 301);
        }

        if ($slug == 'bosonijki' && $id == "14") {
            return redirect(route('category.showAll', [14, 'bosonijky']), 301);
        } elseif (!Category::where('slug', $slug)->where('id', $id)->first()) {
            abort(404);
        }

        $range_price = ($request->has("range")) ? explode(';', $request->range) : null;

        //dd((isset($range_price[1])) ? $range_price[1] : 500);

        //if current category is parent , we get all child categories
        //if($categories_find->parent_id == null){
        if ($categories_find->children->count()) {
            $cat_id = Category::where('parent_id', $categories_find->id)->pluck('id');
            $cat_id = array_merge($cat_id->toArray(), Category::whereIn('parent_id', $cat_id)->pluck('id')->toArray());

        } else {
            $cat_id = [$id];
        }

        $shop_settings = DB::table('shop_settings')->get();
        if (count($shop_settings) > 0) {
            $shop_settings = $shop_settings[0];
        }

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

        if (parse_url(url()->previous(), PHP_URL_PATH) !== "/" . $request->path()) {
            Session::forget('sort');
        }

        if (is_null(Input::get('sort'))) {
            $sort = Session::get('sort');
        } else {
            $sort = Input::get('sort');
        }

        Session::put('sort', $sort);

        //dd ( parse_url( url()->previous(), PHP_URL_PATH),$request->path());

        switch ($sort) {
            case "newest":
                $orber_by ['order'] = "created_at";
                $orber_by ['direction'] = "desc";
                break;
            case 'lowest':
                $orber_by ['order'] = "price";
                $orber_by ['direction'] = "asc";
                break;
            case "highest":
                $orber_by ['order'] = "price";
                $orber_by ['direction'] = "desc";
                break;
            default:
                $orber_by ['order'] = 'created_at';
                $orber_by ['direction'] = "desc";
//                $orber_by ['order'] = DB::raw('RAND()');
//                $orber_by ['direction'] = "";
                break;
        }

        $items_per_page = Input::get('items');

        if (is_null($items_per_page)) {
            $items_per_page = Session::get('items_per_page') ? Session::get('items_per_page') : 12;
        }

        Session::put('items_per_page', $items_per_page);

        $showAll = Input::get('showAll');


//        dd (Product::with(['category'])
//            ->select(DB ::raw('id','DISTINCT(id)'))
//            ->whereHas('category',function ($query) use ($cat_id){
//                $query->whereIn('id',$cat_id);
//            })
//            ->where(function ($query) use ($shop_settings, $sort){
//                if ($sort == "newest") {
//                    $query->where('created_at', '>', Carbon::now()->subSeconds($shop_settings->time_new));
//                }
//            })
//            ->where('active', '1')
//            ->orderBy( $orber_by['order'] , $orber_by['direction'] )
//            ->get()
//            ->pluck('id')
//        );

        //        $model = FilterValue::has('product')->groupBy('name_id')->get();
//        $model = FilterType::whereHas('filter_values', function ($query) use ($cat_id) {
//
//            $query->whereHas('product', function ($query) use ($cat_id) {
//
//                $query->whereHas('category', function ($query) use ($cat_id) {
//                    $query->whereIn('id', $cat_id);
//                });
//
//            })
//                ->groupBy('name_id');
//        })
//            ->with('filter_names')
//            ->get();

        $filter_types = FilterType::whereHas('filter_names', function ($query) use ($cat_id) {


            $query->whereHas('filter_values', function ($query) use ($cat_id) {

                $query->whereHas('product', function ($query) use ($cat_id) {

                    $query->whereHas('category', function ($query) use ($cat_id) {
                        $query->whereIn('id', $cat_id);
                    });

                })
                    ->groupBy('name_id');
            });
        })
            ->with(array('filter_names' => function ($query) use ($cat_id) {


                $query->whereHas('filter_values', function ($query) use ($cat_id) {

                    $query->whereHas('product', function ($query) use ($cat_id) {

                        $query->whereHas('category', function ($query) use ($cat_id) {
                            $query->whereIn('id', $cat_id);
                        });

                    })
                        ->groupBy('name_id');
                });
            }))
            ->get();

//        dd($filter_types->pluck('id'));

//        dd(isset($request->filter));


        if($showAll != null) {
            $products = Product::with(['category'])
                ->select(DB ::raw('*','DISTINCT(id)'))
                ->whereHas('category',function ($query) use ($cat_id){
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
                ->where(function ($query) use ($request){
                    if (isset($request->filter)) {
                        $query->whereHas('filters', function ($query) use ($request){
                            $query->whereIn('name_id',$request->filter);
                        });
                    }
                })
                ->where(function($q) use ($range_price) {
                    if (isset($range_price)) {
                        $q->whereBetween('price', $range_price)->orWhereBetween('reduced_price', $range_price);
                    }
                })
                ->where(function ($query) use ($shop_settings, $sort){
                    if ($sort == "newest") {
                        $query->where('created_at', '>', Carbon::now()->subSeconds($shop_settings->time_new));
                    }
                })
                ->where('active', '1')
                ->orderBy( $orber_by['order'] , $orber_by['direction'] )
                ->get();
            $productCount = count($products);
            $products = Product::with(['category'])
                ->select(DB ::raw('*','DISTINCT(id)'))
                ->whereHas('category',function ($query) use ($cat_id){
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
                ->where(function ($query) use ($request){
                    if (isset($request->filter)) {
                        $query->whereHas('filters', function ($query) use ($request){
                            $query->whereIn('name_id',$request->filter);
                        });
                    }
                })

                ->where(function($q) use ($range_price) {
                    if (isset($range_price)) {
                        $q->whereBetween('price', $range_price)->orWhereBetween('reduced_price', $range_price);
                    }
                })
//                ->where(function ($query) use ($shop_settings, $sort){
//                    if ($sort == "newest") {
//                        $query->where('created_at', '>', Carbon::now()->subSeconds($shop_settings->time_new));
//                    }
//                })
                ->where('active', '1')
                ->orderBy( $orber_by['order'] , $orber_by['direction'] )
                ->paginate($productCount);
        }else{

            $products = Product::with(['category'])
                ->select(DB ::raw('*','DISTINCT(id)'))
                ->whereHas('category',function ($query) use ($cat_id){
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
                    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    /*
                     *
                     *
                     *
                     *
                     *
                     $query
                            ->whereIn('product_id','id');
                    *
                     *
                     *
                     *
                     *
                     */
                    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                })
                ->where(function ($query) use ($request){
                    if (isset($request->filter)) {
                        $query->whereHas('filters', function ($query) use ($request){
                            $query->whereIn('name_id',$request->filter);
                        });
                    }
                })
                ->where(function($q) use ($range_price) {
                    if (isset($range_price)) {
                        $q->whereBetween('price', $range_price)->orWhereBetween('reduced_price', $range_price);
                    }
                })
//                ->where(function ($query) use ($shop_settings, $sort){
//                    if ($sort == "newest") {
//                        $query->where('created_at', '>', Carbon::now()->subSeconds($shop_settings->time_new));
//                    }
//                })
                ->orderBy( $orber_by['order'] , $orber_by['direction'] )->orderBy('product_name', 'DESC')
                ->where('active', '1')
                ->paginate($items_per_page);

            $showAllButton = true;

            if ($products->total() <= $products->count()) {
                $showAllButton = false;
            }

        }

        $products_by_category = Product::with(['category'])
            ->select(DB ::raw('*','DISTINCT(id)'))
            ->whereHas('category',function ($query) use ($cat_id){
                $query->whereIn('id',$cat_id);
            })
            ->has('product_information')
            ->where('active', '1')
            ->get();

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
            if (!is_null($categories_find->parent->parent)) {
                array_push($breadcrumbs,(object) ['page' => $categories_find->parent->parent->$category, "link" => route('category.showAll',[$categories_find->parent->parent->id,$categories_find->parent->parent->slug])]);
            }
            array_push($breadcrumbs,(object) ['page' => $categories_find->parent->$category, "link" => route('category.showAll',[$categories_find->parent->id,$categories_find->parent->slug])]);
            array_push($breadcrumbs,(object) ['page' => $categories_find->$category, "link" => route('category.showAll',[$categories_find->id,$categories_find->slug])]);
        }else {
            array_push($breadcrumbs,(object) ['page' => $categories_find->$category, "link" => route('category.showAll',[$categories_find->id,$categories_find->slug])]);
        }


        // Count the products under a certain category
        $count = $products->count();

        // From Traits/CartTrait.php
        // ( Count how many items in Cart for signed in user )
        //$cart_count = $this->countProductsInCart();

        //get discount from DB table shop_settings
        $discount = DB::table('shop_settings')->first();

        $products_id = Product::with(['category'])
            ->select(DB ::raw('id','DISTINCT(id)'))
            ->whereHas('category',function ($query) use ($cat_id){
                $query->whereIn('id',$cat_id);
            })
//            ->where(function ($query) use ($shop_settings, $sort){
//                if ($sort == "newest") {
//                    $query->where('created_at', '>', Carbon::now()->subSeconds($shop_settings->time_new));
//                }
//            })
            ->where('active', '1')
            ->orderBy( $orber_by['order'] , $orber_by['direction'] )
            ->get()
            ->pluck('id');

        //Get all sizes unique  from bd product_information
        $sizes = DB::table('product_information')->select('size')->whereIn('product_id' , $products_id)->where("size","<>",1)->where("size","<>",0)->distinct()->orderBy('size', 'asc')->get();

        //Get all colors unique  from bd table_colors
        $colors = DB::table('product_information')->select('color')->whereIn('product_id' , $products_id)->whereNotNull('color')->distinct()->get();

        //$metaTags = $this->metaTags($categories_find);

        $title = "";

        if ( App::getLocale() == 'ua' ? empty($categories[0]->seo_title)  : empty($categories[0]->seo_title_ru) )
        {
            $title = trans('messages.category_title', [
//                'Title' => App::getLocale() == 'ua' ? $categories[0]->category  : $categories[0]->category_ru,
                'Title' => App::getLocale() == 'ua' ? ($categories[0]->h1 ? : $categories[0]->category)  : ($categories[0]->h1_ru? : $categories[0]->category_ru),
            ]);
        }
        else
        {
            $title = App::getLocale() == 'ua' ? $categories[0]->seo_title  : $categories[0]->seo_title_ru;
        }

        $title .= ( Input::has("page") && Input::get("page") != 1 ) ? ( trans('messages.page' ) . Input::get("page") ) : ("");

        $description = "";

        if ( App::getLocale() == 'ua' ? empty($categories[0]->seo_description)  : empty($categories[0]->seo_description_ru) ) {

            $description = trans('messages.category_description', [
                // 'Title' => App::getLocale() == 'ua' ? $categories[0]->category : $categories[0]->category_ru,
                'Title' => App::getLocale() == 'ua' ? ($categories[0]->h1 ? : $categories[0]->category)  : ($categories[0]->h1_ru? : $categories[0]->category_ru),
//                'page' => Input::has("page") ? Input::get("page") : 1,
            ]);
        }
        else
        {
            $description =  App::getLocale() == 'ua' ? $categories[0]->seo_description  : $categories[0]->seo_description_ru;
        }

        $description .= ( Input::has("page") && Input::get("page") != 1 ) ? ( trans('messages.page' ) . Input::get("page") ) : ("");


        $keywords = App::getLocale() == 'ua' ? $categories[0]->seo_keywords  : $categories[0]->seo_keywords_ru;

        //dd($categories[0], $keywords);


        $canonical = $request->url();


        return view('category.show', compact('filter_types', 'canonical','shop_settings','showAllButton','products','categories', 'products_by_category', 'slug','range_price','brands', 'categoryAll', 'search', 'discount','brandsAll','sizes','colors','breadcrumbs', 'title', 'description', 'keywords'))->with('count', $count)->with('categoryId',$id);
    }

    /** Display Products by Brand
     *
     * @param $id
     * @return $this
     */
    public function displayProductsByBrand($id) {


        // Get the Brand ID , so we can display the brand name under each list view
        $brands = Brand::where('id', $id)->get();

        $brands_find = Brand::where('id', $id)->find($id);

        // If no brand exists with that particular ID, then redirect back to Home page.
        if (!$brands_find) {
            return redirect('/');
        }

        // From Traits/CategoryTrait.php
        // ( Show Categories in side-nav )
        $categoryAll = $this->categoryAll();

        // From Traits/BrandAll.php
        // Get all the Brands
        $brand = $this->brandsAll();

        // Get all the Brands in NavBar template
        $brandsAll  = $this->brandsAll();

        // From Traits/SearchTrait.php
        // ( Enables capabilities search to be preformed on this view )
        $search = $this->search();

        // Get the Products under the Brand ID
//        $products = Product::where('brand_id', '=', $id)->get();

        // Get all latest products, and paginate them by 10 products per page
        $showAll = Input::get('showAll');
        if($showAll != null) {
            $products = Product::where('brand_id',  $id)->latest('created_at')->get();
            $productCount = count($products);
            $products = Product::where('brand_id',  $id)->latest('created_at')->paginate($productCount);
        }else{
            $products = Product::where('brand_id',  $id)->latest('created_at')->paginate(18);
        }

        // Count the products under a certain brand
        $count = $products->count();

        // From Traits/CartTrait.php
        // ( Count how many items in Cart for signed in user )
        //$cart_count = $this->countProductsInCart();

        return view('brand.show', compact('products', 'brands', 'brand', 'categoryAll', 'search', 'brandsAll'))->with('count', $count)->with('brandId',$id);
    }

    /** Subscribe user
     *
     * @param $email
     * @return $status
     */

    public function subscribeUser(Request $request){
        $this->validate($request, [
            'email' => 'required|email|unique:subscribers,email',
        ]);
        DB::table('subscribers')->insert([
            ['email' => $request->email],
        ]);
        flash()->overlay('Успіх', 'Ви підписались на нашу розсилку!',"success");
        return redirect()->back();

    }

    public function favorites(){

        // From Traits/CategoryTrait.php
        // ( Show Categories in side-nav )
        $categories = $this->categoryAll();
        //Need to fast fox bug (for all pages render bar with categories);
        $categoryAll =  $this->categoryAll();

        $categoryId = null;

        $products = Session::get('favorites');

        $shop_settings =  DB::table('shop_settings')->get();
        if(count($shop_settings)>0){$shop_settings = $shop_settings[0];}

        // From Traits/CartTrait.php
        // ( Count how many items in Cart for signed in user )
        //$cart_count = $this->countProductsInCart();


        $breadcrumbs = [
            (object) ['page' => trans('messages.mainPage'), "link" => url('/')],
            (object) ['page' => trans('messages.favorites'), "link" => url('/favorites')]
        ];

        $favoritesSeo =  DB::table('seo')->where('page','favorites')->first();

        $metaTags = $this->metaTags($favoritesSeo);

        return view('pages.favorites', compact('categories','categoryAll','categoryId','products','shop_settings','breadcrumbs','metaTags' ));
    }

    public function showPage($name){
        // From Traits/CategoryTrait.php
        // ( Show Categories in side-nav )
        $page = Page::where('name',$name)->first();

        if(!$page){
            return redirect('/');
        }


        $obj = (object) ['page' => trans('messages.mainPage'), "link" => url('/')];
        $breadcrumbs = [$obj];


        switch ($name) {
            case "contacts":
                $obj = (object) ['page' => trans('messages.contacts'), "link" => url('page/contacts')];
                break;
            case "about-us":
                $obj = (object) ['page' => trans('messages.aboutUs'), "link" => url('page/about-us')];
                break;
            case "guarantee":
                $obj = (object) ['page' => trans('messages.guarantee'), "link" => url('page/guarantee')];
                break;
            case "payment-and-delivery":
                $obj = (object) ['page' => trans('messages.paymentAndDelivery'), "link" => url('page/payment-and-delivery')];
                break;
        }
        array_push($breadcrumbs,$obj);

        $categories = $this->categoryAll();
        //Need to fast fox bug (for all pages render bar with categories);
        $categoryAll =  $this->categoryAll();

        $categoryId = null;

        // From Traits/CartTrait.php
        // ( Count how many items in Cart for signed in user )
        //$cart_count = $this->countProductsInCart();

        $page = Page::where('name',$name)->first();

        $mainPageImages = DB::table('main_static_page_images')->where('page_id',$page->id)->get();

        $metaTags = $this->metaTags($page);

        return view('pages.page', compact('page','categories','categoryAll','categoryId','mainPageImages','breadcrumbs','metaTags'));
    }

    // create this method for SEO optimization
    public function getTableSizeInstruction (){
        return view('partials.sizeInstruction')->render();
    }



}