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
        body {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            background-color: #FAFAFA;
            font: 12pt "Tahoma";
        }
        * {
            box-sizing: border-box;
            -moz-box-sizing: border-box;
        }
        .page {
            width: 8.27in;
            min-height: 11.69in;
            padding: 0;
            margin: 0 auto;
            border: 1px #D3D3D3 solid;
            border-radius: 5px;
            background: white;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        @page {
            size: 8.27in 11.69in;
            margin: 0;
        }
        @media print {
            html, body {
                width: 100%;
                height: 100%;
                margin: 0;
                padding: 0;
                background-color: #FAFAFA;
                font: 12pt "Tahoma";
            }
            .page {
                padding: 0mm;
                margin: 0mm auto;
                border: initial;
                border-radius: initial;
                width: 8.27in;
                min-height: 11.69in;
                box-shadow: initial;
                background: initial;
                page-break-after: always;
                position: relative;
            }
            .footer{
                position: absolute;
                bottom: 10px;
                width: 100%;
                padding-right:20mm;
            }
        }
    </style>

    @yield('after-styles-end')

</head>
{{--<body class="skin-{!! config('backend.theme') !!}" style="font-family: khmeros" onload="window.print()">--}}
<body class="skin-{!! config('backend.theme') !!}" style="font-family: khmeros" >

@yield('content')

@yield('scripts')
</body>
</html>