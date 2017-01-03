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
        .h4 {
            text-align: left;
            margin-top: -3px !important;
        }
    </style>

@endsection
@section('content')
    <div class="box box-success">

        <div class="box-header with-border">
            <div class="col-md-12 no-padding col-lg-12 col-sm-12">
                   <h4 for="year" class=" h4 col-md-4 no-padding col-lg-4 col-sm-4">{{$academicYear->name_latin}} /{{$department->code}} /{{$degree->name_en}}/ {{$grade->name_en}}</h4>

                <div class="pull-right">
                    <select  name="academic_year" id="filter_academic_year" class="selection col-md-1 col-lg-1 col-sm-1">
                        @foreach($academicYears as $key=>$year)
                            @if($key == $academicYear->id)
                                <option value="{{$key}}" selected> {{$year}}</option>
                            @else
                                <option value="{{$key}}"> {{$year}}</option>
                            @endif
                        @endforeach
                    </select>

                    <select  name="semester" id="filter_semester" class="selection col-md-1 col-lg-1 col-sm-1">
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
                    @permission('someone-not-simple-user')

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
                    @endauth
                    <select  name="sort" id="sort_table" class="selection col-md-1 col-lg-1 col-sm-1">
                        <option value="">Sort</option>
                        <option value="name">Name</option>
                        <option value="id_card">ID</option>
                        <option value="rank">By Rank</option>
                    </select>
                </div>
            </div>
        </div><!-- /.box-header -->

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
                        if(prop != 'Classement') {
                            var check = prop.split('_');
                            if(check[0] != 'Abs') {

                                if(prop != 'total') {
                                    var colSemester = prop.split('_');
                                    if(colSemester[0] != 'S' ) {
                                        td.style.backgroundColor = '#FF8D74';
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

        var table_size;
        $(window).on('load resize', function(){
            table_size = $('.box-body').width();
        });

        var numberOfStudents = '{{isset($students)?count($students):0}}';

        var hotInstance;
        var setting = {
            readOnly:true,
            manualColumnMove: false,
            filters: true,
            autoWrapRow: false,
            manualColumnResize: true,
            manualRowResize: true,
            minSpareRows: false,
            fixedColumnsLeft: 3,
            height:700,
            width: table_size,
            filters: true,
            dropdownMenu: ['filter_by_condition', 'filter_action_bar', 'sort'],
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
            }

        };

        $('document').ready(function() {

            var BaseUrl = '{{route('admin.course.get_all_handsontable_data')}}';
            var BaseData = {

                dept_id: '{{$department->id}}',
                degree_id: '{{$degree->id}}',
                grade_id: '{{$grade->id}}',
                academic_year_id: '{{$academicYear->id}}',
                semester_id:'{{$semesterId}}'
            }

            //--------------- when document ready call ajax
            $.ajax({
                type: 'GET',
                url: BaseUrl,
                data:BaseData ,
                dataType: "json",
                success: function(resultData) {


                    setting.data = resultData.data;
//                    setting.colHeaders = resultData.columnHeader;
//                    setting.columns = resultData.columns;
                    setting.nestedHeaders = resultData.nestedHeaders;
                    setting.colWidths = resultData.colWidths;
                    hotInstance = new Handsontable(jQuery("#all_score_course_annual_table")[0], setting)


                }
            });

        });

        $('#sort_table').on('change', function(){

            var sortType = $('#sort_table :selected').val();

            var BaseData = {
                sort_type: sortType,
                dept_id: $('#filter_dept :selected').val(),
                degree_id: $('#filter_degree :selected').val(),
                grade_id: $('#filter_grade :selected').val(),
                academic_year_id: $('#filter_academic_year :selected').val(),
                semester_id:$('#filter_semester :selected').val()
            }


            $.ajax({
                type: 'GET',
                url: '{{route('admin.course.filter_course_annual_scores')}}',
                data: BaseData,
                dataType: "json",
                success: function(resultData) {

                    alert('here we go');
                }
            });
        })


        if($(".sidebar-toggle").toggle($(".sidebar").is(':visible'))) {
        } else {
            alert('false');
        }

    </script>
@stop