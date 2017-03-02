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
        </style>
        <div class="box-header with-border">
            <div class=" no-paddingcol-sm-12">

                <div class="pull-left">
                    <button class="btn btn-primary btn-xs" id="btn-print"><i class="fa fa-print"></i> Print</button>
                </div>

                <button class="btn btn-xs btn-primary pull-left" id="btn_export_score" style="margin-left: 5px"> <i class="fa fa-export">Export</i></button>

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
            manualColumnMove: false,
            manualColumnResize: false,
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

//                console.log(prop);


                return cellProperties;
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
//                setSelectedRow();
            },
            afterSelectionEnd: function() {
                setSelectedRow();
            },

            afterMomentumScroll: function() {

                return false;
            },
            beforeTouchScroll: function() {

                return false;
            },
            afterScrollHorizontally: function() {

                return false;
            },
            afterScrollVertically: function() {

                return false;
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

                        var table_size = $('.box-body').width() + 30;
                        var mainHeaderHeight = $('.main-header').height();
                        var mainFooterHeight = $('.main-footer').height();
                        var boxHeaderHeight = $('.box-header').height();
                        var height = $(document).height();
                        var tab_height = height - (mainHeaderHeight + mainFooterHeight + boxHeaderHeight + 60);


                        setting.height=tab_height;

                        hotInstance = new Handsontable(jQuery("#all_score_course_annual_table")[0], setting);
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
//            hotInstance = new Handsontable(jQuery("#all_score_course_annual_table")[0], setting)
            hotInstance.updateSettings({
                data: resultData['data'],
                nestedHeaders:resultData['nestedHeaders'],
                colWidths:resultData['colWidths']
            });
        }



        $('#refresh_score_sheet').on('click', function() {
            filter_table();
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



    </script>


@stop