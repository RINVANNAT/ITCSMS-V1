@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.employees.title'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.employees.title') }}
        <small>{{ trans('labels.backend.employees.sub_index_title') }}</small>
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
                <a href="{!! route('admin.employees.create') !!}">
                    <button class="btn btn-primary btn-sm"><i class="fa fa-plus-circle"></i> Add
                    </button>
                </a>
                <a href="{!! route('admin.employee.request_import') !!}">
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
                <table class="table table-striped table-bordered table-hover dt-responsive nowrap" cellspacing="0" width="100%" id="employees-table">
                    <thead>
                    <tr>
                        <th>{{ trans('labels.backend.employees.fields.name_kh') }}</th>
                        <th>{{ trans('labels.backend.employees.fields.name_latin') }}</th>
                        <th>{{ trans('labels.backend.employees.fields.email') }}</th>
                        <th>{{ trans('labels.backend.employees.fields.phone') }}</th>
                        <th>{{ trans('labels.backend.employees.fields.department_id') }}</th>
                        <th>{{ trans('labels.backend.employees.fields.role_id') }}</th>
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
            $('#employees-table').DataTable({
                processing: true,
                serverSide: true,
                pageLength: {!! config('app.records_per_page')!!},
                ajax: '{!! route('admin.employee.data') !!}',
                columns: [
                    { data: 'name_kh', name: 'name_kh'},
                    { data: 'name_latin', name: 'name_latin'},
                    { data: 'email', name: 'email'},
                    { data: 'phone', name: 'phone'},
                    { data: 'department_id', name: 'department_id'},
                    { data: 'roles', name: 'roles', orderable:false,searchable:false},
                    { data: 'action', name: 'action',orderable: false, searchable: false}
                ]
            });

            enableDeleteRecord($('#employees-table'));
        });
    </script>
@stop
