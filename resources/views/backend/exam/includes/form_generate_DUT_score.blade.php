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
            {{--here what i need to write --}}

            <table class="table text-center">
                <thead>
                <tr>
                    <th>Name Department</th>
                    <th> </th>
                </tr>
                </thead>
                <tbody>

                <form action="{{route('admin.exam.candidate_dut_generate_result',$examId)}}" class="table_number_candidate_pass">

                <?php
                //the php code is to display room data in two lines
                if(fmod(count($departments), 2) != 0) {
                for($i = 0; $i <=  (int)(count($departments)/2) ; $i++) {

                ?>
                <tr>
                    <?php if($i < (int)(count($departments)/2) ) {
                    ?>

                    <td><?php echo $departments[2*$i]->name_abr;?></td>
                    <td><input type="text" id="{{$departments[2*$i]->name_abr}}" class="form-group number_only number_candidate[{{$departments[2*$i]->name_abr}}]"></td>

                    <td><?php echo $departments[2*$i+1]->name_abr;?></td>
                    <td><input type="text" id="{{$departments[2*$i+1]->name_abr}}" class="form-group number_only number_candidate[{{$departments[2*$i+1]->name_abr}}]"></td>

                    <?php

                    } else if($i == (int)(count($departments)/2)  ) {?>

                    <td><?php echo $departments[2*$i]->name_abr;?></td>
                    <td><input type="text" id="{{$departments[2*$i]->name_abr}}" class="form-group number_only number_candidate[{{$departments[2*$i]->name_abr}}]"></td>

                    <?php
                    }
                    ?>

                </tr>

                <?php

                }
                } else {
                for($i = 0; $i < (int)(count($departments)/2) ; $i++) {
                ?>
                <tr>

                    <td><?php echo $departments[2*$i]->name_abr;?></td>


                    <td>{!! Form::text('number_candidate['.$departments[2*$i]->department_id.']', null, ['class' => 'form-control number_only']) !!}</td>

                    <td><?php echo $departments[2*$i+1]->name_abr;?></td>


                    <td>{!! Form::text('number_candidate['.$departments[2*$i+1]->department_id.']', null, ['class' => 'form-control number_only']) !!}</td>
                </tr>

                <?php
                }
                }
                ?>

                </form>
                </tbody>
            </table>



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