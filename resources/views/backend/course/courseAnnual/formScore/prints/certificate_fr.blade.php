@extends('backend.layouts.printing_landscape_a4_certificate')
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
            line-height: 4mm;
        }

        .blue {
            font-family: "arial-rounded" !important;
            color: #0F6AB4 !important;
        }
        .text-12{
            font-size: 10pt;
        }
        .text-13{
            font-size: 12pt;
        }
        .text-20{
            font-size: 20pt;
        }
        .text-21{
            font-size: 23pt;
        }

        .attestation_title {
            margin-top: 10mm;
            font-size: 23pt;
        }
        .description{
            font-size: 9pt; font-family: 'Calibri Light (Headings)'; text-align: left
        }
        .description-small{
            font-size: 8pt; font-family: 'Calibri Light (Headings)'; text-align: left
        }
        .description-extra-small{
            font-style: italic !important;
            font-size: 6pt; font-family: 'Calibri Light (Headings)'; text-align: left
        }

        .panel_top_8{
            margin-top: 5%;
        }
        .red_col {
            color: red !important;
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
            font-style: italic;
            font-family: "Calibri Light";
        }
        table.pass-result, table.pass-result tr, table.pass-result td{
            font-size: 9pt;
        }
        table.fail-result, table.fail-result tr, table.fail-result td {
            font-size: 12pt;
        }

        .td_result{
            font-style: normal !important;
        }

    </style>
@stop
@section('content')

    @foreach($students as $student)
        <?php
        $ce = "";
        $co = "";
        $pe = "";
        $po = "";
        $total = "";
        $admission = "";
        $is_admis = true;
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
                } else if(strtolower($competencies[$competency_id]->name) == "admission"){
                    if((strtolower($competency_score->score) == "non admis") or (strtolower($competency_score->score) == "non admise")){
                        $is_admis = false;
                    }
                    $admission = '<span style="margin-left: 7mm" class="red_col">'.strtoupper($competency_score->score).'</span>';
                }
            }
        }
        ?>
        @if(!$is_admis )
            <div class="page">
                <div class="row text-center title" style="margin-top: 10mm;">
                    <p class="text-20">
                        Institut de Technologie du Cambodge
                    </p>
                    <p class="text-13">
                        Section de Français
                    </p>
                </div>
                <div class="row text-center attestation_title text-21">
                   <span>
                       RESULTAT de NIVEAU de LANGUE FRANÇAISE
                   </span>
                </div>
                <table class="table fail-result" style="width: 100%;font-size: 12pt; line-height: 3mm;margin-top: 10mm">
                    <thead>
                    <tr class="set_border">
                        <th colspan="2" rowspan="2">Compétences évaluées comblées</th>
                        <th style="text-align: center">Note*</th>
                    </tr>
                    </thead>
                    <tbody>

                    <tr class="set_border">
                        <td rowspan="2" class="td_result" style="text-align:center; vertical-align:middle;" > ÉCRIT </td>
                        <td>Compréhension</td>
                        <td class="td_result">
                            {!! $ce !!}
                        </td>
                    </tr>
                    <tr class="set_border">

                        <td >Production</td>
                        <td class="td_result">
                            {!! $pe !!}
                        </td>
                    </tr>

                    <tr class="set_border">
                        <td rowspan="2" class="td_result" style="text-align:center; vertical-align:middle;"  > ORAL </td>
                        <td>Compréhension</td>
                        <td class="td_result">
                            {!! $co !!}
                        </td>
                    </tr>
                    <tr class="set_border">

                        <td >Production</td>
                        <td class="td_result">
                            {!! $po !!}
                        </td>
                    </tr>

                    <tr style="border-bottom: 0px !important; border-left: none !important; border-right: none !important;">

                        <td style="border-bottom: 0px !important; border-left: none !important; border-right: none !important;"> </td>

                        <td style="border-bottom: 0px !important; border-left: none !important; border-right: none !important; font-style: normal">
                            Note Finale
                        </td>

                        <td style="border-bottom: 0px !important; border-left: none !important; border-right: none !important;">
                            {!! $total !!}
                        </td>

                    </tr>

                    </tbody>
                </table>
                {!! $admission !!}


                <div class="row description" style="margin-top: 5%;line-height: 3mm;">

                    <div class="col-md-12 col-xs-12">
                        <p class="description">
                            *Note minimale requise par épreuve : 05/25 <br/>
                            *Seuil de réussite pour obtenir le niveau : 50/100
                        </p>
                        <p class="description-small">
                            <span class="blue">Le niveau A1 :</span> niveau introductif ou de découverte
                        </p>

                        <p class="description-extra-small">
                            Cette originale attestation est délivrée en un seul exemplaire. Aucun double ne pourra être refait. <br/>
                            Cette attestation est accompagnée par le bulletin de notes de l’étudiant (e) intéressé(e).
                        </p>
                    </div>
                </div>
            </div>
        @else
            <div class="page">
                <div class="row text-center title" style="margin-top: 10mm; margin-left: -17mm">
                    <p class="text-20">
                        Institut de Technologie du Cambodge
                    </p>
                    <p class="text-13">
                        Section de Français
                    </p>
                </div>
                <div class="row">
                    <span class="text-13" style="font-style: italic; margin-left: 5mm">N<sup>o</sup>Réf.: .................ITC</span>
                </div>

                <div class="row text-center attestation_title text-21" style="margin-left: -17mm">
                   <span class="blue">
                       ATTESTATION de NIVEAU de LANGUE FRANÇAISE
                   </span>
                </div>

                <div class="row text-center" style="font-family: 'Calibri Light'; font-size: 12pt; margin-top: 10mm;margin-left: -17mm">
                    <span>
                        Le Directeur de l’Institut de Technologie du Cambodge atteste que 
                    </span>
                </div>

                <div class="row text-center panel_top_8" style="font-family: 'Calibri Light'; font-size: 12pt; margin-top: 10mm; font-weight: bold;margin-left: -17mm">
            <span>
                @if($student->gender_id == 1)
                    M.
                @else
                    Mlle
                @endif
                <?php
                $day = \Carbon\Carbon::createFromFormat("Y-m-d H:i:s",$student->dob)->formatLocalized('%d');
                $month = \Carbon\Carbon::createFromFormat("Y-m-d H:i:s",$student->dob)->formatLocalized('%B');
                $month = strtolower(month_mois($month));
                $year = \Carbon\Carbon::createFromFormat("Y-m-d H:i:s",$student->dob)->formatLocalized('%Y');
                ?>
                {{strtoupper($student->name_latin)}},
                @if($student->gender_id == 1)
                    né
                @else
                    née
                @endif

                le {{$day." ".$month." ".$year}}, ID : {{$student->id_card}}
            </span>
                </div>

                <div class="row" style="margin-bottom: -2mm">
                    <span style="margin-left: 22mm; line-height: 3mm;">
                        Bulletin de notes
                    </span>
                </div>
                <div class="row">

                    <div class="no-padding text-center" style="width: 63mm;float: left">
                        <table class="table pass-result" style="width: 100%;font-size: 12px; line-height: 3mm; margin: 5mm 0 3mm 4mm;">
                            <thead>
                            <tr class="set_border">
                                <th colspan="2" rowspan="2" style="text-align: center">Compétences évaluées comblées</th>
                                <th style="text-align: center">Note*</th>
                            </tr>
                            </thead>
                            <tbody>

                            <tr class="set_border">
                                <td rowspan="2" class="td_result" style="text-align:center; vertical-align:middle;" > ÉCRIT </td>
                                <td>Compréhension</td>
                                <td class="td_result">
                                    {!! $ce !!}
                                </td>
                            </tr>
                            <tr class="set_border">

                                <td >Production</td>
                                <td class="td_result">
                                    {!! $pe !!}
                                </td>
                            </tr>

                            <tr class="set_border">
                                <td rowspan="2" class="td_result" style="text-align:center; vertical-align:middle;"  > ORAL </td>
                                <td>Compréhension</td>
                                <td class="td_result">
                                    {!! $co !!}
                                </td>
                            </tr>
                            <tr class="set_border">

                                <td >Production</td>
                                <td class="td_result">
                                    {!! $po !!}
                                </td>
                            </tr>

                            <tr style="border-bottom: 0px !important; border-left: none !important; border-right: none !important;">

                                <td style="border-bottom: 0px !important; border-left: none !important; border-right: none !important;"> </td>

                                <td style="border-bottom: 0px !important; border-left: none !important; border-right: none !important; font-style: normal">
                                    Note Finale
                                </td>

                                <td style="border-bottom: 0px !important; border-left: none !important; border-right: none !important;">
                                    {!! $total !!}
                                </td>

                            </tr>

                            </tbody>
                        </table>

                        {!! $admission !!}


                        <div class="row" style="margin-top: 5%;line-height: 3mm;">
                            <div class="col-md-12 col-xs-12" style="margin-left: 4mm; padding-right: 0mm">
                                <p class="description">
                                    *Note minimale requise par épreuve : 05/25 <br/>
                                    *Seuil de réussite pour obtenir le niveau : 50/100
                                </p>
                                <p class="description-small">
                                    <span class="blue">Le niveau A1 :</span> niveau introductif ou de découverte
                                </p>

                                <p class="description-extra-small">
                                    Cette originale attestation est délivrée en un seul exemplaire. <br/>Aucun double ne pourra être refait. <br/>
                                    Cette attestation est accompagnée par le bulletin de notes de l’étudiant (e) intéressé(e).
                                </p>
                            </div>
                        </div>
                    </div>
                    <div style="width: 177mm; float: left; margin-left: 6mm; margin-top: 3mm; text-align: justify">
                        <?php
                        $exam_day = "";
                        $start = \Carbon\Carbon::createFromFormat("d/m/Y",$exam_start);
                        $exam_start_day = $start->formatLocalized('%d');
                        $exam_start_month = $start->formatLocalized('%B');
                        $exam_start_month = strtolower(month_mois($exam_start_month));
                        $exam_start_year = $start->formatLocalized('%Y');

                        $end = \Carbon\Carbon::createFromFormat("d/m/Y",$exam_end);
                        $exam_end_day = $end->formatLocalized('%d');
                        $exam_end_month = $end->formatLocalized('%B');
                        $exam_end_month = strtolower(month_mois($exam_end_month));
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
                            a comblé avec succès un niveau de langue française
                        <span class="blue" style="font-size: 21pt;">
                            <?php
                            if($student->grade_id == 1) {
                                echo " &nbsp;A1";
                            } else if($student->grade_id == 2){
                                echo "  &nbsp;A2";
                            } else if($student->grade_id == 3){
                                echo "  &nbsp;B1";
                            } else if($student->grade_id == 4){
                                echo "  &nbsp;B2";
                            } else if($student->grade_id == 5){
                                echo "  &nbsp;C1";
                            }
                            ?>
                        </span>du Cadre Européen Commun de Référence pour les Langues (CECRL) à la session de l’examen de niveau à l’Institut {{$exam_day}}.
                        </p>

                        <p style="font-family: 'Calibri Light'; font-size: 12pt; font-style: italic; margin-top: 2mm;margin-left: 18mm">
                            Pour valoir ce que de droit.
                        </p>

                        <div class="pull-right" style="margin-top: 8mm; margin-right: 26mm">
                            <p class="text-center title" style="font-size: 12pt">
                                <?php
                                if(($issued_date == null) or ($issued_date == "")){
                                    $now = \Carbon\Carbon::now();
                                } else {
                                    $now = \Carbon\Carbon::createFromFormat("d/m/Y",$issued_date);
                                }
                                $c_day = $now->formatLocalized('%d');
                                $c_month = strtolower(month_mois($now->formatLocalized('%B')));
                                $c_year = $now->formatLocalized('%Y');
                                ?>
                                Phnom Penh, ITC, le {{$c_day." ".$c_month." ".$c_year}}
                            <p style="margin-top: -10px !important;">
                                Le Directeur de l’Institut de Technologie du Cambodge, <br/>
                                <span style="font-style: italic">{{$issued_by}}</span>
                            </p>
                            </p>
                        </div>

                    </div>

                </div>
            </div>
        @endif

    @endforeach

@endsection

@section('scripts')
    <script>

    </script>
@stop