@extends ('backend.layouts.popup_master')

@section ('title', 'Course Annual' . ' | ' . 'Total Score Annually')
@section('after-styles-end')
    {!! Html::style(elixir('css/handsontable.full.min.css')) !!}
    {!! Html::style('plugins/select2/select2.min.css') !!}
    <style>
        .handsontable thead tr:first-child {
            height: 80px !important;
            vertical-align: middle !important;
        }
        .handsontable thead tr:nth-child(2) {
            height: 50px !important;
            vertical-align: middle !important;
        }
        .handsontable th {
            white-space: normal !important;
        }

        .handsontable td {
            color: #000 !important;
        }
        .handsontable td .htAutocompleteArrow:hover {
            color: #777 !important;
        }
        .handsontable td.area .htAutocompleteArrow {

            color: #d3d3d3 !important;
        }
        .top {
            margin-top: 5px;
            color: #0A0A0A;
        }
        .top a {
            color: black;
        }
        table tr th, table tr td {
            border: 1px solid #000000 !important;
        }
    </style>
@endsection

@section('content')

    <?php
    $min_score_before_graduated = 100;
    $min_score_graduated = 100;
    $min_moy_score = 100;
    $max_score_before_graduated = 0;
    $max_score_graduated = 0;
    $max_moy_score = 0;
    ?>
    <div class="box box-success">

        <div class="box-header with-border" style="margin-bottom: 0px">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="pull-right btn-right">
                            <select name="student_class" class="form-control filter" id="filter_class"></select>
                            <a target="_blank" href="{{route('admin.student.print_average_final_year', ['type'=>'print'])}}?department_id={{$department_id}}&option_id={{$option_id}}&degree_id={{$degree_id}}&academic_year_id={{$academic_year_id}}">
                                <button class="btn btn-primary btn-average-final-year btn-sm" data-toggle="tooltip" style="margin-left: 5px" data-placement="left"  title="Print Average Final Year" id="print_average_final_year"><i class="fa fa-print"></i></button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- /.box-header -->
        @if (session('status'))
            <div class=" message alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        @if (session('warning'))
            <div class=" message alert alert-danger">
                {{ session('warning') }}
            </div>
        @endif
        <div class="box-body panel">
            <div class="page">
                <div class="container">

                    <div class="row">
                        <div class="col-xs-12">
                            <p align="center" style="line-height: normal"><strong>Moyenne fin d'etude</strong></p>
                            <p align="center" style="line-height: normal">Département {{$department->name_fr}} {{$department_option != null? $department_option->name_fr:""}}</p>
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
                                    <td class="border-thin" align="center" colspan="2">
                                        @if($student_by_groups->first()[0]['grade_id'] == 4)
                                            4 <sup>ème</sup> année
                                        @else
                                            1 <sup>ère</sup> année
                                        @endif
                                    </td>
                                    <td class="border-thin" align="center" colspan="2">
                                        @if($student_by_groups->first()[1]['grade_id'] == 5)
                                            5 <sup>ème</sup> année
                                        @else
                                            2 <sup>ème</sup> année
                                        @endif
                                    </td>
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
                                    $result = [];
                                    foreach ($student_by_group as $student_by_class) {
                                        $result[$student_by_class["grade_id"]]["total_score"] = $scores[$student_by_class["id"]]["final_score"];
                                        $result[$student_by_class["grade_id"]]["total_gpa"] = get_gpa($scores[$student_by_class["id"]]["final_score"]);
                                        $result[$student_by_class["grade_id"]]["credit"] = 0;
                                        foreach ($scores[$student_by_class["id"]] as $key=>$score) {
                                            if(is_numeric($key)){
                                                $result[$student_by_class["grade_id"]]["credit"] += $score["credit"];
                                            }
                                        }
                                    }
                                    ?>
                                    <tr>
                                        <td class="border-thin border-left border-right" align="center">{{$i}}</td>
                                        <td class="border-thin">{{$student_by_group[0]['id_card']}}</td>
                                        <td class="border-thin">{{strtoupper($student_by_group[0]['name_latin'])}}</td>
                                        <td class="border-thin" align="center">{{to_latin_gender($student_by_group[0]['gender'])}}</td>
                                        @foreach($result as $year => $score_each_year)
                                        <?php
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
                                        ?>
                                        <td class="border-thin" align="center"><strong>{{$score_each_year["total_score"]}}</strong></td>
                                        <td class="border-thin" align="center"><strong>{{$score_each_year["total_gpa"]}}</strong></td>
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

                                        <td class="border-thin" align="center"><strong>{{is_numeric($final_average_score)?round($final_average_score,2):"N/A"}}</strong></td>
                                        <td class="border-thin" align="center"><strong>{{$final_average_gpa}}</strong></td>
                                        <td class="border-thin">{{$final_average_mention}}</td>
                                        <td class="border-thin border-left border-right"></td>
                                    </tr>
                                    <?php $i++; ?>
                                @endforeach
                                <tr>
                                    <td class="border-top"></td>
                                    <td class="border-top"></td>
                                    <td class="border-top"></td>
                                    <td class="border-top" align="center">Max</td>
                                    <td class="border-top" align="center">{{$max_score_before_graduated}}</td>
                                    <td class="border-top" align="center">{{get_gpa($max_score_before_graduated)}}</td>
                                    <td class="border-top" align="center">{{$max_score_graduated}}</td>
                                    <td class="border-top" align="center">{{get_gpa($max_score_graduated)}}</td>
                                    <td class="border-top" align="center">{{$max_moy_score}}</td>
                                    <td class="border-top" align="center">{{get_gpa($max_moy_score)}}</td>
                                    <td class="border-top"></td>
                                    <td class="border-top"></td>
                                </tr>
                                <tr>
                                    <td class="no-border"></td>
                                    <td class="no-border"></td>
                                    <td class="no-border"></td>
                                    <td class="no-border" align="center">Min</td>
                                    <td class="border-top" align="center">{{$min_score_before_graduated}}</td>
                                    <td class="border-top" align="center">{{get_gpa($min_score_before_graduated)}}</td>
                                    <td class="border-top" align="center">{{$min_score_graduated}}</td>
                                    <td class="border-top" align="center">{{get_gpa($min_score_graduated)}}</td>
                                    <td class="border-top" align="center">{{$min_moy_score}}</td>
                                    <td class="border-top" align="center">{{get_gpa($min_moy_score)}}</td>
                                    <td class="no-border"></td>
                                    <td class="no-border"></td>
                                </tr>

                            </table>
                        </div>
                    </div>


                </div>
            </div>

        </div>

    </div>
@stop

@section('after-scripts-end')
    {!! HTML::script(elixir('js/handsontable.full.min.js')) !!}
    {!! Html::script('plugins/jpopup/jpopup.js') !!}
    {!! Html::script('js/backend/course/courseAnnual/all_score.js') !!}
    {!! Html::script('plugins/select2/select2.full.min.js') !!}
    {{--myscript--}}

    <script>

        $(function () {
            $.ajax({
                type: 'POST',
                url: '{{route('admin.filter.get_filter_by_class_final_year')}}',
                data: {'academic_year_id': 2018},
                dataType: "json",
                success: function (response) {
                    if (response.status == "success") {
                        $('#filter_class').select2({
                            data: response.data,
                            placeholder: "Select a class",
                        });
                        try {
                            callback();
                        } catch (exception) {

                        }
                    } else {
                        notify("error", "info", "Something went wrong! Cannot filtering value");
                    }
                }
            })
        })

    </script>


@stop