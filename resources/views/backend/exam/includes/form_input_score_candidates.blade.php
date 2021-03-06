@extends ('backend.layouts.popup_master')

@section ('title', trans('labels.backend.exams.title') . ' | ' . trans('labels.backend.exams.score.form_score.form_input_score'))

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

            .enlarge-selection{
                margin-top: 15px;
                font-size: 40px;
                border-radius: 0;
                height: 45px;
                border:none;
                text-indent: 10px;
                color:#fff;
                background-color: #31708f;
            }
            #pre_room, #next_room {
                margin-top: 18px;
                background-color: #fff;
                color: #444;
                font-size: 20px;
                border-color: #fff;
                text-decoration: underline;
            }
            #pre_room:hover,#next_room:hover{
                background-color: #fff !important;
            }
            #selected_room {
                margin-top: -10px;
            }
            .highlight{
                background-color: #c7d4ea;
            }
        </style>
        <div class="box-header with-border">
            <h3 class="box-title "> <span class="enlarge-number">{{trans('labels.backend.exams.score.input_score')}}</span> </h3>
        </div>


        <!-- /.box-header -->
        <div class="box-body">
           {{--here what i need to write--}}
            <div class="container">
                <div class="col-sm-12 no-padding" style="margin-top: -15px">
                    <div class="col-sm-4">
                        <div class="col-sm-12" style="margin-left: 20px">
                            <h3> {{trans('labels.backend.exams.score.form_score.room_code')}}  </h3>
                        </div>


                       <div>
                           {{--here we need --}}

                           <div class="col-sm-12 text-center" style="padding-left: 0px;padding-right: 0px;margin-left: -50px">
                               @if($preRoom != null)
                               <div class="col-sm-3 text-info btn btn-default no-padding enlarge-number" id="pre_room" room_id="{{$preRoom['room_id']}}" room_code="{{ $preRoom['room_code'] }}">
                                   ❮ {{ $preRoom['room_code'] }}
                               </div>
                               @else
                               <div class="col-sm-3">
                               </div>
                               @endif

                               <div class="col-sm-5 text-info enlarge-number no-padding" id="selected_room" room_id="{{$roomId}}">
                                   {!! Form::select('room',$availableRooms, $roomId, array('class'=>'form-control enlarge-selection','id'=>'room_selection')) !!}
                               </div>
                               @if($nextRoom != null)
                               <div class="col-sm-3 text-info btn btn-default no-padding enlarge-number" id="next_room" room_id="{{$nextRoom['room_id']}}" room_code="{{ $nextRoom['room_code'] }}">
                                   {{ $nextRoom['room_code'] }} ❯
                               </div>
                               @endif
                               <div class="col-sm-1">

                               </div>

                           </div>
                       </div>

                    </div>
                    <div class="col-sm-4" style="text-align: center">
                        <div class="col-sm-12 ">
                            <h3>{{trans('labels.backend.exams.score.form_score.scoring_sheet')}}</h3>
                        </div>
                        <div class="col-sm-12" style="margin-top: -5px">
                            {!! Form::hidden('subject_id', $subjectId, ['class' => 'form-control', 'id'=>'course_id']) !!}
                            <h5> <span class="text-info enlarge-text">{{$subject}}</span></h5>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="col-sm-12" style="text-align: center">
                            <h3> {{trans('labels.backend.exams.score.form_score.correction')}} </h3>
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

                <div class="col-sm-12" style="margin-top: 15px">
                    <div class="col-sm-6 no-padding">

                    </div>
                    <div class="col-sm-6 no-padding">
                        <div class="col-sm-4 no-padding" style="margin-top: 5px">
                            <label for="corrector_name"> {{trans('labels.backend.exams.score.form_score.corrector_name')}} </label>
                        </div>

                        <div class="col-sm-8 no-padding">
                            {!! Form::text('corrector', null, ['class' => 'form-control', 'placeholder'=>'name corrector', 'id'=> 'corrector_name', 'required']) !!}
                        </div>


                    </div>

                </div>

                <div class="col-sm-12 no-padding " style="margin-top: 5px">

                    <table class="table">
                        <thead>
                        <tr>
                            <th> {{trans('labels.backend.exams.score.form_score.order')}} </th>
                            <th> {{trans('labels.backend.exams.score.form_score.correct')}} </th>
                            <th> {{trans('labels.backend.exams.score.form_score.wrong')}}  </th>
                            <th> {{trans('labels.backend.exams.score.form_score.no_answer')}} </th>
                            @if($candidates)
                                <th> Total: {{$candidates[0]->total_question}} </th>
                            @endif

                        </tr>
                        </thead>
                        <tbody>

                            @if($status)
                                <?php $i = 0 ;?>

                                {!! Form::open(['route' => ['admin.exam.insert_exam_score_candidate',$exam_id], 'class' => 'form-horizontal table_score', 'role' => 'form', 'method' => 'post']) !!}

                                {!! Form::hidden('sequence', $number_correction, ['class' => 'form-control ']) !!}
                                {!! Form::hidden('roomcode', $roomCode, ['class' => 'form-control ']) !!}
                                {!! Form::hidden('course_id', $subjectId, ['class' => 'form-control']) !!}
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

                                            {{--<td style="width: 20%">--}}
                                                {{--{!! Form::text('corrector[]', null, ['class' => 'form-control', 'placeholder'=>'name corrector']) !!}--}}
                                            {{--</td>--}}
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

@section('after-scripts-end')

@if($status)
    <script>
      var total_candidate = {{count($candidates)}};
      function ajaxRequest(method, baseUrl, baseData){

        $.ajax({
          type: method,
          url: baseUrl,
          data: baseData,
          dataType: "json",
          success: function(result) {
            if(result.status) {

              notify("success","info", "{{trans('labels.backend.exams.score.message.save_success')}}");
            } else {
              notify("error","info", "{{trans('labels.backend.exams.score.message.save_duplicate')}}");
            }

          }
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
              $("input#total_"+i).val(tmp);
              if( $("input#total_"+i).val() == total_question) {
                $(".validate_"+i).css("color", "");
                $("input#total_"+i).css("color", "");
              } else if( $("input#total_"+i).val() == 0) {
                $(".validate_"+i).css("color", "red");
              } else {
                $(".validate_"+i).css("color", "");
                $("input#total_"+i).val(tmp).css("color", "red");
              }
            }
          });

        }
      }
      $("#btn_cancel_form").click(function () {
//                opener.update_ui_course();
            window.close();
        });
      $("#btn_save_candidate_score").click(function() {

            var data = $( "form.table_score" ).serializeArray();

            console.log(data);
            var baseData = $( "form.table_score" ).serialize();
            var corrector_name = $('#corrector_name').val();
            var check =0;
            var status = 0;
            $.each(data, function(index, value) {
                if(value.name == 'score_id[]') {
                    check++;
                }
            });

           for(var i =1 ; i <= check; i++) {
               if( $("input#total_"+i).val() == 0) {
                   status++;
               }
           }

            if(corrector_name != '' ) {

                if(status > 0) {
                    swal({
                        title: "Confirm",
                        text: "{{trans('labels.backend.exams.score.message.zero_value')}}",
                        type: "info",
                        showCancelButton: true,
                        cancelButtonText: "{{ trans('labels.backend.exams.score.btn_cancel') }}",
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "{{ trans('labels.backend.exams.score.btn_ok') }}",
                        closeOnConfirm: true
                    }, function(confirmed) {
                        if (confirmed) {
                            ajaxRequest('POST', $( "form.table_score").attr('action')+'?corrector_name='+corrector_name, $( "form.table_score" ).serialize());
                        }
                    });
                } else {
                    ajaxRequest('POST', $( "form.table_score").attr('action')+'?corrector_name='+corrector_name, $( "form.table_score" ).serialize());
                }
            } else {
              $('#corrector_name').focus();
              notify("error","{{trans('labels.backend.exams.score.message.corrector_missing')}}","Error");
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
                var column_number = $(this).parent().index();
                var index = $('.inputs_score').index(this);
                if(index === ((total_candidate*3)-(4-column_number))){
                  index = column_number;

                  if(index === 3) return;
                } else {
                  index = index + 3;
                }
                $('.inputs_score').eq(index).focus().select();
            }
      });
//      $('.inputs_score').on('focus',function(e){
//        console.log("CELL: "+ $('.inputs_score').index(this));
//        console.log("TD: "+ $(this).parent().index());
//        console.log("Total: "+total_candidate);
//      });
      // calculation input value on each row
      var length = JSON.parse('<?php echo $i ?>');
      calculateSum(length);
      for(var k=1; k<=length; k++) {
        $('.validate_'+k).on('keyup', function() {
          calculateSum(length);
        });
      }
    </script>
@endif
    <script>
        var baseUrl = '{!! route('admin.exam.request_input_score_form', $exam_id) !!}';
        var course_id = $('#course_id').val();
        var course_name = '{{$subject}}';
        var number_correction = JSON.parse('{{$number_correction}}');

        $('#next_room').on('click', function(){
            var room_id =  $(this).attr('room_id');
            var room_code = $(this).attr('room_code');
            window.location.href = baseUrl+'?room_id='+ room_id + '&room_code=' + room_code + '&entrance_course_id=' + course_id + '&course_name=' + course_name + '&number_correction=' + number_correction;
        });

        $('#pre_room').on('click', function(){
            var room_id =  $(this).attr('room_id');
            var room_code = $(this).attr('room_code');
            window.location.href = baseUrl+'?room_id='+ room_id + '&room_code=' + room_code + '&entrance_course_id=' + course_id + '&course_name=' + course_name + '&number_correction=' + number_correction;
        });

        $('#room_selection').on('change', function() {
            var room_id = $(this).val();
            var room_code = $('#room_selection :selected' ).text();
            console.log(room_id + '--' + room_code);
            window.location.href = baseUrl+'?room_id='+ room_id + '&room_code=' + room_code + '&entrance_course_id=' + course_id + '&course_name=' + course_name +'&number_correction=' + number_correction;
        });

        // Highlight selected row
        $("tr > td > input").focus(function(e){
          $(this).parent().parent().addClass('highlight');
        }).blur(function(e){
          $(this).parent().parent().removeClass('highlight');
        });
    </script>

@stop

