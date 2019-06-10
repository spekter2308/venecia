<?php
    $full_url = Request::fullUrl();
//    $full_url = preg_replace("/^ru\//", "", $full_url);
//    $full_url = preg_replace("/^ru/", "", $full_url);

   //dd($full_url);
?>
<div class="sidebar col-sm-hidden">
    <ul class="sidebar__menu">

        @foreach($categoryAll->sortBy('sequence') as $category)
            {{--<li class="dropdown">--}}
                <li class="dropdown">
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

                    @if ($category_nested_depth > 2)
                        <span>
                            <a href="{{ ($full_url == route('category.showAll', [$category->id, $category->slug]) ) ? "javasript:;" : route('category.showAll', [$category->id, $category->slug]) }}"
                               class="{{$category->id == $categoryId ? 'current-cat' : ''}} red {{ ($full_url == route('category.showAll', [$category->id, $category->slug]) ) ? "no-cursor" : "" }}"
                            >
                                {{ Lang::locale()=='ua' ? $category->category : $category->category_ru}}
                            </a>
                        </span>


                        @if($show_category)
                            @foreach($category->children->sortBy('sequence') as $key=>$children)
                                <span>
                                    <a href="{{ ($full_url == route('category.showAll', [$children->id, $children->slug]) ) ? "javasript:;" : route('category.showAll', [$children->id, $children->slug]) }}"
                                       class="{{$children->id == $categoryId ? 'current-cat' : ''}} red {{ ($full_url == route('category.showAll', [$children->id, $children->slug]) ) ? "no-cursor" : "" }}"
                                    >
                                        {{ Lang::locale()=='ua' ? $children->category : $children->category_ru}}
                                    </a>
                                </span>

                                <?php
//                                    if ($children->id == $categoryId || ($category->id == $categoryId && $key == 0)) {
//                                        $show_subcategory= true;
//                                    } else {
//                                        $show_subcategory= false;
//                                    }

                                    foreach ($children->children->sortBy('sequence') as $sub_child) {
                                        if ($sub_child->id == $categoryId) {
                                            $show_subcategory= true;
                                        }
                                    }
                                ?>


{{--                                @if($show_subcategory)--}}
                                    @foreach($children->children->sortBy('sequence') as $child)
                                        <ul>
                                            <li>
                                                <span class="{{$child->id == $categoryId ? 'current-cat' : ''}}">
                                                    <a href="{{ ($full_url == route('category.showAll',[$child->id , $child->slug]) ) ? "javasript:;" : route('category.showAll',[$child->id , $child->slug]) }}"
                                                       class="{{ ($full_url == route('category.showAll',[$child->id , $child->slug]) ) ? "no-cursor" : "" }}"
                                                    >
                                                        {{ Lang::locale()=='ua' ? $child->category : $child->category_ru }}
                                                    </a>
                                                </span>
                                            </li>
                                        </ul>
                                    @endforeach
                                {{--@endif--}}

                            @endforeach
                        @endif

                    @else
                        @if ($category->products->count() or array_map(function ($child) { return $child->products ? $child->products : null;}, iterator_to_array($category->children)))
                                <span class="{{$category->id == $categoryId ? 'current-cat' : ''}}">
                            <a href="{{ ($full_url == route('category.showAll', [$category->id, $category->slug]) ) ? "javasript:;" : route('category.showAll', [$category->id, $category->slug]) }}"
                               class="{{($category->is_marked) ? "red" : "" }} {{ ($full_url == route('category.showAll', [$category->id, $category->slug]) ) ? "no-cursor" : "" }}"
                            >
                                {{ Lang::locale()=='ua' ? $category->category : $category->category_ru}}
                            </a>
                        </span>
                        @endif
                        @if(in_array($categoryId, $category->children->pluck('id')->toArray()) || $category->id == $categoryId)
                            @foreach($category->children->sortBy('sequence') as $children)
                                <ul>
                                    <li>
                                <span class="{{$children->id == $categoryId ? 'current-cat' : ''}}">
                                    <a href="{{ ($full_url == route('category.showAll',[$children->id , $children->slug]) ) ? "javasript:;" : route('category.showAll',[$children->id , $children->slug]) }}"
                                       class="{{ ($full_url == route('category.showAll',[$children->id , $children->slug]) ) ? "no-cursor" : "" }}"
                                    >
                                        {{ Lang::locale()=='ua' ? $children->category : $children->category_ru }}
                                    </a>
                                </span>
                                    </li>
                                </ul>
                            @endforeach
                        @endif
                    @endif

                    {{--@if(in_array($categoryId,$category->children->pluck('id')->toArray()) || $category->id == $categoryId)--}}
                        {{--@foreach($category->children as $children)--}}
                            {{--<ul>--}}
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
                </li>
            {{--</li>--}}
        @endforeach

    </ul>
</div>