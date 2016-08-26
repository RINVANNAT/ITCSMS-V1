@extends ('backend.layouts.popup_master')

<div style="text-align: center; margin-top: 15px">
    <h4>Do you really want to delete?? </h4>
    <br/>
    <h4 style="color:darkred; margin-top: -20px">ROOM : {{$roomName}}</h4>
    <br/>

    <input type="hidden" name="room_id" id="delete_room_id" value="{{$roomId}}">
    <input type="hidden" name="role_id" id="delete_role_id" value="{{$roleId}}">
    <input type="hidden" name="staff_id" id="delete_staff_id" value="{{$staffId}}">
    <input type="hidden" name="staff_type" id="delete_staff_type" value="{{$staffType}}">

    <button id="delete_room" style="color: red"> Delete</button>
    <button id="cancel_delete_room"> Candel</button>
</div>
@section('after-scripts-end')
    <script>

        function ajaxRequest(method, baseUrl, baseData){
            $.ajax({
                type: method,
                url: baseUrl,
                data: baseData,
                dataType: 'json',
                success: function(result) {
                    if(result.status) {
                        window.close();
                        window_view_role_staff.location.reload();
                    }
                }
            });
        }
        $('#delete_room').on('click',function() {

            var baseUrl = "{{route('admin.exam.delete_room_from_staff',$examId)}}";
            var baseData ={
                room_id: $('#delete_room_id').val(),
                staff_id: $('#delete_staff_id').val(),
                role_id: $('#delete_role_id').val(),
                staff_type: $('#delete_staff_type').val(),

            }

            ajaxRequest('DELETE', baseUrl,baseData )

        });
    </script>
@stop
