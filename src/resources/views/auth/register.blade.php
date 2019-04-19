@extends('app')

@section('content')

<section class="loginform">
    <main>
        <img src="/src/public/img/logo_nobg.png" class="img__anim" alt="Venezia" />

        <form role="form" method="POST" action="{{ route('auth.register') }}" id="form">
            {!! csrf_field() !!}


            <input type="email" class="login" name="email" value="{{ old('email') }}" placeholder="{{trans('messages.mail')}}" id="email">
            @if ($errors->has('email'))
            <span class="help-block">
            <strong>{{ $errors->first('email') }}</strong>
            </span>
            @endif

            <input type="text"  class="login" name="username" value="{{ old('username') }}" placeholder="{{trans('messages.userName')}}" id="login">
            @if ($errors->has('username'))
                <span class="help-block">
            <strong>{{ $errors->first('username') }}</strong>
            </span>
            @endif

            <input type="password" class="password" name="password" placeholder="Пароль"  id="password">
            @if ($errors->has('password'))
            <span class="help-block">
            <strong>{{ $errors->first('password') }}</strong>
            </span>
            @endif


            <input type="password"  class="password" name="password_confirmation" placeholder="{{trans('messages.confirmPassword')}}">
            @if($errors->has('password_confirmation'))
                <span class="help-block">
            <strong>{{ $errors->first('password_confirmation') }}</strong>
            </span>
            @endif

            <input type="submit" value="{{trans('messages.register')}}" class="submit" id="submit"/>
        </form>
    </main>
</section>



<script  src="{{ asset('src/public/js/libs/jquery.js') }}"></script>
<script src="{{ asset('src/public/js/libs/validation.js') }}"></script>

@endsection