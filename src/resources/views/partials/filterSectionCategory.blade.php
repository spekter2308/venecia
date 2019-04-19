<?php
use Illuminate\Support\Facades\Input;
?>
<div id="overlay_filters"></div>
<a href="#" id="open_filters__btn">Filters</a>
<div class="filters" id="filters__container">


    <p>{{trans('messages.sortedBy')}}:</p>
    <ul class="sorting">
        <li class="{{ ( Session::get('sort') == "newest" || Session::get('sort') == null ) ? 'active' : '' }}"><a href="{{ url(Request::url()) . '?' . http_build_query(['sort' => 'newest']) }}">{{trans('messages.lastAdded')}}</a></li>
        <li class="{{ ( Session::get('sort') == "lowest" ) ? 'active' : '' }}"><a href="{{ url(Request::url()) . '?' . http_build_query(['sort' => 'lowest']) }}">{{trans('messages.sortedByLowestPrice')}}</a></li>
        <li class="{{ ( Session::get('sort') == "highest" ) ? 'active' : '' }}"><a href="{{ url(Request::url()) . '?' . http_build_query(['sort' => 'highest']) }}">{{trans('messages.sortedByHeightPrice')}}</a></li>
    </ul>
    {{--<h2>Параметри сортування</h2>--}}
    <p>{{trans('messages.items_per_page')}}:</p>
    <select class="items_per_page" onchange="location = this.value;">
        <option value="{{addItemsToUrl(12)}}" {{(Session::get('items_per_page') == "12" ) ? 'selected' : ''}}>12</option>
        <option value="{{addItemsToUrl(24)}}" {{(Session::get('items_per_page') == "24" ) ? 'selected' : ''}}>24</option>
        <option value="{{addItemsToUrl(48)}}" {{(Session::get('items_per_page') == "48" ) ? 'selected' : ''}}>48</option>
    </select>

    <form action="{{ route('category.showAll', [$categoryId, $slug]) }}" method="get" >
        {{--{!! csrf_field() !!}--}}
        <input type="text" id="range" value="" name="range" />

        @if(count($sizes))
        <p>{{trans('messages.size')}}:</p>



        <div class="sizes">
    @foreach($sizes as $size)
            <div class="size__checkbox_item">
                <input type="checkbox" name="size[]" value="{{$size->size}}" id="size_{{$size->size}}" {{ (Request::has("size") && in_array( $size->size, Request::input("size")) ) ? "checked" : "" }}/>
                <label for="size_{{$size->size}}">{{$size->size}}</label>
            </div>
     @endforeach


        </div>

        @endif

        <p>{{trans('messages.color')}}:</p>
        <div class="colors">
            @foreach($colors as $color)

                <div class="color__checkbox_item">
                    <input type="checkbox" id="{{$color->color}}" name="color[]" value="{{$color->color}}" {{ (Request::has("color") && in_array( $color->color, Request::input("color")) ) ? "checked" : "" }}/>
                    <label for="{{$color->color}}" style="background-color: {{$color->color}}"></label>
                </div>
            @endforeach

        </div>

        @foreach($filter_types as $filter_type)
            <p>{{ Lang::locale()=='ua' ? $filter_type->type : $filter_type->type_ru }}:</p>
            @foreach($filter_type->filter_names as $filter_name)
                <div class="filters-checkbox">
                    <input id="filter-{{ $filter_name->id }}" type="checkbox" name="filter[]" value="{{ $filter_name->id }}" {{ (Request::has("filter") && in_array( $filter_name->id, Request::input("filter")) ) ? "checked" : "" }}>
                    <label for="filter-{{ $filter_name->id }}">
                        {{ Lang::locale()=='ua' ? $filter_name->name : $filter_name->name_ru }}
                    </label>
                </div>

            @endforeach
        @endforeach

        {{--<p>Вид підошви:</p>--}}

        {{--<div class="standart_checkboxes">--}}
            {{--<div class="standart__checkbox_item">--}}
                {{--<input type="checkbox" name="foot" id="low"/>--}}
                {{--<label for="low">Низький хід</label>--}}
            {{--</div>--}}
            {{--<div class="standart__checkbox_item">--}}
                {{--<input type="checkbox" name="foot" id="kabluk"/>--}}
                {{--<label for="kabluk">Каблук</label>--}}
            {{--</div>--}}
            {{--<div class="standart__checkbox_item">--}}
                {{--<input type="checkbox" name="foot" id="tanketka"/>--}}
                {{--<label for="tanketka">Танкетка</label>--}}
            {{--</div>--}}
        {{--</div>--}}

        {{--SORT SECTION--}}

<button style="margin-top: 10px" type="submit" class="btn btn-primary-outline pull-left"> ПОШУК </button>


    </form>
</div>