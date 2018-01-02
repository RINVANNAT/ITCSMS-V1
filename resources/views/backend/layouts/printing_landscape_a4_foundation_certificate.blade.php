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
        <meta name="description" content="@yield('meta_description', 'Printing Transcript')">
        <meta name="author" content="@yield('meta_author', 'Department Information and Communication Engineering')">
        @yield('meta')

        <!-- Styles -->
        @yield('before-styles-end')
        {!! Html::style(elixir('css/backend.css')) !!}
{{--        {!! Html::style(elixir('css/student_transcript.css'), array('media' => 'print, screen')) !!}--}}
        {{--<link rel="stylesheet" media="print, screen" href="{{ url('css/backend/printing_portrait_a4_transcript.css') }}">--}}
        <style>
            h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6 {
                font-family: 'bayon';
            }
            .modal-title{
                font-family: 'KhmerOSMoulpali';
            }

            @font-face {
                font-family: franklin_gothic;
                src: url("{{url('assets/fonts/Franklin Gothic Demi Cond Regular.ttf')}}");
            }

            @font-face {
                font-family: arial-rounded;
                src: url("{{url('assets/fonts/arial-rounded-mt-bold.ttf')}}");
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
                font: 11pt "Times New Roman";
                line-height: 110%;
            }

            h4, h3, h2, h1 {
                font-family: "Times New Roman"
            }
            * {
                box-sizing: border-box;
                -moz-box-sizing: border-box;
            }

            .page {
                width: 297mm;
                height: 210mm;
                margin: 0 auto;
                margin-top:10mm;
                border: 1px #D3D3D3 solid;
                padding: 10mm 10mm 10mm 10mm;
                border-radius: 5px;
                background: white;
                box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
                position: relative;
            }
            td {
                font-family: "Times New Roman" !important;
            }
            @page {
                size: 297mm 210mm;
                margin: 0;
            }
            @media print {
                html, body {
                    width: 100%;
                    height: 100%;
                    margin: 0;
                    padding: 0;
                    background-color: #FAFAFA;
                    font: 11pt "Times New Roman";
                }
                .page {
                    padding: 10mm 10mm 10mm 10mm;
                    margin: 0 auto;
                    border: initial;
                    border-radius: initial;
                    width: 297mm;
                    height: 210mm;
                    box-shadow: initial;
                    page-break-after: always;
                    position: relative;
                }

            }
        </style>

        @yield('after-styles-end')

    </head>
    <body class="skin-{!! config('backend.theme') !!}">

        @yield('content')
    </body>
    <script>window.jQuery || document.write('<script src="{{asset('js/vendor/jquery/jquery-2.1.4.min.js')}}"><\/script>')</script>
    @yield('scripts')
</html>