<section class="tiles container-fluid">
    <div class="heading">
        <span>{{trans('messages.featured')}}</span>
        <hr/>
    </div>

    <div class="container featured__items">
        @foreach($products as $product)
            <div>
                <div class="item">
                    <a href="{{ route('show.product', $product->slug) }}">
                        <div class="img">
                            @if ($product->photos->count() === 0)
                                <img src="src/public/images/no-image-found.jpg" alt="No Image Found Tag">
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
                            @if($product->reduced_price == 0)
                                <span>{{ $product->price }}₴</span>
                            @else
                                <span>{{ $product->reduced_price }}₴</span>
                            @endif
                        </div>
                   <span>{{  Lang::locale()=='ua' ? $product->product_name  : $product->product_name_ru }}</span>
                    </a>

                    {{--<form action="{{route('addToCart')}}" method="post" name="add_to_cart">--}}
                        {{--{!! csrf_field() !!}--}}
                        {{--<input type="hidden" name="product" value="{{$product->id}}"/>--}}
                        {{--<input type="hidden" name="qty" value="1"/>--}}
                        {{--<button class="btn btn-primary-outline">В КОШИК</button>--}}
                    {{--</form>--}}
                </div>
            </div>
        @endforeach
    </div>
</section>


{{--<section class="promo">--}}
    {{--<h2>Heading of the promo</h2>--}}

    {{--<p>description of the promo here... blah blah blah... atata atata..</p>--}}
    {{--<a href="#" class="btn btn-primary-outline">Show me</a>--}}

{{--</section>--}}


<!--  For Mobile -->
{{--<div class="col-sm-6 col-md-3 animated zoomIn" id="featured-container-m">--}}
{{--<a href="{{ route('show.product', $product->product_name) }}">--}}
{{--<div class="col-xs-12">--}}
{{--<div id="featured-product-name-container">--}}
{{--<h6 class="center-on-small-only" id="featured-product-name">--}}
{{--<br>{{ $product->product_name }}</h6>--}}
{{--</div>--}}
{{--</div>--}}
{{--<div class="col-xs-6">--}}
{{--@if ($product->photos->count() === 0)--}}
{{--<img src="/store/src/public/images/no-image-found.jpg" alt="No Image Found Tag"--}}
{{--style="width: 200px; height: 200px;" id="image-m">--}}
{{--@else--}}
{{--@if ($product->featuredPhoto)--}}
{{--<img src="/store/{{ $product->featuredPhoto->thumbnail_path }}"--}}
{{--alt="Photo ID: {{ $product->featuredPhoto->id }}"--}}
{{--class="img-responsive img-thumbnail" id="image-m"/>--}}
{{--@elseif(!$product->featuredPhoto)--}}
{{--<img src="/store/{{ $product->photos->first()->thumbnail_path}}" alt="Photo"--}}
{{--class="img-responsive img-thumbnail" id="image-m"/>--}}
{{--@else--}}
{{--N/A--}}
{{--@endif--}}
{{--@endif--}}
{{--</div>--}}
{{--<div class="col-xs-6">--}}
{{--@if($product->reduced_price == 0)--}}
{{--<div class="light-300 black-text medium-500" id="Product_Reduced-Price">--}}
{{--$ {{  $product->price }}</div>--}}
{{--@else--}}
{{--<div class="green-text medium-500" id="Product_Reduced-Price">--}}
{{--$ {{ $product->reduced_price }}</div>--}}
{{--@endif--}}
{{--</div>--}}
{{--</a>--}}

{{--<form action="/store/cart/add" method="post" name="add_to_cart">--}}
{{--{!! csrf_field() !!}--}}
{{--<input type="hidden" name="product" value="{{$product->id}}"/>--}}
{{--<input type="hidden" name="qty" value="1"/>--}}
{{--<button class="btn btn-default waves-effect waves-light">ADD TO CART</button>--}}
{{--</form>--}}
{{--</div>--}}