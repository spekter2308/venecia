@extends('app')

@section('content')


    <section class="loginform">
        <main>
            <img src="/src/public/img/logo_nobg.png" class="img__anim" alt="Venezia"/>

            <form method="POST" action="{{ route('auth.login') }}" id="form">
                {!! csrf_field() !!}

                <input type="email" name="email" value="{{ old('email') }}" class="login"
                       placeholder="{{trans('messages.mail')}}"/>
                @if ($errors->has('email'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif

                <input type="password" name="password" class="password" value="{{ old('email') }}" placeholder="Пароль"/>
                @if ($errors->has('password'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif

                <div class="checkbox">
                    <input type="checkbox" name="remember" id="remember"/>
                    <label for="remember">{{trans('messages.rememberMe')}}</label>
                </div>
                <input type="submit" value="{{trans('messages.enter')}}" class="submit"/>
            </form>
            <a href="{{ url('/register') }}" class="forgot">{{trans('messages.registration')}}</a>
            <a href="{{ url('password/email') }}" class="forgot">{{trans('messages.forgetPassword')}}</a>
        </main>

    </section>
   <script  src="{{ asset('src/public/js/libs/jquery.js') }}"></script>

   <script>
       'use strict';
       $(function () {
           let windowH = $(window).height();
           let headerH = $('header').height();
           let height = windowH - headerH -10;
           $('main').css('min-height', height + 'px').css('overflow', 'hidden');
       })

       $(function () {
           $('.img__anim').addClass('animated fadeInDown').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
               $(this).removeClass('animated fadeInDown');
           });
           $('#form').addClass('animated bounceIn').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
               $(this).removeClass('animated bounceIn');
           });
       })
   </script>
@endsection
