@extends('app')

<?php
//$d = [$categoryId, $categories[0]->slug];

$get_params = Request::all();
$get_params['showAll'] = 'showAll';
?>

@section('json-ld')
    @php $language = Lang::locale(); @endphp
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "ItemList"
            @if (count($products)  != 0)
            @php $counter = 1; @endphp
            ,"itemListElement":	[
               @foreach($products as $key => $product)
                @php $counter++; @endphp
                {
                   "@type": "ListItem",
                   "position": "{{$key+1}}",
                   "url": "{{ $language=='ua' ? url("product/{$product->slug}") : url("{$language}/product/{$product->slug}") }}"
                 }
                   @if (count($products) + 1 != $counter)
                    ,
                 @endif
            @endforeach
            ]
            @endif
        }
    </script>
@endsection

@section('content')
    <section class="contentbox container-fluid">
        <div class="row os-row">
            {{--INCLUDE STATIC NAV BAR --}}
            @include('partials.navFixSection')

            <div class="products">
                <div class="row pr_10">

                    <div class="col-xs-12">
                        <h1>{{App::getLocale() == 'ua' ? ($categories[0]->h1 ? : $categories[0]->category)  : ($categories[0]->h1_ru? : $categories[0]->category_ru)}}</h1>
                    </div>

                    @foreach($products as $product)

                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 ">
                            <div class="products__item">
                                <a href="{{ route('show.product', $product->slug) }}">
                                    <div class="img__handler" data-sale="{{ $product->reduced_price != 0 ? 'true' : 'false' }}">
                                        @if((time() - $product->created_at->timestamp) <= $shop_settings->time_new )
                                            <div class="new">
                                                <span>
                                                     {{$shop_settings->new}}
                                                </span>
                                            </div>
                                        @endif
                                        @if ($product->photos->count() === 0)
                                            <img data-src="/src/public/images/no-image-found.jpg"
                                                 src="/src/public/images/no-image-found.jpg"
                                                 class="img-responsive"
                                                 alt="No Image Found Tag"
                                                 title="No Image Found Tag"
                                            />
                                        @else
                                            @if ($product->featuredPhoto)
                                                <img class="img-responsive"
                                                     data-src="/{{ $product->featuredPhoto->thumbnail_path }}"
                                                     src="/{{ $product->featuredPhoto->thumbnail_path }}"
                                                     alt="{{ $product->product_sku }}"
                                                     title="{{ $product->product_sku }}"
                                                />
                                            @elseif(!$product->featuredPhoto)
                                                <img class="img-responsive"
                                                     data-src="/{{ $product->photos->first()->thumbnail_path}}"
                                                     src="/{{ $product->photos->first()->thumbnail_path}}"
                                                     alt="{{ $product->product_sku }}"
                                                     title="{{ $product->product_sku }}"
                                                />
                                            @else
                                                N/A
                                            @endif
                                        @endif

                                    </div>
                                    <div class="item__description">
                                        <p style="font-size: 14px; color: #777777;"> {{App::getLocale() == 'ua' ?
                                        $product->product_name  :
                                        $product->product_name_ru}} Venezia <span style="display: inline;
                                        font-size: 13px;">{{$product->product_sku
                                        }}</span></p>
                                    </div>
                                    <div class="related-products">
                                        {{--<p><strong>{{  trans("messages.available_colors") }}:</strong></p>--}}
                                        @forelse(\App\Product::related($products_by_category, $product) as $related_product)
                                            <a href="{{ route('show.product', $related_product->slug) }}">
                                                <img class="related"
                                                     src="/{{ $related_product->photos->first()->path }}"
                                                     alt="{{$related_product->product_sku}}"
                                                     title="{{$related_product->product_sku}}"
                                                />
                                            </a>
                                        @empty
                                            {{--<p>{{  trans("messages.absent_colors") }}</p>--}}
                                        @endforelse
                                    </div>

                                </a>
                                <div class="item__description">
                                    @if($product->reduced_price != 0)
                                        <div><span>{{$product->price}}грн</span>{{ $product->reduced_price }}грн</div>
                                    @else
                                        <span style="display: inline; font-size: 18px; color: #666666;
">{{$product->price}}</span> грн
                                    @endif
                                </div>
                            </div>
                        </div>

                    @endforeach

                    @if(!count($products))
                        <p>{{trans('messages.products_not_found')}}</p>
                    @endif
                </div>


                @if(!isset($_GET['showAll']) && $showAllButton)
                    <form action="" class="row paginator" style="margin-top: 30px;">
                        <a href="{{ route('category.showAll', [$categoryId, $categories[0]->slug]) . '?' . http_build_query($get_params) }}" class="btn btn-primary-outline">{{trans('messages.showAll')}}</a>
                    </form>
                @endif

                <div class="row paginator" style="margin-top: 30px;">
                    @if(!isset($bag))
                        <div class="pagination-wrapper">
                            {{--Pagination--}}
                            {!! $products->links() !!}
                        </div>
                    @endif
                </div>

                @if(!isset($_GET['page']))
                    <div class="row" style="margin-top: 30px;">
                        <div class="col-md-12">
                            {!!  App::getLocale() == 'ua' ? $categories[0]->description : $categories[0]->description_ru !!}
                        </div>

                    </div>
                @endif

            </div>
            {{--INCLUDE  FILTERS   SECTION --}}
            @include('partials.filterSectionCategory')
        </div>
    </section>


    <script  src="{{ asset('src/public/js/libs/jquery.js') }}"></script>
    <script>

        $(function() {
            $('.related-products .related').mouseover(function () {
                //console.log( $(this).attr("src") );
                //console.log ( $(this).parents('.products__item').find('.img-responsive').attr('src') );
                $(this).parents('.products__item').find('.img-responsive').attr('src',$(this).attr("src"));
            });

            $('.related-products .related').mouseleave(function () {
                var src = $(this).parents('.products__item').find('.img-responsive').attr('data-src');
                console.log($(this).parents('.products__item').find('.img-responsive'));
                $(this).parents('.products__item').find('.img-responsive').attr('src',src);
            });
        });
        $('.addToCart').click(function () {
            getSizes($(this).data('id'));
            // instanciate new modal
            var modal = new tingle.modal({
                footer: true,
                stickyFooter: false,
                cssClass: ['custom-class-1', 'custom-class-2'],
                onOpen: function () {
                },
                onClose: function () {
                }
            });

            // set content (our modal form, display  when user click "Confirm  order" )
            modal.setContent('<div class="col-md-12"><h1 class="heading">Додати в корзину:</h1></div>' +
                    '<form action="{{ route('addToCart') }}" method="POST" id="addToCartForm">' +
                    '{{ csrf_field() }}' +
                    '<input type="hidden" value="1" name="qty">' +
                    '<input type="hidden" value="' + $(this).data('id') + '" name="product">' +
                    '<div class="col-md-12" style="margin-top: 10px">' +
                    '<span id="empty_sizes"></span>' +
                    '<select id="size" name="size" data-id="' + $(this).data('id') + '"></select>' +
                    '</div>' +
                    '<div class="col-md-12" style="margin-top: 10px">' +
                    '<div class="attributes colors">' +
                    '<div id="colorsList"></div>' +
                    '</div>' +
                    '</div>' +
                    '</form>');

            // add a button
            modal.addFooterBtn('Додати в корзину', 'btn btn-primary-outline pull-right', function () {
                modal.close();
                $('#addToCartForm').submit();
            });



            @if (count($errors) > 0)
            modal.open();
            @endif
                modal.open();
        });
        function getSizes(product_id) {
            $.post('{{route('show.product.info')}}', {
                product_id: product_id,
                _token: '{{ csrf_token() }}',
            })
                    .done(function (response) {
                        var parsed = JSON.parse(response);
                        var languageMessage = '{{trans('messages.selectSize')}}';
                        if (parsed.length == 0) {
                            var options = '<p><b style="color: darkred">Товару немає на складі!</b></p>';
                            $('#size').remove();
                            $('#empty_sizes').html(options);
                        } else {
                            var options = '<option disabled selected>--' + languageMessage + '--</option>';
                            $.each(parsed, function (key, value) {
                                options += '<option value="' + value.size + '">' + value.size + '</option>';
                            })
                            $('#size').html(options);
                        }

                    })
                    .fail(function (error) {
                        console.log(error)
                    });
        }
        $('body').on('change', '#size', function () {
            getColors($(this).data('id'), $(this).val())
        })
        function getColors(product_id, size) {
            $.post('{{route('show.product.info')}}', {
                product_id: product_id,
                _token: '{{ csrf_token() }}',
                size: size
            })
                    .done(function (response) {
                        var items = '';
                        var parsed = JSON.parse(response);
                        $.each(parsed, function (key, value) {
                            items += '<div class="color__checkbox_item">';
                            items += '<input type="radio" name="color" id="color_' + key + '" value="' + value.color + '">';
                            items += '<label for="color_' + key + '"  style="background-color: ' + value.color + '"></label>';
                            items += '</div>';
                        })
                        $('#colorsList').html(items)
                    })
                    .fail(function (error) {
                        console.log(error)
                    });
        }

        var sale =  '<div class="sale"> <span>{{$shop_settings->sale}}</span> </div>';

        $( document ).ready(function() {

            $('.img__handler').each( function () {
                if($(this).attr('data-sale') == 'true'){
                    $(this).append(sale)
                }
            });
//            $('.item__description > h2').each( function () {
//                $(this).prepend('арт.:')
//            })

        });



    </script>

    @include('pages.partials.footer')

@endsection



