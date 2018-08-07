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
        @font-face {
            font-family: times_new_roman_normal;
            src: url("{{ asset('fonts/TIMES.TTF') }}");
        }

        @font-face {
            font-family: times_new_roman_normal_bold;
            src: url("{{ asset('fonts/Times_New_Roman_Bold.ttf') }}");
        }

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
            font-family: times_new_roman_normal !important;
        }

        .margin-top-degree{
            margin-top: 10px;
        }

        .seen {
            margin-top: 25px;
            font-family: times_new_roman_normal !important;
        }

        .footer {
            margin-top: 180px;
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
            font-family: times_new_roman_normal !important;
        }
        div.page
        {
            page-break-after: always;
            page-break-inside: avoid;
        }

        .remark h5 {
            margin-bottom: 0px;
            font-family: times_new_roman_normal !important;
        }

        .smis-row {
            width: 100%;
            display: flex;
            justify-content: space-between;
        }

        .smis-column:nth-child(1) { text-align: left; flex: 1;}
        .smis-column:nth-child(2) { text-align: center; flex: 1;}
        .smis-column:nth-child(3) { text-align: right; flex: 1;}
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
                    @if(strlen($student_by_group->first()['name_latin']) > 15)
                        <div class="col-xs-7 no-padding">
                            <p class="name"><strong>Name: {{strtoupper($student_by_group->first()['name_latin'])}}</strong></p>
                        </div>
                        <div class="col-xs-2" align="center"><span><strong>Sex:</strong> {{to_latin_gender($student_by_group->first()['gender'])}}</span></div>
                        <div class="col-xs-3" align="center"><span><strong>ID:</strong> {{$student_by_group->first()['id_card']}}</span></div>
                    @else
                        <div class="col-xs-4 no-padding">
                            <p class="name"><strong>Name: {{strtoupper($student_by_group->first()['name_latin'])}}</strong></p>
                        </div>
                        <div class="col-xs-4" align="center"><span><strong>Sex:</strong> {{to_latin_gender($student_by_group->first()['gender'])}}</span></div>
                        <div class="col-xs-4" align="center"><span><strong>ID:</strong> {{$student_by_group->first()['id_card']}}</span></div>
                    @endif
                </div>

                <div class="row">
                    <div class="col-xs-12 no-padding department">
                        <p><strong>Department:</strong> {{$student_by_group->first()['department_en']}}</p>
                    </div>
                </div>
                @if($student_by_group->first()['option_en'] != null)
                <div class="row">
                    <div class="col-xs-12 no-padding">
                        <p><strong>Option:</strong> {{$student_by_group->first()['option_en']}}</p>
                    </div>
                </div>
                @endif
                <div class="row">
                    <div class="col-xs-12 no-padding">
                        <p><strong>Degree:</strong> {{$student_by_group->first()['degree_en']}}</p>
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
                            <h4 style="line-height: 0.1; font-family: times_new_roman_normal !important; font-size: 18px;"><strong>{{$issued_by}}</strong></h4>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="footer">
                        <div class="grading-system">
                            <span><b>GRADING SYSTEM:</b></span>
                            <table style="margin-left: 0px">
                                <tr>
                                    <td>
                                        <table style="width: 105%;">
                                            <tr>
                                                <td style="width: 6% !important">A</td>
                                                <td style="width: 6% !important"> =&nbsp; </td>
                                                <td style="width: 6% !important">85%</td>
                                                <td style="width: 6% !important"> &nbsp;&minus; </td>
                                                <td style="width: 6% !important">100%</td>
                                                <td style="width: 6% !important"> =&nbsp; </td>
                                                <td style="width: 6% !important">4.0</td>
                                                <td style="width: 6% !important"> &nbsp;=&nbsp; </td>
                                                <td style="width: 52% !important">Excellent</td>
                                            </tr>

                                            <tr>
                                                <td style="width: 6% !important">B<sup>+</sup></td>
                                                <td style="width: 6% !important"> =&nbsp; </td>
                                                <td style="width: 6% !important">80%</td>
                                                <td style="width: 6% !important"> &nbsp;&minus; </td>
                                                <td style="width: 6% !important">84%</td>
                                                <td style="width: 6% !important"> =&nbsp; </td>
                                                <td style="width: 6% !important">3.5</td>
                                                <td style="width: 6% !important"> &nbsp;=&nbsp; </td>
                                                <td style="width: 52% !important">Very Good</td>
                                            </tr>

                                            <tr>
                                                <td style="width: 6% !important">B</td>
                                                <td style="width: 6% !important"> =&nbsp; </td>
                                                <td style="width: 6% !important">70%</td>
                                                <td style="width: 6% !important"> &nbsp;&minus; </td>
                                                <td style="width: 6% !important">79%</td>
                                                <td style="width: 6% !important"> =&nbsp; </td>
                                                <td style="width: 6% !important">3.0</td>
                                                <td style="width: 6% !important"> &nbsp;=&nbsp; </td>
                                                <td style="width: 52% !important">Good</td>
                                            </tr>

                                            <tr>
                                                <td style="width: 6% !important">C<sup>+</sup></td>
                                                <td style="width: 6% !important"> =&nbsp; </td>
                                                <td style="width: 6% !important">65%</td>
                                                <td style="width: 6% !important"> &nbsp;&minus; </td>
                                                <td style="width: 6% !important">69%</td>
                                                <td style="width: 6% !important"> =&nbsp; </td>
                                                <td style="width: 6% !important">2.5</td>
                                                <td style="width: 6% !important"> &nbsp;=&nbsp; </td>
                                                <td style="width: 52% !important">Fairly Good</td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td>
                                        <table style="width: 130%;">
                                            <tr>
                                                <td style="width: 6% !important">C</td>
                                                <td style="width: 6% !important"> &nbsp;= </td>
                                                <td style="width: 6% !important">50%</td>
                                                <td style="width: 6% !important"> &nbsp;&minus; </td>
                                                <td style="width: 6% !important">64%</td>
                                                <td style="width: 6% !important"> &nbsp;= </td>
                                                <td style="width: 6% !important">2.0</td>
                                                <td style="width: 6% !important"> &nbsp;= </td>
                                                <td style="width: 52% !important">Fair</td>
                                            </tr>

                                            <tr>
                                                <td style="width: 6% !important">D</td>
                                                <td style="width: 6% !important"> &nbsp;= </td>
                                                <td style="width: 6% !important">45%</td>
                                                <td style="width: 6% !important"> &nbsp;&minus; </td>
                                                <td style="width: 6% !important">49%</td>
                                                <td style="width: 6% !important"> &nbsp;= </td>
                                                <td style="width: 6% !important">1.5</td>
                                                <td style="width: 6% !important"> &nbsp;= </td>
                                                <td style="width: 52% !important">Poor</td>
                                            </tr>

                                            <tr>
                                                <td style="width: 6% !important">E</td>
                                                <td style="width: 6% !important"> &nbsp;= </td>
                                                <td style="width: 6% !important">40%</td>
                                                <td style="width: 6% !important"> &nbsp;&minus; </td>
                                                <td style="width: 6% !important">44%</td>
                                                <td style="width: 6% !important"> &nbsp;= </td>
                                                <td style="width: 6% !important">1.0</td>
                                                <td style="width: 6% !important"> &nbsp;= </td>
                                                <td style="width: 52% !important">Very Poor</td>
                                            </tr>
                                            <tr>
                                                <td style="width: 6% !important">F</td>
                                                <td style="width: 6% !important"> &nbsp;= <</td>
                                                <td style="width: 6% !important">40%</td>
                                                <td style="width: 6% !important"> &nbsp;= </td>
                                                <td style="width: 6% !important">0.0</td>
                                                <td style="width: 6% !important"> &nbsp;= </td>
                                                <td colspan="3">Failure</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="remark">
                            <h5><b>Remark:</b></h5>
                            <span style="margin-left: 10mm"><b style="font-size: 14pt">*</b> The annual Grade Point Average minimum requirement to pass to the higher class is 2.0.</span><br/>
                            <span style="margin-left: 10mm"><b style="font-size: 14pt">*</b> This attestation cannot be given for the second time.</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endforeach
</body>
{!! Html::script('plugins/jquery.min.js') !!}
<script>
    $(function () {
        var name = $('.name');

        var numWords = name.text().split("").length;
        console.log(numWords)

        if (numWords >= 14) {
            // name.css("font-size", "8px");
        }
    })
</script>
</html>