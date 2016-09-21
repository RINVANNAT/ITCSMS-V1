
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
                <button class="btn btn-primary" id="print_candidate_result"> Print </button>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">

            <div class="container">
                <div class="col-md-12 text-center">
                    <h3> Result of DUT Selection </h3>
                </div>
                <div class="col-md-12">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Order</th>
                            <th>Register ID</th>
                            <th>Name Khmer</th>
                            <th>Name Latin</th>
                            <th>Sexe</th>
                            <th>Birth Date</th>
                            <th>Study Ressident</th>
                            <th>Result</th>
                            <th>Department </th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i =0;?>
                        @foreach($candidateDUTs as $result)
                            <?php $i++;?>
                            <tr>
                                <td><?php echo str_pad($i, 4, '0', STR_PAD_LEFT);?></td>
                                <td>{{$result->register_id}}</td>
                                <td>{{$result->name_kh}}</td>
                                <td>{{$result->name_latin}}</td>
                                <td>{{$result->gender}}</td>
                                <td> <?php $date = explode(' ', $result->birth_date); echo $date[0];?></td>
                                <td>{{$result->home_town}}</td>
                                <td>{{$result->is_success}}</td>
                                <td>{{$result->department_name}}</td>

                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <div class="modal">

        </div>

    </div>
@stop

@section('after-scripts-end')
    <script>
        window.onload = function() {
            $('.modal').style.display = "none";
        };

        var exam_id = JSON.parse('<?php echo $examId; ?>');

        function ajaxRequest(method, baseUrl, baseData){

            $.ajax({
                type: method,
                url: baseUrl,
                data:baseData,
                success: function(result) {
                    console.log(result);
                    if(result.status) {
                        window.close();
                        var printUrl = "{!! route('print_candidate_result_lists') !!}";
                        window_print_candidate_result = PopupCenterDual(printUrl+'?status='+'print_page'+'?exam_id='+exam_id,'print candidates result','1000','1200');
                    }
                }
            });
        }

        $('#print_candidate_result').on('click', function() {

            var baseUrl  = "{!! route('print_candidate_result_lists') !!}";

            var baseData = {status: 'request_print_page'}

            ajaxRequest('GET', baseUrl, baseData);
        })
    </script>
@stop