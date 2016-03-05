@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.incomes.title'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.incomes.title') }}
        <small>{{ trans('labels.backend.incomes.sub_index_title') }}</small>
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
                <a href="{!! route('admin.accounting.incomes.create') !!}">
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
                <table class="table table-striped table-bordered table-hover" id="incomes-table">
                    <thead>
                    <tr>
                        <th>{{ trans('labels.backend.incomes.fields.number') }}</th>
                        <th>{{ trans('labels.backend.incomes.fields.amount_dollar') }}</th>
                        <th>{{ trans('labels.backend.incomes.fields.amount_riel') }}</th>
                        <th>{{ trans('labels.backend.incomes.fields.account_id') }}</th>
                        <th>{{ trans('labels.backend.incomes.fields.payslip_client_id') }}</th>
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
            $('#incomes-table').DataTable({
                processing: true,
                serverSide: true,
                pageLength: {!! config('app.records_per_page')!!},
                ajax: '{!! route('admin.accounting.income.data') !!}',
                columns: [
                    { data: 'number', name: 'number'},
                    { data: 'amount_dollar', name: 'amount_dollar'},
                    { data: 'amount_riel', name: 'amount_riel'},
                    { data: 'account_id', name: 'account_id'},
                    { data: 'payslip_client_id', name: 'payslip_client_id'},
                    { data: 'action', name: 'action',orderable: false, searchable: false}
                ]
            });

            enableDeleteRecord($('#incomes-table'));
        });
    </script>
@stop
