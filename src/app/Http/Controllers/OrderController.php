<?php
namespace App\Http\Controllers;
use App\Cart;
use App\Cities;
use App\OneClickOrder;
use App\Order;
use App\OrderProduct;
use DB;
use Illuminate\Support\Facades\Lang;
use Validator;
use App\Product;
use Stripe\Stripe;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Routing\Controller as BaseController;
use App\Http\Traits\BrandAllTrait;
use App\Http\Traits\CategoryTrait;
use App\Http\Traits\SearchTrait;
use App\Http\Traits\CartTrait;

class OrderController extends Controller
{
    use BrandAllTrait, CategoryTrait, SearchTrait, CartTrait;
    /**
     * Show products in Order view
     * @return mixed
     */
    public function index()
    {

        // From Traits/SearchTrait.php
        // Enables capabilities search to be preformed on this view )
        $search = $this->search();

        // From Traits/CategoryTrait.php
        // ( Show Categories in side-nav )
        $categories = $this->categoryAll();

        // Get brands to display left nav-bar
        $brands = $this->BrandsAll();

        // From Traits/CartTrait.php
        // ( Count how many items in Cart for signed in user )
        $cart_count = $this->countProductsInCart();

        // Set the $user_id the the currently authenticated user
        $user_id = Auth::user()->id;

        // Count the items in a signed in users shopping cart
        $check_cart = Cart::with('products')->where('user_id', '=', $user_id)->count();

        // Count all the products in a cart  with the currently signed in user
        $count = Cart::where('user_id', '=', $user_id)->count();

        // If there are no items in users shopping cart, redirect them back to cart
        // page so they cant access checkout view with no items
        if (!$check_cart) {
            return redirect()->route('cart');
        }

        // Set $cart_books to the member ID, along with the products.
        // ( "products" is coming from the Products() method in the Product.php Model )
        $cart_products = Cart::with('products')->where('user_id',  $user_id)->get();

        // Set $cart_products to the total in the Cart for that user_id to check and see if the cart is empty
        $cart_total = Cart::with('products')->where('user_id',  $user_id)->sum('total');

        return view('cart.checkout', compact('search', 'categories', 'brands', 'cart_count', 'count'))
            ->with('cart_products', $cart_products)
            ->with('cart_total', $cart_total);
    }


    /**
     * Make the order when user enters all credentials
     *
     * @param Request $request
     * @return mixed
     */
    public function postOrder(Request $request)
    {

        // Validate each form field
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|max:30|min:2',
            'last_name' => 'required|max:30|min:2',
            'address' => 'required|max:50|min:4',
            'address_2' => 'max:50|min:4',
            'city' => 'required|max:50|min:3',
            'state' => 'required|',
            'zip' => 'required|max:11|min:4',
            'full_name' => 'required|max:30|min:2',
        ]);


        // If error occurs, display it
        if ($validator->fails()) {
            return redirect('/checkout')
                ->withErrors($validator)
                ->withInput();
        }

        // Set your secret key: remember to change this to your live secret key in production
        Stripe::setApiKey('YOUR STRIPE SECRET KEY');

        // Set Inputs to the the form fields so we can store them in DB
        $first_name = Input::get('first_name');
        $last_name = Input::get('last_name');
        $address = Input::get('address');
        $address_2 = Input::get('address_2');
        $city = Input::get('city');
        $state = Input::get('state');
        $zip = Input::get('zip');
        $full_name = Input::get('full_name');

        // Set $user_id to the currently authenticated user
        $user_id = Auth::user()->id;

        // Set $cart_products to the Cart Model with its products where
        // the user_id = to the current signed in user ID
        $cart_products = Cart::with('products')->where('user_id', '=', $user_id)->get();

        // Set $cart_total to the Cart Model alond with all its products, and
        // where the user_id = the current signed in user ID, and
        // also get the sum of the total field.
        $cart_total = Cart::with('products')->where('user_id', '=', $user_id)->sum('total');

        //  Get the total, and set the charge amount
        $charge_amount = number_format($cart_total, 2) * 100;

        // Create the charge on Stripe's servers - this will charge the user's card
        try {
            $charge = \Stripe\Charge::create(array(
                'source' => $request->input('stripeToken'),
                'amount' => $charge_amount, // amount in cents, again
                'currency' => 'usd',
            ));

        } catch (\Stripe\Error\Card $e) {
            // The card has been declined
            echo $e;
        }


        // Create the order in DB, and assign each variable to the correct form fields
        $order = Order::create(
            array(
                'user_id' => $user_id,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'address' => $address,
                'address_2' => $address_2,
                'city' => $city,
                'state' => $state,
                'zip' => $zip,
                'total' => $cart_total,
                'full_name' => $full_name,
            ));

        // Attach all cart items to the pivot table with their fields
        foreach ($cart_products as $order_products) {
            $order->orderItems()->attach($order_products->product_id, array(
                'qty' => $order_products->qty,
                'price' => $order_products->products->price,
                'reduced_price' => $order_products->products->reduced_price,
                'total' => $order_products->products->price * $order_products->qty,
                'total_reduced' => $order_products->products->reduced_price * $order_products->qty,

            ));

        }


        // Decrement the product quantity in the products table by how many a user bought of a certain product.
        DB::table('products')->decrement('product_qty', $order_products->qty);


        // Delete all the items in the cart after transaction successful
        Cart::where('user_id', '=', $user_id)->delete();

        // Then return redirect back with success message
        flash()->success('Success', 'Your order was processed successfully.');

        return redirect()->route('cart');


    }

    /**
     * Make the order when user enters all data !
     *
     * @param Request $request
     */
    public function confirmOrder(Request $request)
    {

        // Validate each form field
        $validator = Validator::make($request->all(), [
            'fio' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'city' => 'required',
            'mailDepartment' => 'required',
            'payment_method' => 'required'
        ]);
        // If error occurs, display it
        if ($validator->fails()) {
            return redirect('/cart')
                ->withErrors($validator)
                ->withInput();
        }


        //Get products and qty from cart user
        $products = json_decode($request->qtys, true);
        //Send logic into CarTrait
        $orders = $this->confirmOrders($request->all(), $products);
        $order_id  = $orders[0]['order'];
        // Set $user_id to the currently authenticated user
        $user_id = Auth::id();
        $session_id = \Session::getId();
        // clean cart
        Cart::where('session_id',$session_id)->delete();

        // Then return redirect back with success message
        flash()->overlay('Успіх', 'Ваше замовлення №' . $order_id . ' прийнято !', "success");

        session()->flash('order_id', $order_id);

        return redirect()->route('cart');
    }

    /**
     * Check user code discount
     *
     * @param Request $request
     */
    public function checkDiscount(Request $request)
    {
        //get info about discount in our shop_settings
        $discount = DB::table('shop_settings')->first();
        //check if our code  equals user promocode
        $userCode = htmlspecialchars($request->promocode);
        if ($discount->code == $userCode) {
            //start session with success promocode
            session(['CorrectDiscountCode' => $discount->discount]);
            return response('Success code', 200);
        } else {
            return response('Fail code', 422);
        }
    }

    /**
     * Set Status in orders_product !
     */
    public function setStatus($id)
    {
        $order = OrderProduct::find($id);

        $quantity = DB::table('product_information')->where('product_id',$order->product_id)->first();

//        if($quantity->quantity - $order->product_qty  == 0){
//            DB::table('product_information')->where('product_id',$order->product_id)->where('size',$order->size)->delete();
//        }else if($quantity->quantity - $order->product_qty  > 0) {
//            DB::table('product_information')->where('product_id',$order->product_id)
//                ->update([
//                    'quantity' => $quantity->quantity - $order->product_qty
//                ]);
//        }else{
//            flash()->error('Error', 'Неодостатня кількость товару на складі');
//            return redirect()->back();
//        }
        $this->addOrderToCsv($order);



//        $informationToSend = $order;
//        $color_name = DB::table('colors_table')->where('color' ,$informationToSend->color)->value('name');
//        dd(array(
//            $informationToSend->product->code,
//            $informationToSend->email,
//            $informationToSend->product->product_sku,
//            $informationToSend->full_name,
//            $informationToSend->phone,
//            $informationToSend->city->city_ua,
//            $informationToSend->postDepartment,
//            $informationToSend->product_qty,
//            $informationToSend->price,
//            $informationToSend->total_price,
//            $informationToSend->size,
//            $color_name,
//            $informationToSend->discount,
//            $informationToSend->order,
//            $informationToSend->created_at,
//        ));


        //Status 1 - confirm order
        OrderProduct::where('id' , $id )->update(['status' => '1']);
        // Then redirect back.
        return redirect()->back();


    }

    /**
     * delete order in orders_product !
     */
    public function deleteOrder($id)
    {
        $order = OrderProduct::find($id);
        $order->delete();
        // Then redirect back.
        return redirect()->back();
    }

    public function oneClickOrder (Request $request) {
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|max:255',
            'product_id' => 'required',
        ]);

        if ($validator->fails()) {
            flash()->error(trans("messages.error"), trans("messages.error"));
            return back();
        }

        //dd($request->all());

        $model = new OneClickOrder();
        $model->fill($request->only(['phone_number', 'product_id', 'user_id']));

        if ( empty($model->user_id)) {
            $model->user_id = null;
        }

        $model->save();


        flash()->overlay(trans('messages.success'), trans('messages.order_accepted'), "success");
        return back();
    }

    public function oneClickOrderDelete ($id ) {
        $one_click_order = OneClickOrder::findOrFail($id);
        $one_click_order->delete();

        return back();
    }

    public function addOrder ($id) {
        $one_click_order = OneClickOrder::with('user', 'product.product_information', 'product.photos')->findOrFail($id);

        $veriable = 'city_'.Lang::locale();;
        $cities = Cities::select('id',$veriable.' as city')->get();

//        dd($one_click_order->product);

        return view('admin.pages.add-order', compact('one_click_order','cities'));
    }

    public function storeOrder (Request $request, $id) {
        DB::beginTransaction();

        try {

            $one_click_order = OneClickOrder::with('user', 'product.product_information', 'product.photos')->findOrFail($id);
            $data = [
                'full_name' => 'required|max:255',
                'payment_method' => 'required|max:255',
                'product_qty' => 'required|min:1',
                'city_id' => 'required',
                'postDepartment' => 'required',
                'size' => 'required',
            ];
            if (!empty($request->get('email')))
            {
                $data["email"] = "email";
            }

            $validator = Validator::make($request->all(), $data);

            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $order = new OrderProduct();

            $number_order = DB::table('order_products')->max('order');

            $number_order++ ;

            $order->fill($request->only(['full_name','payment_method','product_qty','city_id','postDepartment','email','size']));

            $order->user_id = ($one_click_order->user_id) ? $one_click_order->user_id : null;
            $order->product_id = $one_click_order->product_id;
            $order->state = null;
            $order->status = 0;
            $order->order = $number_order;
            $order->color = $one_click_order->product->product_information[0]->color;
            $order->phone = $one_click_order->phone_number;
            $order->price = $one_click_order->product->discount ? $one_click_order->product->reduced_price : $one_click_order->product->price;
            $order->discount = $one_click_order->product->discount ? $one_click_order->product->discount : 0;
            $order->total_price = number_format($order->price * $order->product_qty, 2, '.', '');

            //dd($order);

            $order->save();

            $one_click_order->delete();

            DB::commit();

            flash()->success('Успіх', 'Замовлення оброблено!');

            return redirect()->route('admin.pages.index');
            // all good
        } catch (\Exception $e) {
            DB::rollback();

            flash()->error("Помилка", "Сталася помилка");

            return redirect()->route('admin.pages.index');

        }


        //dd($request->get('email'));
        //dd($request->all());
    }
}