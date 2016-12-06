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
        <div class="box-header with-border">
            <h3 class="box-title">Complete Score Mathematic Course</h3>
        </div><!-- /.box-header -->



        <div class="box-body">

            <button class="btn btn-primary pull-right" id="add_column"> <i class="fa fa-plus"> Add column</i></button>
            <div id="popup" style="display: none;">
                <label for="percentage"> Percentage</label>
                <input type="text" id="percentage">
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

    <div class="box box-success">
        <div class="box-body">
            <div class="pull-left">
                <a href="{!! route('admin.course.course_annual.index') !!}" class="btn btn-danger btn-xs">{{ trans('buttons.general.cancel') }}</a>
            </div>

            <div class="pull-right">
                <input type="submit" class="btn btn-info btn-xs" value="{{ trans('buttons.general.crud.update') }}" />
            </div>
            <div class="clearfix"></div>
        </div><!-- /.box-body -->
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

        var setting = {
            data: JSON.parse('<?php echo ($studentData)?>'),
            colHeaders: ['Student ID', 'Student Name', 'Gender', 'Num Absence', 'Absence-10%', 'Average'],
            columns: [{
                data: 'student_id',
                name: 'studentID'
            }, {
                data: 'student_name'
            }, {
                data: 'student_gender'
            }, {
                data: 'num_absence'
            }, {
                data: 'absence',
                type : 'numeric',
                percentage: 10
            }, {
                type : 'numeric',
                readOnly: true
            }],
            rowHeaders: true,
            manualColumnMove: true,
            filters: true,
            contextMenu: true,
            stretchH: 'last',
            autoWrapRow: true,
            minSpareRows: true,
            width:1200,
            height:1000,
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
                                    semester_id:        rowData.semester_id
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
                                    semester_id:        rowData.semester_id
                                }
                            }

                            ajaxRequest('POST', url, baseData);
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


            var averageData = setting.columns[headerLength-1].data;
            var averageDataType = setting.columns[headerLength-1].type;
            var averageHeader = setting.colHeaders[headerLength-1];



            setting.columns[headerLength-1] = {data:colHeader+'-'+percentage, type:'numeric'};
            setting.columns.push({type:averageDataType});
            setting.colHeaders[headerLength-1] = (colHeader+'-'+percentage+'%');
            setting.colHeaders.push(averageHeader) ;

            console.log( setting.columns);
            hotInstance = new Handsontable(jQuery("#score_table")[0], setting);
            $('#popup').hide();
        })

        hotInstance = new Handsontable(jQuery("#score_table")[0], setting);


    </script>
@stop