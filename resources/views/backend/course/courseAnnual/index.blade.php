@extends ('backend.layouts.master')
@section ('title', trans('labels.backend.courseAnnuals.title'))
@section('page-header')
    <h1>
        {{ trans('labels.backend.courseAnnuals.title') }}
        <small>{{ trans('labels.backend.courseAnnuals.sub_index_title') }}</small>
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
    @if (Session::has('flash_notification.message'))
        <div class="alert alert-{{ Session::get('flash_notification.level') }}">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>

            {{ Session::get('flash_notification.message') }}
        </div>
    @endif

    <div class="box box-success">
        <div class="box-header with-border">
            <div class="mailbox-controls">
                <!-- Check all button -->
                <a href="{!! route('admin.course.course_annual.create') !!}">
                    <button class="btn btn-primary btn-sm"><i class="fa fa-plus-circle"></i> Add
                    </button>
                </a>
                <a href="{!! route('admin.course.course_annual.request_import') !!}">
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

                @permission('course-annual-assignment')
                    <button class="btn btn-primary btn-sm pull-right " id="course_assignment"><i class="fa fa-plus-circle"></i> {{trans('buttons.course.course_annual.course_assignment')}}</button>
                @endauth


            </div>
        </div><!-- /.box-header -->

        <div class="box-body">
            @include("backend.course.courseAnnual.includes.index_table")
            <div class="clearfix"></div>
        </div><!-- /.box-body -->
    </div><!--box-->
@stop

@section('after-scripts-end')
    {!! Html::script('plugins/datatables/jquery.dataTables.min.js') !!}
    {!! Html::script('plugins/datatables/dataTables.bootstrap.min.js') !!}
    <script>
        $(function() {
            var oTable = $('#courseAnnuals-table').DataTable({
                dom: 'l<"toolbar">frtip',
                processing: true,
                serverSide: true,
                pageLength: {!! config('app.records_per_page')!!},

                ajax: {
                    url:"{!! route('admin.course.course_annual.data') !!}",
                    type:"POST",
                    data:function(d){
                        // In case additional fields is added for filter, modify export view as well: popup_export.blade.php
                        d.academic_year = $('#filter_academic_year').val();
                        d.degree = $('#filter_degree').val();
                        d.grade = $('#filter_grade').val();
                        d.department = $('#filter_department').val();

                        d.lecturer = $('#filter_lecturer').val();
                    }
                },

                columns: [
                    { data: 'name', name: 'name'},
                    { data: 'semester_id', name: 'semester_id'},
                    { data: 'academic_year_id', name: 'academic_year_id'},
                    { data: 'department_id', name: 'department_id'},
                    { data: 'degree_id', name: 'degree_id'},
                    { data: 'grade_id', name: 'grade_id'},
                    { data: 'employee_id', name: 'employee_id'},
                    { data: 'action', name: 'action',orderable: false, searchable: false}
                ]
            });

            enableDeleteRecord($('#courseAnnuals-table'));

            $("div.toolbar").html(
                    '{!! Form::select('academic_year',$academicYears,null, array('class'=>'form-control','id'=>'filter_academic_year')) !!} ' +
                    '{!! Form::select('degree',$degrees,null, array('class'=>'form-control','id'=>'filter_degree','placeholder'=>'Degree')) !!} '+
                    '{!! Form::select('grade',$grades,null, array('class'=>'form-control','id'=>'filter_grade','placeholder'=>'Year')) !!} '+
                    '{!! Form::select('department',$departments,null, array('class'=>'form-control','id'=>'filter_department','placeholder'=>'Department')) !!} '+
                    '{!! Form::select('lecturer',$employees,null, array('class'=>'form-control','id'=>'filter_lecturer','placeholder'=>'Lecturer')) !!} '
            );
//            $('#filter_academic_year, #filter_degree, #filter_grade, #filter_department').on('change', function(e) {
//                oTable.draw();
//                e.preventDefault();
//            });
            $('#filter_academic_year').on('change', function(e) {
                oTable.draw();
                e.preventDefault();
            });

            $('#filter_degree').on('change', function(e) {
                oTable.draw();
                e.preventDefault();
            });
            $('#filter_grade').on('change', function(e) {
                oTable.draw();
                e.preventDefault();
            });
            $('#filter_department').on('change', function(e) {
                oTable.draw();
                e.preventDefault();
            });
            $('#filter_lecturer').on('change', function(e) {
                oTable.draw();
                e.preventDefault();
            });
        });


        $('#course_assignment').on('click', function() {
            var academic_year_id = $('#filter_academic_year :selected').val();
            var degree_id = $('#filter_degree :selected').val();
            var grade_id  = $('#filter_grade :selected').val();
            var department_id = $('#filter_department :selected').val();
            var department_name = $('#filter_department :selected').text();
            var grade_name = $('#filter_grade :selected').text();
            var degree_name = $('#filter_degree :selected').text();
            var url = "{!! route('admin.course.course_assignment') !!}";
            var course_assignment_window = PopupCenterDual(url+'?department_id='+department_id+'&academic_year_id='+academic_year_id+'&degree_id='+degree_id+'&grade_id='+grade_id,'course assignment','1400','900');
        })
    </script>
@stop
