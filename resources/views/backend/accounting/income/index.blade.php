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

                <a href="{!! route('admin.accounting.income.request_import') !!}" class="import">
                    <button class="btn btn-primary btn-sm"><i class="fa fa-plus-circle"></i> Import
                    </button>
                </a>
                <!-- /.btn-group -->
                <button class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></button>

            </div>
            <div class="box-tools pull-right">
                @include('backend.accounting.income.includes.partials.header-buttons')
            </div>


        </div><!-- /.box-header -->

        <div class="box-body">
            <div>
                <table class="table table-striped table-bordered table-hover dt-responsive nowrap" cellspacing="0" width="100%" id="incomes-table">
                    <thead>
                    <tr>
                        <th>{{ trans('labels.backend.accounting.fields.number') }}</th>
                        <th>{{ trans('labels.backend.accounting.fields.amount_dollar') }}</th>
                        <th>{{ trans('labels.backend.accounting.fields.amount_riel') }}</th>
                        <th>{{ trans('labels.backend.accounting.fields.account_id') }}</th>
                        <th>{{ trans('labels.backend.accounting.fields.incomeType_id') }}</th>
                        <th>{{ trans('labels.backend.accounting.fields.payslip_client_id') }}</th>
                        <th>{{ trans('labels.backend.accounting.fields.description') }}</th>
                        <th>{{ trans('labels.general.actions') }}</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th colspan="6" style="text-align: right; border:none">
                            Total Sum ($) :<br/>
                            Total Sum (៛) :
                        </th>
                        <th align="left" style="border: none">
                            <span id="total_dollar">0</span> <br/>
                            <span id="total_riel">0</span>
                        </th>
                    </tr>
                    </tfoot>
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
                drawCallback: function (settings) {
                    //$('#total_dollar').html(json.total_dollar);
                    //$('#total_riel').html(json.total_riel);
                    this.api().columns(1).every(function () {
                        var column = this;
                        var sum1 = column.data();
                        if(sum1.length > 0){
                            sum1  = sum1.reduce(function (a, b) {
                                return parseInt(a, 10) + parseInt(b, 10);
                            });
                        } else {
                            sum1 = 0;
                        }

                        $('#total_dollar').html(sum1);
                    });

                    this.api().columns(2).every(function () {
                        var column = this;
                        var sum2 = column.data();
                        if(sum2.length > 0){
                            sum2  = sum2.reduce(function (a, b) {
                                return parseInt(a, 10) + parseInt(b, 10);
                            });
                        } else {
                            sum2 = 0;
                        }

                        $('#total_riel').html(sum2);
                    });
                },
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
                    { data: 'name', name: 'name'},
                    { data: 'description', name: 'incomes.description'},
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

            $(document).on('click', '.import', function (e) {
                e.preventDefault();
                var url = '{{route("admin.accounting.income.request_import")}}'

                PopupCenterDual(url,'Export student data','1200','960');
            });
        });
    </script>
@stop

