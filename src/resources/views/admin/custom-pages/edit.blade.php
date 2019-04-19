@extends('admin.dash')

@section('content')

        <a href="#menu-toggle" class="btn btn-default" id="menu-toggle"><i class="fa fa-bars fa-5x"></i></a>
        <a href="{{ url('admin/pages') }}" class="btn btn-danger">Назад</a>
        <br><br>
        <div class="col-md-12">
            <h3 class="text-center">Редагування сторінки</h3>
            @if($page)

                <form action="{{route('updatePage',$page->id)}}" method="POST">

                {{ csrf_field() }}
                <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Українська</a></li>
                        <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Російська</a>
                        </li>
                    </ul>
                <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="home">
                            <div class="col-md-12">
                                <input type="hidden" class="form-control" name="seo_keywords"  value="" placeholder="">
                                <div class="form-group{{ $errors->has('seo_title') ? ' has-error' : '' }}">
                                    <label>meta title</label>
                                    <input type="text" class="form-control" name="seo_title" value="{{  Request::old('seo_title') ? : $page->seo_title }}" placeholder="meta title ">
                                    @if($errors->has('seo_title'))
                                        <span class="help-block">{{ $errors->first('seo_title') }}</span>
                                    @endif
                                </div>
                                <div class="form-group{{ $errors->has('seo_description') ? ' has-error' : '' }}">
                                    <label>meta description</label>
                                    <input type="text" class="form-control" name="seo_description" value="{{  Request::old('seo_description') ? : $page->seo_description }}" placeholder="meta description">
                                    @if($errors->has('seo_description'))
                                        <span class="help-block">{{ $errors->first('seo_description') }}</span>
                                    @endif
                                </div>
                                <div class="form-group{{ $errors->has('page') ? ' has-error' : '' }}">
                                    <label for="page">Сторінка на українській</label>
                                    <textarea id="page" name="page" >
                                    {{ Request::old('page') ? : $page->page }}
                                </textarea>
                                    @if($errors->has('page'))
                                        <span class="help-block">{{ $errors->first('page') }}</span>
                                    @endif
                                </div>
                            </div>

                        </div>
                        <div role="tabpanel" class="tab-pane" id="profile">
                            <div class="col-md-12">
                                <input type="hidden" class="form-control" name="seo_keywords_ru"  value="" placeholder="">
                                <div class="form-group{{ $errors->has('seo_title_ru') ? ' has-error' : '' }}">
                                    <label>meta title_ru</label>
                                    <input type="text" class="form-control" name="seo_title_ru" value="{{  Request::old('seo_title_ru') ? : $page->seo_title_ru }}" placeholder="meta title_ru">
                                    @if($errors->has('seo_title_ru'))
                                        <span class="help-block">{{ $errors->first('seo_title_ru') }}</span>
                                    @endif
                                </div>
                                <div class="form-group{{ $errors->has('seo_description_ru') ? ' has-error' : '' }}">
                                    <label>meta description_ru</label>
                                    <input type="text" class="form-control" name="seo_description_ru" value="{{  Request::old('seo_description_ru') ? : $page->seo_description_ru }}" placeholder="meta description_ru">
                                    @if($errors->has('seo_description_ru'))
                                        <span class="help-block">{{ $errors->first('seo_description_ru') }}</span>
                                    @endif
                                </div>
                                <div class="form-group{{ $errors->has('page_ru') ? ' has-error' : '' }}">
                                    <label for="page_ru">Сторінка на російській</label>
                                    <textarea id="page_ru" name="page_ru">
                                    {{ Request::old('page_ru') ? : $page->page_ru }}
                                </textarea>
                                    @if($errors->has('product_spec'))
                                        <span class="help-block">{{ $errors->first('page_ru') }}</span>
                                    @endif
                                </div>
                            </div>

                        </div>

                    </div>
                    <div class="form-group col-md-12">
                        <button type="submit" class="btn btn-primary waves-effect waves-light">Редагувати сторінку</button>
                    </div>
                </form>
            @endif


        </div>

@endsection

@section('footer')
    <script>
//        $(function () {
//            $('#page').ckeditor()
//        });
//        $(function () {
//            $('#page_ru').ckeditor()
//        });

        $(function () {
            CKEDITOR.replace( 'page', {
                allowedContent: 'iframe[*]; p b i s table hr symbol; a[*];img[*];ul;ol;blockquote;',
            } );
        });
        $(function () {
            CKEDITOR.replace( 'page_ru', {
                allowedContent: 'iframe[*]; p b i s table hr symbol; a[*];img[*];ul;ol;blockquote;',
            } );
        });
    </script>

@endsection

