@extends('backend.layouts.printing_landscape_a4')
@section('title')
    ITC-SMS | Print Certificate
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
            /*margin-top: 10mm;*/
            margin-top: 5mm;
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
        @media print {
            .red_col {
                color: red !important;
            }
            .attestation_title span{
                /*margin-top: 10mm;*/
                font-size: 24pt;
                margin-top: 5mm;
                font-family: "Calibri Light (Headings)";
                font-weight: bold;
                color: #0F6AB4 !important;
            }
            .level {
                font-size: 21pt !important;
                color: #0a6aa1 !important;
            }
        }
    </style>
@stop
@section('content')
    @foreach($students as $student)
        <div class="page">
            <div class="row text-right title" style="margin-top: 10mm;">
                <p class="p1">
                    Kingdom of Cambodia
                </p>
                <p style="margin-right: 18px; font-weight: normal">
                    Nation Religion King
                </p>
                <p class="p2" style="text-align: left !important;">
                    Institut de Technologie du Cambodge
                </p>
                <p style="text-align: left !important; font-weight: normal;">
                    N<sup style="font-weight: lighter;">0</sup>............ITC/ES
                </p>
            </div>

            <div class="row text-center attestation_title">
               <span>
                   ATTESTATION of ENGLISH LANGUAGE LEVEL
               </span>
            </div>

            <div class="row text-center" style="font-family: 'Calibri Light'; font-size: 12pt; /*margin-top: 15mm*/ margin-top: 8mm;">
            <span>
                The Director of the Institute of Technology of Cambodia certified that 
            </span>
            </div>

            <div class="row text-center panel_top_8" style="font-family: 'Calibri Light'; font-size: 12pt; /*margin-top: 12mm*/margin-top: 8mm; font-weight: bold">
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
                {{strtoupper($student->name_latin)}}, ID : {{$student->id_card}}, Born on
                {{$day." ".$month.", ".$year}}
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
                            <th class="text-center">Band score level</th>
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
                                    if(strtolower($competencies[$competency_id]->name) == "l"){
                                        $ce = '<span class="red_col">'.$competency_score->score.'</span>/'.$property->max;
                                    } else if(strtolower($competencies[$competency_id]->name) == "r"){
                                        $co = '<span class="red_col">'.$competency_score->score.'</span>/'.$property->max;
                                    } else if(strtolower($competencies[$competency_id]->name) == "w"){
                                        $pe = '<span class="red_col">'.$competency_score->score.'</span>/'.$property->max;
                                    } else if(strtolower($competencies[$competency_id]->name) == "s"){
                                        $po = '<span class="red_col">'.$competency_score->score.'</span>/'.$property->max;
                                    } else if(strtolower($competencies[$competency_id]->name) == "ielts band score"){
                                        $total = '<span class="red_col">'.$competency_score->score.'</span>/'.$property->max;
                                    }
                                }
                            }
                        ?>

                        <tr class="set_border">
                            <td style="text-align:center; vertical-align:middle;" > Reading </td>
                            <td>
                                {!! $co !!}
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
                                {!! $ce !!}
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

                        <p style="font-size: 9pt; font-family: 'Calibri Light (Headings)'; text-align: left; line-height: 0pt !important;">
                            *Overall Band score
                        </p>
                        <p style="text-align: left !important;font-size: 6.5pt; font-family: 'Calibri Light (Headings)'; text-align: left">
                            Speaking/5+Reading/4+Writing/4+Listening/4<br/>
                            38 out of 40 IELTS Band score 9.0 CEFR: C2<br/>
                            37 out of 40 IELTS Band score 8+ (8.5) CEFR: C2<br/>
                            30 out of 40 IELTS Band score 7+ (7.5-8) CEFR: C1<br/>
                            23 out of 40 IELTS Band score 6.0+ (6.5-7) CEFR: B2<br/>
                            16 out of 40 IELTS Band score 5.0+ (5.5-6) CFER: B1<br/>
                            9 out of 40 IELTS Band score4.0+ (4.5-5) CFER: A2
                        </p>
                    </div>

                </div>
                <div style="width: 160mm; float: left; margin-left: 15mm; text-align: justify">
                    <p style="font-family: 'Calibri Light'; font-size: 12pt; line-height: 1.5;">
                        Has successfully passed the English Exam equivalent to Common European Framework of Reference for Language (CEFR) at session talking place at the Institute of Technology of Cambodia on {{ \Carbon\Carbon::createFromFormat("d/m/Y",$exam_start)->format('F, jS Y') }}.
                    </p>

                    <div class="pull-right" style="margin-top: 8mm">
                        <p class="text-center title" style="font-size: 12pt">
                            <?php
                                $now = \Carbon\Carbon::now();
                                $c_day = $now->formatLocalized('%d');
                                $c_month = $now->formatLocalized('%B');
                                $c_year = $now->formatLocalized('%Y');
                            ?>
                            <b>Phnom Penh, ITC, {{$c_day." ".$c_month." ".$c_year}}</b>
                        <p style="margin-top: -10px !important;">
                            P. The Director of the Institute of Technology of Cambodia
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