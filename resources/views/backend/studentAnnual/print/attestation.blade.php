<!doctype html>
<html class="no-js" lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="_token" content="X2IQMvdghRfpLlHKugb0MK9lDoeaeSpbxQpH2Oq9"/>

    <title>
        ITC-SMS | Attestation
    </title>

    <!-- Meta -->
    <meta name="description" content="Printing Attestation">
    <meta name="author" content="Department Information and Communication Engineering">

    <!-- Styles -->
    <link media="all" type="text/css" rel="stylesheet" href="{{ asset('css/student_transcript.css') }}">
    <link media="all" type="text/css" rel="stylesheet" href="{{ asset('css/backend/prints/prints.css') }}">

    <style>
        .tran-title {
            height: 1.3cm;
            background: #00c0ef;
        }

        .attestation-cell td {
            line-height: 5px !important;
        }

        .attestation-table {
            width: 78%;
        }

        .title {
            text-align: center;
            font-size: 24px !important;
            vertical-align: middle;
            line-height: 50px;
            color: #fff;
        }
        .border-thin {
            border: 0.5px solid black !important;
        }

        .border-top {
            border-top: 1px solid black !important;
        }

        .border-left {
            border-left: 1px solid black !important;
        }

        .border-right {
            border-right: 1px solid black !important;
        }
        .border-bottom {
            border-bottom: 1px solid black !important;
        }

        .department {
            font-family: Arial, Helvetica, sans-serif;
        }

        .margin-top-degree{
            margin-top: 10px;
        }

        .seen {
            margin-top: 25px;
        }

        .footer {
            margin-top: 220px;
            bottom: 10mm;
            left: 22mm;
            font-size: 10pt;
        }
        .background {
            background: rgba(166, 169, 175, 0.51);
        }
        .background-half{
            width: 50%;
            background: #a6a9af2b;
        }

        .underline {
            border-bottom: 1px solid;
        }
        .half-width {
            width: 47%;
            padding-left: 2px !important;
        }

        .page {
            margin: 0 auto;
            margin-top: 10mm;
            padding: 37mm 7mm 8mm 7mm;
            position: relative;
            font-family: "Times New Roman" !important;
        }
        div.page
        {
            page-break-after: always;
            page-break-inside: avoid;
        }
    </style>

</head>
<body>
@foreach($student_by_groups as $student_by_group)
<div class="page">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="row">
                    <div class="tran-title">
                        <p class="title"><strong>ATTESTATION</strong></p>
                    </div>
                </div>

                <div class="row margin-top-degree">
                    <div class="col-xs-4 no-padding">
                        <p>Name: <strong>{{strtoupper($student_by_group->first()['name_latin'])}}</strong></p>
                    </div>
                    <div class="col-xs-4" align="center"><span>Sex: {{to_latin_gender($student_by_group->first()['gender'])}}</span></div>
                    <div class="col-xs-4" align="center"><span>ID: {{$student_by_group->first()['id_card']}}</span></div>
                </div>

                <div class="row">
                    <div class="col-xs-12 no-padding department">
                        <p>Department: {{$student_by_group->first()['department_en']}}</p>
                    </div>
                </div>
                @if($student_by_group->first()['option_en'] != null)
                <div class="row">
                    <div class="col-xs-12 no-padding">
                        <p>Option: {{$student_by_group->first()['option_en']}}</p>
                    </div>
                </div>
                @endif
                <div class="row">
                    <div class="col-xs-12 no-padding">
                        <p>Degree: {{$student_by_group->first()['degree_en']}}</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <p align="center" style="margin-bottom: 30px; margin-top: 10px; font-size: 16px; font-family: Times New Roman !important;">
                            <span class="underline">
                                <b>BALANCE SHEET OF TWO FINAL YEARS</b>
                            </span>
                        </p>
                    </div>
                </div>
                <?php
                    $fail = false;
                    $result = [];
                    foreach ($student_by_group as $key => $student_by_class) {
                        $lowest_score = 100;
                        if(is_numeric($key)) {
                            $result[$student_by_class["grade_id"]]["total_score"] = $scores[$student_by_class["id"]]["final_score"];
                            $result[$student_by_class["grade_id"]]["total_gpa"] = get_gpa($scores[$student_by_class["id"]]["final_score"]);
                            $result[$student_by_class["grade_id"]]["credit"] = 0;
                            $result[$student_by_class["grade_id"]]["courses_fail"] = "";
                            foreach ($scores[$student_by_class["id"]] as $key=>$score) {
                                if(is_numeric($key)){
                                    $result[$student_by_class["grade_id"]]["credit"] += $score["credit"];
                                    if($score["score"] <30) {
                                        if($score["resit"] == null) {
                                            $result[$student_by_class["grade_id"]]["courses_fail"] = $result[$student_by_class["grade_id"]]["courses_fail"] . $score["name_fr"] . " (". $score["score"] .")". "<br/>";
                                            $fail = true;
                                        } else if($score["resit"] < 30) {
                                            $result[$student_by_class["grade_id"]]["courses_fail"] = $result[$student_by_class["grade_id"]]["courses_fail"] . $score["name_fr"] . " (". $score["score"] .")". "<br/>";
                                            $fail = true;
                                        }
                                    }
                                }
                            }
                        }
                    }
                ?>
                <div class="row">
                    <div class="attestation-table">
                        <table class="table attestation-cell">
                            <tr align="center">
                                <td class="no-border half-width"></td>
                                <td class="border-top border-left border-bottom border-thin">Obtained scores</td>
                                <td class="border-top border-right border-bottom border-thin">Number of credits</td>
                            </tr>
                            @foreach($result as $year => $score_each_year)
                            <tr>
                                <td class="border-thin half-width">{{get_order_alpha_numeric($year)}} year average:</td>
                                <td align="center" class="border-left border-bottom border-thin background">
                                    <span class="background-half"><strong>{{substr($score_each_year["total_gpa"],0,3)}}</strong></span>
                                </td>
                                <td align="center" class="border-right border-bottom">{{round($score_each_year["credit"], 2)}}</td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                </div>

                <div class="row">
                    <table class="table no-border">
                        <?php
                        $final_average_score = 0;
                        foreach($result as $result_score) {
                            if(is_numeric($result_score["total_score"]) && is_numeric($final_average_score)) {
                                $final_average_score = $final_average_score + $result_score["total_score"];
                            } else {
                                $final_average_score = "N/A";
                            }
                        }
                        if(is_numeric($final_average_score)) {
                            $final_average_score = $final_average_score / 2;
                            $final_average_gpa = get_gpa($final_average_score);
                            $final_average_mention = get_english_mention($final_average_score);
                            if ($fail) {
                                $final_average_gpa = "";
                                $final_average_mention = "Echoué";
                            } else if($lowest_score<50) {
                                $final_average_gpa = get_gpa($lowest_score);
                                $final_average_mention = get_english_mention($lowest_score);
                            }
                        } else {
                            $final_average_score = "N/A";
                            $final_average_gpa = "N/A";
                            $final_average_mention = "N/A";
                        }
                        ?>
                        <tr>
                            <td align="right" style="width: 60%">
                                Average of 2 final years = <strong><span style="font-size: 16px;"> FINAL AVERAGE:</span></strong>
                            </td>
                            <td></td>
                            <td align="center" style="width: 22%; font-size: 18px; border: 0.5px solid black; background-color: yellow">
                                <?php
                                if($fail) {
                                    echo "";
                                } else if(is_numeric($final_average_score)) {
                                    if($final_average_gpa<2) {
                                        echo "<strong style='color: red'>".substr($final_average_gpa,0,3)."</strong>>";
                                    } else {
                                        echo "<strong>".substr($final_average_gpa,0,3)."</strong>";
                                    }

                                } else {
                                    echo "<strong style='color: red'>N/A</strong>";
                                }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td align="right" style="width: 60%"><strong>FINAL MENTION:</strong></td>
                            <td></td>
                            <td align="center" style="width: 22%; border: 0.5px solid black; background-color: yellow">
                                <?php
                                if($final_average_gpa<2) {
                                    echo "<strong style='color:red'>$final_average_mention</strong>";
                                } else {
                                    echo "<strong>$final_average_mention</strong>";
                                }
                                ?>
                            </td>
                        </tr>
                    </table>
                </div>


                <div class="row">
                    <div class="col-xs-6 col-xs-offset-6">
                        <?php
                        $date = \Carbon\Carbon::createFromFormat("d/m/Y",$issued_date);
                        ?>
                        <div class="seen" align="center">
                            <p>Phnom Penh, {{$date->formatLocalized('%B %d, %Y')}}</p>
                            <h4 style="line-height: 0.1;"><strong>{{$issued_by}}</strong></h4>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="footer">
                        <div class="grading-system">
                            <span><b>GRADING SYSTEM:</b></span>
                            <table style="margin-left: 18px" width="100%">
                                <tr>
                                    <td>A = 85% -100% = 4.00 = Excellent</td>
                                    <td>C = 50% - 64% = 2.0 = Fair</td>
                                </tr>
                                <tr>
                                    <td>B<sup>+</sup> = 80% - 84% = 3.5 = Very Good</td>
                                    <td>D = 45% - 49% = 1.5 = Poor</td>
                                </tr>
                                <tr>
                                    <td>B = 70% - 79% = 3.0 = Good</td>
                                    <td>E = 40% - 44% = 1.00 = Very Poor</td>
                                </tr>
                                <tr>
                                    <td>C<sup>+</sup> = 65% - 69% = 2.50 = Faily Good</td>
                                    <td>F = < 40% = 0.00 = Failure</td>
                                </tr>
                            </table>
                        </div>
                        <div class="remark">
                            <span class="smallest_text"> <u><b>Remark</b></u>: This attestation cannot be given a second time.</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endforeach
</body>
</html>