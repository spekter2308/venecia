<?php

namespace App\Http\Controllers;

use App\Callback;
use App\Cart;
use App\Category;
use App\Classes\MainPhoto;
use App\Classes\MainPhotoStatic;

use App\FilterName;
use App\FilterType;
use App\OneClickOrder;
use App\Page;
use App\Review;
use App\User;
use App\Order;
use App\Product;

use Carbon\Carbon;
use File;
use Illuminate\Http\Request;
use App\Http\Traits\CartTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

use Mail;
use App\OrderProduct;
use Yajra\Datatables\Datatables;


class AdminController extends Controller {

    use CartTrait;


    /**
     * Show the Admin Dashboard
     * 
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index() {

        $one_click_orders = OneClickOrder::with('user', 'product.product_information', 'product.photos')->orderBy('created_at', 'desc')->get();

//       dd ($one_click_orders[0]->product);

        // From Traits/CartTrait.php
        // ( Count how many items in Cart for signed in user )
        $cart_count = $this->countProductsInCart();
        
        // Get all the orders in DB
//        $orders = Order::all();

        // status 0 - not confirmed order
        $orders = OrderProduct::where('status' , '0')->get();


        // Get the grand total of all totals in orders table
        $count_total = Order::sum('total');

        // Get all the users in DB
        $users = User::all();
        
        
        // Get all the carts in DB
        $carts = Cart::all();

        // Get all the carts in DB
        $products = Product::all();
        
        // Select all from Products where the Product Quantity = 0
        $product_quantity = Product::where('product_qty', '=', 0)->get();
        //Select all settings from  shop_settings table
        $shop_settings =  DB::table('shop_settings')->first();
//        $shop_settings =  DB::table('shop_settings')->get();
//        if(count($shop_settings)>0){$shop_settings = $shop_settings[0];}

        return view('admin.pages.index', compact('one_click_orders', 'cart_count', 'orders', 'users', 'carts', 'count_total', 'products', 'product_quantity', 'shop_settings'));
    }

    public function reviews() {
        return view('admin.pages.review');
//        return view('datatables.eloquent.basic');
    }

    public function getReviews() {
        $model = Review::select([
            'reviews.id as id',
            'name',
            'message',
            'product_id',
            'reviews.created_at as created_at',
            'products.product_name as product_name',
            'products.product_sku as product_sku',
            'products.slug as slug',
        ])
            ->join('products', 'reviews.product_id', '=', 'products.id')->get();

        return Datatables::of($model)
            ->addColumn('action', function ($review) {
                return '<a target="'. $review->id.'" name="'. $review->name.'" message="'. $review->message.'" product_id="'. $review->product_id.'"class="btn btn-xs btn-success review-edit"><i class="material-icons white-text">mode_edit</i></a>'.
                    '<a target="'. $review->id .'" class="btn btn-xs btn-danger review-delete"><i class="material-icons white-text">delete_forever</i></a>';
            })
            ->editColumn('product_sku', '<strong><a href="/product/{{$slug}}">{{$product_sku}}</a></strong>')
            ->make(true);
    }

    public function shopSettings(Request $request){



        //if we have request discount change
        if(isset($request->discount)) {
            DB::table('shop_settings')
                ->update(['discount' => $request->discount]);
        }
        //if we have request code change
        if(isset($request->code)) {
            DB::table('shop_settings')
                ->update(['code' => $request->code]);
        }
        //if we have request state change
        if(isset($request->state)) {
            $state = $request->state === 'false'? true: false;
            DB::table('shop_settings')
                ->update(['discount_state' =>$state]);
            return  response()->json($state);
        }



    }

    public function getEmail(){

        // From Traits/CartTrait.php
        // ( Count how many items in Cart for signed in user )
        $cart_count = $this->countProductsInCart();

        // Get all the orders in DB
        $orders = Order::all();

        // Get the grand total of all totals in orders table
        $count_total = Order::sum('total');

        // Get all the users in DB
        $users = User::all();


        // Get all the carts in DB
        $carts = Cart::all();

        // Get all the carts in DB
        $products = Product::all();

        // Select all from Products where the Product Quantity = 0
        $product_quantity = Product::where('product_qty', '=', 0)->get();

        return view('admin.pages.sendEmail',compact('cart_count', 'orders', 'users', 'carts', 'count_total', 'products', 'product_quantity'));
    }


    public function showImages(){

        // From Traits/CartTrait.php
        // ( Count how many items in Cart for signed in user )
        $cart_count = $this->countProductsInCart();

        //select all images from DB table main_page_images
        $allMainImages =  DB::table('main_page_images')->get();
        //select all category from DB in
        $categories = Category::all();
        return view('admin.pages.showImages',compact('cart_count','allMainImages','categories'));
    }

    public function showImagesStatic(){
        // From Traits/CartTrait.php
        // ( Count how many items in Cart for signed in user )
        $cart_count = $this->countProductsInCart();

        //select all images from DB table main_static_page_images
        $allStaticMainImages =  DB::table('main_static_page_images')->get();
        //select all category from DB in
        $pages = Page::all();
        return view('admin.pages.showStaticImages',compact('cart_count','allStaticMainImages','pages'));
    }


    public function colorPiker(){
        // From Traits/CartTrait.php
        // ( Count how many items in Cart for signed in user )
        $cart_count = $this->countProductsInCart();

        //select all images from DB table main_page_images
        $allMainImages =  DB::table('main_page_images')->get();
        //select all category from DB in
        $categories = Category::all();
        
        $colors   = DB::table('colors_table')->get();
        
        return view('admin.pages.colorPiker',compact('cart_count','allMainImages','categories','colors'));
    }

    public function addColorPiker(Request $request){
        //concat color hash
       $color = '#'.$request->color;
        DB::table('colors_table')->insert(
            ['color' => $color , 'name' => $request->name]
        );
        flash()->overlay('Успіх', 'Колір доданий!','success');
        return redirect()->back();
    }

    public function updateColorPiker($id ,Request $request){
        DB::table('colors_table')
            ->where('id',$id)
            ->update([
                'color' => '#'.$request->color ,
                'name' => $request->name
            ]);

        return response()->json("Success",200);
    }

    public function deleteColorPiker(Request $request)
    {
        $color_id = $request->id;
        DB::table('colors_table')->where('id', '=', $color_id)->delete();

        return response()->json("DELETE",200);
        //$color_id = Input::get('id_delete');
//        DB::table('colors_table')->where('id', '=', $color_id)->delete();
//        flash()->overlay('Успіх', 'Колір видалено!');
//        return redirect()->back();
        
        
    }


    //Set MainPageImg to category button
    public function setPageToImg(Request $request){
        $page = Page::where('name',$request->page)->first();
        //Update photo category
        DB::table('main_static_page_images')
            ->where('id', $request->photoId)
            ->update(['page_id' => $page->id]);
        flash()->overlay('Успіх', 'Сторінка успішно приєднана до фото!','success');
        return redirect()->back();
    }

    
    //Set MainPageImg to category button
    public function setCategoryToImg(Request $request){
        $category = Category::where('id',$request->category)->first();
        //Update photo category
        DB::table('main_page_images')
            ->where('id', $request->photoId)
            ->update(['category_id' => $category->id]);
        flash()->overlay('Успіх', 'Категорія успішно приєднана до фото!','success');
        return redirect()->back();
    }

    public function addMainImages(Request $request){
        // Store the photo from the file instance
        // -- ('photo') is coming from "public/js/dropzone.forms.js" --
        $photo = $request->file('photo');
        //create main photo image
        MainPhoto::SavePhoto($photo);

        return response('image save', 200);

    }

    public function addMainImagesStatic(Request $request){
        // Store the photo from the file instance
        // -- ('photo') is coming from "public/js/dropzone.forms.js" --
        $photo = $request->file('photo');
        //create main photo image
        MainPhotoStatic::SavePhoto($photo);
        return response('image save', 200);

    }

    public function deleteMainImage($id){

        //find img to delete
       $imgToDelete = DB::table('main_page_images')
            ->where('id',$id)
            ->get();
        // delete img in folders
        File::delete([
            $imgToDelete[0]->path,
            $imgToDelete[0]->thumbnail_path
        ]);
        //delete img from DB
        DB::table('main_page_images')->where('id',$id)->delete();
        return redirect()->back();
    }
    public function deleteMainImageStatic($id){
        //find img to delete
       $imgToDelete = DB::table('main_static_page_images')
            ->where('id',$id)
            ->get();
        // delete img in folders
        File::delete([
            $imgToDelete[0]->path,
            $imgToDelete[0]->thumbnail_path
        ]);
        //delete img from DB
        DB::table('main_static_page_images')->where('id',$id)->delete();
        return redirect()->back();
    }
    public function sendEmails(Request $request){

        //Select all users who not admin
        $users = User::where('admin','=',0)->get();
        $subscribers = DB::table('subscribers')->get();

        $data = $request->all();

        foreach($users as $user){
            Mail::send('emails.emailUsers',  ['user' => $user,'data'=>$data], function ($message) use ($user,$data) {
                $message->from('shop@venezia-online.com.ua', 'Інтернет магазин "Венеція"');
                $message->to($user->email)->subject($data['title']);
            });
        }
        //fast add customer fix (send emails to  subscribers)
        foreach($subscribers as $user){
            Mail::send('emails.emailUsers',  ['user' => $user,'data'=>$data], function ($message) use ($user,$data) {
                $message->from('shop@venezia-online.com.ua', 'Інтернет магазин "Венеція"');
                $message->to($user->email)->subject($data['title']);
            });
        }

        flash()->success('Успіх', 'Лист відправленний користувачам!');
      return redirect()->back();

    }

    /**
     * Delete a user
     * 
     * @param $id
     * @return mixed
     */
    public function delete($id) {

        // Find the product id and delete it from DB.
        $user = User::findOrFail($id);

        if (Auth::user()->id == 2) {
            // If user is a test user (id = 2),display message saying you cant delete if your a test user
            flash()->error('Error', 'Cannot delete users because you are signed in as a test user.');
        } elseif ($user->admin == 1) {
            // If user is a admin, don't delete the user, else delete a user
            flash()->error('Error', 'Cannot delete Admin.');
        } else {
            $user->delete();
        }

        // Then redirect back.
        return redirect()->back();
    }


    /** Delete a cart session
     * 
     * @param $id
     * @return mixed
     */
    public function deleteCart($id) {
        // Find the product id and delete it from DB.
        $cart = Cart::findOrFail($id);

        if (Auth::user()->id == 2) {
            // If user is a test user (id = 2),display message saying you cant delete if your a test user
            flash()->error('Error', 'Cannot delete cart because you are signed in as a test user.');
        } else {
            $cart->delete();
        }

        // Then redirect back.
        return redirect()->back();
    }


    /**
     * Update the Product Quantity if empty for Admin dashboard
     * 
     * @param Request $request
     * @return mixed
     */
    public function update(Request $request) {

        // Validate email and password.
        $this->validate($request, [
            'product_qty' => 'required|max:2|min:1',
        ]);

        // Set the $qty to the quantity inserted
        $qty = Input::get('product_qty');

        // Set $product_id to the hidden product input field in the update cart from
        $product_id = Input::get('product');

        // Find the ID of the products in the Cart
        $product = Product::find($product_id);

        $product_qty = Product::where('id', '=', $product_id);

        if (Auth::user()->id == 2) {
            // If user is a test user (id = 2),display message saying you cant delete if your a test user
            flash()->error('Error', 'Cannot update product quantity because you are signed in as a test user.');
        } else {
            // Update your product qty
            $product_qty->update(array(
                'product_qty' => $qty
            ));
        }


        return redirect()->back();
        
    }



    public function settings() {

        // From Traits/CartTrait.php
        // ( Count how many items in Cart for signed in user )
        $cart_count = $this->countProductsInCart();

        //Select all settings from  shop_settings table
        $shop_settings =  DB::table('shop_settings')->get();
        if(count($shop_settings)>0){$shop_settings = $shop_settings[0];}

        $days = [
            '1 день','2 дні','3 дні','4 дні','5 днів','6 днів','7 днів','8 днів','9 днів','10 днів','11 днів','12 днів','13 днів','14 днів',
            '15 днів','16 днів','17 днів','18 днів','19 днів','20 днів', '21 день', '22 дні', '23 дні','24 дні','25 днів','26 днів','27 днів','28 днів','29 днів','30 днів','31 день'
        ];



        return view('admin.pages.setting', compact('cart_count', 'shop_settings','days'));
    }


    public function updateSetting(Request $request) {

        DB::table('shop_settings')
            ->update([
                    'sale' => $request->sale,
                    'new' => $request->new,
                    'time_new' => $request->time_new,
                ]);



        return redirect()->back();
    }

    public function callback(Request $request) {

        // ( Count how many items in Cart for signed in user )
        $cart_count = $this->countProductsInCart();

        $callbacks = Callback::all();

        // change status to 1 , 1 red messages
        DB::table('callback')->where('status',0)->update(['status'=> 1]);

        return view('admin.pages.callback', compact('cart_count','callbacks'));
    }

    /**
     * delete callback in orders_product !
     */
    public function deleteCallback($id)
    {
        $callback = Callback::find($id);
        $callback->delete();
        // Then redirect back.
        return redirect()->back();
    }

    public function sendMessage($id,Request $request){
        //fast add customer fix (send emails to  subscribers)

        $validator = \Validator::make($request->all(), [
            'title' => 'required|max:255',
            'content' => 'required|max:5000',
        ]);

        if ($validator->fails()) {
            return redirect(url('/admin/callback/send-mail',$id))
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();

        $callback = Callback::find($id);

        Mail::send('emails.emailUsers',  ['data'=>$data], function ($message) use ($callback,$data) {
             $message->from('shop@venezia-online.com.ua', 'Інтернет магазин "Венеція"');
             $message->to($callback->email)->subject($data['title']);
        });

        flash()->success('Успіх', 'Лист відправленний користувачу!');
         return redirect()->route('callback');
    }

    public function showCallback($id){
        // ( Count how many items in Cart for signed in user )
        $cart_count = $this->countProductsInCart();

        $callback = Callback::find($id);


        return view('admin.pages.show-callback', compact('cart_count','callback'));
    }

    public function showPages(){
        // ( Count how many items in Cart for signed in user )
        $cart_count = $this->countProductsInCart();
        //select all images from DB table main_static_page_images
        $allStaticMainImages =  DB::table('main_static_page_images')->get();
        //select all category from DB in

        $pages = Page::all();
        return view('admin.custom-pages.index',compact('pages','cart_count','allStaticMainImages'));
    }
    public function editPage($id){
        // ( Count how many items in Cart for signed in user )
        $cart_count = $this->countProductsInCart();

        $page = Page::find($id);
        return view('admin.custom-pages.edit',compact('page','cart_count'));
    }

    public function updatePage($id,Request $request){

        $page = Page::find($id);
        $page->update($request->all());

        flash()->success('Успіх', 'Сторінка оновленна');
        return redirect()->route('showPages');
    }

    public function  updateImgAttributes(Request $request){

     if($request->imgAtributes){
         $atributes = $request->imgAtributes;

         foreach ($atributes as $atribute ){
             DB::table('main_page_images')
                 ->where('id',$atribute['data-id'])
                 ->update([
                     'option' => json_encode($atribute)
                 ]);
         }
     }

        return response()->json("Success",200);

    }

    // generate products and category slug url (domain/admin/slugs)
    public static function generateSlugs(){

        $replace = ['а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','ё'=>'e','ж'=>'j','з'=>'z','и'=>'y','й'=>'y','к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u','ф'=>'f','х'=>'h','ц'=>'c','ч'=>'ch','ш'=>'sh','щ'=>'shch','ы'=>'y','э'=>'e','ю'=>'yu','я'=>'ya','ъ'=>'','ь'=>'','і'=> 'i'];

        $products = Product::all();
        foreach ($products as $product){


            // translit product name
            $product_name = function_exists('mb_strtolower') ? mb_strtolower($product->product_name) : strtolower($product->product_name); // переводим строку в нижний регистр (иногда надо задать локаль)
            $product_name = strtr($product_name, $replace);
            $product_name = preg_replace("/[^0-9a-z-_ ]/i", "", $product_name);
            $product_name = str_replace(" ", "-", $product_name);

            $art = str_replace(" ", "-", $product->product_sku);

            // add slug to product (product name + art)
            Product::where('id',$product->id)->update(['slug'=>$product_name.'-'.$art]);
        }

//        $categories = Category::all();
//
//        foreach ($categories as $category){
//            $category_name = function_exists('mb_strtolower') ? mb_strtolower($category->category) : strtolower($category->category); // переводим строку в нижний регистр (иногда надо задать локаль)
//            $category_name = strtr($category_name, $replace);
//            $category_name = preg_replace("/[^0-9a-z-_ ]/i", "", $category_name);
//            $category_name = str_replace(" ", "-", $category_name);
//
//            Category::where('id',$category->id)->update(['slug'=>$category_name]);
//        }

        return "success";




    }


    public function updateMainPage(Request $request){

        DB::table('seo')->where('page','main')->update([
            'seo_keywords' => $request->seo_keywords,
            'seo_keywords_ru' => $request->seo_keywords_ru,
            'seo_title' => $request->seo_title,
            'seo_title_ru' => $request->seo_title_ru,
            'seo_description' => $request->seo_description,
            'seo_description_ru' => $request->seo_description_ru,
            'description' => $request->description,
            'description_ru' => $request->description_ru,
            'intro_description' => $request->intro_description,
            'intro_description_ru' => $request->intro_description_ru,
        ]);


        return redirect(url('/admin/main'));
    }

    public function mainPage(){
        // ( Count how many items in Cart for signed in user )
        $cart_count = $this->countProductsInCart();

        $mainPage =  DB::table('seo')->where('page','main')->first();

        return view('admin.custom-pages.mainPage',compact('mainPage','cart_count'));
    }

    private static function createElement($xw, $now, $url, $priority){
        xmlwriter_start_element($xw, 'url');

        xmlwriter_start_element($xw, 'loc');
        xmlwriter_text($xw, url($url));
        xmlwriter_end_element($xw); // loc

        xmlwriter_start_element($xw, 'lastmod');
        xmlwriter_text($xw,$now);
        xmlwriter_end_element($xw); // lastmod

        xmlwriter_start_element($xw, 'priority');
        xmlwriter_text($xw, $priority);
        xmlwriter_end_element($xw); // priority
        xmlwriter_end_element($xw); // end url
    }

    private static function createLocElement($xw, $now, $url){
        xmlwriter_start_element($xw, 'sitemap'); // sitemap
            xmlwriter_start_element($xw, 'loc');
            xmlwriter_text($xw, url($url));
            xmlwriter_end_element($xw); // loc

            xmlwriter_start_element($xw, 'lastmod');
            xmlwriter_text($xw,$now);
            xmlwriter_end_element($xw); // lastmod
        xmlwriter_end_element($xw); // sitemap
    }

    private static function generateSiteMapLocale($data, $type = null){
        $xw = xmlwriter_open_memory();
        xmlwriter_set_indent($xw, 1);
        xmlwriter_start_document($xw, '1.0', 'UTF-8');
        // A first element
        xmlwriter_start_element($xw, 'urlset');

        // Attribute 'att1' for element 'urlset'
        xmlwriter_start_attribute($xw, 'xmlns');
        xmlwriter_text($xw, 'http://www.sitemaps.org/schemas/sitemap/0.9');
        xmlwriter_end_attribute($xw);

        $url = $type ? url("{$type}/") : url("/");
        self::createElement($xw, $data['now'], $url, "1");

        foreach ($data['categories'] as $category){
            $url = $type ? url("{$type}/category/{$category->id}/{$category->slug}") : url("category/{$category->id}/{$category->slug}") ;
            self::createElement($xw, $data['now'], $url, "0.7");
        }

        foreach ($data['products'] as $product){
            $url = $type ? url("{$type}/product/{$product->slug}") : url("product/{$product->slug}") ;
            self::createElement($xw, $data['now'], $url, "0.6");
        }

        xmlwriter_end_element($xw); // end urlset
        xmlwriter_end_document($xw);

        $fileName = $type ? "/sitemap{$type}.xml" : "/sitemapua.xml";
        file_put_contents($_SERVER['DOCUMENT_ROOT'].$fileName ,xmlwriter_output_memory($xw));
    }



    public static function generateSiteMap(){

        $data['now'] = Carbon::today()->toDateString();
        $data['products'] = Product::all();
        $data['categories'] = Category::all();

        self::generateSiteMapLocale($data);
        self::generateSiteMapLocale($data, 'ru');

        $siteMap = xmlwriter_open_memory();
        xmlwriter_set_indent($siteMap, 1);
        xmlwriter_start_document($siteMap, '1.0', 'UTF-8');
            // A first element
            xmlwriter_start_element($siteMap, 'sitemapindex');

                // Attribute 'att1' for element 'sitemapindex'
                xmlwriter_start_attribute($siteMap, 'xmlns');
                xmlwriter_text($siteMap, 'http://www.sitemaps.org/schemas/sitemap/0.9');
                xmlwriter_end_attribute($siteMap);
                self::createLocElement($siteMap, $data['now'], url('sitemapua.xml'));
                self::createLocElement($siteMap, $data['now'], url('sitemapru.xml'));

            xmlwriter_end_element($siteMap); // end urlset
        xmlwriter_end_document($siteMap);

        file_put_contents($_SERVER['DOCUMENT_ROOT'].'/sitemap.xml', xmlwriter_output_memory($siteMap));

        return "success";


    }

    public function filters ()
    {
        return view('admin.pages.filters',compact(''));
    }

    public function get_filters () {
        $models = FilterType::all();

        $response = [];

        foreach ($models as $model) {
            array_push($response, $model->attributesToArray() );
        }

        return  response()
            ->json($response);
    }

    public function store_filters (Request $request) {

        $model = new FilterType();
        $model->fill($request->only(['type', 'type_ru']));
        $model->save();

        return  response()
            ->json($model->attributesToArray());
    }

    public function delete_filters (Request $request) {

        $model = FilterType::findOrFail($request->get('id'));

        $model->delete();
        return  response()
            ->json(['status' => 200]);
    }



    public function get_filters_name ($id) {
        $models = FilterName::where('type_id', $id)->get();

        $response = [];

        foreach ($models as $model) {
            array_push($response, $model->attributesToArray() );
        }

        return  response()
            ->json($response);
    }


    public function store_filters_name (Request $request) {

        $model = new FilterName();
        $model->fill($request->only(['type_id','name', 'name_ru']));
        $model->save();

        return  response()
            ->json($model->attributesToArray());
    }

    public function delete_filters_name (Request $request) {

        $model = FilterName::findOrFail($request->get('id'));

        $model->delete();
        return  response()
            ->json(['status' => 200]);
    }

}