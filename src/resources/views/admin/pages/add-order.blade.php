@extends('admin.dash')

@section('content')

    <div class="container" id="admin-category-container">

        <br><br>
        <a href="#menu-toggle" class="btn btn-default" id="menu-toggle"><i class="fa fa-bars fa-5x"></i></a>
        <a href="{{ route('admin.pages.index') }}" class="btn btn-danger">Назад</a>
        <br><br>

        <div class="col-sm-8 col-md-9" id="admin-category-container">
            <ul class="collection with-header">
                <form role="form" method="POST" action="{{ route('admin_pages_store_order', ['id' => $one_click_order->id]) }}">
                    {{ csrf_field() }}

                    <input type="hidden" name="user_id" value="{{ $one_click_order->user_id }}">
                    <input type="hidden" name="product_id" value="{{ $one_click_order->product_id }}">
                    <input type="hidden" name="state">
                    <input type="hidden" name="status" value="0">

                    <li class="collection-item blue">
                        <h4 class="white-text text-center">
                            Додати замовлення
                        </h4>
                    </li>

                    <li class="collection-item">
                        <label for="category">Товар</label>
                        <div class="form-group">
                            <input type="text" class="form-control" value="{{ $one_click_order->product->product_name }} {{ $one_click_order->product->product_sku }}" readonly>
                        </div>
                    </li>

                    <li class="collection-item">
                        <label for="category">Розмір</label>
                        <div class="form-group{{ $errors->has('size') ? ' has-error' : '' }}">
                            <select id="size" name="size" class="form-control">
                                <option {{ !old('size') ? 'selected' : ""}} disabled>Виберіть розмір</option>
                                @foreach($one_click_order->product->product_information as $product_information)
                                    <option value="{{ $product_information->size }}" {{ old('size') ==  $product_information->size ? 'selected' : ""}} data-quantity="{{ $product_information->quantity }}">{{ $product_information->size }}</option>
                                @endforeach
                            </select>

                            @if($errors->has('size'))
                                <span class="help-block">{{ $errors->first('size') }}</span>
                            @endif
                        </div>
                    </li>

                    <li class="collection-item">
                        <label for="category">Колір</label>
                        <div class="form-group{{ $errors->has('color') ? ' has-error' : '' }}">
                            <input type="text" class="form-control" name="color" value="{{ $one_click_order->product->product_information[0]->color }}" style="background-color:{{ $one_click_order->product->product_information[0]->color }};" readonly>
                            @if($errors->has('color'))
                                <span class="help-block">{{ $errors->first('color') }}</span>
                            @endif
                        </div>
                    </li>

                    <li class="collection-item">
                        <label for="category">Кількість</label>
                        <div class="form-group{{ $errors->has('product_qty') ? ' has-error' : '' }}">
                            <input id="product_qty" type="number" min="1" class="form-control" name="product_qty" value="{{ old('product_qty') ? old('product_qty') : "" }}" placeholder="Спочатку виберіть розмір">
                            @if($errors->has('product_qty'))
                                <span class="help-block">{{ $errors->first('product_qty') }}</span>
                            @endif
                        </div>
                    </li>


                    <li class="collection-item">
                        <label for="category">ПІБ</label>
                        <div class="form-group{{ $errors->has('full_name') ? ' has-error' : '' }}">
                            <input type="text" class="form-control" name="full_name" value="{{ old('full_name') ? old('full_name') : ($one_click_order->user ? $one_click_order->user->username : "") }}" placeholder="ПІБ">
                            @if($errors->has('full_name'))
                                <span class="help-block">{{ $errors->first('full_name') }}</span>
                            @endif
                        </div>
                    </li>

                    <li class="collection-item">
                        <label for="category">email</label>
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <input type="text" class="form-control" name="email" value="{{ old('email') ? old('email') : "" }}" placeholder="email">
                            @if($errors->has('email'))
                                <span class="help-block">{{ $errors->first('email') }}</span>
                            @endif
                        </div>
                    </li>

                    <li class="collection-item">
                        <label for="category">Телефонний номер</label>
                        <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                            <input type="text" class="form-control" name="phone" value="{{ old('phone') ? old('phone') : $one_click_order->phone_number }}" placeholder="Телефонний номер">
                            @if($errors->has('phone'))
                                <span class="help-block">{{ $errors->first('phone') }}</span>
                            @endif
                        </div>
                    </li>


                    <li class="collection-item">
                        <label for="category">Спосіб оплати</label>
                        <div class="form-group{{ $errors->has('payment_method') ? ' has-error' : '' }}">
                            <select name="payment_method" class="form-control">
                                <option {{ !old('payment_method') ? 'selected' : ""}} disabled>{{trans('messages.select_payment_method')}}</option>
                                <option value="cash_payment_method" {{ (old('payment_method') == trans('messages.cash_payment_method')) ? 'selected' : ""}}>{{trans('messages.cash_payment_method')}}</option>
                                <option value="prepayment_payment_method" {{ (old('payment_method') == trans('messages.prepayment_payment_method')) ? 'selected' : ""}}>{{trans('messages.prepayment_payment_method')}}</option>
                            </select>

                            @if($errors->has('payment_method'))
                                <span class="help-block">{{ $errors->first('payment_method') }}</span>
                            @endif
                        </div>
                    </li>

                    <li class="collection-item">
                        <label for="category">Місто</label>
                        <div class="form-group{{ $errors->has('city_id') ? ' has-error' : '' }}">
                            <select id="city_id" name="city_id" class="form-control">
                                <option {{ !old('city_id') ? 'selected' : ""}} disabled>Оберіть місто</option>
                                @foreach($cities as $city)
                                    <option value="{{$city->id}}">{{$city->city}}</option>
                                @endforeach
                                
                            </select>

                            @if($errors->has('city_id'))
                                <span class="help-block">{{ $errors->first('city_id') }}</span>
                            @endif
                        </div>
                    </li>

                    <li class="collection-item">
                        <div id="departments">
                            {{--<label>Відділення нової пошти</label>--}}
                            {{--<div class="form-group{{ $errors->has('city_id') ? ' has-error' : '' }}">--}}
                                {{--<select id="city_id" name="city_id" class="form-control">--}}
                                {{--</select>--}}
                            {{--</div>--}}
                        </div>
                    </li>

                    <li class="collection-item">
                        <label for="category">Ціна</label>
                        <div class="form-group">
                            <input id="price" type="text" class="form-control" name="price" value="{{ $one_click_order->product->discount ? $one_click_order->product->reduced_price : $one_click_order->product->price }}" readonly>
                        </div>
                    </li>

                    <li class="collection-item">
                        <label for="category">Знижка</label>
                        <div class="form-group">
                            <input type="text" class="form-control" name="discount" value="{{ $one_click_order->product->discount ? $one_click_order->product->discount : 0 }}" readonly>
                        </div>
                    </li>

                    <li class="collection-item">
                        <label for="category">Загальна ціна</label>
                        <div class="form-group">
                            <input id="total_price" type="text" class="form-control" name="total_price" value="" readonly>
                        </div>
                    </li>


                    <!-- Tab panes -->

                    <li class="collection-item blue">
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-link grey lighten-5">Додати замовлення</button>
                        </div>
                    </li>
                </form>
            </ul>
        </div>

    </div>


@endsection

@section('footer')
    <script>
        $(document).ready(function() {


            $('#city_id').select2({
                language: {
                    noResults: function () {
                        return '{{trans('messages.noResult')}}';
                    }
                }
            }).change(function() {
                var city = $('#city_id option:selected').text();

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
                    {{--$('#departments').append(--}}
                        {{--'<div class="col-md-12">' +--}}
                        {{--'<label class="text-muted" for="code" >{{trans('messages.Department_new_mail')}}:</label>'+--}}
                        {{--'</div>'+--}}
                        {{--'<div class="col-md-12">' +--}}
                        {{--'<select class="order_form_department_select" name="mailDepartment" ></select>' +--}}
                        {{--'</div>'--}}
                    {{--);--}}
                    $('#departments').append(
                        `<label>Відділення нової пошти</label>
                        <div class="form-group">
                            <select class="order_form_department_select form-control" name="postDepartment"  class="form-control">
                            </select>
                        </div>`
                    );

                    if(response.language == 'ru'){
                        response.Departments.forEach(function(data) {
                            $('select[name=postDepartment]').append('<option value="' + data.DescriptionRu + '">' + data.DescriptionRu + "</option>");
                        });
                    }else{
                        response.Departments.forEach(function(data) {
                            $('select[name=postDepartment]').append('<option value="' + data.Description + '">' + data.Description + "</option>");
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

            $('#size').change(function() {
                var max_quantity = $("#size option:selected").data("quantity");

                $('#product_qty').attr("max", max_quantity);
                $('#product_qty').attr("placeholder", "Максимальна кількість товару рівна " + max_quantity);

                var product_qty = $('#product_qty').val();

                if ( product_qty > max_quantity ) {
                    $('#product_qty').val(max_quantity);
                }
            });

            $('#product_qty').change(function() {

                if ( ! $('#size').val() ) {
                    $('#product_qty').val('');
                    sweetAlert("Спочатку виберіть розмір");
                    return;
                }

                var max_quantity = $('#product_qty').attr("max");

                var product_qty = $('#product_qty').val();

                if ( product_qty > max_quantity ) {
                    $('#product_qty').val(max_quantity);
                }

                product_qty = $('#product_qty').val();

                var price = $("#price").val();

                $('#total_price').val( (product_qty * price).toFixed(2) );
            });

            //sweetAlert("1");
        });
    </script>
@endsection