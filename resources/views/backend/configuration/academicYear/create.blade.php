@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.academicYears.title') . ' | ' . trans('labels.backend.academicYears.sub_create_title'))
@section('after-styles-end')

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
    {!! Form::open(['route' => 'admin.configuration.academicYears.store', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post', 'id' => 'create-role']) !!}

        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">{{ trans('labels.backend.academicYears.sub_create_title') }}</h3>
    
                <div class="box-tools pull-right">
                    @include('backend.access.includes.partials.header-buttons')
                </div>
            </div><!-- /.box-header -->

            <div class="box-body">
                <div class="form-group">
                    {!! Form::label('name', trans('labels.backend.academicYears.fields.code'), ['class' => 'col-lg-2 control-label']) !!}
                    <div class="col-lg-10">
                        {!! Form::text('code', null, ['class' => 'form-control']) !!}
                    </div>
                </div><!--form control-->

                <div class="form-group">
                    {!! Form::label('name', trans('labels.backend.academicYears.fields.name_kh'), ['class' => 'col-lg-2 control-label']) !!}
                    <div class="col-lg-10">
                        {!! Form::text('name_kh', null, ['class' => 'form-control']) !!}
                    </div>
                </div><!--form control-->

                <div class="form-group">
                    {!! Form::label('name', trans('labels.backend.academicYears.fields.name_latin'), ['class' => 'col-lg-2 control-label']) !!}
                    <div class="col-lg-10">
                        {!! Form::text('name_en', null, ['class' => 'form-control']) !!}
                    </div>
                </div><!--form control-->
                <div class="form-group">
                    {!! Form::label('name', trans('labels.backend.academicYears.fields.date_start'), ['class' => 'col-lg-2 control-label']) !!}
                    <div class="col-lg-10">
                        <input type='text' name="date_start" class="form-control" id='date_start' />
                    </div>
                </div><!--form control-->
                <div class="form-group">
                    {!! Form::label('name', trans('labels.backend.academicYears.fields.date_end'), ['class' => 'col-lg-2 control-label']) !!}
                    <div class="col-lg-10">
                        {!! Form::text('date_end', null, ['class' => 'form-control date-form','required'=>'required']) !!}
                    </div>
                </div><!--form control-->
            </div><!-- /.box-body -->
        </div><!--box-->

        <div class="box box-success">
            <div class="box-body">
                <div class="pull-left">
                    <a href="{!! route('admin.access.roles.index') !!}" class="btn btn-danger btn-xs">{{ trans('buttons.general.cancel') }}</a>
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
            $('#date_start').datetimepicker();
        });
    </script>
@stop