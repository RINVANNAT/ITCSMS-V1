@extends ('backend.layouts.popup_master')

@section ('title', trans('labels.backend.exams.title') . ' | ' . trans('labels.backend.exams.course.add_course'))

@section('content')

    <style>
        .error{
            color: #9c0033;
        }
        .equal{
            color: darkgreen;
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
                                <label for="Wrong Answer"> Correct Answer </label>
                            </div>
                            <div class="col-sm-2  no-padding ">
                                <label for="Wrong Answer"> Wrong Answer </label>
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


                        <?php $k=0; $length = [];?>
                        @foreach($errorCandidateScores as $errorScoreProperties)
                            <?php $k++; $p=0;  ?>

                            <div class="box-body with-border" id="correction_score_<?php echo $k;?>">

                                <div class="col-sm-12">

                                    @foreach($errorScoreProperties->scoreErrors as $errorScore)
                                        <?php  $p++; ?>

                                            <div class="col-sm-2">
                                                <p > {{$errorScoreProperties->candidateProperties->room_code}} </p>
                                            </div>
                                            <div class="col-sm-1">
                                                <p >{{$errorScore->candidate_number_in_room}}</p>
                                            </div>
                                            <div class="col-sm-2">
                                                <p class="score_c_<?php echo $k;?>" id="score_c_<?php echo $k.'_'.$p;?>">{{$errorScore->score_c}}</p>
                                            </div>
                                            <div class="col-sm-2">
                                                <p class="score_w_<?php echo $k;?>" id="score_w_<?php echo $k.'_'.$p;?>" >{{$errorScore->score_w}}</p>
                                            </div>
                                            <div class="col-sm-2">
                                                <p class="score_na_<?php echo $k;?>" id="score_na_<?php echo $k.'_'.$p;?>" >{{$errorScore->score_na}}</p>
                                            </div>
                                            <div class="col-sm-1">
                                                <p class="score_total_<?php echo $k;?>" id="score_total_<?php echo $k.'_'.$p;?>">{{$errorScore->score_c + $errorScore->score_w + $errorScore->score_na}}</p>
                                            </div>
                                            <div class="col-sm-1">
                                                <p class="socre_properties_<?php echo $k;?>" style="border: 2px solid orangered; text-align: center"> {{$errorScore->sequence}}</p>
                                            </div>

                                    @endforeach


                                        <?php array_push($length,$p)?>
                                        <div class="col-sm-1">
                                            <button class="btn-xs btn_add_new_correction_score"  onclick="AddNewCorrection(<?php echo $k;?>)"> Add </button>
                                        </div>
                                </div>
                            </div>

                            <div id="new_correction_score_<?php echo $k;?>" class="col-sm-12">

                                <div class="col-sm-2">
                                    <label for="order">{{$errorScoreProperties->candidateProperties->room_code}}</label>
                                    {!! Form::hidden('candidate_score_id_'."[candidate_id]", $errorScoreProperties->candidateProperties->candidate_id, ['class' => 'form-control', 'id'=>'candidate_id_'.$k ]) !!}

                                </div>

                                <div class="col-sm-1" >
                                    <label for="roomCode">{{$errorScore->candidate_number_in_room}}</label>
                                    {!! Form::hidden('candidate_score_id_'."[order]", $errorScore->candidate_number_in_room, ['class' => 'form-control', 'id'=>'ordering_id_'.$k]) !!}
                                </div>
                                <div class="col-sm-2">
                                    {!! Form::text('candidate_score_id_'."[score_c]", null, ['class' => 'form-control', 'id'=>'correct_'.$k]) !!}

                                </div>
                                <div class="col-sm-2">
                                    {!! Form::text('candidate_score_id_'."[score_w]", null, ['class' => 'form-control', 'id'=>'wrong_'.$k]) !!}

                                </div>
                                <div class="col-sm-2">
                                    {!! Form::text('candidate_score_id_'."[score_na]", null, ['class' => 'form-control', 'id'=>'na_'.$k]) !!}
                                    {!! Form::hidden('candidate_score_id_'."[course_id]", $errorScore->course_id, ['class' => 'form-control', 'id'=>'course_id_'.$k]) !!}
                                </div>

                                <div class="col-sm-1">
                                    {!! Form::text('candidate_score_id_'."[score_total]", null, ['class' => 'form-control', 'id'=>'toal_'.$k]) !!}
                                </div>

                                <div class="col-sm-1 ">
                                        <p style="border: 2px solid orangered; text-align: center" > {{$errorScore->sequence + 1}}</p>
                                        {!! Form::hidden('candidate_score_id_'."[sequence]", $errorScore->sequence + 1, ['class' => 'form-control', 'id'=>'new_sequence_'.$k ]) !!}
                                </div>

                                <div class="col-sm-1 no-padding" >
                                    <div class="col-sm-6">
                                        <button class="btn btn-primary btn-xs" id="save_correction_<?php echo $k;?>"> save </button>

                                    </div>
                                    <div class="col-sm-6">
                                        <button class="btn btn-primary btn-xs" id="cancel_correction_<?php echo $k;?>"> cancel </button>
                                    </div>


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

       }

       function ajaxRequest(method, baseUrl, baseData){
           $.ajax({
               type: method,
               url: baseUrl,
               data: baseData,
               dataType: "json",
               success: function(result) {

                   location.reload();
                   notify("error","info", "you done it");
               }
           });
       }

       $('#cancel_error_score_form').on('click', function() {
           window.close();
       });

        var scoreLength = JSON.parse('<?php echo $p?>');


       var arrayIds = JSON.parse('<?php echo json_encode($length);?>');
//        console.log(arrayIds.length);

       for(var i =1; i <=arrayIds.length; i++) {
           var scoreCorrect = $(".score_c_"+i).map(function(){return $(this).text();}).get();
           var scoreWrong = $(".score_w_"+i).map(function(){return $(this).text();}).get();
           var scoreNoan = $(".score_na_"+i).map(function(){return $(this).text();}).get();

                console.log(scoreCorrect);
//               compareScore(scoreCorrect,"#score_c_"+i+'_');
//               compareScore(scoreWrong,$("#score_w_"+i+'_'));
//               compareScore(scoreNoan,$("#score_na_"+i+'_'));


//           console.log(scoreCorrect);

       }

       var total = JSON.parse('{{$errorScore->total_question}}');

       function compareScore(array, object) {
//           console.log(array);
           var leng = array.length;
           console.log(leng);
           var tmp = array[0];
           for(var k=0; k < leng; k++) {

                if(parseInt(tmp) == parseInt(array[k])) {
                    console.log(array[k]);
//                    console.log(parseInt(tmp)+' == '+parseInt(array[k]))
                   $(object+k+1).addClass('equal');
                } else {
                    $(object+k+1).addClass('error');
                }

           }
       }

   </script>
@stop