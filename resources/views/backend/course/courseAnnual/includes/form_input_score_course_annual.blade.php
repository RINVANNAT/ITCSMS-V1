@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.courseAnnuals.title') . ' | ' . trans('labels.backend.courseAnnuals.sub_edit_title'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.courseAnnuals.title') }}
        <small>{{ trans('labels.backend.courseAnnuals.sub_edit_title') }}</small>
    </h1>

@endsection

@section('content')

    <div class="box box-success">

            <div class="box-body">
                <div class="pull-left">
                    <a href="{!! route('admin.course.course_annual.index') !!}" class="btn btn-danger btn-xs">{{ trans('buttons.general.cancel') }}</a>
                </div>

                <div class="pull-right">
                    <input type="submit" class="btn btn-info btn-xs" id="save_editted_score" value="Save Changes!" />
                </div>
                <div class="clearfix"></div>
            </div><!-- /.box-body -->

        <div class="box-header with-border">
            <h3 class="box-title">Complete Score Mathematic Course</h3>
        </div><!-- /.box-header -->



        <div class="box-body">

            <button class="btn btn-xs btn-primary pull-right" id="add_column"> <i class="fa fa-plus"> Add column</i></button>
            <div id="popup" style="display: none;">
                <label for="percentage"> Percentage</label>
                <input type="text" id="percentage" class="number_only">
                <label for="name">Name of Score</label>
                <input type="text" id="name_exam" name="name_exam">
                <label for="score_type"> Score Type </label>
                <select name="score_type" id="score_type">
                    <option value="normal">Normal</option>
                    <option value="subplementary_exam">Subplementary Exam</option>
                </select>
                <button class="btn btn-xs btn-primary" id="add_column_ok"> OK</button>
            </div>
            <div id="score_table" class="handsontable htColumnHeaders">

            </div>
        </div>


    </div><!--box-->


@stop


@section('after-scripts-end')
    {!! Html::style('plugins/handsontable/handsontable.full.css') !!}
    {!! Html::style('plugins/handsontable/handsontable.full.min.css') !!}
    {!! Html::script('plugins/handsontable/handsontable.full.js') !!}


    {{--myscript--}}

    <script>

        function ajaxRequest (method, baseUrl, baseData) {
            $.ajax({
                type: method,
                url: baseUrl,
                data: baseData,
                dataType: "json",
                success: function(resultData) {
                    if(resultData.status == true) {

                        notify('success', 'info', resultData.message);

                    } else {
                        notify('error', 'info', resultData.message);
                    }
                }
            });
        }

        var hotInstance;
        var cellChanges = [];

        var setting = {
            rowHeaders: true,
            manualColumnMove: true,
            filters: true,
            contextMenu: true,
            autoWrapRow: true,
            minSpareRows: true,
            height:800,
            className: "htLeft",
            afterChange: function (changes, source) {

                if(changes){
                    $.each(changes, function (index, element) {
                        var change = element;
                        var rowIndex = change[0];
                        var columnIndex = change[1];
                        var oldValue = change[2];
                        var newValue = change[3];
                        var cellChange = {
                            'rowIndex': rowIndex,
                            'columnIndex': columnIndex
                        };

                        if(columnIndex != 'num_absence') {
                            var rowData = setting.data[rowIndex];
                            var url = '{{route('admin.course.save_score_course_annual')}}';
                            var pourcent = columnIndex.split('-');
                            if(columnIndex == 'absence') {
                                var baseData = {
                                    score: newValue,
                                    percentage: 10,
                                    student_annual_id:  rowData.student_annual_id,
                                    department_id:      rowData.department_id,
                                    degree_id:          rowData.degree_id,
                                    grade_id:           rowData.grade_id,
                                    academic_year_id :  rowData.academic_year_id,
                                    semester_id:        rowData.semester_id,
                                    course_annual_id: '{{$courseAnnualID}}'
                                }
                            } else {
                                var baseData = {
                                    score: newValue,
                                    percentage: parseInt(pourcent[pourcent.length-1]),
                                    student_annual_id: rowData.student_annual_id,
                                    department_id:      rowData.department_id,
                                    degree_id:          rowData.degree_id,
                                    grade_id:           rowData.grade_id,
                                    academic_year_id :  rowData.academic_year_id,
                                    semester_id:        rowData.semester_id,
                                    course_annual_id: '{{$courseAnnualID}}'
                                }
                            }

                            ajaxRequest('POST', url, baseData);
                        }

                        if(columnIndex == 'num_absence') {

                            var arrayAbsence=[];
                            var rowData = setting.data[rowIndex];

                            var baseData = {
                                num_absence: newValue,
                                student_annual_id: rowData.student_annual_id,
                                department_id:      rowData.department_id,
                                degree_id:          rowData.degree_id,
                                grade_id:           rowData.grade_id,
                                academic_year_id :  rowData.academic_year_id,
                                semester_id:        rowData.semester_id,
                                course_annual_id: '{{$courseAnnualID}}'
                            }

                            var cellChange = {
                                'rowIndex': rowIndex,
                                'columnIndex': columnIndex
                            };

                            if(oldValue != newValue){
                                cellChanges.push(baseData);
                            }

                        }
                    });
                }
            }


        };

        $('#add_column').on('click', function(e) {
            $('#popup').show();
        });

        $('#add_column_ok').on('click', function() {

            var colHeader = $('#name_exam').val();
            var percentage = $('#percentage').val();
            var headerLength = setting.colHeaders.length;
//            var averageData = setting.columns[headerLength-1].data;
            var averageDataType = setting.columns[headerLength-1].type;
            var averageHeader = setting.colHeaders[headerLength-1];
            var baseData = {
                percentage_name: colHeader+'-'+percentage+'%',
                percentage:percentage,
                percentage_type: $('#score_type :selected').val(),
                course_annual_id: '{{$courseAnnualID}}'

            };

            var BaseUrl = '{{route('admin.course.add_new_column_courseannual')}}'

            swal({
                title: "Confirm",
                text: "You want to add column?",
                type: "info",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, add id!!",
                closeOnConfirm: true
            }, function(confirmed) {
                if (confirmed) {
                    $.ajax({
                        type: 'POST',
                        url: BaseUrl,
                        data: baseData,
                        dataType: "json",
                        success: function(resultData) {
                            setting.data = resultData.data;
                            setting.colHeaders = resultData.columnHeader;
                            setting.columns = resultData.columns;
                            hotInstance = new Handsontable(jQuery("#score_table")[0], setting);
                            $('#popup').hide();
                        }
                    });
                }
            });



        })


        $(".number_only").keypress(function (e) {
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                return false;
            }
        });




        $('document').ready(function() {

            var BaseUrl = '{{route('admin.course.get_data_course_annual_score')}}';

            //--------------- when document ready call ajax
            $.ajax({
                type: 'GET',
                url: BaseUrl,
                data: {course_annual_id: '{{$courseAnnualID}}' },
                dataType: "json",
                success: function(resultData) {
                    setting.data = resultData.data;
                    setting.colHeaders = resultData.columnHeader;
                    setting.columns = resultData.columns;
                    hotInstance = new Handsontable(jQuery("#score_table")[0], setting);
                }
            });

        });


        $('#save_editted_score').on('click', function() {

            var url = '{{route('admin.course.save_number_absence')}}';
            swal({
                title: "Confirm",
                text: "You want to add column?",
                type: "info",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, add id!!",
                closeOnConfirm: true
            }, function(confirmed) {
                if (confirmed) {
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: {baseData:cellChanges},
                        dataType: "json",
                        success: function(resultData) {
                            setting.data = resultData.data;
                            setting.colHeaders = resultData.columnHeader;
                            setting.columns = resultData.columns;
                            hotInstance = new Handsontable(jQuery("#score_table")[0], setting);
                            cellChanges=[];
                        }
                    });
                }
            });

        })


        window.onbeforeunload = function(e){

            if(cellChanges.length == 0) {

            } else {
                return 'You have not yet save your changes!!';
            }
        };



    </script>
@stop