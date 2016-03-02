@extends('frontend.layouts.master')
@section('after-styles-end')
    <style>
        .feature-item {
            margin-bottom: 40px;
            text-align: center;
        }

        .feature-item .fa {
            font-size: 25px;
            border-radius: 5px;
            color: #3c8dbc;
            display: block;
            margin: 0 auto;
            transition: background-color 0.25s linear;
        }
    </style>
@endsection


@section('content')
    <div class="row">

        <div class="col-md-10 col-md-offset-1">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-home"></i> {{ trans('navs.frontend.home') }}
                </div>

                <div class="panel-body">
                    {{ trans('strings.frontend.welcome_to', ['place' => app_name()]) }}
                </div>
            </div><!-- panel -->

        </div><!-- col-md-10 -->

        <div class="col-md-12">
            <h2 class="text-center" style="margin-bottom: 40px"><b>ITC-SMS</b> Features</h2>

            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-4">
                    <div class="feature-item animated fadeInUp delay-3">
                        <i class="fa fa-css3"></i>
                        <h4>Entrance Exam</h4>

                        <p>
                            Register new candidate, validate score, and generate successful student to enter itc for the new academic.
                        </p>
                    </div>
                </div>
                <!-- /.item -->

                <div class="col-xs-12 col-sm-6 col-md-4">
                    <div class="feature-item animated fadeInUp delay-3">
                        <i class="fa fa-laptop"></i>
                        <h4>Student Payment</h4>

                        <p>
                            Register and manage the student's payment. It is equipped with reminder and alert to particular student.
                        </p>
                    </div>
                </div>
                <!-- /.item -->
                <div class="clearfix visible-sm"></div>
                <div class="col-xs-12 col-sm-6 col-md-4">
                    <div class="feature-item animated fadeInUp delay-3">
                        <i class="fa fa-rocket"></i>
                        <h4>Student Management</h4>

                        <p>
                            Featuring Font Awesome, Ion Icons, and Glyphicons.
                        </p>
                    </div>
                </div>
                <!-- /.item -->
                <div class="clearfix visible-md visible-lg"></div>
                <div class="col-xs-12 col-sm-6 col-md-4">
                    <div class="feature-item animated fadeInUp delay-3">
                        <i class="fa fa-paint-brush"></i>
                        <h4>Time Table</h4>

                        <p>
                            Choose a skin that matches your branding or
                            edit the LESS variables to create your own.
                        </p>
                    </div>
                </div>
                <!-- /.item -->
                <div class="clearfix visible-sm"></div>
                <div class="col-xs-12 col-sm-6 col-md-4">
                    <div class="feature-item animated fadeInUp delay-3">
                        <i class="fa fa-print"></i>
                        <h4>Calendar</h4>

                        <p>
                            Support for printing any page. The invoice page
                            makes a perfect example.
                        </p>
                    </div>
                </div>
                <!-- /.item -->

                <div class="col-xs-12 col-sm-6 col-md-4">
                    <div class="feature-item animated fadeInUp delay-3">
                        <i class="fa fa-bolt"></i>
                        <h4>Score Management</h4>

                        <p>
                            Although AdminLTE is full of features, it was
                            designed to be fast and lightweight.
                        </p>
                    </div>
                </div>
                <!-- /.item -->
                <div class="clearfix visible-sm visible-md visible-lg"></div>
                <div class="col-xs-12 col-sm-6 col-md-4">
                    <div class="feature-item animated fadeInUp delay-3">
                        <i class="fa fa-globe"></i>
                        <h4>Course Management and Assignment</h4>

                        <p>
                            Support for most major browsers including Safari, IE9+, Chrome, FF, and Opera.
                        </p>
                    </div>
                </div>
                <!-- /.item -->

                <div class="col-xs-12 col-sm-6 col-md-4">
                    <div class="feature-item animated fadeInUp delay-3">
                        <i class="fa fa-th"></i>
                        <h4>Inventory Management</h4>

                        <p>
                            Over 18 plugins and an aditional 3 custom made
                            plugins just for AdminLTE.
                        </p>
                    </div>
                </div>
                <!-- /.item -->
                <div class="clearfix visible-sm"></div>
                <div class="col-xs-12 col-sm-6 col-md-4">
                    <div class="feature-item animated fadeInUp delay-3">
                        <i class="fa fa-users"></i>
                        <h4>Scholarship Managment</h4>

                        <p>
                            Have a suggestion or an issue?
                            Visit our <a href="https://github.com/almasaeed2010/AdminLTE/issues">Github</a>
                            repository to get help.
                        </p>
                    </div>
                </div>
        </div>

@endsection

@section('after-scripts-end')
    <script>
        //Being injected from FrontendController
        console.log(test);
    </script>
@stop