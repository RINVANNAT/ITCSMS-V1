@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.departments.title') . ' | ' . trans('labels.backend.departments.sub_edit_title'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.departments.title') }}
        <small>{{ trans('labels.backend.departments.sub_edit_title') }}</small>
    </h1>
@endsection
    {!! Html::style('plugins/datetimepicker/bootstrap-datetimepicker.min.css') !!}
@section('after-styles-end')
    {!! Html::style('css/backend/plugin/jstree/themes/default/style.min.css') !!}
@stop

@section('content')
    {!! Form::model($department, ['route' => ['admin.configuration.departments.update', $department->id],'class' => 'form-horizontal', 'role'=>'form', 'method' => 'patch']) !!}

        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">{{ trans('labels.backend.departments.sub_edit_title') }}</h3>
            </div><!-- /.box-header -->

            <div class="box-body">
                @include ("backend.configuration.department.fields")
            </div><!-- /.box-body -->
        </div><!--box-->

        <div class="box box-success">
            <div class="box-body">
                <div class="pull-left">
                    <a href="{!! route('admin.configuration.departments.index') !!}" class="btn btn-danger btn-xs">{{ trans('buttons.general.cancel') }}</a>
                </div>

                <div class="pull-right">
                    <input type="submit" class="btn btn-info btn-xs" value="{{ trans('buttons.general.crud.update') }}" />
                </div>
                <div class="clearfix"></div>
            </div><!-- /.box-body -->
        </div><!--box-->
    {!! Form::close() !!}
@stop
