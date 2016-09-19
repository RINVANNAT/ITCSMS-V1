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
        </div><!-- /.box-body -->
    </div><!--box box-success-->
@endsection

@section('after-scripts-end')
    <script>
    </script>
@stop