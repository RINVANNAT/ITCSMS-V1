@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.departments.index_title'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.departments.index_title') }}
        <small>{{ trans('labels.backend.departments.sub_index_title') }}</small>
    </h1>

@endsection

@section('after-styles-end')
    {!! Html::style('plugins/datatables/dataTables.bootstrap.css') !!}
@stop

@section('content')
    <div class="box box-success">
        <div class="box-header with-border">
            <div class="mailbox-controls">
                <!-- Check all button -->
                <a href="{!! route('admin.configuration.departments.create') !!}">
                    <button class="btn btn-primary btn-sm"><i class="fa fa-plus-circle"></i> Add
                    </button>
                </a>
                <a href="#">
                    <button class="btn btn-primary btn-sm"><i class="fa fa-plus-circle"></i> Import
                    </button>
                </a>

                <div class="btn-group">
                    <button class="btn btn-default btn-sm"><i class="fa fa-trash-o"></i></button>
                    <button class="btn btn-default btn-sm"><i class="fa fa-reply"></i></button>
                    <button class="btn btn-default btn-sm"><i class="fa fa-share"></i></button>
                </div>
                <!-- /.btn-group -->
                <button class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></button>

            </div>


        </div><!-- /.box-header -->

        <div class="box-body">
            <div>
                <table class="table table-striped table-bordered table-hover" id="students-table">
                    <thead>
                    <tr>
                        <th>{{ trans('labels.backend.students.fields.id_card') }}</th>
                        <th>{{ trans('labels.backend.students.fields.name_kh') }}</th>
                        <th>{{ trans('labels.backend.students.fields.name_latin') }}</th>
                        <th>{{ trans('labels.backend.students.fields.class') }}</th>
                        <th>{{ trans('labels.general.actions') }}</th>
                    </tr>
                    </thead>
                </table>
            </div>

            <div class="clearfix"></div>
        </div><!-- /.box-body -->
    </div><!--box-->
@stop

@section('after-scripts-end')
    {!! Html::script('plugins/datatables/jquery.dataTables.min.js') !!}
    {!! Html::script('plugins/datatables/dataTables.bootstrap.min.js') !!}
    <script>
        $(function() {

            $('#students-table').DataTable({
                processing: true,
                serverSide: true,
                pageLength: {!! config('app.records_per_page')!!},
                ajax: '{!! route('student_datatable.data') !!}',
                columns: [
                    { data: 'id_card', name: 'students.id_card',searchable:false },
                    { data: 'name_kh', name: 'students.name_kh',searchable:false },
                    { data: 'name_latin', name: 'students.name_latin', searchable:false},
                    { data: 'class' , name: 'class', searchable:false}
                ]
            });
        });
    </script>
@stop
