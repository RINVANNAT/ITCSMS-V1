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
        <link rel="stylesheet" href="{{url('plugins/toastr/toastr.min.css')}}">
        <style>
            .content-wrapper{
                background-image: url("/img/bg_contit.gif"); !important;
            }
        </style>
        @yield('after-styles-end')

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
    <div class="loading">
        <i class="fa fa-refresh fa-spin"></i>
    </div>
    <div class="wrapper">

        <div class="content-wrapper" style="margin: 0px;">
            <!-- Main content -->
            <section class="content">
                @include('includes.partials.messages')
                @yield('content')
            </section><!-- /.content -->
        </div><!-- /.content-wrapper -->
    </div><!-- ./wrapper -->

    <!-- JavaScripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="{{asset('js/vendor/jquery/jquery-2.1.4.min.js')}}"><\/script>')</script>
    {!! Html::script('js/vendor/bootstrap/bootstrap.min.js') !!}
    {!! Html::script('plugins/toastr/toastr.min.js') !!}

    @yield('before-scripts-end')
    {!! HTML::script(elixir('js/backend.js')) !!}
    {!! HTML::script('js/custom.js') !!}

    <script>
        var needConfirm = false;
        window.onbeforeunload = confirmExit;
        function confirmExit()
        {
            if(needConfirm){
                return "Data isn't saved yet. ";
            }
        }

        $(document).ready(function(){
            toggleLoading(false);
        });

    </script>
    @yield('after-scripts-end')
    </body>
</html>