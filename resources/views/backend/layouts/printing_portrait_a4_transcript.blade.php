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
        @font-face {
            font-family: times_new_roman_normal;
            src: url("{{url('fonts/Times_New_Roman_Normal.ttf')}}");
        }

        h4, h3, h2, h1 {
            font-family: times_new_roman_normal !important;
        }

        * {
            box-sizing: border-box;
            -moz-box-sizing: border-box;
            font-family: times_new_roman_normal !important;
        }

        .page {
            width: 210mm;
            height: 297mm;
            padding-top: 15mm;
            padding-left: 13mm;
            padding-right: 15mm;
            background: white;
            position: relative;
            page-break-after: always;
            page-break-before: always;
        }

        .transcript-footer {
            position: absolute;
            bottom: 5mm !important;
            height: auto;
            width: 100%;
            left: 13mm;
            font-size: 10pt;
        }

        .transcript-header {
            padding-left: 0px !important;
            padding-right: 0px !important;
        }

        td {
            font-family: times_new_roman_normal !important;
        }

        @media print {
            .page {
                width: 210mm;
                height: 297mm;
                padding-top: 15mm;
                padding-left: 13mm;
                padding-right: 15mm;
                padding-bottom: 0mm;
                background: white;
                position: relative;
                page-break-after: always;
                page-break-before: always;
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
