@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.outcomes.title'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.outcomes.title') }}
        <small>{{ trans('labels.backend.outcomes.sub_index_title') }}</small>
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
                <a href="{!! route('admin.accounting.outcomes.create') !!}">
                    <button class="btn btn-primary btn-sm"><i class="fa fa-plus-circle"></i> Add
                    </button>
                </a>
                <button class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></button>
            </div>
            <div class="box-tools pull-right">
                @include('backend.accounting.outcome.includes.partials.header-buttons')
            </div>

        </div><!-- /.box-header -->

        <div class="box-body">
            <div>
                <table class="table table-striped table-bordered table-hover dt-responsive nowrap" cellspacing="0" width="100%" id="outcomes-table">
                    <thead>
                    <tr>
                        <th>{{ trans('labels.backend.accounting.fields.number') }}</th>
                        <th>{{ trans('labels.backend.accounting.fields.amount_dollar') }}</th>
                        <th>{{ trans('labels.backend.accounting.fields.amount_riel') }}</th>
                        <th>{{ trans('labels.backend.accounting.fields.account_id') }}</th>
                        <th>{{ trans('labels.backend.accounting.fields.client') }}</th>
                        <th>{{ trans('labels.general.actions') }}</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th colspan="5" style="text-align: right; border:none">
                            Total Sum ($) :<br/>
                            Total Sum (៛) :
                        </th>
                        <th align="left" style="border: none">
                            0 <br/>
                            0
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
            var oTable = $('#outcomes-table').DataTable({
                dom: 'l<"toolbar">frtip',
                processing: true,
                serverSide: true,
                pageLength: {!! config('app.records_per_page')!!},
                ajax: {
                    url:'{!! route('admin.accounting.outcome.data') !!}',
                    data:function(d){
                        d.account = $('#filter_account').val();
                        d.outcome_type = $('#filter_outcomeType').val();
                        d.date_range = $('#filter_date_range').val();
                    }
                },
                columns: [
                    { data: 'number', name: 'outcomes.number'},
                    { data: 'amount_dollar', name: 'outcomes.amount_dollar'},
                    { data: 'amount_riel', name: 'outcomes.amount_riel'},
                    { data: 'account_name', name: 'accounts.name'},
                    { data: 'name', name: 'name'},
                    { data: 'action', name: 'action',orderable: false, searchable: false}
                ]
            });

            $("div.toolbar").html(
                    '{!! Form::text('date_start_end',null,array('class'=>'form-control','id'=>'filter_date_range','placeholder'=>'Date range')) !!} ' +
                    '{!! Form::select('account',$accounts,null, array('class'=>'form-control','id'=>'filter_account','placeholder'=>'Account')) !!} '+
                    '{!! Form::select('outcomeType',$outcomeTypes,null, array('class'=>'form-control','id'=>'filter_outcomeType','placeholder'=>'Outcome Type')) !!} '
            );

            $('#filter_date_range').daterangepicker({
                format: 'DD/MM/YYYY',
            });

            $('#filter_date_range').daterangepicker({
                format: 'DD/MM/YYYY',
            });

            $('#filter_account').on('change', function(e) {
                oTable.draw();
                e.preventDefault();
            });
            $('#filter_outcomeType').on('change', function(e) {
                oTable.draw();
                e.preventDefault();
            });
            $('#filter_date_range').on('change', function(e) {
                oTable.draw();
                e.preventDefault();
            });

            $('#export_outcome_list').click(function(e){
                e.preventDefault();
                window.location = '{{route("admin.accounting.outcome.export")}}'+
                        "?date_range="+$('#filter_date_range').val()+
                        '&account='+ $('#filter_account').val()+
                        '&outcome_type=' + $('#filter_outcomeType').val();
            });
        });
    </script>
@stop
