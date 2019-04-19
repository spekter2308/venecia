@extends('admin.dash')

@section('content')

    <div class="container" id="admin-product-container">

        <br><br>
        <a href="#menu-toggle" class="btn btn-default" id="menu-toggle"><i class="fa fa-bars fa-5x"></i></a>
        <a href="{{ url('admin/products') }}" class="btn btn-danger">Назад</a>
        <br><br>

        <h4 class="text-center">Додати новий продукт</h4><br><br>

        <div class="col-md-12">

            <form role="form" method="POST" action="{{ route('admin.product.post') }}">
                {{ csrf_field() }}

                <div class="row">
                    <div class="col-sm-6 col-md-6" id="Product-Input-Field">
                        <div class="form-group{{ $errors->has('product_name') ? ' has-error' : '' }}">
                            <label>Назва продукту</label>
                            <input type="text" class="form-control" name="product_name"
                                   value="{{ old('product_name') }}" placeholder="Назва">
                            @if($errors->has('product_name'))
                                <span class="help-block">{{ $errors->first('product_name') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-6" id="Product-Input-Field">
                        <div class="form-group{{ $errors->has('product_name_ru') ? ' has-error' : '' }}">
                            <label>Назва продукту на російській</label>
                            <input type="text" class="form-control" name="product_name_ru"
                                   value="{{ old('product_name_ru') }}" placeholder="Російська назва">
                            @if($errors->has('product_name_ru'))
                                <span class="help-block">{{ $errors->first('product_name_ru') }}</span>
                            @endif
                        </div>
                    </div>



                    <div class="col-sm-6 col-md-6" id="Product-Input-Field">
                        <div class="form-group{{ $errors->has('code') ? ' has-error' : '' }}">
                            <label>Код</label>
                            <input type="text" class="form-control" name="code"
                                   value="{{ old('code') }}" placeholder="Код товару в 1с">
                            @if($errors->has('code'))
                                <span class="help-block">{{ $errors->first('code') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-6" id="Product-Input-Field">
                        <div class="form-group{{ $errors->has('active') ? ' has-error' : '' }}">
                            <label>Активація продукту</label>
                            <select class="form-control" name="active" id="active">
                                <option value="1">Активований</option>
                                <option value="0">Не активований</option>
                            </select>
                            @if($errors->has('active'))
                                <span class="help-block">{{ $errors->first('active') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-6" id="Product-Input-Field">
                        <div class="form-group{{ $errors->has('brand_id') ? ' has-error' : '' }}">
                            <label>Бренд</label>
                            <select class="form-control" name="brand_id" id="brand_id">
                                <option value=""></option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}"
                                            @if ( old('brand_id') == $brand->id) selected="selected" @endif >{{ $brand->brand_name }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('brand_id'))
                                <span class="help-block">{{ $errors->first('brand_id') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-6" id="Product-Input-Field">
                        <div class="form-group{{ $errors->has('seo_keywords') ? ' has-error' : '' }}">
                            <label>Meta keywords</label>
                            <input type="text" class="form-control" name="seo_keywords"
                                   value="{{ Request::old('seo_keywords')  }}"
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
                                   value="{{ Request::old('seo_title') }}"
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
                                   value="{{ Request::old('seo_description')  }}"
                                   placeholder="Опис">
                            @if($errors->has('seo_description'))
                                <span class="help-block">{{ $errors->first('seo_description') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-12" id="category-dropdown-container">

                        <div class="col-sm-6 col-md-6" id="Product-Input-Field">
                            <div class="form-group{{ $errors->has('price') ? ' has-error' : '' }}">
                                <label>Ціна</label>
                                <div class="input-group">
                                    <div class="input-group-addon"><i class="material-icons">attach_money</i></div>
                                    <input type="text" class="form-control" name="price" value="{{ old('price') }}"
                                           placeholder="Ціна продукту">
                                </div>
                                @if($errors->has('price'))
                                    <span class="help-block">{{ $errors->first('price') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-6" id="Product-Input-Field">
                            <div class="form-group{{ $errors->has('reduced_price') ? ' has-error' : '' }}">
                                <label>Зниженна ціна(не обов'язково)</label>
                                <div class="input-group">
                                    <div class="input-group-addon"><i class="material-icons">attach_money</i></div>
                                    <input type="text" class="form-control" name="reduced_price"
                                           value="{{ old('reduced_price') }}" placeholder="Знижена ціна продукту">
                                </div>
                                @if($errors->has('reduced_price'))
                                    <span class="help-block">{{ $errors->first('reduced_price') }}</span>
                                @endif
                            </div>
                        </div>

                    </div>



                    <div class="col-md-12" id="category-dropdown-container">

                        <div class="col-sm-6 col-md-6" id="Product-Input-Field">
                            <div class="form-group{{ $errors->has('main_category_id') ? ' has-error' : '' }}">
                                <label>Основна категорія</label>
                                <select class="form-control" name="main_category_id" id="main_category_id">
                                    <option selected="selected" disabled="disabled">Виберіть категорію</option>

                                    @foreach($categories->sortBy('sequence') as $category)

                                        @php($disabled = count($category->children) ? true : false )

                                        <option
                                                value="{{ $category->id }}"
                                                {{ $disabled ? 'disabled=disabled' : '' }}
                                        >
                                            {{ $category->category }}
                                        </option>

                                        @foreach($category->children->sortBy('sequence') as $sub_category)

                                            @php($disabled = count($sub_category->children) ? true : false )

                                            <option
                                                    value="{{ $sub_category->id }}"
                                                    {{ $disabled ? 'disabled=disabled' : '' }}
                                            >
                                                {{ $category->category }} - {{ $sub_category->category }}
                                            </option>

                                            @foreach($sub_category->children->sortBy('sequence') as $sub_sub_category)
                                                <option
                                                        value="{{ $sub_sub_category->id }}"
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
                                                {{ $disabled ? 'disabled=disabled' : '' }}
                                        >
                                            {{ $category->category }}
                                        </option>

                                        @foreach($category->children->sortBy('sequence') as $sub_category)

                                            @php($disabled = count($sub_category->children) ? true : false )

                                            <option
                                                    value="{{ $sub_category->id }}"
                                                    {{ $disabled ? 'disabled=disabled' : '' }}
                                            >
                                                {{ $category->category }} - {{ $sub_category->category }}
                                            </option>

                                            @foreach($sub_category->children->sortBy('sequence') as $sub_sub_category)
                                                <option value="{{ $sub_sub_category->id }}">{{ $category->category }} - {{ $sub_category->category }} - {{ $sub_sub_category->category }}</option>
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

                    <div class="col-sm-2 col-md-2" id="Product-Input-Field">
                        <div class="form-group">
                            <label>Додати рекомендовані</label><br>
                            <input type="checkbox" name="featured" value="1">
                        </div>
                    </div>

                    {{--ADD SIZE AND COLOR--}}
                    <div class="col-sm-4 col-md-4" id="Product-Input-Field">
                        <div class="row" style="margin:0!important;">
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



                    <div class="col-sm-6 col-md-6" id="Product-Input-Field">
                        <div class="form-group{{ $errors->has('product_sku') ? ' has-error' : '' }}">
                            <label>Артикул</label>
                            <input type="text" class="form-control" name="product_sku" id="product_sku"
                                   value="{{ old('product_sku') }}" placeholder="Артикул">
                            {{--<button class="btn btn-info btn-sm waves-effect waves-light" onclick="GetRandom()"--}}
                            {{--type="button" id="product_sku">Генерувати--}}
                            {{--</button>--}}
                            @if($errors->has('product_sku'))
                                <span class="help-block">{{ $errors->first('product_sku') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-12" >

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
                                    {{ Request::old('description')  }}
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
                                    {{ Request::old('description_ru') }}
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
                                    {{ Request::old('product_spec')  }}
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
                                    {{ Request::old('product_spec_ru')  }}
                                </textarea>
                                        @if($errors->has('product_spec_ru'))
                                            <span class="help-block">{{ $errors->first('product_spec_ru') }}</span>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <button type="submit" class="btn btn-primary waves-effect waves-light">Створити продукт</button>
                        </div>

                    </div>

                </div>
            </form>

        </div> <!-- Close col-md-12 -->

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
            $('#product_spec').ckeditor()
            $('#product-description').ckeditor()
        });
        $(function () {
            $('#product-description_ru').ckeditor()
            $('#product_spec_ru').ckeditor()
        });


    </script>


    {{--SCRIPT  ADD SIZE AND COLOR--}}

    <script>
        'use strict';
        var id = 0;
        $('#add').click(function () {
            id = id + 1;
            let tpl = '<div class="row" style="border-bottom: 1px solid #e8e8e8; padding-bottom: 5px;" id="size_' + id + '"><input type="text" name="color[' + id + '][color]" id="color__' + id + '"' +
                ' data-palette=\'["{!! implode('", "',$colorsArr) !!}"]\'  value="" style="margin-right:48px; visibility: hidden">' +
                '<div id="sizes_' + id + '"></div>' +
                '<a href="#" class="add__size btn btn-success btn-sm waves-effect waves-light pull-left" data-color="sizes_' + id + '" style="margin: 5px 0 0 0;">Додати розмір</a><a href="#" data-del="size_' + id + '" class="btn btn-danger btn-sm waves-effect waves-light pull-right del_size">Видалити колір</a></div>';
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
        $('#sub_category').select2();
        $('#main_category_id').select2();
        $('#filters_id').select2();
    </script>

@endsection
