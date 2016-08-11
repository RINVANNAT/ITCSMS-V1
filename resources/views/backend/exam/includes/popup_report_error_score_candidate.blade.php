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
    </style>
    <div class="box box-success">
        <div class="box-header with-border">
            <h3  style="text-align: center"> Error Inputed Score </h3>
        </div>
        <!-- /.box-header -->
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


                        <?php $k=0; $length = []; $p=0;?>
                        @foreach($errorCandidateScores as $errorScoreProperties)
                            <?php $k++; $p=0;  ?>

                            <div class="box-body with-border" id="correction_score_<?php echo $k;?>">

                                <div class="col-sm-12" style="background-color: #F5EAEC; padding-top: 5px;">

                                    @foreach($errorScoreProperties->scoreErrors as $errorScore)
                                        <?php  $p++; ?>

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


                                        <?php array_push($length,$p)?>
                                        <div class="col-sm-1">
                                            <button class="btn-xs btn_add_new_correction_score "  onclick="AddNewCorrection(<?php echo $k;?>)"> Add </button>
                                        </div>
                                </div>
                            </div>


                            <div id="new_correction_score_<?php echo $k;?>" class="col-sm-12 " style="background-color: #F5EAEC">

                                <div class="col-sm-2 enlarge-number">
                                    <label for="order">{{$errorScoreProperties->candidateProperties->room_code}}</label>
                                    {!! Form::hidden('candidate_score_id_'."[candidate_id]", $errorScoreProperties->candidateProperties->candidate_id, ['class' => 'form-control', 'id'=>'candidate_id_'.$k ]) !!}

                                </div>

                                <div class="col-sm-1 enlarge-number" >
                                    <label for="roomCode">{{$errorScore->candidate_number_in_room}}</label>
                                    {!! Form::hidden('candidate_score_id_'."[order]", $errorScore->candidate_number_in_room, ['class' => 'form-control', 'id'=>'ordering_id_'.$k]) !!}
                                </div>
                                <div class="col-sm-2">
                                    {!! Form::text('candidate_score_id_'."[score_c]", null, ['class' => 'form-control number_only enlarge-number validate_score_'.$k, 'id'=>'correct_'.$k]) !!}

                                </div>
                                <div class="col-sm-2">
                                    {!! Form::text('candidate_score_id_'."[score_w]", null, ['class' => 'form-control number_only enlarge-number validate_score_'.$k, 'id'=>'wrong_'.$k]) !!}

                                </div>
                                <div class="col-sm-2">
                                    {!! Form::text('candidate_score_id_'."[score_na]", null, ['class' => 'form-control number_only enlarge-number validate_score_'.$k, 'id'=>'na_'.$k]) !!}
                                    {!! Form::hidden('candidate_score_id_'."[course_id]", $errorScore->course_id, ['class' => 'form-control', 'id'=>'course_id_'.$k]) !!}
                                </div>

                                <div class="col-sm-1">
                                    {!! Form::text('candidate_score_id_'."[score_total]", null, ['class' => 'form-control enlarge-number total_score_'.$k, 'disabled', 'id'=>'total_score_'.$k]) !!}
                                </div>

                                <div class="col-sm-1 enlarge-number ">
                                        <p style="border: 2px solid orangered; text-align: center" > {{$errorScore->sequence + 1}}</p>
                                        {!! Form::hidden('candidate_score_id_'."[sequence]", $errorScore->sequence + 1, ['class' => 'form-control', 'id'=>'new_sequence_'.$k ]) !!}
                                </div>

                                <div class="col-sm-1 " style="margin-top: 5px" >

                                        <button class="btn btn-info btn-xs " id="save_correction_<?php echo $k;?>"> <i class="fa fa-save"> </i> </button>

                                        <button class="btn btn-danger btn-xs " id="cancel_correction_<?php echo $k;?>"><i class="fa fa-times"> </i> </button>


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
                <input type="button" id="btn_save_candidate_score" class="btn btn-danger btn-xs" value="Print Report" />
            </div>
            <div class="clearfix"></div>
        </div><!-- /.box-body -->
    </div><!--box-->
@stop

@section('after-scripts-end')
   {{--here my script --}}

   <script>
       $(document).ready(function () {

           $(".number_only").keypress(function (e) {
               if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                   return false;
               }
           });

       });

       var length = JSON.parse('<?php echo $k ?>');
       for(var i =1; i <=length; i++) {
           $('#new_correction_score_'+i).hide();
       }

       function AddNewCorrection (key,e) {
           var click=0;
           $('#correction_score_'+key).append( $('#new_correction_score_'+key).delay(200).show(0));
           $('#cancel_correction_'+key).on('click', function() {
               $('#new_correction_score_'+key).delay(200).hide(0);
           });

           $('#save_correction_'+key).on('click', function(e) {

               console.log(key);
               var baseData = {
                   score_c: $('#correct_'+key).val(),
                   score_w: $('#wrong_'+key).val(),
                   score_na: $('#na_'+key).val(),
                   sequence: $('#new_sequence_'+key).val(),
                   course_id: $('#course_id_'+key).val(),
                   order: $('#ordering_id_'+key).val(),
                   candidate_id: $("#candidate_id_"+key).val()
               }
               var baseUrl = "{{route('admin.exam.add_new_correction_score',$exam_id)}}";

               if(baseData.score_c + baseData.score_w + baseData.score_na == 0) {
                   notify("error","info", "Please Check Your Record Was Not Saved!");
               } else {
                   if( click==0) {
                       ajaxRequest('POST', baseUrl,baseData );
                       click++;
                   }

               }
           });

           $('.validate_score_'+key).on('keydown keyup', function() {
               console.log(key);
               calculateSum(key);
           });



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
                   } else{
                       notify("error","info", "there is an error");
                   }
                   location.reload();

               }
           });
       }

       $('#cancel_error_score_form').on('click', function() {
           window.close();
       });

       function calculateSum(k) {
                var total_question = JSON.parse('{{$totalQuestion}}');
               var sum =0;
               $(".validate_score_"+k).each(function() {
                   if (!isNaN(this.value) && this.value.length != 0) {
                       sum += parseInt(this.value);
                       console.log(sum);
                       $(this).css("background-color", "#FEFFB0");
                       if(sum == total_question) {
                           $("input#total_score_"+k).val(sum).css("color", "");

                       } else {
                           $("input#total_score_"+k).val(sum).css("color", "red");
                       }
                   }
                   else if (this.value.length != 0){
                       $(this).css("background-color", "red");
                   }
               });
       }

        var scoreLength = JSON.parse('<?php echo $p?>');
       var arrayIds = JSON.parse('<?php echo json_encode($length);?>');
//        console.log(arrayIds.length);

       for(var i =1; i <=arrayIds.length; i++) {
           var scoreCorrect = $(".score_c_"+i).map(function(){return $(this).text();}).get();
           var scoreWrong = $(".score_w_"+i).map(function(){return $(this).text();}).get();
           var scoreNoan = $(".score_na_"+i).map(function(){return $(this).text();}).get();

       }


       function compareScore(array, i) {
//           console.log(array);
           var leng = array.length;
           console.log(leng);
           var tmp = array[0];
           for(var k=0; k < leng; k++) {

                if(parseInt(tmp) == parseInt(array[k])) {
                    console.log(array[k]);
//                    console.log(object+k+1);
//                    console.log(parseInt(tmp)+' == '+parseInt(array[k]))
                    $("#score_c_"+i+'_'+k).addClass('equal');
                } else {
                    $(object+k+1).addClass('error');
                }

           }
       }

   </script>
@stop