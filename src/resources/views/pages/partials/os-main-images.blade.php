<section class="os-images-main">
    @if(count($mainPageImages)>0)
        @foreach($mainPageImages as $pathToImage)
            <div class="mobile-gallery">
                <a href="{{ url('category', $pathToImage->category_id) }}">
                    <img src="{{$pathToImage->path}}" alt="sales" class="os-image-to-go">
                </a>
            </div>
        @endforeach

        <div id="res-width">

            <div class="grid-stack  " data-gs-width="64"
                 data-gs-current-height="1000" >
                    @foreach($mainPageImages as $pathToImage)

                            <div class="grid-stack-item "
                                 @if($pathToImage->option)
                                     @php $option = json_decode($pathToImage->option,true) @endphp
                                     data-gs-x="{{$option['data-gs-x']  }}"
                                     data-gs-y="{{$option['data-gs-y']   }}"
                                     data-gs-width="{{$option['data-gs-width']  }}"
                                     data-gs-height="{{$option['data-gs-height']  }}"
                                     data-id="{{$option['data-id']  }}"
                                 @endif

                            >
                                <div class="grid-stack-item-content">
                                    <a href="{{ url('category', [$pathToImage->category_id, $pathToImage->slug]) }}">
                                        <img src="{{$pathToImage->path}}" alt="sales" class="os-image-to-go">
                                    </a>
                                </div>
                            </div>
                    @endforeach
            </div>
        </div>
    @endif
</section>