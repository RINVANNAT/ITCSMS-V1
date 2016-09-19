@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.studentBac2OldRecords.title'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.studentBac2OldRecords.title') }}
        <small>{{ trans('labels.backend.studentBac2OldRecords.sub_index_title') }}</small>
    </h1>

@endsection

@section('after-styles-end')
    {!! Html::style('plugins/datatables/dataTables.bootstrap.css') !!}
@stop

@section('content')
    <div class="box box-success">
        <div class="box-header with-border">
            <div class="mailbox-controls">
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
                        <th>{{ trans('labels.backend.studentBac2s.fields.bac_math_grade') }}</th>
                        <th>{{ trans('labels.backend.studentBac2s.fields.bac_phys_grade') }}</th>
                        <th>{{ trans('labels.backend.studentBac2s.fields.bac_chem_grade') }}</th>
                        <th>{{ trans('labels.backend.studentBac2s.fields.bac_year') }}</th>
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
                    url: '{!! route('admin.configuration.studentBac2OldRecords.data') !!}',
                    method: 'POST'
                },
                columns: [
                    { data: 'name_kh', name: 'student_bac2s_old_record.name_kh'},
                    { data: 'dob', name: 'student_bac2s_old_record.dob'},
                    { data: 'gender_name_kh', name: 'genders.name_kh'},
                    { data: 'highSchool_name_kh', name: 'highSchools.name_kh'},
                    { data: 'percentile', name: 'percentile',searchable:false},
                    { data: 'total_grade_name', name: 'total_grade.name_en',searchable:false},
                    { data: 'math_grade_name', name: 'math_grade.name_en',searchable:false},
                    { data: 'phys_grade_name', name: 'phys_grade.name_en',searchable:false},
                    { data: 'chem_grade_name', name: 'chem_grade.name_en',searchable:false},
                    { data: 'bac_year', name: 'student_bac2s_old_record.bac_year'}
                ]
            });
            enableDeleteRecord($('#studentBac2s-table'));
        });
    </script>
@stop
