@extends ('backend.layouts.popup_master')

@section ('title', trans('labels.backend.exams.title') . ' | ' . trans('labels.backend.exams.course.choose_course'))

@section('content')

    <style>
        .font_text{
            font-size: 13pt;
        }

    </style>

    <div class="box box-success">
        <div class="box-header with-border">
            <h1 class="box-title"> <span class="text_font"> Department Opption of Candidate DUT </span></h1>
        </div>
        <!-- /.box-header -->
        <div class="box-body">

            <form action="{{route('admin.candidate.register_student_dut',$examId)}}" class="student_registration_dept">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Choice</th>
                        <th>Department</th>
                        <th>Result</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($candidateDepartments as $candidateDepartment)
                            <tr class="font_text">
                                <td>
                                    @if($candidateDepartment->result == 'Pass')

                                        <input type="radio" name="department" value="{{$candidateDepartment->dept_id}}" class="text-success" checked>

                                    @else
                                        <input type="radio" name="department" value="{{$candidateDepartment->dept_id}}" >
                                    @endif
                                </td>
                                <td>
                                    {!! Form::label($candidateDepartment->dept_id, $candidateDepartment->dept_name, ['class' => 'col-md-4 col-sm-4 control-label required']) !!}
                                </td>
                                <td>
                                    {!! Form::label('', $candidateDepartment->result=='Pass'?$candidateDepartment->result:null, ['class' => 'col-md-4 col-sm-4 control-label label label-success']) !!}
                                </td>

                            </tr>
                        @endforeach
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

            var url = "{{route('admin.candidate.register_student_dut',$examId)}}";

            ajaxRequest('PUT', url,baseData);
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
//                        opener.refresh();
                        setTimeout(function(){
                            window.close();
                        },2000);
                    }
                }
            });
        }


    </script>
@stop