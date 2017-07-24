@extends('backend.layouts.printing_landscape_a4')
@section('title')
    ITC-SMS | Certificate
@stop

@section('after-styles-end')
    <link rel="stylesheet" media="print, screen" href="{{ url('css/backend/transcript.css') }}">
    <style>
        .table > thead > tr > th, .table > thead > tr > td, .table > tbody > tr > th, .table > tbody > tr > td, .table > tfoot > tr > th, .table > tfoot > tr > td{
            padding: 3px !important;
        }
        *{
            font-family: "Calibri Light" !important;
        }
        .title {
            font-weight: bold;
            line-height: 4mm;
        }

        .p1 {
            font-size: 14pt;
        }
        .p2 {
            font-size: 13pt;
        }

        .attestation_title {
            margin-top: 10mm;
            font-size: 24pt;
            font-family: "Calibri Light (Headings)";
            color: #0F6AB4;
            font-weight: bold;
        }

        .panel_top_8{
            margin-top: 5%;
        }
        .red_col {
            color: red;
        }
        .set_border_ {
            border: 1px solid black !important;
        }

        table tr {
            border: 1px solid black !important;
        }
        table thead th  {
            border: 1px solid black !important;
        }
        table tr td {
            border: 1px solid black !important;
            font-size: 9pt;
            font-family: "Calibri Light";
        }

    </style>
@stop
@section('content')

    @foreach($students as $student)
        <div class="page">
            <div class="row text-center title" style="margin-top: 10mm;">
                <p class="p1">
                    Ministry of Education Youth and Sport
                </p>
                <p class="p2">
                    Institute of Technology of Cambodia
                </p>
                <p class="p2">
                    English Section
                </p>
            </div>

            <div class="row text-center attestation_title">
           <span>
               ATTESTATION of ENGLISH LANGUAGE LEVEL
           </span>
            </div>

            <div class="row text-center" style="font-family: 'Calibri Light'; font-size: 12pt; margin-top: 15mm">
            <span>
                The Director of the Institute of Technology of Cambodia certified that 
            </span>
            </div>

            <div class="row text-center panel_top_8" style="font-family: 'Calibri Light'; font-size: 12pt; margin-top: 12mm; font-weight: bold">
            <span>
                @if($student->gender_id == 1)
                    Mr.
                @else
                    Miss.
                @endif
                <?php
                    $day = \Carbon\Carbon::createFromFormat("Y-m-d H:i:s",$student->dob)->formatLocalized('%d');
                    $month = \Carbon\Carbon::createFromFormat("Y-m-d H:i:s",$student->dob)->formatLocalized('%B');
                    $year = \Carbon\Carbon::createFromFormat("Y-m-d H:i:s",$student->dob)->formatLocalized('%Y');
                ?>
                {{strtoupper($student->name_latin)}},
                , born
                {{$day." ".$month.", ".$year}}, ID : {{$student->id_card}}
            </span>
            </div>

            <div class="row" style="margin-top: 7mm">

                <div class="no-padding text-center" style="width: 70mm;float: left">

                <span>
                    Transcript
                </span>
                    <table class="table" style="width: 100%;font-size: 12px; line-height: 3mm">
                        <thead>
                        <tr class="set_border">
                            <th>Band score level</th>
                            <th style="text-align: center">Score*</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                            $ce = "";
                            $co = "";
                            $pe = "";
                            $po = "";
                            $total = "";
                            if(isset($scores[$student->id])){
                                $score = $scores[$student->id];
                                foreach($score as $competency_id => $competency_score){
                                    $property = json_decode($competencies[$competency_id]->properties);
                                    if(strtolower($competencies[$competency_id]->name) == "ce"){
                                        $ce = '<span class="red_col">'.$competency_score->score.'</span> /'.$property->max;
                                    } else if(strtolower($competencies[$competency_id]->name) == "co"){
                                        $co = '<span class="red_col">'.$competency_score->score.'</span> /'.$property->max;
                                    } else if(strtolower($competencies[$competency_id]->name) == "pe"){
                                        $pe = '<span class="red_col">'.$competency_score->score.'</span> /'.$property->max;
                                    } else if(strtolower($competencies[$competency_id]->name) == "po"){
                                        $po = '<span class="red_col">'.$competency_score->score.'</span> /'.$property->max;
                                    } else if(strtolower($competencies[$competency_id]->name) == "total score"){
                                        $total = '<span class="red_col">'.$competency_score->score.'</span> /'.$property->max;
                                    }
                                }
                            }
                        ?>

                        <tr class="set_border">
                            <td style="text-align:center; vertical-align:middle;" > Reading </td>
                            <td>
                                {!! $ce !!}
                            </td>
                        </tr>
                        <tr class="set_border">

                            <td style="text-align:center; vertical-align:middle;" > Writing </td>
                            <td>
                                {!! $pe !!}
                            </td>
                        </tr>

                        <tr class="set_border">
                            <td style="text-align:center; vertical-align:middle;" > Listening </td>
                            <td>
                                {!! $co !!}
                            </td>
                        </tr>
                        <tr class="set_border">

                            <td style="text-align:center; vertical-align:middle;" > Speaking </td>
                            <td>
                                {!! $po !!}
                            </td>
                        </tr>

                        <tr style="border-bottom: 0px !important; border-left: none !important; border-right: none !important;">
                            <td style="border-bottom: 0px !important; border-left: none !important; border-right: none !important;">
                                Total score
                            </td>

                            <td style="border-bottom: 0px !important; border-left: none !important; border-right: none !important;">
                                {!! $total !!}
                            </td>

                        </tr>

                        </tbody>
                    </table>

                <span class="red_col">
                    Passed
                </span>


                    <div class="row" style="margin-top: 5%;line-height: 3mm;">

                        <p style="font-size: 9pt; font-family: 'Calibri Light (Headings)'; text-align: left">
                            *Overall Band score
                        </p>
                        <p>
                            Speaking/5+Reading/4+Writing/4+Listening/4
                            38 out of 40 IELTS Band score 9.0 CEFR: C2
                            37 out of 40 IELTS Band score 8+ (8.5) CEFR: C2
                            30 out of 40 IELTS Band score 7+ (7.5-8) CEFR: C1
                            23 out of 40 IELTS Band score 6.0+ (6.5-7) CEFR: B2
                            16 out of 40 IELTS Band score 5.0+ (5.5-6) CFER: B1
                            9 out of 40 IELTS Band score4.0+ (4.5-5) CFER: A2
                        </p>

                        <p style="font-size: 6pt; font-family: 'Calibri Light (Headings)'; text-align: left">
                            *The minimum requirement Band score average is 5
                            *The attestation cannot issue for the 2nd time
                        </p>
                    </div>



                </div>
                <div style="width: 160mm; float: left; margin-left: 15mm; text-align: justify">
                    <?php
                        $exam_day = "";
                        $start = \Carbon\Carbon::createFromFormat("d/m/Y",$exam_start);
                        $exam_start_day = $start->formatLocalized('%d');
                        $exam_start_month = $start->formatLocalized('%B');
                        $exam_start_month = month_mois($exam_start_month);
                        $exam_start_year = $start->formatLocalized('%Y');

                        $end = \Carbon\Carbon::createFromFormat("d/m/Y",$exam_end);
                        $exam_end_day = $end->formatLocalized('%d');
                        $exam_end_month = $end->formatLocalized('%B');
                        $exam_end_month = month_mois($exam_end_month);
                        $exam_end_year = $end->formatLocalized('%Y');


                        if(($exam_start_day === $exam_end_day) and ($exam_start_month === $exam_end_month) and ($exam_start_year === $exam_end_year)){
                            $exam_day = "au ". $exam_start_day ." ". $exam_start_month. " " . $exam_start_year;
                        } else if (($exam_start_month === $exam_end_month) and ($exam_start_year === $exam_end_year)){
                            $exam_day = "du ". $exam_start_day ." au ".$exam_end_day." ". $exam_start_month. " " . $exam_start_year;
                        } else if($exam_start_year === $exam_end_year){
                            $exam_day = "du ". $exam_start_day ." ".$exam_start_month." au ".$exam_end_day." ". $exam_end_month. " " . $exam_start_year;
                        } else {
                            $exam_day = "du ". $exam_start_day ." ".$exam_start_month." ".$exam_start_year." au ".$exam_end_day." ". $exam_end_month. " " . $exam_end_year;
                        }
                    ?>
                    <p style="font-family: 'Calibri Light'; font-size: 12pt; ">
                        Has successfully passed the English Language Level
                        <span style="font-size: 21pt; color: #0a6aa1">
                            <?php
                                if($student->grade_id == 1) {
                                    echo "A1";
                                } else if($student->grade_id == 2){
                                    echo "A2";
                                } else if($student->grade_id == 3){
                                    echo "B1";
                                } else if($student->grade_id == 4){
                                    echo "B2";
                                } else if($student->grade_id == 5){
                                    echo "C1";
                                }
                            ?>
                        </span>
                        of the Common European Framework of Reference for Language (CEFR) at the time of level examination session took place at the Institute of Technology of Cambodia in {{$exam_day}}.
                    </p>

                    <p style="font-family: 'Calibri Light'; font-size: 12pt; font-style: italic">
                        To assert that of right
                    </p>

                    <div class="pull-right" style="margin-top: 8mm">
                        <p class="text-center title" style="font-size: 12pt">
                            <?php
                                $now = \Carbon\Carbon::now();
                                $c_day = $now->formatLocalized('%d');
                                $c_month = month_mois($now->formatLocalized('%B'));
                                $c_year = $now->formatLocalized('%Y');
                            ?>
                            <b>Phnom Penh, ITC, {{$c_day." ".$c_month." ".$c_year}}</b>
                        <p style="margin-top: -10px !important;">
                            Director of the Institute of Technology of Cambodia
                        </p>
                        </p>
                    </div>

                </div>

            </div>
        </div>
    @endforeach

@endsection

@section('scripts')
    <script>

    </script>
@stop