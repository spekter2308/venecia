@extends('admin.dash')

@section('content')

        <a href="#menu-toggle" class="btn btn-default" id="menu-toggle"><i class="fa fa-bars fa-5x"></i></a>
        <a href="{{ url('admin/pages') }}" class="btn btn-danger">Назад</a>
        <br><br>
        <div class="container" id="admin-category-container">
            @if($mainPage)
                <div class=" col-sm-8 col-md-9 " id="admin-category-container">
                    <ul class="collection with-header">
                        <form role="form" method="POST" action="{{ route('updateMainPage') }}">
                            {{ csrf_field() }}
                            <li class="collection-item blue" style="margin-bottom: 15px;">
                                <h4 class="white-text text-center">
                                    Головна сторінка
                                </h4>
                            </li>


                            <!-- Tab panes -->
                            <ul class="nav nav-tabs" role="tablist">
                                <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">ОПИС</a></li>
                                <li role="presentation"><a href="#home_ru" aria-controls="home_ru" role="tab" data-toggle="tab">ОПИС на російській</a>
                                </li>
                            </ul>
                            <li class="tab-content ">
                                <div role="tabpanel" class="tab-pane active" id="home">
                                    <div style="padding: 15px;">
                                        <div class="form-group{{ $errors->has('seo_keywords') ? ' has-error' : '' }}">
                                            <label>meta keywords</label>
                                            <input type="text" class="form-control" name="seo_keywords"  value="{{  Request::old('seo_keywords') ? : $mainPage->seo_keywords }}" placeholder="meta keywords">
                                            @if($errors->has('seo_keywords'))
                                                <span class="help-block">{{ $errors->first('seo_keywords') }}</span>
                                            @endif
                                        </div>
                                        <div class="form-group{{ $errors->has('seo_title') ? ' has-error' : '' }}">
                                            <label>meta title</label>
                                            <input type="text" class="form-control" name="seo_title" value="{{  Request::old('seo_title') ? : $mainPage->seo_title }}" placeholder="meta title ">
                                            @if($errors->has('seo_title'))
                                                <span class="help-block">{{ $errors->first('seo_title') }}</span>
                                            @endif
                                        </div>
                                        <div class="form-group{{ $errors->has('seo_description') ? ' has-error' : '' }}">
                                            <label>meta description</label>
                                            <input type="text" class="form-control" name="seo_description" value="{{  Request::old('seo_description') ? : $mainPage->seo_description }}" placeholder="meta description">
                                            @if($errors->has('seo_description'))
                                                <span class="help-block">{{ $errors->first('seo_description') }}</span>
                                            @endif
                                        </div>
                                        <div class="form-group{{ $errors->has('intro_description') ? ' has-error' : '' }}">
                                            <label for="intro_description">Короткий опис</label>
                                            <textarea id="intro_description" name="intro_description">{{ Request::old('intro_description') ? : $mainPage->intro_description  }}</textarea>
                                            @if($errors->has('intro_description'))
                                                <span class="help-block">{{ $errors->first('intro_description') }}</span>
                                            @endif
                                        </div>
                                        <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                                            <label for="description">Повний опис</label>
                                            <textarea id="mainPage-description" name="description">{{ Request::old('description') ? : $mainPage->description  }}</textarea>
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
                                            <input type="text" class="form-control" name="seo_keywords_ru"  value="{{  Request::old('seo_keywords') ? : $mainPage->seo_keywords_ru }}" placeholder="meta keywords">
                                            @if($errors->has('seo_keywords'))
                                                <span class="help-block">{{ $errors->first('seo_keywords') }}</span>
                                            @endif
                                        </div>
                                        <div class="form-group{{ $errors->has('seo_title') ? ' has-error' : '' }}">
                                            <label>meta title ru</label>
                                            <input type="text" class="form-control" name="seo_title_ru" value="{{  Request::old('seo_title') ? : $mainPage->seo_title_ru }}" placeholder="meta title ">
                                            @if($errors->has('seo_title'))
                                                <span class="help-block">{{ $errors->first('seo_title') }}</span>
                                            @endif
                                        </div>
                                        <div class="form-group{{ $errors->has('seo_description') ? ' has-error' : '' }}">
                                            <label>meta description ru</label>
                                            <input type="text" class="form-control" name="seo_description_ru" value="{{  Request::old('seo_description') ? : $mainPage->seo_description_ru }}" placeholder="meta description">
                                            @if($errors->has('seo_description'))
                                                <span class="help-block">{{ $errors->first('seo_description') }}</span>
                                            @endif
                                        </div>
                                        <div class="form-group{{ $errors->has('intro_description_ru') ? ' has-error' : '' }}">
                                            <label for="intro_description_ru">Короткий опис на російській</label>
                                            <textarea id="intro_description_ru" name="intro_description_ru">{{ Request::old('intro_description_ru') ? : $mainPage->intro_description_ru  }}</textarea>
                                            @if($errors->has('intro_description_ru'))
                                                <span class="help-block">{{ $errors->first('intro_description_ru') }}</span>
                                            @endif
                                        </div>
                                        <div class="form-group{{ $errors->has('description_ru') ? ' has-error' : '' }}">
                                            <label for="description_ru">Повний опис на російській</label>
                                            <textarea id="mainPage-description_ru" name="description_ru">{{ Request::old('description_ru') ? : $mainPage->description_ru  }}</textarea>
                                            @if($errors->has('description_ru'))
                                                <span class="help-block">{{ $errors->first('description_ru') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <li class="collection-item blue">
                                <div class="form-group text-center">
                                    <button type="submit" class="btn btn-link grey lighten-5">Зберегти</button>
                                </div>
                            </li>
                        </form>
                    </ul>
                </div>
            @endif


        </div>

@endsection

@section('footer')
    {{--<script>--}}
        {{--$(function () {--}}
            {{--$('#mainPage-description').ckeditor()--}}
        {{--});--}}
        {{--$(function () {--}}
            {{--$('#mainPage-description_ru').ckeditor()--}}
        {{--});--}}
    {{--</script>--}}

    <script>
        //        $(function () {
        //            $('#page').ckeditor()
        //        });
        //        $(function () {
        //            $('#page_ru').ckeditor()
        //        });

        $(function () {
            CKEDITOR.plugins.addExternal( 'justify', '/src/public/js/plugins/justify/', 'plugin.js' );
            CKEDITOR.replace( 'mainPage-description', {
                extraPlugins: 'justify',
                //allowedContent: 'iframe[*]; p b i s table hr symbol; a[*];img[*];ul;ol;blockquote;',
            } );
        });
        $(function () {
            CKEDITOR.plugins.addExternal( 'justify', '/src/public/js/plugins/justify/', 'plugin.js' );
            CKEDITOR.replace( 'mainPage-description_ru', {
                extraPlugins: 'justify',
                //allowedContent: 'iframe[*]; p[*]; b i s table hr symbol; a[*];img[*];ul;ol;blockquote;',
            } );
        });

        $(function () {
            CKEDITOR.plugins.addExternal( 'justify', '/src/public/js/plugins/justify/', 'plugin.js' );
            CKEDITOR.replace( 'intro_description', {
                extraPlugins: 'justify',
                //allowedContent: 'iframe[*]; p b i s table hr symbol; a[*];img[*];ul;ol;blockquote;',
            } );
        });
        $(function () {
            CKEDITOR.plugins.addExternal( 'justify', '/src/public/js/plugins/justify/', 'plugin.js' );
            CKEDITOR.replace( 'intro_description_ru', {
                extraPlugins: 'justify',
                //allowedContent: 'iframe[*]; p[*]; b i s table hr symbol; a[*];img[*];ul;ol;blockquote;',
            } );
        });
    </script>

@endsection

