<!doctype html>
<html class="no-js" lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="_token" content="X2IQMvdghRfpLlHKugb0MK9lDoeaeSpbxQpH2Oq9"/>

    <title>
        ITC-SMS | Average Final Year
    </title>

    <!-- Meta -->
    <meta name="description" content="Printing Attestation">
    <meta name="author" content="Department Information and Communication Engineering">

    <!-- Styles -->
    <link media="all" type="text/css" rel="stylesheet" href="{{ asset('css/student_transcript.css') }}">
    <link media="all" type="text/css" rel="stylesheet" href="{{ asset('css/backend/prints/prints.css') }}">

    <style>
        tr {
            page-break-inside: avoid !important;
        }

        table tr td {
            font-size: 11px;
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
        .page {
            margin: 0 auto;
            padding: -0.3mm -0.3mm 0mm 0.3mm !important;
            position: relative;
            font-family: "Times New Roman" !important;
        }

        .table > thead > tr > th, .table > thead > tr > td, .table > tbody > tr > th, .table > tbody > tr > td, .table > tfoot > tr > th, .table > tfoot > tr > td {
            padding-top: 0px;
            padding-bottom: 0px;
            vertical-align: middle;
        }
        .mention {
            width: 60px !important;
        }
        .cell-sm {
            padding: 1px !important;
            page-break-inside: avoid !important;
        }
    </style>

</head>
<body>
<div class="page">
    <?php
    $min_score_before_graduated = 100;
    $min_score_graduated = 100;
    $min_moy_score = 100;
    $max_score_before_graduated = 0;
    $max_score_graduated = 0;
    $max_moy_score = 0;
    ?>
        <div class="container">

            <div class="row">
                <div class="col-xs-12">
                    <p align="center" style="line-height: normal"><strong>Moyenne fin d'etude</strong></p>
                    <p align="center" style="line-height: normal">Département {{$department->name_fr}} {{$department_option != null? $department_option->name_fr:""}}</p>
                    <p align="center" style="line-height: normal"><strong>Classe: {{$degree->code}} {{$degree->id == 1 ? '5' : '2'}} - {{$department->code}} {{ $department_option !=null ? $department_option->code : null }}</strong></p>
                    <p align="center" style="line-height: normal">Année Scolaire({{$academic_year->name_latin}})</p>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <table class="table">
                        <tr>
                            <td class="no-border"></td>
                            <td class="no-border"></td>
                            <td class="no-border"></td>
                            <td class="no-border"></td>
                            <?php
                            // dd($student_by_groups->first());
                            $first = true;
                            ?>
                            @foreach($student_by_groups->first() as $student_by_group_key => $student_by_group)
                                @if(is_numeric($student_by_group_key))
                                    @if ($first)
                                        @if($student_by_group['grade_id'] == 4)
                                            <td class="border-thin" align="center" colspan="2">
                                                4 <sup>ème</sup> année
                                            </td>
                                        @else
                                            <td class="border-thin" align="center" colspan="2">
                                                1 <sup>ère</sup> année
                                            </td>
                                        @endif
                                        <?php $first = false; ?>
                                    @else
                                        @if($student_by_group['grade_id'] == 5)
                                            <td class="border-thin" align="center" colspan="2">
                                                5 <sup>ème</sup> année
                                            </td>
                                        @else
                                            <td class="border-thin" align="center" colspan="2">
                                                2 <sup>ème</sup> année
                                            </td>
                                        @endif
                                    @endif
                                @endif
                            @endforeach
                            <td class="border-thin" align="center" colspan="2">Moy. de Sortie</td>
                            <td class="border-thin border-bottom" align="center" rowspan="2">Mention <br/> de Sortie</td>
                            <td class="border-thin border-bottom" align="center" rowspan="2">Observation</td>
                        </tr>
                        <tr>
                            <td class="border-thin border-bottom" align="center">N<sup>o</sup></td>
                            <td class="border-thin border-bottom" align="center">ID</td>
                            <td class="border-thin border-bottom" align="center"><strong>Noms et Prenoms</strong></td>
                            <td class="border-thin border-bottom" align="center"><strong>Sexe</strong></td>
                            <td class="border-thin border-bottom" align="center">Moy.(M1)</td>
                            <td class="border-thin border-bottom" align="center">GPA</td>
                            <td class="border-thin border-bottom" align="center">Moy.(M2)</td>
                            <td class="border-thin border-bottom" align="center">GPA</td>
                            <td class="border-thin border-bottom" align="center">(M1+M2)/2</td>
                            <td class="border-thin border-bottom" align="center">GPA</td>
                        </tr>
                        @php
                            $i = 1;
                        @endphp
                        @foreach($student_by_groups as $student_by_group)
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
                            @if($student_by_group->first())
                                <tr>
                                    <td class="border-thin border-left border-right" align="center">{{$i}}</td>
                                    <td class="border-thin">{{$student_by_group->first()['id_card']}}</td>
                                    <td class="border-thin">{{strtoupper($student_by_group->first()['name_latin'])}}</td>
                                    <td class="border-thin" align="center">{{$student_by_group->first()['gender']}}</td>
                                    <?php $courses_fail = "" ?>
                                    @foreach($result as $year => $score_each_year)
                                        <?php
                                        if($lowest_score > $score_each_year["total_score"]) {
                                            $lowest_score = $score_each_year["total_score"];
                                        }
                                        if($year == 4 || $year ==1) {
                                            if(is_numeric($score_each_year["total_score"]) && $min_score_before_graduated>$score_each_year["total_score"]){
                                                $min_score_before_graduated = $score_each_year["total_score"];
                                            }
                                            if(is_numeric($score_each_year["total_score"]) && $max_score_before_graduated<$score_each_year["total_score"]){
                                                $max_score_before_graduated = $score_each_year["total_score"];
                                            }
                                        } else if($year == 5 || $year ==2) {
                                            if(is_numeric($score_each_year["total_score"]) && $min_score_graduated>$score_each_year["total_score"]){
                                                $min_score_graduated = $score_each_year["total_score"];
                                            }
                                            if(is_numeric($score_each_year["total_score"]) && $max_score_graduated<$score_each_year["total_score"]){
                                                $max_score_graduated = $score_each_year["total_score"];
                                            }
                                        }
                                        if($score_each_year["courses_fail"] != "" and $score_each_year["courses_fail"] != " "){
                                            $courses_fail = $courses_fail . $score_each_year["courses_fail"]. "<br/>";
                                        }
                                        ?>
                                        <td class="border-thin" align="center">
                                            @if($score_each_year["total_score"]<50)
                                                <strong style="color: red">
                                                    @else
                                                        <strong>
                                                            @endif
                                                            {{$score_each_year["total_score"]}}
                                                        </strong>
                                        </td>
                                        <td class="border-thin" align="center">
                                            @if($score_each_year["total_score"]<50)
                                                <strong style="color: red">
                                                    @else
                                                        <strong>
                                                            @endif
                                                            {{substr($score_each_year["total_gpa"],0,3)}}
                                                        </strong>
                                        </td>
                                    @endforeach

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
                                        $final_average_mention = get_french_mention($final_average_score);
                                        if ($fail) {
                                            $final_average_gpa = "";
                                            $final_average_mention = "Echoué";
                                        } else if($lowest_score<50) {
                                            $final_average_gpa = get_gpa($lowest_score);
                                            $final_average_mention = get_french_mention($lowest_score);
                                        }
                                        if($min_moy_score>$final_average_score) {
                                            $min_moy_score = $final_average_score;
                                        }
                                        if($max_moy_score<$final_average_score) {
                                            $max_moy_score = $final_average_score;
                                        }
                                    } else {
                                        $final_average_score = "N/A";
                                        $final_average_gpa = "N/A";
                                        $final_average_mention = "N/A";
                                    }

                                    ?>

                                    <td class="border-thin" align="center">
                                        @if($final_average_score<50)
                                            <strong style="color: red">
                                                @else
                                                    <strong>
                                                        @endif
                                                        {{is_numeric($final_average_score)?round($final_average_score,2):"N/A"}}
                                                    </strong>
                                    </td>
                                    <td class="border-thin" align="center">
                                        @if($final_average_gpa<2)
                                            <strong style="color: red">
                                                @else
                                                    <strong>
                                                        @endif
                                                        <?php
                                                        if($fail) {
                                                            echo "";
                                                        } else if(is_numeric($final_average_score)) {
                                                            echo substr($final_average_gpa,0,3);
                                                        } else {
                                                            echo "N/A";
                                                        }
                                                        ?>
                                                    </strong>
                                    </td>
                                    <td class="border-thin">
                                        @if($final_average_gpa<2)
                                            <strong style="color: red">
                                                @else
                                                    <strong>
                                                        @endif
                                                        {{$final_average_mention}}
                                                    </strong>
                                    </td>
                                    <td class="border-thin">
                                        <?php
                                        if($student_by_group->first()['observation'] != '' and $student_by_group->first()['observation'] != ' ') {
                                            echo $student_by_group->first()['observation'] . "<br/>";
                                        }
                                        echo '<span style="color: red;">';
                                        if($courses_fail != "" and $courses_fail != " "){
                                            $courses_fail = substr($courses_fail,0,-5);
                                            echo $courses_fail;
                                        }
                                        echo '</span>';
                                        ?>
                                    </td>
                                </tr>
                            @endif
                            <?php $i++; ?>
                        @endforeach
                        <tr>
                            <td class="border-top"></td>
                            <td class="border-top"></td>
                            <td class="border-top"></td>
                            <td class="border-top" align="center">Max</td>
                            <td class="border-top" align="center">{{$max_score_before_graduated}}</td>
                            <td class="border-top" align="center">{{substr(get_gpa($max_score_before_graduated),0,3)}}</td>
                            <td class="border-top" align="center">{{$max_score_graduated}}</td>
                            <td class="border-top" align="center">{{substr(get_gpa($max_score_graduated),0,3)}}</td>
                            <td class="border-top" align="center">{{$max_moy_score}}</td>
                            <td class="border-top" align="center">{{substr(get_gpa($max_moy_score),0,3)}}</td>
                            <td class="border-top"></td>
                            <td class="border-top"></td>
                        </tr>
                        <tr>
                            <td class="no-border"></td>
                            <td class="no-border"></td>
                            <td class="no-border"></td>
                            <td class="no-border" align="center">Min</td>
                            <td class="border-top" align="center">{{$min_score_before_graduated}}</td>
                            <td class="border-top" align="center">{{substr(get_gpa($min_score_before_graduated),0,3)}}</td>
                            <td class="border-top" align="center">{{$min_score_graduated}}</td>
                            <td class="border-top" align="center">{{substr(get_gpa($min_score_graduated),0,3)}}</td>
                            <td class="border-top" align="center">{{$min_moy_score}}</td>
                            <td class="border-top" align="center">{{substr(get_gpa($min_moy_score),0,3)}}</td>
                            <td class="no-border"></td>
                            <td class="no-border"></td>
                        </tr>

                    </table>
                </div>
            </div>


        </div>
</div>
</body>
</html>