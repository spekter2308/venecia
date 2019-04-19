@extends('app')

@section('content')
    {{--INCLUDE SECTION WITH  MODAL CONTENT--}}
    <link rel="stylesheet" href="{{ asset('src/public/css/tingle.css') }}">
    {{--<script  src="{{ asset('src/public/js/libs/tingle.js') }}"></script>--}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tingle/0.8.4/tingle.min.js"></script>
    {{--END SECTION--}}



    @include('cart.partials.cart_table')


    @include('pages.partials.footer')


    @if($orders)

    <?php

    // Function to return the JavaScript representation of a TransactionData object.
    function getTransactionJs(&$order) {
        $total_revenue = $order->price * $order->product_qty;

        return <<<HTML
ga('ecommerce:addTransaction', {
  'id': '{$order->order}',
  'revenue': '{$total_revenue}'
});
HTML;
    }
    // Function to return the JavaScript representation of an ItemData object.
    function getItemJs(&$order) {
        return <<<HTML
ga('ecommerce:addItem', {
  'id': '{$order->order}',
  'name': '{$order->product->product_name} {$order->product->product_sku}',
  'sku': '{$order->product->product_sku}',
  'category': '{$order->product->category[0]->category}',
  'price': '{$order->price}',
  'quantity': '{$order->product_qty}',
  'currency': 'UAH'
});
HTML;
    }?>

    <script>
        ga('require', 'ecommerce');

        <?php

        $trans = $orders[0];

        //dd($trans);

        echo getTransactionJs($trans);

        foreach ($orders as &$order) {
          echo getItemJs($order);
        }
        ?>

        ga('ecommerce:send');
    </script>

    @endif


@stop

