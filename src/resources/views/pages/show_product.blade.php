@extends('app')

@section('openGraphImage')
    {{ url( $product->photos->first()->path) }}
@endsection

@section('json-ld')
    @php $language = Lang::locale(); @endphp
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "Product",
            "description": " {{ $language=='ua' ?  preg_replace("/&#?[a-z0-9]{2,8};/i"," ",strip_tags($product->description))  :   preg_replace("/&#?[a-z0-9]{2,8};/i"," ",strip_tags($product->description_ru)) }} " ,
            "name": "{{ $language=='ua' ? $product->product_name  : $product->product_name_ru }}",
            "sku": "{{$product->product_sku}}",
            "brand": "VENEZIA",
            "url": "{{ $language=='ua' ? url("/") : url("{$language}/") }}",
            "image": "{{url($product->photos->first()->path)}}",
            "offers": {
                "@type": "Offer",
                "availability": "http://schema.org/InStock",
                "price": "{{$product->reduced_price != 0 ? $product->reduced_price : $product->price}}",
                "priceCurrency": "UAH",
                "url": "{{ $language=='ua' ? url("product/{$product->slug}") : url("{$language}/product/{$product->slug}") }}"
            }
            @if (count($reviews)  != 0)
                @php $counter = 0; @endphp
                ,"review":	[
                   @foreach($reviews as $key => $review)
                   {
                       "@type": "Review",
                       "author": {
                          "@type": "Person",
                          "name": "{{$review->name}}"
                        },
                       "datePublished": "{{$review->created_at}}",
                       "description": "{{$review->message}}"
                   }
                   @if (count($reviews) - 1  != $counter)
                      ,
                   @endif
                   @php $counter++; @endphp
                   @endforeach
                ],
                  "aggregateRating": {
                    "@type": "AggregateRating",
                    "ratingValue": "5",
                    "reviewCount": "{{count($reviews)}}"
                  }

            @endif
        }
</script>
@endsection

@section('content')

    {{--<div class="tingle-modal custom-class-1 custom-class-2 tingle-modal--visible" style="">--}}
        {{--<button class="tingle-modal__close">×</button><div class="tingle-modal-box">--}}
            {{--<div class="tingle-modal-box__content">--}}
                {{--<div class="col-md-12">--}}
                    {{--<h1 class="heading">Купити:</h1>--}}
                    {{--<form action="http://localhost:90/order/confirm" method="POST" id="one-click-form">--}}
                        {{--<div class="col-md-12">--}}
                            {{--<label class="text-muted" for="item_6">Номер телефону:</label>--}}
                        {{--</div>--}}
                        {{--<div class="col-md-12">--}}
                            {{--<input type="text" name="phone_number" required>--}}
                        {{--</div>--}}
                        {{--<div class="col-sm-8 col-sm-offset-2">--}}
                            {{--<img class="img-responsive"--}}
                                 {{--src="/{{ $product->photos->first()->path }}"--}}
                                 {{--id="full__img"--}}
                                 {{--alt="{{$product->product_sku}}"--}}
                                 {{--title="{{$product->product_sku}}"--}}
                            {{--/>--}}
                        {{--</div>--}}
                    {{--</form>--}}
                {{--</div>--}}
            {{--</div>--}}
            {{--<div class="tingle-modal-box__footer"><button class="btn btn-primary-outline pull-right">Купити</button></div>--}}
        {{--</div>--}}
    {{--</div>--}}

    <div class="fullscreen_img__popup" id="fullscreen_img__popup">
        <div class="overlay"></div>
        <div class="img">
            <div class="arrow" id="prev">
                <img src="/src/public/img/svg/arrow_left.svg"
                     alt="arrow left"
                     title="arrow left"
                />
            </div>
            <img src="/src/public/img/svg/close_white.svg"
                 id="close"
                 alt="close"
                 title="close"
            />
            <img src="https://e-venezia.pl/venezia/uploads/products_mini/1236_C_NE_J16__1.jpg"
                 id="fullscreen__img"
                 alt="{{$product->product_sku}}"
                 title="{{$product->product_sku}}"
            />
            <div class="arrow" id="next">
                <img src="/src/public/img/svg/arrow_right.svg"
                     alt="arrow right"
                     title="arrow right"
                />
            </div>
        </div>
    </div>


    <section class="contentbox container-fluid">
        <div class="row">
            <div style="width: 100%;display: flex;">
            {{--<form action="{{route("addToCart")}}" method="post" name="add_to_cart" style="width: 100%;display: flex;">--}}
                <div class="sidebar col-sm-hidden">
                    <ul class="sidebar__menu">
                        @include('partials.navFixSection')
                    </ul>
                </div>

                <div class="single_product" >
                    <div class="product__info">
                        <div class="img__preview">
                            <div class="product-title">
                                <h1>{{  Lang::locale()=='ua' ? $product->product_name  : $product->product_name_ru}} арт : {{$product->product_sku}}</h1>
                            </div>


                            <div class="product-image-wrapper">

                            @if ($product->photos->count() === 0)

                                <div class="thumbnails">
                                </div>
                                <div class="image">
                                    <img src="/src/public/img/data-zoom-in-icon.png" class="fullscreen" id="fullscreen" alt="zoom" title="zoom"/>
                                    <img src="/src/public/images/no-image-found.jpg" id="full__img" alt="no-image-found" title="no-image-found"/>
                                </div>


                                {{--<div style="text-align: center; " class="os-favorite">--}}
                                    {{--<a onclick="ShowFavoritePopupMessage()" class="add-to-favorite">--}}
                                        {{--<span>{{trans('messages.favorite')}}</span>--}}
                                    {{--</a>--}}
                                {{--</div>--}}

                                @else

                                <div class="thumbnails">
                                    <ul>
                                        @foreach ($product->photos as $key => $photo)
                                            <li @if($key == 0) class="active" @endif>
                                                <img  src="/{{ $photo->thumbnail_path }}"
                                                      data-full="/{{ $photo->path }}"
                                                      class="thumbnail"
                                                      alt="{{$product->product_sku}}"
                                                      title="{{$product->product_sku}}"
                                                />
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="image">
                                    <img src="/src/public/img/data-zoom-in-icon.png"
                                         class="fullscreen"
                                         id="fullscreen"
                                         alt="zoom"
                                         title="zoom"
                                    />
                                    <div class="image-justify">
                                        <div class="image-justify-wrapper">
                                            <img class="img-responsive"
                                                 itemprop="image"
                                                 src="/{{ $product->photos->first()->path }}"
                                                 id="full__img"
                                                 alt="{{$product->product_sku}}"
                                                 title="{{$product->product_sku}}"
                                            />
                                        </div>
                                    </div>
                                </div>
                                {{--<div style="text-align: center; "  class="os-favorite">--}}
                                    {{--<a onclick="ShowFavoritePopupMessage()" class="add-to-favorite">--}}
                                        {{--<span>{{trans('messages.favorite')}}</span>--}}
                                    {{--</a>--}}
                                {{--</div>--}}

                            @endif
                                
                            </div>

                            <div class="review-wrapper">
                                <div class="reviews">
                                    <form id="add-review" class="send-review-form">
                                        <input type="hidden" name="product_id" value="{{$product->id}}">
                                        <div class="form-group">
                                            <label for="usr">{{trans('messages.name')}}:</label>
                                            <input type="text" class="form-control" name="name">
                                        </div>
                                        <div class="form-group error name-error"></div>
                                        <div class="form-group">
                                            <label for="comment">{{trans('messages.message')}}:</label>
                                            <textarea class="form-control" rows="5" name="message" maxlength="1000"></textarea>
                                        </div>
                                        <div class="form-group error message-error"></div>
                                        <div class="form-group captcha">
                                            <label>{!! captcha_img()!!}<i class="fas fa-sync-alt"></i></label>
                                            <input type="text" class="form-control" name="captcha">
                                        </div>
                                        <div class="form-group error captcha-error"></div>
                                        <button type="submit" class="btn btn-default">{{trans('messages.send')}}</button>
                                    </form>
                                </div>

                                @if (count($reviews)  != 0)
                                    <div class="reviews-list">
                                        @foreach($reviews as $review)
                                            @include('pages.partials.review')
                                        @endforeach
                                    </div>
                                @endif

                            </div>

                            {{--<div style="text-align: center; "  class="os-favorite">--}}
                                {{--<a onclick="ShowFavoritePopupMessage()" class="add-to-favorite">--}}
                                    {{--<span>{{trans('messages.favorite')}}</span>--}}
                                {{--</a>--}}
                            {{--</div>--}}

                        </div>


                        <div class="info">

                            @if( count($productInfo) )

                                <form action="{{route("addToCart")}}" method="post" name="add_to_cart">
                                    {{--<h2>{{  Lang::locale()=='ua' ? $product->product_name  : $product->product_name_ru}}</h2>--}}
                                    {{--<h3>арт : {{$product->product_sku}}</h3>--}}
                                    <div>
                                        @if($product->reduced_price != 0)
                                            <div><span> {{$product->price}} ₴</span><br>{{ $product->reduced_price }} ₴</div>
                                        @else
                                            <span>{{$product->price}} </span> ₴
                                        @endif
                                    </div>
                                    <div class="related-products">
                                        <p><strong>{{  trans("messages.available_colors") }}:</strong></p>
                                        @forelse($related_products as $related_product)
                                            <a href="{{ route('show.product', $related_product->slug) }}">
                                                <img class="related"
                                                     src="/{{ $related_product->photos->first()->path }}"
                                                     alt="{{$related_product->product_sku}}"
                                                     title="{{$related_product->product_sku}}"
                                                />
                                            </a>
                                        @empty
                                            <p>{{  trans("messages.absent_colors") }}</p>
                                        @endforelse
                                    </div>
                                    {{--<div class="attributes" style="display: none;">--}}
                                    <?php
                                        $show_attributes = false;
                                        $show_attributes_colors = false;
                                        $max_quantity = 0;

                                        foreach($product->product_information as $info)
                                        {
                                            if ($info->size == 0 || $info->size == 1 )
                                            {
                                                $show_attributes_colors = true;
                                                $max_quantity = $info->quantity;

                                            }
                                            else
                                            {
                                                $show_attributes = true;
                                                $show_attributes_colors = true;
                                            }
                                        }

                                        if (count($product->product_information) == 1) {
                                            $max_quantity = $product->product_information [0]->quantity;
                                        }
                                    ?>

                                    <div class="attributes" style="{{ ( $show_attributes ) ? "" : "display: none;"}}">
                                        <strong>{{trans('messages.size')}}: </strong><br>
                                        <span id="empty_sizes"></span>
                                        {{--<select id="sizes" name="size"></select>--}}
                                        <div class="sizes-wrapper">
                                        @foreach($product->product_information as $info)
                                            <div class="size-items">
                                                <input type="radio" name="size" value="{{$info->size}}" id="size_{{$info->size}}"
                                                        {{ (count($product->product_information) == 1) ? "checked='checked'" : "" }}
                                                        data-quantity = {{ $info->quantity }}
                                                >
                                                <label for="size_{{$info->size}}">{{$info->size}}</label>
                                            </div>
                                        @endforeach
                                        </div>
                                    </div>
                                    {{--<div class="attributes colors" style="display: none;">--}}

                                    @if($show_attributes_colors)
                                        <div class="attributes colors">
                                            <strong>{{trans('messages.color')}}: </strong>
                                            <div id="colorsList">
                                                <div class="color__checkbox_item">
                                                        <input type="radio" name="color" id="color_0" value="{{  (count($productInfo)) ? $productInfo[0]->color : "" }}">
                                                    <label for="color_0" style="background-color: {{  (count($productInfo)) ? $productInfo[0]->color : "" }}"></label>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="buy-button-region">
                                        <div class="buttons">
                                            {!! csrf_field() !!}
                                            <input type="hidden" name="product" value="{{$product->id}}"/>

                                            <button class="btn btn-dark-outline" onClick="(function(){ ga('send', 'event', 'conversion', 'addToCart'); })();">
                                                {{trans('messages.inBasket')}}
                                            </button>
                                            <div class="quantity" >
                                                <input type="number" max="{{ ($max_quantity) ? : '10' }}" id="quantity" name="qty" min="1" value="1"/>
                                                <div class="btns">
                                                    <span id="plus">+</span>
                                                    <span id="minus">-</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <button type="button" class="one-click-btn">{{trans('messages.buy_in_one_click')}}</button>
                                    </div>
                                </form>

                            @else
                                <div>
                                    <div class="price">
                                        <div><span> {{$product->price}} ₴</span></div>
                                    </div>
                                    <b style="color: #dd5656;">{{ trans('messages.product_is_sold') }}</b>
                                </div>
                            @endif
                            <div class="attributes">
                                <a onclick="ShowPopupMessage()" class="size-table-link">
                                    <span>{{trans('messages.size_table')}}</span>
                                </a>
                            </div>

                            <strong>{{trans("messages.product_info_description")}}:</strong>
                            <p>

                                {!! Lang::locale()=='ua' ? $product->description  : $product->description_ru !!}

                            </p>

                            @if($product->product_material->material)
                                <div class="info-description">
                                    {!! Lang::locale()=='ua' ? $product->product_material->material  : $product->product_material->material_ru !!}
                                </div>
                            @endif

                            <div style="text-align: center; "  class="os-favorite">
                                <a onclick="ShowFavoritePopupMessage()" class="add-to-favorite">
                                    <span>{{trans('messages.favorite')}}</span>
                                </a>
                            </div>

                            <div class="review-wrapper review-wrapper-mobile">
                                <div class="reviews">
                                    <form id="add-review" class="send-review-form">
                                        <input type="hidden" name="product_id" value="{{$product->id}}">
                                        <div class="form-group">
                                            <label for="usr">{{trans('messages.name')}}:</label>
                                            <input type="text" class="form-control" name="name">
                                        </div>
                                        <div class="form-group error name-error"></div>
                                        <div class="form-group">
                                            <label for="comment">{{trans('messages.message')}}:</label>
                                            <textarea class="form-control" rows="5" name="message" maxlength="1000"></textarea>
                                        </div>
                                        <div class="form-group error message-error"></div>
                                        <div class="form-group captcha">
                                            <label>{!! captcha_img()!!}<i class="fas fa-sync-alt"></i></label>
                                            <input type="text" class="form-control" name="captcha">
                                        </div>
                                        <div class="form-group error captcha-error"></div>
                                        <button type="submit" class="btn btn-default">{{trans('messages.send')}}</button>
                                    </form>
                                </div>

                                @if (count($reviews)  != 0)
                                    <div class="reviews-list">
                                        @foreach($reviews as $review)
                                            @include('pages.partials.review')
                                        @endforeach
                                    </div>
                                @endif

                            </div>
                        </div>

                    </div>

                    <section class="tiles container-fluid">
                        <div class="heading">
                            <span>{{trans('messages.seen_products')}}</span>
                            <hr/>
                        </div>

                        {{--<div class="row">--}}
                            {{--<div class="col-md-12">--}}
                                <div class="featured__items">
                                    @foreach($seen_products_models as $seen_product)
                                        <div>
                                            <div class="item">
                                                <a href="{{ route('show.product', $seen_product->slug) }}">
                                                    <div class="img">
                                                        @if ($seen_product->photos->count() === 0)
                                                            <img src="src/public/images/no-image-found.jpg" alt="No Image Found Tag">
                                                        @else
                                                            @if ($seen_product->featuredPhoto)
                                                                <img class="img-responsive"
                                                                     src="/{{ $seen_product->featuredPhoto->thumbnail_path }}"
                                                                     alt="Photo ID: {{ $seen_product->featuredPhoto->id }}"/>
                                                            @elseif(!$seen_product->featuredPhoto)
                                                                <img class="img-responsive"
                                                                     src="/{{ $seen_product->photos->first()->thumbnail_path}}" alt="Photo"/>
                                                            @else
                                                                N/A
                                                            @endif
                                                        @endif
                                                        @if($seen_product->reduced_price == 0)
                                                            <span>{{ $seen_product->price }}₴</span>
                                                        @else
                                                            <span>{{ $seen_product->reduced_price }}₴</span>
                                                        @endif
                                                    </div>
                                                    <span>{{  Lang::locale()=='ua' ? $seen_product->product_name  : $seen_product->product_name_ru }}</span>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            {{--</div>--}}
                        {{--</div>--}}
                    </section>

                </div>

                <div class="popup-sizes-message" id="shoes_size_popup">
                </div>

                <div class="popup-sizes-message" id="favorite-popup">
                    <div class="title">
                        <span>{{trans('messages.favorite_popup')}}</span>

                    </div>
                    <div class="text-menu">
                        <div style="text-align: center; padding-top: 10px;">
                            <a class="popup-button-add-favorites" onclick="HideFavoritePopupMessage()">{{trans('messages.continue_to_buy')}} </a>
                            <a class="popup-button-add-favorites" href="{{url('favorites')}}">
                                <img style="vertical-align: middle;" src="/src/public/img/ico_fav.png" alt="heart" title="heart">
                                {{trans('messages.go_to_favorite')}}
                            </a>
                        </div>
                    </div>

                </div>

            {{--</form>--}}
            </div>
        </div>
        </div>
    </section>

    @include('pages.partials.footer')


    <script  src="{{ asset('src/public/js/libs/jquery.js') }}"></script>

    <script>


        function ShowFavoritePopupMessage() {

            $.ajax({
                method: "post",
                url: '{{route('addProductToFavorites')}}',
                data: {
                    _token: '{{ csrf_token() }}',
                    id : '{{ $product->id }}'
                }
            }).done(function () {
            });

            $('#favorite-popup').show(100);

        }

        function HideFavoritePopupMessage() {
            $('#favorite-popup').hide(100);
        }

        function ShowPopupMessage() {
            // make this ajax for seo optimization
            $.ajax({
                method: "get",
                url: '{{route('SizeInstruction')}}',
            }).success(function (response) {

                $( "#shoes_size_popup" ).empty();
                $('#shoes_size_popup').append(response)

                $('#shoes_size_popup').show(100);
                $('#womn-inf').hide();
                $('#man-inf').show();
                $('#info-man').addClass('active');
                $('#info-woman').removeClass('active')
            });
        }


    </script>

    <script>
        /**
         * RECOMMENDED CONFIGURATION VARIABLES: EDIT AND UNCOMMENT THE SECTION BELOW TO INSERT DYNAMIC VALUES FROM YOUR PLATFORM OR CMS.
         * LEARN WHY DEFINING THESE VARIABLES IS IMPORTANT: https://disqus.com/admin/universalcode/#configuration-variables
         */
        /*
         var disqus_config = function () {
         this.page.url = PAGE_URL; // Replace PAGE_URL with your page's canonical URL variable
         this.page.identifier = PAGE_IDENTIFIER; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
         };
         */
        (function () { // DON'T EDIT BELOW THIS LINE
            var d = document, s = d.createElement('script');

            //need  register our site domain in https://disqus.com/ and put code from site
            s.src = '//httpdavidtrushkovcomstore.disqus.com/embed.js';

            s.setAttribute('data-timestamp', +new Date());
            (d.head || d.body).appendChild(s);
        })();
    </script>
    <script>
        'use strict';
        $('.thumbnail').click(function () {
            let src = $(this).data('full');
            $(this).parents('ul').find('.active').removeClass('active');
            $(this).parent().addClass('active');
            $('#full__img').attr('src', src);
        });

        $('#plus').click(function () {
            let quantity = $('#quantity');
            let val = quantity.attr('value');
            let max = quantity.attr('max');

            if(Number(val) < Number(max) ){
                quantity.attr('value', parseInt(val) + 1);
            }else{
                sweetAlert('Даної кількості немає в наявності')
            }


        });
        $('#minus').click(function () {
            let quantity = $('#quantity');
            let val = quantity.attr('value');
            if (val <= 1) {
                quantity.attr('value', parseInt(val));
            } else {
                quantity.attr('value', parseInt(val) - 1);
            }
        });

        $(".captcha img").click(function () {
            $(".captcha img").attr('src','/captcha/default?'+Math.random());
        });

        $(".captcha .fa-sync-alt").click(function () {
            $(".captcha img").attr('src','/captcha/default?'+Math.random());
        });

        {{--$( "#add-review" ).submit(function( event ) {--}}
            {{--var product_id = $('#add-review [name=product_id]').val();--}}
            {{--var name = $('#add-review [name=name]').val();--}}
            {{--var message = $('#add-review [name=message]').val();--}}
            {{--var captcha = $('#add-review [name=captcha]').val();--}}
            {{--$(".message-error").hide();--}}
            {{--$(".name-error").hide();--}}
            {{--$(".captcha-error").hide();--}}
            {{--$.ajax({--}}
                {{--method: "POST",--}}
                {{--url: '{{route('review.store')}}',--}}
                {{--data: {--}}
                    {{--_token: '{{ csrf_token() }}',--}}
                    {{--product_id: product_id,--}}
                    {{--name: name,--}}
                    {{--message: message,--}}
                    {{--captcha: captcha--}}
                {{--},--}}
            {{--})--}}
                {{--.success(function (response) {--}}
                    {{--$('#add-review [name=name]').val("");--}}
                    {{--$('#add-review [name=message]').val("");--}}
                    {{--$('#add-review [name=captcha]').val("");--}}
                    {{--$('.reviews-list').prepend(response);--}}
                    {{--$(".captcha img").attr('src','/captcha/default?'+Math.random());--}}
                {{--})--}}
                {{--.error(function (response) {--}}
                    {{--console.log(response);--}}
                    {{--console.log(response.responseJSON);--}}
                    {{--var json = response.responseJSON;--}}
                    {{--if (json.hasOwnProperty('name')) {--}}
                        {{--var text = json.name.join(" " );--}}
                        {{--$(".name-error").html(text);--}}
                        {{--$(".name-error").show();--}}
                    {{--}--}}
                    {{--if (json.hasOwnProperty('message')) {--}}
                        {{--var text = json.message.join(" " );--}}
                        {{--$(".message-error").html(text);--}}
                        {{--$(".message-error").show();--}}
                    {{--}--}}
                    {{--if (json.hasOwnProperty('captcha')) {--}}
                        {{--var text = json.captcha.join(" " );--}}
                        {{--$(".captcha-error").html(text);--}}
                        {{--$(".captcha-error").show();--}}
                    {{--}--}}
                    {{--$(".captcha img").attr('src','/captcha/default?'+Math.random());--}}
                {{--});--}}
            {{--event.preventDefault();--}}
        {{--});--}}
    </script>

    <script>
        $(function () {
            {{--$.post('{{route('show.product.info')}}', {--}}
                {{--product_id: '{{ $product->id }}',--}}
                {{--_token: '{{ csrf_token() }}',--}}
            {{--})--}}
                    {{--.done(function (response) {--}}
                        {{--var parsed = JSON.parse(response);--}}

                        {{--console.log(parsed);--}}

                        {{--var languageMessage = '{{trans('messages.selectSize')}}';--}}
                        {{--if (parsed.length == 0) {--}}
                            {{--var options = '<p><b style="color: darkred">Товару немає на складі!</b></p>';--}}
                            {{--$('#sizes').remove();--}}
                            {{--$('#empty_sizes').html(options);--}}
                        {{--} else {--}}
                            {{--var options = '<option disabled selected>--' + languageMessage + '--</option>';--}}
                            {{--$.each(parsed, function (key, value) {--}}

                                {{--if (value.size == 0 || value.size == 1) {--}}
                                    {{--options = '<option value="' + value.size + '">' + value.size + '</option>';--}}
                                    {{--$(".attributes.colors").show();--}}
                                    {{--$.post('{{route('show.product.info')}}', {--}}
                                        {{--product_id: '{{ $product->id }}',--}}
                                        {{--_token: '{{ csrf_token() }}',--}}
                                        {{--size: value.size--}}
                                    {{--})--}}
                                        {{--.done(function (response) {--}}
                                            {{--var items = '';--}}
                                            {{--var parsed = JSON.parse(response);--}}
                                            {{--//get max quantity--}}
                                            {{--$('#quantity').attr('max',parsed[0].quantity) ;--}}
                                            {{--$('#quantity').attr('value',1) ;--}}


                                            {{--var colors = '<b>{{trans("messages.info_color")}}:</b>';--}}
                                            {{--$.each(parsed, function (key, value) {--}}
                                                {{--items += '<div class="color__checkbox_item">';--}}
                                                {{--items += '<input type="radio" name="color" id="color_' + key + '" value="' + value.color + '">';--}}
                                                {{--items += '<label for="color_' + key + '"  style="background-color: ' + value.color + '"></label>';--}}
                                                {{--items += '</div>';--}}

                                                {{--colors += '<label class="icon-color-item" style="background-color: ' + value.color + '"></label>';--}}
                                            {{--});--}}
                                            {{--$('#colorsList').html(items);--}}
                                            {{--$('#colors-r').html(colors);--}}
                                        {{--})--}}
                                        {{--.fail(function (error) {--}}
                                            {{--console.log(error)--}}
                                        {{--});--}}
                                    {{--//--}}
                                    {{--return false;--}}
                                {{--}--}}
                                {{--$(".attributes").show();--}}
                                {{--options += '<option value="' + value.size + '">' + value.size + '</option>';--}}
                            {{--});--}}
                            {{--$('#sizes').html(options);--}}
                        {{--}--}}

                    {{--})--}}
                    {{--.fail(function (error) {--}}
                        {{--console.log(error)--}}
                    {{--});--}}

            {{--$('#sizes').change(function () {--}}
                {{--$.post('{{route('show.product.info')}}', {--}}
                    {{--product_id: '{{ $product->id }}',--}}
                    {{--_token: '{{ csrf_token() }}',--}}
                    {{--size: $(this).val()--}}
                {{--})--}}
                        {{--.done(function (response) {--}}
                            {{--var items = '';--}}
                            {{--var parsed = JSON.parse(response);--}}
                            {{--//get max quantity--}}
                            {{--$('#quantity').attr('max',parsed[0].quantity) ;--}}
                            {{--$('#quantity').attr('value',1) ;--}}


                            {{--var colors = '<b>{{trans("messages.info_color")}}:</b>';--}}
                            {{--$.each(parsed, function (key, value) {--}}
                                {{--items += '<div class="color__checkbox_item">';--}}
                                {{--items += '<input type="radio" name="color" id="color_' + key + '" value="' + value.color + '">';--}}
                                {{--items += '<label for="color_' + key + '"  style="background-color: ' + value.color + '"></label>';--}}
                                {{--items += '</div>';--}}

                                {{--colors += '<label class="icon-color-item" style="background-color: ' + value.color + '"></label>';--}}
                            {{--});--}}
                            {{--$('#colorsList').html(items);--}}
                            {{--$('#colors-r').html(colors);--}}
                        {{--})--}}
                        {{--.fail(function (error) {--}}
                            {{--console.log(error)--}}
                        {{--});--}}
            {{--})--}}

            $('input[type=radio][name=size]').change(function() {
                var quantity = $(this).data('quantity');

                console.log(quantity);

                $('#quantity').attr('max',quantity) ;

                if ( $('#quantity').attr( 'value' ) > quantity ) {
                    $('#quantity').attr( 'value', quantity );
                }
            });

            $("#add-review").validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 2,
                        maxlength: 255
                    },
                    message: {
                        required: true,
                        minlength: 2,
                        maxlength: 1000
                    },
                    captcha: {
                        required: true
                    },
                },
                messages: {
                    name: {
                        required: "{{trans("messages.validation_required")}}",
                        minlength: "{{trans("messages.validation_min")}}",
                        maxlength: "{{trans("messages.validation_max")}}"
                    },
                    message: {
                        required: "{{trans("messages.validation_required")}}",
                        minlength: "{{trans("messages.validation_min")}}",
                        maxlength: "{{trans("messages.validation_max")}}"
                    },
                    captcha: {
                        required: "{{trans("messages.validation_required")}}"
                    },
                },
                errorPlacement: function(error, element) {
                    $('#add-review [name=name]').val("");
                    $('#add-review [name=message]').val("");
                    $('#add-review [name=captcha]').val("");
                    switch(element.attr("name")) {
                        case 'name':
                            error.appendTo( ".name-error" );
                            $(".name-error").show();
                            break;
                        case 'message':
                            error.appendTo( ".message-error" );
                            $(".message-error").show();
                            break;
                        case 'captcha':
                            error.appendTo( ".captcha-error" );
                            $(".captcha-error").show();
                            break;
                    }
                },
                submitHandler: function(form) {
                    var product_id = $('#add-review [name=product_id]').val();
                    var name = $('#add-review [name=name]').val();
                    var message = $('#add-review [name=message]').val();
                    var captcha = $('#add-review [name=captcha]').val();
                    $(".message-error").empty();
                    $(".name-error").empty();
                    $(".captcha-error").empty();
                    $.ajax({
                        method: "POST",
                        url: '{{route('review.store')}}',
                        data: {
                            _token: '{{ csrf_token() }}',
                            product_id: product_id,
                            name: name,
                            message: message,
                            captcha: captcha
                        },
                    })
                        .success(function (response) {
                            $('#add-review [name=name]').val("");
                            $('#add-review [name=message]').val("");
                            $('#add-review [name=captcha]').val("");
                            if ($('.reviews-list').length) {
                                $('.reviews-list').prepend(response);
                            } else {
                                $(".review-wrapper").append("<div class='reviews-list'></div>");
                                $('.reviews-list').prepend(response);
                            }
                            $(".captcha img").attr('src','/captcha/default?'+Math.random());
                        })
                        .error(function (response) {
                            console.log(response);
                            console.log(response.responseJSON);
                            var json = response.responseJSON;
                            if (json.hasOwnProperty('name')) {
                                var text = json.name.join(" " );
                                $(".name-error").html(text);
                                $(".name-error").show();
                            }
                            if (json.hasOwnProperty('message')) {
                                var text = json.message.join(" " );
                                $(".message-error").html(text);
                                $(".message-error").show();
                            }
                            if (json.hasOwnProperty('captcha')) {
                                var text = json.captcha.join(" " );
                                $(".captcha-error").html(text);
                                $(".captcha-error").show();
                            }
                            $(".captcha img").attr('src','/captcha/default?'+Math.random());
                        });
                    event.preventDefault();
                }
            });
        })
    </script>

    <script>
        var modal = new tingle.modal({
            footer: true,
            stickyFooter: false,
            cssClass: ['custom-class-1', 'custom-class-2'],
            onOpen: function () {
//            console.log('modal open');
            },
            onClose: function () {
//            console.log('modal closed');
            }
        });
        modal.setContent(`<div class="col-md-12">
                    <h1 class="heading">{{trans('messages.buy')}}:</h1>
                    <form action="{{ route('one_click_order') }}" method="POST"  id="one-click-form">
                        {{ csrf_field() }}
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="user_id" value="{{ ( Auth::user() ) ? Auth::user()->id : null }}">
                        <div class="col-md-12">
                            <label class="text-muted" for="phone_number_field">{{trans('messages.Phone_number')}}:</label>
                        </div>
                        <div class="col-md-12">
                            <input type="text" name="phone_number" id="phone_number_field" required>
                        </div>
                        <div class="col-sm-6 col-sm-offset-3">
                            <img class="img-responsive"
                                 src="/{{ $product->photos->first()->path }}"
                                 alt="{{$product->product_sku}}"
                                 title="{{$product->product_sku}}"
                            />
                        </div>
                    </form>
                </div>`);

        // add a button
        modal.addFooterBtn('{{trans('messages.buy')}}', 'btn btn-primary-outline pull-right', function () {
            var phone_number = $('#phone_number_field').val();

            console.log(phone_number);

            if (phone_number.length > 3) {
                (function () {
                    ga('send', 'event', 'conversion', 'buy');
                })();
                $('#one-click-form').submit();
            }
        });

        $('.one-click-btn').click(function () {
            modal.open();
            $('#phone_number_field').focus();
        });
    </script>
@stop