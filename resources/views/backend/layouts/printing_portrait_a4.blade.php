<!doctype html>
<html class="no-js" lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="_token" content="{{ csrf_token() }}" />

        <title>ITC-SMS | Printing</title>

        <!-- Meta -->
        <meta name="description" content="@yield('meta_description', 'Default Description')">
        <meta name="author" content="@yield('meta_author', 'Anthony Rappa')">
        @yield('meta')

        <!-- Styles -->
        @yield('before-styles-end')
        {!! Html::style(elixir('css/backend.css')) !!}
        @yield('after-styles-end')

        <style type="text/css">
            h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6 {
                font-family: 'bayon';
            }
            .modal-title{
                font-family: 'KhmerOSMoulpali';
            }

            @font-face {
                font-family: bayon;
                src: url("{{url('assets/fonts/Bayon.ttf')}}");
            }
            @font-face {
                font-family: kh-bokor;
                src: url("{{url('assets/fonts/Kh-Bokor.ttf')}}");
            }
            @font-face {
                font-family: khmerosmoulpali;
                src: url("{{url('assets/fonts/KhmerOSMoulpali.ttf')}}");
            }
            @font-face {
                font-family: metal;
                src: url("{{url('assets/fonts/Metal.ttf')}}");
            }
            @font-face {
                font-family: tactieng;
                src: url("{{url('assets/fonts/TACTIENG.TTF')}}");
            }
            @font-face {
                font-family: khmeros;
                src: url("{{url('assets/fonts/KhmerOSContent-Regular.ttf')}}");
            }

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
                width: 210mm;
                min-height: 297mm;
                padding: 20mm;
                margin: 10mm auto;
                border: 1px #D3D3D3 solid;
                border-radius: 5px;
                background: white;
                box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
                position: relative;
            }


            @media screen{
                .footer{
                    position: absolute;
                    bottom: 10px;
                    width: 100%;
                    padding-right:40mm;
                }
            }

            @page {
                size: A4;
                margin: 0;
            }
            @media print {
                html, body {
                    width: 210mm;
                    height: 297mm;
                }
                .page {
                    margin: 0;
                    padding-right: 0;
                    border: initial;
                    border-radius: initial;
                    width: initial;
                    min-height: initial;
                    box-shadow: initial;
                    background: initial;
                    page-break-after: always;
                    position: initial;
                }
                .footer{
                    position: absolute;
                    bottom: 10px;
                    width: 100%;
                    padding-right:40mm;
                }
            }

        </style>

    </head>
    <body class="skin-{!! config('backend.theme') !!}" style="font-family: khmeros" onload="window.print()">

        @yield('content')

        @yield('scripts')
    </body>
</html>