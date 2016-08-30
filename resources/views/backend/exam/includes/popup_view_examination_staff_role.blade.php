
@extends ('backend.layouts.popup_master')

@section ('title', trans('labels.backend.exams.title') . ' | ' . 'Staff Role Listes')

@section('content')

    <div class="box box-success">

        <style>


        </style>
        <div class="box-header with-border">
            <h3 class="box-title">Listes of Staff Role</h3>
            <div class="pull-right">
                <button id="assign_room" class="btn btn-primary"> Assign </button>
                <button class="btn btn-primary" id="print_staff_role"> Print </button>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">

            <div class="col-md-12">
                <h1 id="hello"> </h1>
            </div>

            <div class="col-md-12">

                <div class="col-md-6">
                    <select name="course" id="entran_exam_course">
                        @foreach($res[1] as $course)
                            <option value="{{$course->course_id}}"> {{$course->course_name}} </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <select name="role" id="staff_role_selection">
                        @foreach($res[0] as $role)
                            <option value="{{$role->id}}"> {{$role->name}} </option>
                        @endforeach
                    </select>
                </div>

            </div>


            <div class="col-md-12">

                <div class="col-md-8 staff_by_role">


                </div>

                <div class="col-md-4 available_room" >


                </div>
            </div>

            <div class="col-md-12 testing">

            </div>



        </div>
    </div>
@stop

@section('after-scripts-end')

    <script>

        var data = [];
        var baseUrl = "{{route('admin.exam.get_staff_by_role_course',$examId)}}";
        var roomUrl = "{{route('admin.exam.get_room_list_by_role',$examId)}}";

        var baseData = {    course_id: $('#entran_exam_course :selected').val(),
                            role_id: $('#staff_role_selection :selected').val()};
        ajaxRequest('GET',baseUrl, baseData);

        requestRoom('GET', roomUrl,baseData);


        $('#staff_role_selection').on('change', function() {

            var baseData = {    course_id: $('#entran_exam_course :selected').val(),
                                role_id: $('#staff_role_selection :selected').val()};
            var baseUrl = "{{route('admin.exam.get_staff_by_role_course',$examId)}}";
            ajaxRequest('GET',baseUrl, baseData);

            var roomUrl = "{{route('admin.exam.get_room_list_by_role',$examId)}}";
            requestRoom('GET', roomUrl,baseData);

        });

        function ajaxRequest(method, baseUrl, baseData){
            $.ajax({
                type: method,
                url: baseUrl,
                data: baseData,
                success: function(result) {
                    $('.staff_by_role').html(result);
                }
            });
        }

        function requestRoom(method, baseUrl, baseData){
            $.ajax({
                type: method,
                url: baseUrl,
                data: baseData,
                success: function(result) {
                    $('.available_room').html(result);
                }
            });
        }

        function Request(method, baseUrl, baseData){
            $.ajax({
                type: method,
                url: baseUrl,
                data: baseData,
                dataType:'json',
                success: function(result) {
                    console.log(result);
                    if(result.status == true) {
                        var baseUrl = "{{route('admin.exam.get_staff_by_role_course',$examId)}}";
                        var roomUrl = "{{route('admin.exam.get_room_list_by_role',$examId)}}";

                        var baseData = {    course_id: $('#entran_exam_course :selected').val(),
                            role_id: $('#staff_role_selection :selected').val()};
                        ajaxRequest('GET',baseUrl, baseData);

                        requestRoom('GET', roomUrl,baseData);
                    }
                }
            });
        }


        function getRoomCheckedVal() {

            var val = [];
            $('.checkbox_room:checkbox:checked').each(function(i){
                val[i] = $(this).val();
            });
            return val;
        }

        function getStaffCheckedVal() {

            var val = [];
            $('.checkbox_staff:checkbox:checked').each(function(i){
                val[i] = $(this).val();
            });

            return val;
        }

        function storeStaffRoleRooms(rooms, staffs) {

            var courseName = $('#entran_exam_course :selected').val();
            var roleName = $('#staff_role_selection :selected').val();
            var staffWithRooms = {
                course_id: courseName,
                role_id: roleName,
                staff_id: staffs,
                room_id: rooms
            }
            return staffWithRooms;

        }

        function getStaffByRole () {

            var course_id =  $('#entran_exam_course :selected').val();
            var role_name =  $('#staff_role_selection :selected').text();



            for(var key = 0; key< AllStaffs.length; key++) {

                if( AllStaffs[key].role_name == role_name.trim()) {

                    console.log(AllStaffs[key].staffs);

                    for (var i = 0; i < AllStaffs[key].staffs.length; i++) {

                        var newItem = $("<tbody class='table'><tr>" + "<td>" + i + "</td>" + "<td>" + AllStaffs[key].staffs[i].text + "<td>" + '' + "</td>"+ "</td>"+ "</tr></tbody>");

                        console.log(newItem.html());

                        $("#staff_tab").after(newItem.html());
                    }
                }
            }
        }
        function deleteRoom(key) {
            var roomId = $('#room_'+key).attr('value');
            var params = key.split('_');
            var roomName = $('#room_'+key).attr('name');

            console.log(params);
            var res =confirm('Do you want to delete ROOM : '+roomName);
            var baseUrl = "{{route('admin.exam.delete_room_from_staff',$examId)}}";
            var baseData ={
                room_id: roomId,
                staff_id: params[2],
                role_id: params[1],
                staff_type: params[0],

            };
            if(res == true) {
                Request('DELETE', baseUrl,baseData );
            }

//            window_report_error = PopupCenterDual(popUpUrl+"?staff_role_id=" + key+"&&room_id=" +roomId,'Error Inputted Score Form ','250','200');
        }



        $('#assign_room').on('click', function() {

            var baseUrl = "{{route('admin.exam.update_staff_with_room',$examId)}}";
            var baseData =  storeStaffRoleRooms(getRoomCheckedVal(), getStaffCheckedVal());
            Request('PUT', baseUrl, baseData);

        })


        $('#print_staff_role').on('click', function() {

            var baseUrl = '{!! route('admin.exam.staff_role_room_examination_export', $examId) !!}';
            window.location.href = baseUrl;

            {{--var baseUrl  = "{!! route('admin.exam.print_role_staff_lists', $examId) !!}";--}}

            {{--window_print_candidate_result = PopupCenterDual(baseUrl, 'print staff role listes','1000','1200');--}}
        })
    </script>

@stop