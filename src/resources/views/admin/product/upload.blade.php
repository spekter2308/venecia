@extends('admin.dash')

@section('content')

    <div class="container">

        <br><br>
        <a href="#menu-toggle" class="btn btn-default" id="menu-toggle"><i class="fa fa-bars fa-5x"></i></a>
        <a href="{{ url('admin/products') }}" class="btn btn-danger">Назад</a>
        <br><br>

        <div class="col-md-12">

            <h5 class="text-center">Завантажити зображення продуктів для
                @foreach($product as $products)
                    <p>{{ $products->product_name }}</p>
                @endforeach
            </h5>

            <br>

            @if (Auth::user()->id == 2)
                <h6 class="text-center"><b>Cant upload images as test user</b></h6>
            @else
                @if ($products->photos->count() > 7)
                    <p class="text-center"><b>Неможливо завантажити більше 8 фотографій для одного  продукту. Видаліть кілька фотографій, щоб завантажити інші фотографії.</b></p><br><br>
                @else
                    <form method="POST" action="/admin/products/{{ $products->id }}/photo" class="dropzone" id="addProductImages" enctype="multipart/form-data">
                        {{ csrf_field() }}
                    </form>
                    <p class="text-center"><span class="red-text">*</span> Тільки 8 фотографій будуть відображатися на сторінці продукту</p>
                @endif
            @endif


            <div class="col-md-12 gallery">
                @foreach ($products->photos->chunk(4) as $set)
                    <div class="row" id="image_row">
                        @foreach ($set as $photo)
                            <div class="col-xs-6 col-sm-3 col-md-3 gallery_image">
                                <label>{{ $photo->id }}</label>
                                @if (Auth::user()->id == 2)
                                    <a href="/{{ $photo->path }}" data-lity>
                                        <img src="/{{ $photo->thumbnail_path }}" alt="" data-id="{{ $photo->id }}">
                                    </a>
                                @else
                                    <div class="img-wrap">
                                        <form method="post" action="/admin/products/photos/{{ $photo->id }}">
                                            {!! csrf_field() !!}
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="submit" class="close">&times;</button>
                                            <a href="/{{ $photo->path }}" data-lity>
                                                <img src="/{{ $photo->thumbnail_path }}" alt="" data-id="{{ $photo->id }}">
                                            </a>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endforeach

                <br><br>

                    @if (Auth::user()->id == 2)

                    @else
                        <button class="btn btn-info btn-sm waves-effect waves-light" onclick="location.reload();">Показати</button>
                    @endif

            </div>

        </div> <!-- Close col-md-12 -->


        <div class="col-md-12 gallery" id="gallery">

            <hr>

            <br><br>

            <h6>Виберіть одне головне зображення для продукту: {{ $products->product_name }}?</h6><br>


            <form method="post" action="/admin/products/add/featured/{{ $products->id }}">
                {!! csrf_field() !!}
                @foreach($products->photos as $set)
                        <div class="form-group{{ $errors->has('featured') ? ' has-error' : '' }}">
                            <div class="input-group">
                                <label>{{ $set->id }}&nbsp;&nbsp;&nbsp;</label>
                                <input type="checkbox" name="featured" value="{{ $set->id }}" {{ $set->featured === 1 ? "checked=checked" : "" }}><br>
                                @if($errors->has('featured'))
                                    <span class="help-block">{{ $errors->first('featured') }}</span>
                                @endif
                            </div>
                        </div>
                @endforeach


                <div class="form-group col-md-12">
                    <button type="submit" class="btn btn-primary waves-effect waves-light">Зберегти</button>
                </div>

            </form>

        </div> <!-- Close col-md-12 -->

    </div>  <!-- Close container -->

@endsection


@section('footer')

    <script  src="{{ asset('src/public/js/libs/dropzone.js') }}"></script>

    <script>

        Dropzone.options.addProductImages = {
            paramName: 'photo',
            maxFilesize: 2,
            maxFiles: 12,
            acceptedFiles: '.jpg, .jpeg, .png'
        }

    </script>

    <script>
        if(!$('#image_row').length){
            $('#gallery').hide();
        }
    </script>

@endsection
