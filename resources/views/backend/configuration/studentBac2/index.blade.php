@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.studentBac2s.title'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.studentBac2s.title') }}
        <small>{{ trans('labels.backend.studentBac2s.sub_index_title') }}</small>
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
                <a href="{!! route('admin.configuration.studentBac2.request_import') !!}">
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
                <table class="table table-striped table-bordered table-hover dt-responsive nowrap" cellspacing="0" width="100%" id="studentBac2s-table">
                    <thead>
                    <tr>
                        <th>{{ trans('labels.backend.studentBac2s.fields.name_kh') }}</th>
                        <th>{{ trans('labels.backend.studentBac2s.fields.dob') }}</th>
                        <th>{{ trans('labels.backend.studentBac2s.fields.gender_id') }}</th>
                        <th>{{ trans('labels.backend.studentBac2s.fields.highschool_id') }}</th>
                        <th>{{ trans('labels.backend.studentBac2s.fields.percentile') }}</th>
                        <th>{{ trans('labels.backend.studentBac2s.fields.grade') }}</th>
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
            $('#studentBac2s-table').DataTable({
                processing: true,
                serverSide: true,
                pageLength: {!! config('app.records_per_page')!!},
                ajax: {
                    url: '{!! route('admin.configuration.studentBac2.data') !!}',
                    method: 'POST'
                },
                columns: [
                    { data: 'action', name: 'action',orderable: false, searchable: false},
                    { data: 'name_kh', name: 'studentBac2s.name_kh'},
                    { data: 'dob', name: 'studentBac2s.dob'},
                    { data: 'gender_name_kh', name: 'genders.name_kh'},
                    { data: 'highSchool_name_kh', name: 'highSchools.name_kh'},
                    { data: 'percentile', name: 'percentile',searchable:false},
                    { data: 'gdeGrade_name_en', name: 'gdeGrades.name_en'},
                ]
            });
            enableDeleteRecord($('#studentBac2s-table'));
        });
    </script>
@stop
