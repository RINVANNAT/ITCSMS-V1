@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.highSchools.title') . ' | ' . trans('labels.backend.highSchools.sub_import_title'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.highSchools.title') }}
        <small>{{ trans('labels.backend.highSchools.sub_import_title') }}</small>
    </h1>
@endsection

@section('content')

    {!! Form::open(['route' => 'admin.configuration.highSchool.import','id' => 'import_form_highSchool', 'role'=>'form','files' => true])!!}
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('labels.backend.highSchools.sub_import_title') }}</h3>
        </div><!-- /.box-header -->

        <div class="box-body">
            <div class="row no-margin">
                <div class="form-group col-sm-12" style="padding: 20px;">
                    <span>Select the .CSV file to import. if you need a sample importable file, you can use the export tool to generate one.</span>
                </div>
            </div>

            <div class="row no-margin" style="padding-left: 20px;padding-right: 20px;">
                <div class="form-group col-sm-12 box-body with-border text-muted well well-sm no-shadow" style="padding: 20px;">
                    {!! Form::label('import','Selected File (csv, xls, xlsx)') !!}
                    {!! Form::file('import', null) !!}
                </div>

            </div>
        </div><!-- /.box-body -->
    </div><!--box-->

    <div class="box box-success">
        <div class="box-body">
            <div class="pull-left">
                <a href="{!! route('admin.configuration.highSchools.index') !!}" class="btn btn-danger btn-xs">{{ trans('buttons.general.cancel') }}</a>
            </div>

            <div class="pull-right">
                <input type="submit" class="btn btn-success btn-xs" value="{{ trans('buttons.general.import') }}" />
            </div>
            <div class="clearfix"></div>
        </div><!-- /.box-body -->
    </div><!--box-->
    {!! Form::close() !!}
@stop

@section('after-scripts-end')
    {!! Html::script('js/backend/plugin/jstree/jstree.min.js') !!}
    {!! Html::script('js/backend/access/roles/script.js') !!}
@stop