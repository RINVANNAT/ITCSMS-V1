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
    <style>
        .toolbar {
            float: left;
        }
    </style>
@stop

@section('content')

    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('labels.backend.candidates.sub_create_title') }}</h3>
            <button id="btn-manual" class="btn btn-sm btn-info pull-right">Add Manually</button>
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
                        <th>{{ trans('labels.backend.studentBac2s.fields.origin') }}</th>
                        <th>{{ trans('labels.backend.studentBac2s.fields.bac_year') }}</th>
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
        var candidate_window;
        $(function() {
            var oTable = $('#studentBac2s-table').DataTable({
                dom: 'l<"toolbar">frtip',
                processing: true,
                serverSide: true,
                pageLength: {!! config('app.records_per_page')!!},
                ajax: {
                    url: '{!! route('admin.configuration.studentBac2.data')."?exam_id=".$exam_id !!}',
                    method: 'POST',
                    data:function(d){
                        // In case additional fields is added for filter, modify export view as well: popup_export.blade.php
                        d.academic_year = $('#filter_academic_year').val();
                        d.origin = $('#filter_origin').val();
                    }
                },
                columns: [
                    { data: 'name_kh', name: 'studentBac2s.name_kh'},
                    { data: 'dob', name: 'studentBac2s.dob',searchable:false},
                    { data: 'gender_name_kh', name: 'genders.name_kh',searchable:false},
                    { data: 'highSchool_name_kh', name: 'highSchools.name_kh'},
                    { data: 'percentile', name: 'percentile',searchable:false},
                    { data: 'gdeGrade_name_en', name: 'gdeGrades.name_en',searchable:false},
                    { data: 'origin', name: 'origins.name_kh',searchable:false},
                    { data: 'bac_year', name: 'studentBac2s.bac_year',searchable:false},
                    { data: 'export', name: 'export',orderable: false, searchable: false}
                ]
            });
            $("div.toolbar").html(
                    '{!! Form::select('academic_year',$academicYears,null, array('class'=>'form-control','id'=>'filter_academic_year')) !!} '+
                    '{!! Form::select('origin',$origins,null, array('class'=>'form-control','id'=>'filter_origin','placeholder'=>'Origin')) !!} '
            );

            $('#filter_academic_year').on('change', function(e) {
                oTable.draw();
                e.preventDefault();
            });

            $('#filter_origin').on('change', function(e) {
                oTable.draw();
                e.preventDefault();
            });

            enableDeleteRecord($('#studentBac2s-table'));

            $(document).on('click', ".export", function(e){
                e.preventDefault();

                candidate_window = PopupCenterDual($(this).attr('href'),'Add new Candidate','1200','960');

            });

            $("#btn-manual").on("click",function(){
                candidate_window = PopupCenterDual("{!! route('admin.candidate.popup_create').'?exam_id='.$exam_id.'&studentBac2_id=0' !!}",'Add new Candidate','1200','960');
            });

            window.onunload = function() {
                if (candidate_window && !candidate_window.closed) {
                    candidate_window.close();
                }
            };
        });
    </script>
@stop
