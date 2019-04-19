<div id="overlay_filters"></div>
<a href="#" id="open_filters__btn">Filters</a>
<div class="filters" id="filters__container">
    {{--<h2>Параметри</h2>--}}

    {{--<form>--}}
        {{--<p>Розмір:</p>--}}

        {{--<div class="sizes">--}}
            {{--<div class="size__checkbox_item">--}}
                {{--<input type="checkbox" name="size" id="size_35"/>--}}
                {{--<label for="size_35">35</label>--}}
            {{--</div>--}}
            {{--<div class="size__checkbox_item">--}}
                {{--<input type="checkbox" name="size" id="size_36"/>--}}
                {{--<label for="size_36">36</label>--}}
            {{--</div>--}}
            {{--<div class="size__checkbox_item">--}}
                {{--<input type="checkbox" name="size" id="size_37"/>--}}
                {{--<label for="size_37">37</label>--}}
            {{--</div>--}}
            {{--<div class="size__checkbox_item">--}}
                {{--<input type="checkbox" name="size" id="size_38"/>--}}
                {{--<label for="size_38">38</label>--}}
            {{--</div>--}}
        {{--</div>--}}

        {{--<p>Колір:</p>--}}

        {{--<div class="colors">--}}
            {{--<div class="color__checkbox_item">--}}
                {{--<input type="checkbox" id="red" name="color"/>--}}
                {{--<label for="red"></label>--}}
            {{--</div>--}}
            {{--<div class="color__checkbox_item">--}}
                {{--<input type="checkbox" id="blue" name="color"/>--}}
                {{--<label for="blue"></label>--}}
            {{--</div>--}}
            {{--<div class="color__checkbox_item">--}}
                {{--<input type="checkbox" id="black" name="color"/>--}}
                {{--<label for="black"></label>--}}
            {{--</div>--}}
            {{--<div class="color__checkbox_item">--}}
                {{--<input type="checkbox" id="green" name="color"/>--}}
                {{--<label for="green"></label>--}}
            {{--</div>--}}
        {{--</div>--}}

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
        <h3>{{trans('messages.sortedBy')}}:</h3>
        <ul class="sorting">
            <li class="{{ Request::is('brand/*/newest') ? 'active' : '' }}"><a href="{{ route('brand.newest', $brandId) }}">{{trans('messages.lastAdded')}}</a></li>
            <li class="{{ Request::is('brand/*/price/lowest') ? 'active' : '' }}"><a href="{{ route('brand.lowest', $brandId) }}">{{trans('messages.sortedByLowestPrice')}}</a></li>
            <li class="{{ Request::is('brand/*/price/highest') ? 'active' : '' }}"><a href="{{ route('brand.highest', $brandId) }}">{{trans('messages.sortedByHeightPrice')}}</a></li>
            <li class="{{ Request::is('brand/*/alpha/lowest') ? 'active' : '' }}"><a href="{{ route('brand.alpha.lowest', $brandId) }}">{{trans('messages.sortedByAZ')}}</a></li>
            <li class="{{ Request::is('brand/*/alpha/highest') ? 'active' : '' }}"><a href="{{ route('brand.alpha.highest', $brandId) }}">{{trans('messages.sortedByZA')}}</a></li>
        </ul>
    </form>
</div>