@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.buildings.title'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.buildings.title') }}
        <small>{{ trans('labels.backend.buildings.sub_index_title') }}</small>
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
                <a href="{!! route('admin.configuration.buildings.create') !!}">
                    <button class="btn btn-primary btn-sm"><i class="fa fa-plus-circle"></i> Add
                    </button>
                </a>

                <a href="{!! route('admin.configuration.buildings.request_import') !!}">
                    <button class="btn btn-primary btn-sm"><i class="fa fa-plus-circle"></i> Import
                    </button>
                </a>

                <!-- /.btn-group -->
                <button class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></button>

            </div>


        </div><!-- /.box-header -->

        <div class="box-body">
            <div>
                <table class="table table-striped table-bordered table-hover dt-responsive nowrap" cellspacing="0" width="100%" id="buildings-table">
                    <thead>
                    <tr>
                        <th>{{ trans('labels.general.id') }}</th>
                        <th>{{ trans('labels.backend.buildings.fields.name') }}</th>
                        <th>{{ trans('labels.backend.buildings.fields.code') }}</th>
                        <th>{{ trans('labels.backend.buildings.fields.description') }}</th>
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
            $('#buildings-table').DataTable({
                processing: true,
                serverSide: true,
                pageLength: {!! config('app.records_per_page')!!},
                ajax: {
                    url: '{!! route('admin.configuration.building.data') !!}',
                    method: 'POST'
                },
                columns: [
                    { data: 'id', name: 'id'},
                    { data: 'name', name: 'name'},
                    { data: 'code', name: 'code'},
                    { data: 'description', name: 'description'},
                    { data: 'action', name: 'action',orderable: false, searchable: false}
                ]
            });

            enableDeleteRecord($('#buildings-table'));
        });
    </script>
@stop
