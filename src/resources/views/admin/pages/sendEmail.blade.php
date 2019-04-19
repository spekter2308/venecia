@extends('admin.dash')
@section('content')
    <div class="container">
       <div class="row">
           <h1 style="text-align: center">Лист</h1>
           <form role="form" method="POST" action="{{ url('admin/email') }}">
               {{ csrf_field() }}

               <div class=" col-md-8 col-md-offset-2 ">
                   <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                       <label for="title"><h5>Заголовок листа:</h5></label>
                       <input id="title" type="text" class="form-control" name="title" value="{{ old('title') }}" placeholder="Заголовок">
                       @if($errors->has('title'))
                           <span class="help-block">{{ $errors->first('title') }}</span>
                       @endif
                   </div>
               </div>

               <div class=" col-md-8 col-md-offset-2 ">
                   <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                       <label for="content"><h5>Контент листа:</h5></label>
                       <textarea id="content-box" name="content" class="form-control" rows="20" ></textarea>
                   </div>
               </div>

               <div class="form-group col-sm-8 col-md-8 col-md-offset-2 text-center">
                   <button type="submit" class="btn btn-primary waves-effect waves-light">Відправити листа</button>
               </div>

           </form>
       </div>
    </div>


@endsection

@section('footer')
        <!-- Include Froala Editor JS files. -->
    <script  src="{{ asset('src/public/js/libs/froala_editor.min.js') }}"></script>
    <script>
        $(function() {
            $('#content-box').froalaEditor({
                charCounterMax: 2500,
                height: 500,
                codeBeautifier: true,
                placeholderText: 'Контент для листа...',
            })
        });
    </script>
@endsection