@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.courseAnnuals.title') . ' | ' . trans('labels.backend.courseAnnuals.sub_edit_title'))

@section('page-header')

    <style>

        .popupdiv{
            height:200px;
            width: 600px;
            background-color: #AED6F1;
            opacity: 60;

        }

        .drop-menu {
            margin-top: 5px;
        }

        .pop_margin{
            margin-right: 30px;
        }
        .popup_wrapper .popup {
            background: #08B65F;
            width: 400px;
            border-radius: 3px;
            border: 0;
            box-shadow: 0 5px 20px rgba(0,0,0,1);
            outline: 1px solid #ddd;
            outline: 1px solid rgba(0,0,0,.03);
            font-family: "Roboto", sans-serif;

        }
        .popup_wrapper .popup .popup_title {
            color:#FFF;
            font-weight:bold;
            line-height: 45px;
            padding: 0 20px;
            margin-bottom: 10px;
            min-height: 20px;
        }
        .popup_wrapper .popup .popup_title h1,
        .popup_wrapper .popup .popup_title h2,
        .popup_wrapper .popup .popup_title h3,
        .popup_wrapper .popup .popup_title h4,
        .popup_wrapper .popup .popup_title h5,
        .popup_wrapper .popup .popup_title h6 {
            margin: 0;
        }
        .popup_wrapper .popup .popup_title h1 {
            font-size: 22px;
        }
        .popup_wrapper .popup .popup_title h2 {
            font-size: 18px;
        }
        .popup_wrapper .popup .popup_title h3 {
            font-size: 16px;
        }
        .popup_wrapper .popup .popup_title h4 {
            font-size: 14px;
        }
        .popup_wrapper .popup .popup_title h5 {
            font-size: 12px;
        }
        .popup_wrapper .popup .popup_title h6 {
            font-size: 12px;
        }
        .popup_wrapper .popup_close {
            position: absolute;
            top: 5px;
            right: 6px;
            width: 20px;
            height: 20px;
            border-radius: 3px;
            cursor: pointer;
            font-family: "Roboto", sans-serif;
            font-size: 10px;
            text-align: center;
            line-height: 17px;
            color:#FFF;
        }
        .popup_wrapper .popup_close svg {
            position: absolute;
            top: 0;
            left: 0;
            fill: #FFF;
            border-radius: 3px;
            color:#FFF;
        }
        .popup_wrapper .popup_content {
            color:#FFF;
            padding: 0 20px;
        }
        .popup_wrapper .popup p {
            font-size: 16px;
            color: #FFF;
        }
        .popup_wrapper .popup p + p {
            margin: 16px 0 0 0;
        }
        .popup_wrapper .popup input,
        .popup_wrapper .popup textarea {

            outline: 0;
            font-size:15px;

            padding:5px;
            line-height: 20px;
            padding: 8px 8px;
            color: #000;
            box-shadow: none;
        }
        .popup_wrapper .popup input:focus,
        .popup_wrapper .popup textarea:focus {

        }
        .popup_wrapper .popup p + textarea,
        .popup_wrapper .popup p + input,
        .popup_wrapper .popup input + textarea,
        .popup_wrapper .popup textarea + input {
            margin: 16px 0 0 0;
        }
        .popup_wrapper .popup .popup_buttons {
            float: left;
            width: 100%;
            box-sizing: border-box;
            padding: 0 10px 0 0;
            min-height: 10px;
        }
        b.author{
            font-family: "Roboto", sans-serif;
            font-size: 14px;
            font-weight: 700;
            color: #FFF;
            line-height: 20px;
            padding: 0 10px;
            margin: 20px 10px 20px 0;
            border: 0;
            border-radius: 3px;
            background: none;
            outline: 0;
        }
        b.author{
            font-weight: normal;
        }
        .popup_wrapper .popup .popup_buttons button {
            font-family: "Roboto", sans-serif;
            font-size: 14px;
            color: #FFF;
            line-height: 20px;
            padding: 0 10px;
            margin: 20px 10px 20px 0;
            border: 0;
            border-radius: 3px;
            background: none;
            float: right;
            cursor: pointer;
            outline: 0;
            text-decoration: underline;
        }
        .popup_wrapper .popup .popup_close:hover,
        .popup_wrapper .popup .popup_close:hover svg,
        .popup_wrapper .popup .popup_buttons button:hover {

        }
        .popup_wrapper .popup .popup_close:active,
        .popup_wrapper .popup .popup_close:active svg,
        .popup_wrapper .popup .popup_buttons button:active {
            text-decoration: underline;
        }
        .popup_wrapper .popup .popup_buttons button.ok {
            text-decoration: underline;
        }
        .popup_wrapper .popup .popup_buttons button.no {
            text-decoration: underline;
        }
        /* stretchTop & stretchBottom */
        .popup_wrapper[data-position="stretchTop"] .popup,
        .popup_wrapper[data-position="stretchBottom"] .popup {
            border-radius: 0;
        }
        /* stretchLeft & stretchRight */
        .popup_wrapper[data-position="stretchLeft"] .popup,
        .popup_wrapper[data-position="stretchRight"] .popup {
            border-radius: 0;
        }
        .popup_wrapper[data-position="stretchLeft"] .popup  .popup_title,
        .popup_wrapper[data-position="stretchRight"] .popup .popup_title {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
        }
        .popup_wrapper[data-position="stretchLeft"] .popup  .popup_buttons,
        .popup_wrapper[data-position="stretchRight"] .popup .popup_buttons {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
        }
        .popup_wrapper[data-position="stretchLeft"] .popup .popup_content,
        .popup_wrapper[data-position="stretchRight"] .popup .popup_content {
            position: absolute;
            top: 74px;
            left: 0;
            right: 0;
            bottom: 76px;
            overflow: auto;
        }
        /* Overflow */
        .popup_wrapper.popup_overflow_y,
        .popup_wrapper.popup_overflow_y .popup {
            height: 100% !important;
            top: 0 !important;
        }
        .popup_wrapper.popup_overflow_x:not([data-position="stretchTop"]):not([data-position="stretchBottom"]),
        .popup_wrapper.popup_overflow_x:not([data-position="stretchTop"]):not([data-position="stretchBottom"]) .popup {
            width: 100% !important;
            height: 100% !important;
            top: 0 !important;
            left: 0 !important;
            border: 0;
            border-radius: 0;
        }
        .popup_wrapper.popup_overflow_y .popup .popup_title,
        .popup_wrapper.popup_overflow_x:not([data-position="stretchTop"]):not([data-position="stretchBottom"]) .popup .popup_title {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
        }
        .popup_wrapper.popup_overflow_x:not([data-position="stretchTop"]):not([data-position="stretchBottom"]) .popup .popup_title {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            cursor: inherit !important;
        }
        .popup_wrapper.popup_overflow_y .popup .popup_buttons,
        .popup_wrapper.popup_overflow_x:not([data-position="stretchTop"]):not([data-position="stretchBottom"]) .popup .popup_buttons {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
        }
        .popup_wrapper.popup_overflow_y .popup .popup_content,
        .popup_wrapper.popup_overflow_x:not([data-position="stretchTop"]):not([data-position="stretchBottom"]) .popup .popup_content {
            position: absolute;

            top: 74px;
            left: 0;
            right: 0;
            bottom: 76px;
            overflow: auto;
        }
        /* ScrollTop */
        .popup_wrapper .popup .popup_title:after,
        .popup_wrapper .popup .popup_buttons:before,
        .popup_wrapper .popup .popup_title:after,
        .popup_wrapper .popup .popup_buttons:before {
            content: "";
            position: absolute;
            left: 0;
            right: 0;
            height: 10px;
            pointer-events: none;
            opacity: .1;
            transition: margin-top .3s,
            margin-bottom .3s,
            height .3s;
        }
        .popup_wrapper .popup .popup_title:after,
        .popup_wrapper .popup .popup_title:after {
            margin-bottom: -20px;
            bottom: 0;
            background: -webkit-linear-gradient(rgba(0,0,0,1) 0%, rgba(0,0,0,0) 100%);
            background: -moz-linear-gradient(rgba(0,0,0,1) 0%, rgba(0,0,0,0) 100%);
            background: -o-linear-gradient(rgba(0,0,0,1) 0%, rgba(0,0,0,0) 100%);
            background: linear-gradient(rgba(0,0,0,1) 0%, rgba(0,0,0,0) 100%);
            z-index: 1;
        }
        .popup_wrapper .popup .popup_buttons:before,
        .popup_wrapper .popup .popup_buttons:before {
            margin-top: -10px;
            top: 0;
            background: -webkit-linear-gradient(rgba(0,0,0,0) 0%, rgba(0,0,0,1) 100%);
            background: -moz-linear-gradient(rgba(0,0,0,0) 0%, rgba(0,0,0,1) 100%);
            background: -o-linear-gradient(rgba(0,0,0,0) 0%, rgba(0,0,0,1) 100%);
            background: linear-gradient(rgba(0,0,0,0) 0%, rgba(0,0,0,1) 100%);
        }
        .popup_wrapper.popup_scroll_top .popup .popup_title:after,
        .popup_wrapper.popup_scroll_bottom .popup .popup_buttons:before,
        .popup_wrapper.popup_scroll_top .popup .popup_title:after,
        .popup_wrapper.popup_scroll_bottom .popup .popup_buttons:before {
            height: 0;
            opacity: 0;
        }
        .popup_wrapper.popup_scroll_top .popup .popup_title:after,
        .popup_wrapper.popup_scroll_top .popup .popup_title:after {
            margin-bottom: -10px;
        }
        .popup_wrapper.popup_scroll_bottom .popup .popup_buttons:before,
        .popup_wrapper.popup_scroll_bottom .popup .popup_buttons:before {
            margin-top: 0;
        }


    </style>

@endsection

@section('content')

    <div class="box box-success">

        <div class="box-header with-border">
            <h3 class="box-title">Subject: <span class="label label-success">{{$courseAnnual->name_en}}</span></h3>
            <div class="btn-group pull-right">

                <button class="btn btn-primary btn-xs" id="save_editted_score" style="margin-right:5px">Save Changes!</button>
                <button type="button" class="btn btn-warning btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    Actions <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" role="menu">

                    <li class="drop-menu"> <a href="#"  id="add_column"> <i class="fa fa-plus"> Add Score</i></a></li>
                    <li class="drop-menu"> <a href="#"  id="get_average"> <i class="fa fa-circle-o-notch"> Generate Average</i></a></li>


                </ul>
            </div><!--btn group-->
        </div><!-- /.box-header -->

        <div class="box-body">

            <div id="score_table" class="handsontable htColumnHeaders">

            </div>
        </div>

    </div><!--box-->


@stop


@section('after-scripts-end')

    {!! Html::style('plugins/handsontable-test/handsontable.full.min.css') !!}
    {!! Html::script('plugins/handsontable-test/handsontable.full.min.js') !!}
    {!! Html::script('plugins/jpopup/jpopup.js') !!}

    {{--myscript--}}

    <script>



        function ajaxRequest (method, baseUrl, baseData) {
            var result=null;
            var ajax = $.ajax(result,{
                type: method,
                url: baseUrl,
                data: baseData,
                dataType: "json",
                done: function(resultData) {
                    result = resultData;
                    if(resultData.status == true) {



                        cellScoreChanges=[];
                        cellIndex = [];
//                        notify('success', 'info', resultData.message);

                    } else {
                        return resultData;
//                        notify('error', 'info', resultData.message);
                    }
                }

            });

        }

        var hotInstance;// declaration of handsontable object
        var celldata = []; // each cell data to render in a table
        var cellChanges = [];// the properties of changes when user edit on column number-absence
        var cellScoreChanges=[]; // when make changes on every score columns
        var sentrow, sentcol; // not use
        var cellIndex=[]; // to get each col and row and check value with colorRenderer
        var colDataArray = []; // column score data key=>value use to pass data to server

        // this function is to declare global empty array and we use the empty arrays to store the data when user make change of each cell score value to pass to the sever
        function declareColumnHeaderDataEmpty() {
            // create empty array by the columns score which user created
            // because we want to store data cell changes by column and send them to the server by one column ...not all columns at once
            for(var i = 5; i < setting.colHeaders.length -1 ; i++) {
                colDataArray[setting.colHeaders[i]] = [];
            }
        }
        // use this function to update the table when success of ajax request
        function updateSettingHandsontable(resultData) {
            setting.data = resultData.data;
            setting.colHeaders = resultData.columnHeader;
            setting.columns = resultData.columns;
            hotInstance.updateSettings({
                data: resultData['data'],
                colHeaders:resultData['columnHeader'],
                columns:resultData['columns']
            });
        }


        // this global variable is to tie each cell of the value the has over the limitted we will render them with specific color...this function will call by cell function

        var colorRenderer = function ( instance, td, row, col, prop, value, cellProperties) {

            Handsontable.renderers.TextRenderer.apply(this, arguments);

            if(cellIndex.length >0) {
//                console.log(cellIndex);
                for(var i =0; i< cellIndex.length; i++) {
                    if(cellIndex[i]['row'] == row) {

                        if( setting.columns[col]['data'] == cellIndex[i]['col']) {

                            if(value > 100) { // the score should be lower or equal 100
                                td.style.backgroundColor = 'red';
                            } else {
                                td.style.backgroundColor = '#FEFFB0';
                            }

                        }
                    }
                }
            } else {
                td.style.backgroundColor = '';
            }
            //-----when the average is less than 50
            if(col == setting.colHeaders.length-1) {
                if(value != null) {
                    if(value < 30) {
                        td.style.backgroundColor = '#FF8D74';
                    }
                }
            }
        };

        // this is the property of the handson table / or configuration
        var table_size;
        $(window).on('load resize', function(){
            table_size = $('.box-body').width();
        });
        var setting = {
            AutoColumnSize:true,
            rowHeaders: true,
            manualColumnMove: true,
            filters: true,
            autoWrapRow: true,
            minSpareRows: false,
            stretchH: 'last',
            height:800,
            width: table_size,
            filters: true,
            dropdownMenu: ['filter_by_condition', 'filter_action_bar'],
//            hiddenColumns: {
//                columns: [0],
//                indicators: true
//            },
            className: "htLeft",
            cell: celldata,
            cells: function (row, col, prop) {


                if (row === sentrow) {
                    this.renderer = colorRenderer;
                }
                if (col === sentcol) {
                    this.renderer = colorRenderer;
                }
                this.renderer = colorRenderer;
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
                        if(columnIndex != 'num_absence') {

                            var rowData = hotInstance.getData();
                            var element={};
                            var score_id = 'score_id'+'_'+columnIndex;
                            for(var keyIndex=0; keyIndex< tableData.length; keyIndex++) {
                                $.each(tableData[keyIndex],function(i, value){
//                                    console.log(index+'--->'+value);
                                    if(rowData[rowIndex][0] == value) {
                                        element = {
                                            score_id: tableData[keyIndex][score_id],
                                            score: newValue,
                                            score_absence: tableData[keyIndex]['absence'],
                                            course_annual_id: '{{$courseAnnualID}}'
                                        };
                                    }
                                });

                            }
                            colDataArray[columnIndex].push(element) // cell changes data by each column score use to pass data to server
                            cellScoreChanges.push(element); // use this cell score change to test if user has made any changes
                        }

                        if(columnIndex == 'num_absence') {
                            var arrayAbsence=[];
                            var rowData = hotInstance.getData();

                            for(var keyIndex=0; keyIndex< tableData.length; keyIndex++) {
                                $.each(tableData[keyIndex],function(i, value){
//                                    console.log(index+'--->'+value);
                                    if(rowData[rowIndex][0] == value) {//rowData[rowIndex][0] with the row data we get rowDat by Key rowIndex then we will get the student_id_card
                                        element = {
                                            num_absence: newValue,
                                            student_annual_id: tableData[keyIndex]['student_annual_id'],
//                                            department_id: tableData[keyIndex]['department_id'],
//                                            degree_id: tableData[keyIndex]['degree_id'],
//                                            grade_id:           tableData[keyIndex]['grade_id'],
//                                            academic_year_id :  tableData[keyIndex]['academic_year_id'],
//                                            semester_id:        tableData[keyIndex]['semester_id'],
                                            course_annual_id: '{{$courseAnnualID}}'
                                        };
                                    }
                                });

                            }

                            if(oldValue != newValue){
                                cellChanges.push(element);
                            }

                        }
                    });
                }
            },

            beforeChange: function (changes, source) {
                var lastChange = changes[0];
                var rowIndex = lastChange[0];
                var columnIndex = lastChange[1];

                cellIndex.push({ row: rowIndex,col: columnIndex });

            }
        };

        $('#add_column').on('click', function(e) {

            var pop = new jPopup({
               title: 'Add New Column',
                content: '<div class="form-group col-sm-12 no-padding">' +
                            '<label for="percentage" class="col-sm-2 control-label pop_margin no-padding "> Percentage</label>'+
                            '<div class="col-sm-7 no-padding">'+
                            '<input type="text" id="percentage" class="form-control number_only" required>'+
                            '</div>'+
                        '</div>'+

                        '<div class="form-group col-sm-12 no-padding">' +
                            '<label for="column_name" class="col-sm-2 control-label pop_margin no-padding">Column Name</label>'+
                            '<div class="col-sm-7 no-padding">'+
                                '<input type="text" class="form-control" id="name_exam" name="name_exam" required>'+
                            '</div>'+
                        '</div class="form-group col-sm-12 no-padding">'+
                '<div class="form-group col-sm-12 no-padding">' +
                        '<label for="score_type" class="col-sm-3 no-padding"> Score Type</label>'+
                        '<div class="col-sm-7 no-padding">' +
                            '<select name="score_type" class="form-control" id="score_type">'+
                                '<option value="normal">Normal</option>'+
                                '<option value="subplementary_exam"> Subplementary Exam</option>'+
                            '</select>'+
                        '</div>'+
                '</div>'+
                '<button id="add_col_ok" class="btn btn-xs btn-primary pull-right"> OK </button>',
                closeButton:true,
                buttons:[{
//                    text: '<button class="btn btn-danger"> OK </button>',
//                    value: 'ok',
//                    "class": "ok_event"
                }]
            });

            pop.open(function(r) {// call this function to open dialog
                switch(r) {
                    case 'ok':// vaule of btn
                       //do nothing
                        break;
                }

            });
        });

        function addColumns(colHeader, percentage) {

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
                                updateSettingHandsontable(resultData);
                                declareColumnHeaderDataEmpty();
                                $('#popup').hide();
                            }
                        });
                    }
                });
        }

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
//                    setting.colWidths = resultData.colWidths;
                    // loop for declaring array key of columns score with empty value ---> then we will push the cell score change for updating score value--> this idea is to reduce the amount of parametter that pass to the server
                    declareColumnHeaderDataEmpty();

                    hotInstance = new Handsontable(jQuery("#score_table")[0], setting);


                    hotInstance.updateSettings({
                        contextMenu: {
                            callback: function (key, options) {

                                if (key === 'rowcolor') {
                                    setTimeout(function () {
                                        //timeout is used to make sure the menu collapsed before alert is shown
                                        var row = hotInstance.getSelected()[0];
                                        sentrow = row;
                                        hotInstance.render();

                                    }, 100);
                                }

                                if (key === 'deletecol') {
                                    console.log( hotInstance.getSelected());

                                    if(hotInstance.getSelected()) {

                                        var colIndex = hotInstance.getSelected()[1]; //console.log(hotInstance.getSelected()[1]);// return index of column header count from 0 index

                                        // check not allow to delete on the specific columns
                                       if(((colIndex != 0) && (colIndex != 1)) && ((colIndex != 2) && (colIndex != 3)) && ((colIndex != 4) && (colIndex != setting.colHeaders.length-1))) {

                                           var colNmae = setting.colHeaders[colIndex];
                                           var percentageId = setting.data[0]['percentage_id_'+colNmae];
                                           var courseAnnualId = setting.data[0]['course_annual_id'];
                                           var baseUrl = '{{route('admin.course.delete-score')}}';
                                           var baseData = {
                                               percentage_id: percentageId,
                                               percentage_name: colNmae,
                                               course_annual_id: '{{$courseAnnualID}}'
                                           };

//                                           console.log(setting.colHeaders);

                                           swal({
                                               title: "Confirm",
                                               text: "Delete Score??",
                                               type: "info",
                                               showCancelButton: true,
                                               confirmButtonColor: "#DD6B55",
                                               confirmButtonText: "Yes",
                                               closeOnConfirm: true
                                           }, function(confirmed) {
                                               if (confirmed) {
                                                   console.log(confirmed);

                                                   $.ajax({
                                                       type: 'DELETE',
                                                       url: baseUrl,
                                                       data: baseData,
                                                       dataType: "json",
                                                       success: function(resultData) {
                                                           notify('success', 'info', 'Score Deleted!!');
                                                           updateSettingHandsontable(resultData);
                                                       }
                                                   });

                                               }
                                           });
                                       } else {
                                           notify('error', 'info', 'This Column is not Deletable');
                                       }

                                    } else {
                                        notify('error', 'info', 'Column Score Not Selected!!')
                                    }

                                }
                                if(key == 'freeze_column') {

                                    if(hotInstance.getSelected()) {

                                        let selectedColumn = hotInstance.getSelected()[1];

                                        if(setting.fixedColumnsLeft) {

                                            if (selectedColumn > setting.fixedColumnsLeft - 1) {

                                                freezeColumn(selectedColumn);
                                            } else {
                                                unfreezeColumn(selectedColumn);
                                            }

                                        } else {

                                            freezeColumn(selectedColumn);
                                        }

                                    }

                                }

                                function freezeColumn(column) {
                                    setting.fixedColumnsLeft = column+1;
                                    setting.manualColumnFreeze = true;
                                    hotInstance.updateSettings({
                                        fixedColumnsLeft: column + 1,
                                        manualColumnFreeze: true
                                    });
                                }

                                function unfreezeColumn(column) {

                                    console.log(column);

                                    if (column > setting.fixedColumnsLeft - 1) {
                                        return; // not fixed
                                    }
                                    removeFixedColumn(column+1);
                                }

                                function removeFixedColumn(column) {
                                    hotInstance.updateSettings({
                                        fixedColumnsLeft: column - 1
                                    });
                                    setting.fixedColumnsLeft--;
                                }
                            },
                            items: {

                                "deletecol": {
                                    name: '<span><i class="fa fa-trash"> Delete Column</i></span>'
                                },

                                "freeze_column": {
                                    name: function() {
                                        let selectedColumn = hotInstance.getSelected()[1];
                                        if(setting.fixedColumnsLeft) {
                                            if (selectedColumn > setting.fixedColumnsLeft - 1) {
                                                return '<span><i class="fa fa-fire"> Freeze This Column </i></span>';
                                            } else {
                                                return '<span><i class="fa fa-leaf"> Unfreeze This Column </i></span>';
                                            }
                                        } else {
                                            return '<span><i class="fa fa-fire"> Freeze This Column </i></span>';
                                        }

                                    }
                                }
                            }
                        }
                    })
                }
            });

        });

        $('#save_editted_score').on('click', function() {

            var url = '{{route('admin.course.save_number_absence')}}';

            if(cellChanges.length > 0 || cellScoreChanges.length > 0) {
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

                        if(cellScoreChanges.length > 0) {// save each score

                            //recursive function fo send the request by the column data array
                            function sendRequest (index, message) {

                                var baseUrl = '{{route('admin.course.save_score_course_annual')}}';

                                if(index < setting.colHeaders.length -1) {

                                    if(colDataArray[setting.colHeaders[index]].length > 0 ) {
                                        $.ajax({
                                            type: 'POST',
                                            url: baseUrl,
                                            data: {data:colDataArray[setting.colHeaders[index]]},
                                            dataType: "json",
                                            success: function(resultData) {
                                                if(resultData.status) {
                                                    index++;
                                                    updateSettingHandsontable(resultData.handsontableData);
                                                    sendRequest(index);
                                                } else {
                                                    notify('error', resultData.message, 'Alert');
                                                }
                                            }
                                        })
                                    } else {
                                        index++;
                                        sendRequest(index, message);
                                    }
                                } else {
                                    notify('success', 'Score Saved!', 'Info');
                                }
                            }
                            var index = 5;
                            var message = null;
                            sendRequest(index, message);
                            cellScoreChanges=[];
                            cellIndex = [];

                        }

                        if(cellChanges.length > 0) { // save each number absence

                            var url = '{{route('admin.course.save_number_absence')}}';

                            console.log(cellChanges);
                            console.log(cellScoreChanges);
                            $.ajax({
                                type: 'POST',
                                url: url,
                                data: {baseData:cellChanges},
                                dataType: "json",
                                success: function(resultData) {

                                    updateSettingHandsontable(resultData);
//                                    setting.data = resultData.data;
//                                    setting.colHeaders = resultData.columnHeader;
//                                    setting.columns = resultData.columns;
//                                    hotInstance = new Handsontable(jQuery("#score_table")[0], setting);
                                    cellChanges=[];
                                    cellIndex = [];
                                }
                            });

                            cellIndex = [];
                        }

                    }
                });

            } else {
                notify('error', 'There are no changes!!', 'Info');
            }
        })

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


                    var Baseurl = '{{route('admin.course.get_average_score', $courseAnnualID)}}';

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
                                url: Baseurl,
                                data: {data: setting.data, colHeader:setting.colHeaders},
                                dataType: "json",
                                success: function(resultData) {

                                    notify('success', 'info', 'Score Calculated!!');
                                    setting.data = resultData.data;
                                    setting.colHeaders = resultData.columnHeader;
                                    setting.columns = resultData.columns;
                                    hotInstance = new Handsontable(jQuery("#score_table")[0], setting);
                                    cellIndex=[];

//                                alert('Redraw Table');
                                }
                            });


                        }
                    });
                }
            }



        })

    </script>
@stop