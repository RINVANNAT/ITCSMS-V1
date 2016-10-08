@extends ('backend.layouts.popup_master')

@section ('title', trans('labels.backend.exams.title') . ' | ' . 'Generate Candidates Result')

@section('content')

    <div class="box box-success">

        <style>
            .enlarge-text{
                font-size: 36px;
            }
            .enlarge-number{
                font-size: 22px;
            }


            .modal {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0,0,0,0.8);
                z-index: 1;
            }
        </style>
        <div class="box-header with-border">
            <h3 class="box-title">Get Result Score</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">

            {!! Form::open(['route' => ['admin.exam.candidate_calculatioin_exam_score',$exam_id], 'class' => 'form-horizontal calculation_score', 'role' => 'form', 'method' => 'post']) !!}

                @foreach($courseIds as $courseId)
                    <div class="col-sm-12" style="margin-bottom: 5px">
                        <div class="col-sm-4 no-padding enlarge-number">
                            <label for="course_name"> {{$courseId->course_name}}: Correct</label>
                        </div>
                        <div class="col-sm-1 no-padding ">
                            {!! Form::hidden("course[".$courseId->course_name."][id]", $courseId->course_id) !!}
                            {!! Form::text("course[".$courseId->course_name."][correct]", 4, ['class' => 'form-control enlarge-number']) !!}
                        </div>

                        <div class="col-sm-2 enlarge-number">
                            <label for="wrong"> Wrong </label>
                        </div>
                        <div class="col-sm-1 no-padding">
                            {!! Form::text("course[".$courseId->course_name."][wrong]", 1, ['class' => 'form-control enlarge-number']) !!}
                        </div>


                        <div class="col-sm-1 no-padding enlarge-number">
                            <label for="coefficient" style="margin-left: 5px">Coe: </label>
                        </div>
                        <div class="col-sm-1 no-padding">
                            {!! Form::text("course[".$courseId->course_name."][coe]", null, ['class' => 'form-control enlarge-number']) !!}
                        </div>

                    </div>

                @endforeach


                <div class="col-sm-12" style="margin-bottom: 5px">

                    <div class="col-sm-4 no-padding enlarge-number">
                        <label for="total_passed"> Student Passed: </label>
                    </div>
                    <div class="col-sm-4 no-padding ">
                        {!! Form::text('total_pass', null, ['class' => 'form-control enlarge-number', 'id'=> 'total_pass']) !!}
                    </div>
                </div>

                <div class="col-sm-12" style="margin-bottom: 5px">

                    <div class="col-sm-4 no-padding enlarge-number">
                        <label for="total_reserved" > Student Reserved: </label>
                    </div>
                    <div class="col-sm-4 no-padding ">
                        {!! Form::text('total_reserve', null, ['class' => 'form-control enlarge-number', 'id'=> 'total_reserve']) !!}
                    </div>

                </div>

            {!! Form::close() !!}

        </div>


        <div class="modal" style="display: none; text-align: center">
            <img src="{{url('img/exam/loader.gif')}}" alt="loading" style="margin-top: 200px">
        </div>

    </div>

    <div class="box box-success">
        <div class="box-body">
            <div class="pull-left">
                <a href="#" id="btn_cancel_candidate_result" class="btn btn-default btn-xs">{{ trans('buttons.general.cancel') }}</a>
            </div>

            <div class="pull-right">
                <input type="button" id="btn_get_factor_ok" class="btn btn-danger btn-xs" value="OK" />
            </div>
            <div class="clearfix"></div>
        </div><!-- /.box-body -->
    </div><!--box-->
@stop

@section('after-scripts-end')

    {{--myscript--}}

    <script>

        function ajaxRequest(method, baseUrl, baseData){

            $.ajax({
                type: method,
                url: baseUrl,
                data: baseData,
                success: function(result) {
                    console.log(result);

                    if(result.status) {
//
                        $('.modal').hide();
                        {{--var Url = '{!! route('candidate_result_lists') !!}';--}}
//                        window_request_room = PopupCenterDual(Url+'?exam_id='+result.exam_id,'Candidates Result List','1000','1200');

                        $('#btn_result_score_candidate').hide();
                        notify('success', 'Result Success!');
                        window.close();

                    } else {
                        $('.modal').hide();
                        notify("error","info", "There are not enough candidates!!!");
                    }

                }
            });
        }


        $('#btn_get_factor_ok').on('click', function(e) {
            e.preventDefault();
            var check =0;
            var Data = $("form.calculation_score" ).serializeArray();
            for(var i =1; i < Data.length; i++){
                if(Data[i].value == ''){
                    check++;
                }
            }
            if(check == 0) {

                $('.modal').show();
                ajaxRequest('POST', $( "form.calculation_score").attr('action'), $( "form.calculation_score" ).serialize() );


            } else{
                notify("error","info", "Please Input All Value");
            }

        })

        $('#btn_cancel_candidate_result').on('click', function() {
            window.close();
        })


    </script>


@stop