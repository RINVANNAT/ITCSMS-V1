@extends ('backend.layouts.popup_master')

@section ('title', trans('labels.backend.exams.title') . ' | ' . 'Report Candidates Score Error')

@section('content')

    <style>
        .error{
            color: #9c0033;
        }
        .equal{
            color: darkgreen;
        }
        .enlarge-number{
            font-size: 20px;
        }
        .number_only{
            border: 1px solid black;
        }

        .myloading {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: white;
            z-index: 1;
        }

    </style>
    <div class="box box-success">
        <div class="box-header with-border">
            <div class="col-sm-12">
                <div class="col-sm-6">
                    <h4> Error Inputed Score </h4>
                </div>

                <div class="col-sm-6 no-padding">
                    <div class="col-sm-3 no-padding" style="margin-top: 5px">
                        <label for="corrector_name"> Corrector Name : </label>
                    </div>

                    <div class="col-sm-9 no-padding">
                        {!! Form::text('corrector', null, ['class' => 'form-control', 'placeholder'=>'name corrector', 'id'=> 'corrector_error_score', 'required']) !!}
                    </div>
                </div>
            </div>
        </div>
        <!-- /.box-header -->

        <div class="myloading" style=" text-align: center">
            <img src="{{url('img/exam/loader.gif')}}" alt="load" style="margin-top: 400px">

        </div>
        <div class="box-body">
            {{--here what i need to write--}}
            <div class="container">

                <div class="col-sm-12 no-padding" style="margin-top: -15px">

                </div>

                <div class="col-sm-12 no-padding" style="margin-top: 5px">

                    <table class="table">
                        <thead>

                        </thead>
                        <tbody>

                        <div class="col-sm-12">

                            <div class="col-sm-2 no-padding pull-left">
                                <label for="room code pull-right"> Room Code </label>
                            </div>
                            <div class="col-sm-1  no-padding pull-left">
                                <label for="order"> Order </label>
                            </div>
                            <div class="col-sm-2  no-padding ">
                                <label for="Wrong Answer"> Correct  </label>
                            </div>
                            <div class="col-sm-2  no-padding ">
                                <label for="Wrong Answer"> Wrong  </label>
                            </div>
                            <div class="col-sm-2  no-padding">
                                <label for="NA">No Answer </label>
                            </div>
                            <div class="col-sm-1  no-padding">
                                <label for="NA"> Total </label>
                            </div>
                            <div class="col-sm-1  no-padding ">
                                <label for="correction " style="text-align: center"> Correction </label>
                            </div>

                            <div class="col-sm-1">

                            </div>
                        </div>


                        <?php $k=0; $length = []; $p=0; $arrayRoomCode = []; $arrayOrderInRoom = [];?>
                        @foreach($errorCandidateScores as $errorScoreProperties)
                            <?php $k++; $p=0;  ?>

                            <div class="box-body with-border">

                                <div class="col-sm-12 old_correction" style="background-color: #F5EAEC; padding-top: 5px;">

                                    @foreach($errorScoreProperties->scoreErrors as $errorScore)
                                        <?php  $p++;?>

                                            <div class="col-sm-2 enlarge-number">
                                                <p > {{$errorScoreProperties->candidateProperties->room_code}} </p>
                                            </div>
                                            <div class="col-sm-1 enlarge-number">
                                                <p >{{$errorScore->candidate_number_in_room}}</p>
                                            </div>
                                            <div class="col-sm-2">
                                                <p class="score_c_<?php echo $k;?> enlarge-number" id="score_c_<?php echo $k.'_'.$p;?>">{{$errorScore->score_c}}</p>
                                            </div>
                                            <div class="col-sm-2">
                                                <p class="score_w_<?php echo $k;?> enlarge-number" id="score_w_<?php echo $k.'_'.$p;?>" >{{$errorScore->score_w}}</p>
                                            </div>
                                            <div class="col-sm-2">
                                                <p class="score_na_<?php echo $k;?> enlarge-number" id="score_na_<?php echo $k.'_'.$p;?>" >{{$errorScore->score_na}}</p>
                                            </div>
                                            <div class="col-sm-1">
                                                <p class="score_total_<?php echo $k;?> enlarge-number" id="score_total_<?php echo $k.'_'.$p;?>">{{$errorScore->score_c + $errorScore->score_w + $errorScore->score_na}}</p>
                                            </div>
                                            <div class="col-sm-1">
                                                <p class="socre_properties_<?php echo $k;?> enlarge-number" style="border: 2px solid orangered; text-align: center"> {{$errorScore->sequence}}</p>
                                            </div>

                                    @endforeach


                                        <?php array_push($length,$p);   $arrayRoomCode[] =  $errorScoreProperties->candidateProperties->room_code; $arrayOrderInRoom[] = $errorScore->candidate_number_in_room;?>

                                        <div class="col-sm-1">
                                            <button class="btn-xs" onclick="addNewCorrection(this)"> Add </button>
                                        </div>
                                </div>
                                <div class="col-sm-12 new_correction" style="background-color: #F5EAEC;display: none;">
                                    <form class="new_correction_form">
                                        <div class="col-sm-2 enlarge-number">
                                            <label for="order">{{$errorScoreProperties->candidateProperties->room_code}}</label>
                                            {!! Form::hidden('candidate_id[]', $errorScoreProperties->candidateProperties->candidate_id, ['class' => 'form-control candidate_has_score_error_id']) !!}

                                        </div>

                                        <div class="col-sm-1 enlarge-number" >
                                            <label for="roomCode">{{$errorScore->candidate_number_in_room}}</label>
                                            {!! Form::hidden('order[]', $errorScore->candidate_number_in_room, ['class' => 'form-control candidate_order_in_room']) !!}
                                        </div>
                                        <div class="col-sm-2">
                                            {!! Form::text('score_c[]', 0, ['class' => 'form-control number_only enlarge-number score_c input_new_correction']) !!}

                                        </div>
                                        <div class="col-sm-2">
                                            {!! Form::text('score_w[]', 0, ['class' => 'form-control number_only enlarge-number score_w input_new_correction']) !!}

                                        </div>
                                        <div class="col-sm-2">
                                            {!! Form::text('score_na[]', 0, ['class' => 'form-control number_only enlarge-number score_na input_new_correction']) !!}
                                            {!! Form::hidden('course_id[]', $errorScore->course_id, ['class' => 'form-control']) !!}
                                        </div>

                                        <div class="col-sm-1">
                                            {!! Form::text('score_total[]', 0, ['class' => 'form-control enlarge-number score_total', 'disabled']) !!}
                                        </div>

                                        <div class="col-sm-1 enlarge-number ">
                                            <p style="border: 2px solid orangered; text-align: center" > {{$errorScore->sequence + 1}}</p>
                                            {!! Form::hidden('sequence[]', $errorScore->sequence + 1, ['class' => 'form-control' ]) !!}
                                        </div>

                                        <div class="col-sm-1 " style="margin-top: 5px" >

                                            <button type="submit" class="btn btn-info btn-xs btn_save_new_correction"> <i class="fa fa-save"> </i> </button>
                                            <button type="button" onclick="cancelNewCorrection(this)" class="btn btn-danger btn-xs"><i class="fa fa-times"> </i> </button>

                                        </div>
                                    </form>

                                </div>
                            </div>



                        @endforeach

                        </tbody>
                    </table>
                </div>





            </div>

        </div>
    </div>
    <div class="box box-success">
        <div class="box-body">
            <div class="pull-left">
                <a href="#" id="cancel_error_score_form" class="btn btn-default btn-xs">{{ trans('buttons.general.cancel') }}</a>
            </div>

            <div class="pull-right">
                <input type="button" id="submit_candidate_error_socre" class="btn btn-primary btn-xs" value="Submit" />
                <input type="button" id="btn_candidate_error_socre" class="btn btn-danger btn-xs" value="Print Error" />
            </div>
            <div class="clearfix"></div>
        </div><!-- /.box-body -->
    </div><!--box-->
@stop

@section('after-scripts-end')
   {{--here my script --}}

   <script>

       @if(count($errorCandidateScores)==0)
               window.close();
       @endif

       var store_array_correction = [];
       var serializeData = [];
       var tmp = null;
       $(document).ready(function () {
           $('.myloading').hide();

           $(".number_only").keypress(function (e) {
               if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                   return false;
               }
           });

           $(".new_correction_form").on('submit',function(e){
               e.preventDefault();
               var data = $(this).serializeArray();
               var status = 0;
               $.each(data, function(index,value) {
                    if(value.name =='score_c[]') {
                        if(value.value == '') {
                            status++
                        }
                    }
                   if(value.name =='score_w[]') {
                       if(value.value == '') {
                           status++
                       }
                   }
                   if(value.name =='score_na[]') {
                       if(value.value == '') {
                           status++
                       }
                   }
               });
               var baseData =$(this).serialize();
               var baseUrl = "{{route('admin.exam.add_new_correction_score',$exam_id)}}";
               var corrector_name = $('#corrector_error_score').val();

               if(corrector_name != '') {
                   if(status == 0) {
//                       ajaxRequest('POST', baseUrl+'?corrector_name='+corrector_name,baseData );
                       // check to store the inputted value without redandanc data
                       if(store_array_correction.length > 0) {
                           var check=0;
                           $.each(store_array_correction, function(key, value) {

                               for( var term=0; term<value.length; term++) {
                                   if(value[term].name == 'candidate_id[]') {
                                       if(value[term].value == data[term].value) {
                                           check++;
                                           serializeData[key] = baseData;
                                       }
                                   }
                               }
                           });

                           if(check == 0) {
                               store_array_correction.push(data);
                               serializeData.push(baseData);
                           }
                       } else {
                           store_array_correction.push(data);
                           serializeData.push(baseData);
                       }

                       console.log(serializeData);
                       notify("success","info", "done!");
                       $('.new_correction').delay(1000).hide(0);

                   } else {
                       notify("error","info", "Required Score Value!!");
                   }

               } else {
                   notify("error","info", "Please Add The Corrector Name!!!");
               }

           });

           $('.input_new_correction').on('keyup', function() {
                calculateSum(this);
           });


       });

       var length = JSON.parse('<?php echo $k ?>');
       for(var i =1; i <=length; i++) {
           $('#new_correction_score_'+i).hide();
       }

       function cancelNewCorrection(obj){
           $(obj).closest('.new_correction').hide();
       }

       function addNewCorrection(obj){
           //console.log("new");
           $(obj).closest('.old_correction').next('.new_correction').show();
       }

       function ajaxRequest(method, baseUrl, baseData){
           $.ajax({
               type: method,
               url: baseUrl,
               data: baseData,
               dataType: "json",
               success: function(result) {

                   console.log(result);
                   if(result.status){
                       notify("success","info", "you done it");
                       location.reload();
                   } else{
                       notify("error","info", "there is an error");
                       location.reload();
                   }
               }
           });
       }

       $('#cancel_error_score_form').on('click', function() {
           window.close();
       });

       $('#submit_candidate_error_socre').on('click', function() {

           var baseUrl = "{{route('admin.exam.add_new_correction_score',$exam_id)}}";
           var corrector_name = $('#corrector_error_score').val();

           if(store_array_correction.length > 0) {

               var baseData = {
                   data: store_array_correction,
                   serializ_data: serializeData
               }

               console.log(store_array_correction.length);
               ajaxRequest('POST', baseUrl+'?corrector_name='+corrector_name,baseData );
           }

       });

       function calculateSum(obj) {

           var total_question = JSON.parse('{{$totalQuestion}}');

           var score_c = isNaN(parseInt($(obj).closest('.new_correction').find('.score_c').val()))?0:parseInt($(obj).closest('.new_correction').find('.score_c').val());
           var score_w = isNaN(parseInt($(obj).closest('.new_correction').find('.score_w').val()))?0:parseInt($(obj).closest('.new_correction').find('.score_w').val());
           var score_na = isNaN(parseInt($(obj).closest('.new_correction').find('.score_na').val()))?0:parseInt($(obj).closest('.new_correction').find('.score_na').val());

           $(obj).css("background-color", "#FEFFB0")
           var sum =score_c+score_na+score_w;
           if(sum == total_question) {
               $(obj).closest('.new_correction').find('.score_total').val(sum).css("color", "");

           } else {
               $(obj).closest('.new_correction').find('.score_total').val(sum).css("color", "red");
           }

       }

       @if($errorCandidateScores)



           $('#btn_candidate_error_socre').on('click', function() {

           var course_id = JSON.parse('<?php echo json_encode($courseId)?>');
           var course_name = JSON.parse('<?php echo json_encode($courseName)?>');
           var baseUrl = '{!! route('admin.exam.print_candidate_error_socre', $exam_id) !!}';

           var array_candidate_ids = $(".candidate_has_score_error_id").map(function(){return $(this).val();}).get();
           var array_room_code_id = JSON.parse('<?php echo json_encode($arrayRoomCode)?>');
           var order = $(".candidate_order_in_room").map(function(){return $(this).val();}).get();

//           window.location.href = baseUrl+'?course_id=' + course_id +'&candidate_array_ids='+array_candidate_ids+'&room_code_ids='+ array_room_code_id +'&order_in_room=' +order;

           window_request_room = PopupCenterDual(baseUrl+'?course_id=' + course_id +'&room_code_ids='+ array_room_code_id +'&order_in_room=' +order + '&course_name=' + course_name,'Candidates Result List','1000','1200');


//           for(var index =0; index< candidate_score_error.length; index++) {
//
//               var tmp = candidate_score_error[index].candidateProperties.room_code;
//               for(var key= index+1; key< candidate_score_error.length; key++) {
//                   if(tmp == candidate_score_error[key].candidateProperties.room_code) {
//                       candidate_number_in_room.push(candidate_score_error[key].scoreErrors.canididate_number_in_room);
//                       unique_room_code.push(tmp);
//                   }
//               }
//           }
           })
       @endif



   </script>
@stop