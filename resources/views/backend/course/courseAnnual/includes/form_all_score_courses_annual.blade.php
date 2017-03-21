@extends ('backend.layouts.popup_master')

@section ('title', 'Course Annual' . ' | ' . 'Total Score Annually')

@section('content')

    <div class="box box-success">

        <style>

            .handsontable td.area {
                background-color: black;
            }

            /*.ht_master tr > td:nth-of-type(odd) {*/
            /*background-color: #f00;*/
            /*}*/

            /*!* Every even column *!*/
            /*.ht_master tr > td:nth-of-type(even) {*/
            /*background-color: #919291;*/
            /*}*/

            /* Every odd row */
            /*.ht_master tr:nth-of-type(odd) > td {*/
            /*background-color: #F7F8FF;*/
            /*}*/

            /*Every even row */
            /*.ht_master tr:nth-of-type(even) > td {*/
            /*background-color: #F7F8FF;*/
            /*}*/


            /*.ht_master tr:nth-of-type(50) > td {*/
            /*background-color: #F7F8FF;*/
            /*}*/
            .popupdiv{
                height:200px;
                width: 600px;
                background-color: red;
                opacity: 60;

            }

            .drop-menu {
                margin-top: 5px;
            }

            .pop_margin{
                margin-right: 30px;
            }

            .margin-left2 {
                margin-left: 5px;
            }
            .selection {
                width: 80px;
                font-size: 10pt;
                height: 23px;
                margin-left: 5px;

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

            .htAutocompleteArrow{

                float:right !important;
                font-size:10px !important;
                color:#EEE !important;
                cursor:default !important;
                width:16px !important;
                text-align:center !important;
            }
            .handsontable td .htAutocompleteArrow:hover{
                color:#777 !important;
            }

            .handsontable td.area .htAutocompleteArrow{

                color:#d3d3d3 !important;
            }
        </style>
        <div class="box-header with-border">


            <div class=" no-paddingcol-sm-12">

                <div class="pull-left">
                    <button class="btn btn-primary btn-xs" id="btn-print"><i class="fa fa-print"></i> Print</button>
                </div>

                <button class="btn btn-xs btn-info pull-left" id="btn_export_score" style="margin-left: 5px"> <i class="fa fa-download"> Export</i></button>
                <button class="btn btn-xs btn-warning pull-left" id="get_radie" style="margin-left: 5px"> <i class="fa fa-download"> Radié</i></button>
                <button class="btn btn-xs btn-success pull-left" id="generate_rattrapage" style="margin-left: 5px"> <i class="fa fa-circle-o"> Rattrapage</i></button>

                <div class="pull-right">

                    <button class="btn btn-primary btn-xs col-sm-1" id="refresh_score_sheet" style="margin-left: -50px"><i class="fa fa-refresh"></i></button>

                    <select  name="academic_year" id="filter_academic_year" style="width: 100px;" class=" col-sm-1">
                        @foreach($academicYears as $key=>$year)
                            <option value="{{$key}}"> {{$year}}</option>
                        @endforeach
                    </select>

                    <select  name="department" id="filter_dept" class="selection col-sm-1">
                        @foreach($departments as $key=>$departmentName)
                            <option value="{{$key}}"> {{$departmentName}}</option>
                        @endforeach
                    </select>

                    <select  name="dept_option" id="filter_dept_option" class="selection col-sm-1">
                        <option value="">Option</option>
                        @foreach($departmentOptions as $option)
                            <option value="{{$option->id}}" class="dept_option department_{{$option->department_id}}">{{$option->code}}</option>
                        @endforeach
                    </select>

                    <select  name="semester" id="filter_semester" style="width: 90px;" class=" col-sm-1">
                        @foreach($semesters as $key=>$semester)
                            <option value="{{$key}}"> {{$semester}}</option>
                        @endforeach
                            <option value=""> Semesters </option>
                    </select>

                    <select  name="degree" id="filter_degree" class="selection  col-sm-1">
                        @foreach($degrees as $key=>$degreeName)
                            <option value="{{$key}}"> {{$degreeName}}</option>
                        @endforeach
                    </select>


                    <select  name="grade" id="filter_grade" class="selection col-sm-1">
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
            </div>

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
            <div id="all_score_course_annual_table" class="table table-striped handsontable htColumnHeaders">

            </div>

        </div>

    </div>
@stop

@section('after-scripts-end')

    {!! Html::style('plugins/handsontable-test/handsontable.full.min.css') !!}
    {!! Html::script('plugins/handsontable-test/handsontable.full.min.js') !!}
    {!! Html::script('plugins/jpopup/jpopup.js') !!}
    {{--myscript--}}

    <script>


        function setSelectedRow() {

            var current_rows = $(document).find(".current_row");
            if(current_rows != null){
                current_rows.removeClass("current_row");
            }
            $(".current").closest("tr").addClass("current_row");
        }

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
        var colorRenderer = function ( instance, td, row, col, prop, value, cellProperties) {

            Handsontable.renderers.TextRenderer.apply(this, arguments);

            if(prop == 'Redouble') {
//                td.textContent = '<div class="htAutocompleteArrow"> ▼ </div>'
//                td.div =
            }

            if(jQuery.isNumeric(value) ) {
                if(value < parseInt('{{\App\Models\Enum\ScoreEnum::Pass_Moyenne}}')) {
                    if(prop != 'number' ) {
                        if(prop != 'Rank' && prop != 'Rattrapage') {
                            var check = prop.split('_');
                            if(check[0] != 'Abs') {

                                if(prop != 'total') {
                                    var colSemester = prop.split('_');
                                    if(colSemester[0] != 'S' ) {

                                        if(value < parseInt('{{\App\Models\Enum\ScoreEnum::Under_30}}')) {

                                            if(value <= parseInt('{{\App\Models\Enum\ScoreEnum::Score_10}}')) {
                                                td.style.backgroundColor = '#A41C00';
                                            } else {
                                                td.style.backgroundColor = '#F76A4D';
                                            }

                                        } else {
                                            td.style.backgroundColor = '#D2B500';
                                        }

                                    }
                                }
                            }

                        }
                    }

                }
                var check = prop.split('_');

                if(check[0] == 'Abs') {
                    td.style.backgroundColor= '#E6E6E8'
                }
            }

        };
        var table_width;
        var hotInstance;
        var print_url = "{{route('admin.course.print_total_score')}}";
        var setting = {
            readOnly:true,
            rowHeaders: false,
            manualColumnMove: true,
            manualColumnResize: true,

            manualRowResize: false,
            minSpareRows: false,
            fixedColumnsLeft: 3,
            filters: true,
            dropdownMenu: ['filter_by_condition', 'filter_action_bar'],
            className: "htRight",
            cells: function (row, col, prop) {


                this.renderer = colorRenderer;

//                console.log(row+'------'+col+'----'+prop);

                var cellProperties = {};
                if ( prop  === 'Redouble') {
                    cellProperties.readOnly = false;
                } else if ( prop  === 'Observation') {
                    cellProperties.readOnly = false;
                } else if ( prop  === 'Rattrapage') {
                    cellProperties.readOnly = false;
                } else if ( prop  === 'Passage') {
                    cellProperties.readOnly = false;
                } else if(prop == 'Remark') {
                    cellProperties.readOnly = false;
                }

                if(prop == 'number') {
                    cellProperties.className = 'htLeft';
                } else if(prop == 'student_name') {
                    cellProperties.className = 'htLeft';

                } else if (prop == 'student_gender') {
                    cellProperties.className = 'htLeft';

                } else if(prop == 'student_id_card') {
                    cellProperties.className = 'htLeft';
                } else if(prop == 'Observation') {
                    cellProperties.className = 'htLeft';
                } else if (prop == 'Remark') {
                    cellProperties.className = 'htLeft';
                }

                if (prop === 'Redouble') {
                    cellProperties.className = "htRight";
                    this.type = 'autocomplete';
                    this.filter = false;
                    if($.trim($('#filter_degree :selected').text().toUpperCase()) == 'ENGINEER') {
                        this.source =  ['Red. '+ 'I'+ $('#filter_grade :selected').val(), 'Radié', '{{\App\Models\Enum\ScoreEnum::Pass}}'] // to add to the beginning do this.source.unshift(val) instead
                    } else {
                        this.source =  ['Red. '+"T"+$('#filter_grade :selected').val(), 'Radié', '{{\App\Models\Enum\ScoreEnum::Pass}}'] // to add to the beginning do this.source.unshift(val) instead
                    }

                }

                return cellProperties;


            },
            beforeOnCellMouseDown: function (event,coord, TD) {
                return true;
            },
            afterOnCellMouseDown: function (event,coord, TD) {
                return true;
            },
            afterCellMetaReset: function() {
                return  true;
            },
            afterRowMove: function() {
                return true;
            },

            afterSelectionEnd: function() {
                setSelectedRow();
            },
            beforeTouchScroll: function() {

                return true;
            },
            afterScrollHorizontally: function() {

                return true;
            },

            afterColumnResize: function() {
                return false;
            },

            afterChange: function(changes, source) {

                if(changes) {
                    $.each(changes, function (index, element) {

                        var change = element;
                        var rowIndex = change[0];
                        var columnIndex = change[1];
                        var oldValue = change[2];
                        var newValue = change[3];
                        var col_student_id = hotInstance.getDataAtProp('student_id_card'); //---array data of column student_id

                        {{--if(columnIndex == 'Observation') {--}}
{{--//                            var rowData = hotInstance.getData();//----all table data--}}
                        {{----}}
                            {{--var route = '{{route('course_annual.save_each_cell_observation')}}';--}}
                            {{--var baseData ={student_id_card: col_student_id[rowIndex], observation: newValue};--}}

                            {{--$.ajax({--}}
                                {{--type: 'POST',--}}
                                {{--url: route,--}}
                                {{--data: baseData,--}}
                                {{--dataType: "json",--}}
                                {{--success: function(resultData) {--}}

                                    {{--//---call back function ....do some stuff--}}
                                {{--}--}}
                            {{--});--}}
                        {{--}--}}

                        if(columnIndex == 'Redouble') {

                            if(oldValue != newValue) {
                                var remark_rul = '{{route('student.update_status')}}';
                                var baseData_redouble ={student_id_card: col_student_id[rowIndex], redouble: newValue, academic_year_id: $('#filter_academic_year :selected').val(), old_value: oldValue};
                                $.ajax({
                                    type: 'POST',
                                    url: remark_rul,
                                    data: baseData_redouble,
                                    dataType: "json",
                                    success: function(resultData) {

                                        if(resultData.status) {
                                            notify('success', resultData.message, 'Info');
                                        } else {

                                            notify('error', resultData.message, 'Attention');

                                        }


                                    }
                                });
                            }
                        }

                        if(columnIndex == 'Remark') {
                            var remark_rul = '{{route('course_annual.save_each_cell_remark')}}';
                            var baseData_remark ={student_id_card: col_student_id[rowIndex], remark: newValue};

                            $.ajax({
                                type: 'POST',
                                url: remark_rul,
                                data: baseData_remark,
                                dataType: "json",
                                success: function(resultData) {

                                    //---call back function ....do some stuff
                                }
                            });
                        }
                    })
                }
            }

        };

        $('.dept_option').hide();

        $(document).ready(function() {

            $("#btn-print").on("click", function(){

                var department_id= $('#filter_dept :selected').val();
                var degree_id= $('#filter_degree :selected').val();
                var grade_id= $('#filter_grade').val();
                var academic_year_id= $('#filter_academic_year :selected').val();
                var semester_id=$('#filter_semester :selected').val();
                var dept_option_id= $('#filter_dept_option :selected').val();

                var win = window.open(print_url+
                        "?department_id="+department_id+
                        "&degree_id="+degree_id+
                        "&grade_id="+grade_id+
                        "&academic_year_id="+academic_year_id+
                        "&semester_id="+semester_id+
                        "&dept_option_id="+dept_option_id
                        , '_blank');
                win.focus();
            });



            $('#btn_export_score').on('click', function() {

                var route_export = '{{route('course_annual.export_view_total_score')}}'
                var D = {
                    department_id: $('#filter_dept :selected').val(),
                    degree_id: $('#filter_degree :selected').val(),
                    grade_id: $('#filter_grade :selected').val(),
                    academic_year_id: $('#filter_academic_year :selected').val(),
                    semester_id:$('#filter_semester :selected').val(),
                    dept_option_id: $('#filter_dept_option :selected').val()
                }

                window.open(route_export+'?department_id='+ D.department_id+
                        '&degree_id='+D.degree_id+
                        '&grade_id='+D.grade_id+
                        '&academic_year_id='+ D.academic_year_id+
                        '&semester_id='+D.semester_id+
                        '&dept_option_id='+D.dept_option_id, '_blank'
                )
            })

            if(val = $('#filter_dept :selected').val()) {
                $('.department_'+ val).show();

                if(val == parseInt('{{\App\Models\Enum\ScoreEnum::Dept_TC}}')) {

                    $('#filter_grade option').each(function(key, val) {

                        if($(this).val() == parseInt('{{\App\Models\Enum\ScoreEnum::Year_1}}')) {
                            $(this).prop('selected', true);
                        }
                    });

                } else {
                    $('#filter_grade option').each(function(key, val) {

                        if($(this).val() == parseInt('{{\App\Models\Enum\ScoreEnum::Year_3}}')) {
                            $(this).prop('selected', true);
                        }
                    });
                }
            }

            initTable();

        });

        function initTable() {


            var BaseUrl = '{{route('admin.course.get_all_handsontable_data')}}';
            var BaseData = {
                department_id: $('#filter_dept :selected').val(),
                degree_id: $('#filter_degree :selected').val(),
                grade_id: $('#filter_grade').val(),
                academic_year_id: $('#filter_academic_year :selected').val(),
                semester_id:$('#filter_semester :selected').val(),
                dept_option_id: $('#filter_dept_option :selected').val(),
            }

            //--------------- when document ready call ajax
            $.ajax({
                type: 'GET',
                url: BaseUrl,
                data:BaseData ,
                dataType: "json",
                success: function(resultData) {
                    if(resultData.status == false) {

                        swal({
                            title: "Attention",
                            text: 'Please Check Incase Yours Department Option Is Not Select!!' ,
                            type: "warning",
                            confirmButtonColor: "red",
                            confirmButtonText: "Close",
                            closeOnConfirm: true
                        }, function(confirmed) {
                            if (confirmed) {
                                // do some staff if you want ---
                            }
                        });

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


                        setting.height=tab_height;

                        hotInstance = new Handsontable(jQuery("#all_score_course_annual_table")[0], setting);
                        assignNumberRattrapage();// ---after initial handsontable
                        hotInstance.updateSettings({
                            contextMenu: {
                                callback: function (key, options) {

                                    if (key === 'sort') {
                                        setTimeout(function () {
//                                            console.log(hotInstance);
//                                            console.log(hotInstance.getDataAtCol(2));
                                            //timeout is used to make sure the menu collapsed before alert is shown

                                            var col_student_id = hotInstance.getDataAtProp('student_id_card')
                                            var row = hotInstance.getSelected()[0];
                                            var col = hotInstance.getSelected()[1];

                                            var data = hotInstance.getData();
                                            var settingData = setting.data;
                                            var arrayData = [];
                                            var averageMaxMin = [];


                                            for(var key = 0; key < data.length; key++) {
                                                if((data[key][col] != null) && (data[key][col] != '')) {
                                                    arrayData.push({student_id_card: data[key][1], score: data[key][col]});
                                                } else {
                                                    break;
                                                }
                                            }

                                            if(col > 3 && col < (hotInstance.countCols() - 5)) {

                                                arrayData.sort(SortByScore);
                                                var sortedData = [];
                                                for(var rank=0; rank < arrayData.length; rank++) {
                                                    $.each(settingData, function(key, val) {

                                                        if(val.student_id_card == arrayData[rank].student_id_card) {
//                                                    console.log(val.student_id_card +'=='+ arrayData[rank].student_id_card);
                                                            val.number = rank+1;
                                                            sortedData.push(val)
                                                        }
                                                    })
                                                }
                                                for(var index= arrayData.length; index < settingData.length; index++) {
                                                    averageMaxMin.push(settingData[index])
                                                }
                                                var finalData = sortedData.concat(averageMaxMin);
                                                setting.data = finalData;
                                                hotInstance.updateSettings({
                                                    data: finalData
                                                });

                                            }

                                            if(col == 1) {

                                                var dataToSort = settingData.slice(0,arrayData.length);
                                                averageMaxMin = settingData.slice(arrayData.length);
                                                dataToSort.sort(SortId);
                                                var index =0;
                                                $.each(dataToSort, function(key, val) {
                                                    val.number = index+1;
                                                    index++;
                                                })

                                                var finalData = dataToSort.concat(averageMaxMin);
                                                setting.data = finalData;
                                                hotInstance.updateSettings({
                                                    data: finalData
                                                });
                                            }

                                            if(col == 2) {

                                                var dataToSort = settingData.slice(0,arrayData.length);
                                                averageMaxMin = settingData.slice(arrayData.length);
                                                dataToSort.sort(SortName);
                                                var index =0;
                                                $.each(dataToSort, function(key, val) {
                                                    val.number = index+1;
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

                                    function SortByScore(a, b){
                                        var aScore = a.score;
                                        var bScore = b.score;
                                        return bScore - aScore ;
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
                                        name: function() {
                                            var selectedColumn = hotInstance.getSelected()[1];
                                            if (selectedColumn == 3 || selectedColumn == 0) {
                                                return '';
                                            } else {
                                                if(selectedColumn < hotInstance.countCols() -5) {
                                                    return '<span><i class="fa fa-leaf"> Sort Column </i></span>';
                                                } else {
                                                    return '';
                                                }

                                            }
                                        }
                                    }
                                }
                            }
                        })
                    }

                }
            });


            $(window).resize(function() {

                var table_width = $('.box-body').width();
//                alert(table_width);
                setting.width = table_width;
                hotInstance.updateSettings({
                    width: table_width
                });
            });
        }


        function filter_table () {

            var BaseData = {
                department_id: $('#filter_dept :selected').val(),
                degree_id: $('#filter_degree :selected').val(),
                grade_id: $('#filter_grade :selected').val(),
                academic_year_id: $('#filter_academic_year :selected').val(),
                semester_id:$('#filter_semester :selected').val(),
                dept_option_id: $('#filter_dept_option :selected').val(),
                group_name: $('#filter_group :selected').val()
            }

            if(hotInstance) {

                $.ajax({
                    type: 'GET',
                    url: '{{route('admin.course.filter_course_annual_scores')}}',
                    data: BaseData,
                    dataType: "json",
                    success: function(resultData) {
                        if(resultData.status == false) {
                            swal({
                                title: "Attention",
                                text: 'Please Check Incase Yours Department Option Is Not Select!!' ,
                                type: "warning",
                                confirmButtonColor: "red",
                                confirmButtonText: "Close",
                                closeOnConfirm: true
                            }, function(confirmed) {
                                if (confirmed) {
                                    // do some staff if you want ---
                                }
                            });
                        } else {
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

//            console.log(resultData)
            setting.data = resultData.data;
            setting.nestedHeaders = resultData.nestedHeaders;
            setting.colWidths = resultData.colWidths;
            setting.array_fail_subject = resultData.array_fail_subject;
//            hotInstance = new Handsontable(jQuery("#all_score_course_annual_table")[0], setting)
            hotInstance.updateSettings({
                data: resultData['data'],
                nestedHeaders:resultData['nestedHeaders'],
                colWidths:resultData['colWidths']

            });
        }

        $('#refresh_score_sheet').on('click', function() {
            filter_table();
            assignNumberRattrapage();
        });

        $('#filter_dept').on('change', function() {
            $('.dept_option').hide();
            $('.department_'+ $(this).val()).show();

            if($(this).val() == parseInt('{{\App\Models\Enum\ScoreEnum::Dept_TC}}')) {

                $('#filter_grade option').each(function(key, val) {

                    if($(this).val() == parseInt('{{\App\Models\Enum\ScoreEnum::Year_1}}')) {
                        $(this).prop('selected', true);
                    }
                });

            } else {

                $('#filter_grade option').each(function(key, val) {

                    if($(this).val() == parseInt('{{\App\Models\Enum\ScoreEnum::Year_3}}')) {
                        $(this).prop('selected', true);
                    }
                });
            }

            filter_table();
        });

        $('#filter_academic_year').on('change', function() {
            filter_table();
        })
        $('#filter_grade').on('change', function(){
            filter_table();
        });
        $('#filter_semester').on('change', function() {
            filter_table();
        })
        $('#filter_degree').on('change', function(){
            filter_table();
        });

        $('#filter_group').on('change', function() {
            filter_table();
        });



        $('#generate_rattrapage').on('click', function() {


            var pop_url = '{{route('course_annual.student_redouble_exam')}}';
            var BaseData = {
                department_id: $('#filter_dept :selected').val(),
                degree_id: $('#filter_degree :selected').val(),
                grade_id: $('#filter_grade :selected').val(),
                academic_year_id: $('#filter_academic_year :selected').val(),
                semester_id:$('#filter_semester :selected').val(),
                dept_option_id: $('#filter_dept_option :selected').val(),

            }

            student_reexam_lists = window.open(
                    pop_url+
                            '?department_id='+BaseData.department_id+
                            '&degree_id='+BaseData.degree_id+
                            '&grade_id='+BaseData.grade_id+
                            '&semester_id='+BaseData.semester_id+
                            '&dept_option_id='+BaseData.dept_option_id+
                            '&academic_year_id='+BaseData.academic_year_id,'_blank');

        })


        //----this one is not use----
        $('#generate_redouble').on('click',function() {

            var pop_url = '{{route('course_annual.student_redouble_exam')}}';
            var array_fail_subject = setting.array_fail_subject;
            var table_data = setting.data;
            var academic_year_id = $('#filter_academic_year :selected').val();

            var base_data = {};
            var count = 0;

            $.each(table_data, function(i, student) {

                if(count < table_data.length -5) {

                    if(student['student_id_card'] != null && student['student_id_card'] != '')  {

                        var student_id_card = student['student_id_card'];
                        var student_exam_subjects = getStudentReExam(array_fail_subject[student_id_card]);

                        if('fail' in student_exam_subjects) {

                            if(student_exam_subjects['fail'].length > 0) {

                                if('pass' in student_exam_subjects ) {

                                    //----here if we want to set that ..only student who obtain the final moyenne upper than 30 %, will be allowed to take re-exam
                                    //---then we should not check the subjects['pass'] length we check only the finally moyenne
                                    if(student_exam_subjects['pass'].length == 0) {
                                        var average =  calculate_moyenne(student_exam_subjects);
                                        if(average > parseFloat('{{\App\Models\Enum\ScoreEnum::Under_30}}')) {

                                            base_data[student_id_card]= removeElement(student_exam_subjects);
                                        }
                                    } else {

                                        base_data[student_id_card]= removeElement(student_exam_subjects);
                                    }

                                    //------end checking moyenne upper then 30

                                } else {

                                    //----student have to re-exam for all subject ---but we have to check if their final moyenne is upper than 30, or he will be redouble

                                    var average =  calculate_moyenne(student_exam_subjects);
                                    if(average > parseFloat('{{\App\Models\Enum\ScoreEnum::Under_30}}')) {

                                        base_data[student_id_card]= removeElement(student_exam_subjects);
                                    }
                                }

                            }
                        }
                    }
                }
                count++;
            });

           var  data = dataToSend(base_data);
            student_reexam_lists = PopupCenterDual(pop_url+'?data='+data+'&academic_year_id='+academic_year_id,'Student Redouble Lists','1500','900');
        });

        function dataToSend(base_data) {

            var array_data = [];

            $.each(base_data, function(student_id_card, object) {
                var fail = '';
                var pass = '';

                fail = fail+ student_id_card + ':F_';

                if('fail' in object) {

                    $.each(object['fail'], function(f_key, f_val) {

                        fail = fail+ f_val['course_annual_id']+ '_'
                    })
                }
                fail = fail+ ':P_';
                if('pass' in object) {

                    $.each(object['pass'], function(p_key, p_val) {
                        pass = pass+ p_val['course_annual_id']+'_'
                    })
                }

                array_data.push(fail+pass);
            })

            return array_data;

        }
        function removeElement (student_exam_subjects) {

            if('pass' in student_exam_subjects) {
                $.each(student_exam_subjects['pass'], function (index, value) {
                    delete  value['score'];
                    delete value['credit'];
                })
            }
            if('fail' in student_exam_subjects) {

                $.each(student_exam_subjects['fail'], function (key, val) {
                    delete  val['score'];
                    delete val['credit'];
                })

            }

            return student_exam_subjects;

        }


        function assignNumberRattrapage() {

            var array_fail_subject = setting.array_fail_subject;
            var table_data = setting.data;

            $.each(table_data, function(i, student) {

                if(student['student_id_card'] != null && student['student_id_card'] != '')  {
                    /*if(student['student_id_card'] == 'e20160662') {
                        console.log(student);
                        console.log(array_fail_subject[student['student_id_card']]);

                    }*/

                    var number_rattrapage = numberSubjectRattrapage(array_fail_subject[student['student_id_card']]);
                    student['Rattrapage'] = number_rattrapage;
                }
            });

            setting.data = table_data;
            hotInstance.updateSettings({
                data: table_data,
                colWidths: setting.colWidths

            });
        }

        function numberSubjectRattrapage(subjects) {

            var number_subject = getStudentReExam(subjects);

            if('fail' in number_subject) {
                return number_subject['fail'].length;
            } else {
                return 0;
            }
        }

        function calculate_moyenne(subjects) {

            var credit = 0;
            var score = 0;
            if('fail' in subjects) {
                if('pass' in subjects) {

                    $.each(subjects['fail'], function(f_index, f_value) {
                        credit = credit + parseFloat(f_value['credit']);
                        score = score + (parseFloat(f_value['score']) * parseFloat(f_value['credit']));
                    });

                    $.each(subjects['pass'], function(p_index, p_value) {
                        credit = credit + parseFloat(p_value['credit']);
                        score = score + (parseFloat(p_value['score']) * parseFloat(p_value['credit']));
                    });

                    return parseFloat(parseFloat(score)/parseFloat(credit));

                } else {

                    $.each(subjects['fail'], function(f_index, f_value) {
                        credit = credit + parseFloat(f_value['credit']);
                        score = score + (parseFloat(f_value['score']) * parseFloat(f_value['credit']));
                    });

                    return parseFloat(parseFloat(score)/parseFloat(credit));
                }

            } else {

                $.each(subjects['pass'], function(index, value) {
                    credit = credit + parseFloat(value['credit']);
                    score = score + (parseFloat(value['score']) * parseFloat(value['credit']));
                });

                return parseFloat(parseFloat(score)/parseFloat(credit));
            }
        }


        function getStudentReExam(subjects) {

            var total_credit = 0;
            if('fail' in subjects) {//=== isset() in php
                if('pass' in subjects) {

                    var validate_score = 0;
                    $.each(subjects['fail'], function(f_key, f_val) {

                        //console.log(validate_score +'=='+ validate_score +'++'+ parseFloat('{{\App\Models\Enum\ScoreEnum::Pass_Moyenne}}') +'*'+ f_val['credit']);
                         total_credit = total_credit + parseFloat(f_val['credit']);
                         validate_score = validate_score + parseFloat('{{\App\Models\Enum\ScoreEnum::Pass_Moyenne}}') * f_val['credit'];
                        //console.log(validate_score);
                    });

                    $.each(subjects['pass'], function(p_key, p_val) {
                        //console.log(validate_score +'=='+ validate_score +'++'+ parseFloat(p_val['score']) +'*'+ p_val['credit']);
                        total_credit = total_credit +parseFloat(p_val['credit']) ;
                        validate_score = validate_score + (parseFloat(p_val['score']) * p_val['credit']);
                        //console.log(validate_score);
                    });

                    var approximation_moyenne = parseFloat((parseFloat(validate_score) / parseFloat(total_credit)));

                    //console.log(approximation_moyenne +'=='+ (parseFloat(validate_score) +'/'+ parseFloat(total_credit)));

                    if(approximation_moyenne < parseFloat('{{\App\Models\Enum\ScoreEnum::Aproximation_Moyenne}}')) {//----55

                        if(subjects['pass'].length > 0) {

                            var find_min = findMinScore(subjects['pass']);

                            if(find_min['element']['score'] < parseFloat('{{\App\Models\Enum\ScoreEnum::Pass_Moyenne}}')) {//--count couse score for only less than 50

                                subjects['fail'].push(find_min['element'])
                                delete subjects['pass'][find_min['index']] ;

                                var tmp_subject_pass=[];

                                $.each(subjects['pass'], function(key, obj_subject) {
                                    if(!$.isEmptyObject(obj_subject)) {
                                        tmp_subject_pass.push(obj_subject);
                                    }
                                }) ;
                                subjects['pass'] = tmp_subject_pass;

                                return getStudentReExam(subjects); //---recuring this function again
                            } else {

                                //---if approximation  moyenne is bigger than 50 allow him
                                if(approximation_moyenne > parseFloat('{{\App\Models\Enum\ScoreEnum::Pass_Moyenne}}') ) {
                                    return subjects;
                                } else {
                                    return subjects;
                                }
                            }
                        } else {

                            return subjects;
                        }
                    } else {
                        return subjects;
                    }
                } else {
                    return subjects; //---student fail all subject
                }
            } else  {

                /*--check if the moyenne of student is under 50 and all subject are bigger than 30*/

                var approximation_moyenne = calculate_moyenne(subjects);

                if(approximation_moyenne < parseFloat('{{\App\Models\Enum\ScoreEnum::Pass_Moyenne}}')) {

                    subjects['fail'] = [];
                    if(subjects['pass'].length > 0) {

                        var find_min = findMinScore(subjects['pass']);

                        if(find_min['element']['score'] < parseFloat('{{\App\Models\Enum\ScoreEnum::Pass_Moyenne}}')) {

                            subjects['fail'].push(find_min['element'])
                            delete subjects['pass'][find_min['index']] ;
                            var tmp_subject_pass=[];
                            $.each(subjects['pass'], function(key, obj_subject) {
                                if(!$.isEmptyObject(obj_subject)) {
                                    tmp_subject_pass.push(obj_subject);
                                }
                            }) ;
                            subjects['pass'] = tmp_subject_pass;
                            return getStudentReExam(subjects); //---recuring this function again

                        } else {

                            return subjects;
                        }
                    } else {
                        return subjects;
                    }

                } else {

                    return subjects;
                }
            }
        }

        function findMinScore(subjects_pass) {

           var  min = subjects_pass[0]['score'];
           var  credit = subjects_pass[0]['credit'];
           var  course_annual_id = subjects_pass[0]['course_annual_id'];
           var  index = 0;

            for(var int=1; int< subjects_pass.length; int++) {
                if(min > subjects_pass[int]['score']) {

                    index = int;
                    min = subjects_pass[int]['score'];
                    credit = subjects_pass[int]['credit'];
                    course_annual_id = subjects_pass[int]['course_annual_id'];
                }
            }
            return {
                element: {score:min, credit: credit, course_annual_id:course_annual_id},
                index: index
            }
        }


        if($('.message').is(':visible')) {
            setTimeout(function(){
                $(".message").fadeOut("slow");
            },3000);
        };




        $('#get_radie').on('click', function() {

            /*var academic_year_id = $('#filter_academic_year :selected').val();
            var department_id = $('#filter_dept :selected').val();*/



            var pop_url = '{{route('student.dismiss')}}';
            var BaseData = {
                department_id: $('#filter_dept :selected').val(),
                degree_id: $('#filter_degree :selected').val(),
                grade_id: $('#filter_grade :selected').val(),
                academic_year_id: $('#filter_academic_year :selected').val(),
                semester_id:$('#filter_semester :selected').val(),
                dept_option_id: $('#filter_dept_option :selected').val(),

            }

            student_reexam_lists = window.open(
                    pop_url+
                    '?department_id='+BaseData.department_id+
                    '&degree_id='+BaseData.degree_id+
                    '&grade_id='+BaseData.grade_id+
                    '&semester_id='+BaseData.semester_id+
                    '&dept_option_id='+BaseData.dept_option_id+
                    '&academic_year_id='+BaseData.academic_year_id,'_blank');




            /*swal({
                title: "Attention",
                text: 'Please wait we are working on it!!' ,
                type: "warning",
                confirmButtonColor: "red",
                confirmButtonText: "Close",
                closeOnConfirm: true
            }, function(confirmed) {
                if (confirmed) {
                    // do some staff if you want ---
                }
            });*/


        })

    </script>


@stop