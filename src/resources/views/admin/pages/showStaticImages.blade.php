@extends('admin.dash')

@section('content')

    <div class="container">

        <br><br>
        <a href="#menu-toggle" class="btn btn-default" id="menu-toggle"><i class="fa fa-bars fa-5x"></i></a>
        <a href="{{ url('admin/dashboard') }}" class="btn btn-danger">Назад</a>
        <br><br>
        <div class="col-md-12">
            <h3 class="text-center">Фото на головну сторінку</h3><br>

            <form method="POST" action="{{url('/admin/mainImagesStatic')}}" class="dropzone" id="addProductImages"
                  enctype="multipart/form-data">
                {{ csrf_field() }}
            </form>
            <div class="col-md-12 gallery">
                @if(count($allStaticMainImages)>0)
                    @foreach ($allStaticMainImages as $photo)
                    <div class="col-md-6" id="image_row">

                        <div class="col-xs-6 col-sm-3 col-md-3 gallery_image">
                            <label>{{ $photo->id }}</label>
                            @if (Auth::user()->id == 2)
                                <a href="/store/{{ $photo->path }}" data-lity>
                                    <img src="/store/{{ $photo->thumbnail_path }}" alt="" data-id="{{ $photo->id }}">
                                </a>
                            @else
                                <div class="img-wrap">
                                    <form method="post" action="{{url('/admin/image/photosStatic', $photo->id )}}">
                                        {!! csrf_field() !!}
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class="close">&times;</button>
                                        <a href="/{{ $photo->path }}" data-lity>
                                            <img src="/{{ $photo->thumbnail_path }}" alt=""
                                                 data-id="{{ $photo->id }}">
                                        </a>
                                    </form>
                                </div>
                            @endif
                        </div>
                        <form method="post" action="/admin/image/photos/page">
                            {!! csrf_field() !!}
                            <input type="hidden" name="photoId" value="{{ $photo->id }}">
                            <select class="form-control" id="sel1" name="page">
                                @foreach($pages as $page)
                                    @if($page->id == $page->page_id)
                                        <option selected>{{$page->name}}</option>
                                    @else
                                        <option>{{$page->name}}</option>
                                    @endif
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-success btn-sm waves-effect waves-green">Закріпити
                            </button>
                        </form>
                    </div>
                    @endforeach
                @endif

                <br><br>


            </div>
            <button class="btn btn-info btn-sm waves-effect waves-light" onclick="location.reload();">Показати</button>
        </div> <!-- Close col-md-12 -->


        <div class="col-md-12 gallery" id="gallery">

            <hr>

            <br><br>


        </div> <!-- Close col-md-12 -->

    </div>  <!-- Close container -->

@endsection


@section('footer')

    <script  src="{{ asset('src/public/js/libs/dropzone.js') }}"></script>

    <script>

        Dropzone.options.addProductImages = {
            paramName: 'photo',
            maxFilesize: 10,
            maxFiles: 12,
            acceptedFiles: '.jpg, .jpeg, .png'
        }

    </script>

    <script>
        if (!$('#image_row').length) {
            $('#gallery').hide();
        }
    </script>



@endsection