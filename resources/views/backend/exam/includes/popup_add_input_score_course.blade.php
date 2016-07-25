@extends ('backend.layouts.popup_master')

@section ('title', trans('labels.backend.exams.title') . ' | ' . trans('labels.backend.exams.course.add_course'))

@section('content')


        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Request Input Score</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                {{--here what i need to write --}}
                <div class="col-sm-12 no-padding">
                    {{--<label class="col-sm-3 "> Select Building: </label>--}}
                    {{--<div class="col-sm-3 no-padding">--}}
                        {{--<select style="margin-left: -55px" name="building" id="building_input_score_id">--}}
                            {{--@foreach($buildings as $building)--}}
                                {{--<option value="{{$building->id}}"> {{$building->name}}</option>--}}
                            {{--@endforeach--}}
                        {{--</select>--}}
                    {{--</div>--}}
                    <label class="col-sm-3"> Select Room Code: </label>
                    <div class="col-sm-3 no-padding" style="margin-left: -30px;">
                        <select name="room" id="room_id_input_score">
                            @foreach($rooms as $room)
                                <option class="col-sm-4" value="{{$room['room_id']}}"> {{$room['room_code']}}</option>
                            @endforeach
                        </select>
                    </div>

                    <label class="col-sm-3 no-padding"> Select Subject: </label>
                    <div class="col-sm-3 no-padding">
                        <select  name="subject" id="subject_id" style="float: left; margin-left: -75px">
                            @foreach($courses as $course)
                                <option class="col-sm-4" value="{{$course->id}}"> {{$course->name_kh}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-sm-12">

                </div>
                <div class="col-sm-12" style="margin-top: 15px">
                    <input id="first_scoring_attemp" value="1" type="checkbox" >
                    <label style="margin-left: 10px" > First Attemp  </label>
                </div>

                <div class="col-sm-12">
                    <input id="second_scoring_attemp" value="2" type="checkbox" >
                    <label  style="margin-left: 10px"> Second Attemp  </label>
                </div>

            </div>
        </div>

    <div class="box box-success">
        <div class="box-body">
            <div class="pull-left">
                <a href="#" id="btn_cancel_request_input" class="btn btn-default btn-xs">Cancel</a>
            </div>

            <div class="pull-right">
                <input type="button" id="btn_ok_request_input_score" class="btn btn-primary btn-xs" value="OK" />
            </div>
            <div class="clearfix"></div>
        </div><!-- /.box-body -->
    </div><!--box-->
@stop

@section('after-scripts-end')
   {{--here where i need to write the js script --}}
   <script>

       var input_score_url = "{{route('admin.exam.request_input_score_form',$exam_id)}}";

       function ajaxRequest(method, baseUrl, baseData){
           console.log('hello');
           $.ajax({
               type: method,
               url: baseUrl,
               data: baseData,
               dataType: "json",
               success: function(result) {
                   console.log(result);
                   if(result.status =='room_success') {
                       var selectOption = $('#room_id_input_score');
                       selectOption.empty();
                       for( var i=0; i<result.Data.length; i++) {
                           console.log(result.Data[i]['room_name']);
                           selectOption.append(

                                   $('<option></option>').val(result.Data[i]['room_id']).text(result.Data[i]['room_name'])
                           );
                       }
                   }
                   notify("success","info", "You have done!");
               }
           });
       }


       $( "#building_input_score_id" ).change(function() {

           var changeBuildingUrl = "{{route('admin.exam.request_change_building_room',$exam_id)}}";
           var baseData = {building_id: $('#building_input_score_id :selected').val()};
           ajaxRequest('GET',changeBuildingUrl, baseData);
       });





       $('#btn_cancel_request_input').on('click', function() {
           window.onunload = function() {
//               candidate_window.close();
           };
       });


       var first_attemp = null;
       var second_attemp= null;

       function validateCheckbox(var1, var2) {
           if(var1 !== null && var2 === null) {

               return var1;
               console.log('ok');
           } else if(var1 === null && var2 !== null) {
               console.log('ok');
               return var2;

           } else if(var1 ===null && var2 ===null){
               alert('please select number of attemp');
               return null;
           } else {
               alert('you cannot select both at the same time');
               return null;
           }
       }

        $('#first_scoring_attemp').on('change', function() {
            if($(this).is(":checked")) {
                first_attemp = $(this).val();
                validateCheckbox(first_attemp,second_attemp );
            }else {
                first_attemp = null ;
            }
        })

       $('#second_scoring_attemp').on('change', function() {
           if($(this).is(":checked")) {
               second_attemp = $(this).val();
               validateCheckbox(first_attemp,second_attemp );
           } else {
               second_attemp = null;
           }
       });


           $("#btn_ok_request_input_score").on("click",function(){

               var requestData = {
                   room_id: $('#room_id_input_score :selected').val(),
                   room_code: $('#room_id_input_score :selected').text(),
                   entrance_course_id: $('#subject_id :selected').val(),
                   entrance_course_name: $('#subject_id :selected').text()
               };
               if(first_attemp!==null && second_attemp !== null) {
                   alert('no');
               } else if (first_attemp ===null && second_attemp === null) {
                   alert('no selected attemp');
               } else {
                   input_score_window = PopupCenterDual(input_score_url+"?room_id="+requestData.room_id+"&entrance_course_id="+requestData.entrance_course_id+"&first_attemp="+ first_attemp +"&second_attemp="+ second_attemp +"&course_name="+requestData.entrance_course_name + "&room_code=" + requestData.room_code,'request input form ','1200','960');
               }

           });





   </script>
@stop