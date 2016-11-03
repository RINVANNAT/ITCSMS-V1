@if($exam->type_id == 1)
    <style>
        table, th, td {
            border: 1px solid black;
            font-size: 12pt;
        }
    </style>
    <div class="col-lg-12 col-md-12">

        <table class="table " style="border: 2px solid black">
            <tr>
                <th class="text-center" colspan="2"> <span> <i class="fa fa-bar-chart " style="font-size: 16pt"> Statistic: <strong style="color: #00a7d0">Engineer Entrance Exam</strong> </i></span> </th>


            </tr>
            <tr>
                <td> {!! Form::label('date_start_end', "Academic Year", ['class' => ' control-label ']) !!}</td>
                <td>  <strong style="color: #00a7d0"><?php $academic = explode(' ',$exam->name); echo $academic[2];?></strong></td>



            </tr>
            <tr>
                <td>{!! Form::label('date_start_end', trans('labels.backend.exams.fields.date_start_end'), ['class' => ' control-label ']) !!} </td>
                <td> <strong style="color: #00a7d0"> <?php $start = explode(' ', $exam->date_start); $end = explode(' ', $exam->date_end); echo $start[0].' - '. $end[0];?></strong></td>


            </tr>
        </table>

    </div>
    <div id="chart_blog">

        <div class="form-group">
            <div class="col-lg-12">
                <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>

            </div>

            <div class="col-lg-12">
                <div id="candidate_engineer_result" style="min-width: 310px; height: 400px; margin: 0 auto"></div>

            </div>

            <div class="col-lg-12">
                <div id="candidate_registration" style="min-width: 310px; height: 400px; margin: 0 auto"></div>

            </div>

        </div>

        <div id="table_data">

        </div>
    </div>
@endif

@if($exam->type_id == 2)
    <style>
        table, th, td {
            border: 1px solid black;
            font-size: 12pt;
        }
    </style>

    <div class="col-lg-12 col-md-12">
        <table class="table " style="border: 2px solid black">
            <tr>
                <th class="text-center" colspan="2"> <span> <i class="fa fa-bar-chart " style="font-size: 16pt"> Statistic: <strong style="color: #00a7d0">Candidate DUT Selection</strong> </i></span> </th>


            </tr>
            <tr>
                <td> {!! Form::label('date_start_end', "Academic Year", ['class' => ' control-label ']) !!}</td>
                <td>  <strong style="color: #00a7d0"><?php $academic = explode(' ',$exam->name); echo $academic[1];?></strong></td>



            </tr>
            <tr>
                <td>{!! Form::label('date_start_end', trans('labels.backend.exams.fields.date_start_end'), ['class' => ' control-label ']) !!} </td>
                <td> <strong style="color: #00a7d0"> <?php $start = explode(' ', $exam->date_start); $end = explode(' ', $exam->date_end); echo $start[0].' - '. $end[0];?></strong></td>


            </tr>
        </table>

    </div>

    <div id="blog_chart_dut">


            <div class="col-lg-12">
                <div id="candidate_dut_registration" style="min-width: 310px; height: 400px; margin: 0 auto"></div>

            </div>

            <div id="table_dut_data">

            </div>


        <div class="col-lg-12">
            <div id="result_candidate_dut" style="min-width: 310px; height: 400px; margin: 0 auto"></div>

        </div>


        <div class="col-lg-12">
            <div id="student_dut_registration" style="min-width: 310px; height: 400px; margin: 0 auto"></div>

        </div>



    </div>

@endif


