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
    {!! Html::style('plugins/select2/select2.min.css') !!}
    <style>
        .toolbar, .session, #courseAnnuals-table_length {
            float: left;
        }

        .toolbar {
            margin-bottom: 5px;
        }

        #courseAnnuals-table_filter {
            float:right;
        }

        #filter_dept_option {
            margin-left: 5px;
        }

        .selected td:first-child {
            background-color: #83B8EC !important;
        }
        .selected td{
            border-left: 0;
            border-right: 0;
            border-top:thin solid #83B8EC !important;
            border-bottom:thin solid #83B8EC !important;
        }

        .selected td:last-child {
            border-right:thin solid #83B8EC !important;
        }

        .selected .select2-result-repository__forks, .selected .select2-result-repository__stargazers,
        .selected .select2-result-repository__watchers, .selected .select2-result-repository__description,
        .selected .select2-result-repository__title{
            color: white !important;
        }

        table h4{
            margin-top: 0px !important;
        }

        .image_mark {
            width: 20px;
        }

        td:first-child, td:last-child {
            vertical-align: middle !important;
            text-align: center !important;
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

    <div class="content_body">
        <div class="box box-success">
            <div class="box-header with-border">
                <div class="mailbox-controls">
                    <div class="col-md-7">
                        @permission('create-courseAnnuals')
                        <a href="{!! route('admin.course.course_annual.create') !!}">
                            <button class="btn btn-primary btn-sm"><i class="fa fa-plus-circle"></i> Add
                            </button>
                        </a>
                        <a href="{!! route('admin.course.course_annual.request_import') !!}">
                            <button class="btn btn-primary btn-sm"><i class="fa fa-plus-circle"></i> Import
                            </button>
                        </a>
                        @endauth

                        <div class="btn-group" style="float: right;">
                            @permission('course-annual-assignment')
                            <button class="btn btn-primary btn-sm" id="course_assignment"><i class="fa fa-map-signs"></i> {{trans('buttons.course.course_annual.course_assignment')}}</button>
                            @endauth
                            @permission('generate-course-annual')
                            <button class="btn btn-primary btn-sm" id="generate_course_annual"><i class="fa fa-puzzle-piece"></i> Generate Courses</button>
                            @endauth

                            @permission('view-all-score-course-annual')
                            <button class="btn btn-warning btn-sm" id="all_score_course_annual"><i class="fa fa-eye"></i> View Total Score </button>
                            @endauth
                        </div>
                    </div>
                    <div class="col-md-5">

                    </div>
                </div>
            </div><!-- /.box-header -->

            <div class="box-body">
                <div class="row">
                    <div class="col-md-7" style="min-height: 15px; padding: 0 30px 15px 30px;" id="filter_panel">

                    </div>
                    <div class="col-md-5">
                        <h3 style="margin: 0px;">Course Sessions</h3>
                    </div>
                </div>
                <div class="col-md-7" style="border-right: 3px solid #b8c7ce;">
                    @include("backend.course.courseAnnual.includes.index_table")
                </div>
                <div class="col-md-5">
                    <div class="course_session_message col-sm-12 box-body with-border text-muted well well-sm no-shadow" style="padding: 20px; min-height: 50px;">
                        <center><h4>Please select any course on the left.</h4></center>
                        <form class="form_add_session">
                            <div class="row">
                                <div class="col-md-2" style="padding-left:3px;padding-right: 3px;">
                                    <div class="form-group">
                                        <label for="session_time_course">Course</label>
                                        <input type="number" class="form-control" id="session_time_course">
                                    </div>
                                </div>
                                <div class="col-md-2" style="padding-left:3px;padding-right: 3px;">
                                    <div class="form-group">
                                        <label for="session_time_td">TD</label>
                                        <input type="number" class="form-control" id="session_time_td">
                                    </div>
                                </div>
                                <div class="col-md-2" style="padding-left:3px;padding-right: 3px;">
                                    <div class="form-group">
                                        <label for="session_time_tp">TP</label>
                                        <input type="number" class="form-control" id="session_time_tp">
                                    </div>
                                </div>
                                <div class="col-md-6" style="padding-left:3px;padding-right: 3px;">
                                    <div class="form-group">
                                        <label for="time_tp">Lecturer</label>
                                        {!! Form::select('employee',[],null,['id'=>'employee','class'=>"select_employee form-control",'style'=>'width:100%;']) !!}
                                        {{ Form::hidden('employee_id', null, ['class' => 'form-control', 'id'=>'session_lecturer']) }}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12" style="padding-left:3px;padding-right: 3px;">
                                    <div class="form-group">
                                        <label for="groups">Groups</label>

                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="course_session_wrapper" style="display: none;">
                        {{--@include("backend.course.courseAnnual.includes.index_course_session_table")--}}
                    </div>

                </div>
                <div class="clearfix"></div>
            </div><!-- /.box-body -->
        </div><!--box-->

    </div>
@stop

@section('after-scripts-end')
    {!! Html::script('plugins/datatables/jquery.dataTables.min.js') !!}
    {!! Html::script('plugins/datatables/dataTables.bootstrap.min.js') !!}
    {!! HTML::script('plugins/select2/select2.full.min.js') !!}
    <script>
        var $search_url = "{{route('admin.employee.search')}}";
        var base_url = '{{url('img/profiles/')}}';
        var current_course = null;
        $(function() {
            var toolbar_html =
                    '{!! Form::select('academic_year',$academicYears,null, array('class'=>'','id'=>'filter_academic_year')) !!}' +
                    @if($department_id != null)
                            ''+
                            @if(isset($deptOptions))
                                    '{!! Form::select('dept_option',$deptOptions,null, array('class'=>'','id'=>'filter_dept_option','placeholder'=>'Division')) !!} '+
                            @endif
                    @else
                            ' {!! Form::select('department',$departments,$department_id, array('class'=>'','id'=>'filter_department','placeholder'=>'Dept.')) !!} '+
                    @endif
                    '{!! Form::select('semester',$semesters,null, array('class'=>'','id'=>'filter_semester','placeholder'=>'Semester')) !!} '+
                    '{!! Form::select('degree',$degrees,null, array('class'=>'','id'=>'filter_degree','placeholder'=>'Degree')) !!} '+
                    '{!! Form::select('grade',$grades,null, array('class'=>'','id'=>'filter_grade','placeholder'=>'Year')) !!} '+
                    @if($lecturers != null)
                    '{!! Form::select('lecturer',$lecturers,null, array('class'=>'','id'=>'filter_lecturer','placeholder'=>'Lecturer')) !!} '
                    @else
                    ''
                    @endif



            var oTable = $('#courseAnnuals-table').DataTable({
                    processing: true,
                    serverSide: true,
                    scrollY:  "90vh",
                    scrollCollapse: true,
                    pageLength: {!! config('app.records_per_page')!!},
                    deferLoading : true,
                    ajax: {
                        url:"{!! route('admin.course.course_annual.data') !!}",
                        type:"POST",
                        data:function(d){
                            // In case additional fields is added for filter, modify export view as well: popup_export.blade.php
                            d.academic_year = $('#filter_academic_year').val();
                            d.degree = $('#filter_degree').val();
                            d.grade = $('#filter_grade').val();
                            d.department = $('#filter_department').val();
                            d.semester = $('#filter_semester').val();
                            d.lecturer = $('#filter_lecturer').val();
                            d.student_group = $('#filter_student_group').val();
                            d.dept_option = $('#filter_dept_option').val();
                        }
                    },

                    columns: [
                        { data: 'mark', name:'mark', searchable:false, orderable:false},
                        { data: 'name', name: 'course_annuals.name_en'},
                        { data: 'employee_id', name: 'employee_id',searchable:false},
                        { data: 'action', name: 'action',orderable: false, searchable: false}
                    ]
            });

            enableDeleteRecord($('#courseAnnuals-table'));

            $('#courseAnnuals-table tbody').on( 'click', 'td:first-child, td:nth-child(2),td:nth-child(3)', function (event) {
                if ($(this).closest('tr').hasClass('selected') ) {
                    $(this).closest('tr').removeClass('selected');
                    current_course = null;
                    $(".course_session_message").show();
                    $(".course_session_wrapper").hide();
                }
                else {
                    oTable.$('tr.selected').removeClass('selected');
                    $(this).closest('tr').addClass('selected');
                    current_course = $(this).closest('tr').find('.course_id').html();

                    $(".course_session_message").hide();
                    $(".course_session_wrapper").show();
                    load_session(current_course);
                }
            });

            var employee_search_box = $(".select_employee").select2({
                placeholder: 'Enter name ...',
                allowClear: true,
                tags: true,
                createTag: function (params) {
                    return {
                        id: params.term,
                        name: params.term,
                        group: 'customer',
                        newOption: true
                    }
                },
                ajax: {
                    url: $search_url,
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            term: params.term || '', // search term
                            page: params.page || 1
                        };
                    },
                    cache: true
                },
                escapeMarkup: function (markup) {
                    return markup;
                }, // let our custom formatter work
                minimumInputLength: 3,
                templateResult: formatRepoEmployee, // omitted for brevity, see the source of this page
                templateSelection: formatRepoSelectionEmployee, // omitted for brevity, see the source of this page
            });

            $("#filter_panel").html(
                    toolbar_html
            );

//            $('#filter_academic_year, #filter_degree, #filter_grade, #filter_department').on('change', function(e) {
//                oTable.draw();
//                e.preventDefault();
//            });
            $('#filter_academic_year').on('change', function(e) {

                oTable.draw();
                appendFilterGroupSeclection();
                e.preventDefault();
            });

            $('#filter_degree').on('change', function(e) {
                oTable.draw();
                appendFilterGroupSeclection();
                e.preventDefault();
            });
            $('#filter_grade').on('change', function(e) {
                oTable.draw();
                appendFilterGroupSeclection();
                e.preventDefault();
            });
            $('#filter_department').on('change', function(e) {
                oTable.draw();
                appendFilterGroupSeclection();
                hasDeptOption();
                e.preventDefault();
            });
            @if($lecturers != null)
            $('#filter_lecturer').on('change', function(e) {
                oTable.draw();
                e.preventDefault();
            });
            @endif

            $('#filter_semester').on('change', function(e) {
                oTable.draw();
                e.preventDefault();
            });

            $(document).on('change', '#filter_student_group', function() {
                oTable.draw();
                e.preventDefault();
            })

            $(document).on('change', '#filter_dept_option', function() {
                oTable.draw();
                e.preventDefault();
            });

            oTable.draw();

        });

        function load_session(current_course){
            $.ajax({
                type: 'POST',
                url: "{!! route('admin.course.course_session.data') !!}",
                data: {
                    course_id:current_course
                },
                dataType: "html",
                success: function(resultData) {
                    $(".course_session_wrapper").html(resultData);
                }
            });
        }

        function hasDeptOption() {
            var dept_option_url = '{{route('course_annual.dept_option')}}';
            var department_id = $('#filter_department :selected').val();

            $.ajax({
                type: 'GET',
                url: dept_option_url,
                data: {department_id: department_id},
                dataType: "html",
                success: function(resultData) {

//                    console.log(resultData);
                    if($('#filter_dept_option').is(':visible')) {
                        $('#filter_dept_option').html(resultData);
                    } else {
                        $("div.toolbar > select#filter_department").after(resultData);
                    }

                }
            });

        }



        function appendFilterGroupSeclection() {

            var academic_year_id,degree_id,grade_id, department_id ;

            academic_year_id = $('#filter_academic_year :selected').val();
            degree_id = $('#filter_degree :selected').val();
            grade_id  = $('#filter_grade :selected').val();

            @if($department_id != null)
                department_id = '{{$department_id}}';
            @else
                department_id = $('#filter_department :selected').val();
            @endif
            var baseData = {
                academic_year_id: academic_year_id,
                degree_id: degree_id,
                grade_id:grade_id,
                department_id:department_id
            };

            $.ajax({
                type: 'GET',
                url: '{{route('course_annual.get_student_group_selection')}}',
                data: baseData,
                dataType: "html",
                success: function(resultData) {

//                    console.log(resultData);
                    if($('#filter_student_group').is(':visible')) {
                        $('#filter_student_group').html(resultData);
                    } else {
                        $('div.toolbar').append(resultData);

                        $('#filter_student_group').addClass('form-control')
                    }

                }
            });

        }

        $('#course_assignment').on('click', function() {
            var academic_year_id = $('#filter_academic_year :selected').val();
            var degree_id = $('#filter_degree :selected').val();
            var grade_id  = $('#filter_grade :selected').val();
            var department_option_id = $('#filter_dept_option :selected').val();
            var semester_id = $('#filter_semester :selected').val();


            @if($department_id !=null)
                var department_id = '{{$department_id}}';
            @else
                var department_id = $('#filter_department :selected').val();
            @endif
            var department_name = $('#filter_department :selected').text();
            var grade_name = $('#filter_grade :selected').text();
            var degree_name = $('#filter_degree :selected').text();
            var url = "{!! route('admin.course.course_assignment') !!}";
            var course_assignment_window = PopupCenterDual(url+'?department_id='+department_id+'&academic_year_id='+academic_year_id+'&degree_id='+degree_id+'&grade_id='+grade_id+'&department_option_id='+department_option_id + '&semester_id='+semester_id,'course assignment','1400','900');
        });

        $('#generate_course_annual').on('click', function() {

            var url = "{!! route('admin.course.generate_course_annual') !!}";
            var baseData = {
                academic_year_id: $('#filter_academic_year :selected').val(),
                degree_id : $('#filter_degree :selected').val(),
                grade_id: $('#filter_grade :selected').val(),
                department_id:$('#filter_department :selected').val()
            };

            swal({
                title: "Confirm",
                text: "Do you really want to generate courses??",
                type: "info",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes",
                closeOnConfirm: true
            }, function(confirmed) {
                if (confirmed) {
                    $.ajax({
                        type: 'GET',
                        url: url,
                        data: baseData,
                        dataType: "json",
                        success: function(resultData) {
                            if(resultData.status == true) {
                                notify('success', 'info', resultData.message);
                                oTable.draw();

                            } else {
                                notify('error', 'info', resultData.message);
                            }
                        }
                    });
                }
            });
        });

        $(document).on('click', '#all_score_course_annual', function(e) {

            var  baseUrl = '{{route('admin.course.get_form_evaluation_score')}}';
            @if($department_id != null)
                var baseData = {
                            academic_year_id: $('#filter_academic_year :selected').val(),
                            degree_id : $('#filter_degree :selected').val(),
                            grade_id: $('#filter_grade :selected').val(),
                            department_id:'{{$department_id}}',
                            semester_id:$('#filter_semester :selected').val(),
                            dept_option_id: $('#filter_dept_option :selected').val(),
                            group_name: $('#filter_student_group :selected').val()
                        };
            @else
                var baseData = {
                            academic_year_id: $('#filter_academic_year :selected').val(),
                            degree_id : $('#filter_degree :selected').val(),
                            grade_id: $('#filter_grade :selected').val(),
                            department_id:$('#filter_department :selected').val(),
                            semester_id:$('#filter_semester :selected').val(),
                            dept_option_id: $('#filter_dept_option :selected').val(),
                            group_name: $('#filter_student_group :selected').val()
                        };
            @endif
            if(baseData.academic_year_id != null) {
                if(baseData.degree_id) {
                    if(baseData.grade_id) {
                        if(baseData.department_id) {

                            window.location.replace(baseUrl+'?academic_year_id='+baseData.academic_year_id+
                                            '&degree_id='+baseData.degree_id+
                                            '&grade_id='+baseData.grade_id+
                                            '&department_id='+baseData.department_id+
                                            '&semester_id='+baseData.semester_id+
                                            '&dept_option_id='+baseData.dept_option_id+
                                            '&group_name='+baseData.group_name
                            );

                        } else {
                            notify('error', 'Department Not Selected!!', 'info');
                        }

                    } else {
                        notify('error', 'Grade Not Selected!!', 'info');
                    }

                } else {
                    notify('error', 'Degree Not Selected!!', 'info');
                }
            }



        })


        $('#courseAnnuals-table').on('click', '.input_score_course', function(e) {
            e.preventDefault();
//            alert($(this).attr('href'));
            var url = $(this).attr('href');
            var baseData = {student_group: $(document).find('#filter_student_group').val()};
//            console.log(baseData.student_group);
            window.location.replace(url+'?student_group='+baseData.student_group);

        })


    </script>
@stop
