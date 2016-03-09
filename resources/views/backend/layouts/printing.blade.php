<!doctype html>
<html class="no-js" lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="_token" content="{{ csrf_token() }}" />

        <title>@yield('title', app_name())</title>

        <!-- Meta -->
        <meta name="description" content="@yield('meta_description', 'Default Description')">
        <meta name="author" content="@yield('meta_author', 'Anthony Rappa')">
        @yield('meta')

        <!-- Styles -->
        @yield('before-styles-end')
        {!! Html::style(elixir('css/backend.css')) !!}
        @yield('after-styles-end')

        <style type="text/css">
            body {
                width: 100%;
                height: 100%;
                margin: 0;
                background-color: #ffffff;
            }

            .page {
                width: 287mm;
                margin: auto;
            }

            @page { margin: 0; }

            @media print {
                * {
                    size: landscape;
                }
                html, body {
                    width: 297mm;
                    height: 210mm;
                    padding: 1.6cm 1.6cm 0cm 1.6cm;
                }
                .page {
                    width: initial;
                }
            }
        </style>

    </head>
    <body class="skin-{!! config('backend.theme') !!}" style="font-family: khmeros" onload="window.print()">

        <div class="page">
            @yield('content')
        </div>

    </body>
</html>