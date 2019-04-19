<?php
    $url = Request::path();
    $url = preg_replace("/^ru\//", "", $url);
    $url = preg_replace("/^ru/", "", $url);

    $full_url = Request::fullUrl();
    //dd($url);
?>
<!-- Footer -->
<footer>
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-md-3 bottom">
                <img src="/src/public/img/logo.jpg" class="logo" title="Магазин Venezia" alt="Магазин Venezia"/>
            </div>
            <div class="col-xs-12 col-md-6 bottom">
                <div class="sunscribe-block">
                <p>{{ trans('messages.submitOnUs') }}</p>
                <form action="{{ route('subscribe.user') }}" id="subscribe_form" method="POST">
                    <div class="input__group">
                        {{ csrf_field() }}
                        <input type="text" placeholder="{{ trans('messages.your_email') }}" value="{{ old('email') }}" name="email" style="color: black"/>
                        <a href="javascript:;" id="subscribe_submit">
                            {{ trans('messages.submit') }}
                        </a>
                    </div>
                </form>
                </div>
            </div>
            <div class="col-xs-12 col-md-3 bottom">
                <div class="sosial-block">
                    <p>{{ trans('messages.inSocialNetworks') }}</p>
                    <p>
                        <a rel="nofollow" href="https://facebook.com/veneziaukraine1/?ref=bookmarks" class=""><span class="social-icon"><i class="fab fa-facebook-f"></i></span></a>
                        <a rel="nofollow" href="https://www.instagram.com/accounts/edit/?hl=ru" class=""><span  class="social-icon"><i class="fab fa-instagram"></i></span></a>
                    </p>
                </div>
            </div>
            <div class="col-xs-12 col-md-12">
                <div class="row cards">
                    @if( App::getLocale() == 'ua')
                        <div class="col-xs-12 col-sm-4 col-md-2 col-md-offset-1">
                            <img src="/src/public/img/Icons-04.svg" alt="icon">
                            <p>Безкоштовна<br>доставка</p>
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-2">
                            <img src="/src/public/img/Icons-01.svg"  alt="icon">
                            <p>Можливість<br>повернення<br>та обміну</p>
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-2">
                            <img src="/src/public/img/Icons-02.svg" alt="icon">
                            <p>On-line<br>консультація</p>
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-2 ">
                            <img src="/src/public/img/Icons-03.svg" alt="icon">
                            <p>Завжди<br>в тренді</p>
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-2">
                            <img src="/src/public/img/Icons-05.svg" alt="icon">
                            <p>Гарантія<br>якості</p>
                        </div>
                    @else
                        <div class="col-xs-12 col-sm-4 col-md-2 col-md-offset-1">
                            <img src="/src/public/img/Icons-04.svg" alt="icon">
                            <p>Бесплатная<br>доставка</p>
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-2">
                            <img src="/src/public/img/Icons-01.svg" alt="icon">
                            <p>Возможность<br>возвращения<br>и обмена</p>
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-2">
                            <img src="/src/public/img/Icons-02.svg" alt="icon">
                            <p>On-line<br>консультация</p>
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-2 ">
                            <img src="/src/public/img/Icons-03.svg" alt="icon">
                            <p>Всегда<br>в тренде</p>
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-2">
                            <img src="/src/public/img/Icons-05.svg">
                            <p>Гарантия<br>качества</p>
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-xs-12 col-md-12">
                <ul>
                {{--<li class="{{($url == '/' || $url == '')? "active" : "" }}"><a href="{{ url('/') }}">{{trans('messages.mainPage')}}</a></li>--}}
                {{--<li class="{{($url == 'page/contacts')? "active" : "" }}"><a href="{{url('page/contacts')}}">{{trans('messages.contacts')}}</a></li>--}}
                {{--<li class="{{($url == 'favorites')? "active" : "" }}"><a href="{{url('favorites')}}">{{trans('messages.favorites')}}</a></li>--}}
                {{--<li class="{{($url == 'page/about-us')? "active" : "" }}"><a href="{{url('page/about-us')}}">{{trans('messages.aboutUs')}}</a></li>--}}
                {{--<li class="{{($url == 'page/guarantee')? "active" : "" }}"><a href="{{url('page/guarantee')}}">{{trans('messages.guarantee')}}</a></li>--}}
                {{--<li class="{{($url == 'page/payment-and-delivery')? "active" : "" }}"><a href="{{url('page/payment-and-delivery')}}">{{trans('messages.paymentAndDelivery')}}</a></li>--}}
                    <li class="{{($url == '/' || $url == '')? "active" : "" }}">
                        <a href="{{ ($url == "/" || $url == "") ? "javasript:;" : url('/') }}" class="{{ ($url == "/" || $url == "") ? "no-cursor" : "" }}">{{trans('messages.mainPage')}}</a>
                    </li>

                    <li class="{{($url == 'page/contacts')? "active" : "" }}">
                        <a href="{{ ($full_url == route('showPage', ['contacts']) ) ? "javasript:;" : route('showPage', ['contacts']) }}" class="{{ ($full_url == route('showPage', ['contacts']) ) ? "no-cursor" : "" }}">{{trans('messages.contacts')}}</a>
                    </li>

                    <li class="{{($url == 'favorites')? "active" : "" }}">
                        <a href="{{ ($full_url == route('page.favorites') ) ? "javasript:;" : route('page.favorites') }}" class="{{ ($full_url == route('page.favorites') ) ? "no-cursor" : "" }}">{{trans('messages.favorites')}}</a>
                    </li>

                    <li class="{{($url == 'page/about-us')? "active" : "" }}">
                        <a href="{{ ($full_url == route('showPage', ['about-us']) ) ? "javasript:;" : route('showPage', ['about-us']) }}" class="{{ ($full_url == route('showPage', ['about-us']) ) ? "no-cursor" : "" }}">{{trans('messages.aboutUs')}}</a>
                    </li>

                    <li class="{{($url == 'page/guarantee')? "active" : "" }}">
                        <a href="{{ ($full_url == route('showPage', ['guarantee']) ) ? "javasript:;" : route('showPage', ['guarantee']) }}" class="{{ ($full_url == route('showPage', ['guarantee']) ) ? "no-cursor" : "" }}">{{trans('messages.guarantee')}}</a>
                    </li>

                    <li class="{{($url == 'page/payment-and-delivery')? "active" : "" }}">
                        <a href="{{ ($full_url == route('showPage', ['payment-and-delivery']) ) ? "javasript:;" : route('showPage', ['payment-and-delivery']) }}" class="{{ ($full_url == route('showPage', ['payment-and-delivery']) ) ? "no-cursor" : "" }}">{{trans('messages.paymentAndDelivery')}}</a>
                    </li>
                </ul>
            </div>
            <div class="copyrights col-xs-12 col-md-12">
                @if( App::getLocale() == 'ua')
                    м. Рівне, вул. Кн.Ольги, 1, ТК “Покровський”
                @else
                    г. Ровно, ул. Кн.Ольги, 1, ТК “Покровский”
                @endif
            </div>
            <div class="copyrights col-xs-12 col-md-12">
                    +38 097 100 78 09 | +38 095 928 30 70 | venezia-rivne@ukr.net
            </div>
            <div class="copyrights col-xs-12 col-md-12">
                <span>Copyright @ 2016 <a href="http://softgroup.ua" rel="nofollow" target="_blank">SoftGroup</a></span>
            </div>
        </div>
    </div>
</footer>
<script  src="{{ asset('src/public/js/libs/jquery.js') }}"></script>

<script>

    $('#subscribe_submit').click(function () {
        $('#subscribe_form').submit();
    })
</script>