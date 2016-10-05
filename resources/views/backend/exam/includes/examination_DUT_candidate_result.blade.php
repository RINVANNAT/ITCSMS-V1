
@extends ('backend.layouts.popup_master')

@section ('title', trans('labels.backend.exams.title') . ' | ' . 'Candidates Result')

@section('content')

    <div class="box box-success">

        <style>
            .enlarge-text{
                font-size: 36px;
            }
            .enlarge-number{
                font-size: 28px;
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
            <h3 class="box-title">Candidates Result</h3>
            <div class="pull-right">

                <select name="result_type" id="dut_result_type" class="enlarge-number">
                    <option value="Pass"> Passed </option>
                    <option value="Reserve">  Reserved </option>
                    <option value="pass_by_dept">  Passed By Department </option>
                    <option value="reserve_by_dept">  Reserved By Department </option>
                </select>
                <button class="btn btn-primary btn-xs" id="print_candidate_dut_result"> Print </button>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">

            <div class="container">
                <div class="col-md-12 text-center">
                    <div class="col-md-2">

                    </div>
                    <div class="col-md-4">
                        <h3> Lists Of Candidates: </h3>
                    </div>
                    <div class="col-md-4" >
                        <h3> <div class="title text-info" style="margin-left: -100px"> </div></h3>
                    </div>
                    <div class="col-md-2">


                    </div>


                </div>


                <div class="col-md-12 candidate_DUT_result">


                </div>


            </div>

        </div>

        <div class="modal">

        </div>

    </div>
@stop

@section('after-scripts-end')
    <script>

        var baseUrl = "{!! route('admin.exam.dut_candidate_result_list_type', $examId) !!}";

        $('document').ready(function() {

            var selected_result_type = $('#dut_result_type :selected').val();

            $('.title').html($('#dut_result_type :selected').text());
            var baseData = {
                type:   selected_result_type
            };
            ajaxRequest('GET', baseUrl, baseData);
        });

        $('#dut_result_type').on('change', function() {

            var selected_result_type = $('#dut_result_type :selected').val();
            $('.title').html($('#dut_result_type :selected').text());
            var baseData = {
                type:   selected_result_type
            };
            ajaxRequest('GET', baseUrl, baseData);
        });


        function ajaxRequest(method, baseUrl, baseData){

            $.ajax({
                type: method,
                url: baseUrl,
                data:baseData,
                success: function(result) {
                    console.log(result);

                    $('.candidate_DUT_result').html(result);
                }
            });
        }

        $('#print_candidate_dut_result').on('click', function(){
            var selected_result_type = $('#dut_result_type :selected').val();
            var printUrl = "{!! route('admin.exam.print_candidate_dut_result', $examId) !!}";
            window_print_candidate_result = PopupCenterDual(printUrl+'?status='+selected_result_type,'print candidates result','1000','1200');
        });

    </script>
@stop