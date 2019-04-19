 <!-- Sidebar -->
        <div class="landing__sidebar">
            <ul class="sidebar__menu">


                @foreach($categories as $category)
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

                @if(Auth::user())
                    @if((Auth::user()->admin)==1)
                        <li>
                            <a href="{{ url('admin/dashboard') }}">
                                Панель адміністратора
                            </a>
                        </li>
                    @endif

                @endif


            </ul>
        </div>