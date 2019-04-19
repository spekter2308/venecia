@extends('admin.dash')
@section('content')
    <div class="container-fluid">
        <div class="edit-review">
            <h3>Редагування відгука:</h3>
            <form id="edit-review" class="send-review-form">
                <input type="hidden" name="product_id" value="">
                <input type="hidden" name="target" value="">
                <div class="form-group">
                    <label for="usr">{{trans('messages.name')}}:</label>
                    <input type="text" class="form-control" name="name">
                </div>
                <div class="form-group hidden error name-error"></div>
                <div class="form-group">
                    <label for="comment">{{trans('messages.message')}}:</label>
                    <textarea class="form-control" rows="5" name="message" maxlength="1000"></textarea>
                </div>
                <div class="form-group hidden error message-error"></div>
                <button type="submit" class="btn btn-default">{{trans('messages.send')}}</button>
            </form>
        </div>
        <div>
            <h3>Масова загрузка відгуків:</h3>
            <form id="load-excel-form" enctype="multipart/form-data">
                {!! csrf_field() !!}
                <div class="form-group">
                    <label for="excel"></label>
                    <input type="file" name="excel">
                </div>
                <div class="form-group error excel-error"></div>
                <button type="submit" class="btn btn-default">{{trans('messages.send')}}</button>
            </form>
        </div>
        <div class="reviews-list">
            <h3>Список відгуків:</h3>
            <table id="users" class="table table-hover table-condensed" style="width:100%">
                <thead>
                <tr>
                    <th>Посилання</th>
                    <th>Ім'я</th>
                    <th>Повідомлення</th>
                    <th>Дата</th>
                    <th>Дія</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>


@endsection

@section('footer')
    <script >
        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            //Show datatable
            var oTable = $('#users').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{route('admin.pages.reviews.get')}}',
                columns: [
                    {data: 'product_sku', name: 'product_sku'},
                    {data: 'name', name: 'name'},
                    {data: 'message', name: 'message'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                initComplete: function( settings, json ) {
                    $('div.loading').remove();
                }
            });

            // Handle events after datatable is drawed
            oTable.on( 'draw', function () {
                $(".review-delete").click(function () {
                    var id = $(this).attr('target');
                    $.ajax({
                        method: "DELETE",
                        url: '{{route('admin.pages.reviews.delete')}}',
                        data: {
                            _token: '{{ csrf_token() }}',
                            id: id
                        },
                    })
                        .success(function (response) {
                            oTable.ajax.url( "{{route('admin.pages.reviews.get')}}" ).load();
                        })
                        .error(function (response) {
                            oTable.ajax.url( "{{route('admin.pages.reviews.get')}}" ).load();
                        });
                });
                $(".review-edit").click(function () {
                    var id = $(this).attr('target');
                    var product_id = $(this).attr('product_id');
                    var name = $(this).attr('name');
                    var message = $(this).attr('message');

                    $('#edit-review [name=name]').val(name);
                    $('#edit-review [name=message]').val(message);
                    $('#edit-review [name=product_id]').val(product_id);
                    $('#edit-review [name=target]').val(id);

                    $('.edit-review').show();
                    $(window).scrollTop(0);
                });
            } );


            //Handle edit form submit
            $( "#edit-review" ).submit(function( event ) {
                var id = $('#edit-review [name=target]').val();
                var product_id = $('#edit-review [name=product_id]').val();
                var name = $('#edit-review [name=name]').val();
                var message = $('#edit-review [name=message]').val();
                var captcha = $('#edit-review [name=captcha]').val();
                $(".message-error").hide();
                $(".name-error").hide();
                $(".captcha-error").hide();
                $.ajax({
                    method: "PUT",
                    url: '{{route('admin.pages.reviews.update')}}',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id,
                        product_id: product_id,
                        name: name,
                        message: message,
                        captcha: captcha
                    },
                })
                    .success(function (response) {
                        $('#edit-review [name=name]').val("");
                        $('#edit-review [name=message]').val("");
                        $('.edit-list').prepend(response);
                        $('.edit-review').hide();
                        oTable.ajax.url( "{{route('admin.pages.reviews.get')}}" ).load();
                    })
                    .error(function (response) {
                        console.log(response);
                        console.log(response.responseJSON);
                        var json = response.responseJSON;
                        if (json.hasOwnProperty('name')) {
                            var text = json.name.join(" " );
                            $(".name-error").html(text);
                            $(".name-error").show();
                        }
                        if (json.hasOwnProperty('message')) {
                            var text = json.message.join(" " );
                            $(".message-error").html(text);
                            $(".message-error").show();
                        }
                    });
                event.preventDefault();
            });

            //habdle load excel form submit
            $("#load-excel-form").submit(function(){
                var formData = new FormData($(this)[0]);
                $(".excel-error").hide();
                $(".excel-error").removeClass("text-danger");
                $(".excel-error").removeClass("text-success");
                $.ajax({
                    url: "{{route('admin.pages.reviews.excel')}}",
                    method: 'POST',
                    data: formData,
                    cache: false,
                    processData: false,
                    contentType: false,
                })
                    .success (function (data) {
                        console.log(data);
                        var json = data;
                        if (json.hasOwnProperty('message')) {
                            var text = json.message;
                            $(".excel-error").html(text);
                            $(".excel-error").show();
                            $(".excel-error").addClass("text-success");
                        }
                        $('#load-excel-form')[0].reset();
                        oTable.ajax.url( "{{route('admin.pages.reviews.get')}}" ).load();
                    })
                    .error(function (data) {
                        var json = data.responseJSON;
                        if (json.hasOwnProperty('message')) {
                            var text = json.message;
                            $(".excel-error").html(text);
                            $(".excel-error").show();
                            $(".excel-error").addClass("text-danger");
                        }
                    });

                return false;
            });

        });
    </script>
@endsection