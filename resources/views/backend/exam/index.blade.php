@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.exams.title'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.exams.title') }}
        <small>{{ trans('labels.backend.exams.sub_index_title') }}</small>
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
                <a href="{!! route('admin.exam.create',$type) !!}">
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
                <table class="table table-striped table-bordered table-hover" id="exams-table">
                    <thead>
                    <tr>
                        <th>{{ trans('labels.backend.exams.fields.name') }}</th>
                        <th>{{ trans('labels.backend.exams.fields.date_start') }}</th>
                        <th>{{ trans('labels.backend.exams.fields.date_end') }}</th>
                        <th>{{ trans('labels.backend.exams.fields.description') }}</th>
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
            $('#exams-table').DataTable({
                processing: true,
                serverSide: true,
                pageLength: {!! config('app.records_per_page')!!},
                ajax: '{!! route('admin.exam.data',$type) !!}',
                columns: [
                    { data: 'name', name: 'name'},
                    { data: 'date_start', name: 'date_start'},
                    { data: 'date_end', name: 'date_end'},
                    { data: 'description', name: 'description'},
                    { data: 'action', name: 'action',orderable: false, searchable: false}
                ]
            });
            enableDeleteRecord($('#exams-table'));
        });
    </script>
@stop
