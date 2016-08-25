
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
                        <h3> Result of Standadize Testing Exam 2016-2017</h3>
                    </div>
                    <div class="col-md-12">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Order</th>
                                <th>Name Khmer</th>
                                <th>Name Latin</th>
                                <th>Result</th>
                                <th>Total Score</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i =0;?>
                            @foreach($candidatesResults as $result)
                                <?php $i++;?>
                                <tr>
                                    <td><?php echo $i;?></td>
                                    <td>{{$result->name_latin}}</td>
                                    <td>{{$result->name_kh}}</td>
                                    <td>{{$result->result}}</td>
                                    <td>{{$result->total_score}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

        </div>

    </div>
@stop

@section('after-scripts-end')
    <script>

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
                        window_request_room = PopupCenterDual(printUrl+'?status='+'print_page','print candidates result','1000','1200');
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