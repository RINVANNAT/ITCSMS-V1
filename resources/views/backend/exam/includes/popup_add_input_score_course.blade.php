@extends ('backend.layouts.popup_master')

@section ('title', trans('labels.backend.exams.title') . ' | ' . 'Request Input Score Form')

@section('content')
    <style>
        .text_font{
            font-size: 18pt;
        }
        .area{
            font-size: 26px;
            border-radius: 0;
            background: transparent;
            width: 180px;
            text-indent: 10px;
        }

    </style>

        <div class="box box-success">
            <div class="box-header with-border text_font">
                <h1 class="box-title"> <span class="text_font">Request Input Score</span></h1>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                {{--here what i need to write --}}

                <div class="col-sm-12 no-padding">
                    <div class="col-sm-12">

                    </div>

                    <label class="col-sm-4  text_font"> Select Subject: </label>
                    <div class="col-sm-2 no-padding text_font">
                        <select  name="subject" class="area" id="subject_id" style="float: left; margin-left: -115px;">
                            @foreach($courses as $course)
                                <option class="col-sm-4" value="{{$course->id}}"> {{$course->name_kh}}</option>
                            @endforeach
                        </select>
                    </div>

                </div>
                <form id="correctionForm">
                    <div class="col-sm-12 text_font correction_1" style="margin-top: 15px">
                        <input type="radio" name="correction" id="first_scoring_attemp" value="1">
                        <label style="margin-left: 10px" > Correction 1  </label>

                    </div>

                    <div class="col-sm-12 text_font correction_2">
                        <input type="radio" name="correction" id="second_scoring_attemp" value="2">
                        <label  style="margin-left: 10px"> Correction 2  </label>

                    </div>
                </form>

                <div class="col-sm-12 text_font row" style="margin-bottom: 20px">



                </div>

                <div class="col-sm-12 no-padding selection_room_course">

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

           $.ajax({
               type: method,
               url: baseUrl,
               data: baseData,
               success: function(result) {
                   console.log(result);
                   $('.selection_room_course').html(result);


               }
           });
       }

       $('#btn_cancel_request_input').on('click', function() {
           window.close();
       });

       var numberCorrection = null;
       var baseUrl = "{{route('admin.exam.ajax_request_room_course_selection',$exam_id)}}";
       $('#correctionForm input').on('change', function() {
           numberCorrection = $('input[name="correction"]:checked', '#correctionForm').val();
           var baseData = { number_correction: numberCorrection,
               entrance_course_id: $('#subject_id :selected').val()};
           ajaxRequest('GET',baseUrl, baseData);
       });

       $('#subject_id').on('change', function() {

           if(numberCorrection != null) {
               var baseUrl = "{{route('admin.exam.ajax_request_room_course_selection',$exam_id)}}";
               var baseData = { number_correction: numberCorrection,
                   entrance_course_id: $(this).val()};
               ajaxRequest('GET',baseUrl, baseData);
           }
       });



       $("#btn_ok_request_input_score").on("click",function(){

           var requestData = {
               room_id: $('#room_id_input_score :selected').val(),
               room_code: $('#room_id_input_score :selected').text(),
               entrance_course_id: $('#subject_id :selected').val(),
               entrance_course_name: $('#subject_id :selected').text()
           };
           if(requestData.room_id) {
               if(numberCorrection !== null) {
                   console.log(numberCorrection);
                   input_score_window = PopupCenterDual(input_score_url+"?room_id="+requestData.room_id+"&entrance_course_id="+requestData.entrance_course_id+"&number_correction="+ numberCorrection +"&course_name="+requestData.entrance_course_name + "&room_code=" + requestData.room_code,'request input form ','1250','960');
               }
           }



       });

   </script>
@stop