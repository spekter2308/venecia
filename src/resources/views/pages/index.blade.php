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

<div id="wrapper" class="toggled">

    <div class="os-menu-images contentbox container-fluid">
        @include('pages.partials.main-nav')
        <div class="os-images-container">
            @include('pages.partials.os-main-images')


        <!-- Featured Products section -->

            @include('pages.partials.featured')

            <div class="description-wrapper">
                <div class="intro">{!!  App::getLocale() == 'ua' ? $mainSeo->intro_description : $mainSeo->intro_description_ru !!}</div>
                <div class="full-description"></div>
                <div class="description-toggle" data-status="intro">{{ trans('messages.readMore') }}</div>
            </div>

        </div>

    </div>


    <!-- New Products section -->



    @include('pages.partials.footer')

    </div>  <!-- close wrapper -->


@stop

@section('footer')

{{--    <script  src="{{asset('src/public/js/plugins/gridstack.js')}}"></script>--}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gridstack.js/0.4.0/gridstack.min.js"></script>
{{--    <script  src="{{asset('src/public/js/plugins/gridstack.jQueryUI.js')}}"></script>--}}
    <script  src='//cdnjs.cloudflare.com/ajax/libs/gridstack.js/0.4.0/gridstack.jQueryUI.min.js'></script>

    <script>

        $(document).ready(function(){
            $(".description-toggle").click(function () {
                if ( $(this).data('status') == 'intro' ) {
                    $(this).data('status', 'full-description');

                    var scrollTop = document.documentElement.scrollTop;

                    $(this).text('{{ trans('messages.hide') }}');
                    $('.description-wrapper .intro').html('');
                    $('.description-wrapper .intro').hide();

                    $('.description-wrapper .full-description').append(`{!!  App::getLocale() == 'ua' ? $mainSeo->description : $mainSeo->description_ru !!}`);
                    $('.description-wrapper .full-description').show();

                    document.documentElement.scrollTop = scrollTop;

                } else {
                    $(this).data('status', 'intro');
                    $('.description-wrapper .full-description').html('');
                    $('.description-wrapper .full-description').hide();


                    $('.description-wrapper .intro').append(`{!!  App::getLocale() == 'ua' ? $mainSeo->intro_description : $mainSeo->intro_description_ru !!}`);
                    $('.description-wrapper .intro').show();
                    $(this).text('{{ trans('messages.readMore') }}');
                }
            });
        });



        if($(window).width() > 810){
            var options = {
                cellHeight:  Math.round($('#res-width').width() / 17 ),
                width: 160,
                verticalMargin: 10,
                staticGrid: true
            };
            $('.grid-stack').gridstack(options);
        }




        $(window).resize(function() {



            if($(window).width() > 810)
            {
                var grid;
                grid = $('.grid-stack').data('gridstack');

                var sH = Math.round($('#res-width').width() / 18  );
                grid.destroy(false);
                grid.container.removeData("gridstack");
                var options = {
                    cellHeight: sH,
                    width: 160,
                    verticalMargin: 10,
                    staticGrid: true
                };
                $('.grid-stack').gridstack(options);

            }


        });
    </script>
@endsection



