@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.exams.title') . ' | ' . trans('labels.backend.exams.sub_detail_title'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.exams.title') }}
        <small>{{ trans('labels.backend.exams.sub_detail_title') }}</small>
    </h1>
@endsection

@section('after-styles-end')
    {!! Html::style('plugins/jstree/themes/default/style.min.css') !!}
    {!! Html::style('plugins/datatables/dataTables.bootstrap.css') !!}
    <style>
        /*.Pending {
            background-color: #9FAFD1;
        }
        .Pass {
            background-color: white;
        }
        .Fail {
            background-color: #842210;
        }*/
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

        #side_window_right_staff_role.collapsed {
            margin-right: -25%; /* same width as sidebar */
        }

        #main_window_staff_role, .side-window {
            min-height: 650px;

        }

        #main_window_staff_role {
            -webkit-transition: width 0.3s ease;
            -moz-transition: width 0.3s ease;
            -o-transition: width 0.3s ease;
            transition: width 0.3s ease;
        }

        #main_window_staff_role .btn-group {
            margin-bottom: 10px;
        }

        .addRolePopUp {
            cursor:default;
            display:none;
            overflow:hidden;
            background-color: #f5f5f5;
            width:100%;
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
                    @if($exam->type_id == 3)
                    <li role="presentation" class="active">
                    @else
                    <li role="presentation">
                    @endif
                        <a href="#general_info" aria-controls="generals" role="tab" data-toggle="tab">
                            {{ trans('labels.backend.exams.show_tabs.general_info') }}
                        </a>
                    </li>
                    @if($exam->type_id != 3)
                        <li role="presentation" class="active">
                    @else
                        <li role="presentation">
                    @endif
                        <a href="#candidate_info" aria-controls="candidates" role="tab" data-toggle="tab">
                            {{ trans('labels.backend.exams.show_tabs.candidate_info') }}
                        </a>
                    </li>
                    @if($exam->type_id != 2)
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
                    @endif
                </ul>

                <div class="tab-content">
                    @if($exam->type_id == 3)
                        <div role="tabpanel" class="tab-pane active" id="general_info" style="padding-top:20px;">
                    @else
                        <div role="tabpanel" class="tab-pane" id="general_info" style="padding-top:20px;">
                    @endif

                        {!! Form::model($exam, ['#','class' => 'form-horizontal', 'role'=>'form', 'method' => 'patch', 'id'=> 'exam_show']) !!}
                        @include ("backend.exam.fields")
                        {!! Form::close() !!}
                    </div>
                    @if($exam->type_id != 3)
                        <div role="tabpanel" class="tab-pane active" id="candidate_info" style="padding-top:20px">
                    @else
                        <div role="tabpanel" class="tab-pane" id="candidate_info" style="padding-top:20px">
                    @endif
                            @include('backend.exam.show.exam_candidate')
                        </div>
                    @if($exam->type_id != 2)
                        <div role="tabpanel" class="tab-pane" id="course_info" style="padding-top:20px">
                            @include('backend.exam.show.exam_course')
                        </div>

                        <div role="tabpanel" class="tab-pane" id="room_info" style="padding-top:20px;max-width: 100%;">
                            @include('backend.exam.show.exam_room')
                        </div>
                        <div role="tabpanel" class="tab-pane" id="staff_info" style="padding-top:20px">
                            @include('backend.exam.show.exam_staff')
                        </div>
                    @endif
                </div>
            </div>

        </div><!-- /.box-body -->
    </div><!--box-->


@stop

@section('after-scripts-end')
    {!! Html::script('plugins/datatables/jquery.dataTables.min.js') !!}
    {!! Html::script('plugins/datatables/dataTables.bootstrap.min.js') !!}
    {!! Html::script('plugins/jstree/jstree.min.js') !!}
    {!! Html::script('js/exam_staff.js') !!}

    <script>

        var candidate_datatable = null;
        var course_datatable = null;
        var save_room_url = '{{route('admin.exam.save_rooms',$exam->id)}}';
        var delete_room_url = '{{route('admin.exam.delete_rooms',$exam->id)}}';
        var exam_id = {{$exam->id}};
        var window_secret_code;
        var window_course;
        var window_bac2;
        var window_candidate;
        
        function toggleSidebar() {
            var right = $("#side-window-right"),
                    content = $("#main-window"),
                    contentClass = "";
            // determine number of open sidebars
            if (content.hasClass("col-sm-6")) {
                contentClass = "col-sm-12";
                right.hide();
                $("#btn-add").show();
                $("#btn-delete").hide();
            } else {
                contentClass = "col-sm-6";
            }

            // apply class to content
            content.removeClass("col-sm-12 col-sm-9 col-sm-6")
                    .addClass(contentClass);

            if(content.hasClass("col-sm-6")){
                right.delay(300).show(0);
                $("#btn-add").hide();
                $("#btn-delete").show();
            }

        }

        function count_exam_seat(){
            var checked_available_rooms = $('#all_rooms').jstree("get_checked");
            var buildings = $('#all_rooms').data().jstree.get_json();

            var total_exam_seat = 0 ;

            $.each(checked_available_rooms, function (index, room_id){
                $.each(buildings, function(index_building, building){
                    $.each(building.children, function(index_room, room){
                        if(room.id == room_id){
                            total_exam_seat = total_exam_seat + room.data.chair_exam;
                        }
                    })
                });
            });

            $("#selected_available_seat").html(total_exam_seat);
        }

        function count_availble_seat(){
            var buildings = $('#all_rooms').data().jstree.get_json();

            var total_exam_seat = 0 ;

            console.log(buildings);

            $.each(buildings, function(index_building, building){
                $.each(building.children, function(index_room, room){
                    total_exam_seat = total_exam_seat + room.data.chair_exam;
                })
            });

            $("#all_available_seat").html(total_exam_seat);
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
                    "keep_selected_style" : false,
                    "tie_selection" :false
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

        function get_total_seat(ui_object,type){
            $.ajax({
                type: 'GET',
                url: "{{route('admin.exam.count_seat_exam',$exam->id)}}"+"?type="+type,
                dataType: "json",
                success: function(resultData) {
                    ui_object.html(resultData.seat_exam);
                }
            });
        }

        function refresh_candidate_list (){
            $('#candidates-table').DataTable().ajax.reload();
            notify("success","Info", "Candidate list is updated!");
        }

        function update_ui_room(){
            $('#all_rooms').jstree("refresh");
            $('#selected_rooms').jstree("refresh");
            get_total_seat($("#all_available_seat"),"available");
            get_total_seat($("#all_reserve_seat"),"selected");
        }

        function update_ui_course(){
            course_datatable.draw();
        }


        $(function(){
            $("#exam_show :input").attr("disabled", true);
            candidate_datatable = $('#candidates-table').DataTable({
                processing: true,
                serverSide: true,
                pageLength: {!! config('app.records_per_page')!!},
                ajax: {
                    url: '{!! route('admin.candidate.data')."?exam_id=".$exam->id !!}',
                    method: 'POST'
                },
                columns: [
                    { data: 'register_id', name: 'candidates.register_id'},
                    { data: 'name_kh', name: 'candidates.name_kh'},
                    { data: 'name_latin', name: 'candidates.name_en'},
                    { data: 'gender_name_kh', name: 'genders.name_kh'},
                    { data: 'dob', name: 'candidates.dob'},
                    { data: 'province', name: 'origins.name_kh'},
                    { data: 'bac_total_grade', name: 'bac_total_grade'},
                    { data: 'room', name: 'candidates.room', searchable:false},
                    { data: 'result', name: 'candidates.result'},
                    { data: 'action', name: 'action',orderable: false, searchable: false}
                ]
            });

            if(exam_id == 1){
                course_datatable = $('#table-exam-course').DataTable({
                    processing: true,
                    serverSide: true,
                    pageLength: {!! config('app.records_per_page')!!},
                    ajax: {
                        url: '{!! route('admin.entranceExamCourses.data',$exam->id) !!}',
                        method: 'POST'
                    },
                    columns: [
                        { data: 'name_kh', name: 'entranceExamCourses.name_kh'},
                        { data: 'total_question', name: 'entranceExamCourses.total_question'},
                        { data: 'description', name: 'entranceExamCourses.description'},
                        { data: 'action', name: 'action',orderable: false, searchable: false}
                    ]
                });
                enableDeleteRecord($('#table-exam-course'));
            } else {
                course_datatable = $('#table-exam-course').DataTable({
                    processing: true,
                    serverSide: true,
                    pageLength: {!! config('app.records_per_page')!!},
                    ajax: {
                        url: '{!! route('admin.exam.get_courses',$exam->id) !!}',
                        method: 'POST'
                    },
                    columns: [
                        { data: 'name_kh', name: 'courseAnnuals.name_kh'},
                        { data: 'semester', name: 'courseAnnuals.semester'},
                        { data: 'academic_year', name: 'academicYears.name_kh'},
                        { data: 'class', name: 'class', orderable:false, searchable:false},
                        { data: 'lecturer', name: 'employees.name_kh'},
                        { data: 'action', name: 'action',orderable: false, searchable: false}
                    ]
                });
            }


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
                window_bac2 = PopupCenterDual('{{route("admin.studentBac2.popup_index")."?exam_id=".$exam->id}}','Add new customer','1200','960');
            });

            $(document).on('click', '#btn_add_candidate_manual', function (e) {
                window_candidate = PopupCenterDual("{!! route('admin.candidate.popup_create').'?exam_id='.$exam->id.'&studentBac2_id=0' !!}",'Add new Candidate','1200','960');
            });

            initJsTree($('#all_rooms'),'available');

            initJsTree($('#selected_rooms'),'selected');

            var iconUrl1 = "{{url('plugins/jstree/img/department.png')}}";
            var iconUrl2 = "{{url('plugins/jstree/img/role.png')}}";
            var iconUrl3 = "{{url('plugins/jstree/img/employee.png')}}";

            initJsTree_StaffSelected($('#selected_staffs'), '{{route('admin.exam.get-all-roles',$exam->id)}}', '{{route('admin.exam.get-staff-by-role',$exam->id)}}', iconUrl2, iconUrl3);


            initJsTree_StaffRole($('#all_staff_role'), '{{route('admin.exam.get-all-departements',$exam->id)}}', '{{route('admin.exam.get-all-positions',$exam->id)}}','{{route('admin.exam.get-all-staffs-by-position',$exam->id)}}', iconUrl1, iconUrl2, iconUrl3 );
            $('#all_staff_role').jstree("load_all");


            $('#all_rooms').on("check_node.jstree", function (e, data) {
                count_exam_seat();
            });

            $('#all_rooms').on("uncheck_node.jstree", function (e, data) {
                count_exam_seat();
            });

            $("#btn-candidate-refresh").click(function(){
                refresh_candidate_list();
            });

            $("#btn-add").click(function () {
                toggleSidebar();
                return false;
            });
            $("#btn-cancel").click(function () {
                toggleSidebar();
                return false;
            });
            $("#btn-save").click(function(){

                $.ajax({
                    type: 'POST',
                    url: save_room_url,
                    data: {room_ids:JSON.stringify($('#all_rooms').jstree("get_checked"))},
                    dataType: "json",
                    success: function(resultData) {
                        update_ui_room(); // Data changed, so we need to refresh UI
                    }
                });
            });

            $("#btn-delete").click(function(){

                $.ajax({
                    type: 'POST',
                    url: delete_room_url,
                    data: {room_ids:JSON.stringify($('#selected_rooms').jstree("get_checked"))},
                    dataType: "json",
                    success: function(resultData) {
                        update_ui_room(); // Data changed, so we need to refresh UI
                    }
                });
            });

            $("#btn-candidate-generate-room").click(function(){
                $.ajax({
                    type: 'GET',
                    url: "{{route('admin.exam.candidate.generate_room',$exam->id)}}",
                    dataType: "json",
                    success: function(resultData) {
                        //update_ui_room(); // Data changed, so we need to refresh UI
                        if(resultData.status = true){
                            notify('success','Generate Room', resultData.message);
                            candidate_datatable.draw();
                        } else {
                            notify('error','Generate Room', resultData.message);
                        }
                    }
                });
            });

            $("#btn-secret-code").click(function(){
                window_secret_code = PopupCenterDual('{{route("admin.exam.view_room_secret_code",$exam->id)}}','Room Secret Code','1200','960');
            });

            $("#btn-add-course").click(function(){
                window_course = PopupCenterDual('{{route("admin.entranceExamCourses.create")}}'+'?exam_id='+'{{$exam->id}}','Course for exam','800','470');
            });

            get_total_seat($("#all_available_seat"),"available");
            get_total_seat($("#all_reserve_seat"),"selected");

            // Close any open window upon this main window is closed or refreshed

            window.onunload = function() {
                if (window_bac2 && !window_bac2.closed) {
                    window_bac2.close();
                }
                if (window_candidate && !window_candidate.closed) {
                    window_candidate.close();
                }
                if (window_course && !window_course.closed) {
                    window_course.close();
                }
                if (window_secret_code && !window_secret_code.closed) {
                    window_secret_code.close();
                }
            };

        });


    </script>


{{--vannat script--}}
    <script>

        var baseUrl = "{{route('admin.exam.gsave-staff-role',$exam->id)}}";
        var report_score_url = "{{route("admin.exam.report_exam_score_candidate",$exam->id)}}";
        var roleValue;
        var baseData;
        $("#btn_save_staff_role").click(function(){
            baseData= {
                role_id: $('#role :selected').val(),
                staff_ids: JSON.stringify($('#all_staff_role').jstree("get_selected"))
            }

            if(baseData.role_id != null && baseData.staff_ids != '[]') {
                console.log(baseData.staff_ids);
                console.log(baseData.role_id);
                ajaxRequest('POST', baseUrl, baseData);
            } else{

//                alert("Please Select Role and Check On Staff Before Adding New Record !!!!");
                $('#alert_save_staff_role').fadeIn().delay(2000).fadeOut();
            }

        });

        $('#submit_new_role').click(function() {
            var inputBaseUrl = "{{route('admin.exam.save-new-role', $exam->id)}}";
            var inputBaseData= {
                role_name: $('#new_role').val(),
                description: $('#new_des').val()
            }

            if(inputBaseData.role_name != '' && inputBaseData.description != '') {
                console.log(inputBaseData.role_name);
                console.log(inputBaseData.description);
                ajaxRequest('POST', inputBaseUrl,inputBaseData);
            } else{

                console.log("Please Complete Record Before Submitting !!!!");
                notify("error","info", "Please Complete Record Before Submitting !!!!");
            }
        })

        $("#btn_delete_node").click(function(){
            var deleteNodeUrl = "{{route('admin.exam.delete-role-node',$exam->id)}}"
            var baseData = {staff_ids:JSON.stringify($('#selected_staffs').jstree("get_checked"))};
            if(baseData.staff_ids !== '[]') {
                console.log(baseData);
                $('#check_ok').fadeIn();
                $('#ok_delete').on('click', function() {
                    $('#check_ok').fadeOut();
                    ajaxRequest('DELETE',deleteNodeUrl, baseData);
                });
                $('#cancel_delete').on('click', function() {
                    $('#check_ok').fadeOut();
                });

            } else {
                $('#alert_delete_role_staff').fadeIn().delay(2000).fadeOut();
            }
        });



        $("#btn_save_chang_role").click(function(){
            var changeNodeUrl = "{{route('admin.exam.update-role-node',$exam->id)}}"
            var baseData = {staff_ids:JSON.stringify($('#selected_staffs').jstree("get_checked")),
                            role_id:$('#role_change :selected').val()
                            };
            if(baseData.staff_ids !== '[]') {
                console.log(baseData);
                ajaxRequest('PUT',changeNodeUrl, baseData);
                clickAddRole($('.popUpRoleDown'));
                $('#btn_delete_node').show();
                $('#btn_move_node').show();
            } else {
//                alert('no selected value')
                $('#alert_add_role_staff').fadeIn().delay(2000).fadeOut();
            }
        });
/*
-----------------create inputscore in course page
*/
        $(document).on('click', '#btn_input_score_course', function (e) {
            window_request_room = PopupCenterDual('{{route("admin.exam.request_input_score_courses",$exam->id)}}','Course for exam','800','470');
        });


//        error popup page

        $(document).on('click', '.btn-report-error', function (e) {
            var course_id = $(this).data('remote');
            window_report_error = PopupCenterDual(report_score_url+"?course_id="+course_id,'Error Inputted Score Form ','1250','960');

        });


    </script>

@stop
