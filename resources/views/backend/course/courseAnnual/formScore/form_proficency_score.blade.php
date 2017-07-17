@extends ('backend.layouts.popup_master')

@section ('title', trans('labels.backend.exams.title') . ' | ' . trans('labels.backend.exams.score.request_score_form'))

@section('content')
    <style>

        .current_row td {

            gradient(to bottom, rgba(181, 209, 255, 0.34) 0, rgba(181, 209, 255, 0.34) 100 %);
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

        .handsontable td{
            color: #000 !important;
        }

    </style>
    {!! Html::style('plugins/handsontable-test/handsontable.full.min.css') !!}

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="{{asset('js/vendor/jquery/jquery-2.1.4.min.js')}}"><\/script>')</script>


    <div class="box box-success">
        <div class="box-header with-border text_font">
            <strong class="box-title">
                <span class="text_font" >
                    Input Score:
                    <span style=" color: #00a157;">
                        {{$courseAnnual->name_en. ' |~ '.(($courseAnnual->department_id == config('access.departments.sa'))?'SA' :' SF').'-'.(($courseAnnual->degree_id == config('access.degrees.degree_engineer'))?'I':'T').$courseAnnual->grade_id}}
                    </span>
                </span>
            </strong>


            <button class="btn btn-primary btn-xs pull-right" id="import">
                <i class="fa fa-upload"></i>
                Import
            </button>

            <button class="btn btn-info btn-xs pull-right" style="margin-right: 5px" id="export">
                <i class="fa fa-download"> </i>
                Export
            </button>

            <button class="btn btn-warning btn-xs pull-right" style="margin-right: 5px" id="save">
                <i class="fa fa-submit"> </i>
                Save Change
            </button>

            <button class="btn btn-success btn-xs pull-right" style="margin-right: 5px" id="calculate">
                <i class="fa fa-refresh"> </i>
                Calculate
            </button>


        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <input type="hidden" name="course_annual_id" value="{{$courseAnnual->id}}">
            <input type="hidden" name="token" value="{{csrf_token()}}">
            {{--here what i need to write --}}

            <div id="score_table" class="handsontable htColumnHeaders">

            </div>
        </div>
    </div>

    <div class="box box-success" id="box_footer">
        <div class="box-body">
            <div class="clearfix"></div>
        </div><!-- /.box-body -->
    </div><!--box-->

    <div class="loading">
        <i class="fa fa-refresh fa-spin"></i>
    </div>
@stop
@section('after-scripts-end')
    {!! Html::script('plugins/handsontable-test/handsontable.full.min.js') !!}
    {!! Html::script('score/js/proficency_score.js') !!}
    <script>

        $(document).ready(function() {

            var Fraud = '{{\App\Models\Enum\ScoreEnum::Fraud}}';
            var Absence = '{{\App\Models\Enum\ScoreEnum::Absence}}';
            var colProperties = JSON.parse('<?php echo  json_encode($competencies);?>');
            var propRenderer =  JSON.parse('<?php echo  json_encode($renderer);?>');
            var cellMaxValue =  JSON.parse('<?php echo  json_encode($cellMaxValue);?>');
            var additionalProp = JSON.parse('<?php echo  json_encode($additionalCols);?>');
            setting.nestedHeaders = JSON.parse('<?php echo  json_encode($headers);?>');

            setting. hiddenColumns =  {
                columns: [parseInt('{{count($headers[1]) }}')],
                indicators: false
            };

            setting.colWidths = JSON.parse('<?php echo  json_encode($colWidths);?>');

            declareColumnHeaderDataEmpty(colProperties);

            var colorRenderer = function (instance, td, row, col, prop, value, cellProperties) {

                Handsontable.renderers.TextRenderer.apply(this, arguments);


                $.each(propRenderer, function (index, object) {

                    if(prop == object.index) {

                        if (jQuery.isNumeric(value)) {
                            if( value < parseInt(object.min)) {

                                if(value != 0 && value != null && value != '') {

                                    td.style.backgroundColor = object.color;
                                }
                            } else if (value > parseInt(object.max)) {
                                td.style.backgroundColor = object.color;
                            }
                        } else {

                            if(value == Fraud || value == Absence) {
                                td.style.backgroundColor = 'gray';
                            } else {
                                td.style.backgroundColor = '#A41C00';
                            }
                        }

                    }
                });

                $.each(additionalProp, function(key, js_object) {

                    if(prop == js_object.index) {

                        if( value < parseInt(js_object.min)) {

                            if(value != 0 && value != null && value != '') {

                                if(js_object.color != null) {

                                    td.style.backgroundColor = js_object.color;
                                } else {

                                    td.style.backgroundColor = 'red';
                                }

                            }
                        } else if (value > parseInt(js_object.max)) {
                            td.style.backgroundColor = js_object.color;
                        }

                    }

                })
            };

            setting.cells = function (row, col, prop) {

                var cellProperties = {};

                $.each(propRenderer, function (index, object) {
                    if(prop == object.index) {
                        cellProperties.readOnly = object.readOnly;
                    }


                });

                this.renderer = colorRenderer;

                return cellProperties;
            };

            setting.afterChange =  function (changes, source) {

                    if(changes) {

                        $.each(changes, function (index, element) {

                            var change = element;
                            var rowIndex = change[0];
                            var columnIndex = change[1];
                            var oldValue = change[2];
                            var newValue = change[3];
                            var col_student_id = hotInstance.getDataAtProp('student_id_card'); //---array data of column student_id
                            var current_table_data = hotInstance.getData();
                            var currentColDataChange = hotInstance.getDataAtProp(columnIndex)
                            var currentRowDataChange = hotInstance.getDataAtRow(rowIndex);
                            var maxValue = cellMaxValue[columnIndex];
                            var student_annual_id = currentRowDataChange[currentRowDataChange.length -1];

                            var element_change = onInputScoreChange(newValue, maxValue, Fraud, Absence, student_annual_id, oldValue);
                            if(!$.isEmptyObject(element_change)) {
                                colDataArray[columnIndex].push(element_change)

                            }
                            CELL_CHANGE.push(element_change);
                            checkIfStringValExist(currentColDataChange, columnIndex, maxValue, Fraud, Absence)
                        });
                    }
            };



            initTale()

            $(window).on('resize', function(){
                var table_size = $('.box-body').width();
                setting.width=table_size;
                hotInstance.updateSettings({
                    width:table_size
                });
            });

            $('#save').on('click', function(e) {

                $.each(array_col_status, function(key, val) {
                    if(val === false) {
                        objectStatus.status = val;
                        objectStatus.colName = key;
                    }
                });

                if(CELL_CHANGE.length > 0 ) {

                    if(objectStatus.status == true) {

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
                                sendRequest(propRenderer);
                            }
                        });
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

                } else {
                    notify('error', 'There are no changes!!', 'Info');
                }
            });

            $(document).on('click', '#calculate', function (e) {
                calcuateScore('POST',  '{{route('course_annual.competency_score.calculate')}}');
            })

        });



        function initTale()
        {

            /*---ajax load to get data---*/

            toggleLoading(true);
            $.ajax({
                type: 'POST',
                url: '/admin/course/course-annual/proficency/score-data',
                data: {course_annual_id: $('input[name=course_annual_id]').val(), _token:$('input[name=token]').val()},
                dataType: "json",
                success: function (resultData) {

                    setting.data = resultData;
                    if(hotInstance) {
                        hotInstance.updateSettings({
                            data: resultData,
                        });
                    } else {
                        setting = calculateSite(setting)
                        hotInstance = new Handsontable(document.getElementById('score_table'), setting);
                    }

                    notify('info', 'Data Loaded!', 'Info')
                    toggleLoading(false)
                },

                error:function(response) {
                    toggleLoading(false)
                    notify('warning', 'No Data Loaded!', 'Warning')
                }
            });

            /*---end ajax load to get data---*/

        }


    </script>
@stop