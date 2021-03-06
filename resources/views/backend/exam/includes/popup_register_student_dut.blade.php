@extends ('backend.layouts.popup_master')

@section ('title', trans('labels.backend.exams.title') . ' | ' . 'Department Option of Candidate DUT')

@section('content')

    <style>
        .font_text{
            font-size: 13pt;
        }

    </style>

    <div class="box box-success">
        <div class="box-header with-border">
            <h1 class="box-title"> <span class="text_font"> Candidate Name: {{$candidate->name_kh}} </span></h1>
        </div>
        <!-- /.box-header -->
        <div class="box-body">

            <form action="{{$register_url}}" class="student_registration_dept">
                <table class="table">
                    <thead>
                    <tr>
                        <th width="10mm">Choice</th>
                        <th>Department</th>
                        <th>Result</th>
                        <th> Count</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($candidateDepartments as $candidateDepartment)
                            <tr class="font_text">
                                <td>
                                        <input type="radio" name="department" value="{{$candidateDepartment->dept_id}}" class="text-success" >
                                </td>
                                <td>
                                    {!! Form::label($candidateDepartment->dept_id, $candidateDepartment->dept_name, ['class' => 'col-md-4 col-sm-4 control-label']) !!}
                                </td>
                                <td>

                                    @if($candidateDepartment->result=='Pass')
                                        {!! Form::label('', $candidateDepartment->result, ['class' => 'col-md-4 col-sm-4 control-label label label-success']) !!}
                                    @elseif($candidateDepartment->result=='Reserve')
                                        {!! Form::label('', $candidateDepartment->result, ['class' => 'col-md-4 col-sm-4 control-label label label-info']) !!}
                                    @else
                                        {!! Form::label('', null, ['class' => 'col-md-4 col-sm-4 control-label label label-info']) !!}
                                    @endif

                                </td>

                                <td>
                                    <label for="number" > {{$studentWithRegisteredStudetn[$candidateDepartment->dept_name]}}</label>
                                </td>

                            </tr>
                        @endforeach
                            <tr class="font_text" style="background-color: grey">
                                <td colspan="3"><strong>Total</strong></td>
                                <td>
                                    <?php $count = 0 ?>
                                    @foreach($studentWithRegisteredStudetn as $element)
                                        <?php $count = $count + $element ?>
                                    @endforeach
                                    <strong>{{$count}}</strong>
                                </td>
                            </tr>
                    </tbody>
                </table>
            </form>


        </div>

    </div>
    <div class="box box-success">
        <div class="box-body">
            <div class="pull-left">
                <a href="#" id="btn_cancel" class="btn btn-default btn-xs">{{ trans('buttons.general.cancel') }}</a>
            </div>

            <div class="pull-right">
                <input type="button" id="btn_ok_department_option" class="btn btn-danger btn-xs" value="OK" />
            </div>
            <div class="clearfix"></div>
        </div><!-- /.box-body -->
    </div><!--box-->
@stop

@section('after-scripts-end')
    <script>
        var register_url = "{{$register_url}}";
        $('#btn_cancel').on('click', function() {
            window.close();
        });

        $('#btn_ok_department_option').on('click', function() {

            var selected_department_id = $('input[name="department"]:checked', '.student_registration_dept').val();
            var candidate_id = JSON.parse('{{$candidate_id}}');
            var baseData = {
                department_id: selected_department_id,
                candidate_id: candidate_id
            };

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // confirm then
            $.ajax({
                url: register_url,
                type: 'GET',
                data: baseData,
                dataType: 'json',
                success:function(data) {
                    notify('success', 'Student Registered!!');
                    opener.refresh_candidate_list();
                    setTimeout(function(){
                        window.close();
                    },2000);
                }
            });

            //ajaxRequest('POST', register_url,baseData);

        });


        function ajaxRequest(method, baseUrl, baseData) {
            $.ajax({
                type: method,
                url: baseUrl,
                dataType: "json",
                data: baseData,
                success: function(resultData) {
                    console.log(resultData);
                    if(resultData.success) {
                        notify('success', 'Student Registered!!');
                        opener.refresh_candidate_list();
                        setTimeout(function(){
                            window.close();
                        },2000);
                    }
                }
            });
        }


    </script>
@stop