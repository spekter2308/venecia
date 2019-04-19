@extends('app')

@section('content')

    <link rel="stylesheet" href="{{ asset('src/public/css/tingle.css') }}">
{{--    <script  src="{{ asset('src/public/js/libs/tingle.js') }}"></script>--}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tingle/0.8.4/tingle.min.js"></script>


    <section class="contentbox container-fluid">
        <div class="row">
            {{--INCLUDE STATIC NAV BAR --}}
            @include('partials.navFixSection')

            <div class="products">
                <div class="row pr_10">

            @if($products != null)
                @foreach($products as $product)
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 " data-id="{{$product->id}}">
                        <div class="products__item" >
                            <a href="{{ route('show.product', $product->id) }}">
                                <div class="img__handler">
                                    @if((time() - $product->created_at->timestamp) <= $shop_settings->time_new )
                                        <div class="new">
                                                    <span>
                                                         {{$shop_settings->new}}
                                                    </span>
                                        </div>
                                    @endif
                                    @if($product->reduced_price != 0)
                                        <div class="sale">
                                                    <span>
                                                         {{$shop_settings->sale}}
                                                    </span>

                                        </div>
                                    @endif
                                    @if ($product->photos->count() === 0)
                                        <img src="/src/public/images/no-image-found.jpg"
                                             class="img-responsive" alt="No Image Found Tag"/>
                                    @else
                                        @if ($product->featuredPhoto)
                                            <img class="img-responsive"
                                                 src="/{{ $product->featuredPhoto->thumbnail_path }}"
                                                 alt="Photo ID: {{ $product->featuredPhoto->id }}"/>
                                        @elseif(!$product->featuredPhoto)
                                            <img class="img-responsive"
                                                 src="/{{ $product->photos->first()->thumbnail_path}}" alt="Photo"/>
                                        @else
                                            N/A
                                        @endif
                                    @endif

                                </div>
                            </a>
                            <div class="item__description">
                                <a href="#">
                                    <h2>арт.: {{$product->product_sku }}</h2>
                                </a>

                                <p>
                                    @if($product->reduced_price != 0)
                                        <span>  {{$product->price}} ₴</span>
                                        {{  $product->reduced_price }} ₴</p>
                                @else
                                    {{$product->price}} ₴</p>
                                @endif
                                <a href="#" class="delete_button" data-id="{{$product->id}}">{{trans('messages.deleteFavorites')}}</a>
                            </div>

                        </div>

                    </div>

                @endforeach
            @endif
                 </div>
            </div>
        </div>


    </section>

    @include('pages.partials.footer')


@stop

@section('footer')
    <script>
        $('.item__description').on( "click", "a.delete_button", function() {
            var product_id = $(this).attr('data-id');

            $.ajax({
                method: "POST",
                url: '{{route("deleteProductToFavorites")}}',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: product_id,
                },
            }) .done(function (){
                $('[data-id='+product_id+']').remove();
            });
        });
    </script>


@endsection
