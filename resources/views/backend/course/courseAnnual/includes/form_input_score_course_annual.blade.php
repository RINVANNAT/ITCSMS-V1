
@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.courseAnnuals.title') . ' | ' . trans('labels.backend.courseAnnuals.sub_edit_title'))

@section('page-header')

    <style>
        .space{
            margin-left: 5px;
        }

        .popupdiv{
            height:200px;
            width: 600px;
            background-color: #AED6F1;
            opacity: 60;

        }

        .drop-menu {
            margin-top: 5px;
        }

        .handsontable td{
            color: #000 !important;
        }

        .selection {
            width: 120px;
            font-size: 13pt;
            height: 23px;
            margin-bottom: 5px;

        }
        #filter_academic_year {
            font-size: 10pt;
            height: 23px;
            margin-left: 5px;
        }
        #filter_semester{
            font-size: 10pt;
            height: 23px;
            margin-left: 5px;
        }
        .current_row td{

        gradient(to bottom,rgba(181,209,255,0.34) 0,rgba(181,209,255,0.34) 100%);
            background-image: linear-gradient(rgba(181, 209, 255, 0.5) 0px, rgba(181, 209, 255, 0.341176) 100%);
            background-position-x: initial;
            background-position-y: initial;
            background-size: initial;
            background-repeat-x: initial;
            background-repeat-y: initial;
            background-attachment: initial;
            background-origin: initial;
            background-clip: initial;
            background-color: #fff !important;
        }
    </style>


@endsection

@section('after-style-end')
    <link rel="stylesheet" href="node_modules/jquery-offcanvas/dist/jquery.offcanvas.min.css">
@endsection

@section('content')

    <div class="box box-success">
        <div class="box-header with-border">
            <div class="pull-left drop_selection">
                <select  name="available_course" id="available_course">
                    <option value="">Other Course</option>
                    @foreach($availableCourses as $course_annual_id => $course)
                        @if($course_annual_id == $courseAnnual->id)

                            <option value="{{$course_annual_id}}" selected> {{$course[0]->name_en}} {{ '|'.$course[0]->department_code.'-'.(($course[0]->degree_id ==1)?'I':'T').$course[0]->grade_id }} {{' |S_'.$course[0]->semester_id}}</option>
                        @else
                            <option value="{{$course_annual_id}}"> {{$course[0]->name_en}} {{'|'.$course[0]->department_code.'-'.(($course[0]->degree_id ==1)?'I':'T').$course[0]->grade_id }} {{' |S_'.$course[0]->semester_id}}</option>
                        @endif
                    @endforeach
                </select>

            </div>

            <div id="blog_button">
                <a class="btn btn-primary btn-xs pull-right" id="export_score" href="{{route('course_annual.export_course_score_annual')}}" target="_blank" style="margin-left:5px">Export Score</a>
                @if($courseAnnual->is_allow_scoring)
                    @if($allowCloningScore)
                        <button class="btn btn-success btn-xs pull-right" id="clone_score" data-toggle="tooltip" data-placement="top" title=" The action is to allow you to clone score of this course from responsible department!"  style="margin-left:5px"> Clone-Score </button>
                    @else
                        @if(access()->user()->allow("input-score-without-blocking") || ($courseAnnual->is_allow_scoring && $mode == "edit"))
                            <button class="btn btn-primary btn-xs pull-right" id="save_editted_score" style="margin-left:5px">Save Changes!</button>
                        @endif
                        @if(access()->user()->allow("input-score-without-blocking") || ($courseAnnual->is_allow_scoring && $mode == "edit"))
                            <a href="{{route('course_annual.form_import_score')}}" target="_self" class="btn btn-info btn-xs pull-right" id="import_score" style="margin-left: 5px"> Import Score</a>
                        @endif

                    @endif
                @endif

            </div>

        </div><!-- /.box-header -->

        <div class="box-body">

            <div id="view_message">

                @if($mode == "view")
                    <div class="alert alert-info">
                        <h4><i class="icon fa fa-info"></i> Viewing mode!</h4>
                        <p>
                            This is in viewing mode. You cannot delete or modify this course.
                        </p>
                    </div>
                @elseif(!$courseAnnual->is_allow_scoring)
                    <div class="alert alert-danger">
                        <h4><i class="icon fa fa-info"></i> Scoring is blocked!</h4>
                        <p>
                            This course is blocked for scoring. Please contact student & study affair office if you wish to make change.
                        </p>
                    </div>
                @endif

            </div>

            <div class="blog_body">

            </div>

            <div id="score_table" class="handsontable htColumnHeaders">

            </div>


        </div>

    </div><!--box-->
@stop


@section('after-scripts-end')

    {!! Html::style('plugins/handsontable-test/handsontable.full.min.css') !!}
    {!! Html::script('plugins/handsontable-test/handsontable.full.min.js') !!}
    {!! Html::script('plugins/jpopup/jpopup.js') !!}
    {!! Html::script('score/js/input_score.js') !!}

    <script>

        @if($courseAnnual->is_counted_absence)
                var is_counted_absence = parseInt('{{\App\Models\Enum\ScoreEnum::is_counted_absence}}')
        @else
                var is_counted_absence = 0;
        @endif


        var objectStatus={};
        var array_col_status = {};
        var status=false;// to check user if he/she input string in each cell
        var hotInstance;// declaration of handsontable object
        var celldata = []; // each cell data to render in a table
        var cellChanges = [];// the properties of changes when user edit on column number-absence
        var cellScoreChanges=[]; // when make changes on every score columns
        var sentrow, sentcol; // not use
        var cellIndex=[]; // to get each col and row and check value with colorRenderer
        var colDataArray = []; // column score data key=>value use to pass data to server
        var resitScoreChange = [];
        var rowColor=0;

        // this function is to declare global empty array and we use these empty arrays to store the data when user make change of each cell score value to pass to the sever
        // the main purpose is to get col-change-data and to send them to server by each col
        function declareColumnHeaderDataEmpty() {
            // create empty array by the columns score which user created
            // because we want to store data cell changes by column and send them to the server by one column ...not all columns at once
            for(var i = 0; i < setting.colHeaders.length -1 ; i++) {
                colDataArray[setting.colHeaders[i]] = [];

            }

        }

        // this global variable is to tie each cell of the value the has over the limitted we will render them with specific color...this function will call by cell function

        var colorRenderer = function ( instance, td, row, col, prop, value, cellProperties) {

            Handsontable.renderers.TextRenderer.apply(this, arguments);


            if( $.inArray(prop,['student_id_card', 'student_name', 'student_gender', 'absence', 'average', 'notation', 'resit'])) {


                var explode = prop.split('-');
                var percentage = parseInt(explode[explode.length-1]);
                if($.isNumeric(value)) {

                    if( (value > percentage) || (value < 0)) { // the score should be lower or equal the percentage

                        if((prop != 'notation')) {
                            td.style.backgroundColor = '#cc3300';
                        }
                    }
                } else {

                    if((value == '{{\App\Models\Enum\ScoreEnum::Fraud}}') || (value == '{{\App\Models\Enum\ScoreEnum::Absence}}')) {

                        td.style.backgroundColor = 'gray'

                    } else if(value == '') {
                        td.style.backgroundColor = ''
                    } else if(value == null) {
                        td.style.backgroundColor = ''
                    } else {
                        td.style.backgroundColor = '#cc3300';
                    }
                }
            } else {
                td.style.backgroundColor = '';
            }
            //-----when the average is less than 30
            if(prop == 'average') {

                if($.isNumeric(value)) {
                    if(value < parseInt('{{\App\Models\Enum\ScoreEnum::Pass_Moyenne}}') ) {
                        if(value < parseInt('{{\App\Models\Enum\ScoreEnum::Under_30}}')) {
                            td.style.backgroundColor = '#cc3300';
                        } else {
                            td.style.backgroundColor = '#D2B500';
                        }
                    }
                }
            }
            if(prop == 'num_absence') {

                if($.isNumeric(value) && value >= 0) {
                    if(value > parseInt('{{$courseAnnual->time_course + $courseAnnual->time_td + $courseAnnual->time_tp}}')) {

                        td.style.backgroundColor = '#cc3300';
                    }
                } else if(value == '') {

                    td.style.backgroundColor = '';

                } else if(value == null) {

                    td.style.backgroundColor = '';
                } else {
                    td.style.backgroundColor = '#cc3300';
                }
            }
        };

        // this is the property of the handson table / or configuration

        var setting = {
            AutoColumnSize:true,
            rowHeaders: true,
            manualColumnMove: true,
            filters: true,
            autoWrapRow: true,
            minSpareRows: false,
            stretchH: 'last',
            filters: true,
            dropdownMenu: ['filter_by_condition', 'filter_action_bar'],
            className: "htLeft",
            cells: function (row, col, prop) {

                var cellProperties = {};
                if( ((prop != 'student_id_card')&& (prop != 'student_name')) && ((prop != 'student_gender')&& (prop != 'absence')) && (((prop != 'average')&& (prop != 'notation'))) ) {
                    this.renderer = colorRenderer;
                }
                if(prop == 'average') {
                    this.renderer = colorRenderer;
                }

                if(prop == 'resit') {
                    this.renderer = colorRenderer;
                }

            },

            beforeOnCellMouseDown: function (event,coord, TD) {
                return false;
            },
            afterOnCellMouseDown: function (event,coord, TD) {
                return false;
            },
            afterCellMetaReset: function() {
                return  false;
            },
            afterRowMove: function() {
                return false;
            },
            afterGetCellMeta: function () {
                return false;
            },
            afterSelectionEnd: function() {
                setSelectedRow();
            },

            afterMomentumScroll: function() {

                return false;
            },

            afterColumnResize: function() {
              return false;
            },
            afterChange: function (changes, source) {

                if(changes){

                    $.each(changes, function (index, element) {

                        var change = element;
                        var rowIndex = change[0];
                        var columnIndex = change[1];
                        var oldValue = change[2];
                        var newValue = change[3];
                        var tableData = setting.data;

                        if(columnIndex != 'num_absence' && columnIndex != 'notation' && columnIndex != 'resit') {

                            var colData = findDataAtCol(columnIndex);
                            var explode = columnIndex.split('-');
                            var percentage = parseInt(explode[explode.length-1]);

                            if(($.isNumeric(newValue) || (newValue == '')) || ((newValue == '{{\App\Models\Enum\ScoreEnum::Fraud}}') || (newValue == '{{\App\Models\Enum\ScoreEnum::Absence}}'))) {


                                if((newValue <= percentage) ||  (newValue >= parseInt('{{\App\Models\Enum\ScoreEnum::Zero}}') ) || (newValue == '') || ((newValue == '{{\App\Models\Enum\ScoreEnum::Fraud}}') || (newValue == '{{\App\Models\Enum\ScoreEnum::Absence}}'))) {

                                    var rowData = hotInstance.getData();
                                    var element={};
                                    var score_id = 'score_id'+'_'+columnIndex;
                                    for(var keyIndex=0; keyIndex< tableData.length; keyIndex++) {

                                        $.each(tableData[keyIndex],function(i, value){

                                            if(rowData[rowIndex][0] == value) {

                                                if(newValue <= percentage) {
                                                    element = {
                                                        score_id: tableData[keyIndex][score_id],
                                                        score: newValue,
                                                        score_absence: tableData[keyIndex]['absence'],
                                                        course_annual_id: $('select[name=available_course] :selected').val(),
                                                        student_annual_id: tableData[keyIndex]['student_annual_id']
                                                    };

                                                } else if(!$.isNumeric(newValue))  {

//                                                    notify('error', 'Danger', 'Score must be less than or equal to '+percentage )
                                                    element = {
                                                        score_id: tableData[keyIndex][score_id],
                                                        score: newValue,
                                                        score_absence: tableData[keyIndex]['absence'],
                                                        course_annual_id: $('select[name=available_course] :selected').val(),
                                                        student_annual_id: tableData[keyIndex]['student_annual_id']
                                                    };
                                                } else {
                                                    element = {
                                                        score_id: tableData[keyIndex][score_id],
                                                        score: 0,
                                                        score_absence: tableData[keyIndex]['absence'],
                                                        course_annual_id: $('select[name=available_course] :selected').val(),
                                                        student_annual_id: tableData[keyIndex]['student_annual_id']
                                                    };
                                                }
                                            }
                                        });

                                    }

                                    if(oldValue != newValue) {
                                        colDataArray[columnIndex].push(element) // cell changes data by each column score use to pass data to server
                                        cellScoreChanges.push(element); // use this cell score change to test if user has made any changes
                                    }

//
                                }
                            }

                            var count = 0;
                            var numAbs = 0;
                            checkIfStringValExist(colData, columnIndex, count, numAbs, percentage);
                        }

                        if(columnIndex == 'notation') {
                            var rowData = hotInstance.getData();
                            var route = '{{route('course_annual.save_each_cell_notation')}}';
                            var baseData ={};

                            for(var k=0; k< tableData.length; k++) {
                                $.each(tableData[k],function(i, value){
                                    if(rowData[rowIndex][0] == value) {
                                         baseData = {
                                            course_annual_id: $('select[name=available_course] :selected').val(),
                                            student_annual_id: tableData[k]['student_annual_id'],
                                            description: newValue
                                        };
                                    }
                                });
                            }
                            $.ajax({
                                type: 'POST',
                                url: route,
                                data: baseData,
                                dataType: "json",
                                success: function(resultData) {

                                }
                            });
                        } else {
                            cellIndex.push(newValue)
                        }

                        if(columnIndex == 'num_absence') {
                            {{--console.log(parseInt('{{$courseAnnual->time_course + $courseAnnual->time_td + $courseAnnual->time_tp}}'));--}}
                            var colData = findDataAtCol('Abs');
                            if($.isNumeric(newValue) || (newValue == '')) {
                                if(newValue <= parseInt('{{$courseAnnual->time_course + $courseAnnual->time_td + $courseAnnual->time_tp}}')) {

                                    var rowData = hotInstance.getData();
                                    for(var keyIndex=0; keyIndex< tableData.length; keyIndex++) {
                                        $.each(tableData[keyIndex],function(i, value){
                                            if(rowData[rowIndex][0] == value) {//rowData[rowIndex][0] with the row data we get rowDat by Key rowIndex then we will get the student_id_card
                                                element = {
                                                    num_absence: newValue,
                                                    student_annual_id: tableData[keyIndex]['student_annual_id'],
                                                    course_annual_id: $('select[name=available_course] :selected').val()
                                                };
                                            }
                                        });
                                    }
                                    cellChanges.push(element);
                                }
                            }
                            var count = 0;
                            var numAbs = 0;
                            checkIfStringValExist(colData,'Absence', count, numAbs, parseInt('{{$courseAnnual->time_course + $courseAnnual->time_td + $courseAnnual->time_tp}}'));
                        }

                        if(columnIndex == 'resit') {
                            var colData = findDataAtCol('Resit-Score');

                            if(($.isNumeric(newValue) || (newValue == '')) || ((newValue == '{{\App\Models\Enum\ScoreEnum::Fraud}}') || (newValue == '{{\App\Models\Enum\ScoreEnum::Absence}}'))) {

                                if((newValue <= parseInt('{{ \App\Models\Enum\ScoreEnum::Pass_Moyenne }}')) ||  (newValue >= parseInt('{{\App\Models\Enum\ScoreEnum::Zero}}') ) || (newValue == '') || ((newValue == '{{\App\Models\Enum\ScoreEnum::Fraud}}') || (newValue == '{{\App\Models\Enum\ScoreEnum::Absence}}'))) {

                                    var rowData = hotInstance.getData();
                                    for(var keyIndex=0; keyIndex< tableData.length; keyIndex++) {
                                        $.each(tableData[keyIndex],function(i, value){
                                            if(rowData[rowIndex][0] == value) {//rowData[rowIndex][0] with the row data we get rowDat by Key rowIndex then we will get the student_id_card
                                                element = {
                                                    resit_score: newValue,
                                                    student_annual_id: tableData[keyIndex]['student_annual_id'],
                                                    course_annual_id: $('select[name=available_course] :selected').val()
                                                };
                                            }
                                        });
                                    }
                                    resitScoreChange.push(element);
                                }
                            }

                            var count = 0;
                            var numAbs = 0;
                            checkIfStringValExist(colData,'Resit-Score', count, numAbs, parseInt('{{ \App\Models\Enum\ScoreEnum::Pass_Moyenne }}'));

                        }
                    });
                }
            },
        };

        function checkIfStringValExist(colData, colName, count, numAbs, valToCompare) {

            var arrayNull=[];
            for(var check =0; check < colData.length; check++) {

                if(colName != 'Absence') {

                    if($.isNumeric(colData[check]) && (parseInt(colData[check]) >= 0)) {
                        count++;
                        if((colData[check] <= valToCompare) ) {
                            numAbs++;
                        }
                    } else if( ((colData[check] == null) || (colData[check] == ''))  || ((colData[check] == '{{\App\Models\Enum\ScoreEnum::Fraud}}') || (colData[check] == '{{\App\Models\Enum\ScoreEnum::Absence}}'))) {// to check if he/she deose not input any value or input only empty string
                        arrayNull.push(colData[check])
                    }
                } else {

                    if($.isNumeric(colData[check]) && (parseInt(colData[check]) >= 0)) {
                        count++;
                        if((colData[check] <= valToCompare) ) {
                            numAbs++;
                        }
                    } else if( ((colData[check] == null) || (colData[check] == '')) ) {// to check if he/she deose not input any value or input only empty string
                        arrayNull.push(colData[check])
                    }

                }

            }

            if((parseInt(count) + arrayNull.length) == colData.length) {

//                console.log((parseInt(numAbs) + arrayNull.length) +'=='+ colData.length);

                if((parseInt(numAbs) + arrayNull.length) == colData.length) {

                    objectStatus.status = true;

                } else {
                    objectStatus.status = false;
                    objectStatus.val_to_compare = valToCompare;
                    objectStatus.colName = colName;
                }
            } else {
                objectStatus.status = false;
                objectStatus.val_to_compare = valToCompare;
                objectStatus.colName = colName;
            }
            array_col_status[colName] = objectStatus.status;

//            console.log(array_col_status);
        }

        function findDataAtCol(colIndex) {

            var col= 0;
            var header = setting.colHeaders;
            for( var ja = 0; ja < header.length; ja++ ) {
                if(setting.colHeaders[ja] == colIndex) {
                    col = ja;
                }
            }
            return hotInstance.getDataAtCol(col);
        }

        $(document).ready(function() {

            toggleLoading(true);

            var getDataBaseUrl = '{{route('admin.course.get_data_course_annual_score')}}';

            //--------------- when document ready call ajax
            $.ajax({
                type: 'GET',
                url: getDataBaseUrl,
                data: {course_annual_id: '{{$courseAnnualId}}' },
                dataType: "json",
                success: function(resultData) {
                    toggleLoading(false);
                    if(resultData.status) {

                        init_input_score_table(resultData);
                    } else {

                        swal({
                            title: "Confirm",
                            text: resultData.message,
                            type: "info",
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "Close",
                            closeOnConfirm: true
                        }, function(confirmed) {
                            if (confirmed) {

                            }
                        });

                    }
                }
            });
        });

        $(document).on('click','#save_editted_score', function() {


            var course_annual_id = $('#available_course :selected').val();

            $.each(array_col_status, function(key, val) {
                if(val == false) {
                    objectStatus.status = val;
                    objectStatus.colName = key;
                }
            });

            var url = '{{route('admin.course.save_number_absence')}}';

            if(cellIndex.length > 0) {

                if(objectStatus.status == true) {

                    if(cellChanges.length > 0 || cellScoreChanges.length >0 || resitScoreChange.length > 0) {
                        swal({
                            title: "Confirm",
                            text: "Save Changes?",
                            type: "info",
                            showCancelButton: true,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "Yes",
                            closeOnConfirm: true
                        }, function(confirmed) {

                            if (confirmed) {

                                toggleLoading(true);

                                if(cellScoreChanges.length > 0) {// save each score

                                    //recursive function fo send the request by the column data array

                                    function sendRequest (index, message) {

                                        var saveBaseUrl = '{{route('admin.course.save_score_course_annual')}}';

                                        if(index < setting.colHeaders.length -1) {

                                            if(colDataArray[setting.colHeaders[index]].length > 0 ) {

                                                $.ajax({
                                                    type: 'POST',
                                                    url: saveBaseUrl,
                                                    data: {data:colDataArray[setting.colHeaders[index]], percentage: setting.colHeaders[index] },
                                                    dataType: "json",
                                                    success: function(resultData) {
                                                        if(resultData.status) {
                                                            colDataArray[setting.colHeaders[index]] = [];
                                                            index++;
                                                            updateSettingHandsontable(resultData.handsontableData);
                                                            sendRequest(index);
                                                        } else {
                                                            notify('error', resultData.message, 'Alert');
                                                        }
                                                    },
                                                    error: function(error) {
                                                            colDataArray[setting.colHeaders[index]] = [];
                                                            notify('error', 'Something went wrong!');
                                                    }
                                                })
                                            } else {
                                                index++;
                                                sendRequest(index, message);
                                            }
                                        } else {

                                            toggleLoading(false);
                                            notify('success', 'Score Saved!', 'Info');
                                        }
                                    }


                                    if(is_counted_absence == parseInt('{{\App\Models\Enum\ScoreEnum::is_counted_absence}}') ) {
                                        var index = 5; // ---count header column --with absence
                                    } else {
                                        var index = 3; // ---count header column-- without absence
                                    }

                                    var message = null;
                                    sendRequest(index, message);
                                    cellScoreChanges=[];
                                }

                                if(cellChanges.length > 0) { // save each number absence

                                    var url = '{{route('admin.course.save_number_absence')}}';
                                    $.ajax({
                                        type: 'POST',
                                        url: url,
                                        data: {baseData:cellChanges},
                                        dataType: "json",
                                        success: function(resultData) {

                                            toggleLoading(false);

                                            if(resultData.status) {
                                                notify('success', resultData.message, 'Info')
                                                updateSettingHandsontable(resultData.handsonData);

                                            } else {
                                                notify('error', resultData.message, 'Attention')
                                                updateSettingHandsontable(resultData.handsonData);
                                            }
                                            cellChanges=[];
                                        },
                                        error:function(error) {
                                            notify('error', 'Something went wrong!')
                                        }
                                    });
                                }

                                if(resitScoreChange.length > 0) {

                                    var url = '{{route('admin.score.store_resit')}}';
                                    $.ajax({
                                        type: 'POST',
                                        url: url,
                                        data: {baseData:resitScoreChange},
                                        dataType: "json",
                                        success: function(resultData) {
                                            toggleLoading(false);

                                            if(resultData.status) {
                                                notify('success', resultData.message, 'Info');

                                                updateSettingHandsontable(resultData.handsontableData);

                                            } else {
                                                notify('error', resultData.message, 'Attention')
                                                updateSettingHandsontable(resultData.handsontableData);
                                            }
                                            resitScoreChange=[]; // set to empty array
                                        }
                                    });
                                }
                            }
                        });
                    } else {
                        notify('error', 'There are no changes!!', 'Info');
                    }
                } else {

                    swal({
                        title: "Attention",
                        text: objectStatus.colName+" value must be: 0 <= X <= "+ objectStatus.val_to_compare + ', No String Allowed!' ,
                        type: "warning",
                        confirmButtonColor: "red",
                        confirmButtonText: "Close",
                        closeOnConfirm: true
                    }, function(confirmed) {
                        if (confirmed) {

                        }
                    });
                }
            }

        });

        window.onbeforeunload = function(e){

            if(cellChanges.length > 0) {
                return 'You have not yet save your changes!!';
            }
            if(cellScoreChanges.length > 0) {
                return 'You have not yet save your changes!!';
            }
        };

        $('#get_average').on('click', function() {
           var check = false;
            if(check) {
                if(cellChanges.length > 0 || cellScoreChanges.length > 0) {
                    notify('error', 'info', 'Please Save Your Changes Before Getting Average!!!')
                } else {


                    var getAverageBaseurl = '{{route('admin.course.get_average_score', $courseAnnualId)}}';

                    swal({
                        title: "Confirm",
                        text: "Calculate Score?",
                        type: "info",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Yes",
                        closeOnConfirm: true
                    }, function(confirmed) {
                        if (confirmed) {

                            $.ajax({
                                type: 'POST',
                                url: getAverageBaseurl,
                                data: {data: setting.data, colHeader:setting.colHeaders},
                                dataType: "json",
                                success: function(resultData) {
                                    notify('success', 'info', 'Score Calculated!!');
                                    setting.data = resultData.data;
                                    setting.colHeaders = resultData.columnHeader;
                                    setting.columns = resultData.columns;
                                    hotInstance = new Handsontable(jQuery("#score_table")[0], setting);
                                }
                            });
                        }
                    });
                }
            }
        });

        $('select[name=available_course]').on('change', function() {

            var baseData = {
                course_annual_id: $(this).val()
            }

            checkCourse($(this).val());
            switch_course(baseData);
            list_group($(this).val());

        });

        function checkCourse(course_id)
        {
            $.ajax({
                type: 'POST',
                url: '{{route('course_annual.is_allow_cloning_course')}}',
                data: {_token:'{{csrf_token()}}', course_annual_id: course_id},
                dataType: "json",
                success: function(resultData) {
                    if(resultData.allow_scoring) {

                        if($('div#view_message').is(':visible')) {
                            $('div#view_message').html('')
                        }

                        if(resultData.status) {// check if the course of department vocational SA or SF

                            if(!$('#clone_score').is(':visible')) {
                                $('#blog_button').append('<button class="btn btn-success btn-xs pull-right" id="clone_score" data-toggle="tooltip" data-placement="top" title=" The action is to allow you to clone score of this course from responsible department!"  style="margin-left:5px"> Clone-Score </button>')
                            }

                            @if(access()->user()->allow("input-score-without-blocking") || ($courseAnnual->is_allow_scoring && $mode == "edit"))
                                if($('#save_editted_score').is(':visible')) {
                                   $('#blog_button').find('#save_editted_score').remove();
                                }
                            @endif

                            @if(access()->user()->allow("input-score-without-blocking") || ($courseAnnual->is_allow_scoring && $mode == "edit"))//import_score
                                if($('#import_score').is(':visible')) {
                                    $('#blog_button').find('#import_score').remove();
                                }
                            @endif

                        } else {

                            if($('#clone_score').is(':visible')) {
                                $('#blog_button').find('#clone_score').remove();
                            }

                            @if(access()->user()->allow("input-score-without-blocking") || ($courseAnnual->is_allow_scoring && $mode == "edit"))
                            if(!$('#save_editted_score').is(':visible')) {
                                $('#blog_button').append('<button class="btn btn-primary btn-xs pull-right" id="save_editted_score" style="margin-left:5px">Save Changes!</button>')
                            }
                            @endif
                            @if(access()->user()->allow("input-score-without-blocking") || ($courseAnnual->is_allow_scoring && $mode == "edit"))//import_score
                            if(!$('#import_score').is(':visible')) {
                                $('#blog_button').append('<a href="{{route('course_annual.form_import_score')}}" target="_self" class="btn btn-info btn-xs pull-right" id="import_score" style="margin-left: 5px"> Import Score</a>')
                            }
                            @endif

                        }
                    } else{


                        var div = '<div class="alert alert-danger">' +
                                '<h4><i class="icon fa fa-info"></i> Scoring is blocked!</h4>' +
                                'This course is blocked for scoring. Please contact student & study affair office if you wish to make change.'+
                                '</p>' +
                                '</div>';

                        $('div#view_message').html(div)
                        if($('#save_editted_score').is(':visible')) {
                            $('#blog_button').find('#save_editted_score').remove();
                        }
                        if($('#import_score').is(':visible')) {
                            $('#blog_button').find('#import_score').remove();
                        }

                        if($('#clone_score').is(':visible')) {
                            $('#blog_button').find('#clone_score').remove();
                        }
                    }
                },
                error:function(error) {
                    notify('error', 'Something went wrong', 'Server Error')
                }
            });

        }

        $(document).ready(function() {
            if(val = $('select[name=available_course] :selected').val()) {
                list_group(val);
            }
        });

        function switch_course(baseData) {

            toggleLoading(true);

            $.ajax({
                type: 'POST',
                url: '{{route('course_annual.ajax_switch_course_annual')}}',
                data: baseData,
                dataType: "json",
                success: function(resultData) {

                    toggleLoading(false);
                    init_input_score_table(resultData);

                }
            });
        }

        function list_group(course_annual_id) {

            $.ajax({
                type: 'GET',
                url: '{{route('course.list_group_by_course_annual_id')}}',
                data: {course_annual_id:course_annual_id},
                dataType: "html",
                success: function(resultData) {

                    if($('select[name=group_name]').is(':visible')) {
                        $('select[name=group_name]').html(resultData);
                    } else {
                        $('select[name=available_course]').after(resultData);
                        $('select[name=group_name]').addClass('space')
                    }

                }
            });
        }

        $(document).on('change', 'select[name=group_name]', function() {
            var baseData = {
                course_annual_id: $('select[name=available_course] :selected').val(),
                group_id : $(this).val()
            }
            switch_course(baseData);

        })

        $('#export_score').on('click', function(e) {

            e.preventDefault();
            var colHeaders = setting.colHeaders
            if(colHeaders.length > 3) {// we exactly knew the fixed column header so we just check if the couse has added the score column
                var url = $(this).attr('href');
                window.open(url+'?course_annual_id='+ $('select[name=available_course] :selected').val()+'&col_headers='+colHeaders+'&group_id='+$('select[name=group_name] :selected').val())

            } else {

                swal({
                    title: "Attention",
                    text: 'Please Add Score Before Export!' ,
                    type: "warning",
                    confirmButtonColor: "red",
                    confirmButtonText: "Close",
                    closeOnConfirm: true
                }, function(confirmed) {
                    if (confirmed) {

                    }
                });

            }

        });

        $(document).on('click','#import_score', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            var colHeaders = setting.colHeaders
            if(colHeaders.length > parseInt('{{\App\Models\Enum\ScoreEnum::Col_Header}}')) {// we exactly knew the fixed column header so we just check if the couse has added the score column
                window.open(url+'?course_annual_id='+$('select[name=available_course] :selected').val()+'&group_id='+$('select[name=group_name] :selected').val(), '_self');

            } else {
                swal({
                    title: "Attention",
                    text: 'Please Add Score Before Import!' ,
                    type: "warning",
                    confirmButtonColor: "red",
                    confirmButtonText: "Close",
                    closeOnConfirm: true
                }, function(confirmed) {
                    if (confirmed) {
                        // do some staff if you want ---
                    }
                });

            }
        });

        @if(session('status_student'))
                var str = ' ';
                @foreach(session('status_student') as $student)
                        str = str + '{{$student['student_id']}}'+ ' ';
                @endforeach

                 swal({
                    title: "Attention",
                    text: 'Your file has imported but the student with this Id: '+ str +' cannot find in the system' ,
                    type: "warning",
                    confirmButtonColor: "red",
                    confirmButtonText: "Close",
                    closeOnConfirm: true
                }, function(confirmed) {
                    if (confirmed) {
                        // do some staff if you want ---
                    }
                });
        @endif

         @if(session('status'))
            notify('success', '{{session('status')}}', 'Info')
        @endif


        $(document).on('click', '#clone_score', function(e) {

            var count_group = $('select[name=group_name] option').length;
            var url = '{{route('course_annual.popup_clone_score_panel')}}'

           /* if(count_group >= parseInt('{{\App\Models\Enum\GroupEnum::TEN}}')) {
                PopupCenterDual(url+'?course_annual_id='+$('select#available_course :selected').val(),'Popup Clone Score','730','450');
            } else {


            }*/

            var baseData = {
                course_annual_id: $('select#available_course :selected').val(),
                group_id: $('select#group_name :selected').val()
            }

            toggleLoading(true);
            cloning(baseData, status = false);
        });


        function cloning(baseData, status ) {

            $.ajax({
                type: 'GET',
                url: '{{route('course_annual.clone_score')}}',
                data: baseData,
                dataType: "JSON",
                success: function(resultData) {

                    var div_message = '<div class="alert alert-warning">' +
                            '<h4><i class="icon fa fa-info"></i> Clone Score Warning!</h4>' +
                            '<p>' +
                            resultData.message +
                            '</p>' +
                            '</div>';


                    if(resultData.status) {
                        toggleLoading(status)

                        notify('success', resultData.message, 'Clone Score!')
                        switch_course(baseData);
                        $('div.blog_body').html('');
                        if(!$('div#score_table').is(':visible')) {
                            $('div#score_table').show()
                        }
                    } else {
                        //notify('error', resultData.message, 'Clone Score!')

                        $('div#score_table').hide()
                        toggleLoading(status)
                        if(resultData.edit_url) {

                            var btn_back = '<a href="' +resultData.edit_url+'" class="btn btn-xs btn-danger">'+
                                    'Update Course Panel' +
                                    '</a>';
                            $('div.blog_body').html(div_message + btn_back);
                        } else {
                            var btn_back = '<a href="/admin/course/course_annual" class="btn btn-xs btn-danger">'+
                                    'Close' +
                                    '</a>';
                            $('div.blog_body').html(div_message + btn_back);
                        }
                    }
                },
                error:function(error) {

                }
            });
        }

    </script>
@stop