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
                    '{!! Form::select('grade',$grades,null, array('class'=>'form-control','id'=>'filter_grade','placeholder'=>'Grade')) !!} '+
                    '{!! Form::select('department',$departments,null, array('class'=>'form-control','id'=>'filter_department','placeholder'=>'Department')) !!} '

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
        });
    </script>
@stop
