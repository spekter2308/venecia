@extends('admin.dash')

@section('content')


    <div id="page-content-wrapper">
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
            <label>Імпорт csv файлу з 1С</label>
            <input type="file" name="file"></br>
            <button type="submit">Імпорт</button>
        </form>
    </div>




    {{--<div class="row">--}}
        {{--<div class="col-sm-7 col-xs-12">--}}
            {{--<form role="form" id="fileForm" target="uploadFrame" method="post" enctype="multipart/form-data" action="loadImage.php?task=file" onsubmit="return ZM.loadImage(this)">--}}
                {{--<div class="input-group input-group-sm">--}}
			{{--<span class="input-group-btn">--}}
				{{--<span class="btn btn-gray btn-file">--}}
				{{--Browse… <input id="OneSfile" type="file" name="file" accept="image/*">--}}
				{{--</span>--}}
			{{--</span>--}}
                    {{--<input type="text" class="form-control" placeholder="Choose file" readonly >--}}
			{{--<span class="input-group-btn">--}}
				{{--<button class="btn btn-gray" type="submit">Ok</button>--}}
			{{--</span>--}}
                {{--</div>--}}
                {{--<br />--}}
                {{--<br />--}}
            {{--</form>--}}
        {{--</div>--}}
    {{--</div>--}}
@endsection

@section('footer')

    {{--<script>--}}
        {{--$("#btn1").click(function(){--}}
            {{--alert("Text: " + $("#OneSfile").text());--}}
        {{--});--}}
    {{--</script>--}}

@endsection

