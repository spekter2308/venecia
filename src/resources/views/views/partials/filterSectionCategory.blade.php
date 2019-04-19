<div id="overlay_filters"></div>
<a href="#" id="open_filters__btn">Filters</a>
<div class="filters" id="filters__container">


    <h3>{{trans('messages.sortedBy')}}:</h3>
    <ul class="sorting">
        <li class="{{ Request::is('category/*/newest') ? 'active' : '' }}"><a href="{{ route('category.newest', $categoryId) }}">{{trans('messages.lastAdded')}}</a></li>
        <li class="{{ Request::is('category/*/price/lowest') ? 'active' : '' }}"><a href="{{ route('category.lowest', $categoryId) }}">{{trans('messages.sortedByLowestPrice')}}</a></li>
        <li class="{{ Request::is('category/*/price/highest') ? 'active' : '' }}"><a href="{{ route('category.highest', $categoryId) }}">{{trans('messages.sortedByHeightPrice')}}</a></li>


    </ul>
    {{--<h2>Параметри сортування</h2>--}}

    <form action="{{ route('category.size', $categoryId) }}" method="post" >
        {!! csrf_field() !!}

        <p class="filter-caption">{{trans('messages.size')}}:</p>

        <div class="sizes">
            <div >
    @foreach($sizes as $size)
        @if($size->size!='')
            <div class="size__checkbox_item">
                <input type="checkbox" name="size[]" value="{{$size->size}}" id="size_{{$size->size}}"/>
                {{--new--}}
                <label for="size_{{$size->size}}">{{$size->size}}</label>
                {{--<label for="size_{{$size->size}}">&nbsp;</label><span>{{$size->size}}</span>--}}

            </div>
                @endif
     @endforeach
            </div>

        </div>

        <p class="filter-caption">{{trans('messages.color')}}:</p>
        <div class="colors">
            <span class="color-reg">
            @foreach($colors as $color)

                <div class="color__checkbox_item">
                    <input type="checkbox" id="{{$color->color}}" name="color[]" value="{{$color->color}}"/>
                    <label for="{{$color->color}}" style="background-color: {{$color->color}}"></label>
                </div>
            @endforeach
            </span>
        </div>

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