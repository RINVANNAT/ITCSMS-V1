@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.candidates.title'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.candidates.title') }}
        <small>{{ trans('labels.backend.candidates.sub_index_title') }}</small>
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
                <a href="{!! route('admin.candidates.create') !!}">
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
                @include('backend.candidate.includes.index_table_header')
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
            $('#candidates-table').DataTable({
                processing: true,
                serverSide: true,
                pageLength: {!! config('app.records_per_page')!!},
                ajax: '{!! route('admin.candidate.data') !!}',
                columns: [
                    { data: 'name_kh', name: 'candidates.name_kh'},
                    { data: 'name_latin', name: 'candidates.name_latin'},
                    { data: 'gender_name_kh', name: 'genders.name_kh'},
                    { data: 'bac_total_grade', name: 'gdeGrade.name_en'},
                    { data: 'action', name: 'action',orderable: false, searchable: false}
                ]
            });
            enableDeleteRecord($('#candidates-table'));
        });
    </script>
@stop
