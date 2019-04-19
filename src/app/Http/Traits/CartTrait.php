<?php
namespace App\Http\Traits;

use App\Cart;
use App\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Excel,File,Config;
use DB;
use Mail;

trait CartTrait {



    public function countProductsInCart() {
//        if (Auth::check()) {
//            // Grt the currently signed in user ID to count how many items in their cart
//            $user_id = Auth::user()->id;
//            // Count ho man items in cart for signed in user
//            return $cart_count = Cart::where('user_id', '=', $user_id)->count();
//        }
        $session_id = \Session::getId();
        return Cart::where('session_id', '=', $session_id)->count();
    }

    /**
     * Insert data to DB when user  confirm order(from Order controller)
     */

    public function confirmOrders($orderInformation,$products){


        //configuration data from order
        $data = $this->collectInformationToSend($products ,$orderInformation);

        //Send notification mail to payment Manager when user confirm order
        /* $mailAddress = env('PAYMENT_NOTIFICATION_MAIL');

         Mail::send('emails.emailPaymentNotification', ['mail' => $mailAddress,'data' => $data], function ($message) use ($mailAddress,$data)
         {
             $message->from('venezia@shop.com', 'venezia@shop.com');
             $message->to($mailAddress)->subject('Інформація про замовлення');
         });*/

        return $data;
    }

    public function collectInformationToSend($information ,$orderInformation){

        // Set $user_id to the currently authenticated user
        $user_id = Auth::id();
        //get user_email to notification email(who buy it )
        //$user_mail = Auth::user()->email;

        //check user code discount
        $discountStatus = session('CorrectDiscountCode');

        $number_order = DB::table('order_products')->max('order');

        $number_order++ ;

        //Information to email notification
        $data = [];
        foreach ($information as $productInfo) {
            $product = Product::find($productInfo['id']);
            $productFromCart = Cart::where('product_id',  $productInfo['id'])->first();
//            $productSize = $productFromCart->size;
            $productSize = $productInfo['size'];
            $productColor = $productFromCart->color;
            $fullName = $orderInformation['fio'];
            $email = $orderInformation['email'];
            $payment_method = $orderInformation['payment_method'];
            $phone =  $orderInformation['phone'];
            $city =  $orderInformation['city'];
            $postDepartment =  $orderInformation['mailDepartment'];
            $productId = $productInfo['id'];
            $productQty = $productInfo['qty'];
            //if reduce price sets
            if($product->reduced_price!='0.00'){
                $price = $product->reduced_price;
                $totalPrice = $productQty * $price;
            }else{
                $price = $product->price;
                $totalPrice = $productQty * $product->price;
            }
            //Check if user have discount status and calculate discount
            if($discountStatus!= null){
                $discount = (float)$discountStatus;
                $totalPrice = ($totalPrice / 100) *(100 - $discount);
            }else {
                $discount = 0;
            }
            //insert data  in  db order_products
            DB::table('order_products')->insert(
                [
                    'user_id' => $user_id,
                    'full_name' => $fullName,
                    'email' => $email,
                    'phone' => $phone,
                    'payment_method' => $payment_method,
                    'city_id' => $city,
                    'postDepartment' => $postDepartment,
                    'product_id' => $productId,
                    'product_qty' => $productQty,
                    'size' => $productSize,
                    'color' => $productColor,
                    'price' => $price,
                    'total_price' => $totalPrice,
                    'discount' => $discount,
                    'order' => $number_order
                ]
            );
            //Collect information to send
            $informationToSend = [];
            //$informationToSend['userEmail'] = $user_mail;
            $informationToSend['productName'] = $product->product_name;
            $informationToSend['fullName'] = $fullName;
            $informationToSend['email'] = $email;
            $informationToSend['phone'] = $phone;
            $informationToSend['city'] = $city;
            $informationToSend['postDepartment'] = $postDepartment;
            $informationToSend['productQty'] = $productQty;
            $informationToSend['price'] = $price;
            $informationToSend['totalPrice'] = $totalPrice;
            $informationToSend['size'] = $productSize;
            $informationToSend['color'] = $productColor;
            $informationToSend['discount'] = $discount;
            $informationToSend['order'] = $number_order;


//            $this->addOrderToCsv($informationToSend,$product);

            //Insert information to final collection data
            array_push($data, $informationToSend);
        }
        return $data;
    }

    public function addOrderToCsv($informationToSend){

        $color_name = DB::table('colors_table')->where('color' ,$informationToSend->color)->value('name');

            if(!File::exists(storage_path('exports') . '/orders.csv')) {

                Excel::create('orders', function ($excel) use ($informationToSend,$color_name) {

                    $excel->sheet('Sheet 1', function ($sheet) use ($informationToSend,$color_name) {
                        $sheet->fromArray(array(
                            'code','userEmail', 'art' , 'fullName' ,'phone', 'city' , 'postDepartment' ,'productQty' ,'price' ,'totalPrice' , 'size' , 'color', 'discount','order','date'
                        ));
                        $sheet->appendRow();
                        $sheet->rows(array(
                            array(
                                $informationToSend->product->code,
                                $informationToSend->email,
                                $informationToSend->product->product_sku,
                                $informationToSend->full_name,
                                $informationToSend->phone,
                                $informationToSend->city->city_ua,
                                $informationToSend->postDepartment,
                                $informationToSend->product_qty,
                                $informationToSend->price,
                                $informationToSend->total_price,
                                $informationToSend->size,
                                $color_name,
                                $informationToSend->discount,
                                $informationToSend->order,
                                $informationToSend->created_at,
                            )
                        ));
                    });
                })->store('csv');
            }else{
                $list = array(
                    '"' .$informationToSend->product->code . '"',
                    '"' .$informationToSend->email . '"',
                    '"' .$informationToSend->product->product_sku . '"',
                    '"' .$informationToSend->full_name . '"',
                    '"' .$informationToSend->phone . '"',
                    '"' .$informationToSend->city->city_ua . '"',
                    '"' .$informationToSend->postDepartment . '"',
                    '"' .$informationToSend->product_qty . '"',
                    '"' .$informationToSend->price . '"',
                    '"' .$informationToSend->total_price . '"',
                    '"' .$informationToSend->size . '"',
                    '"' .$color_name . '"',
                    '"' .$informationToSend->discount . '"',
                    '"' .$informationToSend->order . '"',
                    '"' .$informationToSend->created_at . '"',
                );

                $fp = fopen(storage_path('exports') . '/orders.csv', 'a');
                //fputcsv($fp, $list, '^', '"');
                fputs($fp, implode($list, '^')."\n");
                fclose($fp);
            }
    }



}