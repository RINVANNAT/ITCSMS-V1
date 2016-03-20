@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.error.reporting.title'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.error.reporting.title') }}
        <small>{{ trans('labels.backend.error.reporting.sub_index_title') }}</small>
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
                <a href="{!! route('admin.reporting.create') !!}">
                    <button class="btn btn-primary btn-sm"><i class="fa fa-plus-circle"></i> Add
                    </button>
                </a>
                <button class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></button>

            </div>


        </div><!-- /.box-header -->

        <div class="box-body">
            <div>
                @include('backend.reporting.includes.index_table_header')
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
            $('#reporting-table').DataTable({
                processing: true,
                serverSide: true,
                pageLength: {!! config('app.records_per_page')!!},
                ajax: '{!! route('admin.reporting.data') !!}',
                columns: [
                    { data: 'title', name: 'title'},
                    { data: 'description', name: 'description'},
                    { data: 'status', name: 'status'},
                    { data: 'created_at', name: 'created_at'},
                    { data: 'action', name: 'action',orderable: false, searchable: false}
                ]
            });
            enableDeleteRecord($('#reporting-table'));
        });
    </script>
@stop
