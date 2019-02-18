@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.coursePrograms.title'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.coursePrograms.title') }}
        <small>{{ trans('labels.backend.coursePrograms.sub_index_title') }}</small>
    </h1>

@endsection

@section('after-styles-end')
    {!! Html::style('plugins/datatables/dataTables.bootstrap.css') !!}
    <style>
        #filter_dept_option {
            margin-left: 5px;
        }
        .toolbar, .toolbar_deactivate {
            float: left;
        }
        table td:nth-child(2), table td:nth-child(3) {
            display: none;
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
                @permission("create-coursePrograms")
                <a href="{!! route('admin.course.course_program.create') !!}">
                    <button class="btn btn-primary btn-sm"><i class="fa fa-plus-circle"></i> Add </button>
                </a>
                @endauth

                <a href="{!! route('course_program.export_list') !!}" id="export_file" class="pull-right">
                    <button class="btn btn-info btn-sm" ><i class="fa fa-download"></i> Export </button>
                </a>

                {{--<button class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></button>--}}

            </div>


        </div><!-- /.box-header -->

        <div class="box-body">
            <div>
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#activate" aria-controls="active" role="tab" data-toggle="tab">Activate Course Program</a></li>
                    <li role="presentation"><a href="#deactivate" aria-controls="deactivate" role="tab" data-toggle="tab">Deactivate Course Program</a></li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="activate">
                        <div style="margin-top: 10px">
                            <table class="table table-striped table-bordered table-hover dt-responsive nowrap" cellspacing="0" width="100%" id="coursePrograms-table">
                                <thead>
                                <tr>
                                    <th>{{ trans('labels.backend.coursePrograms.fields.name_kh') }}</th>
                                    <th style="display: none"></th>
                                    <th style="display: none"></th>
                                    <th>{{ trans('labels.backend.coursePrograms.fields.code') }}</th>
                                    <th>Class</th>
                                    <th>Permitted to</th>
                                    <th>{{ trans('labels.backend.coursePrograms.fields.semester') }}</th>
                                    <th width="20px;">{{ trans('labels.backend.coursePrograms.fields.time_course') }}</th>
                                    <th width="20px;">{{ trans('labels.backend.coursePrograms.fields.time_td') }}</th>
                                    <th width="20px;">{{ trans('labels.backend.coursePrograms.fields.time_tp') }}</th>
                                    <th>{{ trans('labels.backend.coursePrograms.fields.credit') }}</th>
                                    <th  width="100px;">{{ trans('labels.general.actions') }}</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="deactivate">
                        <div style="margin-top: 10px">
                            <table class="table table-striped table-bordered table-hover dt-responsive nowrap coursePrograms-table" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>{{ trans('labels.backend.coursePrograms.fields.name_kh') }}</th>
                                    <th style="display: none"></th>
                                    <th style="display: none"></th>
                                    <th>{{ trans('labels.backend.coursePrograms.fields.code') }}</th>
                                    <th>Class</th>
                                    <th>Permitted to</th>
                                    <th>{{ trans('labels.backend.coursePrograms.fields.semester') }}</th>
                                    <th width="20px;">{{ trans('labels.backend.coursePrograms.fields.time_course') }}</th>
                                    <th width="20px;">{{ trans('labels.backend.coursePrograms.fields.time_td') }}</th>
                                    <th width="20px;">{{ trans('labels.backend.coursePrograms.fields.time_tp') }}</th>
                                    <th>{{ trans('labels.backend.coursePrograms.fields.credit') }}</th>
                                    <th  width="100px;">{{ trans('labels.general.actions') }}</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>

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
            var toolbar_html =
                    @if($department_id != null)
                            ''+
                        @if(isset($deptOptions))
                                '{!! Form::select('dept_option',$deptOptions,null, array('class'=>'form-control','id'=>'filter_dept_option','placeholder'=>'Division')) !!} '+
                        @endif
                    @else
                            ' {!! Form::select('department',$departments,$department_id, array('class'=>'form-control','id'=>'filter_department','placeholder'=>'Department')) !!} '+
                    @endif
                        '{!! Form::select('semester',$semesters,null, array('class'=>'form-control','id'=>'filter_semester','placeholder'=>'Semester')) !!} '+
                        '{!! Form::select('degree',$degrees,null, array('class'=>'form-control','id'=>'filter_degree','placeholder'=>'Degree')) !!} '+
                        '{!! Form::select('grade',$grades,null, array('class'=>'form-control','id'=>'filter_grade','placeholder'=>'Year')) !!} '+
                        '{!! Form::select('responsible_department',$responsible_departments,null, array('class'=>'form-control','id'=>'filter_responsible_department','placeholder'=>'Permitted department')) !!} '

            var toolbar_html_deactivate =
                    @if($department_id != null)
                        ''+
                    @if(isset($deptOptions))
                        '{!! Form::select('dept_option',$deptOptions,null, array('class'=>'form-control','id'=>'filter_dept_option_deactivate','placeholder'=>'Division')) !!} '+
                    @endif
                            @else
                        ' {!! Form::select('department',$departments,$department_id, array('class'=>'form-control','id'=>'filter_department_deactivate','placeholder'=>'Department')) !!} '+
                    @endif
                        '{!! Form::select('semester',$semesters,null, array('class'=>'form-control','id'=>'filter_semester_deactivate','placeholder'=>'Semester')) !!} '+
                '{!! Form::select('degree',$degrees,null, array('class'=>'form-control','id'=>'filter_degree_deactivate','placeholder'=>'Degree')) !!} '+
                '{!! Form::select('grade',$grades,null, array('class'=>'form-control','id'=>'filter_grade_deactivate','placeholder'=>'Year')) !!} '+
                '{!! Form::select('responsible_department',$responsible_departments,null, array('class'=>'form-control','id'=>'filter_responsible_department_deactivate','placeholder'=>'Permitted department')) !!} '


            var oTable = $('#coursePrograms-table').DataTable({
                processing: true,
                serverSide: true,
                dom: 'l<"toolbar">frtip',
                //deferLoading: true,
                pageLength: {!! config('app.records_per_page')!!},

                ajax: {
                    url:'{!! route('admin.course.course_program.data') !!}',
                    method:'POST',
                    data:function(d){
                        // In case additional fields is added for filter, modify export view as well: popup_export.blade.php
                        d.degree = $('#filter_degree').val();
                        d.grade = $('#filter_grade').val();
                        d.department = $('#filter_department').val();
                        d.department_option = $('#filter_dept_option').val();
                        d.semester = $('#filter_semester').val();
                        d.responsible_department = $('#filter_responsible_department').val();

                    }
                },
                columns: [
                    { data: 'name_kh', name: 'name_kh'},
                    { data: 'name_en', name: 'name_en'},
                    { data: 'name_fr', name: 'name_fr'},
                    { data: 'code', name: 'code'},
                    { data: 'class', name: 'class', searchable:false},
                    { data: 'responsible_department', name: 'responsible_department', searchable:false},
                    { data: 'semester', name: 'semesters.id'},
                    { data: 'time_course', name: 'time_course'},
                    { data: 'time_td', name: 'time_td'},
                    { data: 'time_tp', name: 'time_tp'},
                    { data: 'credit', name: 'credit'},
                    { data: 'action', name: 'action',orderable: false, searchable: false}
                ]
            });

            var oTableDeactive = $('.coursePrograms-table').DataTable({
                processing: true,
                serverSide: true,
                dom: 'l<"toolbar_deactivate">frtip',
                //deferLoading: true,
                pageLength: {!! config('app.records_per_page')!!},

                ajax: {
                    url:'{!! route('admin.course.course_program.data') !!}',
                    method:'POST',
                    data:function(d){
                        // In case additional fields is added for filter, modify export view as well: popup_export.blade.php
                        d.degree = $('#filter_degree_deactivate').val();
                        d.grade = $('#filter_grade_deactivate').val();
                        d.department = $('#filter_department_deactivate').val();
                        d.department_option = $('#filter_dept_option_deactivate').val();
                        d.semester = $('#filter_semester_deactivate').val();
                        d.responsible_department = $('#filter_responsible_department_deactivate').val();
                        d.deactive = true

                    }
                },
                columns: [
                    { data: 'name_kh', name: 'name_kh'},
                    { data: 'name_en', name: 'name_en'},
                    { data: 'name_fr', name: 'name_fr'},
                    { data: 'code', name: 'code'},
                    { data: 'class', name: 'class', searchable:false},
                    { data: 'responsible_department', name: 'responsible_department', searchable:false},
                    { data: 'semester', name: 'semesters.id'},
                    { data: 'time_course', name: 'time_course'},
                    { data: 'time_td', name: 'time_td'},
                    { data: 'time_tp', name: 'time_tp'},
                    { data: 'credit', name: 'credit'},
                    { data: 'action', name: 'action',orderable: false, searchable: false}
                ]
            });

            enableDeleteRecord($('#coursePrograms-table'));
            enableDeleteRecord($('.coursePrograms-table'));

            $("div.toolbar").html(toolbar_html);
            $("div.toolbar_deactivate").html(toolbar_html_deactivate);



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
                hasDeptOption();
                e.preventDefault();
            });
            $('#filter_responsible_department').on('change', function(e) {
                oTable.draw();
                e.preventDefault();
            });

            $(document).ready(function() {
                if($('#filter_department :selected').val()) {
                    hasDeptOption();
                }
            })

            $(document).on('change', '#filter_dept_option', function() {
                oTable.draw();
                e.preventDefault();
            })

            $('#filter_semester').on('change', function(e) {
                oTable.draw();
                e.preventDefault();
            });


            $('#filter_degree_deactivate').on('change', function(e) {
                oTableDeactive.draw();
                e.preventDefault();
            });
            $('#filter_grade_deactivate').on('change', function(e) {
                oTableDeactive.draw();
                e.preventDefault();
            });
            $('#filter_department_deactivate').on('change', function(e) {
                oTableDeactive.draw();
                hasDeptOption();
                e.preventDefault();
            });
            $('#filter_responsible_department_deactivate').on('change', function(e) {
                oTableDeactive.draw();
                e.preventDefault();
            });

            $(document).ready(function() {
                if($('#filter_department_deactivate :selected').val()) {
                    hasDeptOption();
                }
            })

            $(document).on('change', '#filter_dept_option_deactivate', function() {
                oTableDeactive.draw();
                e.preventDefault();
            })

            $('#filter_semester_deactivate').on('change', function(e) {
                oTableDeactive.draw();
                e.preventDefault();
            });
        });



        function hasDeptOption() {
            var dept_option_url = '{{route('course_program.dept_option')}}';
            var department_id = $('#filter_department :selected').val();

            $.ajax({
                type: 'GET',
                url: dept_option_url,
                data: {department_id: department_id},
                dataType: "html",
                success: function(resultData) {
                    if($('#filter_dept_option').is(':visible')) {
                        $('#filter_dept_option').html(resultData);
                    } else {
                        $("div.toolbar > select#filter_department").after(resultData);
                    }

                }
            });

        }


        $('#export_file').on('click', function (e) {
            e.preventDefault();
            var url= $(this).attr('href');
            var department_id = $('#filter_department :selected').val();
            var degree_id = $('#filter_degree :selected').val();
            var grade_id = $('#filter_grade :selected').val();
            var semester_id = $('#filter_semester :selected').val();
            var department_option_id = $('#filter_dept_option :selected').val();

            if(department_id != null && department_id != '') {

                if(degree_id != null && degree_id != '') {

                    if(grade_id != null && grade_id != '') {

                        window.open(
                                url+'?department_id=' + department_id +
                                        '&degree_id='+ degree_id +
                                        '&grade_id=' + grade_id+
                                        '&semester_id=' + semester_id+
                                        '&department_option_id=' + department_option_id
                                , '_blank'
                        )

                    } else {
                        notify('error', 'Attention! Please Select Grade!')
                    }
                } else {
                    notify('error', 'Attention! Please Select Degree!')
                }

            } else {
	            if(degree_id != null && degree_id != '') {

		            if(grade_id != null && grade_id != '') {

			            window.open(
				            url+'?degree_id='+ degree_id +
				            '&grade_id=' + grade_id+
				            '&semester_id=' + semester_id+
				            '&department_option_id=' + department_option_id
				            , '_blank'
			            )

		            } else {
			            notify('error', 'Attention! Please Select Grade!')
		            }
	            } else {
		            notify('error', 'Attention! Please Select Degree!')
	            }
            }
        });

    </script>
@stop

