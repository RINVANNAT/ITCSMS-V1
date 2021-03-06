@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.highSchools.title'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.highSchools.title') }}
        <small>{{ trans('labels.backend.highSchools.sub_index_title') }}</small>
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
                <a href="{!! route('admin.configuration.highSchool.request_import') !!}">
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
                <table class="table table-striped table-bordered table-hover dt-responsive nowrap" cellspacing="0" width="100%" id="highSchools-table">
                    <thead>
                    <tr>
                        <th>{{ trans('labels.backend.highSchools.fields.prefix_id') }}</th>
                        <th>{{ trans('labels.backend.highSchools.fields.name_kh') }}</th>
                        <th>{{ trans('labels.backend.highSchools.fields.name_en') }}</th>
                        <th>{{ trans('labels.backend.highSchools.fields.province_id') }}</th>
                        <th>{{ trans('labels.backend.highSchools.fields.is_no_school') }}</th>
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
            $('#highSchools-table').DataTable({
                processing: true,
                serverSide: true,
                pageLength: {!! config('app.records_per_page')!!},
                ajax: {
                    url: '{!! route('admin.configuration.highSchool.data') !!}',
                    method: 'POST'
                },
                columns: [
                    { data: 'prefix_id', name: 'prefix_id'},
                    { data: 'highSchools.name_kh', name: 'highSchools.name_kh'},
                    { data: 'highSchools.name_en', name: 'highSchools.name_en'},
                    { data: 'province_id', name: 'province_id',searchable:false},
                    { data: 'is_no_school', name: 'is_no_school'},
                    { data: 'action', name: 'action',orderable: false, searchable: false}
                ]
            });
            enableDeleteRecord($('#highSchools-table'));
        });
    </script>
@stop
