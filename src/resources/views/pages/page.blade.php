@extends('app')

@section('json-ld')
    @php $language = Lang::locale(); @endphp
    <script type="application/ld+json">
        { "@context": "http://schema.org",
             "@type": "Organization",
             "name": "VENEZIA",
             "url": "{{ $language=='ua' ? url("/") : url("{$language}/") }}",
             "logo": "{{ url('/src/public/img/logo.jpg') }}",
             "foundingDate": "2016",
             "address": {
             "@type": "PostalAddress",
             "streetAddress": "{{trans( 'messages.street' )}}",
             "addressLocality": "{{trans( 'messages.location' )}}",
             "addressRegion": "Ua",
             "postalCode": "33000",
             "addressCountry": "Ukraine"
             },
             "contactPoint": {
             "@type": "ContactPoint",
             "contactType": "customer support",
             "email": "venezia-rivne@ukr.net",
             "url": "{{ $language=='ua' ? url("/") : url("{$language}/") }}"
             },
             "sameAs": [
             "https://www.facebook.com/veneziaukraine1/?ref=bookmarks",
             "https://www.instagram.com/accounts/edit/?hl=ru"
             ]}
    </script>
@endsection

@section('content')

    <link rel="stylesheet" href="{{ asset('src/public/css/tingle.css') }}">
{{--    <script  src="{{ asset('src/public/js/libs/tingle.js') }}"></script>--}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tingle/0.8.4/tingle.min.js"></script>


    <section class="contentbox container-fluid">
        <div class="row" >
            @include('partials.navFixSection')
            <div class="static-page col-sm-7 col-xs-12 col-md-5">
                @if(App::getLocale() == 'ru')
                    {!! $page->page_ru !!}
                @else
                    {!! $page->page !!}
                @endif
            </div>
            <div class="col-sm-0 col-xs-0 col-md-3">
                @if(count($mainPageImages)>0)

                    @foreach($mainPageImages as $pathToImage)
                        <div class="static-page-images">
                            <img  src="{{url($pathToImage->path)}}" >
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

    </section>

    @include('pages.partials.footer')


@stop
