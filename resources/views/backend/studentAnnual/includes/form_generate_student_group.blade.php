@extends ('backend.layouts.popup_master')

@section ('title', trans('labels.backend.exams.title') . ' | ' . 'Form Generating Group')

@section('after-styles-end')
    {!! Html::style('plugins/select2/select2.min.css') !!}
@endsection

@section('content')
    <style>
        .text_font{
            font-size: 14pt;
        }


        .form-control_ {
            display: block;
            width: 24%;
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
            width: 10% !important;
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
                                <a href="#"  class=" btn_cancel btn btn-danger btn-xs">Cancel</a>
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
                        <h1 class="box-title">ក្នុងឆ្នាំសិក្សា <strong id="year_title"></strong> ឆមាសទី <strong id="semester_title"> </strong></h1>
                    </div>
                    <!-- /.box-header -->

                    <div class="box-body">

                        <form action="{{route('admin.student.generate_student_group')}}" id="form_generate_group">

                            <div class="col-sm-12 no-padding buttom-10" >

                                {!! Form::select('academic_year_id',$academicYears,null, array('class'=>'form-control_ col-sm-3','id'=>'academic_year')) !!}

                                {!! Form::select('degree_id',$degrees,null, array('class'=>'form-control_ col-sm-3','id'=>'degree')) !!}

                                {!! Form::select('grade_id',$grades,null, array('class'=>'form-control_ col-sm-3','id'=>'grade')) !!}

                                {!! Form::select('semester_id',$semesters,null, array('class'=>'form-control_ col-sm-3','id'=>'semester')) !!}

                            </div>



                            <div class="col-sm-12 buttom-10 no-padding" style="margin-left: 5px; margin-top: 10px">

                                <p style="font-size: 12pt"> Group Department</p>

                                @foreach($departments as $department)
                                    <label for="{{$department->code}}" class="btn btn-sm btn-warning" style="width: 78px; font-size: 12pt">

                                        <?php $checked = ($department->id == config('access.departments.department_tc'))?'checked':''; ?>
                                        <input type="checkbox" name="department_id" class="department" style="font-size: 12pt" id="{{$department->code}}" value="{{$department->id}}" {{$checked}}>
                                        {{$department->code}}
                                    </label>
                                @endforeach
                            </div>


                            <div class="col-sm-12 buttom-10 no-padding" style="margin-left: 5px; margin-top: 10px">

                                <label for="title_option" style="font-size: 12pt; margin-right: 10px"> Options: </label>

                                @foreach($options as $option)
                                    <label for="{{$option->code}}" class="btn btn-sm btn-primary dept_option {{$option->department_id}} " style="width: 78px; font-size: 12pt">

                                        <input type="checkbox" name="department_option_id" class="department_option " style="font-size: 12pt" id="{{$option->code}}" value="{{$option->id}}">
                                        {{$option->code}}
                                    </label>
                                @endforeach
                            </div>


                            <div class="col-sm-12 no-padding" style="margin-top: 10px">

                                <label for=" student_pergroup" class="col-sm-3 c-sm-3" style="margin-top: 5px"> Number Student Per-Group </label>
                                <div class="col-sm-3 no-padding">
                                    <input type="text" name="number_student" class="form-control input_text" style="width: 100%">
                                </div>


                                <label for=" student_pergroup" class="col-sm-5"  style="margin-top: 5px"> Total Student:  <strong id="total_stdent" style="color: green; font-size: 16pt">  </strong> </label>
                                <div class="col-sm-3 no-padding pull-right">

                                    <input disabled type="hidden" name="total_student" class="form-control input_text pull-right" style="width: 100%; color: green; font-size: 14pt" value="" >
                                </div>

                            </div>


                            <div class="col-sm-12 no-padding" style="margin-top: 10px;">

                                <div class="box-header with-border text_font" style="border-top: 1px solid #f5eeee">
                                    <h1 class="box-title"> Generate Group BY: </h1>
                                </div>

                                <div class="box-body">

                                    <label for="name_latin_rul" class="btn btn-sm btn-info" style="width: 160px;margin-right: 5px; font-size: 12pt" data-toggle="tooltip" data-position="top" title="If you choose this option, it will automatically generate groups of students by their name in latin">

                                        <input type="radio" name="rule" class="rule" style="font-size: 10pt" id="name_latin_rul" value="by_name" checked>
                                        Name Latin
                                    </label>


                                    <label for="id_card_rule" class="btn btn-sm btn-info" style="width: 160px; font-size: 12pt; margin-right: 5px;" data-toggle="tooltip" data-position="top" title="If you choose this option, it will automatically generate groups of students by ID Card.">

                                        <input type="radio" name="rule" class="rule" style="font-size: 10pt" id="id_card_rule" value="by_id_card" >
                                       ID Card
                                    </label>

                                    {{--<label for="score_rule" class="btn btn-sm btn-info" style="width: 160px; font-size: 12pt" data-toggle="tooltip" data-position="top" title="For this option you have to select one of the course program to generate group!">

                                        <input type="radio" name="rule" class="rule" style="font-size: 10pt" id="score_rule" value="by_score" >
                                       Score Course
                                    </label>--}}


                                   {{-- <div class="col-sm-4 no-padding pull-right" style="margin-top: 5px" id="div_load_course">

                                        <select  name="reference_course_id" id="reference_course_id" style="width: 100%; height: 100%"  data-placeholder="Course Program" class="form-control" >


                                        </select>
                                    </div>--}}

                                </div>


                                <div class="col-sm-12 no-padding" style="margin-top: 10px">

                                    <label for="fomart_text" class="col-sm-6 text_font"> Fomart: Group Code </label>

                                    <div class="col-sm-12 no-padding text_font buttom-10">

                                        <label for="fomart_text" class="col-sm-3 sm-3"> Prefix </label>
                                        <div class="col-sm-2 no-padding">
                                            <input type="text" name="prefix" readonly class="form-control input_text">
                                        </div>

                                        <label for="suffix" class="col-sm-1" style="margin-right: 10px"> Suffix  </label>

                                        {!! Form::select('suffix',['number' => 'Number', 'alphabet'=> 'Alphabet'],null, array('class'=>'control-form col-sm-4','id'=>'grade')) !!}

                                        <label for="post_fix" class="col-sm-1" style="margin-right: 20px"> Result </label>
                                        <div class="col-sm-3 no-padding">
                                            <input type="text" name="result" readonly class="form-control input_text" style="width: 100%; color: green">
                                        </div>

                                    </div>
                                </div>

                            </div>



                        </form>
                    </div>

                    <div class="box-body with-border">
                       <div class="col-sm-12">

                           <div class="pull-left">
                               <a href="#" class=" btn_cancel btn btn-danger btn-xs">Cancel</a>
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
    {!! HTML::script('plugins/select2/select2.full.min.js') !!}
    <script>

        var totalStudent = function(object) {
            return $(object).val();
        };

        $(document).ready(function() {
            initSelect2();
            $('.dept_option').hide();
            $('#div_load_course').hide()
            setTitle();
            getStudents();

            $(document).on('change', 'select[name=suffix]', function() {
                setTitle();
            });
            $(document).on('change', 'select[name=academic_year_id]', function (e) {
                setTitle();
                getStudents();
                loadCourse();

            });
            $(document).on('change', 'select[name=degree_id]', function (e) {
                setTitle();
                getStudents();
                loadCourse();

            });
            $(document).on('change', 'select[name=grade_id]', function (e) {
                setTitle();
                getStudents();
                loadCourse();

            });
            $(document).on('change', 'select[name=semester_id]', function (e) {
                setTitle();
                getStudents();
                loadCourse();

            });

            $(document).on('change', 'input[name=department_option_id]', function() {
               if($(this).is(':checked')) {

                   var current_object = $(this);
                   $('input[name=department_option_id]').each(function(key, value) {
                       if($(value).attr('id') != current_object.attr('id')) {
                           $(value).prop('checked', false);
                       }
                   });
                   getStudents();
                   loadCourse();

               } else {
                   getStudents();
                   loadCourse();

               }
            });

            $(document).on('change', '.department', function (e) {
                clearChecked();
                if($(this).is(':checked')) {
                    var current_object = $(this);
                    $('input.department').each(function(key, checkbox) {
                        if($(checkbox).attr('id') != current_object.attr('id')) {
                          $(checkbox).prop('checked', false);
                        }
                    });

                    getStudents();
                    loadCourse();

                } else {
                    getStudents();
                    loadCourse();

                }
                setTitle();
            });

            $(document).on('keyup', 'input[name=number_student]', function (e) {
                if($(this).val() != null && $(this).val() != '') {
                    setGroupLabel($(this).val());
                }

            });


            $(document).on('change', 'input[name=rule]', function (e) {
                if($(this).is(':checked')) {
                    if($(this).val() == 'by_score') {
                        $('#div_load_course').show();

                    } else {
                        if($('#div_load_course').is(':visible')) {
                            $('#div_load_course').hide();
                        }
                    }
                }
            });


            $('.btn_cancel').on('click', function() {
                window.close();
            });

            $('#btn_ok_generate_group').on('click', function() {

                var baseUrl = $('#form_generate_group').attr('action');
                var baseData = $('#form_generate_group').serialize();
                var number_studetn = $('input[name=number_student]').val();
                var group_by = $('input[name=rule] :checked').val();
                var prefix = $('input[name=prefix]').val();
                if(number_studetn) {
                    $.ajax({
                        type: 'GET',
                        url: baseUrl,
                        dataType: "json",
                        data: baseData,
                        success: function(resultData) {

                            if(resultData.status) {
                                notify('success', 'info', resultData.message);
                                var url = '{{route('student.annual.export_generated_group')}}';
                                var new_url = url+
                                        '?academic_year_id='+ resultData.request.academic_year_id+
                                        '&degree_id='+ resultData.request.degree_id+
                                        '&grade_id='+ resultData.request.grade_id+
                                        '&semester_id='+ resultData.request.semester_id+
                                        '&number_student='+ resultData.request.number_student+
                                        '&result='+ resultData.request.result+
                                        '&rule='+ resultData.request.rule+
                                        '&suffix='+ resultData.request.suffix+
                                        '&prefix='+ resultData.request.prefix;

                                if(resultData.request.department_id ) {
                                    if(resultData.request.department_id != 'undefined' && resultData.request.department_id  != '') {
                                        new_url += '&department_id='+ resultData.request.department_id;

                                        if(resultData.request.department_option_id) {
                                            if(resultData.request.department_option_id != 'undefined' && resultData.request.department_option_id  != '') {

                                                new_url += '&department_option_id=' + resultData.request.department_option_id;
                                            }
                                        }
                                    }

                                }
                                window.open(new_url, '_self')
                            }
                        }
                    });

                } else {
                    notify('error', 'Input Number of Student', 'Attention!');
                }
            });
        });


        function setGroupLabel(inputVal) {

            if(inputVal != null && inputVal != '') {

                if(totalStudent('input[name=total_student]') != null && totalStudent('input[name=total_student]') != '') {
                    var group = parseInt(parseInt(totalStudent('input[name=total_student]')) / parseInt(inputVal ))

                    $('#total_stdent').html(totalStudent('input[name=total_student]')+ ' |~ '+ 'Groups: ' + group);
                }
            }
        }

        var getBaseData = function () {

            var department_id = '';
            var departmentOptionId = '';
            $('input.department').each(function(key, checkbox) {
                if($(checkbox).is(':checked')) {
                    department_id = $(this).val();
                }
            });

            $('input[name=department_option_id]').each(function(key, value) {
                if($(value).is(':checked')) {
                    departmentOptionId = $(this).val();
                }
            });
            var baseData = {
                academic_year_id : $('select[name=academic_year_id] :selected').val(),
                degree_id : $('select[name=degree_id] :selected').val(),
                grade_id : $('select[name=grade_id] :selected').val(),
                semester_id : $('select[name=semester_id] :selected').val(),
                department_id:department_id,
                department_option_id :departmentOptionId
            };

            return baseData;
        }

        function getStudents() {

            var baseData = getBaseData();
            $.ajax({
                type: 'GET',
                url: '{{route('admin.student.number_student_annual')}}',
                dataType: "json",
                data: baseData,
                success: function(resultData)  {

                    if(resultData.status) {
                        $('#total_stdent').html(resultData.count)
                        $('input[name=total_student]').val(resultData.count)

                        setGroupLabel($('input[name=number_student]').val());
                    }
                }
            });

        }

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
                        addSelect_2_option(resultData.data);

                       /* setTimeout(function(){
                            window.close();
                        },2000);*/
                    }

                }
            });
        }


        function clearChecked()
        {
            $('input[name=department_option_id]').each(function(key, checkbox) {
                if($(checkbox).is(':checked')) {
                    department_id = $(this).prop('checked', false);
                }
            });
        }

        function loadCourse() {
            var baseData = getBaseData();
            //ajaxRequest('GET', '{{route('student.annual.load_course')}}', baseData);
        }

        function initSelect2()
        {
            $("#reference_course_id").select2({
                placeholder: "Select a reference course program",
                allowClear: true

            });
        }

        function addSelect_2_option(data)
        {

            clearOption();
            $('#reference_course_id').select2({
                allowClear: true,
                placeholder: " Select Course Program",
                data: data

            });
        }

        function  clearOption() {

            $('#reference_course_id').html('');
            initSelect2();
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
            var semester_title = $('select[name=semester_id] :selected').val();
            var Dept_name = '';

            $('input.department').each(function(key, checkbox) {
               if($(checkbox).is(':checked')) {
                   Dept_name = $(this).attr('id');
                   if(!$('.'+$(this).val()).is(':visible')) {
                       $('.'+$(this).val()).show();
                   }
               } else {
                   if($('.'+$(this).val()).is(':visible')) {
                       $('.'+$(this).val()).hide();
                   }
               }
            })
            $('#year_title').html(year)
            $('#class_name').html(class_name);
            $('#dept_name').html(Dept_name);
            $('#semester_title').html(semester_title);


            /*--set prefix value---*/

            var suffix = '';
            if($('select[name=suffix] :selected').val() == 'number') {
                suffix = '1';
            } else {
                suffix = 'A'
            }

            $('input[name=prefix]').val(degree_code+grade);

            $('input[name=result]').val(degree_code+grade+'-'+suffix)
        }


        /*---session message of the page----*/

        @if(Session::has('status'))
            @if(Session::get('status') == true)
                notify('success', '{{Session::get('message')}}', 'Import-Group')
            @else
                 notify('error', '{{Session::get('message')}}', 'Import-Group')
            @endif
        @endif



    </script>
@stop