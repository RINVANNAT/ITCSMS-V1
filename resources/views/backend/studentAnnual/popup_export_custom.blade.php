@extends ('backend.layouts.popup_master')

@section ('title', trans('labels.backend.candidates.title') . ' | ' . trans('labels.backend.candidates.sub_create_title'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.candidates.title') }}
        <small>{{ trans('labels.backend.candidates.sub_create_title') }}</small>
    </h1>
@endsection

@section('after-styles-end')
    {!! Html::style('plugins/datatables/dataTables.bootstrap.css') !!}
@stop

@section('content')

    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">Selected student to export:</h3>
            <div class="pull-right">
                <button class="btn btn-sm" id="btn-export">Export</button>
            </div>
        </div><!-- /.box-header -->

        <div class="box-body">
            <table class="table table-striped table-bordered table-hover dt-responsive nowrap" cellspacing="0" width="100%" id="students-table">
                <thead>
                <tr>
                    <th>id</th>
                    <th>{{ trans('labels.backend.students.fields.id_card') }}</th>
                    <th>{{ trans('labels.backend.students.fields.name_kh') }}</th>
                    <th>{{ trans('labels.backend.students.fields.name_latin') }}</th>
                    <th>{{ trans('labels.backend.students.fields.dob') }}</th>
                    <th>{{ trans('labels.backend.students.fields.gender_id') }}</th>
                    <th>{{ trans('labels.backend.students.fields.class') }}</th>
                    <th>{{ trans('labels.backend.students.fields.department_option_id') }}</th>
                </tr>
                </thead>
            </table>

            <div class="clearfix"></div>
        </div><!-- /.box-body -->
    </div><!--box-->

@stop

@section('after-scripts-end')
    {!! Html::script('plugins/datatables/jquery.dataTables.min.js') !!}
    {!! Html::script('plugins/datatables/dataTables.bootstrap.min.js') !!}
    <script>
        var oTable = $('.table').DataTable({
            columns:[
                {data: 'id'},
                {data: 'id_card'},
                {data: "name_kh"},
                {data: "name_latin"},
                {data: "dob"},
                {data: "gender"},
                {data: "class"},
                {data: "option"}
            ]
        });
        function addRow(data){
            oTable.rows.add(
                data
            ).draw();
        }

        $('#btn-export').on('click',function(){
        });
        window.onbeforeunload = function(){
            console.log('close window');
            window.opener.hideCustomExport();
        }

    </script>
@stop