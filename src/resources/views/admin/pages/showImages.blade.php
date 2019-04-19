@extends('admin.dash')

@section('content')

    <div class="container">

        <br><br>
        <a href="#menu-toggle" class="btn btn-default" id="menu-toggle"><i class="fa fa-bars fa-5x"></i></a>
        <a href="{{ url('admin/dashboard') }}" class="btn btn-danger">Назад</a>
        <br><br>
        <div class="col-md-12">
            <h3 class="text-center">Фото для слайдера</h3><br>

            <form method="POST" action="/admin/addMainImages" class="dropzone" id="addProductImages"
                  enctype="multipart/form-data">
                {{ csrf_field() }}
            </form>
            <div class="col-md-12 gallery">
                @if(count($allMainImages)>0)
                    @foreach ($allMainImages as $photo)
                    <div class="col-md-6" id="image_row">

                        <div class="col-xs-6 col-sm-3 col-md-3 gallery_image">
                            <label>{{ $photo->id }}</label>
                            @if (Auth::user()->id == 2)
                                <a href="/{{ $photo->path }}" data-lity>
                                    <img src="/{{ $photo->thumbnail_path }}" alt="" data-id="{{ $photo->id }}">
                                </a>
                            @else
                                <div class="img-wrap">
                                    <form method="post" action="/admin/image/photos/{{ $photo->id }}">
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

                        <form method="post" action="/admin/image/photos/category">
                            {!! csrf_field() !!}
                            <input type="hidden" name="photoId" value="{{ $photo->id }}">
                            <select class="form-control" id="sel1" name="category">
                                @foreach($categories as $category)
                                    @foreach($category->children as $children)
                                        @if($children->id == $photo->category_id)
                                            <option value="{{$children->id}}" selected>{{$category->category}} - {{$children->category}}</option>
                                        @else
                                            <option value="{{$children->id}}" >{{$category->category}} - {{$children->category}}</option>
                                        @endif
                                    @endforeach
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

            <h4>Редагування фото</h4>


            <div class="grid-stack-custom">

                <div class="grid-stack  " data-gs-width="12" data-gs-animate="no" data-gs-no-resize="true" data-gs-no-move="true"
                     data-gs-current-height="64" >

                    @if(count($allMainImages)>0)
                        @foreach ($allMainImages as $photo)
                            <div class="grid-stack-item"
                                 @if($photo->option)
                                    @php $option = json_decode($photo->option,true) @endphp
                                     data-gs-x="{{$option['data-gs-x']  }}"
                                     data-gs-y="{{$option['data-gs-y']  }}"
                                     data-gs-width="{{$option['data-gs-width']  }}"
                                     data-gs-height="{{$option['data-gs-height'] }}"
                                     data-id="{{$option['data-id']  }}"

                                 @else
                                     data-gs-x="0"
                                     data-gs-y="0"
                                     data-gs-width="21"
                                     data-gs-height="2"
                                     data-id="{{$photo->id}}"

                                 @endif

                                    >
                                <div class="grid-stack-item-content">
                                    <img class="response_img" src="/{{ $photo->path }}" alt=""
                                         data-id="{{ $photo->id }}">
                                </div>
                            </div>
                        @endforeach
                    @endif

                </div>



            </div>
            <button class="btn btn-info btn-sm waves-effect waves-light" style="float: right" id="sendImgAttributes">Зберегти позиції</button>
            <div style="margin-bottom: 250px"></div>

        </div> <!-- Close col-md-12 -->



    </div>  <!-- Close container -->

@endsection


@section('footer')


{{--    <script  src="{{asset('src/public/js/plugins/gridstack.js')}}"></script>--}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gridstack.js/0.4.0/gridstack.min.js"></script>
    {{--<script  src="{{asset('src/public/js/plugins/gridstack.jQueryUI.js')}}"></script>--}}
    <script  src='//cdnjs.cloudflare.com/ajax/libs/gridstack.js/0.4.0/gridstack.jQueryUI.min.js'></script>
    <script>
        var options = {
         
         cellHeight:  Math.round($('.grid-stack-custom').width() / 18 ),
            verticalMargin: 10


        };
        $('.grid-stack').gridstack(options);



      

    </script>
    <script>





        $("#sendImgAttributes").click(function(){

            function getAttributes ( $node ) {

                var attributess = [];

                for(var i=0 ; i<$node.length ; i++){
                    var attrs = {};
                    $.each( $node[i].attributes, function ( index, attribute ) {
                        attrs[attribute.name] = attribute.value;

                    } );

                    attributess[i] = attrs;
                }


                return attributess;
            }

            var attrs = getAttributes($('.grid-stack-item'));


            $.ajax({
                method: "POST",
                url: '{{route('imgAttributes')}}',
                data: {
                    _token: '{{ csrf_token() }}',
                    imgAtributes: attrs
                }
            }) .success(function (){
                swal("Успіх!", "Позиції змінено", "success");
            });

        })

    </script>

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