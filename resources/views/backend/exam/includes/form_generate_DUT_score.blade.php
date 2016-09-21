@extends ('backend.layouts.popup_master')

@section ('title', trans('labels.backend.exams.title') . ' | ' . 'Request Input Score Form')

@section('content')
    <style>
        .text_font{
            font-size: 14pt;
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
            <h1 class="box-title"> <span class="text_font">Input Number of Student Pass In Each Dept</span></h1>
        </div>
        <!-- /.box-header -->
        <div class="box-body">

            <form action="{{route('admin.exam.candidate_dut_generate_result',$examId)}}" class="table_number_candidate_pass">
                @foreach($departments as $department)
                    <div class="col-md-6 col-sm-6 form-group">
                        {!! Form::label("department[".$department->department_id."][success]", $department->name_abr, ['class' => 'col-md-4 col-sm-4 control-label required']) !!}
                        <div class="col-md-4 col-sm-4">
                            {{ Form::number("department[".$department->department_id."][success]", 0, ['class' => 'form-control number_only']) }}
                        </div>
                        <div class="col-md-4 col-sm-4">
                            {{ Form::number("department[".$department->department_id."][reserve]", 0, ['class' => 'form-control number_only']) }}
                        </div>
                    </div>
                @endforeach
            </form>

        </div>
    </div>

    <div class="box box-success">
        <div class="box-body">
            <div class="pull-left">
                <a href="#" id="btn_cancel" class="btn btn-default btn-xs">Cancel</a>
            </div>

            <div class="pull-right">
                <input type="button" id="btn_ok_generate_score" class="btn btn-primary btn-xs" value="OK" />
            </div>
            <div class="clearfix"></div>
        </div><!-- /.box-body -->
    </div><!--box-->

    <div class="loading">
        <i class="fa fa-refresh fa-spin"></i>
    </div>
@stop

@section('after-scripts-end')
    {{--here where i need to write the js script --}}
    <script>
        $('#btn_cancel').on('click', function() {
            window.close();
        });


        $(".number_only").keypress(function (e) {
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                return false;
            }
        }).keydown(function(e) {
            if (e.which === 13) {
                var index = $('.number_only').index(this) + 1;
                $('.number_only').eq(index).focus().select();
            }
        });

        $('#btn_ok_generate_score').on('click', function() {
           var student_pass = $('#dut_candidate_pass').val();
            var student_reserve = $('#dut_candidate_reserve').val();
            var baseData = {
                student_passed: student_pass,
                student_reserved: student_reserve
            };


            if(student_pass != '' && student_reserve != '') {

                ajaxRequest('GET', $( "form.table_number_candidate_pass").attr('action'), $( "form.table_number_candidate_pass" ).serialize());
            } else {
                notify('error', 'Please Input Number of Selected Student', 'Alert');
            }
        });

        function ajaxRequest(method, baseUrl, baseData) {
            $.ajax({
                type: method,
                url: baseUrl,
                dataType: "json",
                data: baseData,
                success: function(resultData) {
                    console.log(resultData);

                    if(resultData.status = true){
                        notify('success', 'well done');
                        var DUT_cand_list_Url = "{!! route('admin.exam.dut_candidate_result_lists', $examId) !!}";
                        window_print_candidate_result = PopupCenterDual(DUT_cand_list_Url,' candidates result lists','1000','1200');
//

                    } else {
                        notify('error','Cannot Calculate Score','Alert');
                    }
                }
            });
        }

        window.onload = function() {
            $('.modal').style.display = "none";
        };

    </script>
@stop