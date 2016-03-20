@extends ('backend.layouts.popup_master')

@section ('title', trans('labels.backend.candidates.title') . ' | ' . trans('labels.backend.candidates.sub_create_title'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.candidates.title') }}
        <small>{{ trans('labels.backend.candidates.sub_create_title') }}</small>
    </h1>
@endsection

@section('after-styles-end')
    {!! Html::style('plugins/datatables/dataTables.bootstrap.css') !!}
@stop

@section('content')

    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('labels.backend.candidates.sub_create_title') }}</h3>
        </div><!-- /.box-header -->

        <div class="box-body">
            <div>
                <table class="table table-striped table-bordered table-hover" id="studentBac2s-table">
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
                ajax: '{!! route('admin.configuration.studentBac2.data')."?exam_id=".$exam_id !!}',
                columns: [
                    { data: 'name_kh', name: 'studentBac2s.name_kh'},
                    { data: 'dob', name: 'studentBac2s.dob',searchable:false},
                    { data: 'gender_name_kh', name: 'genders.name_kh',searchable:false},
                    { data: 'highSchool_name_kh', name: 'highSchools.name_kh'},
                    { data: 'percentile', name: 'percentile',searchable:false},
                    { data: 'gdeGrade_name_en', name: 'gdeGrades.name_en',searchable:false},
                    { data: 'export', name: 'export',orderable: false, searchable: false}
                ]
            });
            enableDeleteRecord($('#studentBac2s-table'));
        });
    </script>
@stop
