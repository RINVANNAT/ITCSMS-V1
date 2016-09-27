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

        div.img {
            margin: 5px;
            border: 1px solid #ccc;
            float: left;
            width: 180px;
        }

        div.img:hover {
            border: 1px solid #777;
        }

        div.img img {
            width: 100%;
            height: auto;
        }

        div.desc {
            padding: 15px;
            text-align: center;
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
                    @permission('view-exam-candidate')
                    <li role="presentation">
                        <a href="#candidate_info" aria-controls="candidates" role="tab" data-toggle="tab">
                            {{ trans('labels.backend.exams.show_tabs.candidate_info') }}
                        </a>
                    </li>
                    @endauth
                @if($exam->type_id != 2)  {{-- If this is DUT Examination, there is no course, room and staff --}}
                    @permission('view-entrance-exam-course')
                    <li role="presentation">
                        <a href="#course_info" aria-controls="courses" role="tab" data-toggle="tab">
                            {{ trans('labels.backend.exams.show_tabs.course_info') }}
                        </a>
                    </li>
                    @endauth
                    @permission('view-exam-room')
                    <li role="presentation">
                        <a href="#room_info" aria-controls="rooms" role="tab" data-toggle="tab">
                            {{ trans('labels.backend.exams.show_tabs.room_info') }}
                        </a>
                    </li>
                    @endauth
                    @permission('view-exam-staff')
                    <li role="presentation">
                        <a href="#staff_info" aria-controls="staffs" role="tab" data-toggle="tab">
                            {{ trans('labels.backend.exams.show_tabs.staff_info') }}
                        </a>

                    @endauth
                @endif
                    @permission('view-exam-document')
                    <li role="presentation">
                        <a href="#download_info" aria-controls="staffs" role="tab" data-toggle="tab">
                            {{ trans('labels.backend.exams.show_tabs.download') }}
                        </a>
                    </li>
                    @endauth
                </ul>

                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="general_info" style="padding-top:20px;">
                        {!! Form::model($exam, ['#','class' => 'form-horizontal', 'role'=>'form', 'method' => 'patch', 'id'=> 'exam_show']) !!}
                        @include ("backend.exam.fields")
                        {!! Form::close() !!}
                    </div>
                    @permission('view-exam-candidate')
                    <div role="tabpanel" class="tab-pane" id="candidate_info" style="padding-top:20px">
                        @include('backend.exam.show.exam_candidate')
                    </div>
                    @endauth
                    @if($exam->type_id != 2)
                        @permission('view-entrance-exam-course')
                        <div role="tabpanel" class="tab-pane" id="course_info" style="padding-top:20px">
                            @include('backend.exam.show.exam_course')
                        </div>
                        @endauth
                        @permission('view-exam-room')
                        <div role="tabpanel" class="tab-pane" id="room_info" style="padding-top:20px;max-width: 100%;">
                            @include('backend.exam.show.exam_room')
                        </div>
                        @endauth
                        @permission('view-exam-staff')
                        <div role="tabpanel" class="tab-pane" id="staff_info" style="padding-top:20px">
                            @include('backend.exam.show.exam_staff')
                        </div>
                        @endauth
                    @endif
                    @permission('view-exam-document')
                    <div role="tabpanel" class="tab-pane" id="download_info" style="padding-top:20px">
                        @include('backend.exam.show.download')
                    </div>
                    @endauth
                </div>
            </div>

        </div><!-- /.box-body -->
    </div><!--box-->


@stop

@section('after-scripts-end')
    {!! Html::script('plugins/datatables/jquery.dataTables.min.js') !!}
    {!! Html::script('plugins/datatables/dataTables.bootstrap.min.js') !!}
    {!! Html::script('plugins/jstree/jstree.min.js') !!}
    @permission('view-exam-staff')
    {!! Html::script('js/exam_staff.js') !!}
    @endauth

    <script>

        var candidate_datatable = null;
        var course_datatable = null;
        var save_room_url = '{{route('admin.exam.save_rooms',$exam->id)}}';
        var generate_room_url = '{{route('admin.exam.generate_rooms',$exam->id)}}';
        var merge_room_url = '{{route('admin.exam.merge_rooms',$exam->id)}}';
        var add_room_url = '{{route('admin.exam.add_room',$exam->id)}}';
        var split_room_url = '{{route('admin.exam.split_room',$exam->id)}}';
        var delete_room_url = '{{route('admin.exam.delete_rooms',$exam->id)}}';
        var edit_seat_url = '{{route('admin.exam.edit_seats',$exam->id)}}';
        var refresh_room_url = '{{route('admin.exam.refresh_room',$exam->id)}}';
        var sort_room_capacity_url = '{{route('admin.exam.sort_room_capacity',$exam->id)}}';

        var check_missing_candidates_url = '{{route('admin.exam.check_missing_candidates',$exam->id)}}';
        var find_missing_candidates_url = '{{route('admin.exam.find_missing_candidates',$exam->id)}}';
        var exam_id = {{$exam->id}};
        var exam_type_id = {{$exam->type_id}};

        var window_secret_code;
        var window_course;
        var window_bac2;
        var window_candidate;
        var window_missing_candidate;

        var check_course_error = false;
        var iconUrl1 = "{{url('plugins/jstree/img/department.png')}}";
        var iconUrl2 = "{{url('plugins/jstree/img/role.png')}}";
        var iconUrl3 = "{{url('plugins/jstree/img/employee.png')}}";
        var baseUrl = "{{route('admin.exam.gsave-staff-role',$exam->id)}}";
        var report_score_url = "{{route("admin.exam.report_exam_score_candidate",$exam->id)}}";
        var roleValue;
        var baseData;

        /*---------- Functions for candidates ---------*/

        function check_missing_candidates(){
            $.ajax({
                type: 'GET',
                url: check_missing_candidates_url,
                dataType: "json",
                success: function(resultData) {
                    if(resultData.status == true){
                        $("#candidate_notification").show();
                    } else {
                        $("#candidate_notification").hide();
                    }
                }
            });
        }

        function refresh_candidate_list (){
            $('#candidates-table').DataTable().ajax.reload();
            notify("success","Info", "Candidate list is updated!");
            check_missing_candidates();
        }

        /*----------------------------------------------------------------- Functions for course -------------------------------------------------------------------*/

        function update_ui_course(){
            course_datatable.draw();
        }

        /* -------- Function for room -------- */
        function get_total_seat(){
            $.ajax({
                type: 'GET',
                url: "{{route('admin.exam.count_seat_exam',$exam->id)}}",
                dataType: "json",
                success: function(resultData) {
                    $('#all_reserve_seat').html(resultData.seat_exam);
                }
            });
        }

        function count_assigned_seat(){
            $.ajax({
                type: 'GET',
                url: "{{route('admin.exam.count_assigned_seat',$exam->id)}}",
                dataType: "json",
                success: function(resultData) {
                    var result = "";
                    $.each(resultData, function(key,value){
                        result = result+"<span class='badge'><em>"+key+" :</em> "+value+" rooms</span> ";
                    });
                    $('#count_assigned_seat').html(result);
                }
            });
        }

        function disable_room_editing(){
            $('#exam_room_list_table tbody').removeClass('editing');
            $('#exam_room_list_table input:checkbox').prop("disabled", true);
            $('#exam_room_list_table tr').removeClass('highlight');
            $('#exam_room_list_table input:checkbox').prop('checked',false);
            $('#btn_room_modify').show();
            $('.room_editing').hide();
        }

        function enable_room_editing(){
            if(($('[name="exam_room[]"]:checked').length > 0)){
                $('#btn_room_merge').prop('disabled',false);
                $('#btn_room_delete').prop('disabled',false);
                $('#btn_seat_edit').prop('disabled',false);
            }else{
                $('#btn_room_merge').prop('disabled',true);
                $('#btn_room_delete').prop('disabled',true);
                $('#btn_seat_edit').prop('disabled',true);
            }

            $('#exam_room_list_table tbody').addClass('editing');
            $('#exam_room_list_table input:checkbox').prop("disabled", false);
            $('#btn_room_modify').hide();
            $('.room_editing').show();
        }


        $(function(){
            $("#exam_show :input").attr("disabled", true);




            // Close any open window upon this main window is closed or refreshed

            /* ------------------------------------------------  Staff Exam Section -------------------------------------------*/
            @permission('view-exam-staff')

            initJsTree_StaffSelected($('#selected_staffs'), '{{route('admin.exam.get-all-roles',$exam->id)}}', '{{route('admin.exam.get-staff-by-role',$exam->id)}}', iconUrl2, iconUrl3);

            initJsTree_StaffRole($('#all_staff_role'), '{{route('admin.exam.get-all-departements',$exam->id)}}', '{{route('admin.exam.get-all-positions',$exam->id)}}','{{route('admin.exam.get-all-staffs-by-position',$exam->id)}}', iconUrl1, iconUrl2, iconUrl3 );

            $("#btn_save_staff_role").click(function(){
                baseData= {
                    role_id: $('#role :selected').val(),
                    staff_ids: JSON.stringify($('#all_staff_role').jstree("get_selected"))
                }

                if(baseData.role_id != null && baseData.staff_ids != '[]') {
                    ajaxRequest('POST', baseUrl, baseData);
                } else{
                    $('#alert_save_staff_role').fadeIn().delay(2000).fadeOut();
                }

            });

            $('#submit_new_role').click(function() {

                var inputBaseUrl = "{{route('admin.exam.save-new-role', $exam->id)}}";
                var inputBaseData= {
                    role_name: $('#new_role').val(),
                    description: $('#new_des').val()
                }

                if(inputBaseData.role_name != '') {
                    ajaxRequest('POST', inputBaseUrl,inputBaseData);
                } else{

                    console.log("Please Complete Record Before Submitting !!!!");
                    notify("error","info", "Please Complete Record Before Submitting !!!!");
                }
            })



            $("#btn_save_chang_role").click(function(){

                var changeNodeUrl = "{{route('admin.exam.update-role-node',$exam->id)}}"
                var baseData = {staff_ids:JSON.stringify($('#selected_staffs').jstree("get_checked")),
                    role_id:$('#role_change :selected').val()
                };
                if(baseData.staff_ids !== '[]') {
                    console.log(baseData);
                    ajaxRequest('PUT',changeNodeUrl, baseData);

                    enable_modify_staff();

                } else {
//                alert('no selected value')
                    $('#alert_add_role_staff').fadeIn().delay(2000).fadeOut();
                }
            });

            /*
             -----------------create inputscore in course page
             */

            $("#btn_delete_node").click(function(){
                var deleteNodeUrl = "{{route('admin.exam.delete-role-node',$exam->id)}}"
                var baseData = {staff_ids:JSON.stringify($('#selected_staffs').jstree("get_checked"))};

                if(baseData.staff_ids !== '[]') {

                    swal({
                        title: "Confirm",
                        text: "Delete these staff?",
                        type: "info",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Yes, delete it!",
                        closeOnConfirm: true
                    }, function(confirmed) {
                        if (confirmed) {
                            ajaxRequest('DELETE',deleteNodeUrl, baseData);
                        }
                    });

                } else {
                    $('#alert_delete_role_staff').fadeIn().delay(2000).fadeOut();
                }
            });

            $(document).on('click', '#import_temp_employee', function (e) {
                window_request_room = PopupCenterDual('{!! route('admin.exam.temp_employee.request_import', $exam->id) !!}','import temporary employee','800','470');
            });


            $('#export_temp_employee').on('click', function() {
                var baseUrl = '{!! route('admin.exam.temp_employee.export', $exam->id) !!}';
                window.location.href = baseUrl;
            })

            $('#assign_room_staff').on('click', function() {

                var baseUrl  = '{!! route('admin.exam.assign_room_staff_lists', $exam->id) !!}';
                var window_view_role_staff = PopupCenterDual(baseUrl, 'View Role For Each staff', '1000', '1200');
            });
            @endauth







            /* ------------------------------------------------  Room Exam Section -------------------------------------------*/
            @permission('view-exam-room')

            var room_exam_add_title = "{{ trans('labels.backend.exams.exam_room.title.add') }}";
            var room_exam_edit_title = "{{ trans('labels.backend.exams.exam_room.title.edit') }}";

            /* ---------- Event Area --------- */
            get_total_seat(); // Count total seat after page ready
            count_assigned_seat();

            $("#btn-secret-code").click(function(){
                window_secret_code = PopupCenterDual('{{route("admin.exam.view_room_secret_code",$exam->id)}}','Room Secret Code','1200','960');
            });

            $("#generate_room_exam").click(function () {
                $('#empty_room_notification').hide();
                $('#form_generate_room_wrapper').show();
            });

            // Show the estimation by multiplying number of room with number of seat
            $("#exam_chair").on("keyup",function(){
               $("#exam_seat_estimation").html($("#exam_chair").val()*$("#available_room").val());
            });

            $("#submit_exam_room").on("click",function(){
                $.ajax({
                    type: 'POST',
                    url: generate_room_url,
                    data: $("#form_generate_exam_room").serialize(),
                    dataType: "html",
                    success: function(resultData) {
                        $('#form_generate_room_wrapper').hide();
                        $('#selected_rooms').html(resultData);
                    }
                });
            });

            // Click button modify in room section
            $("#btn_room_modify").click(function () {
                enable_room_editing();
            });

            // Close editing mode
            $("#btn_room_cancel").click(function () {
                disable_room_editing();
            });

            $(document).on('click',"#refresh_room_list", function (e) {
                e.preventDefault();
                $.ajax({
                    type: 'GET',
                    url: refresh_room_url,
                    dataType: "html",
                    success: function(resultData) {
                        $('#selected_rooms').html(resultData);
                    }
                });
            });

            $(document).on('click',"#sort_room_capacity", function (e) {
                e.preventDefault();
                $.ajax({
                    type: 'GET',
                    url: sort_room_capacity_url,
                    dataType: "html",
                    success: function(resultData) {
                        $('#selected_rooms').html(resultData);
                    }
                });
            });

            /* ---------------- Add Section ---------------- */

            $(document).on("click",".btn_room_edit",function () {
                $("#modal_exam_room_id").val($(this).data('roomid'));
                $("#modal_exam_room_name").val($(this).data('roomname'));
                $("#modal_exam_room_seat").val($(this).data('capacity'));
                $("#modal_exam_room_building").val($(this).data('building'));
                $("#modal_exam_room_description").val($(this).data('description'));

                $("#modal_exam_room_title").html(room_exam_edit_title);
                $('#modal_exam_room_modify').modal('toggle');
            });

            $(document).on("click","#btn_room_add",function () {
                $("#modal_exam_room_id").val(null);
                $("#modal_exam_room_name").val(null);
                $("#modal_exam_room_seat").val(null);
                $("#modal_exam_room_building").val(null);
                $("#modal_exam_room_description").val(null);

                $("#modal_exam_room_title").html(room_exam_add_title);
                $('#modal_exam_room_modify').modal('toggle');
            });

            $("#btn_add_save").click(function () {
                $.ajax({
                    type: 'POST',
                    url: add_room_url,
                    data: $('#form_exam_room_add').serialize(),
                    dataType: "html",
                    success: function(resultData) {
                        //$('#form_generate_room_wrapper').hide();
                        $('#selected_rooms').html(resultData);
                        $('#modal_exam_room_modify').modal('toggle');
                        get_total_seat(); // Added new roo, so update total seat
                        enable_room_editing();
                    }
                });
            });

            /* ---------------- Merge Section ---------------- */
            // Merge Room after select room
            $("#btn_room_merge").click(function () {
                var selected_rooms = $('#exam_room_list_table input:checkbox:checked').map(function () {
                    return $(this).data('roomname');
                }).get();
                var temporary_name = "";
                $.each(selected_rooms, function(index, value){
                    temporary_name = value+temporary_name;
                });

                $('#form_exam_room_merge input[name=name]').val(temporary_name);

                $('#modal_exam_room_merge').modal('toggle');
            });

            $("#btn_merge_save").click(function () {
                var data = $("#form_exam_room_merge").serializeArray();
                var selected_rooms = $('#exam_room_list_table input:checkbox:checked').map(function () {
                    return $(this).val();
                }).get();

                $.each(selected_rooms, function (index, value){
                    data.push({name: 'rooms[]', value: value});
                });

                $.ajax({
                    type: 'POST',
                    url: merge_room_url,
                    data: data,
                    dataType: "html",
                    success: function(resultData) {
                        //$('#form_generate_room_wrapper').hide();
                        $('#selected_rooms').html(resultData);
                        $('#modal_exam_room_merge').modal('toggle');
                        get_total_seat(); // Merge ready, so update seat
                        enable_room_editing();
                    }
                });
            });

            /* --------------------- Split Room Section ---------------------- */
            var next_split_index = null;
            var name_split = null;
            var size_split = null;
            var building_split = null;
            var buildings = {!! $buildings !!};
            var option_buildings = null;
            $.each(buildings, function(index, value){
               option_buildings = option_buildings+'<option value="'+index+'">'+value+'</option>';
            });

            function add_more_split(){

                var item =  '<div class="col-md-12 col-xs-12 room_split_added">'+
                                '<div class=" form-group col-md-6">' +
                                    '<div class="col-md-6">'+
                                        '<input type="text" name="name[]" value="'+name_split+"-"+next_split_index+'" class="form-control">'+
                                    '</div>'+
                                    '<div class="col-md-6">'+
                                        '<input type="number" name="nb_chair_exam[]" class="form-control" value="'+Math.floor(size_split/next_split_index)+'">'+
                                    '</div>'+
                                '</div>'+
                                '<div class=" form-group col-md-6">'+
                                    '<div class="col-md-6">'+
                                        '<select name="building_id[]" class="form-control room_split_building">'+
                                            option_buildings+
                                        '</select>'+
                                    '</div>'+
                                    '<div class="col-md-6">'+
                                        '<input type="text" name="description[]" class="form-control">'+
                                    '</div>'+
                                '</div>'+
                            '</div>';
                return item;
            }

            // Pop up split room panel
            $(document).on('click','.btn_room_split',function () {
                next_split_index = 1;
                name_split = $(this).data('roomname');
                size_split = $(this).data('capacity');
                building_split = $(this).data('building');
                $('#form_exam_room_split .room_split_name').each(function(i, obj) {
                    $(obj).val(name_split+"-"+next_split_index);
                    next_split_index++;
                });
                $('#split_room').val($(this).data('roomid'));
                $('.room_split_capacity').val(Math.floor(size_split/2));
                $('.room_split_building').val(building_split);
                $('.room_split_added').remove();
                $('#modal_exam_room_split').modal('toggle');
            });

            // Split more rooms
            $("#btn_add_more_split").click(function () {
                $('#room_split_wrapper').append(
                    add_more_split()
                );
                next_split_index++;
                $('.room_split_building').val(building_split);
            });

            // Save splitted room
            $("#btn_split_save").click(function () {

                $.ajax({
                    type: 'POST',
                    url: split_room_url,
                    data: $("#form_exam_room_split").serialize(),
                    dataType: "html",
                    success: function(resultData) {
                        $('#modal_exam_room_split').modal('toggle');
                        $('#selected_rooms').html(resultData);
                        enable_room_editing();
                        get_total_seat(); // Split ready, so update seat
                    }
                });
            });

            $(document).on('click',"#exam_room_header",function(e){
                if($(this).is(":checked")){
                    $(".exam_room_checkbox:not(:checked)").trigger("click");
                } else {
                    $(".exam_room_checkbox:checked").trigger("click");
                }
            });

            /* ------------------ Checkbox Event ---------------------*/
            $(document).on("click","#exam_room_list_table [name='exam_room[]']", function(){
                if($(this).is(":checked")){
                    $(this).closest('tr').addClass('highlight');
                } else {
                    $(this).closest('tr').removeClass('highlight');
                }

                if(($('[name="exam_room[]"]:checked').length > 0)){
                    $('#btn_room_merge').prop('disabled',false);
                    $('#btn_room_delete').prop('disabled',false);
                    $('#btn_seat_edit').prop('disabled',false);
                }else{
                    $('#btn_room_merge').prop('disabled',true);
                    $('#btn_room_delete').prop('disabled',true);
                    $('#btn_seat_edit').prop('disabled',true);
                }
            });

            // Apply class editing on tbody or table to allow the below behavior
            $(document).on('click','.editing tr',function(event) {
                if (event.target.type !== 'checkbox') {
                    $(':checkbox', this).trigger('click');
                }
            });

            $("#btn_room_delete").click(function(){

                $.ajax({
                    type: 'POST',
                    url: delete_room_url,
                    data: $('#form_editing_exam_room').serialize(),
                    dataType: "html",
                    success: function(resultData) {
                        //update_ui_room(); // Data changed, so we need to refresh UI
                        $('#selected_rooms').html(resultData);
                        get_total_seat(); // Delete ready, so update seat
                        enable_room_editing();
                    }
                });
            });

            $("#btn_seat_edit").click(function(){

                $('#modal_exam_room_seat').modal('toggle');
            });

            $("#btn_seat_edit_save").click(function () {
                var data = $("#form_exam_room_seat").serializeArray();
                var selected_rooms = $('#exam_room_list_table input:checkbox:checked').map(function () {
                    return $(this).val();
                }).get();

                console.log(selected_rooms);
                $.each(selected_rooms, function (index, value){
                    data.push({name: 'rooms[]', value: value});
                });

                $.ajax({
                    type: 'POST',
                    url: edit_seat_url,
                    data: data,
                    dataType: "html",
                    success: function(resultData) {
                        //$('#form_generate_room_wrapper').hide();
                        $('#selected_rooms').html(resultData);
                        $('#modal_exam_room_seat').modal('toggle');
                        get_total_seat(); // Merge ready, so update seat
                        enable_room_editing();
                    }
                });
            });
            @endauth
            /* ------------------------------------------------------------------------ Candidate Section ------------------------------------------------------------------ */
            @permission('view-exam-candidate')
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
                    { data: 'name_latin', name: 'candidates.name_latin'},
                    { data: 'gender_name_kh', name: 'genders.name_kh'},
                    { data: 'dob', name: 'candidates.dob'},
                    { data: 'province', name: 'origins.name_kh'},
                    { data: 'high_school', name: 'highSchools.name_kh'},
                    { data: 'bac_total_grade', name: 'bac_total_grade'},
                    { data: 'room', name: 'candidates.room', searchable:false},
                    { data: 'result', name: 'candidates.result'},
                    { data: 'action', name: 'action',orderable: false, searchable: false}
                ]
            });

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
            enableDeleteRecord($('#candidates-table'));

            $(document).on('click', '#btn_input_score_course', function (e) {
                window_request_room = PopupCenterDual('{{route("admin.exam.request_input_score_courses",$exam->id)}}','Course for exam','1000','450');
            });



            $(document).on('click', '#btn_result_score_candidate', function (e) {
                window_request_room = PopupCenterDual('{{route("admin.exam.candidate_exam_result_score",$exam->id)}}','Candidate Result Score','800','470');
            });

            $(document).on('click', '#btn_add_candidate', function (e) {
                window_bac2 = PopupCenterDual('{{route("admin.studentBac2.popup_index")."?exam_id=".$exam->id}}','Add new customer','1200','960');
            });

            $(document).on('click', '#btn_add_candidate_manual', function (e) {
                window_candidate = PopupCenterDual("{!! route('admin.candidates.create').'?exam_id='.$exam->id.'&studentBac2_id=0' !!}",'Add new Candidate','1200','960');
            });

            $("#btn-candidate-refresh").click(function(){
                refresh_candidate_list();
            });

            // check if there is missing candidates in a separate request
            $(document).ready(function(){
                check_missing_candidates();
            });

            $("#btn-candidate-generate-room").click(function(){
                toggleLoading(true);
                $.ajax({
                    type: 'GET',
                    url: "{{route('admin.exam.candidate.generate_room',$exam->id)}}",
                    dataType: "json",
                    success: function(resultData) {
                        if(resultData.status = true){
                            toggleLoading(false);
                            notify('success','Generate Room', resultData.message);
                            candidate_datatable.draw();
                            count_assigned_seat();
                        } else {
                            notify('error','Generate Room', resultData.message);
                        }
                    }
                });
            });



            $(document).on('click','.btn_candidate_edit',function(e){
                e.preventDefault();
                candidate_window = PopupCenterDual($(this).attr('href'),'Update Candidate','1200','960');
            });


            $(document).on('click','#btn_show_missing_candidate',function(e){
                e.preventDefault();
                window_missing_candidate = PopupCenterDual($(this).attr('href'),'Missing Candidate Register IDs','1200','960');
            });


            // ---------DUT Examination------

            $('#btn_generate_result').on('click', function() {

                PopupCenterDual('{!! route('admin.exam.request_form_generate_score', $exam->id) !!}','Form To Generate Score','900','450');

            });


            @endauth
            /* ------------------------------------------------------------------------ Course Section ------------------------------------------------------------------ */
            @permission('view-entrance-exam-course')

            $('#btn-course-refresh').on('click',function(e){
                course_datatable.draw();
            });
            if(exam_type_id == 1){
                course_datatable = $('#table-exam-course').DataTable({
                    processing: true,
                    serverSide: true,
                    pageLength: {!! config('app.records_per_page')!!},
                    ajax: {
                        url: '{!! route('admin.entranceExamCourses.data',$exam->id) !!}',
                        method: 'POST',
                        data: function(d){
                            d.check_course_error= check_course_error
                        }
                    },
                    columns: [
                        { data: 'name_kh', name: 'entranceExamCourses.name_kh'},
                        { data: 'total_question', name: 'entranceExamCourses.total_question'},
                        { data: 'description', name: 'entranceExamCourses.description'},
                        { data: 'action', name: 'action',orderable: false, searchable: false}
                    ]
                });

            } else {
                course_datatable = $('#table-exam-course').DataTable({
                    processing: true,
                    serverSide: true,
                    pageLength: {!! config('app.records_per_page')!!},
                    ajax: {
                        url: '{!! route('admin.exam.get_courses',$exam->id) !!}',
                        method: 'POST',
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

            enableDeleteRecord($('#table-exam-course'));

            $(document).on('click', '.btn-report-error', function (e) {
                var course_id = $(this).data('remote');
                window_report_error = PopupCenterDual(report_score_url+"?course_id="+course_id,'Error Inputted Score Form ','1250','960');

            });

            $('#btn_result_score_candidate').hide();

            $('#btn_check_course_error').on('click', function() {
                check_course_error = true;
                course_datatable.draw();
                var baseUrl ='{{route("admin.exam.ajax_check_candidate_score", $exam->id)}} ';

                $.ajax({
                    type: 'GET',
                    url: baseUrl,
                    success: function(result) {
                        console.log(result.status);
                        if(result.status == false) {

                            $('#btn_result_score_candidate').show();
                        } else {
                            $('#btn_result_score_candidate').hide();
                        }
                    }
                });
            })

            $("#btn-add-course").click(function(){
                window_course = PopupCenterDual('{{route("admin.entranceExamCourses.create")}}'+'?exam_id='+'{{$exam->id}}','Course for exam','800','470');
            });

            $(document).on('click','.btn_course_edit',function(e){
                e.preventDefault();
                window_course = PopupCenterDual($(this).data('remote'),'Update Entrance Exam Course','1200','960');
            });


            @endauth
            /* ----------------------------------------------------------------------------------------------------------------*/
        });


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

            if (window_missing_candidate && !window_missing_candidate.closed) {
                window_missing_candidate.close();
            }
        };


    </script>
@stop
