<?php

namespace App\Http\Controllers;


use App;
use App\FilterType;
use App\Http\Traits\SeoTrait;
use App\Product;
use App\Product_material;
use App\Category;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\Auth;
use Cookie;
use Illuminate\Support\Facades\Input;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\ProductEditRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Traits\BrandAllTrait;
use App\Http\Traits\CategoryTrait;
use App\Http\Traits\SearchTrait;
use App\Http\Traits\CartTrait;
use Session;
use Validator;


class ProductsController extends Controller {

    use BrandAllTrait, CategoryTrait, SearchTrait, CartTrait, SeoTrait;


    /**
     * Show all products
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showProducts() {

        // Get all latest products, and paginate them by 10 products per page
//        $product = Product::latest('created_at')->paginate(10);
        $product = Product::with('photos', 'featuredPhoto' )->orderBy('updated_at','desc')->get();
//        $product = Product::orderBy('updated_at','desc')->take('10')->get();

        // Count all Products in Products Table
        $productCount = Product::all()->count();

        // From Traits/CartTrait.php
        // ( Count how many items in Cart for signed in user )
        //$cart_count = $this->countProductsInCart();

        return view('admin.product.show', compact('productCount', 'product'));
    }


    /**
     * Return the view for add new product
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addProduct() {
        // From Traits/CategoryTrait.php
        // ( This is to populate the parent category drop down in create product page )
        $categories = $this->parentCategory();

        $filter_list = FilterType::with(['filter_names' => function ($query) {
            $query->orderBy('id', 'asc');

        }])->get();

        // From Traits/BrandAll.php
        // Get all the Brands
        $brands = $this->brandsAll();

        // From Traits/CartTrait.php
        // ( Count how many items in Cart for signed in user )
        //$cart_count = $this->countProductsInCart();
        //select colors in palletPicker
        $colors =  DB::table('colors_table')->get();
        $colorsArr = [];
        foreach ($colors as $color) {
            $colorsArr[] = $color->color;
        }
        return view('admin.product.add', compact('categories', 'brands', 'colorsArr' , 'filter_list'));
    }


    /**
     * Add a new product into the Database.
     *
     * @param ProductRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addPostProduct(Request $request) {

        $validator = Validator::make($request->all(), [
            'code' => '|unique:products|numeric',
            'product_name_ru' => 'required|max:75|min:3',
            'product_name' => 'required|max:75|min:3',
            'product_qty' => 'max:2|min:1',
            'product_sku' => 'unique:products',
            'price' => 'required|max:9||min:1',
            'reduced_price' => 'max:9||min:1',
            'category' => 'required',
//            'cat_id.*' => 'required',
            'brand_id' => 'required',
            'description' => 'required|max:2500|min:10',
            'product_spec' => 'max:3500',
            'color' => 'required',
            'main_category_id' => 'required|exists:categories,id',
            'cat_id' => 'required',
            'filter_id' => 'exists:filter_names,id',
        ]);

        $validator->after(function ($validator) use ($request){
            if (count($request->get('cat_id'))) {
                if (!in_array($request->get('main_category_id'), $request->get('cat_id'))) {
                    $validator->errors()->add('main_category_id', 'Основна категорія повинна відповідати одній з підкатегорій');
                }
            }
        });

        if ($validator->fails()) {
            return redirect(route('admin.product.add'))
                ->withErrors($validator)
                ->withInput();
        }

        // Check if checkbox is checked or not for featured product
        $featured = Input::has('featured') ? true : false;

        // Replace any "/" with a space.
        $product_name =  str_replace("/", " " ,$request->input('product_name'));
        $product_name_ru =  str_replace("/", " " ,$request->input('product_name_ru'));

            $product = Product::create([
                'product_name' => $product_name,
                'product_name_ru' => $product_name_ru,
                'code' => $request->input('code'),
                'active' => $request->input('active'),
                'product_sku' => $request->input('product_sku'),
                'price' => $request->input('price'),
                'reduced_price' => $request->input('reduced_price'),
                'brand_id' => $request->input('brand_id'),
                'featured' => $featured,
                'description' => $request->input('description'),
                'description_ru' => $request->input('description_ru'),
                'product_spec' => $request->input('product_spec'),
                'seo_keywords' => $request->input('seo_keywords'),
                'seo_title' => $request->input('seo_title'),
                'seo_description' => $request->input('seo_description'),
            ]);
            $product->category()->sync($request->input('cat_id'));

            $filters = [];

            foreach ($request->input('filters_id') as $filter_id)
            {
                array_push($filters, (new App\FilterValue())->fill([
                    'name_id' => $filter_id,
                    'type_id' => App\FilterName::findOrFail($filter_id)->filter_type->id
                ]));
            }

            $product->filters()->saveMany($filters);


            $product->product_material()->create([
                'material' =>  $request->input('product_spec'),
                'material_ru' =>  $request->input('product_spec_ru'),
            ]);

        // insert in product_information DB info, about color and sie
        foreach($request->input('color') as $color){
            $color_product = $color['color'];
            foreach($color['item'] as $item)
            {
                DB::table('product_information')->insert(
                    [
                        'product_id' => $product->id,
                        'color' =>  $color_product,
                        'size' =>   $item['size'],
                        'quantity'=>$item['quantity'],
                    ]
                );
            }
        }

            // Save the product into the Database.

            $product->save();

            // Flash a success message
            flash()->overlay('Успіх', 'Продук створено успішно!');

        // }


        // Redirect back to Show all products page.
        return redirect()->route('admin.product.show');
    }


    /**
     * This method will fire off when a admin chooses a parent category.
     * It will get the option and check all the children of that parent category,
     * and then list them in the sub-category drop-down.
     *
     * @return \Illuminate\Http\Response
     */
    public function categoryAPI() {
        // Get the "option" value from the drop-down.
        $input = Input::get('option');

        // Find the category name associated with the "option" parameter.
        $category = Category::find($input);

        // Find all the children (sub-categories) from the parent category
        // so we can display then in the sub-category drop-down list.
        $subcategory = $category->children();


        //return \Response::make($category->children);

        $categories_list = collect();

        $categories_list->push(['id' => $category->id, 'category' => $category->category ]);

        foreach ($category->children as $children) {
            $categories_list->push(['id' => $children->id, 'category' => $category->category . ' - ' . $children->category ]);

            foreach ($children->children as $sub_children) {
                $categories_list->push(['id' => $sub_children->id, 'category' => $category->category . ' - ' . $children->category. " - " .$sub_children->category ]);
            }
        }



        // Return a Response, and make a request to get the id and category (name)
//        dd( $subcategory->get(['id', 'category'])->push( ['id' => $category->id, 'category' => $category->category ] )->toArray() );
//        return \Response::make($subcategory->get(['id', 'category']));
//        return \Response::make( $subcategory->get(['id', 'category'])->push(['id' => $category->id, 'category' => $category->category ]) );
        return \Response::make( $categories_list );
    }


    /**
     * Return the view to edit & Update the Products
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editProduct($id) {

//        // Find the product ID
//        $product = Product::find($id);
//
//        // If no product exists with that particular ID, then redirect back to Show Products Page.
//        if (!$product) {
//            return redirect('admin/products');
//        }
//
//        // From Traits/CategoryTrait.php
//        // ( This is to populate the parent category drop down in create product page )
//        $categories = $this->parentCategory();
//
//        // From Traits/BrandAll.php
//        // Get all the Brands
//        $brands = $this->BrandsAll();
//
//        // From Traits/CartTrait.php
//        // ( Count how many items in Cart for signed in user )
//        //$cart_count = $this->countProductsInCart();
//
////        $parentCategory = $product->category->pluck('parent_id')->first();
//
//        $parentCategory = (count($product->category)) ? ( ($product->category[0]->parent )  ? ( ($product->category[0]->parent->parent )  ? $product->category[0]->parent->parent->id : $product->category[0]->parent->id) : null ) :  null;
//
//        $selectedChildrenCategories = $product->category->pluck('id')->toArray();
//
//        $category = Category::find($parentCategory);
//
//        $childrenCategories = collect();
//
//        if ($category) {
//
//            $childrenCategories->push(['id' => $category->id, 'category' => $category->category]);
//
//            foreach ($category->children as $children) {
//                $childrenCategories->push(['id' => $children->id, 'category' => $category->category . ' - ' . $children->category]);
//
//                foreach ($children->children as $sub_children) {
//                    $childrenCategories->push(['id' => $sub_children->id, 'category' => $category->category . ' - ' . $children->category . " - " . $sub_children->category]);
//                }
//            }
//        } elseif( count($product->category) ) {
//            $childrenCategories->push(['id' => $product->category[0]->id, 'category' => $product->category[0]->category]);
//        }
//
//        //dd($childrenCategories);
//
//        //$childrenCategories = Category::where('parent_id', $product->category->pluck('parent_id')->first() )->get();
//
//        //Select all data from product_information table
//        $productInformation =  DB::table('product_information')->where('product_id',  $id)->get();
//
//        // Return view with products and categories
//        $colors =  DB::table('colors_table')->get();
//        $colorsArr = [];
//        foreach ($colors as $color) {
//            $colorsArr[] = $color->color;
//        }

//        return view('admin.product.edit', compact('product', 'categories', 'brands', 'productInformation','parentCategory','childrenCategories','selectedChildrenCategories', 'colorsArr'));

        // Find the product ID
        $product = Product::with('category', 'product_information', 'filters')->find($id);

        if (!$product) {
            return redirect('admin/products');
        }
        $categories = Category::with('children.children')->whereNull('parent_id')->get();

        $filter_list = FilterType::with(['filter_names' => function ($query) {
            $query->orderBy('id', 'asc');

        }])->get();

        $selected_categories = $product->category->pluck('id')->toArray();

        //Select all data from product_information table
        $product_information =  $product->product_information;

//        Return view with products and categories
        $colors =  DB::table('colors_table')->get();
        $colorsArr = [];
        foreach ($colors as $color) {
            $colorsArr[] = $color->color;
        }

//        dd($product, $categories, $selected_categories, $product_information);

        return view('admin.product.edit', compact('product', 'categories', 'product_information','selected_categories', 'colorsArr', 'filter_list'));

    }


    /**
     * Update a Product
     *
     * @param $id
     * @param ProductEditRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProduct($id, Request $request) {

        if ( !$request->input('discount') == "" && !($request->input('discount') >= 5 && $request->input('discount') <= 70)) {
            abort(404);
        }

        //dd($request->all());

        $validator = Validator::make($request->all(), [
            'main_category_id' => 'required|exists:categories,id',
            'cat_id' => 'required',
            'filter_id' => 'exists:filter_names,id',
        ]);

        $validator->after(function ($validator) use ($request){
            if ( ! in_array( $request->get('main_category_id'), $request->get('cat_id') ) ) {
                $validator->errors()->add('main_category_id', 'Основна категорія повинна відповідати одній з підкатегорій');
            }
        });

        if ($validator->fails()) {
            return redirect(route('admin.product.edit',[$id]))
                ->withErrors($validator)
                ->withInput();
        }

        // Check if checkbox is checked or not for featured product
        $featured = Input::has('featured') ? true : false;

        // Find the Products ID from URL in route
        $product = Product::findOrFail($id);


        if (Auth::user()->id == 2) {
            // If user is a test user (id = 2),display message saying you cant delete if your a test user
            flash()->error('Error', 'Cannot edit Product because you are signed in as a test user.');
        } else {
            // Update product
            $product->update(array(
                'code' => $request->input('code'),
                'active' => $request->input('active'),
                'product_name_ru' => $request->input('product_name_ru'),
                'product_name' => $request->input('product_name'),
                'product_qty' => $request->input('product_qty'),
                'product_sku' => $request->input('product_sku'),
                'main_category_id' => $request->input('main_category_id'),
                'price' => $request->input('price'),
                'discount' => (!$request->input('discount') == "" ) ? $request->input('discount') : null,
                'reduced_price' => (!$request->input('discount') == "" ) ? ceil($request->input('price') * (100 - $request->input('discount')) / 100 ) : 0,
                'brand_id' => $request->input('brand_id'),
                'featured' => $featured,
                'description' => $request->input('description'),
                'description_ru' => $request->input('description_ru'),
                'seo_keywords' => $request->input('seo_keywords'),
                'seo_title' => $request->input('seo_title'),
                'seo_description' => $request->input('seo_description'),
            ));

            $product->category()->sync($request->input('cat_id'));

            $product->filters()->delete();

            if (count($request->input('filters_id'))) {

                $filters = [];

                foreach ($request->input('filters_id') as $filter_id) {
                    array_push($filters, (new App\FilterValue())->fill([
                        'name_id' => $filter_id,
                        'type_id' => App\FilterName::findOrFail($filter_id)->filter_type->id
                    ]));
                }

                $product->filters()->saveMany($filters);
            }

            $product_material = Product_material::where('product_id' ,$product->id)->update([
               'material' =>  $request->input('product_spec'),
               'material_ru' =>  $request->input('product_spec_ru'),
            ]);


            // Update the product with all the validation rules
//            $product->update($request->all());


            // insert in product_information DB info, about color and sie
            if($request->input('color')){
                foreach ($request->input('color') as $color) {
                    $color_product = $color['color'];
                    foreach ($color['item'] as $item) {
                        DB::table('product_information')->insert(
                            [
                                'product_id' => $product->id,
                                'color' => $color_product,
                                'size' => $item['size'],
                                'quantity' => $item['quantity'],
                            ]
                        );
                    }
                }
            }
            // Flash a success message
            flash()->overlay('Успіх', 'Продукт змінено успішно!');
        }

        // Redirect back to Show all categories page.
        return redirect()->route('admin.product.show');
    }

    public function updateProductInfoById($id ,Request $request){
        DB::table('product_information')
            ->where('id',$request->idProductInfo)
            ->update([
                'quantity' => $request->quantity ,
                'color' => $request->color ,
            ]);


        return response()->json("Success",200);
    }
    public function deleteProductInfoById($id ,Request $request){
        DB::table('product_information')
            ->where('id',$request->idProductInfo)
            ->where('product_id',$id)
            ->delete();
        return response()->json("DELETE",200);
    }


    /**
     * Delete a Product
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteProduct($id) {

        if (Auth::user()->id == 2) {
            // If user is a test user (id = 2),display message saying you cant delete if your a test user
            flash()->error('Error', 'Cannot delete Product because you are signed in as a test user.');
        } else {
            // Find the product id and delete it from DB.
            $product = Product::findOrFail($id);

            foreach ($product->photos as $path){

                unlink($path->path);
            }

            $product->delete();
        }
        // Then redirect back.
        return redirect()->back();
    }


    /**
     * Display the form for uploading images for each Product
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function displayImageUploadPage($id) {

        // Get the product ID that matches the URL product ID.
        $product = Product::where('id',  $id)->get();

        // From Traits/CartTrait.php
        // ( Count how many items in Cart for signed in user )
        //$cart_count = $this->countProductsInCart();

        return view('admin.product.upload', compact('product'));
    }


    /**
     * Show a Product in detail
     *
     * @param $slug_name
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($slug) {

        // Find the product by the product name in URL

        $product = Product::with('product_information','product_material','category')->where('slug',$slug)->where('active' , 1)->first();

        //return $product;

        //dd($product->product_information[0]);

        if(!$product){
            return redirect('/');
        }

//        if ( ! count($product->product_information) ) {
//            return redirect(route('category.showAll',['id' => $product->category->first()->id, "slug" => $product->category->first()->slug] ) ,  301);
//        }

//        $sku_explode  = explode(" ", $product->product_sku);
//        array_pop ($sku_explode);
//        $sku_explode = implode( ' ' , $sku_explode);
        $sku_for_search = preg_replace("/ [a-zA-Z0-9]+$/", "", $product->product_sku);

        $related_products = Product::has('product_information')->where('product_sku', 'like' , $sku_for_search.'%')->where('id', "<>", $product->id)->where('active' , 1)->get();

        //dd($related_products);

        // From Traits/SearchTrait.php
        // Enables capabilities search to be preformed on this view )
        $search = $this->search();
        //Need to fast fox bug (for all pages render bar with categories);
        $categoryAll =  $this->categoryAll();
        // From Traits/CategoryTrait.php
        // ( Show Categories in side-nav )
        $categories = $this->categoryAll();

        // Get brands to display left nav-bar
        $brands = $this->BrandsAll();

        // From Traits/CartTrait.php
        // ( Count how many items in Cart for signed in user )
        //$cart_count = $this->countProductsInCart();

//        $similar_product = Product::where('id', '!=', $product->id)
//            ->where(function ($query) use ($product) {
//                $query->where('brand_id', '=', $product->brand_id)
//                    ->orWhere('cat_id', '=', $product->cat_id);
//            })->get();

        $breadcrumbs = [
            (object) ['page' => trans('messages.mainPage'), "link" => url('/')]
        ];

        $locale = App::getLocale();

        if ($locale != "ua") {
            $category = "category_".$locale;
        }else{
            $category = "category";
        }

        $product_category = ( $product->main_category ) ? : $product->category->first();

//        if (!is_null($product->category->first()->parent)){
//            $parent_cat = $product->category->first()->parent;
//            if (!is_null($product->category->first()->parent->parent)){
//                array_push($breadcrumbs,(object) ['page' => $product->category->first()->parent->parent->$category, "link" => route('category.showAll',[$product->category->first()->parent->parent->id,$product->category->first()->parent->parent->slug])]);
//            }
//            array_push($breadcrumbs,(object) ['page' => $product->category->first()->parent->$category, "link" => route('category.showAll',[$product->category->first()->parent->id,$product->category->first()->parent->slug])]);
//            array_push($breadcrumbs,(object) ['page' => $product->category->first()->$category, "link" => route('category.showAll',[$product->category->first()->id,$product->category->first()->slug])]);
//            array_push($breadcrumbs,(object) ['page' => $product->product_sku, "link" => route('show.product', $product->slug)]);
//        }else {
//            array_push($breadcrumbs,(object) ['page' => $product->category->first()->$category, "link" => route('category.showAll',[$product->category->first()->id,$product->category->first()->slug])]);
//            array_push($breadcrumbs,(object) ['page' => $product->product_sku, "link" => route('show.product', $product->slug)]);
//        }

        if (!is_null($product_category->parent)){
            $parent_cat = $product_category->parent;
            if (!is_null($product_category->parent->parent)){
                array_push($breadcrumbs,(object) ['page' => $product_category->parent->parent->$category, "link" => route('category.showAll',[$product_category->parent->parent->id,$product_category->parent->parent->slug])]);
            }
            array_push($breadcrumbs,(object) ['page' => $product_category->parent->$category, "link" => route('category.showAll',[$product_category->parent->id,$product_category->parent->slug])]);
            array_push($breadcrumbs,(object) ['page' => $product_category->$category, "link" => route('category.showAll',[$product_category->id,$product_category->slug])]);
            array_push($breadcrumbs,(object) ['page' => $product->product_sku, "link" => route('show.product', $product->slug)]);
        }else {
            array_push($breadcrumbs,(object) ['page' => $product_category->$category, "link" => route('category.showAll',[$product_category->id,$product_category->slug])]);
            array_push($breadcrumbs,(object) ['page' => $product->product_sku, "link" => route('show.product', $product->slug)]);
        }

        //$metaTags = $this->metaTags($product);

        $title = trans('messages.product_title', [
            'Title' => (App::getLocale()=='ua' ? $product->product_name  : $product->product_name_ru) .  ' арт : ' . $product->product_sku,
        ]);

        $description = trans('messages.product_description', [
            'Title' => (App::getLocale()=='ua' ? $product->product_name  : $product->product_name_ru) .  ' арт : ' . $product->product_sku,
        ]);

        //get discount from DB table shop_settings
        $discount = DB::table('shop_settings')->first();
        //Select quantity size color from db
        $productInfo =  DB::table('product_information')->where('product_id', $product->id)->get();

        $reviews = $product->reviews()->orderBy('created_at', 'desc')->get();

        //save seen products
        $seen_product_amount = 6;

        $seen_products = Session::get('seen_products');

        //dd(Session::all());

        if (!$seen_products) {
            $seen_products = [];
        }

        while (array_search($product->id, $seen_products) !== false) {
            unset($seen_products[array_search($product->id, $seen_products)]);
        }

        array_unshift( $seen_products, $product->id);

        while ( count($seen_products) > $seen_product_amount ) {
            unset( $seen_products[ count($seen_products) - 1 ] );
        }

        Session::put('seen_products', $seen_products);

        $seen_products_models = Product::whereIn('id' , $seen_products )->get()->sortBy(function($model) use ($seen_products){
            return array_search($model->getKey(), $seen_products);
        });

//        return view('pages.show_product', compact('breadcrumbs','product', 'related_products', 'reviews', 'search', 'brands', 'categories', 'similar_product', 'discount','productInfo','categoryAll', 'title', 'description'))->with('categoryId', $product->category->first()->id);
        return view('pages.show_product', compact('seen_products_models', 'breadcrumbs','product', 'related_products', 'reviews', 'search', 'brands', 'categories', 'similar_product', 'discount','productInfo','categoryAll', 'title', 'description'))->with('categoryId', ( $product->main_category ) ? $product->main_category->id: $product->category->first()->id);
    }

    public function getProductInfo(Request $request){
        $id_product =$request->product_id;

        if($request->size != null) {
            $size_product = $request->size;
            $infoProduct = DB::table('product_information')->select(['color','quantity'])->where('product_id','=',$id_product)->where('size','=',$size_product)->get();
        }
        else{
            $infoProduct = DB::table('product_information')->select('size')->where('product_id','=',$id_product)->distinct()->orderBy('size')->get();
        }

        return json_encode($infoProduct);

        
    }

    public function addProductToFavorites(Request $request){

        $id = $request->get('id');

        $product = Product::findorfail($id);

        $products[] = $product;

        $count = false;
        if(Session::has('favorites')){
           foreach (Session::get('favorites') as $prod){
               if($prod->id == $product->id){
                   $count = true;
                   break;
               }
           }
            if($count == false){
                Session::push('favorites',$product);
            }

        }else{
            Session::forget('favorites');
            Session::put('favorites',$products);
            Session::save();
        }


        return response()->json("Success",200);;
    }
    
        public function deleteProductToFavorites(Request $request){

        $id = $request->get('id');

        $products = [];

        if(Session::has('favorites')){
            foreach (Session::get('favorites') as $product){
                if($id != $product->id){
                    array_push($products,$product);
                }

            }

            Session::forget('favorites');
            Session::put('favorites',$products);
            Session::save();

        }

        return response()->json("Success",200);
    }

}