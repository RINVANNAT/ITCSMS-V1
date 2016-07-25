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
                    <label class="col-sm-3 "> Select Building: </label>
                    <div class="col-sm-3 no-padding">
                        <select style="margin-left: -55px" name="building" id="building_input_score_id">
                            @foreach($buildings as $building)
                                <option value="{{$building->id}}"> {{$building->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <label class="col-sm-3" style="margin-left: -50px"> Select Room: </label>
                    <div class="col-sm-3 no-padding">
                        <select name="room" id="room_id_input_score" style="margin-left: -70px">
                            @foreach($rooms as $room)
                                <option class="col-sm-4" value="{{$room->id}}"> {{$room->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-sm-12" style="margin-top: 10px;">
                    <label class="col-sm-3 no-padding"> Select Subject: </label>
                    <div class="col-sm-9 no-padding">
                        <select name="subject" id="subject_id" style="float: left; margin-left: -62px">
                            @foreach($courses as $course)
                                <option class="col-sm-4" value="{{$course->id}}"> {{$course->name_kh}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{--<div class="col-sm-12">--}}
                    {{--<botton class="btn btn-default" id="request_input"> OK </botton>--}}
                {{--</div>--}}

            </div>
        </div>

    <div class="box box-success">
        <div class="box-body">
            <div class="pull-left">
                <a href="#" id="request_input" class="btn btn-default btn-xs">Cancel</a>
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

       function ajaxRequest(method, baseUrl, baseData){
           console.log('hello');
           $.ajax({
               type: method,
               url: baseUrl,
               data: baseData,
               dataType: "json",
               success: function(resultData) {
                   console.log(resultData);
                   var selectOption = $('#room_id_input_score');
//                   selectOption.find('option')
//                           .remove()
//                           .end();
                   selectOption.empty();
                   for( var i=0; i<resultData.length; i++) {
                       console.log(resultData[i]['room_name']);
                       selectOption.append(

                               $('<option></option>').val(resultData[i]['room_id']).text(resultData[i]['room_name'])
                       );
                   }
                   notify("success","info", "You have done!");
               }
           });
       }


       $( "#building_input_score_id" ).change(function() {
//           alert( "Handler for .change() called." );
           var changeBuildingUrl = "{{route('admin.exam.request_change_building_room',$exam_id)}}";
           var baseData = {building_id: $('#building_input_score_id :selected').val()};
           ajaxRequest('GET',changeBuildingUrl, baseData);
       });

       $('#btn_ok_request_input_score').on('click', function() {
           var requestInputFormUrl =  "{{route('admin.exam.request_input_score_form',$exam_id)}}";
           var requestData = {
               building_id:$('#building_input_score_id :selected').val(),
               room_id: $('#room_id_input_score :selected').val(),
               room_name: $('#room_id_input_score :selected').text(),
               entrance_course_id: $('#subject_id :selected').val()
           }
           ajaxRequest('POST',requestInputFormUrl, requestData );
       });

       {{--$(document).on('click', '#btn_input_score_course', function (e) {--}}
           {{--PopupCenterDual('{{route("admin.exam.request_input_score_courses",$exam->id)}}','Course for exam','800','470');--}}
       {{--});--}}

   </script>
@stop