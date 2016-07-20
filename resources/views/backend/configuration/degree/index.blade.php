@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.degrees.title'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.degrees.title') }}
        <small>{{ trans('labels.backend.degrees.sub_index_title') }}</small>
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
                <a href="{!! route('admin.configuration.degrees.create') !!}">
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
                <table class="table table-striped table-bordered table-hover dt-responsive nowrap" cellspacing="0" width="100%" id="degrees-table">
                    <thead>
                    <tr>
                        <th>{{ trans('labels.backend.degrees.fields.code') }}</th>
                        <th>{{ trans('labels.backend.degrees.fields.name_kh') }}</th>
                        <th>{{ trans('labels.backend.degrees.fields.name_en') }}</th>
                        <th>{{ trans('labels.backend.degrees.fields.name_fr') }}</th>
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
            $('#degrees-table').DataTable({
                processing: true,
                serverSide: true,
                pageLength: {!! config('app.records_per_page')!!},
                ajax: {
                    url: '{!! route('admin.configuration.degree.data') !!}',
                    method: 'POST'
                },
                columns: [
                    { data: 'code', name: 'code'},
                    { data: 'name_kh', name: 'name_kh'},
                    { data: 'name_en', name: 'name_en'},
                    { data: 'name_fr', name: 'name_fr'},
                    { data: 'action', name: 'action',orderable: false, searchable: false}
                ]
            });

            enableDeleteRecord($('#degrees-table'));
        });
    </script>
@stop
