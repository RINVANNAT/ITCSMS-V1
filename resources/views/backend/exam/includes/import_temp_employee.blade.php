@extends ('backend.layouts.popup_master')

@section ('title', 'Temporary Employees'. ' | ' . 'import temporary employees')

@section('content')

            {!! Form::open(['route' => ['admin.exam.temp_employee.import', $exam_id],'id' => 'import_form_temporary_employee', 'role'=>'form','files' => true])!!}
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('labels.backend.rooms.sub_import_title') }}</h3>
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
                        <button class="btn btn-danger btn-xs" id="cancel_import">cancel</button>
                    </div>

                    <div class="pull-right">
                        <input type="submit" class="btn btn-success btn-xs" id = "submit_temp_employee" value="{{ trans('buttons.general.import') }}"/>
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
        $('#cancel_import').on('click', function() {
            window.close();
        });

        $(function(){
            $('#submit_temp_employee').on('click',function(){
                toggleLoading(true);
            });
        });


    </script>
@stop