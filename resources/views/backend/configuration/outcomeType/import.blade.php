@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.outcomeTypes.title') . ' | ' . trans('labels.backend.outcomeTypes.sub_import_title'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.outcomeTypes.title') }}
        <small>{{ trans('labels.backend.outcomeTypes.sub_import_title') }}</small>
    </h1>
@endsection

@section('content')

    {!! Form::open(['route' => 'admin.outcomeType.import','id' => 'import_form_student', 'role'=>'form','files' => true])!!}
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('labels.backend.outcomeTypes.sub_import_title') }}</h3>
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