<!doctype html>
<html class="no-js" lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="_token" content="{{ csrf_token() }}"/>

    <title>
        @yield('title')
    </title>

    <!-- Meta -->
    <meta name="description" content="@yield('meta_description', 'Printing Transcript')">
    <meta name="author" content="@yield('meta_author', 'Department Information and Communication Engineering')">
    @yield('meta')

    @yield('before-styles-end')
    {!! Html::style(elixir('css/backend.css')) !!}
    {!! Html::style(elixir('css/student_transcript.css'), array('media' => 'print, screen')) !!}
    <style>
        h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6 {
            font-family: 'bayon';
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
            width: 210mm;
            height: 297mm;
            margin-top: 5mm;
            padding: 20mm;
            padding-right: 0mm;
            background: white;
            position: relative;
        }

        .transcript-footer {
            position: absolute;
            bottom: 5mm;
            width: 100%;
            left: 15mm;
            font-size: 10pt;
        }

        td {
            font-family: "Times New Roman" !important;
        }
        @media print {
            .page {
                width: 210mm;
                height: 297mm;
                margin-top: 10mm;
                padding: 20mm;
                padding-right: 0mm;
                background: white;
                position: relative;
            }
        }
    </style>

    @yield('after-styles-end')

</head>
<body class="skin-{!! config('backend.theme') !!}">

@yield('content')
{{--<script>window.jQuery || document.write('<script src="{{asset('js/vendor/jquery/jquery-2.1.4.min.js')}}"><\/script>')</script>--}}
</body>
@yield('scripts')
</html>