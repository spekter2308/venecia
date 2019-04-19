<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Cities;
use App\Order;
use App\OrderProduct;
use DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\URL;
use Validator;
use App\Product;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Routing\Controller as BaseController;
use App\novaPoshta\src\Delivery\NovaPoshtaApi2;
use App\Http\Traits\BrandAllTrait;
use App\Http\Traits\CategoryTrait;
use App\Http\Traits\SearchTrait;
use App\Http\Traits\CartTrait;
use Illuminate\Http\Request;

class CartController extends Controller {

    use BrandAllTrait, CategoryTrait, SearchTrait, CartTrait;


    public $lang;

    /**
     * CartController constructor.
     */
    public function __construct() {
        //$this->middleware('auth');
        // Reference the main constructor.
        $this->lang = Lang::locale();

        parent::__construct();
    }
    /**
     * Return the Cart page with the cart items and total
     * 
     * @return mixed
     */
    public function showCart() {

//        // Set the $user_id the the currently authenticated user
//        $user_id = Auth::user()->id;

        $orders = null;

        if (session('order_id')) {
            $order_id = session('order_id');
            $orders = OrderProduct::with('product')->where(['order' => $order_id, 'status' => 0 ])->get();
            session()->forget('order_id');
        }

        $session_id = \Session::getId();
        
        // Set $cart_books to the member ID, along with the products.
        // ( "products" is coming from the Products() method in the Product.php Model )
        $cart_products = Cart::with('products.product_information')->where('session_id',  $session_id)->get();

        //dd ($cart_products[0]->size, $cart_products[0]->products->product_information->groupBy('size')[$cart_products[0]->size][0]->quantity);
        //dd($cart_products);

        // Set $cart_products to the total in the Cart for that user_id to check and see if the cart is empty
//        $cart_total = Cart::with('books')->where('user_id', $user_id)->sum('total');
        $cart_total = Cart::where('session_id', $session_id)->sum('total');

        // From Traits/SearchTrait.php
        // Enables capabilities search to be preformed on this view )
        $search = $this->search();

        $categoryAll  = $this->categoryAll();

        // Get brands to display left nav-bar
        $brands = $this->BrandsAll();

        // Count all the products in a cart  with the currently signed in user
        $count = Cart::where('session_id', $session_id)->count();

        // From Traits/CartTrait.php
        // ( Count how many items in Cart for signed in user )
        $cart_count = $this->countProductsInCart();

        //Need to main navbar(need fix)
        $brandsAll = $this->brandsAll();

        //get discount from DB table shop_settings
        $discount = DB::table('shop_settings')->first();

        $breadcrumbs = [
            (object) ['page' => trans('messages.mainPage'), "link" => url('/')],
            (object) ['page' => trans('messages.cart'), "link" => url('/cart')]
        ];

        $veriable = 'city_'.$this->lang;
        $cities = Cities::select('id',$veriable.' as city')->get();

        // Return the cart with products, and total amount in cart
        return view('cart.cart', compact('search', 'breadcrumbs', 'brands', 'count', 'cart_count','brandsAll','categoryAll','discount','cities','orders'))
            ->with('cart_products', $cart_products)
            ->with('cart_total', $cart_total);
    }


    /**
     * Add Products to the cart
     * 
     * @return mixed
     */
    public function addCart()
    {
        $product_size = Input::get('size');

        $product_qty = Input::get('qty');
        // Assign validation rules
        $rules = array(
            'qty' => 'required',
            'product'   => 'required',
            'size'=>'required'
        );
//        // Apply validation
        $validator = Validator::make(Input::all(), $rules);
//        // If validation fails, show error message
        if ($validator->fails()) {
            flash()->error(trans("messages.error"), trans("messages.pls_select_size"));
            return redirect()->back();
        }





        // Set $user_id to the currently signed in user ID
        $user_id = Auth::id();

        $session_id = \Session::getId();




        // Set $product_id to the hidden product input field in the add to cart from
        $product_id = Input::get('product');
        // Set the $qty to the quantity of products selected


        $product_color = DB::table('product_information')
            ->where('product_id' , $product_id)
            ->where('size' , $product_size)
            ->first();


        // set total to quantity * the product price
        // $total = $qty * $product->price;
//
//        if ($product->reduced_price == 0) {
//            $total = $qty * $product->price;
//        } else {
//            $total = $qty * $product->reduced_price;
//        }

        $existing_product = Cart::where([
            'session_id'    => $session_id,
            'product_id' => $product_id,
            'color'        => $product_color->color,
            'size'        => $product_size,

        ])->first();

        if ( $existing_product ) {
            $existing_product->qty += $product_qty;
            $existing_product->save();
        }
        else {
            // Create the Cart
            Cart::create(
                array(
                    'user_id' => $user_id,
                    'session_id' => $session_id,
                    'product_id' => $product_id,
                    'qty' => $product_qty,
                    'color' => $product_color->color,
                    'size' => $product_size,

                )
            );
        }
        // then redirect back
        return redirect()->route('cart');

    }


    /**
     * Update the Cart
     * 
     * @return mixed
     */
    public function update() {
        
        // Set $user_id to the currently signed in user ID
        $user_id = Auth::id();
        $session_id = \Session::getId();

        // Set the $qty to the quantity of products selected
        $qty = Input::get('qty');

        // Set $product_id to the hidden product input field in the update cart from
        $product_id = Input::get('product');

        // Set $cart_id to the hidden cart_id input field in the update cart from
        $cart_id = Input::get('cart_id');
        
        // Find the ID of the products in the Cart
        $product = Product::find($product_id);

        if ($product->reduced_price == 0) {
            $total = $qty * $product->price;
        } else {
            $total = $qty * $product->reduced_price;
        }

        // Select ALL from cart where the user ID = to the current logged in user, product_id = the current product ID being updated, and the cart_id = to the cartId being updated
        $cart = Cart::where('session_id', $session_id)->where('product_id', $product_id)->where('id', $cart_id);

        // Update your cart
        $cart->update(array(
            'user_id'    => $user_id,
            'session_id'    => $session_id,
            'product_id' => $product_id,
            'qty'        => $qty,
            'total'      => $total
        ));

        return redirect()->route('cart');
    }
    

    /**
     * Delete a product from a users Cart
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($id) {
        // Find the Carts table and given ID, and delete the record
        Cart::find($id)->delete();
        // Then redirect back
        return redirect()->back();
    }


    public function refresh (Request $request) {

        $cart_id = $request->get('cart_id');

        $cart = Cart::has('Products.product_information')->with('Products.product_information')->where("id",$cart_id)->first();

        $quantity = $request->get('quantity');

        if ($cart && $quantity)
        {
            $max_quantity = $cart->Products->product_information->groupBy('size')->has($cart->size) ? $cart->Products->product_information->groupBy('size') [$cart->size][0]->quantity : 0;


            if ( $quantity <= $max_quantity )
            {
                Cart::where("id",$cart_id)->update(['qty' => $quantity]);

                return response()->json([
                    "status" => true,
                    "message" => ""
                ]);
            }
            else
            {
                return response()->json([
                    "status" => false,
                    "message" => "Даної кількості немає в наявності"
                ]);
            }
        }
        else
        {
            return response()->json([
                "status" => false,
                "message" => "Помилка"
            ]);
        }
    }
    
    
}