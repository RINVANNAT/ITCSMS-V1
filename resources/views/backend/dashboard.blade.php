@extends('backend.layouts.master')

@section('page-header')
    <h1>
        {!! app_name() !!}
        <small>{{ trans('strings.backend.dashboard.title') }}</small>
    </h1>
@endsection

@section('content')
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('strings.backend.dashboard.welcome') }} {!! access()->user()->name !!}!</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div><!-- /.box tools -->
        </div><!-- /.box-header -->
        <div class="box-body">


            <div class="row">
                <div class="col-md-8">
                    <p>
                        Welcome to ITC-School Management Information System. This application is under construction with partial release.
                    </p>
                    <p>
                        Please report the problems or your demanding to our developers by using this <a href="{{route('admin.reporting.index')}}">REPORTING SYSTEM</a>.
                    </p>
                    <p>
                        We appreciate your contributions and we hope to run this system in full scale very soon.
                    </p>
                    <p>
                        - Developer Team
                    </p>

                    <div class="chart">
                        <!-- Sales Chart Canvas -->
                        <canvas id="salesChart" style="height: 180px; width: 882px;" height="180" width="882"></canvas>
                    </div>
                    <!-- /.chart-responsive -->
                </div>
                <!-- /.col -->
                <div class="col-md-4">
                    <p class="text-center">
                        <strong>Goal Completion</strong>
                    </p>

                    <div class="progress-group">
                        <span class="progress-text">Add Products to Cart</span>
                        <span class="progress-number"><b>160</b>/200</span>

                        <div class="progress sm">
                            <div class="progress-bar progress-bar-aqua" style="width: 80%"></div>
                        </div>
                    </div>
                    <!-- /.progress-group -->
                    <div class="progress-group">
                        <span class="progress-text">Complete Purchase</span>
                        <span class="progress-number"><b>310</b>/400</span>

                        <div class="progress sm">
                            <div class="progress-bar progress-bar-red" style="width: 80%"></div>
                        </div>
                    </div>
                    <!-- /.progress-group -->
                    <div class="progress-group">
                        <span class="progress-text">Visit Premium Page</span>
                        <span class="progress-number"><b>480</b>/800</span>

                        <div class="progress sm">
                            <div class="progress-bar progress-bar-green" style="width: 80%"></div>
                        </div>
                    </div>
                    <!-- /.progress-group -->
                    <div class="progress-group">
                        <span class="progress-text">Send Inquiries</span>
                        <span class="progress-number"><b>250</b>/500</span>

                        <div class="progress sm">
                            <div class="progress-bar progress-bar-yellow" style="width: 80%"></div>
                        </div>
                    </div>
                    <!-- /.progress-group -->
                </div>
                <!-- /.col -->
            </div>


            <!-- /.row -->
        </div><!-- /.box-body -->
    </div><!--box box-success-->
@endsection

@section('after-scripts-end')
    <script>
    </script>
@stop