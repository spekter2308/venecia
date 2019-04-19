@extends('admin.dash')

@section('content')

    <div class="container" id="admin-product-container">

        <br><br>
        <a href="#menu-toggle" class="btn btn-default" id="menu-toggle"><i class="fa fa-bars fa-5x"></i></a>
        <a href="{{ url('admin/products') }}" class="btn btn-danger">Назад</a>
        <br><br>

        <h4 class="text-center">Редагування {{ $product->product_name }}</h4><br><br>
        <div class="col-md-12">

            <form role="form" method="POST" action="{{ route('admin.product.update', $product->id) }}">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-sm-6 col-md-6" id="Product-Input-Field">
                        <div class="form-group{{ $errors->has('product_name') ? ' has-error' : '' }}">
                            <label>Назва продукту</label>
                            <input type="text" class="form-control" name="product_name"
                                   value="{{ Request::old('product_name') ? : $product->product_name }}"
                                   placeholder="Назва продукту">
                            @if($errors->has('product_name'))
                                <span class="help-block">{{ $errors->first('product_name') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-6" id="Product-Input-Field">
                        <div class="form-group{{ $errors->has('product_name_ru') ? ' has-error' : '' }}">
                            <label>Назва продукту на російській</label>
                            <input type="text" class="form-control" name="product_name_ru"
                                   value="{{ Request::old('product_name_ru') ? : $product->product_name_ru }}"
                                   placeholder="Назва продукту на російській">
                            @if($errors->has('product_name_ru'))
                                <span class="help-block">{{ $errors->first('product_name_ru') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-6" id="Product-Input-Field">
                        <div class="form-group{{ $errors->has('code') ? ' has-error' : '' }}">
                            <label>Код товару в 1с</label>
                            <input type="text" class="form-control" name="code"
                                   value="{{ Request::old('code') ? : $product->code }}"
                                   placeholder="Код товару в 1с">
                            @if($errors->has('code'))
                                <span class="help-block">{{ $errors->first('code') }}</span>
                            @endif
                        </div>
                    </div>

                    {{--<div class="col-sm-6 col-md-6" id="Product-Input-Field">--}}
                        {{--<div class="form-group{{ $errors->has('brand_id') ? ' has-error' : '' }}">--}}
                            {{--<label>Бренд</label>--}}
                            {{--<select class="form-control" name="brand_id" id="brand_id">--}}
                                {{--<option value=""></option>--}}
                                {{--@foreach($brands as $brand)--}}
                                    {{--<option value="{{ $brand->id }}" {{ $product->brand_id == $brand->id ? "selected" : "" }}>{{ $brand->brand_name }}</option>--}}
                                {{--@endforeach--}}
                            {{--</select>--}}
                            {{--@if($errors->has('brand_id'))--}}
                                {{--<span class="help-block">{{ $errors->first('brand_id') }}</span>--}}
                            {{--@endif--}}
                        {{--</div>--}}
                    {{--</div>--}}

                    <div class="col-sm-6 col-md-6" id="Product-Input-Field">
                        <div class="form-group{{ $errors->has('active') ? ' has-error' : '' }}">
                            <label>Активація продукту</label>
                            <select class="form-control" name="active" id="active">
                                <option value="1" {{$product->active == '1' ? 'selected' : ''}}>{{'Активований'}}</option>
                                <option value="0" {{$product->active == '0' ? 'selected' : ''}}>{{'Не активований'}}</option>
                            </select>
                            @if($errors->has('active'))
                                <span class="help-block">{{ $errors->first('active') }}</span>
                            @endif
                        </div>
                    </div>


                    <div class="col-sm-6 col-md-6" id="Product-Input-Field">
                        <div class="form-group{{ $errors->has('price') ? ' has-error' : '' }}">
                            <label>Ціна</label>

                            <div class="input-group">
                                <div class="input-group-addon"><i class="material-icons">attach_money</i></div>
                                <input type="text" class="form-control" name="price"
                                       value="{{ Request::old('price') ? : $product->price }}"
                                       placeholder="Ціна продукту">
                            </div>
                            @if($errors->has('price'))
                                <span class="help-block">{{ $errors->first('price') }}</span>
                            @endif
                        </div>
                    </div>




                    <div class="col-sm-6 col-md-6" id="Product-Input-Field">
                        <div class="form-group{{ $errors->has('discount') ? ' has-error' : '' }}">
                            <label>Знижка (не обов'язково)</label>

                            <div class="input-group">
                                <div class="input-group-addon"><i class="material-icons">money_off</i></div>
                                <input type="number" class="form-control" name="discount"
                                       value="{{ Request::old('discount') ? : $product->discount }}"
                                       placeholder="Знижка"
                                       min="5" max="70"
                                >
                            </div>
                            @if($errors->has('discount'))
                                <span class="help-block">{{ $errors->first('discount') }}</span>
                            @endif
                        </div>
                    </div>


                    <div class="col-sm-6 col-md-6" id="Product-Input-Field">
                        <div class="form-group{{ $errors->has('reduced_price') ? ' has-error' : '' }}">
                            <label>Зниженна ціна(не обов'язково)</label>

                            <div class="input-group">
                                <div class="input-group-addon"><i class="material-icons">attach_money</i></div>
                                <input type="text" class="form-control" name="reduced_price"
                                       value="{{ Request::old('reduced_price') ? : $product->reduced_price }}"
                                       placeholder="Знижена ціна продукту" readonly>
                            </div>
                            @if($errors->has('reduced_price'))
                                <span class="help-block">{{ $errors->first('reduced_price') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-6" id="Product-Input-Field">
                        <div class="form-group{{ $errors->has('seo_keywords') ? ' has-error' : '' }}">
                            <label>Meta keywords</label>
                            <input type="text" class="form-control" name="seo_keywords"
                                   value="{{ Request::old('seo_keywords') ? : $product->seo_keywords }}"
                                   placeholder="Ключові слова">
                            @if($errors->has('seo_keywords'))
                                <span class="help-block">{{ $errors->first('seo_keywords') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6" id="Product-Input-Field">
                        <div class="form-group{{ $errors->has('seo_title') ? ' has-error' : '' }}">
                            <label>Meta title</label>
                            <input type="text" class="form-control" name="seo_title"
                                   value="{{ Request::old('seo_title') ? : $product->seo_title }}"
                                   placeholder="заголовок">
                            @if($errors->has('seo_title'))
                                <span class="help-block">{{ $errors->first('seo_title') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6" id="Product-Input-Field">
                        <div class="form-group{{ $errors->has('seo_description') ? ' has-error' : '' }}">
                            <label>Meta description</label>
                            <input type="text" class="form-control" name="seo_description"
                                   value="{{ Request::old('seo_description') ? : $product->seo_description }}"
                                   placeholder="Опис">
                            @if($errors->has('seo_description'))
                                <span class="help-block">{{ $errors->first('seo_description') }}</span>
                            @endif
                        </div>
                    </div>


                    <div class="col-md-12" id="category-dropdown-container">
                        {{--<div class="col-sm-6 col-md-6" id="Product-Input-Field">--}}
                            {{--<div class="form-group{{ $errors->has('category') ? ' has-error' : '' }}">--}}
                                {{--<label>Категорія</label>--}}
                                {{--<select class="form-control" name="category" id="category"--}}
                                        {{--data-url="{{ url('api/dropdown')}}">--}}
                                    {{--<option value="" ></option>--}}
                                    {{--@if($parentCategory != null)--}}
                                        {{--@foreach($categories as $category)--}}
                                            {{--<option value="{{ $category->id }}" {{ $category->id == $parentCategory ? 'selected' : '' }}>{{ $category->category }}</option>--}}
                                        {{--@endforeach--}}
                                    {{--@else--}}
                                        {{--@foreach($categories as $category)--}}
                                            {{--<option value="{{ $category->id }}" {{ in_array($category->id, $selectedChildrenCategories ) ? 'selected' : '' }}>{{ $category->category }}</option>--}}
                                        {{--@endforeach--}}
                                    {{--@endif--}}

                                {{--</select>--}}
                                {{--@if($errors->has('category'))--}}
                                    {{--<span class="help-block">{{ $errors->first('category') }}</span>--}}
                                {{--@endif--}}
                            {{--</div>--}}
                            {{--<br>--}}
                        {{--</div>--}}

                        {{--<div class="col-sm-6 col-md-6" id="Product-Input-Field">--}}
                            {{--<div class="form-group{{ $errors->has('cat_id') ? ' has-error' : '' }}">--}}
                                {{--<label>Підкатегорія</label>--}}
                                {{--<select class="form-control" name="cat_id[]" id="sub_category" multiple>--}}
                                    {{--@if($childrenCategories)--}}
                                        {{--@foreach($childrenCategories as $category)--}}

                                            {{--<option value="{{ $category['id'] }}" {{ in_array($category['id'], $selectedChildrenCategories ) ? 'selected' : '' }}>{{ $category['category'] }}</option>--}}
                                        {{--@endforeach--}}
                                    {{--@endif--}}
{{--                                    <option value="{{ $parentCategory->id }}" {{ in_array($parentCategory->id, $selectedChildrenCategories ) ? 'selected' : '' }}>{{ $parentCategory->category }}</option>--}}
                                {{--</select>--}}
                                {{--@if($errors->has('cat_id'))--}}
                                    {{--<span class="help-block">{{ $errors->first('cat_id') }}</span>--}}
                                {{--@endif--}}
                            {{--</div>--}}
                            {{--<br>--}}
                        {{--</div>--}}

                        <div class="col-sm-6 col-md-6" id="Product-Input-Field">
                            <div class="form-group{{ $errors->has('main_category_id') ? ' has-error' : '' }}">
                                <label>Основна категорія</label>
                                <select class="form-control" name="main_category_id" id="main_category_id">

                                    @if(!$product->main_category_id)
                                        <option selected="selected" disabled="disabled">Виберіть категорію</option>
                                    @endif

                                    @foreach($categories->sortBy('sequence') as $category)

                                        @php($disabled = count($category->children) ? true : false )

                                        <option
                                                value="{{ $category->id }}"
                                                {{ ($category->id ==  $product->main_category_id ) ? 'selected' : '' }}
                                                {{ $disabled ? 'disabled=disabled' : '' }}
                                        >
                                            {{ $category->category }}
                                        </option>

                                        @foreach($category->children->sortBy('sequence') as $sub_category)

                                            @php($disabled = count($sub_category->children) ? true : false )

                                            <option
                                                    value="{{ $sub_category->id }}"
                                                    {{ ($sub_category->id ==  $product->main_category_id ) ? 'selected' : '' }}
                                                    {{ $disabled ? 'disabled=disabled' : '' }}
                                            >
                                                {{ $category->category }} - {{ $sub_category->category }}
                                            </option>

                                            @foreach($sub_category->children->sortBy('sequence') as $sub_sub_category)
                                                <option
                                                        value="{{ $sub_sub_category->id }}"
                                                        {{ ($sub_sub_category->id ==  $product->main_category_id ) ? 'selected' : '' }}
                                                >
                                                    {{ $category->category }} - {{ $sub_category->category }} - {{ $sub_sub_category->category }}
                                                </option>
                                            @endforeach

                                        @endforeach

                                    @endforeach
                                </select>
                                @if($errors->has('main_category_id'))
                                    <span class="help-block">{{ $errors->first('main_category_id') }}</span>
                                @endif
                            </div>
                            <br>
                        </div>

                        <div class="col-sm-6 col-md-6" id="Product-Input-Field">
                            <div class="form-group{{ $errors->has('cat_id') ? ' has-error' : '' }}">
                                <label>Підкатегорія</label>
                                <select class="form-control" name="cat_id[]" id="sub_category" multiple>
                                    @foreach($categories->sortBy('sequence') as $category)

                                        @php($disabled = count($category->children) ? true : false )

                                        <option
                                                value="{{ $category->id }}"
                                                {{ in_array($category->id, $selected_categories ) ? 'selected' : '' }}
                                                {{ $disabled ? 'disabled=disabled' : '' }}
                                        >
                                            {{ $category->category }}
                                        </option>

                                        @foreach($category->children->sortBy('sequence') as $sub_category)

                                            @php($disabled = count($sub_category->children) ? true : false )

                                            <option
                                                    value="{{ $sub_category->id }}"
                                                    {{ in_array($sub_category->id, $selected_categories ) ? 'selected' : '' }}
                                                    {{ $disabled ? 'disabled=disabled' : '' }}
                                            >
                                                {{ $category->category }} - {{ $sub_category->category }}
                                            </option>

                                            @foreach($sub_category->children->sortBy('sequence') as $sub_sub_category)
                                                <option value="{{ $sub_sub_category->id }}" {{ in_array($sub_sub_category->id, $selected_categories ) ? 'selected' : '' }}>{{ $category->category }} - {{ $sub_category->category }} - {{ $sub_sub_category->category }}</option>
                                            @endforeach

                                        @endforeach

                                    @endforeach
                                </select>
                                @if($errors->has('cat_id'))
                                    <span class="help-block">{{ $errors->first('cat_id') }}</span>
                                @endif
                            </div>
                            <br>
                        </div>

                        <div class="col-sm-6 col-md-6" id="Product-Input-Field">
                            <div class="form-group{{ $errors->has('filters_id') ? ' has-error' : '' }}">
                                <label>Фільтри</label>
                                <select class="form-control" name="filters_id[]" id="filters_id" multiple>
                                    @foreach($filter_list as $filter)

                                        @foreach($filter->filter_names as $filter_name)

                                            <option
                                                    value="{{ $filter_name->id }}"
                                                    {{ in_array($filter_name->id, $product->filters->pluck('name_id')->toArray() ) ? 'selected' : '' }}
                                            >
                                                {{ $filter->type }} - {{ $filter_name->name }}
                                            </option>

                                        @endforeach

                                    @endforeach
                                </select>
                                @if($errors->has('cat_id'))
                                    <span class="help-block">{{ $errors->first('cat_id') }}</span>
                                @endif
                            </div>
                            <br>
                        </div>

                    </div>

                    <div class="col-sm-6 col-md-6" id="Product-Input-Field">
                        <div class="form-group">
                            <label>Додати рекомендовані</label><br>
                            <input type="checkbox" name="featured"
                                   value="1" {{ $product->featured === 1 ? "checked=checked" : "" }}>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6" id="Product-Input-Field">
                        <div class="form-group{{ $errors->has('product_sku') ? ' has-error' : '' }}">
                            <label>Артикул</label>
                            <input type="text" class="form-control" name="product_sku" id="product_sku"
                                   value="{{ Request::old('product_sku') ? : $product->product_sku }}"
                                   placeholder="Артикул" >

                            @if($errors->has('product_sku'))
                                <span class="help-block">{{ $errors->first('product_sku') }}</span>
                            @endif
                        </div>
                    </div>


                    <div class="col-sm-12 col-md-12" id="Product-Input-Field">
                        <div class="row" style="margin:0!important;">
                            {{--ADD SIZE AND COLOR--}}
                            <table cellspacing="5px">
                                <tr>
                                    <th></th>
                                    <th>Розмір</th>
                                    <th>Кількість</th>

                                    <th></th>
                                </tr>
                                @php $i = 0 @endphp
                                @foreach($product_information as $productInfo)
                                    <tr class="color-group">
                                        <td>
                                            {{--<div class="color" data-target="color_product_{{$i}}"  style="background:{{$productInfo->color ? $productInfo->color : ''}}">--}}
                                            <div class="color" data-target="color_product_{{$i}}"  style="background:{{$productInfo->color ? $productInfo->color : 'url(/src/public/img/diagonale.png)'}}">
                                                {{--)}}]--}}
                                                <input type="text" class="product_color" name="color_product_{{$i}}" id="color_product_{{$i}}" data-palette='["{!! implode('", "',$colorsArr) !!}"]' value="{{$productInfo->color}}"  hidden data-initialvalue="">
                                            </div>
                                        </td>
                                        <td>
                                            <input type="text" class="size" value="{{$productInfo->size}}"
                                                   style="background-color: #f2f2f2; border: none;" readonly>
                                        </td>
                                        <input type="hidden" class="idProduct" value="{{$productInfo->id}}">
                                        <td>
                                            <input type="text" class="quantity form-control" value="{{$productInfo->quantity}}">
                                        </td>

                                        <td>
                                            <a href="{{asset("admin/product/update/".$productInfo->product_id."/info") }}"
                                               class="confirm-quantity" style="color: green"><i class="fa fa-check"></i></a>
                                            <a href="{{asset("admin/product/delete/".$productInfo->product_id."/info") }}"
                                               class="confirm-delete"><i class="fa fa-close"></i></a>
                                        </td>
                                    </tr>
                                    @php $i += 1 @endphp
                                @endforeach
                            </table>
                            <div id="drop"></div>

                            <div class="row">
                                <a id="add" href="#" class="btn btn-info btn-sm waves-effect waves-light"
                                   style="margin: 5px 0 0 0;">Додати колір</a>
                            </div>
                            @if($errors->has('color'))
                                <h6 class="alert alert-danger"><b>{{ $errors->first('color') }}</b></h6>
                            @endif
                        </div>
                    </div>

                </div>


<!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab"
                                                              data-toggle="tab">ОПИС</a></li>
                    <li role="presentation"><a href="#home_ru" aria-controls="home_ru" role="tab" data-toggle="tab">ОПИС на російській</a>
                    </li>
                    <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Опис матеріалу</a>
                    </li>
                    <li role="presentation"><a href="#profile_ru" aria-controls="profile_ru" role="tab" data-toggle="tab">Опис матеріалу на російській</a>
                    </li>

                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="home">

                        <div class="col-md-12">
                            <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                                <label for="description">Опис продукту</label>
                                <textarea id="product-description" name="description">
                                    {{ Request::old('description') ? : $product->description }}
                                </textarea>
                                @if($errors->has('description'))
                                    <span class="help-block">{{ $errors->first('description') }}</span>
                                @endif
                            </div>
                        </div>

                    </div>
                    <div role="tabpanel" class="tab-pane" id="home_ru">

                        <div class="col-md-12">
                            <div class="form-group{{ $errors->has('description_ru') ? ' has-error' : '' }}">
                                <label for="description_ru">Опис продукту</label>
                                <textarea id="product-description_ru" name="description_ru">
                                    {{ Request::old('description_ru') ? : $product->description_ru }}
                                </textarea>
                                @if($errors->has('description_ru'))
                                    <span class="help-block">{{ $errors->first('description_ru') }}</span>
                                @endif
                            </div>
                        </div>

                    </div>
                    <div role="tabpanel" class="tab-pane" id="profile">
                        <div class="col-md-12">
                            <div class="form-group{{ $errors->has('product_spec') ? ' has-error' : '' }}">
                                <label for="product_spec">Опис матеріалу</label>
                                <textarea id="product_spec" name="product_spec">
                                    {{ Request::old('product_spec') ? : $product->product_material->material }}
                                </textarea>
                                @if($errors->has('product_spec'))
                                    <span class="help-block">{{ $errors->first('product_spec') }}</span>
                                @endif
                            </div>
                        </div>

                    </div>
                    <div role="tabpanel" class="tab-pane" id="profile_ru">
                        <div class="col-md-12">
                            <div class="form-group{{ $errors->has('product_spec_ru') ? ' has-error' : '' }}">
                                <label for="product_spec">Опис матеріалу на російській</label>
                                <textarea id="product_spec_ru" name="product_spec_ru">
                                    {{ Request::old('product_spec_ru') ? : $product->product_material->material_ru }}
                                </textarea>
                                @if($errors->has('product_spec'))
                                    <span class="help-block">{{ $errors->first('product_spec') }}</span>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
                <div class="form-group col-md-12">
                    <button type="submit" class="btn btn-primary waves-effect waves-light">Редагувати продукт</button>
                </div>

            </form>

        </div>
        <!-- Close col-md-12 -->

    </div>  <!-- Close container -->
    @endsection

    @section('footer')
            <!-- Include Froala Editor JS files. -->
    <script  src="{{ asset('src/public/js/libs/froala_editor.min.js') }}"></script>

    <!-- Include Plugins. -->
    <script  src="{{ asset('src/public/js/plugins/align.min.js') }}"></script>
    <script  src="{{ asset('src/public/js/plugins/char_counter.min.js') }}"></script>
    <script  src="{{ asset('src/public/js/plugins/font_family.min.js') }}"></script>
    <script  src="{{ asset('src/public/js/plugins/font_size.min.js') }}"></script>
    <script  src="{{ asset('src/public/js/plugins/line_breaker.min.js') }}"></script>
    <script  src="{{ asset('src/public/js/plugins/lists.min.js') }}"></script>
    <script  src="{{ asset('src/public/js/plugins/paragraph_format.min.js') }}"></script>
    <script  src="{{ asset('src/public/js/plugins/paragraph_style.min.js') }}"></script>
    <script  src="{{ asset('src/public//js/plugins/quote.min.js') }}"></script>
    <script  src="{{ asset('src/public/js/libs/palette-color-picker.js') }}"></script>


    <script>


        $(function () {
            $('#product-description').ckeditor();
            $('#product-description_ru').ckeditor();
            $('.colorpicker').each(function () {
                $(this).paletteColorPicker();
            });

        });

        $('.confirm-quantity').click(function () {
            var quantity = $(this).parents('tr').find('.quantity').val();
            var color = $(this).parents('tr').find('.product_color').val();
            var link = $(this).attr('href');
            var idProductInfo = $(this).parents('tr').find('.idProduct').val();
            $.ajax({
                method: "PATCH",
                url: link,
                data: {
                    _token: '{{ csrf_token() }}',
                    quantity: quantity,
                    color: color,
                    idProductInfo: idProductInfo
                },
            })
                    .done(function () {
                        $(this).parents('tr').find('.size').val(quantity);
                        swal("Успіх!", "Кількість продуктів змінено!", "success");
                    });

            return false;
        });
        $('.confirm-delete').click(function () {
            var link = $(this).attr('href');
            var idProductInfo = $(this).parents('tr').find('.idProduct').val();
            var rowToDelete = $(this);
            swal({
                title: "Видалити запис?",
                text: "Ви впевнені?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Так, видалити !",
                cancelButtonText: "Відмінити",
                closeOnConfirm: false
            }, function () {
                $.ajax({
                    method: "DELETE",
                    url: link,
                    data: {
                        _token: '{{ csrf_token() }}',
                        idProductInfo: idProductInfo
                    },
                })
                 .done(function () {
                            rowToDelete.parents('tr').fadeOut();
                            swal("Успіх!", "Ваш запис успішно видаленний.", "success");
                        });

            });

            return false;
        });


    </script>

     <script>
         $(function () {
             $('#product_spec').ckeditor()
         });
                 $(function () {
             $('#product_spec_ru').ckeditor()
         });

     </script>








    {{--SCRIPT  ADD SIZE AND COLOR--}}

    <script>
        'use strict';
        var id = 0;
        $('#add').click(function () {
            id = id + 1;
            let tpl = '<div class="row" style="border-bottom: 1px solid #e8e8e8; padding-bottom: 5px;" id="size_' + id + '">' +
                    '<div class="col-md-5">' +
                    '<input type="text" name="color[' + id + '][color]" id="color__' + id + '" data-palette=\'["{!! implode('", "',$colorsArr) !!}"]\' value="" style="margin-right:48px; visibility: hidden">' +
                    '<div id="sizes_' + id + '"></div>' +
                    '<a href="#" class="add__size btn btn-success btn-sm waves-effect waves-light pull-left" data-color="sizes_' + id + '" style="margin: 5px 0 0 0;">Додати розмір</a><a href="#" data-del="size_' + id + '" class="btn btn-danger btn-sm waves-effect waves-light pull-right del_size">Видалити колір</a></div>'+
                    '</div>';
            $('#drop').append(tpl);
            $('#color__' + id).paletteColorPicker();
            return false;
        });
        $('#color__1').paletteColorPicker();
        var subid = 0;

        $('body').on('click', '.add__size', function () {

            let data_color = $(this).attr('data-color');
            subid++;
            let tpl = '<div style="margin-top: 10px"><input type="text" name="color[' + id + '][item][' + subid + '][size]" placeholder="Розмір" class="form-control" style="width: 49%; margin-right: 1%; float: left;"/>' +
                    '<input type="number" name="color[' + id + '][item][' + subid + '][quantity]" placeholder="Кількість" class="form-control" style="width: 50%; float: left;"/>' +
                    '</div>';

            let endpoint = $('#' + data_color);
            endpoint.append(tpl);
            return false;
        });
        $('body').on('click', '.del_size', function () {

            let id = $(this).attr('data-del');
            $('#' + id).remove();
            return false;
        });
    </script>


    <script>
        for (var i = 0 ; i<'{{count($product_information)}}' ; i++ ){
            $('#color_product_' + i).paletteColorPicker();
        }
       $('div.palette-color-picker-button').css("background", 'transparent');

    </script>

    <script>
        $('#sub_category').select2();
        $('#main_category_id').select2();
        $('#filters_id').select2();
    </script>

    <style>
        .select2-selection,.select2-selection--multiple{
            overflow: hidden !important;
            height: auto !important;
            min-height: 45px !important;
        }
    </style>


@endsection

