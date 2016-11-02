@if($exam->type_id == 1)

    <div class="col-lg-12 col-md-12">
        <div class="col-lg-4 col-md-4">
            <div class="">
                <span> <i class="fa fa-bar-chart " style="font-size: 16pt"> Statistic: <strong style="color: #00a7d0">Engineer Entrance Exam</strong> </i></span>

                <h4>Academic Year: <strong style="color: #00a7d0"><?php $academic = explode(' ',$exam->name); echo $academic[2];?></strong></h4>
                <h4>Date: <strong style="color: #00a7d0"> <?php $start = explode(' ', $exam->date_start); $end = explode(' ', $exam->date_end); echo $start[0].' - '. $end[0];?></strong></h4>

            </div>

        </div>

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

    <div class="col-lg-12 col-md-12">
        <div class="col-lg-4 col-md-4">
            <div class="">
                <span> <i class="fa fa-bar-chart " style="font-size: 16pt"> Statistic: <strong style="color: #00a7d0">Selection DUT Students</strong> </i></span>

                <h4>Academic Year: <strong style="color: #00a7d0"><?php $academic = explode(' ',$exam->name); echo $academic[1];?></strong></h4>
                <h4>Date: <strong style="color: #00a7d0"> <?php $start = explode(' ', $exam->date_start); $end = explode(' ', $exam->date_end); echo $start[0].' - '. $end[0];?></strong></h4>

            </div>

        </div>

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


