@extends ('backend.layouts.master')

@section('page-header')
    <h1>
        {{ trans('labels.backend.employees.title') }}
        <small>{{ trans('labels.backend.employees.sub_create_title') }}</small>
    </h1>
@endsection

@section('after-styles-end')
    {!! Html::style('css/backend/plugin/jstree/themes/default/style.min.css') !!}
@stop

@section('content')
    @if (Session::has('flash_notification.message'))
        <div class="alert alert-{{ Session::get('flash_notification.level') }}">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>

            {{ Session::get('flash_notification.message') }}
        </div>
    @endif

    {!! Form::open(['route' => 'admin.employee.import','id' => 'import_form_student', 'role'=>'form','files' => true])!!}
    <div class="box box-success">


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
                <a href="{!! route('admin.studentAnnuals.index') !!}" class="btn btn-danger btn-xs">{{ trans('buttons.general.cancel') }}</a>
            </div>

            <div class="pull-right">
                <input type="submit" class="btn btn-success btn-xs" id="submit_student_import" value="{{ trans('buttons.general.import') }}" />
            </div>
            <div class="clearfix"></div>
        </div><!-- /.box-body -->
    </div><!--box-->
    {!! Form::close() !!}
@stop

@section('after-scripts-end')
    {!! Html::script('js/backend/plugin/jstree/jstree.min.js') !!}
    {!! Html::script('js/backend/access/roles/script.js') !!}

    <script>
        $(function(){
            $('#submit_student_import').on('click',function(){
                toggleLoading(true);
            });
        });
    </script>

@stop