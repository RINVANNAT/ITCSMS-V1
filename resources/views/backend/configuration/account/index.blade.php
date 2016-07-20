@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.accounts.title'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.accounts.title') }}
        <small>{{ trans('labels.backend.accounts.sub_index_title') }}</small>
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
                <a href="{!! route('admin.configuration.accounts.create') !!}">
                    <button class="btn btn-primary btn-sm"><i class="fa fa-plus-circle"></i> Add
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
                <table class="table table-striped table-bordered table-hover dt-responsive nowrap" cellspacing="0" width="100%" id="accounts-table">
                    <thead>
                    <tr>
                        <th>{{ trans('labels.backend.accounts.fields.name') }}</th>
                        <th>{{ trans('labels.backend.accounts.fields.amount_dollar') }}</th>
                        <th>{{ trans('labels.backend.accounts.fields.amount_riel') }}</th>
                        <th>{{ trans('labels.backend.accounts.fields.active') }}</th>
                        <th>{{ trans('labels.backend.accounts.fields.description') }}</th>
                        <th>{{ trans('labels.general.actions') }}</th>
                        <th>{{ trans('labels.general.last_updated') }}</th>
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
            $('#accounts-table').DataTable({
                processing: true,
                serverSide: true,
                pageLength: {!! config('app.records_per_page')!!},
                ajax: {
                    url: '{!! route('admin.configuration.account.data') !!}',
                    method: 'POST'
                },
                columns: [
                    { data: 'name', name: 'name'},
                    { data: 'amount_dollar', name: 'amount_dollar'},
                    { data: 'amount_riel', name: 'amount_riel'},
                    { data: 'active', name: 'active'},
                    { data: 'description', name: 'description'},
                    { data: 'updated_at', name: 'updated_at'},
                    { data: 'action', name: 'action',orderable: false, searchable: false}
                ]
            });

            enableDeleteRecord($('#accounts-table'));
        });
    </script>
@stop
