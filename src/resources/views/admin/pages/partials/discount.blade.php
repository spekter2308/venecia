<div class="row">
    @if(isset($shop_settings->discount))
        <div class="col-md-6 "><h4 id="discount-view" >Знижка : {{$shop_settings->discount.' %'}} </h4></div>
    @endif
        <div class="col-md-2 col-md-offset-4">
            <a href="{{asset("/admin/dashboard/shopSettings")}}" id="edit-discount"> <i class="fa fa-pencil  fa-2x" aria-hidden="false"></i></a>
        </div>
</div>
<div class="row" style="display: none" id="edit-discount-form">
    <div class="col-md-6">
        {!! csrf_field() !!}
        <input type="hidden" name="_method" value="PATCH">
            @if(isset($shop_settings->discount))
                <input type="text" id="discountVal" class="form-control" min="1" max="100" value="{{$shop_settings->discount}}">
             @else
                <input type="text" id="discountVal" class="form-control" value="0.00">
            @endif
    </div>
    <div class="col-md-2 col-md-offset-4">
        <a href="#" id="confirm-discount"> <i class="fa fa-check-circle fa-2x" aria-hidden="true"></i></a>
    </div>
</div>
<hr>
<div class="row">
    @if(isset($shop_settings->code))
        <div class="col-md-6 "><h4 id="code-view" >Код : {{$shop_settings->code}} </h4></div>
    @endif
        <div class="col-md-2 col-md-offset-4">
        <a href="{{asset("/admin/dashboard/shopSettings")}}" id="edit-code"> <i class="fa fa-pencil  fa-2x" aria-hidden="false"></i></a>
    </div>
</div>
<div class="row" style="display: none" id="edit-code-form">
    <div class="col-md-6">
            {!! csrf_field() !!}
            @if(isset($shop_settings->code))
                <input type="text" id="codeVal" class="form-control" value="{{$shop_settings->code}}">
            @else
                <input type="text" id="codeVal" class="form-control" value="0.00">
            @endif
    </div>
    <div class="col-md-2 col-md-offset-4">
        <a href="#" id="confirm-code"> <i class="fa fa-check-circle fa-2x" aria-hidden="true"></i></a>
    </div>
</div>
<hr>
<div class="row text-center">
    <input id="toggleButton" type="checkbox" checked data-toggle="toggle" data-on="Увімкнути" data-off="Вимкнути"
           data-onstyle="success" data-offstyle="danger">
</div>
<script  src="{{ asset('src/public/js/libs/jquery.js') }}"></script>
<link rel="stylesheet" href="{{ asset('src/public/css/bootstrap-toggle.css') }}">
<script  src="{{ asset('src/public/js/libs/bootstrap-toggle.min.js') }}"></script>
<script>
    @if(isset($shop_settings->discount_state))
    var _state =  "{{$shop_settings->discount_state}}";
    // we revert state in database if 0 we display false
    var _stateRev = _state == 0 ? 1: 0;
        $('#toggleButton').prop('checked',_stateRev);
    @endif
    $('#toggleButton').change(function(){
      var state = $(this).prop('checked');
        var link = $('#edit-discount').attr('href');
        $.ajax({
            method: "POST",
            url: link,
            data:{
                _token:'{{ csrf_token()}}',
                state: state,
            },
        })
               .done(function (data){
                      if(data == true){
                          swal("Успіх!", "Знижку увімкнено!", "success");
                      }else {
                          swal("Успіх!", "Знижку вимкнено!", "success");
                      }
                    });
    });
$('#edit-discount').click(function(){
    $('#edit-discount-form').fadeToggle();
    return false;
});
    $('#confirm-discount').click(function(){
        var discount = $('#discountVal').val();
        var link = $('#edit-discount').attr('href');
        $.ajax({
            method: "POST",
            url: link,
            data: {
                _token: '{{ csrf_token() }}',
                discount: discount,
            },
        })
                .done(function () {
                    var result = "Знижка : "+discount+" %";
                    $('#discount-view').text(result);
                    swal("Успіх!", "Знижку зміненно!", "success");
                });
        return false;
    });
$('#edit-code').click(function(){
    $('#edit-code-form').fadeToggle();
    return false;
});
$('#confirm-code').click(function(){
    var code = $('#codeVal').val();
    var link = $('#edit-code').attr('href');
    $.ajax({
        method: "POST",
        url: link,
        data: {
            _token: '{{ csrf_token() }}',
            code: code,
        },
    })
            .done(function () {
                var result = "Код : "+code;
                $('#code-view').text(result);
                swal("Успіх!", "Код зміненно!", "success");
            });
    return false;
});
</script>