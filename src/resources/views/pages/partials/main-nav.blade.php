<!-- Sidebar -->
<div class="os">
    {{--<ul class="os-side">--}}
        {{--@foreach($categories as $category)--}}
            {{--<li class="os-menu-category"><a href="#"> {{ $category->category }}</a>--}}
                {{--@foreach($category->children as $children)--}}
                    {{--<ul class="os-menu-children">--}}
                        {{--<li>--}}
                            {{--<a href="{{ url('category', $children->id) }}">--}}
                                {{--{{ $children->category }}--}}
                            {{--</a>--}}
                        {{--</li>--}}
                    {{--</ul>--}}
                {{--@endforeach--}}
            {{--</li>--}}
        {{--@endforeach--}}
        {{--<li><a href="#">{{trans('messages.paymentAndDelivery')}}</a></li>--}}
        {{--<li><a href="#">{{trans('messages.Guarantee')}}</a></li>--}}
        {{--<li><a href="#">{{trans('messages.sizeTable')}}</a></li>--}}
        {{--<li><a href="#">{{trans('messages.contacts')}}</a></li>--}}
        {{--@if(Auth::user())--}}
            {{--@if((Auth::user()->admin)==1)--}}
                {{--<li>--}}
                    {{--<a href="{{ url('admin/dashboard') }}">--}}
                        {{--Панель адміністратора--}}
                    {{--</a>--}}
                {{--</li>--}}
            {{--@endif--}}
        {{--@endif--}}
    {{--</ul>--}}
    @include('partials.navFixSection')
</div>