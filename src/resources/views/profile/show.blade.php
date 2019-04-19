@extends('app')

@section('content')


    <section class="contentbox container-fluid">
        <div class="row os-row">
            {{--INCLUDE STATIC NAV BAR --}}
            @include('partials.navFixSection')

            @php
                $language = Lang::locale();
                $city = 'city_'.$language;

            @endphp

            <div class="row">
                <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6">
                    <div class="order-details-box">{{trans('messages.PIB')}} :  {{$order->full_name}} </div>
                    <div class="order-details-box">{{trans('messages.Phone_number')}} :  {{$order->phone}} </div>
                    <div class="order-details-box">{{trans('messages.town')}} :  {{$order->city->$city}} &nbsp; {{ $order->postDepartment }} </div>
                    <div class="order-details-box">{{trans('messages.dis')}} :  {{$order->discount}} % </div>
                    <div class="order-details-box">{{trans('messages.size')}} :  {{$order->size}}     </div>
                    <div class="order-details-box">{{trans('messages.count')}} : {{$order->product_qty}}  <div class="color_order"  style="background-color:{{$order->color}}"></div></div>

                </div>

                <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6" style="margin-bottom: 20px">
                    <div class="order-details-box">Назва продукту : {{$language = 'ru' ? $order->product->product_name_ru : $order->product->product_name}} &nbsp; Артикул : {{$order->product->product_sku}}</div>
                    <div class="product-image">
                        <img class="profile-response"
                             src="/{{ $order->product->photos->first()->path }}"
                             alt="Photo ID: {{ $order->product->featuredPhoto->id }}"/>
                    </div>
                </div>
                <div class="col-sm-12 col-xs-12 col-md-12 col-lg-12">
                    <div class="order-details-box" style="text-align: right">{{trans('messages.total_price')}} : {{$order->total_price}} </div>
                </div>

            </div>



            </div>
        </div>

    </section>

@endsection