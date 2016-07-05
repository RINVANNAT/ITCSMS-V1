@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.exams.title') . ' | ' . trans('labels.backend.exams.sub_show_title'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.exams.title') }}
        <small>{{ trans('labels.backend.exams.sub_show_title') }}</small>
    </h1>
@endsection

@section('after-styles-end')
    {!! Html::style('plugins/jstree/themes/default/style.min.css') !!}
    {!! Html::style('plugins/datatables/dataTables.bootstrap.css') !!}
    <style>
        .Pending {
            background-color: #9FAFD1;
        }
        .Pass {
            background-color: white;
        }
        .Fail {
            background-color: #842210;
        }
        #main-window, .side-window {
            min-height: 650px;
        }
        #row-main {
            overflow-x: hidden; /* necessary to hide collapsed sidebars */
        }
        #main-window {
            -webkit-transition: width 0.3s ease;
            -moz-transition: width 0.3s ease;
            -o-transition: width 0.3s ease;
            transition: width 0.3s ease;
        }
        #main-window .btn-group {
            margin-bottom: 10px;
        }

        .side-window {
            -webkit-transition: margin 0.3s ease;
            -moz-transition: margin 0.3s ease;
            -o-transition: margin 0.3s ease;
            transition: margin 0.3s ease;
        }
        .collapsed {
            display: none; /* hide it for small displays */
        }
        #side-window-right.collapsed {
            margin-right: -25%; /* same width as sidebar */
        }
    </style>
@stop


@section('content')

    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('labels.backend.exams.sub_edit_title') }}</h3>
        </div><!-- /.box-header -->

        <div class="box-body">

            <div>
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active">
                        <a href="#general_info" aria-controls="generals" role="tab" data-toggle="tab">
                            {{ trans('labels.backend.exams.show_tabs.general_info') }}
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#candidate_info" aria-controls="candidates" role="tab" data-toggle="tab">
                            {{ trans('labels.backend.exams.show_tabs.candidate_info') }}
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#course_info" aria-controls="courses" role="tab" data-toggle="tab">
                            {{ trans('labels.backend.exams.show_tabs.course_info') }}
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#room_info" aria-controls="rooms" role="tab" data-toggle="tab">
                            {{ trans('labels.backend.exams.show_tabs.room_info') }}
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#staff_info" aria-controls="staffs" role="tab" data-toggle="tab">
                            {{ trans('labels.backend.exams.show_tabs.staff_info') }}
                        </a>
                    </li>
                </ul>

                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="general_info" style="padding-top:20px;">
                        {!! Form::model($exam, ['#','class' => 'form-horizontal', 'role'=>'form', 'method' => 'patch', 'id'=> 'exam_show']) !!}
                        @include ("backend.exam.fields")
                        {!! Form::close() !!}
                    </div>
                    <div role="tabpanel" class="tab-pane" id="course_info" style="padding-top:20px">
                        @include('backend.exam.show.exam_course')
                    </div>
                    <div role="tabpanel" class="tab-pane" id="candidate_info" style="padding-top:20px">
                        @include('backend.exam.show.exam_candidate')
                    </div>
                    <div role="tabpanel" class="tab-pane" id="room_info" style="padding-top:20px;max-width: 100%;">
                        @include('backend.exam.show.exam_room')
                    </div>
                    <div role="tabpanel" class="tab-pane" id="staff_info" style="padding-top:20px">
                        @include('backend.exam.show.exam_staff')
                    </div>
                </div>
            </div>

        </div><!-- /.box-body -->
    </div><!--box-->


@stop

@section('after-scripts-end')
    {!! Html::script('plugins/datatables/jquery.dataTables.min.js') !!}
    {!! Html::script('plugins/datatables/dataTables.bootstrap.min.js') !!}
    {!! Html::script('plugins/jstree/jstree.min.js') !!}

    <script>

        var candidate_datatable = null;
        var course_datatable = null;
        function toggleSidebar() {
            var right = $("#side-window-right"),
                    content = $("#main-window"),
                    contentClass = "";
            // determine number of open sidebars
            if (content.hasClass("col-sm-6")) {
                contentClass = "col-sm-12";
                right.hide();
            } else {
                contentClass = "col-sm-6";
            }

            // apply class to content
            content.removeClass("col-sm-12 col-sm-9 col-sm-6")
                    .addClass(contentClass);

            if(content.hasClass("col-sm-6")){
                right.delay(300).show(0);
            }

        }

        function initJsTree(object,type){
            object.jstree({
                "core" : {
                    "animation":0,
                    "check_callback" : true,
                    'force_text' : true,
                    "themes" : {
                        "variant" : "large",
                        "stripes" : true
                    },
                    "data":{
                        'url' : function (node) {
                            return node.id === '#' ? '{{route('admin.exam.get_buildings',$exam->id)}}'+'?type='+type : '{{route('admin.exam.get_rooms',$exam->id)}}'+'?type='+type;
                        },
                        'data' : function (node) {
                            return { 'id' : node.id };
                        }
                    }
                },
                "checkbox" : {
                    "keep_selected_style" : false
                },
                "types" : {
                    "#" : { "max_depth" : 3, "valid_children" : ["building","room"] },
                    "building" : {
                        "icon" : "{{url('plugins/jstree/img/building.png')}}",
                        "valid_children" : ["room"]
                    },
                    "room" :{
                        "icon" : "{{url('plugins/jstree/img/door.png')}}",
                        "valid_children" : []
                    }
                },
                "plugins" : [
                    "wholerow",'checkbox', "contextmenu", "dnd", "search", "state","types"
                ]
            });
        }

        $(function(){
            $("#exam_show :input").attr("disabled", true);

            candidate_datatable = $('#candidates-table').DataTable({
                processing: true,
                serverSide: true,
                pageLength: {!! config('app.records_per_page')!!},
                ajax: '{!! route('admin.candidate.data')."?exam_id=".$exam->id !!}',
                columns: [
                    { data: 'name_kh', name: 'candidates.name_kh'},
                    { data: 'name_latin', name: 'candidates.name_en'},
                    { data: 'gender_name_kh', name: 'genders.name_kh'},
                    { data: 'dob', name: 'candidates.dob'},
                    { data: 'province', name: 'origins.name_kh'},
                    { data: 'bac_total_grade', name: 'bac_total_grade'},
                    { data: 'result', name: 'candidates.result'},
                    { data: 'action', name: 'action',orderable: false, searchable: false}
                ]
            });

            course_datatable = $('#table-exam-course').DataTable({
                processing: true,
                serverSide: true,
                pageLength: {!! config('app.records_per_page')!!},
                ajax: '{!! route('admin.exam.get_courses',$exam->id) !!}',
                columns: [
                    { data: 'name_kh', name: 'courseAnnuals.name_kh'},
                    { data: 'semester', name: 'courseAnnuals.semester'},
                    { data: 'academic_year', name: 'academicYears.name_kh'},
                    { data: 'class', name: 'class', orderable:false, searchable:false},
                    { data: 'lecturer', name: 'employees.name_kh'},
                    { data: 'action', name: 'action',orderable: false, searchable: false}
                ]
            });

            enableDeleteRecord($('#candidates-table'));

            $('#candidates-table').on('click', '.btn-register[data-remote]', function (e) {
                var url = $(this).data('remote');
                e.preventDefault();
                swal({
                    title: "Confirm",
                    text: "Register this candidate?",
                    type: "info",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes, register it!",
                    closeOnConfirm: true
                }, function(confirmed) {
                    if (confirmed) {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });

                        // confirm then
                        $.ajax({
                            url: url,
                            type: 'GET',
                            dataType: 'json',
                            success:function(data) {
                                candidate_datatable.draw();
                            }
                        });
                    }
                });
                return false;

            });

            $(document).on('click', '#btn_add_candidate', function (e) {
                PopupCenterDual('{{route("admin.studentBac2.popup_index")."?exam_id=".$exam->id}}','Add new customer','1200','960');
            });

            initJsTree($('#all_rooms'),'available');

            initJsTree($('#selected_rooms'),'selected');

            $("#btn-add").click(function () {
                toggleSidebar();
                //$('#side-window-right').toggleClass("collapsed");

                return false;
            });

        });

        function refresh_candidate_list (){
            $('#candidates-table').DataTable().ajax.reload();
        }

    </script>
@stop
