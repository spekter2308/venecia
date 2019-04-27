<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta property="og:url" content="{{url("/")}}">
    <meta property="og:image" content="@yield('openGraphImage', url('/src/public/img/logo.jpg'))">
    <meta property="og:image:height" content="1200" />
    <meta property="og:image:height" content="600" />
    @if(isset($metaTags))
        <meta property="og:description" name="description" content="{{Lang::locale()=='ua' ? $metaTags['description'] : $metaTags['description_ru']}}"/>
        <meta name="keywords"   content="{{Lang::locale()=='ua' ? $metaTags['keywords'] : $metaTags['keywords_ru']}}"/>
        <meta property="og:title" content="{{Lang::locale()=='ua' ? $metaTags['title'] : $metaTags['title_ru']}}" />
        <title>{{Lang::locale()=='ua' ? $metaTags['title'] : $metaTags['title_ru']}}</title>
    @else
        @if(isset($title))
            <title>{{$title}}</title>
            <meta property="og:title" content="{{$title}}" />

        @endif
        @if(isset($description))
            <meta property="og:description" name="description" content="{{$description}}"/>
        @endif
        @if(!empty($keywords))
            <meta name="keywords" content="{{ $keywords }}"/>
        @endif
    @endif

    @if( (Request::has('showAll') ||  Request::input('sort') || Request::input('items') || Request::has('range') || Request::has('size') || Request::has('color')) )
        <meta name="robots" content="noindex, follow">
    @elseif( isset($canonical) )
        <link rel="canonical" href="{{ $canonical }}"/>
    @endif


    <link rel="shortcut icon" href="{!! asset('/src/public/images/slider/fav-icon.png') !!}"/>
    <!-- Bootstrap core CSS -->
    <!-- Bootstrap core mdb.css -->
    <!-- Include sweet alert file -->
    <link rel="stylesheet" href="{{ asset('src/public/css/sweetalert.css') }}">
    {{--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.0/sweetalert.min.css" />--}}

    <!-- Include typeahead file -->
    <link rel="stylesheet" href="{{ asset('src/public/css/typeahead.css') }}">
    <!-- Include lity ligh-tbox file -->

    <!-- Include select -->
    {{--<link rel="stylesheet" href="{{ asset('src/public/css/bootstrap-select.css')}}">--}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
{{--FOR COMMENTS--}}
    @yield('json-ld')

<!-- Include app.less file -->
    {{--        <link rel="stylesheet" href="{{ asset('src/public/less/app.less') }}">--}}
    {{--<!-- Include app.scss file -->--}}

    {{--THIS SECTION NEED IMPLEMENT BECOUSE SEARCH DOESNT WORK!!!! (WHEN COMENT )--}}

    {{--        <link rel="stylesheet" href="{{ asset('src/public/sass/app.scss') }}">--}}

    {{--END SECTION NEED IMPLEMENT BECOUSE SEARCH DOESNT WORK!!!! (WHEN COMENT )--}}

    {{--        <link rel="stylesheet" href="{{ asset('src/public/css/bootstrap.min.css') }}">--}}
    {{--        <link rel="stylesheet" href="{{ asset('src/public/css/mdb.css') }}">--}}


    {{--END  COMMENTS--}}


    {{--Our styles for frondend--}}

    <link rel="stylesheet" href="{{ asset('src/public/css/ion.rangeSlider-2.2.0/ion.rangeSlider.css') }}">
    <link rel="stylesheet" href="{{ asset('src/public/css/ion.rangeSlider-2.2.0/ion.rangeSlider.skinFlat.css') }}">
    <link rel="stylesheet" href="{{ asset('src/public/css/lity.css') }}">
    <link rel="stylesheet" href="{{ asset('src/public/css/normalize.css') }}">
    <link rel="stylesheet" href="{{ asset('src/public/css/flexboxgrid.min.css') }}">
    <link rel="stylesheet" href="{{ asset('src/public/css/custom_style.css') }}">
    {{--        <link rel="stylesheet" href="{{ asset('src/public/css/style.css') }}">--}}
{{--    <link rel="stylesheet" href="{{ asset('src/public/css/animate.css') }}">--}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.1/animate.min.css" />
    <link rel="stylesheet" href="{{ asset('src/public/css/tingle.css') }}">


    <!-- Added the main.css file that combines app.scss and app.css togather -->
    {{--        <link rel="stylesheet" href="{{ asset('src/public/css/main.css') }}">--}}


<!-- Material Design Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans%7CRoboto%7CRaleway" rel="stylesheet">
    <!-- Font Awesome -->


    <link href="https://fonts.googleapis.com/css?family=Alegreya+Sans" rel="stylesheet">

    {{--<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet">--}}
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.9/css/all.css" integrity="sha384-5SOiIsAziJl6AWe0HWRKTXlfcSHKmYV4RBF18PPJ173Kzn7jzMyFuTtk8JA7QQG1" crossorigin="anonymous">


{{--Include script for modal--}}
    <!-- jQuery -->
    <script src="{{ asset('src/public/js/libs/jquery.js') }}"></script>
{{--    <script  src="{{ asset('src/public/js/libs/tingle.js') }}"></script>--}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tingle/0.8.4/tingle.min.js"></script>

{{--    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-115540838-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-115540838-1');
    </script>--}}

<!-- Google Analytics -->
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-115540838-1', 'auto');
        ga('send', 'pageview');
    </script>
    <!-- End Google Analytics -->

</head>
<body>

<div class="callback_shadow przycmienie close_callback" style="display: none;"></div>
<div class="callback">
    <div class="callback_ico_{{Lang::locale()}}" data-toggle="modal" data-target="#callback"></div>
    <div class="callback_content modal-content" id="callback" style="display: none; ">
        <a href="#" data-toggle="modal" data-target="#callback" class="close">X</a>
        <div class="title">
            Форма для відправки повідомлення
        </div>
        <form id="callback_form" method="post">
            {{ csrf_field() }}
            <div class="form-group">
                <label>Adres e-mail </label>
                <input type="text" name="email" class="form-control" value="{{$signedIn ? Auth::user()->email : ''}}" >
            </div>
            <div class="form-group">
                <label>Повідомлення</label>
                <textarea name="message" class="form-control"></textarea>
            </div>
            <input id="sendMessage" type="button" name="send_callback" value="Відправити">
        </form>

    </div>
</div>


@include('partials.nav')

@if( isset($breadcrumbs) )
    @include('partials.breadcrumbs')
@endif
@yield('content')


<!-- Include main app.js file -->
<script  src="{{ asset('src/public/js/app.js') }}"></script>
<!-- Bootstrap core JavaScript -->
<script  src="{{ asset('src/public/js/libs/bootstrap.min.js') }}"></script>

{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.0/jquery-ui.js"></script>--}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.0/jquery-ui.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/3.5.0/lodash.min.js"></script>
<!-- MDB core JavaScript -->
{{--    <script  src="{{ asset('src/public/js/libs/mdb.js') }}"></script>--}}
<!-- Include sweet-alert.js file -->
<script  src="{{ asset('src/public/js/libs/sweetalert.js') }}"></script>
<!-- Include typeahead.js file -->
<script  src="{{ asset('src/public/js/libs/typeahead.js') }}"></script>
<!-- Include lity light-box js file -->
{{--<script  src="{{ asset('src/public/js/libs/lity.js') }}"></script>--}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/lity/1.6.5/lity.min.js"></script>
{{--Frontend Scripts JS--}}



<script  src="{{ asset('src/public/js/libs/slick.min.js') }}"></script>

<script  src="{{ asset('src/public/js/libs/main.js') }}"></script>






<!-- Stripe.js file -->
<script  src="https://js.stripe.com/v2/"></script>
<script  src="https://cdn.jsdelivr.net/jquery.validation/1.15.1/jquery.validate.min.js"></script>

{{--<script src="{{ asset('src/public/js/plugins/bootstrap-select.js')}}"></script>--}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script>
//    (function (w, d, s, g, js, fs) {
//        g = w.gapi || (w.gapi = {});
//        g.analytics = {
//            q: [], ready: function (f) {
//                this.q.push(f);
//            }
//        };
//        js = d.createElement(s);
//        fs = d.getElementsByTagName(s)[0];
//        js.src = 'https://apis.google.com/js/platform.js';
//        fs.parentNode.insertBefore(js, fs);
//        js.onload = function () {
//            g.load('analytics');
//        };
//    }(window, document, 'script'));
</script>
<script>
//    gapi.analytics.ready(function () {
//        /**
//         * Authorize the user immediately if the user has already granted access.
//         * If no access has been created, render an authorize button inside the
//         * element with the ID "embed-api-auth-container".
//         */
//        gapi.analytics.auth.authorize({
//            container: 'embed-api-auth-container',
//            clientid: 'YOUR CLIENT ID'
//        });
//        /**
//         * Create a new ViewSelector instance to be rendered inside of an
//         * element with the id "view-selector-container".
//         */
//        var viewSelector = new gapi.analytics.ViewSelector({
//            container: 'view-selector-container'
//        });
//        // Render the view selector to the page.
//        viewSelector.execute();
//        /**
//         * Create a new DataChart instance with the given query parameters
//         * and Google chart options. It will be rendered inside an element
//         * with the id "chart-container".
//         */
//        var dataChart = new gapi.analytics.googleCharts.DataChart({
//            query: {
//                metrics: 'ga:sessions',
//                dimensions: 'ga:date',
//                'start-date': '30daysAgo',
//                'end-date': 'yesterday'
//            },
//            chart: {
//                container: 'chart-container',
//                type: 'LINE',
//                options: {
//                    width: '100%'
//                }
//            }
//        });
//        /**
//         * Render the dataChart on the page whenever a new view is selected.
//         */
//        viewSelector.on('change', function (ids) {
//            dataChart.set({query: {ids: ids}}).execute();
//        });
//    });
</script>
<script>
    // your publish key
    Stripe.setPublishableKey('YOUR STRIPE PUBLISHABLE KEY');
    //
    jQuery(function ($) {
        $('#payment-form').submit(function (event) {
            var $form = $(this);
            // Disable the submit button to prevent repeated clicks
            $form.find($('.btn')).prop('disabled', true);
            Stripe.card.createToken($form, stripeResponseHandler);
            // Prevent the form from submitting with the default action
            return false;
        });
    });
    function stripeResponseHandler(status, response) {
        var $form = $('#payment-form');
        if (response.error) {
            // Show the errors on the form
            $form.find('.payment-errors').text(response.error.message);
            $form.find('.payment-errors').removeClass("hidden");
            $form.find($('.btn')).prop('disabled', false);
        } else {
            // response contains id and card, which contains additional card details
            var token = response.id;
            // Insert the token into the form so it gets submitted to the server
            $form.append($('<input type="hidden" name="stripeToken" />').val(token));
            // and submit
            $form.get(0).submit();
        }
    }
</script>

{{--<script>--}}
{{--new WOW().init();--}}
{{--</script>--}}

{{--<script src="{{ asset('/src/public/js/plugins/ion.rangeSlider-2.2.0/ion.rangeSlider.js') }}"></script>--}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.2.0/js/ion.rangeSlider.min.js"></script>
<script>

    $(function () {

        $("#range").ionRangeSlider({
            hide_min_max: true,
            keyboard: true,
            min: 500,
            max: 5000,
            from: {{ (isset($range_price[0])) ? $range_price[0] : 500 }},
            to: {{ (isset($range_price[1])) ? $range_price[1] : 5000}},
            type: 'double',
            step: 1,
            prefix: "грн",
            grid: true
        });
    });


</script>
<script>

    $(function () {
        $( "#sendMessage" ).click(function() {
            var form = $( "#callback_form" ).serialize();
            var link = '{{url('callback')}}';
            $.ajax({
                method: "POST",
                url: link,
                data: form,

                statusCode: {
                    400: function() {
                       $('input[name=email]').attr('style', 'border: 1px solid rgb(254, 2, 1);');
                       $( "#callback_form" ).append('<p style="color:red;font-size: 13px;">Адреса пошти не є валідною</p>')

                    }
                }
            }).done(function () {
                    $('#callback').modal('hide');
                    sweetAlert('Ваш лист було надіслано. Лист з відповіддю прийде на вказану пошту!')
                });
        });


        //search
        $('.flyer-query').keyup(function () {

            var query = this.value;

            $('.flyer-query').val(query);

            if (query.length >= 3) {
//                console.log(query);

                fetch( "{{ route('queries.ajax.search') }}",{
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        {{--'X-CSRF-TOKEN': '{{ csrf_token() }}'--}}
                    },
                    body: JSON.stringify({search: query})
                })
                    .then(function(response) {
                        return response.json();
                    })
                    .then(function(products) {

                        var ul = '';

//                        if (products.products.length) {
//                            $('.product-search-result').show();
//                        } else {
//                            $('.product-search-result').hide();
//                        }

                        if (products.products.length) {
                            products.products.forEach(function (product) {
                                ul += `<li><div class="wrap"><a href="${product.href}">${product.name}</a></div></li>`;
                            });
                        } else {
                            ul += `<li><div class="wrap">{{ \App::getLocale() == 'ua' ? "Товарів не знайдено" : "Товары не найдены" }}</div></li>`;
                        }

                        $('.product-search-result').show();

//                        products.products.forEach(function (product) {
//                            ul += `<li><div class="wrap"><a href="${product.href}">${product.name}</a></div></li>`;
//                        });

                        $('.product-search-result ul').html(ul);

//                        console.log(typeof products );
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            } else {
                $('.product-search-result ul').html('');
                $('.product-search-result').hide();
            }
        });
    });



</script>
<script>
    function matchStart(params, data) {
        // If there are no search terms, return all of the data
        if ($.trim(params.term) === '') {
            return data;
        }

        // Skip if there is no 'children' property
        if (typeof data.children === 'undefined') {
            return null;
        }

        // `data.children` contains the actual options that we are matching against
        var filteredChildren = [];
        $.each(data.children, function (idx, child) {
            if (child.text.toUpperCase().indexOf(params.term.toUpperCase()) == 0) {
                filteredChildren.push(child);
            }
        });

        // If we matched any of the timezone group's children, then set the matched children on the group
        // and return the group object
        if (filteredChildren.length) {
            var modifiedData = $.extend({}, data, true);
            modifiedData.children = filteredChildren;

            // You can return modified objects from here
            // This includes matching the `children` how you want in nested data sets
            return modifiedData;
        }

        // Return `null` if the term should not be displayed
        return null;
    }

    $(".js-example-matcher-start").select2({
        matcher: matchStart
    });
</script>
@yield('footer')

@include('partials.flash')

</body>
</html>
