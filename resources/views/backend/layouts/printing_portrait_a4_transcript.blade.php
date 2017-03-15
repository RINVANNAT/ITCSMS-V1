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
        <link rel="stylesheet" media="print, screen" href="{{ url('css/backend/printing_portrait_a4_transcript.css') }}">

        @yield('after-styles-end')

    </head>
    <body class="skin-{!! config('backend.theme') !!}" style="font-family: khmeros" onload="window.print()">

        @yield('content')
    </body>
    <script>window.jQuery || document.write('<script src="{{asset('js/vendor/jquery/jquery-2.1.4.min.js')}}"><\/script>')</script>
    @yield('scripts')
</html>