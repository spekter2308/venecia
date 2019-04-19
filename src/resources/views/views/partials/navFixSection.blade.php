{{--<div class="sidebar col-sm-hidden">--}}
<div class="sidebar col-sm-hidden">
    <ul class="sidebar__menu">


        @foreach($categoryAll as $category)
            {{--<li class="dropdown">--}}
            <li class="dropdown"><a href="#"> {{ $category->category }}</a>
            @foreach($category->children as $children)
                <ul>
                    <li>
                        <a href="{{ url('category', $children->id) }}">
                        {{ $children->category }}
                        </a>
                    </li>
                </ul>
            @endforeach
            </li>
            {{--</li>--}}
        @endforeach

    </ul>
</div>