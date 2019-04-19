@if (count($cart_products) == 0)
    {{--NEED FIX AND ADD STYLE --}}

<div class="empty_cart">
    <h2>{{trans('messages.empty_cart')}}</h2>
    <a href="{{ url('/') }}" class="btn btn-primary-outline">Перейти до покупок</a>
</div>

@else
    <section class="contentbox container-fluid">
        <div class="container cart__container">
            <div class="cart">


            @foreach($cart_products as $key => $cart_item)

                    <div class="cart__item">
                        <div class="thumbnail">
                            <a href="{{ route('show.product', $cart_item->products->id) }}">

                                @if ($cart_item->products->photos->count() === 0)
                                    <img src="/src/public/images/no-image-found.jpg" alt="No Image Found Tag"
                                         id="Product-similar-Image">

                                @else

                                    @if ($cart_item->featuredPhoto)

                                        <img src="/{{ $cart_item->featuredPhoto->thumbnail_path }}"
                                             alt="Photo ID: {{ $cart_item->featuredPhoto->id }}"
                                        />
                                    @elseif(!$cart_item->featuredPhoto)

                                        <img src="/{{ $cart_item->products->photos->first()->thumbnail_path}}"
                                             alt="Photo"/>

                                    @else
                                        N/A
                                    @endif
                                @endif
                            </a>
                        </div>
                        <div class="info">
                            <h2>
                                <a href="{{ route('show.product', $cart_item->products->slug) }}">  {{Lang::locale()=='ua' ? $cart_item->products->product_name  : $cart_item->products->product_name_ru  }} {{$cart_item->products->product_sku}}</a>
                                <a href="{{URL::route('delete_book_from_cart', array($cart_item->id))}}"
                                   class="pull-right">
                                    <i class="fa fa-times" aria-hidden="true" style="color: darkred;"></i>
                                </a>
                            </h2>
                            <div class="product__attributes">
                                <div class="attributes">
                                    @if($cart_item->products->reduced_price!=0)
                                    <span class="price" id="price_{{$key}}"
                                          data-price="{{$cart_item->products->reduced_price}}"> {{$cart_item->products->reduced_price}}
                                        ₴</span>
                                    @else
                                        <span class="price" id="price_{{$key}}"
                                              data-price="{{$cart_item->products->price}}"> {{$cart_item->products->price}}
                                            ₴</span>
                                    @endif
                                    <span class="size">{{$cart_item->size}}</span>
                                        <div class="color" style="background-color: {{$cart_item->color}}"></div>

                                </div>
                                <div class="quantity">
                                    <div>
                                        <?php
                                        $item_quantity = 0;

                                        if ( $cart_item->products->product_information->groupBy('size')->has($cart_item->size) )
                                        {
                                            $item_quantity = $cart_item->products->product_information->groupBy('size')[$cart_item->size][0]->quantity;
                                        }

                                        ?>
                                        <a href="#" class="plus" data-id="qty_item_{{$key}}"
                                           data-max = "{{ $item_quantity }}"
                                           data-cart-id = "{{ $cart_item->id }}"
                                        >
                                            <img src="/src/public/img/svg/plus-black-symbol.svg" alt="plus"/>
                                        </a>
                                        <input type="number" class="qty" id="qty_item_{{$key}}"
                                               data-number="{{$key}}"
                                               data-id="{{$cart_item->product_id}}"
                                               data-size="{{$cart_item->size}}"
                                               value="{{( $cart_item->qty < $item_quantity ) ? $cart_item->qty : $item_quantity }}"
                                        />
                                        <a href="#" class="minus"
                                           data-id="qty_item_{{$key}}"
                                           data-cart-id = "{{ $cart_item->id }}"
                                        >
                                            <img
                                                    src="/src/public/img/svg/minus-black-symbol.svg"
                                                    alt="minus"/></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="total__price">

                            @if($cart_item->products->reduced_price!=0)
                                TOTAL: <span id="total_{{$key}}" data-price="{{$cart_item->products->reduced_price}}">{{$cart_item->products->reduced_price}}
                                    ₴</span>
                            @else
                                TOTAL: <span id="total_{{$key}}" data-price="{{$cart_item->products->price}}">{{$cart_item->products->price}}
                                    ₴</span>
                            @endif

                        </div>
                    </div>
                @endforeach
            </div>
            <button class="btn btn-secondary" id="submit" onClick="(function(){ ga('send', 'event', 'conversion', 'Order'); })();">
                {{trans('messages.showAll')}} (0)
            </button>
            <div class="back_to_shopping">
                <a href="{{ url('/') }}" class="btn btn-primary-outline">Перейти до покупок</a>
            </div>
        </div>
    </section>

@endif


<script  src="{{ asset('src/public/js/libs/jquery.js') }}"></script>
<script>
    'use strict';
    $(function () {
        equalHeight($(window).width());
        check_totals();
        calc_summ();
    });
    $(window).resize(function () {
        equalHeight($(window).width());
    });

    //quantity +/-
    $('.plus').click(function () {
        let id = $(this).data('id');
        let item = $('#' + id);
        let val = item.val();
        let cart_id = $(this).data('cart-id');

        let max = $(this).data('max');

        if(Number(val) + 1  > Number(max) ){
            sweetAlert('Даної кількості немає в наявності');
            return;
        }

        $.ajax({
            method: "POST",
            url: '{{ route('cart.refresh') }}',
            data: {
                _token: '{{ csrf_token() }}',
                quantity: (parseInt(val) + 1),
                cart_id: cart_id
            },
            dataType: 'json'
        }) .success(function (response){
            console.log(response.status);
            if (response.status) {
                item.val(parseInt(val) + 1).trigger('change');
                check_totals();
                calc_summ();
                return false;
            } else {
                sweetAlert(response.message);
            }
        })
        .fail(function() {
            sweetAlert('Помилка');
        })
        .error(function() {
            sweetAlert('Помилка');
        });
    });

    $('.minus').click(function () {
        let id = $(this).data('id');
        let item = $('#' + id);
        let val = item.val();
        let cart_id = $(this).data('cart-id');

        if (val <= 1) {
            return false;
        } else {

            $.ajax({
                method: "POST",
                url: '{{ route('cart.refresh') }}',
                data: {
                    _token: '{{ csrf_token() }}',
                    quantity: (parseInt(val) - 1),
                    cart_id: cart_id
                },
                dataType: 'json'
            }) .success(function (response){
                console.log(response.status);
                if (response.status) {
                    item.val(parseInt(val) - 1).trigger('change');
                    check_totals();
                    calc_summ();
                    return false;
                } else {
                    sweetAlert(response.message);
                }
            })
                .fail(function() {
                    sweetAlert('Помилка');
                })
                .error(function() {
                    sweetAlert('Помилка');
                });
        }
    });
    function equalHeight(width) {
        if (width !== null && width > 500) {
            $('.thumbnail').each(function () {
                let height = $(this).height();
                $(this).parent().find('.info').css('height', height);
            });
        } else {
            $('.thumbnail').each(function () {
                $(this).parent().find('.info').css('height', 'auto');
            });
        }
    }


    $('.qty').change(function () {
        let id = $(this).data('number');
        let total = parseInt($('#price_' + id).data('price')) * $(this).val();
        $('#total_' + id).html(total + '₴');
        calc_summ();
    });

    function check_totals() {
        $('input').each(function () {
            let id = $(this).data('number');
            let total = parseInt($('#price_' + id).data('price')) * $(this).val();
            $('#total_' + id).html(total + '₴').data('price', total);
        });
    }
    function calc_summ() {
        let summ = 0;
        $('.total__price').each(function () {
            summ = summ + parseInt($(this).find('span').data('price'));
        });
        $('#submit').html('Оформити замовлення (' + summ + '₴)');
        return summ;
    }
    function getQTYs() {
        let data = [];
        $('.qty').each(function () {
            data.push({
                id: $(this).data('id'),
                size: $(this).data('size'),
                qty: $(this).val()
            })
        });
        return JSON.stringify(data);;
    }
</script>


{{--MODAL WINDOW WITH CONFIRN ORDER --}}
<script>

    // instanciate new modal
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

    // set content (our modal form, display  when user click "Confirm  order" )
    modal.setContent('<div class="col-md-12"><h1 class="heading">Оформити замовлення:</h1>' +

            @php
                $discountSession = session('CorrectDiscountCode');
            @endphp

            @if($discountSession!=null)
            '<p class="discount">У вас діє знижка: '+'{{$discountSession}}'+' %</p></div>' +
            @endif
            '<form  action="{{ route('orderConfirm') }}" method="POST" id="money_form">' +
           '{{ csrf_field() }}'+

            '<div class="col-md-12">' +
            '<label class="text-muted" for="item_6">{{trans('messages.PIB')}}:</label>'+
            @if ($errors->has('fio'))
              '<div class="error">{{$errors->first('fio')}}</div>'+
            @endif

            '</div>' +
            '<div class="col-md-12">' +
            '<input type="text" name="fio" id="item_6" value="{{ old('fio') }}">' +
            '</div>' +

            '<div class="col-md-12">' +
            '<label class="text-muted" for="item_6">Email:</label>'+
                @if ($errors->has('email'))
                    '<div class="error">{{$errors->first('email')}}</div>'+
                @endif

                    '</div>' +
            '<div class="col-md-12">' +
            '<input type="text" name="email" id="item_6" value="{{ old('email') }}">' +
            '</div>' +

            '<div class="col-md-12">' +
            '<label class="text-muted" for="item_2" >{{trans('messages.Phone_number')}}::</label>' +
            @if ($errors->has('phone'))
                    '<div class="error">{{$errors->first('phone')}}</div>'+
            @endif
            '</div>' +
            '<div class="col-md-12">' +
            '<input type="text" name="phone" id="item_2" value="{{ old('phone') }}">' +
            '</div>' +

            '<div class="col-md-12">' +
            '<label class="text-muted" for="item_4">{{trans('messages.town')}}:</label>' +
            @if ($errors->has('city'))
                '<div class="error">{{$errors->first('city')}}</div>'+
            @endif
            '</div>' +
            '<div class="col-md-12">' +
            '<select class="order_form_select" id="city" type="text" name="city" style="width: 100%"  value="{{ old('city') }}">'+
            @foreach($cities as $city)
                 '<option value="{{$city->id}}">{{$city->city}}</option>'+
            @endforeach
            '</select>' +
            '</div>' +
            '<span id="departments"></span>'+
            '<div class="col-md-12">' +
            '<label class="text-muted" for="code" >{{trans('messages.discount_code')}}:</label>' +
            '</div>' +
            '<div class="col-md-12">' +
            '<input type="text" name="promocode" placeholder="{{trans('messages.Optional_field')}}" id="code">' +
            '</div>' +

            '<div class="col-md-12">'+
            '<label class="text-muted">{{trans('messages.payment_method')}}::</label>'+
            @if ($errors->has('payment_method'))
                '<div class="error">{{$errors->first('payment_method')}}</div>'+
            @endif
            '</div>'+
            '<div class="col-md-12">'+
            '<select name="payment_method">'+
            '<option selected disabled>{{trans('messages.select_payment_method')}}</option>'+
            '<option value="cash_payment_method">{{trans('messages.cash_payment_method')}}</option>'+
            '<option value="prepayment_payment_method">{{trans('messages.prepayment_payment_method')}}</option>'+
            '</select>'+
            '</div>'+
            '<input type="hidden" name="totalPrice" value="'+ calc_summ() +'">' +
            "<input type='hidden' name='qtys' id='qty_hidden_input' value='"+ getQTYs() +"'>" +
            '</form>');

    // add a button
    modal.addFooterBtn('Оформити замовлення', 'btn btn-primary-outline pull-right', function () {
        // here goes some logic
//        modal.close();
        (function(){ ga('send', 'event', 'conversion', 'buy'); })();
        $('#money_form').submit();
    });

    // open modal
    //    modal.open();
    //    modal.show();


    //    // close modal
    //    modal.close();

    @if (count($errors) > 0)
    modal.open();
    @endif

        $('#submit').click(function () {
       $('#qty_hidden_input').val(getQTYs());
        modal.open();
    });

    $('#code').focusout(function () {
        $.post("{{ route('checkDiscount') }}", {
            _token: '{{ csrf_token() }}',
            promocode: $(this).val()
        })
                .done(function (response) {
                    swal('Вдалих покупок', 'Ви ввели правильный код!', "success")
                })
                .fail(function (error) {
                    swal('Помилка', 'Неправельний код!', 'error');
                })
    });





    $(document).ready(function() {
        $('.order_form_select').select2({
            language: {
                noResults: function () {
                    return '{{trans('messages.noResult')}}';
                }
            }
        })
            .on('change', function() {
                var city = $('#select2-city-container').text();
                $.ajax({
                    method: "POST",
                    url: '{{route("getDepartments")}}',
                    data: {
                        _token: '{{ csrf_token() }}',
                        city: city
                    },
                    dataType: 'json'
                }) .success(function (response){
                    $('#departments').children().remove();
                    $('#departments').append(
                        '<div class="col-md-12">' +
                            '<label class="text-muted" for="code" >{{trans('messages.Department_new_mail')}}:</label>'+
                        '</div>'+
                        '<div class="col-md-12">' +
                            '<select class="order_form_department_select" name="mailDepartment" ></select>' +
                        '</div>'
                    );

                    if(response.language == 'ru'){
                        response.Departments.forEach(function(data) {
                            $('select[name=mailDepartment]').append('<option value="' + data.DescriptionRu + '">' + data.DescriptionRu + "</option>");
                        });
                    }else{
                        response.Departments.forEach(function(data) {
                            $('select[name=mailDepartment]').append('<option value="' + data.Description + '">' + data.Description + "</option>");
                        });
                    }
                    $('.order_form_department_select').select2({
                        language: {
                            noResults: function () {
                                return '{{trans('messages.noResult')}}';
                            }
                        }
                    })
                });

            });
    });
</script>

