@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.employees.title') . ' | ' . trans('labels.backend.employees.sub_create_title'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.employees.title') }}
        <small>{{ trans('labels.backend.employees.sub_create_title') }}</small>
    </h1>
@endsection

@section('after-styles-end')
    {!! Html::style('plugins/datetimepicker/bootstrap-datetimepicker.min.css') !!}
@endsection

@section('content')
    {!! Form::open(['route' => 'admin.employees.store', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post', 'id' => 'create-role']) !!}

        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">{{ trans('labels.backend.employees.sub_create_title') }}</h3>
            </div><!-- /.box-header -->

            <div class="box-body">
                @include('backend.employee.fields')
            </div><!-- /.box-body -->
        </div><!--box-->

        <div class="box box-success">
            <div class="box-body">
                <div class="pull-left">
                    <a href="{!! route('admin.employees.index') !!}" class="btn btn-danger btn-xs">{{ trans('buttons.general.cancel') }}</a>
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
    {!! Html::script('plugins/datetimepicker/bootstrap-datetimepicker.min.js') !!}

    <script>
        $(function(){
            $('#birthdate').datetimepicker({
                format: 'DD/MM/YYYY'
            });
        });
    </script>
@stop