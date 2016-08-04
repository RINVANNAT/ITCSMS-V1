@extends ('backend.layouts.popup_master')

@section ('title', trans('labels.backend.exams.title') . ' | ' . trans('labels.backend.exams.secret_code.title'))

@section('content')

    <div class="box box-success">
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
                        <div class="col-sm-3 no-padding">
                            <label for="course_name"> {{$courseId->course_name}} </label>
                        </div>
                        <div class="col-sm-3 no-padding">
                            {!! Form::text('course_factor'."[$courseId->course_id]", null, ['class' => 'form-control']) !!}
                            {{--{!! Form::hidden('course_factor_'."[course_id]",$courseId->course_id, ['class' => 'form-control']) !!}--}}
                        </div>
                        <div class="col-sm-3">

                        </div>
                    </div>

                @endforeach
                <div class="col-sm-12" style="margin-bottom: 5px">
                    <div class="col-sm-2">

                    </div>
                    <div class="col-sm-3 no-padding">
                        <label for="total_pass"> Student Pass</label>
                    </div>
                    <div class="col-sm-3 no-padding">
                        {!! Form::text('course_factor'."[total_pass]", null, ['class' => 'form-control', 'id'=> 'total_pass']) !!}
                    </div>
                </div>

                <div class="col-sm-12" style="margin-bottom: 5px">
                    <div class="col-sm-2">

                    </div>
                    <div class="col-sm-3 no-padding">
                        <label for="total_pass">Student Reserve</label>
                    </div>
                    <div class="col-sm-3 no-padding">
                        {!! Form::text('course_factor'."[total_reserve]", null, ['class' => 'form-control', 'id'=> 'total_reserve']) !!}
                    </div>
                </div>

            {!! Form::close() !!}

        </div>

    </div>

    <div class="box box-success">
        <div class="box-body">
            <div class="pull-left">
                <a href="#" id="btn-cancel" class="btn btn-default btn-xs">{{ trans('buttons.general.cancel') }}</a>
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
                        notify("error","info", "Please Check Your Record Was Not Saved!");
                    }

                }
            });
        }

        $('#btn_get_factor_ok').on('click', function() {
            ajaxRequest('POST', $( "form.calculation_score").attr('action'), $( "form.calculation_score" ).serialize() );
        })



    </script>


@stop