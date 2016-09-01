@extends ('backend.layouts.popup_master')

@section ('title', trans('labels.backend.exams.title') . ' | ' . 'Form Input Candidates Scores')

@section('content')

    <div class="box box-success">
        <style>
            .enlarge-text{
                font-size: 36px;
            }
            .enlarge-number{
                font-size: 28px;
            }
            .defaul_numer{
                color: darkred;
            }
        </style>
        <div class="box-header with-border">
            <h3 class="box-title "> <span class="enlarge-number">Input Score</span> </h3>
        </div>


        <!-- /.box-header -->
        <div class="box-body">
           {{--here what i need to write--}}
            <div class="container">
                <div class="col-sm-12 no-padding" style="margin-top: -15px">
                    <div class="col-sm-4">
                        <div class="col-sm-12 ">
                            <h3> ROOM CODE  </h3>
                        </div>
                        <div class="col-sm-12  " style="margin-top: -5px">
                            <h5><span class="text-info enlarge-text">{{ $roomCode }}</span></h5>
                        </div>

                    </div>
                    <div class="col-sm-4" style="text-align: center">
                        <div class="col-sm-12 ">
                            <h3>SCORING SHEET</h3>
                        </div>
                        <div class="col-sm-12" style="margin-top: -5px">
                            <h5> <span class="text-info enlarge-text">{{$subject}}</span></h5>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="col-sm-12" style="text-align: center">
                            <h3>CORRECTION </h3>
                        </div>

                        <div class="col-sm-12" style="margin-top: -10px; text-align: center; ">
                            <div class="col-sm-5">

                            </div>
                            <div class="col-sm-2 " style="border: 2px solid darkgreen; margin-top: 5px">
                                 <span class="text-info enlarge-text">  {{ $number_correction }}</span>
                            </div>

                        </div>
                    </div>
                </div>

                {{--<div class="col-sm-12"> <p style="border-bottom: 2px solid darkgreen"></p> </div>--}}
                {{--<div class="col-sm-12 pull-right no-padding">--}}
                    {{--<div class="col-sm-4">--}}

                    {{--</div>--}}
                    {{--<div class="col-sm-4">--}}
                        {{--<h4 class="text-info">Corrector Name</h4>--}}
                    {{--</div>--}}
                    {{--<div class="col-sm-4 pull-left">--}}
                        {{--<h4> RIN VANNAT</h4>--}}
                    {{--</div>--}}

                {{--</div>--}}

                <div class="col-sm-12 no-padding " style="margin-top: 5px">

                    <table class="table">
                        <thead>
                        <tr>
                            <th> Order </th>
                            <th> Correct </th>
                            <th> Wrong  </th>
                            <th> No Answer </th>
                            <th> Total  </th>
                        </tr>
                        </thead>
                        <tbody>

                            @if($status)
                                <?php $i = 0 ;?>

                                {!! Form::open(['route' => ['admin.exam.insert_exam_score_candidate',$exam_id], 'class' => 'form-horizontal table_score', 'role' => 'form', 'method' => 'post']) !!}

                                {!! Form::hidden('sequence', $number_correction, ['class' => 'form-control ']) !!}
                                @if($candidates)
                                    @foreach ($candidates as $candidate)
                                        <?php $i++; ?>
                                        <tr class="enlarge-number">
                                            <td style="text-align: center" class="candidate_score_id">
                                                {!! Form::hidden('score_id[]', $candidate->candidate_score_id, ['class' => 'form-control']) !!}
                                                {{--<input type="hidden" name="sequence" value="{{$number_correction}}">--}}

                                                {{--<input type="hidden" name="candidate_id" value="{{$candidate->candidate_id}}">--}}

                                                {!! Form::hidden('candidate_id[]', $candidate->candidate_id, ['class' => 'form-control']) !!}

                                                {{--<input type="hidden" name="course_id" value="{{$subjectId}}">--}}

                                                {!! Form::hidden('course_id[]', $subjectId, ['class' => 'form-control']) !!}

                                                <?php echo $i;?>
                                            </td>

                                            <td>
                                                {!! Form::text('score_c[]', $candidate->score_c==null?0:$candidate->score_c, ['class' => 'form-control inputs_score enlarge-number number_only validate_score input_score_c validate_'.$i,'id'=>'correct_'.$i]) !!}
                                            </td>

                                            <td>
                                                {!! Form::text('score_w[]', $candidate->score_w==null?0:$candidate->score_w, ['class' => 'form-control  inputs_score enlarge-number number_only validate_score input_score_w validate_'.$i,'id'=>'wrong_ans_'.$i]) !!}
                                            </td>

                                            <td>
                                                {!! Form::text('score_na[]', $candidate->score_na==null?0:$candidate->score_na, ['class' => 'form-control inputs_score enlarge-number number_only validate_score input_score_na validate_'.$i,'id'=>'no_'.$i]) !!}
                                            </td>

                                            <td>
                                                {!! Form::text('score_total[]', null, ['class' => 'form-control enlarge-number number_only validate_'.$i,'id'=>'total_'.$i, 'disabled', 'placeholder'=>$candidate->total_question]) !!}
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif

                            {!! Form::close() !!}
                            @else
                                <div class="col-sm-12">
                                    <div class="alert-info">
                                        <?php $i = 0 ;?>
                                        <h3>There are no candidate in this room</h3>
                                    </div>
                                </div>
                            @endif
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

@if($status)
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
                        if(result.status) {
                            notify("success","info", "your record have been save!");
                            setTimeout(function(){
                                window.close();
                            },3000);
                        } else {
                            notify("error","info", "Please Check Your Record Was Not Saved!");
                        }

                    }
                });
            }

            $("#btn_save_candidate_score").click(function() {

                var data = $( "form.table_score" ).serializeArray();
                var check =0;
                var status = 0;
                $.each(data, function(index, value) {
                    if(value.name == 'score_id[]') {
                        check++;
                    }
                });

               for(var i =1 ; i <= check; i++) {
                   if( $("input#total_"+i).val() == 0) {
                       console.log($("input#total_"+i).val());
                       status++;
                   }
               }
                if(status > 0) {

                    swal({
                        title: "Confirm",
                        text: "You have inputted 0 Value!!!",
                        type: "info",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Yes",
                        closeOnConfirm: true
                    }, function(confirmed) {
                        if (confirmed) {
                            ajaxRequest('POST', $( "form.table_score").attr('action'), $( "form.table_score" ).serialize());
                        }
                    });
                } else {
                    ajaxRequest('POST', $( "form.table_score").attr('action'), $( "form.table_score" ).serialize());
                }

            });

            // to disable of string inputted
            $(".number_only").keypress(function (e) {
                if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                    return false;
                }
            });

            // when typing enter key to focus on the under input field
            $('.inputs_score').keydown(function (e) {
                if (e.which === 13) {
                    var index = $('.inputs_score').index(this) + 3;
                    $('.inputs_score').eq(index).focus();
                }
            });

            // calculation input value on each row

            var length = JSON.parse('<?php echo $i ?>');
            calculateSum(length);

            for(var k=1; k<=length; k++) {

                $('.validate_'+k).on('keyup', function() {

                    calculateSum(length);

                });
            }
            function calculateSum(length) {

                var total_question = JSON.parse('{{$candidate->total_question}}');

                for(var i=1; i<= length; i++) {
                    var sum =0;
                    $(".validate_"+i).each(function() {
                        if (!isNaN(this.value) && this.value.length != 0) {

                            var tmp = sum;
                            sum += parseInt(this.value);
                            $(this).css("background-color", "#FEFFB0");
                            if(sum == total_question) {

                                $("input#total_"+i).val(sum).css("color", "");


                            } else if(sum == 0) {
                                $(".validate_"+i).css("color", "red");
                            } else {
                                $(".validate_"+i).css("color", "");
                                $("input#total_"+i).val(tmp).css("color", "red");
                            }
                        }
                    });

                }
            }

        </script>
    @stop

@endif
