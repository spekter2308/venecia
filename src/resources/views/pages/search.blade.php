@extends('app')

@section('content')

    <section class="contentbox container-fluid">
        <div class="row">
            <div class="sidebar col-sm-hidden">
                <ul class="sidebar__menu">

                </ul>
            </div>
            <div class="products">
                <div class="row pr_10">
                    @foreach($search as $product)
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <div class="products__item">
                                <form action="/cart/add" method="post" name="add_to_cart">
                                </form>
                                <a href="{{ route('show.product', $product->slug) }}">
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
                                                 class="img-responsive"  alt="No Image Found Tag"/>
                                        @else
                                            @if ($product->featuredPhoto)
                                                <img  class="img-responsive"  src="/{{ $product->featuredPhoto->thumbnail_path }}"
                                                      alt="Photo ID: {{ $product->featuredPhoto->id }}"/>
                                            @elseif(!$product->featuredPhoto)
                                                <img  class="img-responsive"  src="/{{ $product->photos->first()->thumbnail_path}}" alt="Photo"/>
                                            @else
                                                N/A
                                            @endif
                                        @endif

                                    </div>
                                </a>
                                <div class="item__description">
                                    <a href="#"><h2>арт.: {{$product->product_sku}}</h2></a>

                                    <p>
                                        @if($product->reduced_price != 0)
                                            <span>  {{$product->price}} ₴</span>
                                            {{  $product->reduced_price }} ₴</p>
                                    @else
                                        {{$product->price}} ₴</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="row paginator" style="margin-top: 30px;">
                    <div class="pagination-wrapper">
                        {{--Pagination--}}
                        {!! $search->appends(['search' => $query])->links() !!}
                    </div>
                </div>
            </div>
            <div id="overlay_filters"></div>
            <a href="#" id="open_filters__btn">Filters</a>

        </div>
    </section>
    <script  src="{{ asset('src/public/js/libs/jquery.js') }}"></script>
    <script>
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
    </script>




    @include('pages.partials.footer')




@endsection
