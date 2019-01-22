<!doctype html>
<html class="no-js" lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
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
            font-family: khmer_moul;
            src: url("{{url('assets/fonts/KhmerOS_muollight.ttf')}}");
        }

        @font-face {
            font-family: khmer_m1;
            src: url("{{url('assets/fonts/Khmer M1.volt.ttf')}}");
        }

        @font-face {
            font-family: khmer_s1;
            src: url("{{url('assets/fonts/Khmer s1.volt.ttf')}}");
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
            height: 296mm;
            padding-top: 19mm;
            padding-left: 21mm;
            padding-right: 25mm;
            padding-bottom: 0mm;
            background: white;
            position: relative;
            page-break-after: always;
            page-break-before: always;
        }

        .transcript-footer {
            position: absolute;
            bottom: 6mm !important;
            height: auto;
            width: 100%;
            left: 21mm;
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
                height: 99%;
                padding-top: 13mm;
                padding-left: 12mm;
                padding-right: 18mm;
                padding-bottom: 0mm;
                background: white;
                position: relative;
                page-break-after: always;
                page-break-before: always;
                page-break: after;
            }
        }
         .custom-break-line {
            padding-top: 8px;
        }
        .custom-transcript-title img {
            margin-top: 0px;
        }
        table.custom-subject {
            margin-top: 2mm;
        }
        .director-signature {
            margin-right: 0mm;
        }
    </style>

    @yield('after-styles-end')

</head>
<body class="skin-{!! config('backend.theme') !!}">
    @yield('content')
</body>
@yield('scripts')
</html>
