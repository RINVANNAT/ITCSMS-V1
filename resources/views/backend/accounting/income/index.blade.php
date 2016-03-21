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
    {!! Html::style('plugins/daterangepicker/daterangepicker-bs3.css') !!}
    <style>
        .toolbar {
            float: left;
        }
    </style>
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
            <div class="box-tools pull-right">
                @include('backend.accounting.income.includes.partials.header-buttons')
            </div>


        </div><!-- /.box-header -->

        <div class="box-body">
            <div>
                <table class="table table-striped table-bordered table-hover" id="incomes-table">
                    <thead>
                    <tr>
                        <th>{{ trans('labels.backend.accounting.fields.number') }}</th>
                        <th>{{ trans('labels.backend.accounting.fields.amount_dollar') }}</th>
                        <th>{{ trans('labels.backend.accounting.fields.amount_riel') }}</th>
                        <th>{{ trans('labels.backend.accounting.fields.account_id') }}</th>
                        <th>{{ trans('labels.backend.accounting.fields.incomeType_id') }}</th>
                        <th>{{ trans('labels.backend.accounting.fields.payslip_client_id') }}</th>
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
    {!! Html::script('plugins/moment/moment.min.js') !!}
    {!! Html::script('plugins/daterangepicker/daterangepicker.js') !!}
    <script>
        $(function() {

            var oTable = $('#incomes-table').DataTable({
                dom: 'l<"toolbar">frtip',
                processing: true,
                serverSide: true,
                pageLength: {!! config('app.records_per_page')!!},
                ajax: {
                    url:'{!! route('admin.accounting.income.data') !!}',
                    data:function(d){
                        d.account = $('#filter_account').val();
                        d.income_type = $('#filter_incomeType').val();
                        d.date_range = $('#filter_date_range').val();
                    }
                },
                columns: [
                    { data: 'number', name: 'incomes.number'},
                    { data: 'amount_dollar', name: 'incomes.amount_dollar'},
                    { data: 'amount_riel', name: 'incomes.amount_riel'},
                    { data: 'account_name', name: 'accounts.name'},
                    { data: 'income_type_name', name: 'incomeTypes.name'},
                    { data: 'name', name: 'name',searchable:false},
                    { data: 'action', name: 'action',orderable: false, searchable: false}
                ]
            });

            $("div.toolbar").html(
                    '{!! Form::select('account',$accounts,null, array('class'=>'form-control','id'=>'filter_account','placeholder'=>'Account')) !!} '+
                    '{!! Form::select('incomeType',$incomeTypes,null, array('class'=>'form-control','id'=>'filter_incomeType','placeholder'=>'Income Tye')) !!} '+
                    '{!! Form::text('date_start_end',null,array('class'=>'form-control','id'=>'filter_date_range','placeholder'=>'Date range')) !!}'
            );

            $('#filter_date_range').daterangepicker({
                format: 'DD/MM/YYYY',
            });

            $('#filter_account').on('change', function(e) {
                oTable.draw();
                e.preventDefault();
            });
            $('#filter_incomeType').on('change', function(e) {
                oTable.draw();
                e.preventDefault();
            });
            $('#filter_date_range').on('change', function(e) {
                oTable.draw();
                e.preventDefault();
            });

            $('#export_income_list').click(function(e){
                e.preventDefault();
                window.location = '{{route("admin.accounting.income.export")}}'+
                        "?date_range="+$('#filter_date_range').val()+
                        '&account='+ $('#filter_account').val()+
                        '&income_type=' + $('#filter_incomeType').val();
            });
        });
    </script>
@stop

