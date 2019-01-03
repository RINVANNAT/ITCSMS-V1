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
            <div class="row title" style="margin-top: 8mm;">
                <div class="pull-left">
                    <p class="text-14" style="font-size: 13pt; font-weight: bold">
                        Institute of Technology of Cambodia </br>
                        English Section
                    </p>
                </div>
                <div class="pull-right text-center" style="margin-top: -7mm">
                    <p class="text-14" style="font-size: 14pt !important; line-height: 6mm; margin-bottom: 0px; font-family: franklin_gothic !important;">
                        Kingdom of Cambodia <br/>
                        Nation Religion King  <br/>
                        <span style="font-family: tactieng !important; font-size: 35pt">7</span>
                    </p>
                </div>
            </div>

            <div class="row text-center attestation_title">
               <span>
                   ATTESTATION of ENGLISH LANGUAGE LEVEL
               </span>
            </div>

            <div class="row text-center" style="font-family: 'Calibri Light'; font-size: 13pt; /*margin-top: 15mm*/ margin-top: 8mm;">
            <span>
                The Director of the Institute of Technology of Cambodia certifies that 
            </span>
            </div>

            <div class="row text-center panel_top_8" style="font-family: 'Calibri Light'; font-size: 13pt; /*margin-top: 12mm*/margin-top: 8mm; font-weight: bold">
            <span style="line-height: 1.6">
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
                {{strtoupper($student->name_latin)}} <br/> ID : {{$student->id_card}} <br/> Born on
                {{$day." ".$month.", ".$year}}
            </span>
            </div>

            <div class="row">

                <div class="no-padding text-center" style="width: 70mm;float: left">

                <span>
                    Transcript
                </span>
                    <table class="table" style="width: 100%;font-size: 13px; line-height: 3mm">
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
                                $total = 0;
                                foreach($score as $competency_id => $competency_score){
                                    $property = json_decode($competencies[$competency_id]->properties);
                                    if(strtolower($competencies[$competency_id]->name) == "l"){
                                        $ce = '<span class="red_col">'.$competency_score->score.'</span>/'.$property->max;
                                        $total = $total + $competency_score->score;
                                    } else if(strtolower($competencies[$competency_id]->name) == "r"){
                                        $co = '<span class="red_col">'.$competency_score->score.'</span>/'.$property->max;
                                        $total = $total + $competency_score->score;
                                    } else if(strtolower($competencies[$competency_id]->name) == "w"){
                                        $pe = '<span class="red_col">'.$competency_score->score.'</span>/'.$property->max;
                                        $total = $total + $competency_score->score;
                                    } else if(strtolower($competencies[$competency_id]->name) == "s"){
                                        $po = '<span class="red_col">'.$competency_score->score.'</span>/'.$property->max;
                                        $total = $total + $competency_score->score;
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

                            <td style="border-bottom: 0px !important; border-left: none !important; border-right: none !important; font-weight: bold">
                                {!! number_format((float)$total, 2, '.', '') !!} /100
                            </td>

                        </tr>

                        </tbody>
                    </table>
                    <div class="row" style="margin-top: 5%;line-height: 3mm;">

                        <p style="font-size: 9pt; font-family: 'Calibri Light (Headings)'; text-align: left; line-height: 0pt !important;">
                            *Notes
                        </p>
                        <p style="text-align: left !important;font-size: 7.5pt; font-family: 'Calibri Light (Headings)'; text-align: left">
                            - The minimum requirement average score for each skill is 5/25. <br/>
                            - The success threshold to obtain the level is 50/100. <br/>
                            - The attestation will not be issued for the 2nd time. <br/>
                            - This attestation is issued to the bearer for any use deemed applicable.
                        </p>
                        <p style="text-align: left !important;font-size: 10pt; font-family: 'Calibri Light (Headings)'; text-align: left">
                            N<sup style="font-weight: lighter;">0</sup> &nbsp;&nbsp;&nbsp;
                            @if(isset($certificate_references[$student->id]))
                                @if((int) $certificate_references[$student->id]['ref_number'] < 10)
                                    00{{$certificate_references[$student->id]['ref_number']}} &nbsp;&nbsp;&nbsp;
                                @elseif(((int) $certificate_references[$student->id]['ref_number']) >= 10 && ((int) (int) $certificate_references[$student->id]['ref_number']) < 100)
                                    0{{$certificate_references[$student->id]['ref_number']}} &nbsp;&nbsp;&nbsp;
                                @else
                                    {{$certificate_references[$student->id]['ref_number']}}
                                @endif
                            @else
                                ............ITC/SA
                            @endif
                        </p>
                    </div>

                </div>
                <div style="width: 160mm; float: left; margin-left: 15mm; text-align: justify">
                    <p style="font-family: 'Calibri Light'; font-size: 13pt; line-height: 1.5;">
                        Has successfully passed the English Language Exam equivalent to Level <span style="font-size: 30px !important;">{{$level}}</span> of the Common European Framework of Reference for Language (CEFR) at the time of level exam session taking place at the Institute of Technology of Cambodia on {{ \Carbon\Carbon::createFromFormat("d/m/Y",$exam_start)->format('F, jS Y') }}.
                    </p>

                    <div class="pull-right" style="margin-top: 8mm">
                        <p class="text-center title" style="font-size: 13pt">
                            <?php
                            if(($issued_date == null) or ($issued_date == "")){
                                $now = \Carbon\Carbon::now();
                            } else {
                                $now = \Carbon\Carbon::createFromFormat("d/m/Y",$issued_date);
                            }
                            $c_day = $now->formatLocalized('%d');
                            $c_month = $now->formatLocalized('%B');
                            $c_year = $now->formatLocalized('%Y');
                            ?>
                            <b>Phnom Penh, {{$c_day." ".$c_month." ".$c_year}}</b>
                        <p style="margin-top: -10px !important; text-align: center">
                            For The Director of the Institute of Technology of Cambodia <br/>
                            Deputy Director
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