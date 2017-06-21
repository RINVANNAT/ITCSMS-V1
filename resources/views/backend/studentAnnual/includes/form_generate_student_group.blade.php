@extends ('backend.layouts.popup_master')

@section ('title', trans('labels.backend.exams.title') . ' | ' . 'Form Generating Group')

@section('content')
    <style>
        .text_font{
            font-size: 14pt;
        }


        .form-control_ {
            display: block;
            width: 32%;
            height: 34px;
            padding: 6px 12px;
            font-size: 14px;
            line-height: 1.428571429;
            color: #555555;
            background-color: #fff;
            background-image: none;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
            transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s;
            margin-left: 5px;
        }

        .control-form {
            display: block;
            width: 15%;
            height: 34px;
            padding: 6px 12px;
            font-size: 14px;
            line-height: 1.428571429;
            color: #555555;
            background-color: #fff;
            background-image: none;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
            transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s;
            margin-left: 5px;
        }

        .input_text{
            border-radius: 4px !important;
            border-top-left-radius: 4px;
            border-top-right-radius: 4px;
            border-bottom-right-radius: 4px;
            border-bottom-left-radius: 4px;
        }

        .sm-3 {
            width: 20% !important;
        }

        .c-sm-3 {
            width: 27% !important;
        }

        .buttom-10 {
            margin-bottom: 10px;
        }

    </style>

    <div class="box-body">

        <div class="box-group" id="accordion">
            <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
            <div class="panel box box-primary">
                <div class="box-header with-border">
                    <h4 class="box-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" class="">
                            Import-Group
                        </a>
                    </h4>
                </div>
                <div id="collapseOne" class="panel-collapse collapse in" aria-expanded="true" >
                    <div class="box-body">

                        {!! Form::open(['route' => 'student_annual.import','id' => 'import_form_student', 'role'=>'form','files' => true])!!}
                        <div class="row no-margin">
                            <div class="form-group col-sm-12" style="padding: 20px;">
                                <span>Select the .CSV file to import. if you need a sample importable file, you can click the export button to generate one.</span>
                            </div>
                        </div>

                        <div class="row no-margin" style="padding-left: 20px;padding-right: 20px;">
                            <div class="form-group col-sm-12 box-body with-border text-muted well well-sm no-shadow" style="padding: 20px;">
                                {!! Form::label('import','Selected File (csv, xls, xlsx)') !!}
                                {!! Form::file('import', null) !!}
                            </div>

                            <div class="pull-left">
                                <a href="#" id="btn_cancel" class="btn btn-default btn-xs">Cancel</a>
                            </div>


                            <div class="pull-right" style="margin-left: 5pt">
                                <a href="{!! route('admin.student_annual.export_format_lists') !!}" class="btn btn-primary btn-xs" data-toggle="tooltip" data-position="top" title="Sample Student Group Lists">  <i class="fa fa-download"> {{ ' Export' }}</i></a>
                                <input type="submit" class="btn btn-success btn-xs" id="submit_group_import" value="{{ trans('buttons.general.import') }}" />
                            </div>

                        </div>

                        {!! Form::close() !!}


                    </div>
                </div>
            </div>
            <div class="panel box box-warning">
                <div class="box-header with-border">
                    <h4 class="box-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" class="collapsed" aria-expanded="false">
                            Generate-Group
                        </a>
                    </h4>
                </div>
                <div id="collapseTwo" class="panel-collapse collapse " aria-expanded="false" style="height: 0px;">

                    <div class="box-header with-border text_font">
                        <h1 class="box-title"> <span class="text_font">ការចែកក្រុម របស់កូនសិស្ស នៃ ដេបា៉តឺម៉ង់ <strong id="dept_name"> </strong> </span></h1>
                        <h1 class="box-title">សំរាប់ ថ្នាក់ <strong id="class_name"> </strong>  </h1>
                        <h1 class="box-title">ក្នុងឆ្នាំសិក្សា <strong id="year_title"></strong></h1>
                    </div>
                    <!-- /.box-header -->

                    <div class="box-body">

                        <form action="{{route('admin.student.generate_student_group',1)}}" id="form_generate_group">

                            <div class="col-sm-12 no-padding buttom-10" >

                                {!! Form::select('academic_year_id',$academicYears,null, array('class'=>'form-control_ col-sm-3','id'=>'academic_year')) !!}

                                {!! Form::select('degree_id',$degrees,null, array('class'=>'form-control_ col-sm-3','id'=>'degree')) !!}

                                {!! Form::select('grade_id',$grades,null, array('class'=>'form-control_ col-sm-3','id'=>'grade')) !!}

                            </div>

                            <div class="col-sm-12 no-padding text_font ">

                                <label for="fomart_text" class="col-sm-3 sm-3"> Fomart: Prefix </label>
                                <div class="col-sm-2 no-padding">
                                    <input type="text" name="prefix" class="form-control input_text">
                                </div>

                                <label for="suffix" class="col-sm-1" style="margin-right: 10px"> Suffix  </label>

                                {!! Form::select('suffix',['number' => 'Number', 'alphabet'=> 'Alphabet'],null, array('class'=>'control-form col-sm-4','id'=>'grade')) !!}

                                <label for="post_fix" class="col-sm-1" style="margin-right: 20px"> Result </label>
                                <div class="col-sm-3 no-padding">
                                    <input type="text" name="post_fix" class="form-control input_text" style="width: 100%; color: green">
                                </div>

                            </div>

                            <div class="col-sm-12 buttom-10 no-padding" style="margin-left: 5px; margin-top: 10px">

                                <p style="font-size: 12pt"> Select Owner Student's Group Department</p>

                                @foreach($departments as $department)
                                    <label for="{{$department->code}}" class="btn btn-sm btn-warning" style="width: 78px; font-size: 12pt">
                                        <input type="checkbox" name="department_id" class="department" style="font-size: 12pt" id="{{$department->code}}" value="{{$department->id}}">
                                        {{$department->code}}
                                    </label>
                                @endforeach
                            </div>

                            <div class="col-sm-12 no-padding" style="margin-top: 10px">

                                <label for=" student_pergroup" class="col-sm-3 c-sm-3" style="margin-top: 5px"> Number Student Per-Group </label>
                                <div class="col-sm-3 no-padding">
                                    <input type="text" name="number_student" class="form-control input_text" style="width: 100%">
                                </div>


                                <label for=" student_pergroup" class="col-sm-2"  style="margin-top: 5px"> Total Student </label>
                                <div class="col-sm-3 no-padding pull-right">
                                    <input disabled type="text" name="total_student" class="form-control input_text pull-right" style="width: 100%; color: green" value="1000" >
                                </div>

                            </div>

                        </form>
                    </div>

                    <div class="box-body">
                       <div class="col-sm-12">

                           <div class="pull-left">
                               <a href="#" id="btn_cancel" class="btn btn-default btn-xs">Cancel</a>
                           </div>

                           <div class="pull-right">
                               <input type="button" id="btn_ok_generate_group" class="btn btn-primary btn-xs" value="Generate-Group" />
                           </div>
                           <div class="clearfix"></div>

                       </div>
                    </div><!-- /.box-body -->
                </div>
            </div>
        </div>

    </div>

    {{--<div class="box box-success">
        <div class="box-body">
            <div class="pull-left">
                <a href="#" id="btn_cancel" class="btn btn-default btn-xs">Cancel</a>
            </div>

            <div class="pull-right">
                <input type="button" id="btn_ok_generate_group" class="btn btn-primary btn-xs" value="Generate-Group" />
            </div>
            <div class="clearfix"></div>
        </div><!-- /.box-body -->
    </div><!--box-->--}}

    <div class="loading">
        <i class="fa fa-refresh fa-spin"></i>
    </div>
@stop

@section('after-scripts-end')
    {{--here where i need to write the js script --}}
    <script>



        $(document).ready(function() {
            setTitle();
            $(document).on('change', 'select[name=academic_year_id]', function (e) {
                setTitle();
            });
            $(document).on('change', 'select[name=degree_id]', function (e) {
                setTitle();
            });
            $(document).on('change', 'select[name=grade_id]', function (e) {
                setTitle();
            });
        });
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


        function setTitle()
        {
            var year = $('select[name=academic_year_id] :selected').text();
            var degree = $('select[name=degree_id] :selected').val()

            if(parseInt(degree) == parseInt('{{\App\Models\Enum\ScoreEnum::Degree_I}}')) {
                var degree_code = 'I';
            }  else {
                var degree_code = 'T'
            }
            var grade = $('select[name=grade_id] :selected').val();
            var class_name= degree_code + grade;



            $('#year_title').html(year)
            $('#class_name').html(class_name);
            $('#dept_name').html('GIC');
        }



        @if(Session::has('status'))

            @if(Session::get('status') == true)
                notify('success', '{{Session::get('message')}}', 'Import-Group')
            @else

                 notify('error', '{{Session::get('message')}}', 'Import-Group')
            @endif


        @endif



    </script>
@stop