@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.rooms.title'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.rooms.title') }}
        <small>{{ trans('labels.backend.rooms.sub_index_title') }}</small>
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
                <a href="{!! route('admin.configuration.rooms.create') !!}">
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
                <table class="table table-striped table-bordered table-hover dt-responsive nowrap" cellspacing="0" width="100%" id="rooms-table">
                    <thead>
                    <tr>
                        <th>{{ trans('labels.backend.rooms.fields.name') }}</th>
                        <th>{{ trans('labels.backend.rooms.fields.nb_desk') }}</th>
                        <th>{{ trans('labels.backend.rooms.fields.nb_chair') }}</th>
                        <th>{{ trans('labels.backend.rooms.fields.nb_chair_exam') }}</th>
                        <th>{{ trans('labels.backend.rooms.fields.size') }}</th>
                        <th>{{ trans('labels.backend.rooms.fields.room_type_id') }}</th>
                        <th>{{ trans('labels.backend.rooms.fields.building_id') }}</th>
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
            $('#rooms-table').DataTable({
                processing: true,
                serverSide: true,
                pageLength: {!! config('app.records_per_page')!!},
                ajax: '{!! route('admin.configuration.room.data') !!}',
                columns: [
                    { data: 'rooms.name', name: 'rooms.name'},
                    { data: 'nb_desk', name: 'nb_desk'},
                    { data: 'nb_chair', name: 'nb_chair'},
                    { data: 'nb_chair_exam', name: 'nb_chair_exam'},
                    { data: 'size', name: 'size'},
                    { data: 'roomTypes.id', name: 'roomTypes.id'},
                    { data: 'buildings.id', name: 'buildings.id'},

                    { data: 'action', name: 'action',orderable: false, searchable: false}
                ]
            });
            enableDeleteRecord($('#rooms-table'));
        });
    </script>
@stop
