@extends('admin.dash')

@section('content')

    <div class="container" id="admin-category-container">

        <br><br>
        <a href="#menu-toggle" class="btn btn-default" id="menu-toggle"><i class="fa fa-bars fa-5x"></i></a>
        <a href="{{ url('admin/categories') }}" class="btn btn-danger">Назад</a>
        <br><br>

        <div class="col-sm-8 col-md-9" id="admin-category-container">
            <ul class="collection with-header">
                <form role="form" method="POST" action="{{ route('admin.category.updatesub', $category->id) }}">
                    {{ csrf_field() }}
                    <li class="collection-item blue">
                        <h4 class="white-text text-center">
                            Редагувати  підкатегорію
                        </h4>
                    </li>
                    <li class="collection-item">
                        <label for="parent_id">Батьківська категорія</label>
                        <div class="form-group{{ $errors->has('parent_id') ? ' has-error' : '' }}">
                            <select name="parent_id" id="parent_id" class="form-control">
                                <option value="null" {{ (Request::old('parent_id') ? (Request::old('parent_id') == null ? "selected":"") :($category->parent_id == null ? "selected":"")) }}>Немає</option>
                                @forelse($parents_categories_list as $item)

                                    @if ($item->id !=  $category->id)
                                        <option value="{{ $item->id }}" {{ (Request::old('parent_id') ? (Request::old('parent_id') == $item->id ? "selected":"") :($category->parent_id == $item->id ? "selected":"")) }}>{{ $item->category }}</option>
                                    @endif

                                    @if($category_nested_depth == 1)

                                        @foreach($item->children as $sub_item)
                                            @if ($sub_item->id !=  $category->id)
                                                <option value="{{ $sub_item->id }}" {{ (Request::old('parent_id') ? (Request::old('parent_id') == $sub_item->id ? "selected":"") :($category->parent_id == $sub_item->id ? "selected":"")) }}>{{ $item->category }} - {{ $sub_item->category }}</option>
                                            @endif
                                        @endforeach

                                    @endif

                                @empty
                                @endforelse
                            </select>
                            @if($errors->has('parent_id'))
                                <span class="help-block">{{ $errors->first('parent_id') }}</span>
                            @endif
                        </div>

                    </li>
                    <li class="collection-item">
                        <label for="category">Українська назва</label>
                        <div class="form-group{{ $errors->has('category') ? ' has-error' : '' }}">
                            <input type="text" class="form-control" name="category"  value="{{  Request::old('category') ? : $category->category }}" placeholder="Edit Sub-Category">
                            @if($errors->has('category'))
                                <span class="help-block">{{ $errors->first('category') }}</span>
                            @endif
                        </div>
                    </li>
                    <li class="collection-item">
                        <label for="category">Російська назва</label>
                        <div class="form-group{{ $errors->has('category_ru') ? ' has-error' : '' }}">
                            <input type="text" class="form-control" name="category_ru"  value="{{  Request::old('category_ru') ? : $category->category_ru }}" placeholder="Edit Sub-Category">
                            @if($errors->has('category_ru'))
                                <span class="help-block">{{ $errors->first('category_ru') }}</span>
                            @endif
                        </div>
                    </li>

                    <!--------------------------------------------------------------->

                    <li class="collection-item">
                        <label for="h1">h1 категорії український</label>
                        <div class="form-group{{ $errors->has('h1') ? ' has-error' : '' }}">
                            <input type="text" class="form-control" name="h1" value="{{ old('h1') ? : $category->h1 }}" placeholder="Додати h1">
                            @if($errors->has('h1'))
                                <span class="help-block">{{ $errors->first('h1') }}</span>
                            @endif
                        </div>
                    </li>
                    <li class="collection-item">
                        <label for="h1_ru">h1 категорії російський</label>
                        <div class="form-group{{ $errors->has('h1_ru') ? ' has-error' : '' }}">
                            <input type="text" class="form-control" name="h1_ru" value="{{ old('h1_ru') ? : $category->h1_ru }}" placeholder="Додати h1 ru">
                            @if($errors->has('h1_ru'))
                                <span class="help-block">{{ $errors->first('h1_ru') }}</span>
                            @endif
                        </div>
                    </li>
                    <li class="collection-item">
                        <label for="slug">url категорії</label>
                        <div class="form-group{{ $errors->has('slug') ? ' has-error' : '' }}">
                            <input type="text" class="form-control" name="slug" value="{{ old('slug') ? : $category->slug }}" placeholder="Додати url">
                            @if($errors->has('slug'))
                                <span class="help-block">{{ $errors->first('slug') }}</span>
                            @endif
                        </div>
                    </li>

                    <!--------------------------------------------------------------->
                    <li class="collection-item">
                        <label for="sequence">Поле для вибору послідовності категорії</label>
                        <div class="form-group{{ $errors->has('sequence') ? ' has-error' : '' }}">
                            <select  class="form-control"  name="sequence">
                                @for($i=1 ; $i<=$count_sub_categories ; $i++)
                                    @if($category->sequence == $i || Request::old('sequence') == $i)
                                        <option selected  value={{$i}}>{{$i}}</option>
                                    @else
                                        <option value={{$i}}>{{$i}}</option>
                                    @endif
                                @endfor
                            </select>
                        </div>
                    </li>

                    <!-- Tab panes -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab"
                                                                  data-toggle="tab">ОПИС</a></li>
                        <li role="presentation"><a href="#home_ru" aria-controls="home_ru" role="tab" data-toggle="tab">ОПИС на російській</a>
                        </li>
                    </ul>
                    <li class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="home">
                            <div style="padding: 15px;">
                                <div class="form-group{{ $errors->has('seo_keywords') ? ' has-error' : '' }}">
                                    <label>meta keywords</label>
                                    <input type="text" class="form-control" name="seo_keywords"  value="{{  Request::old('seo_keywords') ? : $category->seo_keywords }}" placeholder="meta keywords">
                                    @if($errors->has('seo_keywords'))
                                        <span class="help-block">{{ $errors->first('seo_keywords') }}</span>
                                    @endif
                                </div>
                                <div class="form-group{{ $errors->has('seo_title') ? ' has-error' : '' }}">
                                    <label>meta title</label>
                                    <input type="text" class="form-control" name="seo_title" value="{{  Request::old('seo_title') ? : $category->seo_title }}" placeholder="meta title ">
                                    @if($errors->has('seo_title'))
                                        <span class="help-block">{{ $errors->first('seo_title') }}</span>
                                    @endif
                                </div>
                                <div class="form-group{{ $errors->has('seo_description') ? ' has-error' : '' }}">
                                    <label>meta description</label>
                                    <input type="text" class="form-control" name="seo_description" value="{{  Request::old('seo_description') ? : $category->seo_description }}" placeholder="meta description">
                                    @if($errors->has('seo_description'))
                                        <span class="help-block">{{ $errors->first('seo_description') }}</span>
                                    @endif
                                </div>
                                <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                    <label for="title">Заголовок категорії</label>
                                    <textarea id="category-title" name="title">{{Request::old('title') ? : $category->title}}</textarea>
                                    @if($errors->has('title'))
                                        <span class="help-block">{{ $errors->first('title') }}</span>
                                    @endif
                                </div>
                                <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                                    <label for="description">Опис продукту</label>
                                    <textarea id="category-description" name="description">{{ Request::old('description') ? : $category->description  }}</textarea>
                                    @if($errors->has('description'))
                                        <span class="help-block">{{ $errors->first('description') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="home_ru">
                            <div style="padding: 15px;">
                                <div class="form-group{{ $errors->has('seo_keywords') ? ' has-error' : '' }}">
                                    <label>meta keywords ru</label>
                                    <input type="text" class="form-control" name="seo_keywords_ru"  value="{{  Request::old('seo_keywords') ? : $category->seo_keywords_ru }}" placeholder="meta keywords">
                                    @if($errors->has('seo_keywords'))
                                        <span class="help-block">{{ $errors->first('seo_keywords') }}</span>
                                    @endif
                                </div>
                                <div class="form-group{{ $errors->has('seo_title') ? ' has-error' : '' }}">
                                    <label>meta title ru</label>
                                    <input type="text" class="form-control" name="seo_title_ru" value="{{  Request::old('seo_title') ? : $category->seo_title_ru }}" placeholder="meta title ">
                                    @if($errors->has('seo_title'))
                                        <span class="help-block">{{ $errors->first('seo_title') }}</span>
                                    @endif
                                </div>
                                <div class="form-group{{ $errors->has('seo_description') ? ' has-error' : '' }}">
                                    <label>meta description ru</label>
                                    <input type="text" class="form-control" name="seo_description_ru" value="{{  Request::old('seo_description') ? : $category->seo_description_ru }}" placeholder="meta description">
                                    @if($errors->has('seo_description'))
                                        <span class="help-block">{{ $errors->first('seo_description') }}</span>
                                    @endif
                                </div>
                                <div class="form-group{{ $errors->has('title_ru') ? ' has-error' : '' }}">
                                    <label for="title_ru">Заголовок категорії на російській</label>
                                    <textarea id="category-title_ru" name="title_ru">{{Request::old('title_ru') ? : $category->title_ru}}</textarea>
                                    @if($errors->has('title_ru'))
                                        <span class="help-block">{{ $errors->first('title_ru') }}</span>
                                    @endif
                                </div>
                                <div class="form-group{{ $errors->has('description_ru') ? ' has-error' : '' }}">
                                    <label for="description_ru">Опис категорії на російській</label>
                                    <textarea id="category-description_ru" name="description_ru">{{ Request::old('description_ru') ? : $category->description_ru  }}</textarea>
                                    @if($errors->has('description_ru'))
                                        <span class="help-block">{{ $errors->first('description_ru') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="collection-item blue">
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-link grey lighten-5">Редагувати  підкатегорію</button>
                        </div>
                    </li>
                </form>
            </ul>
        </div>

    </div>

@endsection

@section('footer')
    <script>
        //        $(function () {
        //            $('#category-description_ru').ckeditor()
        //            $('#category-description').ckeditor()
        //        });
        $(function () {
            CKEDITOR.plugins.addExternal( 'justify', '/src/public/js/plugins/justify/', 'plugin.js' );
            CKEDITOR.replace( 'category-description_ru', {
                extraPlugins: 'justify',
                //allowedContent: 'iframe[*]; p b i s table hr symbol; a[*];img[*];ul;ol;blockquote;',
            } );
        });
        $(function () {
            CKEDITOR.plugins.addExternal( 'justify', '/src/public/js/plugins/justify/', 'plugin.js' );
            CKEDITOR.replace( 'category-description', {
                extraPlugins: 'justify',
                //allowedContent: 'iframe[*]; p[*]; b i s table hr symbol; a[*];img[*];ul;ol;blockquote;',
            } );
        });

        $(function () {
            CKEDITOR.plugins.addExternal( 'justify', '/src/public/js/plugins/justify/', 'plugin.js' );
            CKEDITOR.replace( 'category-title_ru', {
                extraPlugins: 'justify',
                //allowedContent: 'iframe[*]; p b i s table hr symbol; a[*];img[*];ul;ol;blockquote;',
            } );
        });
        $(function () {
            CKEDITOR.plugins.addExternal( 'justify', '/src/public/js/plugins/justify/', 'plugin.js' );
            CKEDITOR.replace( 'category-title', {
                extraPlugins: 'justify',
                //allowedContent: 'iframe[*]; p[*]; b i s table hr symbol; a[*];img[*];ul;ol;blockquote;',
            } );
        });

    </script>
@endsection