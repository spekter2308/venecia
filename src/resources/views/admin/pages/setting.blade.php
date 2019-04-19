@extends('admin.dash')

@section('content')

    <div class="container" id="admin-category-container">

        <br><br>
        <a href="#menu-toggle" class="btn btn-default" id="menu-toggle"><i class="fa fa-bars fa-5x"></i></a>
        <a href="{{ url('admin/dashboard') }}" class="btn btn-danger">Назад</a>
        <br><br>

        <div class="col-sm-8 col-md-9" id="admin-category-container">
            <ul class="collection with-header">
                <form role="form" method="POST" action="{{ route('admin.pages.settings') }}">
                    {{ csrf_field() }}

                    <li class="collection-item">
                        <label for="sale">Назва скидки</label>
                        <div class="form-group{{ $errors->has('sale') ? ' has-error' : '' }}">
                            <input type="text" class="form-control" name="sale" value="{{  Request::old('sale') ? : $shop_settings->sale }}" placeholder="Назва скидки">
                            @if($errors->has('sale'))
                                <span class="help-block">{{ $errors->first('sale') }}</span>
                            @endif
                        </div>
                    </li>
                    <li class="collection-item">
                        <label for="new">Назва нового товару</label>
                        <div class="form-group{{ $errors->has('new') ? ' has-error' : '' }}">
                            <input type="text" class="form-control" name="new" value="{{  Request::old('new') ? : $shop_settings->new }}" placeholder="Назва нового товару">
                            @if($errors->has('new'))
                                <span class="help-block">{{ $errors->first('new') }}</span>
                            @endif
                        </div>
                    </li>
                    <li class="collection-item">
                        <label for="time_new">Час нового товару</label>
                        <div class="form-group{{ $errors->has('time_new') ? ' has-error' : '' }}">
                            <select  class="form-control"  name="time_new">
                                @for($i = 0; $i<count($days); $i++)
                                    <option value="{{86400 * $i}}" {{86400 * $i == $shop_settings->time_new ? 'selected' : ''}}>{{$days[$i]}}</option>
                                @endfor

                            </select>


                        </div>

                    </li>
                    <li class="collection-item blue">
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-link grey lighten-5">Зберегти налаштування</button>
                        </div>
                    </li>
                </form>
            </ul>
        </div>

    </div>

@endsection



