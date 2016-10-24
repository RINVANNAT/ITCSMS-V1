<!doctype html>
<html class="no-js" lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="_token" content="{{ csrf_token() }}" />

        <title>
            @yield('title')
        </title>

        <!-- Meta -->
        <meta name="description" content="@yield('meta_description', 'Default Description')">
        <meta name="author" content="@yield('meta_author', 'Anthony Rappa')">
        @yield('meta')

        <!-- Styles -->
        @yield('before-styles-end')
        {!! Html::style(elixir('css/backend.css')) !!}

        <style type="text/css" media="all">
        * { -webkit-print-color-adjust: exact; print-color-adjust: exact; }

        @page {
            margin: -.125in 0 0 -.125in;
            size: 2in 3.5in;
            marks: crop;
        }

        html, body {
            margin: 0;
            padding: 0;
        }
        ol, ol li {
            list-style-type: none;
            margin-left: 0;
            padding-left: 0;
        }
        pre {
            padding: .125in .29in .050in;
            background: none;
            border: none;
            border-radius: 0;
        }
        .frosty {
            margin: 0 -.390in;
            background: rgba(255, 255, 255, .60);
        }

        .card {
            position: relative;
            width: 2.25in;
            height: 3.75in;
        }
        .card-body {
            position: absolute;
            top: .125in;
            left: .125in;
            width: 2in;
            height: 3.5in;
            padding: 0 .250in;
        }
        .card-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 2.25in;
            height: 3.75in;
        }
        .card-background img {
            width: 100%;
        }
    </style>
    <style type="text/css" media="screen">
        .card-body {
            border: 1pt solid gray;
        }
    </style>

        @yield('after-styles-end')

    </head>
    <body class="skin-{!! config('backend.theme') !!}" style="font-family: khmeros" onload="window.print()">

        @yield('content')

        @yield('scripts')
    </body>
</html>