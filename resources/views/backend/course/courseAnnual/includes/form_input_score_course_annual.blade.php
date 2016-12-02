@extends ('backend.layouts.popup_master')

@section ('title', 'Course Annual' . ' | ' . 'Edit Course Annual')

@section('content')

    <div class="box box-success">

        <div class="box-header with-border">
            <h3 class="box-title">Score Course Annually</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body panel">

            <div class="col-sm-12" style="text-align: center">

                <h4 style="text-align: center"> Mathematic </h4>

                <div class="col-sm-1">

                </div>

                <div class="col-sm-10">
                    <div id="score_table"> </div>
                </div>

                <div class="col-sm-1">

                </div>



            </div>
        </div>

    </div>
@stop

@section('after-scripts-end')

    {!! Html::style('plugins/handsontable/handsontable.full.css') !!}
    {!! Html::script('plugins/handsontable/handsontable.full.js') !!}

    {{--myscript--}}


    <script>

        var hotInstance;

        var setting1 = {
            data: JSON.parse('<?php echo ($studentData)?>'),
            colHeaders: ['Student ID', 'Student Name', 'Gender', 'Absence', 'Total Score'],
            columns: [{
                data: 'student_id'
            }, {
                data: 'student_name'
            }, {
                data: 'student_gender'
            }, {
                data: 'abse'
            }, {
                data: 'total'
            }],
            rowHeaders: true,
            minSpareRows: 1,
            minRows: 5,
            manualColumnResize: true,
            manualRowResize: true,
            fixedColumnsLeft: 1,
            manualColumnMove: true,
            filters: true,
            contextMenu: true
        };

        hotInstance = new Handsontable(jQuery("#score_table")[0], setting1);

    </script>

@stop