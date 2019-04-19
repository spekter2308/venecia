@extends('app')

@section('content')


    <section class="contentbox container-fluid">
        <div class="row os-row">
            {{--INCLUDE STATIC NAV BAR --}}
            @include('partials.navFixSection')

                <div class="col-sm-12 col-xs-12 col-md-10 col-lg-10">
                    <div class="row">
                        <div class="col-md-4 col-xs-12 col-sm-12">
                            <div class="profile">
                                <h4>{{Auth::user()->username}}</h4>
                                {{--<img src="{{asset('src/public/img/svg/si-glyph-mail-inbox.svg')}}"/>--}}
                                <p>
                                    <i class="glyphicon"></i>{{Auth::user()->email}}
                                </p>
                                <!-- Split button -->
                                <div class="btn-group">
                                    <a href="{{ url('/logout') }}" type="button" class="btn btn-primary">
                                        {{trans('messages.logOut')}}</a>
                                </div>
                            </div>
                        </div>
                    </div>


            <div class="row">
                <div class="col-sm-12 col-xs-12 col-md-12 col-lg-12">
                    <section id="no-more-tables">
                        @if ($orders->count() == 0)
                            {{trans('messages.noOrders')}}
                        @else
                            <table>
                                <thead>
                                    <tr>
                                        <th>
                                            {{trans('messages.PIB')}}
                                        </th>
                                        <th>
                                            {{trans('messages.Phone_number')}}
                                        </th>
                                        <th>
                                            {{trans('messages.town')}}
                                        </th>
                                        <th>
                                            {{trans('messages.Department_new_mail')}}
                                        </th>
                                        <th>
                                            Артикул
                                        </th>
                                        <th>
                                            {{trans('messages.total_price')}}
                                        </th>
                                        <th>
                                            Статус
                                        </th>
                                        <th>

                                        </th>

                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($orders as $order)
                                    @if($order->state!= null)
                                        <tr class="success">
                                    @else
                                        <tr>
                                            @endif

                                            <td data-title="{{trans('messages.PIB')}}">
                                                {{$order->full_name}}
                                            </td>
                                            <td data-title="{{trans('messages.Phone_number')}}">
                                                {{$order->phone}}
                                            </td>
                                            <td data-title="{{trans('messages.town')}}">
                                                @php
                                                    $city = 'city_'.Lang::locale();
                                                @endphp
                                                {{$order->city->$city}}
                                            </td>
                                            <td data-title="{{trans('messages.Department_new_mail')}}">
                                                {{$order->postDepartment}}
                                            </td>
                                            <td data-title="Артикул">
                                                @php
                                                    try{
                                                    echo $order->product->product_sku;
                                                    }
                                                    catch(Exception $ex) {
                                                    echo "Товар відсутній!";
                                                    }
                                                @endphp
                                            </td>
                                            <td data-title="{{trans('messages.total_price')}}">
                                                {{$order->total_price}}
                                            </td>
                                            <td data-title="Статус">
                                                @if($order->status)
                                                    <span class="approved">{{trans('messages.approved')}}</span>
                                                @else
                                                    <span class="notApproved">{{trans('messages.notApproved')}}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{url('profile',$order->id)}}">{{trans('messages.readMore')}}</a>
                                            </td>

                                        </tr>
                                        @endforeach
                                </tbody>
                            </table>

                        @endif
                    </section>
                </div>
            </div>



            </div>
        </div>

    </section>

@endsection