@extends ('backend.layouts.popup_master')

@section ('title', trans('labels.backend.exams.title') . ' | ' . 'Form Generating Group')

@section('content')
    <style>
        .text_font{
            font-size: 14pt;
        }
        .area{
            font-size: 16px;
            border-radius: 0;
            background: transparent;
            width: 120px;
            text-indent: 10px;
        }

    </style>

    <div class="box box-success">
        <div class="box-header with-border text_font">
            <h1 class="box-title"> <span class="text_font">ការចែកក្រុម នៃ ដេប៉ាតឺម៉ង់់ <strong>{{$departmentName}}</strong> </span></h1>
            <h1 class="box-title">សំរាប់ ថ្នាក់ <strong>{{$gradeName}} {{$degreeName}}</strong>  </h1>
            <h1 class="box-title">ក្នុងឆ្នាំសិក្សា <strong>{{$academic}}</strong></h1>
        </div>
        <!-- /.box-header -->
        <div class="box-body">

            <form action="{{route('admin.student.generate_student_group',1)}}" id="form_generate_group">

                <div class="col-sm-12 no-padding text_font">
                    {!! Form::hidden('gradeId', $gradeId, ['class' => 'form-control', 'id'=>'grade_id']) !!}
                    {!! Form::hidden('degreeId', $degreeId, ['class' => 'form-control', 'id'=>'degree_id']) !!}
                    {!! Form::hidden('departmentId', $departmentId, ['class' => 'form-control', 'id'=>'department_id']) !!}
                    {!! Form::hidden('academicId', $academicId, ['class' => 'form-control', 'id'=>'academic_id']) !!}


                    {!! Form::label("numer_student",'Students', ['class' => 'col-md-4 col-sm-4 control-label required']) !!}
                    {!! Form::text("student",null, ['class' => 'col-md-4 col-sm-4 form-group control-label required', 'id' => 'number_student']) !!}
                </div>
                <div class="col-sm-12 no-padding text_font ">

                    <div class="col-sm-4 no-padding">



                        {!! Form::label("Format",'Format:', ['class' => 'col-md-4 col-sm-4 control-label ', 'style' => 'margin-right:-20px']) !!}
                        {!! Form::label("Format",'Prefix', ['class' => 'col-md-4 col-sm-4 control-label ']) !!}
                        {!! Form::text("prefix",null, ['class' => 'col-md-4 col-sm-4 form-group control-label required', 'style' => 'width: 100px']) !!}

                        {!! Form::label("suffix",'Suffix', ['class' => 'col-md-4 col-sm-4 control-label ']) !!}

                        <select name="suffix" id="filter_format" class="area">
                            <option value="">Select Type </option>
                            <option value="number"> Number </option>
                            <option value="letter"> Letter </option>
                        </select>

                        {!! Form::label("postfix",'Postfix', ['class' => 'col-md-4 col-sm-4 control-label ']) !!}
                        {!! Form::text("postfix",null, ['class' => 'col-md-4 col-sm-4 form-group control-label required', 'style' => 'width: 100px']) !!}

                    </div>

                    <div class="col-sm-4">


                    </div>
                    <div class="col-sm-4">

                    </div>

                </div>
            </form>

        </div>
    </div>

    <div class="box box-success">
        <div class="box-body">
            <div class="pull-left">
                <a href="#" id="btn_cancel" class="btn btn-default btn-xs">Cancel</a>
            </div>

            <div class="pull-right">
                <input type="button" id="btn_ok_generate_group" class="btn btn-primary btn-xs" value="OK" />
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

        $('#btn_ok_generate_group').on('click', function() {

            var baseUrl = $('#form_generate_group').attr('action');
            var baseData = $('#form_generate_group').serialize();
            var number_studetn = $('#number_student').val();
            var filter_format = $('#filter_format option:selected').val();

            if(number_studetn) {
                if(filter_format) {
                    ajaxRequest('POST', baseUrl, baseData);
                } else {
                    notify('error', 'info', 'select suffix');
                }

            } else {
                notify('error', 'info', 'Input Number of Student');
            }
        })

        function ajaxRequest(method, baseUrl, baseData) {
            $.ajax({
                type: method,
                url: baseUrl,
                dataType: "json",
                data: baseData,
                success: function(resultData) {
                    console.log(resultData);

                    if(resultData.status) {
                        notify('success', 'info', resultData.message);

                        setTimeout(function(){
                            window.close();
                        },2000);
                    }

                }
            });
        }



    </script>
@stop