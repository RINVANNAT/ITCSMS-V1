@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.courseAnnuals.title') . ' | ' . trans('labels.backend.courseAnnuals.sub_edit_title'))

@section('page-header')


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
            height: 120px !important;
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


    <div class="box-header with-border" style="margin-bottom: -25px !important;">
        <div class="col-md-12 no-padding col-lg-12 col-sm-12">
            <h4 for="year" class=" h4 col-md-4 no-padding col-lg-4 col-sm-4">{{$academicYear->name_latin}} /{{$department->code}} /{{$degree->name_en}}/ {{$grade->name_en}}</h4>

            <div class="pull-right">
                <select  name="academic_year" id="filter_academic_year" style="width: 100px;" class=" col-md-1 col-lg-1 col-sm-1">
                    @foreach($academicYears as $key=>$year)
                        @if($key == $academicYear->id)
                            <option value="{{$key}}" selected> {{$year}}</option>
                        @else
                            <option value="{{$key}}"> {{$year}}</option>
                        @endif
                    @endforeach
                </select>

                <select  name="semester" id="filter_semester" style="width: 90px;" class=" col-md-1 col-lg-1 col-sm-1">
                    <option value="">Semester</option>
                    @foreach($semesters as $key=>$semester)
                        @if($key == $semesterId)
                            <option value="{{$key}}" selected> {{$semester}}</option>
                        @else
                            <option value="{{$key}}"> {{$semester}}</option>
                        @endif
                    @endforeach
                </select>

                <select  name="degree" id="filter_degree" class="selection col-md-1 col-lg-1 col-sm-1">
                    <option value="">Degree</option>
                    @foreach($degrees as $key=>$degreeName)
                        @if($key == $degree->id)
                            <option value="{{$key}}" selected> {{$degreeName}}</option>
                        @else
                            <option value="{{$key}}"> {{$degreeName}}</option>
                        @endif
                    @endforeach
                </select>


                <select  name="grade" id="filter_grade" class="selection col-md-1 col-lg-1 col-sm-1">
                    <option value="">Grade</option>
                    @foreach($grades as $key=>$gradeName)
                        @if($key == $grade->id)
                            <option value="{{$key}}" selected> {{$gradeName}}</option>
                        @else
                            <option value="{{$key}}"> {{$gradeName}}</option>
                        @endif
                    @endforeach
                </select>

                @if($deptOptions != null)
                    <select  name="dept_option" id="filter_dept_option" class="selection col-md-1 col-lg-1 col-sm-1">
                        <option value="">Division</option>
                        @foreach($deptOptions as $option)
                            @if($option->id == $deptOptionId)
                                <option value="{{$option->id}}" selected> {{$option->name_en}}</option>
                            @else
                                <option value="{{$option->id}}"> {{$option->name_en}}</option>
                            @endif

                        @endforeach
                    </select>

                @else
                    <select  name="department" id="filter_dept" class="selection col-md-1 col-lg-1 col-sm-1">
                        <option value="">Department</option>
                        @foreach($departments as $key=>$departmentName)
                            @if($key == $department->id)
                                <option value="{{$key}}" selected> {{$departmentName}}</option>
                            @else
                                <option value="{{$key}}"> {{$departmentName}}</option>
                            @endif
                        @endforeach
                    </select>
                @endif

            </div>
        </div>
    </div><!-- /.box-header -->

@endsection
@section('content')
    <div class="box box-success">

        <div class="box-body">
            <div id="all_score_course_annual_table" class="table table-striped handsontable htColumnHeaders">

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
                if(value < 5) {
                    if(prop != 'number' ) {
                        if(prop != 'Rank') {
                            var check = prop.split('_');
                            if(check[0] != 'Abs') {

                                if(prop != 'total') {
                                    var colSemester = prop.split('_');
                                    if(colSemester[0] != 'S' ) {
                                        td.style.backgroundColor = '#cc3300';
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

        var numberOfStudents = '{{isset($students)?count($students):0}}';

        var table_width;
        var hotInstance;
        var setting = {
            readOnly:true,
            manualColumnMove: false,
            manualColumnResize: false,
            manualRowResize: false,
            minSpareRows: false,
            fixedColumnsLeft: 3,
            filters: true,
            dropdownMenu: ['filter_by_condition', 'filter_action_bar'],
            className: "htLeft",
            cells: function (row, col, prop) {
                this.renderer = colorRenderer;

                var cellProperties = {};
                if ( prop  === 'Redouble') {
                    cellProperties.readOnly = false;
                } else if ( prop  === 'Observation') {
                    cellProperties.readOnly = false;
                } else if ( prop  === 'Rattrapage') {
                    cellProperties.readOnly = false;
                } else if ( prop  === 'Passage') {
                    cellProperties.readOnly = false;
                }
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

        };

        $(document).ready(function() {

            var BaseUrl = '{{route('admin.course.get_all_handsontable_data')}}';
            var BaseData = {
                dept_id: '{{$department->id}}',
                degree_id: '{{$degree->id}}',
                grade_id: '{{$grade->id}}',
                academic_year_id: '{{$academicYear->id}}',
                semester_id:'{{$semesterId}}',
                dept_option_id: '{{$deptOptionId}}'
            }

            //--------------- when document ready call ajax
            $.ajax({
                type: 'GET',
                url: BaseUrl,
                data:BaseData ,
                dataType: "json",
                success: function(resultData) {
                    setting.data = resultData.data;
                    setting.nestedHeaders = resultData.nestedHeaders;
                    setting.colWidths = resultData.colWidths;

                    var table_size = $('.box-body').width() + 30;
                    var mainHeaderHeight = $('.main-header').height();
                    var mainFooterHeight = $('.main-footer').height();
                    var boxHeaderHeight = $('.box-header').height();
                    var height = $(document).height();

                    var tab_height = height - (mainHeaderHeight + mainFooterHeight + boxHeaderHeight + 60);

//                    alert(tab_height);

                    setting.height=tab_height;

                    hotInstance = new Handsontable(jQuery("#all_score_course_annual_table")[0], setting);
                    hotInstance.updateSettings({
                        contextMenu: {
                            callback: function (key, options) {

                                if (key === 'sort') {
                                    setTimeout(function () {
                                        //timeout is used to make sure the menu collapsed before alert is shown

                                        var row = hotInstance.getSelected()[0];
                                        var col = hotInstance.getSelected()[1];
                                        var data = hotInstance.getData();
                                        var settingData = setting.data;
                                        var arrayData = [];
                                        var averageMaxMin = [];
                                        for(var key = 0; key < data.length; key++) {
                                            if(data[key][col] != null) {
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
            });


            $(window).resize(function() {

                var table_width = $('.box-body').width();
//                alert(table_width);
                setting.width = table_width;
                hotInstance.updateSettings({
                    width: table_width
                });
            });


            $('.sidebar-toggle').on('click', function() {

//                var table_width = $('.box-body').width();
//                setting.width = table_width;
//                hotInstance.updateSettings({
//                    width: table_width
//                });

            })
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
        $('#filter_dept').on('change', function() {
            filter_table();
        })

        function filter_table () {



            @if($department_id != null)

                var BaseData = {
                        dept_id: '{{$department_id}}',
                        degree_id: $('#filter_degree :selected').val(),
                        grade_id: $('#filter_grade :selected').val(),
                        academic_year_id: $('#filter_academic_year :selected').val(),
                        semester_id:$('#filter_semester :selected').val(),
                        dept_option_id: $('#filter_dept_option :selected').val()
                }
            @else
                var BaseData = {
                    dept_id: $('#filter_dept :selected').val(),
                    degree_id: $('#filter_degree :selected').val(),
                    grade_id: $('#filter_grade :selected').val(),
                    academic_year_id: $('#filter_academic_year :selected').val(),
                    semester_id:$('#filter_semester :selected').val(),
                    dept_option_id: $('#filter_dept_option :selected').val()
                }
            @endif
            $.ajax({
                type: 'GET',
                url: '{{route('admin.course.filter_course_annual_scores')}}',
                data: BaseData,
                dataType: "json",
                success: function(resultData) {
                    updateSettingHandsontable(resultData);
                }
            });
        }

        function updateSettingHandsontable(resultData) {

            console.log(resultData)
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


    </script>
@stop