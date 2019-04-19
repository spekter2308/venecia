<?php
    $url = Request::path();
    $url = preg_replace("/^ru\//", "", $url);
    $url = preg_replace("/^ru/", "", $url);

    $full_url = Request::fullUrl();
//    $full_url = preg_replace("/^ru\//", "", $full_url);
//    $full_url = preg_replace("/^ru/", "", $full_url);

//    dd($url);
?>
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
        @if (App::getLocale() == 'ua')
            <span><a href="javasript:;" class="no-cursor">UA</a> | <a href="{{ url('/ru/' . $url ) }}">RU</a></span>
        @else
            <span><a href="{{ url('/ua/' . $url ) }}">UA</a> | <a href="javasript:;" class="no-cursor">RU</a></span>
        @endif
        {{--<span><a href="{{ url('/ua/' . $url ) }}">UA</a> | <a href="{{ url('/ru/' . $url ) }}">RU</a></span>--}}
        <span class="hidden">(097) 100 78 09 / venezia-rivne@ukr.net</span>
        <span class="red hidden" id="free_shipping">
                        <strong>{{ trans('messages.delivery') }}</strong>
        </span>
        <div class="hidden" style="width: 380px;">
            @include('pages.partials.search_box')
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 header">
            <aside class="header__logo">
                <a href="{{ ($url == "/" || $url == "") ? "javasript:;" : url('/') }}" class="{{ ($url == "/" || $url == "") ? "no-cursor" : "" }}">
                    <img src="/src/public/img/logo.jpg"
                         alt="Магазин Venezia"
                         title="Магазин Venezia"
                    />
                </a>
            </aside>
            <aside class="header__menu">

                <nav class="header__nav">
                    <div class="menu_heading">
                        <img src="/src/public/img/svg/menu.svg" alt="Open menu" id="open_menu"
                             class="menu__icons"/>
                        @if(!Auth::user())

                            <img src="/src/public/img/svg/identity.svg" alt="Profile" id="open__actions" class="menu__icons"
                                 onclick="show_auth()"/>

                        @else

                            <a href="{{ url('/profile') }}" class="logout">{{trans('messages.profile')}}</a>
                        @endif


                        <ul class="actions-login" style="">
                            <li>
                                <a href="{{ url('/login') }}">
                                    Авторизація
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('/register') }}">
                                    Реєстрація
                                </a>
                            </li>
                        </ul>



                        <script>
                            show_auth_id = true;
                            function show_auth() {
                                show_auth_id ? $('.actions-login').show(400) : $('.actions-login').hide(300);
                                show_auth_id = !show_auth_id;
                            }
                        </script>
                    </div>
                    <ul class="main_menu">
                        @php
                            $urlToFixBug = Request::path();
                            if((strlen($urlToFixBug)>15)){
                             $urlToFixBug = substr($urlToFixBug,0,15);
                            }

                        @endphp



                        @if($urlToFixBug != "password/email" && $urlToFixBug!="password/reset/")
                            <li class="{{($url == '/' || $url == '')? "active" : "" }}">
                                <a href="{{ ($url == "/" || $url == "") ? "javasript:;" : url('/') }}" class="{{ ($url == "/" || $url == "") ? "no-cursor" : "" }}">{{trans('messages.mainPage')}}</a>
                            </li>

                            @if (!$signedIn)
{{--                                <li class="{{($url == 'login')? "active" : "" }}"><a href="{{ url('login') }}">{{trans('messages.logIn')}}</a></li>--}}
                                <li class="{{($url == 'login')? "active" : "" }}"><a href="{{ ($full_url == route('auth.login') ) ? "javasript:;" : route('auth.login') }}" class="{{ ($full_url == route('auth.login') ) ? "no-cursor" : "" }}">{{trans('messages.logIn')}}</a></li>
                            @endif

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


{{--                            <li class="{{($url == 'favorites')? "active" : "" }}"><a href="{{url('favorites')}}">{{trans('messages.favorites')}}</a></li>--}}
{{--                            <li class="{{($url == 'page/about-us')? "active" : "" }}"><a href="{{url('page/about-us')}}">{{trans('messages.aboutUs')}}</a></li>--}}
{{--                            <li class="{{($url == 'page/guarantee')? "active" : "" }}"><a href="{{url('page/guarantee')}}">{{trans('messages.guarantee')}}</a></li>--}}
{{--                            <li class="{{($url == 'page/payment-and-delivery')? "active" : "" }}"><a href="{{url('page/payment-and-delivery')}}">{{trans('messages.paymentAndDelivery')}}</a></li>--}}
                            @if ($signedIn)
{{--                                <li class="{{($url == 'profile')? "active" : "" }}"<a href="{{ url('login') }}"><a href="{{ url('profile') }}"> {{trans('messages.profile')}} </a></li>--}}
                                <li class="{{($url == 'profile')? "active" : "" }}"><a href="{{ ($full_url == route('profile') ) ? "javasript:;" : route('profile') }}" class="{{ ($full_url == route('profile') ) ? "no-cursor" : "" }}"> {{trans('messages.profile')}} </a></li>
                            @endif
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
                <div id="search__form" class="mobile">
                    @include('pages.partials.search_box')
                </div>
                <div class="right_side">

                    <img src="/src/public/img/svg/search_icon.svg" alt="search" class="icon__search open__search_modal"/>
                    {{--@if ($signedIn)--}}
                        <a href="{{ ($full_url == route('cart') ) ? "javasript:;" : route('cart') }}" class="header__cart_icon {{ ($full_url == route('cart') ) ? "no-cursor" : "" }}"><span>{{ $cart_count }} </span></a>
                    {{--@endif--}}
                </div>
                <div class="right_side icons">
                    <img src="/src/public/img/svg/search_icon.svg" alt="search" class="menu__icons open__search_modal"/>
                    @if ($signedIn)
                        <a href="{{ ($full_url == route('cart') ) ? "javasript:;" : route('cart') }}" class="{{ ($full_url == route('cart') ) ? "no-cursor" : "" }}">
                            <img src="/src/public/img/svg/shopping_cart.svg" alt="Shopping cart" class="menu__icons"/>
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
        @php
            $categoryId = (isset($categoryId)) ? $categoryId : null;
        @endphp
        <ul class="sidebar__menu">
            @foreach($categoryAll as $category)
                <?php
                $category_nested_depth = 1;
                $show_category = false;
                $show_subcategory = false;
                $show_sub_subcategory = false;

                if ($category->id == $categoryId) {
                    $show_category= true;
                }

                if ( $category->children->count() ) {
                    $category_nested_depth = 2;
                    foreach ($category->children as $child) {
                        if ($child->id == $categoryId) {
                            $show_category= true;
//                                $show_subcategory= true;
                        }
                        if ( $child->children->count() ) {
                            $category_nested_depth = 3;
                        }
                        foreach ($child->children as $sub_child) {
                            if ($sub_child->id == $categoryId) {
                                $show_category= true;
//                                    $show_subcategory= true;
//                                    $show_sub_subcategory= true;
                            }
                        }
                    }
                }
                else {
                    $category_nested_depth = 1;
                }
                ?>

                <li class="dropdown custom-mobile-category">

                @if ($category_nested_depth > 2)

                        <span>
                            <a href="{{ ($full_url == route('category.showAll', [$category->id, $category->slug]) ) ? "javasript:;" : route('category.showAll', [$category->id, $category->slug]) }}" class="red">
                                {{ Lang::locale()=='ua' ? $category->category : $category->category_ru}}
                            </a>
                        </span>


                    @if($show_category)
                        @foreach($category->children as $key=>$children)
                            <span>
                                <a href="{{ ($full_url == route('category.showAll', [$children->id, $children->slug]) ) ? "javasript:;" : route('category.showAll', [$children->id, $children->slug]) }}" class="red">
                                    {{ Lang::locale()=='ua' ? $children->category : $children->category_ru}}
                                </a>
                            </span>

                            <?php
                            if ($children->id == $categoryId || ($category->id == $categoryId && $key == 0)) {
                                $show_subcategory= true;
                            } else {
                                $show_subcategory= false;
                            }

                            foreach ($children->children as $sub_child) {
                                if ($sub_child->id == $categoryId) {
                                    $show_subcategory= true;
                                }
                            }
                            ?>


                            @if($show_subcategory)
                                @foreach($children->children as $child)
                                    <ul class="custom-mobile-menu">
                                        <li>
                                            <span class="{{$child->id == $categoryId ? 'current-cat' : ''}}">
                                                <a href="{{ ($full_url == route('category.showAll',[$child->id , $child->slug]) ) ? "javasript:;" : route('category.showAll',[$child->id , $child->slug]) }}">
                                                    {{ Lang::locale()=='ua' ? $child->category : $child->category_ru }}
                                                </a>
                                            </span>
                                        </li>
                                    </ul>
                                @endforeach
                            @endif

                        @endforeach
                    @endif

                @else
                    <span class="{{$category->id == $categoryId ? 'current-cat' : ''}}">
                        <a href="{{ ($full_url == route('category.showAll', [$category->id, $category->slug]) ) ? "javasript:;" : route('category.showAll', [$category->id, $category->slug]) }}" class="{{($category->is_marked) ? "red" : "" }}">
                            {{ Lang::locale()=='ua' ? $category->category : $category->category_ru}}
                        </a>
                    </span>
                        @if(in_array($categoryId,$category->children->pluck('id')->toArray()) || $category->id == $categoryId)
                            @foreach($category->children as $children)
                                <ul class="custom-mobile-menu">
                                    <li>
                                    <span class="{{$children->id == $categoryId ? 'current-cat' : ''}}">
                                        <a href="{{ ($full_url == route('category.showAll',[$children->id , $children->slug]) ) ? "javasript:;" : route('category.showAll',[$children->id , $children->slug]) }}">
                                            {{ Lang::locale()=='ua' ? $children->category : $children->category_ru }}
                                        </a>
                                    </span>
                                    </li>
                                </ul>
                            @endforeach
                        @endif
                @endif

                </li>

                    {{--</li>--}}
                {{--<li class="dropdown">--}}
                    {{--<li class="dropdown custom-mobile-category">--}}
                    {{--<span class="{{$category->id == $categoryId ? 'current-cat' : ''}}">--}}
                        {{--<a href="{{ route('category.showAll', [$category->id, $category->slug]) }}">--}}
                            {{--{{ Lang::locale()=='ua' ? $category->category : $category->category_ru}}--}}
                        {{--</a>--}}
                    {{--</span>--}}
                        {{--@if(in_array($categoryId,$category->children->pluck('id')->toArray()) || $category->id == $categoryId)--}}
                            {{--@foreach($category->children as $children)--}}
                                {{--<ul class="custom-mobile-menu">--}}
                                    {{--<li>--}}
                                    {{--<span class="{{$children->id == $categoryId ? 'current-cat' : ''}}">--}}
                                        {{--<a href="{{ route('category.showAll',[$children->id , $children->slug]) }}">--}}
                                            {{--{{ Lang::locale()=='ua' ? $children->category : $children->category_ru }}--}}
                                        {{--</a>--}}
                                    {{--</span>--}}
                                    {{--</li>--}}
                                {{--</ul>--}}
                            {{--@endforeach--}}
                        {{--@endif--}}
                    {{--</li>--}}
                {{--</li>--}}
            @endforeach
        </ul>
    </nav>
    <div id="overlay"></div>
</div>

