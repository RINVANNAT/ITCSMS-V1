@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.academicYears.title') . ' | ' . trans('labels.backend.academicYears.sub_create_title'))
@section('after-styles-end')
    {!! Html::style('plugins/daterangepicker/daterangepicker-bs3.css') !!}
@endsection
@section('page-header')
    <h1>
        {{ trans('labels.backend.academicYears.title') }}
        <small>{{ trans('labels.backend.academicYears.sub_create_title') }}</small>
    </h1>
@endsection
    {!! Html::style('plugins/datetimepicker/bootstrap-datetimepicker.min.css') !!}
@section('after-styles-end')
    {!! Html::style('css/backend/plugin/jstree/themes/default/style.min.css') !!}
@stop

@section('content')
    {!! Form::model($academicYear, ['route' => ['admin.configuration.academicYears.update', $academicYear->id],'class' => 'form-horizontal', 'role'=>'form', 'method' => 'patch']) !!}

        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">{{ trans('labels.backend.academicYears.sub_create_title') }}</h3>
            </div><!-- /.box-header -->

            <div class="box-body">
                @include ("backend.configuration.academicYear.fields")
            </div><!-- /.box-body -->
        </div><!--box-->

        <div class="box box-success">
            <div class="box-body">
                <div class="pull-left">
                    <a href="{!! route('admin.configuration.academicYears.index') !!}" class="btn btn-danger btn-xs">{{ trans('buttons.general.cancel') }}</a>
                </div>

                <div class="pull-right">
                    <input type="submit" class="btn btn-success btn-xs" value="{{ trans('buttons.general.crud.create') }}" />
                </div>
                <div class="clearfix"></div>
            </div><!-- /.box-body -->
        </div><!--box-->
    {!! Form::close() !!}
@stop

@section('after-scripts-end')
    {!! Html::script('plugins/moment/moment.min.js') !!}
    {!! Html::script('plugins/daterangepicker/daterangepicker.js') !!}

    <script>
        $(function(){
            //$('#date_start').datetimepicker();
            $('#date_start_end').daterangepicker({
                format: 'DD/MM/YYYY',
            });

            $("#code").keydown(function (e) {
                allowNumberOnly(e);
            });
        });
    </script>
@stop