@extends ('backend.layouts.popup_master')

@section ('title', 'Course Annual' . ' | ' . 'Total Score Annually')
@section('after-styles-end')
    {!! Html::style(elixir('css/handsontable.full.min.css')) !!}
    <link rel="stylesheet" href="{{ asset('node_modules/inputmask/css/inputmask.css') }}"/>
    <style>
        .popupdiv {
            height: 200px;
            width: 600px;
            background-color: red;
            opacity: 60;
        }
        .drop-menu {
            margin-top: 5px;
        }
        .pop_margin {
            margin-right: 30px;
        }
        .margin-left2 {
            margin-left: 5px;
        }
        .selection {

            /*width: 80px;
            font-size: 10pt;
            height: 23px;
            margin-left: 5px;*/
        }
        /*@-moz-document url-prefix() { !* targets Firefox only *!
            select {
                padding: 15px 0!important;

            }
        }*/
        .h4 {
            text-align: left;
            margin-top: -3px !important;
        }
        .handsontable thead tr:first-child {
            height: 80px !important;
            vertical-align: middle !important;
        }
        .handsontable thead tr:nth-child(2) {
            height: 50px !important;
            vertical-align: middle !important;
        }
        .handsontable th {
            white-space: normal !important;
        }

        .handsontable td {
            color: #000 !important;
        }
        .currentRow {
            background-image: linear-gradient(rgba(181, 209, 255, 0.5) 0px, rgba(181, 209, 255, 0.341176) 100%) !important;
        }
        .currentCol {
            background-image: linear-gradient(rgba(181, 209, 255, 0.5) 0px, rgba(181, 209, 255, 0.341176) 100%) !important;
        }
        .htAutocompleteArrow {

            float: right !important;
            font-size: 10px !important;
            color: #EEE !important;
            cursor: default !important;
            width: 16px !important;
            text-align: center !important;
        }
        .handsontable td .htAutocompleteArrow:hover {
            color: #777 !important;
        }
        .handsontable td.area .htAutocompleteArrow {

            color: #d3d3d3 !important;
        }
        .top {
            margin-top: 5px;
            color: #0A0A0A;
        }
        .top a {
            color: black;
        }
        .col-sm-3 {
            width:16.2%;

        }
        .left-margin {
            margin-left: 5px;
        }
    </style>
@endsection

@section('content')

    <div class="box box-success">
        <div class="box-header with-border" style="margin-bottom: 0px">

            <div class="no-padding col-sm-12">

                <div class="dropdown pull-left action-button">

                    <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Action
                        <span class="caret"></span>
                    </button>

                    <!-- Button trigger modal -->
                    <button type="button"
                            class="btn btn-default"
                            data-toggle="modal"
                            data-target="#myModal"><strong>Passed Score</strong>
                        <span class="badge" style="background-color: #ef7a35; color: #fff;">@{{ parseFloat(average).toFixed(2) }}</span></button>

                    {{--<button class="btn btn-warning" data-toggle="tooltip" data-placement="right"  title="Generate student for next year" id="generate_student" >
                        <i class="fa fa-circle-o-notch" aria-hidden="true"></i>
                    </button>--}}

                    <ul class="dropdown-menu">
                        <li class="top"><a href="#" class="btn btn-xs" id="btn-print"><i class="fa fa-print"></i> Print</a>
                        </li>
                        <li class="top"><a href="#" class="btn btn-xs " id="get_radie"><i class="fa fa-download"></i>
                                Radié</a></li>
                        <li class="top"><a href="#" class="btn btn-xs " id="btn_export_score"><i
                                        class="fa fa-download"></i> Export</a></li>
                        <li class="top"><a href="#" class="btn btn-xs" id="get_redouble"><i class="fa fa-download"></i>
                                Redouble</a></li>
                        <li class="top"><a href="#" class="btn btn-xs" id="generate_rattrapage"> <i
                                        class="fa fa-circle-o"></i> Rattrapage</a></li>
                        <li class="top"><a href="{{route('student.statistic_radie')}}" class="btn btn-xs"
                                           id="print_total_radie"> <i class="fa fa-bar-chart"> </i>Statistic Radie</a>
                        </li>

                    </ul>

                </div>


                <div class="pull-right btn-right">

                    <button class="btn btn-primary"
                            data-toggle="tooltip"
                            data-placement="left"
                            @click="getAverage"
                            title="Refresh-Table"
                            id="refresh_score_sheet" >
                        <i class="fa fa-refresh" ></i>
                    </button>

                    <button class="btn btn-success"
                            data-toggle="tooltip"
                            data-placement="left"
                            title="Change Option"
                            @click="getAverage"
                            id="change_option" >
                        <i class="fa fa-stack-exchange" ></i>
                    </button>


                </div>
            </div>

            <div class="text-center" style="margin-bottom: 0px">
                <label  style="font-size: 12pt; margin-bottom: -20px">
                    RELEVE DES NOTE CONTROLE: <Strong style="color: darkgreen; font-size: 14pt; font-weight: bold" id="class_title"> GEE-I3 |~EAT </Strong>
                <p  style="font-size: 10pt" class="text-center">Année Scolaire <strong style="color: darkgreen; font-weight: bold; font-size: 12pt" id="year_name_latin"> 2016-2017 </strong></p>

                </label>

            </div>

        </div>

        <div class="selection_blog col-sm-12 box" style="padding-right: 0px; margin-bottom: 10px; margin-top: 5px; border-top: 0px!important;">

            <select name="academic_year"
                    @change="getAverage"
                    id="filter_academic_year"
                    class=" form-control col-sm-3">
                @foreach($academicYears as $key=>$year)
                    <option value="{{$key}}"> {{$year}}</option>
                @endforeach
            </select>

            <select name="department"
                    id="filter_dept"
                    @change="getAverage"
                    class="left-margin form-control col-sm-3">
                @foreach($departments as $key=>$departmentName)
                    <option value="{{$key}}"> {{$departmentName}}</option>
                @endforeach
            </select>

            <select name="dept_option" id="filter_dept_option" class="left-margin form-control col-sm-3">
                <option value="">Option</option>
                @foreach($departmentOptions as $option)
                    <option value="{{$option->id}}"
                            class="dept_option department_{{$option->department_id}}">{{$option->code}}</option>
                @endforeach
            </select>

            <select name="semester"  @change="getAverage" id="filter_semester"  class="left-margin form-control col-sm-3">
                @foreach($semesters as $key=>$semester)
                    <option value="{{$key}}"> {{$semester}}</option>
                @endforeach
                <option value=""> Semesters</option>
            </select>

            <select name="degree" id="filter_degree" class="left-margin form-control  col-sm-3">
                @foreach($degrees as $key=>$degreeName)
                    <option value="{{$key}}"> {{$degreeName}}</option>
                @endforeach
            </select>


            <select name="grade" id="filter_grade" class="left-margin form-control col-sm-3" style="margin-bottom: 5px">
                @foreach($grades as $key=>$gradeName)

                    @if($department_id = \App\Models\Enum\ScoreEnum::Dept_TC)

                        @if($key == \App\Models\Enum\ScoreEnum::Year_1)

                            <option id="{{$key}}" value="{{$key}}" selected> {{$gradeName}}</option>
                        @else
                            <option id="{{$key}}" value="{{$key}}"> {{$gradeName}}</option>
                        @endif

                    @else
                        @if($key == \App\Models\Enum\ScoreEnum::Year_3)

                            <option id="{{$key}}" value="{{$key}}" selected> {{$gradeName}}</option>
                        @else
                            <option id=" {{$key}}" value="{{$key}}"> {{$gradeName}}</option>
                        @endif

                    @endif

                @endforeach
            </select>

        </div>
        <!-- /.box-header -->
        @if (session('status'))
            <div class=" message alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        @if (session('warning'))
            <div class=" message alert alert-danger">
                {{ session('warning') }}
            </div>
        @endif
        <div class="box-body panel">
            <div id="blog_message">

            </div>
            <div id="all_score_course_annual_table" class="table table-striped handsontable htColumnHeaders">

            </div>

        </div>

        <!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title text-uppercase" id="myModalLabel">
                            <strong>Passed Score</strong>
                        </h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Passed Score</label>
                                <div class="col-sm-8">
                                    <input type="text"
                                           id="average"
                                           {{--data-inputmask-regex="[4-9][0-9].[0-9][0-9]"--}}
                                           v-model="average"
                                           class="form-control"
                                           placeholder="Enter average value">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button"
                                class="btn btn-default"
                                ref="btnCloseModal"
                                data-dismiss="modal">Close</button>
                        <button type="button"
                                @click="storeAverage"
                                class="btn btn-primary">Save Passed Score</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('after-scripts-end')
    {!! HTML::script(elixir('js/handsontable.full.min.js')) !!}
    {!! Html::script('plugins/jpopup/jpopup.js') !!}
    {!! Html::script('js/backend/course/courseAnnual/all_score.js') !!}
    <script src="{{ asset('node_modules/inputmask/dist/min/jquery.inputmask.bundle.min.js') }}"></script>
    <script src="{{ asset('node_modules/vue/dist/vue.js') }}"></script>
    <script src="{{ asset('node_modules/axios/dist/axios.js') }}"></script>
    <script src="{{ asset('js/set_average.js') }}"></script>
    {{--myscript--}}

    <script>

        $(function () {
            // $('#average').inputmask({autoUnmask: true, removeMaskOnSubmit: true})
        })

        var table_width;
        var hotInstance;
        var print_url = "{{route('admin.course.print_total_score')}}";
        var average_final_year = '<button class="btn btn-warning btn-average-final-year" data-toggle="tooltip" style="margin-right: 5px" data-placement="left"  title="Print Average Final Year" id="print_average_final_year"> Average Final Year</button>';
        var final_score_url = "{{route('admin.student.print_average_final_year', ['type'=>'show'])}}";
        var setting = {
            readOnly: true,
            rowHeaders: false,
            manualColumnMove: true,
            manualColumnResize: true,
            manualRowResize: false,
            minSpareRows: false,
            fixedColumnsLeft: 3,
            filters: true,
            dropdownMenu: ['filter_by_condition', 'filter_action_bar'],
            className: "htRight 4",
            currentRowClassName: 'currentRow',
	        passMoyenne: 10,
            currentColClassName: 'currentCol',
            cells: function (row, col, prop) {
                console.log(colorRenderer.arguments)
                this.renderer = colorRenderer;

                var cellProperties = {};
                if (prop === 'Redouble') {
                    cellProperties.readOnly = false;
                } else if (prop === 'Rattrapage') {
                    cellProperties.readOnly = false;
                } else if (prop === 'Passage') {
                    cellProperties.readOnly = false;
                } else if (prop == 'Remark') {

                    @if(access()->user()->allow('write-student-remark'))

                            cellProperties.readOnly = false;
                    @else
                            cellProperties.readOnly = true;
                    @endif

                } else if (prop === 'General_Remark') {
                    cellProperties.readOnly = false;
                }

                if (prop == 'number') {
                    cellProperties.className = 'htLeft'+' row_'+row;
                } else if (prop == 'student_name') {
                    cellProperties.className = 'htLeft'+' row_'+row;

                } else if (prop == 'student_gender') {
                     cellProperties.className = 'htLeft'+' row_'+row;

                } else if (prop == 'student_id_card') {
                     cellProperties.className = 'htLeft'+' row_'+row;
                } else if (prop == 'Observation') {
                     cellProperties.className = 'htLeft'+' row_'+row;
                } else if (prop == 'Remark') {
                    cellProperties.className = 'htLeft'+' row_'+row;
                }

                @permission('evaluate-student')
                if (prop === 'Redouble') {
                    cellProperties.className = "htRight";
                    this.type = 'autocomplete';
                    this.filter = false;
                    if ($.trim($('#filter_degree :selected').text().toUpperCase()) == 'ENGINEER') {
                        this.source = ['Red. ' + 'I' + $('#filter_grade :selected').val(), 'Radié', 'P'] // to add to the beginning do this.source.unshift(val) instead
                    } else {
                        this.source = ['Red. ' + "T" + $('#filter_grade :selected').val(), 'Radié', 'P'] // to add to the beginning do this.source.unshift(val) instead
                    }

                }
                @endauth
                        return cellProperties;


            },
//            beforeOnCellMouseDown: function (event, coord, TD) {
//                return true;
//            },
            afterOnCellMouseDown: function (event, coord, TD) {
                return false;
            },
            afterCellMetaReset: function () {
                return true;
            },
            afterRowMove: function () {
                return true;
            },

            afterSelectionEnd: function () {
                setSelectedRow();
            },
            beforeTouchScroll: function () {

                return false;
            },
            afterScrollHorizontally: function () {
                setSelectedRow();
                return false;
            },

            afterScrollVertically:function () {
                setSelectedRow();
                return false;
            },

            afterColumnResize: function () {
                return false;
            },

            afterChange: function (changes, source) {

                if (changes) {

                    $.each(changes, function (index, element) {

                        var change = element;
                        var rowIndex = change[0];
                        var columnIndex = change[1];
                        var oldValue = change[2];
                        var newValue = change[3];
                        var col_student_id = hotInstance.getDataAtProp('student_id_card'); //---array data of column student_id

                        @permission('evaluate-student')

                        if (columnIndex == 'Redouble') {


                            if (newValue != '' && newValue != null) {

                                var check_val = true;
                                var array_val = [];
                                if ($.trim($('#filter_degree :selected').text().toUpperCase()) == 'ENGINEER') {
                                    array_val = ['Red. ' + 'I' + $('#filter_grade :selected').val(), 'Radié', 'P'] ;
                                } else {
                                    array_val = ['Red. ' + "T" + $('#filter_grade :selected').val(), 'Radié', 'P'];
                                }

                                $.each(array_val, function (key, arrayValue) {

                                    if(newValue != arrayValue) {
                                        check_val = false;
                                    }
                                });

                                if (oldValue != newValue) {

                                    //if(check_val) {

                                        var remark_rul = '{{route('student.update_status')}}';
                                        var baseData_redouble = {
                                            student_id_card: col_student_id[rowIndex],
                                            redouble: newValue,
                                            academic_year_id: $('#filter_academic_year :selected').val(),
                                            old_value: oldValue
                                        };

                                        $.ajax({
                                            type: 'POST',
                                            url: remark_rul,
                                            data: baseData_redouble,
                                            dataType: "json",
                                            success: function (resultData) {

                                                if (resultData.status) {
                                                    notify('success', resultData.message, 'Info');
                                                    //filter_table()
                                                } else {

                                                    notify('error', resultData.message, 'Attention');
                                                }
                                            }
                                        });
//                                    } else {
//                                        notify('error', 'Please choose value by double click on cell', 'Danger')
//                                    }
                                }

                            }
                        }

                        @endauth
                        @permission('write-student-remark')

                        if (columnIndex == 'Remark') {
                            var remark_rul = '{{route('course_annual.save_each_cell_remark')}}';
                            var baseData_remark = {
                                student_id_card: col_student_id[rowIndex],
                                remark: newValue,
                                academic_year_id: $('#filter_academic_year :selected').val()
                            };

                            $.ajax({
                                type: 'POST',
                                url: remark_rul,
                                data: baseData_remark,
                                dataType: "json",
                                success: function (resultData) {

                                    notify('success', 'Marked!', 'Info');
                                },
                                error: function (response) {

                                    notify('error', 'Something went wrong!', 'Info');
                                }
                            });
                        }
                        @endauth

                        if (columnIndex == 'General_Remark') {
                            var remark_rul = '{{route('course_annual.save_each_cell_general_remark')}}';
                            var baseData_remark = {
                                student_id_card: col_student_id[rowIndex],
                                general_remark: newValue,
                                academic_year_id: $('#filter_academic_year :selected').val()
                            };

                            $.ajax({
                                type: 'POST',
                                url: remark_rul,
                                data: baseData_remark,
                                dataType: "json",
                                success: function (resultData) {
                                    notify('success', 'General Mark Done!', 'Info');
                                },
                                error: function (response) {

                                    notify('error', 'Something went wrong!', 'Info');
                                }
                            });
                        }
                    })
                }
            }
        };

        $('.dept_option').hide();

        $(document).ready(function () {

            $("#filter_dept").on("change", function () {

                if($('.btn-right').children().hasClass('btn-average-final-year')){
                    $('.btn-right').children('.btn-average-final-year').remove();
                }

                if(($(this).val() != 8 && $(this).val() != 12 && $(this).val() != 13) && (($(this).siblings('#filter_degree').val() == 1 && $(this).siblings('#filter_grade').val() == 5) || ($(this).siblings('#filter_degree').val() == 2 && $(this).siblings('#filter_grade').val() == 2))){
                    $('.btn-right').prepend(average_final_year);
                }
            });

            $("#filter_degree").on("change", function () {

                if($('.btn-right').children().hasClass('btn-average-final-year')){
                    $('.btn-right').children('.btn-average-final-year').remove();
                }

                if(($(this).siblings('#filter_dept').val() != 8 && $(this).siblings('#filter_dept').val() != 12 && $(this).siblings('#filter_dept').val() != 13) && (($(this).val() == 1 && $(this).siblings('#filter_grade').val() == 5) || ($(this).val() == 2 && $(this).siblings('#filter_grade').val() == 2))){
                    $('.btn-right').prepend(average_final_year);
                }

            });

            $("#filter_grade").on("change", function () {
                console.log($('.btn-right').children().hasClass('btn-average-final-year'))

                if($('.btn-right').children().hasClass('btn-average-final-year')){
                    $('.btn-right').children('.btn-average-final-year').remove();
                }

                if(($(this).siblings('#filter_dept').val() != 8 && $(this).siblings('#filter_dept').val() != 12 && $(this).siblings('#filter_dept').val() != 13) && (($(this).siblings('#filter_degree').val() == 1 && $(this).val() == 5) || ($(this).siblings('#filter_degree').val() == 2 && $(this).val() == 2))){
                    $('.btn-right').prepend(average_final_year);
                }

            });

            $("#btn-print").on("click", function () {

                var baseData = getBaseData();

                var win = window.open(print_url +
                        "?department_id=" + baseData.department_id +
                        "&degree_id=" + baseData.degree_id +
                        "&grade_id=" + baseData.grade_id +
                        "&academic_year_id=" + baseData.academic_year_id +
                        "&semester_id=" + baseData.semester_id +
                        "&dept_option_id=" + baseData.dept_option_id
                        , '_blank');
                win.focus();
            });


            $('#btn_export_score').on('click', function () {

                var route_export = '{{route('course_annual.export_view_total_score')}}'
                var D = getBaseData();

                window.open(route_export + '?department_id=' + D.department_id +
                        '&degree_id=' + D.degree_id +
                        '&grade_id=' + D.grade_id +
                        '&academic_year_id=' + D.academic_year_id +
                        '&semester_id=' + D.semester_id +
                        '&dept_option_id=' + D.dept_option_id, '_blank'
                )
            })

            if (val = $('#filter_dept :selected').val()) {

                $('.department_' + val).show();

                if (val == parseInt('{{\App\Models\Enum\ScoreEnum::Dept_TC}}')) {

                    $('#filter_grade option').each(function (key, val) {

                        if ($(this).val() == parseInt('{{\App\Models\Enum\ScoreEnum::Year_1}}')) {
                            $(this).prop('selected', true);
                        }
                    });

                } else {
                    $('#filter_grade option').each(function (key, val) {

                        if ($(this).val() == parseInt('{{\App\Models\Enum\ScoreEnum::Year_3}}')) {
                            $(this).prop('selected', true);
                        }
                    });
                }
            }

            initTable();

        });

        function initTable() {

            setTitle();
            toggleLoading(true);
            var BaseUrl = '{{route('admin.course.get_all_handsontable_data')}}';
            var BaseData = getBaseData();

            //--------------- when document ready call ajax


            $.ajax({
                type: 'GET',
                url: BaseUrl,
                data: BaseData,
                dataType: "json",
                success: function (resultData) {

                    toggleLoading(false);
                    if (resultData.status == false) {

                        showMessage( resultData, true);
                    } else {
                        setting.data = resultData.data;
                        setting.nestedHeaders = resultData.nestedHeaders;
                        setting.colWidths = resultData.colWidths;
                        setting.subject = resultData.subject;
                        setting.array_fail_subject = resultData.array_fail_subject;

                        var table_size = $('.box-body').width() + 30;
                        var mainHeaderHeight = $('.main-header').height();
                        var mainFooterHeight = $('.main-footer').height();
                        var boxHeaderHeight = $('.box-header').height();
                        var height = $(document).height();
                        var tab_height = height - (mainHeaderHeight + mainFooterHeight + boxHeaderHeight + 60);

                        setting.height = tab_height;

                        showMessage( resultData, false);

                        hotInstance = new Handsontable(jQuery("div#all_score_course_annual_table")[0], setting);
                        assignNumberRattrapage();// ---after initial handsontable
                        hotInstance.updateSettings({
                            contextMenu: {
                                callback: function (key, options) {

                                    if (key === 'sort') {
                                        setTimeout(function () {
                                            //timeout is used to make sure the menu collapsed before alert is shown

                                            var col_student_id = hotInstance.getDataAtProp('student_id_card')
                                            var row = hotInstance.getSelected()[0];
                                            var col = hotInstance.getSelected()[1];

                                            var data = hotInstance.getData();
                                            var settingData = setting.data;
                                            var arrayData = [];
                                            var averageMaxMin = [];


                                            for (var key = 0; key < data.length; key++) {
                                                if ((data[key][col] != null) && (data[key][col] != '')) {
                                                    arrayData.push({
                                                        student_id_card: data[key][1],
                                                        score: data[key][col]
                                                    });
                                                } else {
                                                    break;
                                                }
                                            }

                                            if (col > 3 && col < (hotInstance.countCols() - 6)) {

                                                arrayData.sort(SortByScore);
                                                var sortedData = [];
                                                for (var rank = 0; rank < arrayData.length; rank++) {
                                                    $.each(settingData, function (key, val) {

                                                        if (val.student_id_card == arrayData[rank].student_id_card) {

                                                            val.number = rank + 1;
                                                            sortedData.push(val);
                                                        }

                                                    })
                                                }
                                                for (var index = arrayData.length; index < settingData.length; index++) {
                                                    averageMaxMin.push(settingData[index])
                                                }
                                                var finalData = sortedData.concat(averageMaxMin);
                                                setting.data = finalData;
                                                hotInstance.updateSettings({
                                                    data: finalData
                                                });
                                            }
                                            if (col == 1) {

                                                var dataToSort = settingData.slice(0, arrayData.length);
                                                averageMaxMin = settingData.slice(arrayData.length);
                                                dataToSort.sort(SortId);
                                                var index = 0;
                                                $.each(dataToSort, function (key, val) {
                                                    val.number = index + 1;
                                                    index++;
                                                })

                                                var finalData = dataToSort.concat(averageMaxMin);
                                                setting.data = finalData;
                                                hotInstance.updateSettings({
                                                    data: finalData
                                                });
                                            }

                                            if (col == 2) {

                                                var dataToSort = settingData.slice(0, arrayData.length);
                                                averageMaxMin = settingData.slice(arrayData.length);
                                                dataToSort.sort(SortName);
                                                var index = 0;
                                                $.each(dataToSort, function (key, val) {
                                                    val.number = index + 1;
                                                    index++;
                                                })

                                                var finalData = dataToSort.concat(averageMaxMin);
                                                setting.data = finalData;
                                                hotInstance.updateSettings({
                                                    data: finalData
                                                });
                                            }


                                        }, 100);
                                    }

                                    function SortByScore(a, b) {
                                        var aScore = a.score;
                                        var bScore = b.score;
                                        return bScore - aScore;
                                    }

                                    function SortId(a, b) {
                                        var aId = a.student_id_card.toLowerCase();
                                        var bId = b.student_id_card.toLowerCase();
                                        return ((aId < bId) ? -1 : ((aId > bId ) ? 1 : 0));
                                    }

                                    function SortName(a, b) {
                                        var aName = a.student_name.toLowerCase();
                                        var bName = b.student_name.toLowerCase();
                                        return ((aName < bName) ? -1 : ((aName > bName ) ? 1 : 0));
                                    }

                                },
                                items: {
                                    "sort": {
                                        name: function () {
                                            var selectedColumn = hotInstance.getSelected()[1];
                                            if (selectedColumn == 3 || selectedColumn == 0) {
                                                return '';
                                            } else {
                                                if (selectedColumn < hotInstance.countCols() - 5) {
                                                    return '<span><i class="fa fa-leaf"> Sort Column </i></span>';
                                                } else {
                                                    return '';
                                                }

                                            }
                                        }
                                    }
                                }
                            }
                        });
                    }

                }
            });

            $(window).resize(function () {

                var table_width = $('.box-body').width();

                if(hotInstance) {
                    setting.width = table_width;
                    hotInstance.updateSettings({
                        width: table_width
                    });
                }

            });
        }

        function filter_table() {
            toggleLoading(true);
            var BaseData = getBaseData();

            if (hotInstance) {
                $.ajax({
                    type: 'GET',
                    url: '{{route('admin.course.filter_course_annual_scores')}}',
                    data: BaseData,
                    dataType: "json",
                    success: function (resultData) {
                        toggleLoading(false);
                        if (resultData.status == false) {

                            //totalScoreNotification(resultData.type, resultData.message, 'No Course Score Record')

                            showMessage(resultData, true);
                            updateSettingHandsontable(resultData);
                        } else {
                            setTitle();
                            showMessage(resultData, false);
                            updateSettingHandsontable(resultData);
                            assignNumberRattrapage();
                        }
                    }
                });
            } else {
                initTable();
            }
        }

        function updateSettingHandsontable(resultData) {

            setting.data = resultData.data;
            setting.nestedHeaders = resultData.nestedHeaders;
            setting.colWidths = resultData.colWidths;
            setting.array_fail_subject = resultData.array_fail_subject;
            hotInstance.updateSettings({
                data: resultData['data'],
                nestedHeaders: resultData['nestedHeaders'],
                colWidths: resultData['colWidths']

            });
        }

        $('#filter_dept').on('change', function () {
            $('.dept_option').hide();
            $('.department_' + $(this).val()).show();

            if ($(this).val() == parseInt('{{\App\Models\Enum\ScoreEnum::Dept_TC}}')) {

                $('#filter_grade option').each(function (key, val) {

                    if ($(this).val() == parseInt('{{\App\Models\Enum\ScoreEnum::Year_1}}')) {
                        $(this).prop('selected', true);
                    }
                });

            } else {

                $('#filter_grade option').each(function (key, val) {

                    if ($(this).val() == parseInt('{{\App\Models\Enum\ScoreEnum::Year_3}}')) {
                        $(this).prop('selected', true);
                    }
                });
            }

            //filter_table();
        });


        $('#generate_rattrapage').on('click', function () {

            var pop_url = '{{route('course_annual.student_redouble_exam')}}';
            var BaseData =getBaseData();
            student_reexam_lists = window.open(
                    pop_url +
                    '?department_id=' + BaseData.department_id +
                    '&degree_id=' + BaseData.degree_id +
                    '&grade_id=' + BaseData.grade_id +
                    '&semester_id=' + BaseData.semester_id +
                    '&dept_option_id=' + BaseData.dept_option_id +
                    '&academic_year_id=' + BaseData.academic_year_id, '_blank');

        });


        function assignNumberRattrapage() {

           if(hotInstance) {

               var array_fail_subject = setting.array_fail_subject;
               var table_data = setting.data;

               $.each(table_data, function (i, student) {

                   if (student['student_id_card'] != null && student['student_id_card'] != '') {

                       var moyenne = calculate_moyenne(array_fail_subject[student['student_id_card']]);

                       if ( moyenne >= parseFloat('{{\App\Models\Enum\ScoreEnum::Pass_Moyenne}}')) {
                           var all_subjects = array_fail_subject[student['student_id_card']];

                           if ('fail' in all_subjects) {
                               student['Rattrapage'] = all_subjects['fail'].length;
                           } else {
                               student['Rattrapage'] = 0;
                           }
                       } else {
                           var number_rattrapage = numberSubjectRattrapage(array_fail_subject[student['student_id_card']], '{{\App\Models\Enum\ScoreEnum::Pass_Moyenne}}', '{{\App\Models\Enum\ScoreEnum::Aproximation_Moyenne}}');
                           student['Rattrapage'] = number_rattrapage;
                       }
                   }
               });

               setting.data = table_data;
               hotInstance.updateSettings({
                   data: table_data,
                   colWidths: setting.colWidths

               });
           }
        }


        function numberSubjectRattrapage(subjects, pass_moyenne, approximate_moyenne) {

            var number_subject = getStudentReExam(subjects, pass_moyenne, approximate_moyenne);

            if ('fail' in number_subject) {
                return number_subject['fail'].length;
            } else {
                return 0;
            }
        }

        $('#get_radie').on('click', function () {

            var pop_url = '{{route('student.dismiss')}}';
            var BaseData =getBaseData()

            student_reexam_lists = window.open(
                    pop_url +
                    '?department_id=' + BaseData.department_id +
                    '&degree_id=' + BaseData.degree_id +
                    '&grade_id=' + BaseData.grade_id +
                    '&semester_id=' + BaseData.semester_id +
                    '&dept_option_id=' + BaseData.dept_option_id +
                    '&academic_year_id=' + BaseData.academic_year_id, '_blank');
        });


        $('#get_redouble').on('click', function () {

            var pop_url = '{{route('student.redouble')}}';
            var BaseData = getBaseData();

            student_reexam_lists = window.open(
                    pop_url +
                    '?department_id=' + BaseData.department_id +
                    '&degree_id=' + BaseData.degree_id +
                    '&grade_id=' + BaseData.grade_id +
                    '&semester_id=' + BaseData.semester_id +
                    '&dept_option_id=' + BaseData.dept_option_id +
                    '&academic_year_id=' + BaseData.academic_year_id, '_blank');

        });


        $('.selection_blog').hide();

        $('#change_option').on('click', function() {
            $('.selection_blog').slideToggle( "fast" )
        });
        $('#ok_option').on('click', function() {
            filter_table();
            $('.selection_blog').slideToggle( "fast" )
        });



        function setTitle() {

            var seletedText = getSelectedText();

            var title = seletedText.department.trim(' ') +
                    '-'+((seletedText.degree == parseInt('{{\App\Models\Enum\ScoreEnum::Degree_I}}'))? 'I':'T')+
                    seletedText.grade;
            var subTitle = seletedText.academic_year;

            if(seletedText.dept_option != 'Option' && seletedText.dept_option != '') {
                title +=' |'+seletedText.dept_option;
            }

            if(seletedText.semester_id != null && seletedText.semester_id != '') {
                subTitle += ' |~S'+seletedText.semester_id
            }
            $('#class_title').html(title);
            $('#year_name_latin').html(subTitle);

        }

        function showMessage(response, status)
        {

            if(status == true) {

                var div_message = '<div class="alert alert-'+response.type+'">' +
                        '<h4><i class="icon fa fa-info"></i> Total Score Warning!</h4>' +
                        '<p>' +
                        response.message +
                        '</p>' +
                        '</div>';
                $('div#blog_message').html(div_message);
                $('div#all_score_course_annual_table').hide();

            } else {

                $('div#blog_message').html('');
                if(!$('div#all_score_course_annual_table').is(':visible')) {
                    $('div#all_score_course_annual_table').show()
                }
            }
        }
        $('#refresh_score_sheet').on('click', function () {
            filter_table();
            if($('.selection_blog').is(':visible')) {
                $('.selection_blog').slideToggle( "fast" )
            }

        });

        $(document).on("click",".btn-average-final-year", function () {
            var win_final_score = window.open(final_score_url + "?department_id="+$("#filter_dept").val()+"&option_id="+$("#filter_dept_option").val()+"&degree_id="+$("#filter_degree").val()+"&grade_id="+$("#filter_grade").val()+"&academic_year_id="+$("#filter_academic_year").val(), '_blank');
            win_final_score.focus();
        });

        $('#generate_student').on('click', function(e) {

            var baseData  = getBaseData();

            swal({
                title: "Attention!",
                text: "Do you really want to generate student for next year?",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes!",
                cancelButtonText: "No!",
                closeOnConfirm: false,
                closeOnCancel: false
            },
            function(isConfirm) {
                if (isConfirm) {
                    
                    $.ajax({
                        method:'POST',
                        url: '{{route('evaluation.student.generate_next_academic')}}',
                        dataType:'JSON',
                        data:baseData,
                        success:function (result) {

                            swal("Generated", "Students have been successfully graded for next year.", "success");
                        },
                        error:function (response) {

                        }
                    })

                } else {
                    swal("Cancelled", "Students have not been graded for next yeat  :)", "error");
                }
            });
        })


    </script>


@stop