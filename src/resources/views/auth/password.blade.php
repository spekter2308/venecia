@extends('app')
@section('content')

    <script  src="{{ asset('src/public/js/libs/sweetalert.js') }}"></script>
    <section class="loginform">
        <main>
            <img src="/src/public/img/logo_nobg.png" class="img__anim" alt="Venezia"/>
            <form role="form" method="POST"  action="{{ url('/password/email') }}" id="form">
                {!! csrf_field() !!}
                @if(session('status'))
                    <script>
                        swal("Успіх!", "{{ session('status') }}", "success")
                    </script>
                @endif
                <input type="email"  name="email" value="{{ old('email') }}"   class="login" placeholder="{{trans('messages.mail')}}"/>
                @if ($errors->has('email'))
                    <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                @endif
                <input type="submit" value="{{trans('messages.resetPassword')}}" class="submit"/>
            </form>
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