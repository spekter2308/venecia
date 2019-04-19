


{!! Form::open(array('route' => 'queries.search', 'method' => 'get')) !!}
    {{--<div class="typeahead-container" id="typeahead-container">--}}
    <div class="typeahead-container" >
        <div class="typeahead-field" style="z-index: 4;">
            {{--<div class="typeahead-query" id="typeahead-query">--}}
            <div class="typeahead-query">
                {!! Form::text('search', (isset($query) ? $query : null), array('class' => 'flyer-query', 'placeholder' => trans('messages.searchProduct'), 'autocomplete' =>'off' )) !!}
{{--                {!! Form::text('search', (isset($query) ? $query : null), array('id' => 'flyer-query', 'class' => 'flyer-query', 'placeholder' => trans('messages.searchProduct'), 'autocomplete' =>'off' )) !!}--}}
                {{--<div id="product-search-result" class="result product-search-result">--}}
                <div class="result product-search-result">
                    <ul>
                        {{--<li><div class="wrap"><a>черевики жіночі Venezia W3831502 BLACK Venezia W3831502 BLACK</a></div></li>--}}
                        {{--<li><div class="wrap"><a>черевики жіночі Venezia W3831502 BLACK Venezia W3831502 BLACK</a></div></li>--}}
                        {{--<li><div class="wrap"><a>черевики жіночі Venezia W3831502 BLACK Venezia W3831502 BLACK</a></div></li>--}}
                        {{--<li><div class="wrap"><a>черевики жіночі Venezia W3831502 BLACK Venezia W3831502 BLACK</a></div></li>--}}
                        {{--<li><div class="wrap"><a>черевики жіночі Venezia W3831502 BLACK Venezia W3831502 BLACK</a></div></li>--}}
                        {{--<li><div class="wrap"><a>черевики жіночі Venezia W3831502 BLACK Venezia W3831502 BLACK</a></div></li>--}}
                        {{--<li><div class="wrap"><a>черевики жіночі Venezia W3831502 BLACK Venezia W3831502 BLACK</a></div></li>--}}
                        {{--<li><div class="wrap"><a>черевики жіночі Venezia W3831502 BLACK Venezia W3831502 BLACK</a></div></li>--}}
                    </ul>
                </div>
            </div>
            {!! Form::submit(trans("messages.search"), ['class' => 'search__btn']) !!}
            {{--{!! Form::submit('Search', ['id' => 'Search-Btn']) !!}--}}
        </div>
    </div>
{!! Form::close() !!}
