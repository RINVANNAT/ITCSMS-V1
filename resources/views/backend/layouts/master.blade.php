<!doctype html>
<html class="no-js" lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="_token" content="{{ csrf_token() }}" />

        <title>@yield('title', app_name())</title>

        <!-- Meta -->
        <meta name="description" content="@yield('meta_description', 'ITC School Management System')">
        <meta name="author" content="@yield('meta_author', 'ITC SMIS')">
        @yield('meta')

        <!-- Styles -->
        @yield('before-styles-end')
        {!! Html::style(elixir('css/backend.css')) !!}
        <link rel="stylesheet" href="{{url('assets/js/mcustomsearch/style.css')}}">
        <link rel="stylesheet" href="{{url('assets/js/handlebars/groupselector.css')}}">
        <link rel="stylesheet" href="{{url('plugins/toastr/toastr.min.css')}}">


        <style>
            .content-wrapper, .modal-content{
                background-image: url("/img/bg_contit.gif"); !important;
            }
            .highlight{
                background-color: #BFE2F0;
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
    <body class="skin-{!! config('backend.theme') !!} {!! config('backend.layout') !!}">

    <div class="loading">
        <i class="fa fa-refresh fa-spin"></i>
    </div>

    <div class="wrapper">
        @include('backend.includes.header')
        @include('backend.includes.sidebar')


        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            @yield('message')
            <!-- Content Header (Page header) -->
            <section class="content-header">
                @yield('page-header')

                {{-- Change to Breadcrumbs::render() if you want it to error to remind you to create the breadcrumbs for the given route --}}
                {!! Breadcrumbs::renderIfExists() !!}
            </section>

            <!-- Main content -->
            <section class="content">
                @include('includes.partials.messages')
                @yield('content')
            </section><!-- /.content -->
        </div><!-- /.content-wrapper -->

        @include('backend.includes.footer')
    </div><!-- ./wrapper -->

    <!-- JavaScripts -->
    {!! Html::script('plugins/jquery.min.js') !!}
    <script>window.jQuery || document.write('<script src="{{asset('js/vendor/jquery/jquery-2.1.4.min.js')}}"><\/script>')</script>
    {!! Html::script('plugins/jQueryUI/jquery-ui.js') !!}
    {!! Html::script('js/vendor/bootstrap/bootstrap.min.js') !!}
    {!! Html::script('plugins/slimScroll/jquery.slimscroll.min.js') !!}
    {!! Html::script('plugins/toastr/toastr.min.js') !!}
    {!! Html::script('plugins/jquery.validate.min.js') !!}


    @yield('before-scripts-end')
    {!! HTML::script(elixir('js/backend.js')) !!}
    {{--<script src="{{url('js/custom_v1.js')}}"></script>--}}

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