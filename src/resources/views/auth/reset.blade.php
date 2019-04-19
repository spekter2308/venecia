@extends('app')

@section('content')

    <section class="loginform">
        <main>
            <img src="/src/public/img/logo_nobg.png" class="img__anim" alt="Venezia"/>

            <form role="form" method="POST" action="{{ url('/password/reset') }}" id="form">
                <input type="hidden" name="token" value="{{ $token }}">
                 {!! csrf_field() !!}

                <input type="email" name="email" class="login" value="{{ $email or old('email') }}" placeholder="Ваша пошта" id="email"/>
                @if ($errors->has('email'))
                <span class="help-block">
                <strong>{{ $errors->first('email') }}</strong>
                </span>
                @endif
                 <input type="password" name="password" id="password" class="password"  placeholder="Пароль">
                @if ($errors->has('password'))
                            <span class="help-block">
                    <strong>{{ $errors->first('password') }}</strong>
                            </span>
                @endif


                <input type="password" class="password" name="password_confirmation"
                       placeholder="Повторіть пароль">
                @if ($errors->has('password_confirmation'))
                    <span class="help-block">
                <strong>{{ $errors->first('password_confirmation') }}</strong>
                </span>
                @endif

                <input type="submit" value="Змінити пароль" class="submit" id="submit"/>
            </form>
        </main>
    </section>
    <script  src="{{ asset('src/public/js/libs/jquery.js') }}"></script>


    <script>
        'use strict';
            let headerH = $('header').height();
            let height = windowH - headerH -10;
            $(function () {
                let windowH = $(window).height();
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