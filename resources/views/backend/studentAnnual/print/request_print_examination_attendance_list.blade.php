@extends ('backend.layouts.popup_master')

@section ('title', 'ITC-SMIS' . ' | ' . 'Print Transcript')

@section('page-header')
    <h1>
        {{ trans('labels.backend.candidates.title') }}
        <small>{{ trans('labels.backend.candidates.sub_create_title') }}</small>
    </h1>
@endsection

@section('after-styles-end')
    {!! Html::style('plugins/iCheck/square/red.css') !!}
    {!! Html::style('plugins/DataTables-1.10.15/media/css/dataTables.bootstrap.min.css') !!}
    {!! Html::style('plugins/daterangepicker/daterangepicker-bs3.css') !!}
    {!! Html::style('plugins/datetimepicker/bootstrap-datetimepicker.min.css') !!}
    {!! Html::style('plugins/select2/select2.min.css') !!}

    <style>

    </style>
@stop

@section('content')

    <div class="box box-success">
        <div class="box-header with-border">
            <h2 class="box-title pull-left" style="padding-top: 8px;">Printing Student Examination List </h2>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="box box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title">Filter</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="form-group">
                                <label>Academic Year</label>
                                {!! Form::select('academic_year',$academicYears,null, array('class'=>'form-control filter','id'=>'filter_academic_year')) !!}
                            </div>
                            <div class="form-group">
                                <label>Class</label>
                                <select style="width: 100%" name="student_class" class="form-control filter" id="filter_class"></select>
                            </div>
                            <div class="form-group">
                                <label>Semester</label>
                                <select style="width: 100%" name="semester_id" class="form-control filter" id="filter_semester">
                                    <option value="1">Semester 1</option>
                                    <option value="2">Semester 2</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="box box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title">Class Detail</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="callout callout-info">
                                <label>Estimate number of student per class </label>
                                <input type="number" name="number_student_per_class" style="color: black;text-align: center;"/>
                                <span style="margin-left: 20px;">Number of students: <span id="number_of_student" class="text-danger" style="font-size: 20px;"></span></span>
                                <br/>
                                Result : <span id="estimate_student" style="color: black;"></span>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <h3>Order student list by:</h3>
                                    <label for="name_latin_rul" class="btn btn-sm btn-default" style="width: 160px;margin-right: 5px; font-size: 12pt" data-toggle="tooltip" data-position="top" title="If you choose this option, it will automatically order students by their name in latin">
                                        <input type="radio" name="rule" class="rule" style="font-size: 10pt" id="name_latin_rul" value="by_name" checked>
                                        Name Latin
                                    </label>
                                    <label for="id_card_rule" class="btn btn-sm btn-default" style="width: 160px; font-size: 12pt; margin-right: 5px;" data-toggle="tooltip" data-position="top" title="If you choose this option, it will automatically order students by ID Card.">
                                        <input type="radio" name="rule" class="rule" style="font-size: 10pt" id="id_card_rule" value="by_id_card" >
                                        ID Card
                                    </label>
                                </div>
                            </div>

                        </div>
                        <div class="box-footer">
                            <div class="row">
                                <div class="col-md-12" align="right">
                                    <button class="btn btn-success" id="print"><i class="fa fa-print"></i> Print</button>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

@section('after-scripts-end')
    {!! Html::script('plugins/iCheck/icheck.min.js') !!}
    {!! Html::script('plugins/DataTables-1.10.15/media/js/jquery.dataTables.min.js') !!}
    {!! Html::script('plugins/DataTables-1.10.15/media/js/dataTables.bootstrap.min.js') !!}
    {!! Html::script('plugins/daterangepicker/moment.min.js') !!}
    {!! Html::script('plugins/daterangepicker/daterangepicker.js') !!}
    {!! Html::script('plugins/datetimepicker/bootstrap-datetimepicker.min.js') !!}
    {!! Html::script('plugins/select2/select2.full.min.js') !!}

    <script>
        var print_url = "{{ route('admin.student.print_examination_attendance_list') }}";
        var filter_class_url = '{{route('admin.filter.get_filter_by_class')}}';

        function count_student_number() {

            var selected_class = $("#filter_class").select2('data')[0];
            console.log(selected_class);
            $.ajax({
                type: 'GET',
                url: '{{route('admin.student.number_student_annual')}}',
                dataType: "json",
                data: {
                    "department_id" : selected_class.department_id,
                    "academic_year_id" : $('#filter_academic_year').val() ,
                    "degree_id" : selected_class.degree_id,
                    "grade_id" : selected_class.grade_id,
                    "semester_id" : $('#filter_semester').val(),
                    "department_option_id" : selected_class.department_option_id
                },
                success: function(resultData)  {
                    if(resultData.status) {
                        $('#number_of_student').html(resultData.count)
                        $('#number_of_student').data("total_student",resultData.count);

//                        $('input[name=total_student]').val(resultData.count)
                    }
                }
            });
        }
        function update_filter_class(){
            $.ajax({
                type: 'POST',
                url: filter_class_url,
                data: {'academic_year_id': $('#filter_academic_year').val()},
                dataType:"json",
                success: function(response) {
                    if(response.status == "success") {
                        $('#filter_class').select2({
                            data: response.data,
                            placeholder: "Select a class"
                        });
                        count_student_number();
                    } else {
                        notify("error","info", "Something went wrong! Cannot filtering value");
                    }
                }
            });
        }

        function print(selected_ids){
            var selected_class = $("#filter_class").select2('data')[0];
            if(($("input[name=number_student_per_class]").val() === null) || ($("input[name=number_student_per_class]").val() === "")) {
                alert("Number of student per class is required!");
            } else {
                PopupCenterDual(
                    print_url
                    +"?number_student_per_class="+ $("input[name=number_student_per_class]").val()
                    +"&academic_year_id="+$('#filter_academic_year').val()
                    +"&degree_id="+selected_class.degree_id
                    +"&grade_id="+selected_class.grade_id
                    +'&department_id='+selected_class.department_id
                    +"&semester_id="+$('#filter_semester').val()
                    +"&order_by="+$("input[name=rule]").val()
                    +"&department_option_id="+selected_class.department_option_id,
                    'Printing','1200','800');
            }
        }

        $(function() {

            update_filter_class();

            $(document.body).on("change","#filter_class",function(e){
                count_student_number();
            });
            $(document.body).on("change","#filter_academic_year",function(e){
                count_student_number();
            });
            $(document.body).on("change","#filter_semester",function(e){
                count_student_number();
            });
            $(document.body).on("change","input[name=number_student_per_class]", function(e) {
                var total_student = $('#number_of_student').data("total_student");
                var student_per_class = parseInt($(this).val());
                var total_class = Math.floor(total_student/student_per_class);
                var remainder = total_student%student_per_class;
                if (remainder>total_class) {
                   alert("Number of student per class is not valid! There are too many student left");
                   $(this).val("");
                    $("#estimate_student").html("");
                } else {
                    if(remainder !== 0) {
                        var a = total_class-remainder;
                        var b = student_per_class+1;
                        if(a===0) {
                            $("#estimate_student").html(remainder+" class with "+ b +" students");
                        } else {
                            $("#estimate_student").html(a+" class with "+student_per_class+" student and "+remainder+" class with "+ b +" students");
                        }
                    } else {
                        $("#estimate_student").html(total_class+" class with "+student_per_class+" student");
                    }
                }
            });
            $(document.body).on("click","#print",function(e){
                print();
            });
        });

    </script>
@stop