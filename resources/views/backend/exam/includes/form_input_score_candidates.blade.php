@extends ('backend.layouts.popup_master')

@section ('title', trans('labels.backend.exams.title') . ' | ' . trans('labels.backend.exams.course.add_course'))

@section('content')

    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">Input Score </h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
           {{--here what i need to write--}}
            <div class="container">
                <div class="col-sm-12 no-padding" style="margin-top: -15px">
                    <div class="col-sm-4">
                        <div class="col-sm-12">
                            <h4> ROOM CODE  </h4>
                        </div>
                        <div class="col-sm-12" style="margin-top: -5px">
                            <h5><span class="text-info">{{ $roomCode }}</span></h5>
                        </div>

                    </div>
                    <div class="col-sm-4" style="text-align: center">
                        <div class="col-sm-12">
                            <h4>SCORING SHEET</h4>
                        </div>
                        <div class="col-sm-12" style="margin-top: -5px">
                            <h5> <span class="text-info">{{$subject}}</span></h5>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="col-sm-12" style="text-align: center">
                            <h4>CORRECTION </h4>
                        </div>

                        <div class="col-sm-12" style="margin-top: -10px; text-align: center; ">
                            <div class="col-sm-5">

                            </div>
                            <div class="col-sm-2" style="border: 2px solid darkgreen; margin-top: 5px">
                                <h5> {{ $numberAttemp }}</h5>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-sm-12 no-padding" style="margin-top: 5px">

                    <table class="table">
                        <thead>
                        <tr>
                            <th> Candidate Number </th>
                            <th> Correct Answer</th>
                            <th> Wrong Answer </th>
                            <th> No Answer </th>
                            <th> Total Question </th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i = 0 ;?>
                        @foreach ($candidates as $candidate)
                            <?php $i++; ?>
                            <tr>
                                <td style="text-align: center" class="candidate_score_id" candidate_id ="{{$candidate->candidate_id}}"><?php echo $i;?></td>
                                <td><input type="text" id="correct_<?php echo $i;?>" class="correct_ans_score validate_<?php echo $i;?>" style="width: 40%;"></td>
                                <td><input type="text" id="wrong_<?php echo $i;?>" class="wrong_ans_score validate_<?php echo $i;?>" style="width: 40%;"></td>
                                <td><input type="text" id="no_<?php echo $i;?>" class="no_ans_score validate_<?php echo $i;?>" style="width: 40%;"></td>
                                <td><input type="text" id="total_<?php echo $i;?>" class="total_ans_score" style="width: 40%;" placeholder="{{ $candidate->total_question }}"></td>

                            </tr>
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
                <a href="#" id="btn_cancel_form" class="btn btn-default btn-xs">{{ trans('buttons.general.cancel') }}</a>
            </div>

            <div class="pull-right">
                <input type="button" id="btn_save_candidate_score" class="btn btn-danger btn-xs" value="{{ trans('buttons.general.save') }}" />
            </div>
            <div class="clearfix"></div>
        </div><!-- /.box-body -->
    </div><!--box-->
@stop

@section('after-scripts-end')
    <script>

            $("#btn_cancel_form").click(function () {
//                opener.update_ui_course();
                window.close();
            });

            function ajaxRequest(method, baseUrl, baseData){
                console.log('hello');
                $.ajax({
                    type: method,
                    url: baseUrl,
                    data: baseData,
                    dataType: "json",
                    success: function(result) {
                        console.log(result);
                        notify("success","info", "You have done!");
                    }
                });
            }

            $("#btn_save_candidate_score").click(function() {

                var candidate_ids       =  $('.candidate_score_id').map(function(){return $(this).attr('candidate_id');}).get();
                var correct_ans_score   =  $('.correct_ans_score').map(function(){return $(this).val();}).get();
                var wrong_ans_score     =  $('.wrong_ans_score').map(function(){return $(this).val();}).get();
                var no_ans_score        =  $('.no_ans_score').map(function(){return $(this).val();}).get();
                var total_ans_score     =  $('.total_ans_score').map(function(){return $(this).val();}).get();

                baseData = {
                    candidate_ids : candidate_ids,
                    correct_ans_score: correct_ans_score,
                    wrong_ans_score: wrong_ans_score,
                    no_ans_score: no_ans_score,
                    total_ans_score: total_ans_score,
                    subject_name: '{{$subject}}',
                    subject_id: '{{$subjectId}}',
                    room_id:'{{$roomId}}',
                    room_code: '{{$roomCode}}',
                    number_attemp: "{{$numberAttemp}}"


                };
                baseUrl = "{{route('admin.exam.insert_exam_score_candidate',$exam_id)}}";

                ajaxRequest('POST', baseUrl, baseData );
            });

            $(document).ready(function () {
                //called when key is pressed in textbox

                $(".correct_ans_score").keypress(function (e) {


                    if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
//                        $("#errmsg").html("Digits Only").show().fadeOut("slow");
                        return false;
                    }
                });

                $(".wrong_ans_score").keypress(function (e) {

                    if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                        return false;
                    }
                });

                $(".no_ans_score").keypress(function (e) {

                    if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                        return false;
                    }
                });


                var length = JSON.parse('<?php echo $i ?>');
                for(var k=1; k<=length; k++) {
                    $('.validate_'+k).on('keydown keyup', function() {
                        calculateSum(length);
                    })
                }
                calculateSum(length);


            });


            function calculateSum(length) {

                for(var i=1; i<=length; i++) {
                    var sum = 0;
                    $(".validate_"+i).each(function() {
                        //add only if the value is number
                        if (!isNaN(this.value) && this.value.length != 0) {
                            sum += parseInt(this.value);
                            $(this).css("background-color", "#FEFFB0");
                            if(sum == 30) {
//                                parseInt(question)
                                $("input#total_"+i).val(sum).css("color", "");

                            } else {
                                $("input#total_"+i).val(sum).css("color", "red");
                            }
                        }
                        else if (this.value.length != 0){
                            $(this).css("background-color", "red");
                        }
                    });
                }

            }

    </script>
@stop