<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <title>404</title>
    <style>
        html {height:100%}
        body {min-height:100%}
        * {
            font-family: Calibri;
        }

        .container {
            padding-top: 15px;
            padding-bottom: 15px;
        }

        .title-wrap {
            display: flex;           /* establish flex container */
            align-items: center;
            flex-wrap: wrap;
            margin-bottom: 30px;
        }

        .flex-wrap {
            display: flex;           /* establish flex container */
            align-items: center;
            flex-wrap: wrap;
        }

        .separator {
            min-width: 15px;
        }

        .title {
            font-size: 24px;
        }

        .text-div.no-padding,.image-div.no-padding {
            padding: 0;
        }

        .flex-center {
            display: flex;           /* establish flex container */
            flex-direction: column;  /* make main axis vertical */
            justify-content: center; /* center items vertically, in this case */
            align-items: center;
        }

        .image-404 {
            width: 100%;
        }

        .image-div {
        }

        .text-div {
            background-color: #333333;
            color: white;
        }

        .span-404 {
            font-size: 50px;
        }

        a {
            text-decoration: none;
            color: white;
        }

        a:hover {
            text-decoration: none;
            color: white;
        }

        a:visited {
            text-decoration: none;
            color: white;
        }

        .home-link {
            padding-bottom: 15px;

        }
        .home-link a {
            color: #dd5656 !important;
            font-size: 18px;
        }

        .search {
            display: flex;
            flex-grow: 1;
            justify-content: flex-end;
            margin-bottom: 30px;
            max-width: 100%;
        }

        form {
            display: flex;
            position: relative;
            width: 296px;
        }

        input.flyer-query {

            background: #fff;
            height: 40px;
            border: 1px solid #d3d3d3;
            border-radius: 0;
            line-height: 30px;
            padding: 0 10px;
            outline: none;
            -webkit-appearance: none;
            box-sizing: border-box;
            font-size: 13px;
            color: #555;
            flex-grow: 1;

        }

        input.search__btn {
            height: 40px;
            background-color: #dd5656;
            color: #fff;
            line-height: 31px;
            border: 0;
            border-radius: 2px;
            text-decoration: none;
            text-transform: uppercase;
            margin-left: 10px;
            outline: 0 !important;
            padding: 0 15px;
        }

        .result {
            width: 296px;
            position: absolute;
            line-height: 13px;
            z-index: 10;
            display: none;
            top: 60px;
            max-width: 100%;
        }

        .result ul {
            list-style-type: none;
            padding: 0;
            border: 1px solid #d3d3d3;
            background-color: #fff;
        }

        .result ul li {
            width: 296px;
            height: 38px;
            font-size: 13px;
            font-weight: 600;
            padding: 10px;
            border-bottom: 1px solid #d3d3d3;
        }

        .result ul li .wrap a {
            white-space: nowrap;
        }

        .result ul li .wrap a:hover {
            color: #666;
        }
        .result ul li .wrap a:link, a:visited {
            color: #333;
        }
        .result ul li .wrap a:link {
            text-decoration: none;
        }

        .result ul li:last-child {
            border: none;
        }

        @media (min-width: 768px) {
            .no-padding {
                padding: 0;
            }
        }

        @media (max-width: 767.98px) {
            .text-div {
                min-height: 50vw;
            }

            .title-wrap {
                margin-bottom: 15px;
            }
        }

        @media (max-width: 991px) {
            .search {
                display: flex;
                flex-grow: 1;
                justify-content: flex-start;
            }
        }


    </style>
</head>
<body class="flex-center">

    <div class="container">
        <div class="row">
            <div class="col-md-12 flex-wrap no-padding">
                <div class="title-wrap no-padding">
                    <img src="/src/public/img/logo.jpg" alt="Магазин Venezia" title="Магазин Venezia">

                    <div class="separator"></div>
                    <div class="title">
                        <b>{{ trans('messages.page_not_found') }}</b>
                    </div>
                </div>
                <div class="search no-padding">
                    <form method="GET" action="http://localhost:90/queries" accept-charset="UTF-8">
                        <input class="flyer-query" placeholder="{{ trans('messages.searchProduct') }}" autocomplete="off" name="search" type="text">
                        <div class="result product-search-result">
                            <ul>
                            </ul>
                        </div>
                        <input class="search__btn" type="submit" value="Пошук">
                    </form>
                </div>
            </div>
            <div class="col-md-9 image-div no-padding">
                <img src="{{asset('src/public/images/404(2).jpg')}}" alt="404" class="image-404">
            </div>
            <div class="col-md-3 flex-center text-div no-padding">
                <span class="span-404">404</span>
                <span>{{ trans('messages.page_not_found') }}</span>
                <span class="home-link"><b><a href="/">{{ trans('messages.go_to_home') }}</a></b></span>
            </div>
        </div>
    </div>

    <script src="{{ asset('src/public/js/libs/jquery.js') }}"></script>

    <script>

        $(function () {

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

</body>
</html>