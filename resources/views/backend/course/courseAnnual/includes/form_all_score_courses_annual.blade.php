@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.courseAnnuals.title') . ' | ' . trans('labels.backend.courseAnnuals.sub_edit_title'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.courseAnnuals.title') }}
        <small>{{ trans('labels.backend.courseAnnuals.sub_edit_title') }}</small>
    </h1>

    <style>

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

        /* Every even row */
        .ht_master tr:nth-of-type(even) > td {
            background-color: #F7F8FF;
        }


        .ht_master tr:nth-of-type(50) > td {
            background-color: #F7F8FF;
        }
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

        .margin-left2 {
            margin-left: 5px;
        }
    </style>

@endsection

@section('content')

    <div class="box box-success">

        <div class="box-header with-border">
            <div class="row-fluid box-title">
                <label for="year" class="label label-success"> Total Score For Academic Year: {{$academicYear->name_latin}}</label>
                <span class="label label-success arrowed-right arrowed-in margin-left2"> Department: {{$department->name_en}}</span>
                <span class="label label-success arrowed-right arrowed-in margin-left2">Student: {{$degree->name_en}}</span>
                <span class="label label-success arrowed-right arrowed-in margin-left2"> Student:{{$grade->name_en}}</span>
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

        function getSelectedColor() {
            return 'yellow';
        }

        var TableStyles = function(hotInstance) {
            var self = this;
            var _cellStyles = [];
            var _createStyle = function(row, col, color) {
                var _color = color;

                var style = {
                    row: row,
                    col: col,
                    renderer:   function (instance, td, row, col, prop, value, cellProperties) {
                        Handsontable.renderers.TextRenderer.apply(this, arguments);
                        td.style.backgroundColor = _color;
                    },
                    color: function(c) { _color = c; }
                };

                return style;
            };

            self.getStyles = function() {
                return _cellStyles;
            };

            self.setCellStyle = function(row, col, color, updateTable) {
                var _color = color;

                if (_cellStyles.length == 0) {
                    _cellStyles.push(_createStyle(row, col, color));
                } else {
                    var found = _cellStyles.some(function(cell) {
                        if (cell.row == row && cell.col == col) {
                            cell.color(color);
                            return true;
                        }
                    });

                    if (!found) {
                        _cellStyles.push(_createStyle(row, col, color));
                    }
                }

                if (updateTable!=false) {
                    hotInstance.updateSettings({cell: self.getStyles()});
                    hotInstance.render();
                };
            };

            self.setRowStyle = function(row, color) {
                for (var col=0; col<hotInstance.countCols(); col++)
                    self.setCellStyle(row, col, color, false);

                hotInstance.updateSettings({cell: self.getStyles()});
                hotInstance.render();
            };

            self.setColStyle = function(col, color) {
                for (var row=0; row<hotInstance.countCols(); row++)
                    self.setCellStyle(row, col, color, false);

                hotInstance.updateSettings({cell: self.getStyles()});
                hotInstance.render();
            };
        };


//        var colorRenderer = function ( instance, td, row, col, prop, value, cellProperties) {
//
//            Handsontable.renderers.TextRenderer.apply(this, arguments);
//
//            td.style.backgroundColor = '#FEFFB0';
//
//        };

        var table_size;
        $(window).on('load resize', function(){
            table_size = $('.box-body').width();
        });


        var hotInstance;
        var setting = {
            rowHeaders: false,
            manualColumnMove: true,
            filters: true,
            autoWrapRow: true,
            minSpareRows: true,
            fixedColumnsLeft: 4,
            height:1400,
            columnSorting: true,
            width: table_size,
            filters: true,
            dropdownMenu: ['filter_by_condition', 'filter_action_bar', 'sort'],
            className: "htLeft",

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

                    console.log(resultData.nestedHeaders);
                    setting.data = resultData.data;
//                    setting.colHeaders = resultData.columnHeader;
//                    setting.columns = resultData.columns;
                    setting.nestedHeaders = resultData.nestedHeaders;
                    setting.colWidths = resultData.colWidths;
                    hotInstance = new Handsontable(jQuery("#all_score_course_annual_table")[0], setting)

                    var styles = new TableStyles(hotInstance);

//                    setting.columnSummary = [{destinationRow: 54,destinationColumn: 8,type: 'min' },{destinationRow: 55,destinationColumn: 8,type: 'max'}];

                    hotInstance.updateSettings({
                        contextMenu: {
                            callback: function (key, options) {
                                if (key === 'cellcolor') {
                                    setTimeout(function () {
                                        var sel = hotInstance.getSelected();

                                        styles.setCellStyle(sel[0], sel[1], getSelectedColor());
                                    }, 100);
                                }
                                if (key === 'rowcolor') {
                                    setTimeout(function () {
                                        //timeout is used to make sure the menu collapsed before alert is shown
                                        var sel = hotInstance.getSelected();

                                        styles.setRowStyle(sel[0], getSelectedColor());
                                    }, 100);
                                }
                                if (key === 'colcolor') {
                                    setTimeout(function () {
                                        //timeout is used to make sure the menu collapsed before alert is shown
                                        var sel = hotInstance.getSelected();

                                        styles.setColStyle(sel[1], getSelectedColor());
                                    }, 100);
                                }
                            },
                            items: {
                                "rowcolor": {
                                    name: 'Row color'
                                }
                            }
                        }
                    })

                }
            });

        });

    </script>
@stop