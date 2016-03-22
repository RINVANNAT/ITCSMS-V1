@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.outcomeTypes.title'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.outcomeTypes.title') }}
        <small>{{ trans('labels.backend.outcomeTypes.sub_index_title') }}</small>
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
                <a href="{!! route('admin.configuration.outcomeTypes.create') !!}">
                    <button class="btn btn-primary btn-sm"><i class="fa fa-plus-circle"></i> Add
                    </button>
                </a>
                <a href="{!! route('admin.outcomeType.request_import') !!}">
                    <button class="btn btn-primary btn-sm"><i class="fa fa-plus-circle"></i> Import
                    </button>
                </a>
                <!-- /.btn-group -->
                <button class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></button>

            </div>


        </div><!-- /.box-header -->

        <div class="box-body">
            <div>
                <table class="table table-striped table-bordered table-hover dt-responsive nowrap" cellspacing="0" width="100%" id="outcomeTypes-table">
                    <thead>
                    <tr>
                        <th>{{ trans('labels.backend.outcomeTypes.fields.code') }}</th>
                        <th>{{ trans('labels.backend.outcomeTypes.fields.name') }}</th>
                        <th>{{ trans('labels.backend.outcomeTypes.fields.description') }}</th>
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
            $('#outcomeTypes-table').DataTable({
                processing: true,
                serverSide: true,
                pageLength: {!! config('app.records_per_page')!!},
                ajax: '{!! route('admin.configuration.outcomeType.data') !!}',
                columns: [
                    { data: 'code', name: 'code'},
                    { data: 'name', name: 'name'},
                    { data: 'description', name: 'description'},
                    { data: 'action', name: 'action',orderable: false, searchable: false}
                ]
            });
            enableDeleteRecord($('#outcomeTypes-table'));
        });
    </script>
@stop
