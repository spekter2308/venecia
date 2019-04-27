<ul class="breadcrumb" style="margin-left: 260px;">
    @foreach($breadcrumbs as $key => $breadcrumb)
        <li itemscope itemtype = "http://data-vocabulary.org/Breadcrumb" >
            @if(!(count($breadcrumbs) == $key + 1))
                <a href="{{$breadcrumb->link}}" itemprop="url">
                    <span itemprop = "title"> {{$breadcrumb->page}} </span>
                </a>
            @else
                <span itemprop = "title"> {{$breadcrumb->page}} </span>
            @endif
        </li>

    @endforeach
</ul>