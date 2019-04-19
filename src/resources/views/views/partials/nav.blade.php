<!-- Navigation -->
@if(isset($discount->discount_state) && $discount->discount_state==1)
    <section class="pre-footer header">
        <div class="container row">
            <div class="col-md-6 col-sm-12 col-xs-12">
                <h2>{{trans('messages.discount', ['discount' => $discount->discount])}}</h2>

            </div>
            <div class="col-md-6 col-sm-12 col-xs-12 btn__container">
                <a href="{{url('/')}}" class="btn btn-secondary">{{trans('messages.buyNow')}}</a>
            </div>
        </div>
    </section>
@endif


<header class="container-fluid">
    <div class="pre__header">
        <span><a href="{{ url('/ua') }}">UA</a> | <a href="{{ url('/ru') }}">RU</a></span>
        <span class="red" id="free_shipping">
                        <strong>{{ trans('messages.delivery') }}</strong>
        </span>
        <div class="user__actions">
            <ul class="actions">
                <li class="triangle"></li>
                @if (!$signedIn)
                    <a href="{{ url('/login') }}">
                        <li>{{trans('messages.logIn')}}</li>
                    </a>
                    <a href="{{ url('/register') }}">
                        <li>{{trans('messages.registration')}}</li>
                    </a>
                @else
                    {{--<a href="{{ url('/profile') }}">--}}
                    {{--<li>Профіль</li>--}}
                    {{--</a>--}}
                    <a href="{{ url('/logout') }}">
                        <li>{{trans('messages.logOut')}}</li>
                    </a>

                @endif
            </ul>
            <img id="open__actions" src="/src/public/img/svg/person.svg" alt="Person" class="people"/>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 header">
            <aside class="header__logo"><a href="{{ url('/') }}"> <img src="/src/public/img/logo.jpg" alt="Logo"/></a>
            </aside>
            <aside class="header__menu">

                <nav class="header__nav">
                    <div class="menu_heading">
                        <img src="/src/public/img/svg/menu.svg" alt="Open menu" id="open_menu"
                             class="menu__icons"/>
                        <img src="/src/public/img/svg/identity.svg" alt="Identity" id="open__actions" class="menu__icons"/>
                    </div>
                    <ul class="main_menu">
                        @php
                            $urlToFixBug = Request::path();
                            if((strlen($urlToFixBug)>15)){
                             $urlToFixBug = substr($urlToFixBug,0,15);
                            }

                        @endphp
                        @if($urlToFixBug != "password/email" && $urlToFixBug!="password/reset/")
                            <li class="active"><a href="{{ url('/') }}">{{trans('messages.mainPage')}}</a></li>
                            {{--SECTION--}}
                            @foreach($categoryAll as $category)
                                <li><a href="#">{{ Lang::locale()=='ua' ? $category->category : $category->category_ru}}&#8195;<i class="fa fa-angle-down"
                                                                                 aria-hidden="true"></i></a>
                                    <ul>
                                        @foreach($category->children as $children )
                                            <li>
                                                <a href="{{ url('category', $children->id) }}">{{ Lang::locale()=='ua' ? $children->category : $children->category_ru}}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @endforeach
                        @endif
                        @if(Auth::user())
                            @if((Auth::user()->admin)==1 )
                                <li>
                                    <a href="{{ url('admin/dashboard') }}">
                                        Панель адміністратора
                                    </a>
                                </li>
                            @endif
                        @endif

                    </ul>
                </nav>
                <div id="search__form">
                    @include('pages.partials.search_box')
                </div>
                <div class="right_side">

                    <img src="/src/public/img/svg/search_icon.svg" alt="Search"
                         class="icon__search open__search_modal"/>
                    @if ($signedIn)
                        <a href="{{ route('cart')}}" class="header__cart_icon"><span>{{ $cart_count }} </span></a>
                    @endif
                </div>
                <div class="right_side icons">
                    <img src="/src/public/img/svg/search_icon.svg" alt="Search" class="menu__icons open__search_modal"/>
                    @if ($signedIn)
                        <a href="{{ route('cart')}}"><img src="/src/public/img/svg/shopping_cart.svg" alt="Shopping cart"
                                                          class="menu__icons"/>
                            <div>{{ $cart_count }}</div>
                        </a>
                    @endif
                </div>
            </aside>
        </div>
    </div>
</header>

<div class="menu__side" id="menu__side">
    <nav id="side__nav">
        <ul>
            @foreach($categoryAll as $category)
                @foreach($category->children as $children )
                    <li><a href="{{ url('category', $children->id) }}"> {{ $children->category }}</a></li>
                @endforeach
            @endforeach
        </ul>
    </nav>
    <div id="overlay"></div>
</div>



