@extends ('backend.layouts.popup_master')

@section ('title', trans('labels.backend.exams.title') . ' | ' . 'Generate Candidates Result')

@section('content')

    <div class="box box-success">

        <style>
            .enlarge-text{
                font-size: 36px;
            }
            .enlarge-number{
                font-size: 28px;
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
                        <div class="col-sm-2">

                        </div>
                        <div class="col-sm-4 no-padding enlarge-number">
                            <label for="course_name"> {{$courseId->course_name}} </label>
                        </div>
                        <div class="col-sm-3 no-padding">
                            {!! Form::text('course_factor'."[$courseId->course_id]", null, ['class' => 'form-control enlarge-number']) !!}
                            {{--{!! Form::hidden('course_factor_'."[course_id]",$courseId->course_id, ['class' => 'form-control']) !!}--}}
                        </div>
                        <div class="col-sm-3">

                        </div>
                    </div>

                @endforeach
                <div class="col-sm-12" style="margin-bottom: 5px">
                    <div class="col-sm-2">

                    </div>
                    <div class="col-sm-4 no-padding enlarge-number">
                        <label for="total_pass"> Student Pass</label>
                    </div>
                    <div class="col-sm-3 no-padding">
                        {!! Form::text('course_factor'."[total_pass]", null, ['class' => 'form-control enlarge-number', 'id'=> 'total_pass']) !!}
                    </div>
                </div>

                <div class="col-sm-12" style="margin-bottom: 5px">
                    <div class="col-sm-2">

                    </div>
                    <div class="col-sm-4 no-padding enlarge-number">
                        <label for="total_pass">Student Reserve</label>
                    </div>
                    <div class="col-sm-3 no-padding">
                        {!! Form::text('course_factor'."[total_reserve]", null, ['class' => 'form-control enlarge-number', 'id'=> 'total_reserve']) !!}
                    </div>
                </div>

            {!! Form::close() !!}

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
                dataType: "json",
                success: function(result) {
                    console.log(result);
                    if(result.status) {
                        notify("success","info", "You have done!");
                        window.delay(200).close();
                    } else {
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