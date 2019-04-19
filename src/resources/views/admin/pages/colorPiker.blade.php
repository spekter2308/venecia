@extends('admin.dash')

@section('content')
    <style>
        form.colorBlocks {
            display: inline-block;
        }

        div a.confirm-delete{
            color: #FF0000;
        }

        .colorBlock {
            margin-left: 3px;
            border: solid 2px white;
            box-shadow: 0 0 0 1px #bbb;
            width: 78px;
            height: 50px;
            overflow: hidden;
            display: inline-block;
            margin-bottom: 8px;
        }

        .deleteBtn {
            background-color: transparent;
            color: #535353;
            border: none;
        }

        .deleteBtn:hover {
            background-color: rgba(219, 219, 219, 1);
        }
    </style>
    <div class="container">

        <br><br>
        <a href="#menu-toggle" class="btn btn-default" id="menu-toggle"><i class="fa fa-bars fa-5x"></i></a>
        <a href="{{ url('admin/dashboard') }}" class="btn btn-danger">Назад</a>
        <br><br>
        <div class="col-md-12">


            <h3 class="text-center">Додати колір до палітри</h3>

            <form method="POST" action="{{url('admin/colorPiker')}}" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input type="text" name="color" class="jscolor form-control" id="colorpicker">
                <br>
                <label for="name">Назва кольору</label>
                <input type="text" name="name" class="form-control" >
                <div class="row">
                    <input type="submit" class="btn btn-success pull-right" value="Додати">
                </div>
            </form>

            {{--<button class="btn btn-info btn-sm waves-effect waves-light" onclick="location.reload();">Показати</button>--}}
        </div> <!-- Close col-md-12 -->

            @foreach($colors as $color)
                <div class="row colors">
                        {{ csrf_field() }}
                        <div class="col-md-3">
                            <input type="text" name="name" class="form-control" value="{{$color->name}}">
                        </div>
                        <div class="col-md-1">
                            <input type="hidden" name="id"  value="{{$color->id}}">
                            <input type="text" name="color" class="colorBlock jscolor form-control" value="{{$color->color}}">
                            {{--<input type="submit" class="deleteBtn pull-right" value="x">--}}
                        </div>
                        <div class="col-md-3">
                            <a href="{{asset("admin/colorPiker/update/".$color->id)}}"
                               class="confirm-quantity" style="color: green"><i class="fa fa-check"></i></a>
                            <a href="{{asset("admin/colorPiker/delete/".$color->id) }}"
                               class="confirm-delete"><i class="fa fa-close"></i></a>
                        </div>
                </div>
            @endforeach

    </div>  <!-- Close container -->




@endsection


@section('footer')

    <script>


        $('.confirm-quantity').click(function () {
            var color = $(this).parents('div.colors').find('input[name=color]').val();
            var id = $(this).parents('div.colors').find('input[name=id]').val();
            var name = $(this).parents('div.colors').find('input[name=name]').val();
            var link = $(this).attr('href');

            $.ajax({
                method: "POST",
                url: link,
                data: {
                    _token: '{{ csrf_token() }}',
                    color: color,
                    id: id,
                    name: name
                 },
                })
                .done(function () {
                swal("Успіх', 'Колір змінено!", "success");
            });

            return false;
        });
        $('.confirm-delete').click(function () {

            var id = $(this).parents('div.colors').find('input[name=id]').val();
            var rowToDelete = $(this);
            var link = $(this).attr('href');
            swal({
                title: "Видалити колір?",
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
                        id: id
                    },
                })
                        .done(function () {
                            rowToDelete.parents('div.colors').fadeOut();
                            swal("Успіх!", "Ваш колір успішно видаленний.", "success");
                        });

            });

            return false;
        });


    </script>

    <script  src="{{ asset('src/public/js/libs/jscolor.js') }}"></script>

@endsection