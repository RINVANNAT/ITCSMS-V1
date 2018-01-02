@extends('backend.layouts.printing_landscape_a4_foundation_certificate')
@section('before')
@section('title')
    ITC-SMS | {{trans('messages.foundation_certificate')}}
@stop

@section('before-styles-end' )
    <style>
        .right {
            float: right;
        }
        .left {
            float: left;
        }
        .text_right {
            text-align: right;
        }
        .text_left {
            text-align: left;
        }
        .text_center {
            text-align: center;
        }
        .smallest_text {
            font-size: 11pt;
        }
        .border {
            border: 2px solid black;
            height: 190mm;
            padding: 5mm;
        }
    </style>
@endsection

@section('content')
    @foreach($students as $student)
    <div class="page">
        <div class="row">
            <div class="col-md-6">
                <div class="border" align="center">
                    <h2>ព្រឹត្តិបត្រពិន្ទុ</h2>
                    <h3>Academic Transcript</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <span class="left">Name: {{strtoupper($student['name_latin'])}}</span>
                        </div>
                        <div class="col-md-6">
                            <span class="right">ID: {{$student['id_card']}}</span>
                        </div>
                    </div>
                    <table width="100%">
                        <thead>
                        <tr>
                            <th class="text_left">មុខវិជ្ជាទូទៅ</th>
                            <th class="text_left">General Subjects</th>
                            <th class="text_center">Credits</th>
                            <th class="text_center">Grades</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i = 1 ?>
                        @foreach($scores[$student['id']] as $key => $score)
                            @if(is_numeric($key))
                                <tr>
                                    <td style="text-align: left;width: 35%">{{isset($score['name_kh'])?$score['name_kh']:""}}</td>
                                    <td style="text-align: left;width: 35%">{{isset($score['name_en'])?$score['name_en']:""}}</td>
                                    <td style="text-align: center;width: 15%">{{ $score["credit"] }}</td>
                                    <?php
                                    $grade = "";
                                    if($score["score"] >= 85){
                                        $grade = "A";
                                    } else if ($score["score"] >= 80) {
                                        $grade = "B<sup>+</sup>";
                                    } else if ($score["score"] >= 70) {
                                        $grade = "B";
                                    } else if ($score["score"] >= 65) {
                                        $grade = "C<sup>+</sup>";
                                    } else if ($score["score"] >= 50) {
                                        $grade = "C";
                                    } else if ($score["score"] >= 45) {
                                        $grade = "D";
                                    } else if ($score["score"] >= 40) {
                                        $grade = "E";
                                    }  else {
                                        $grade = "F";
                                    }

                                    ?>
                                    <td class="col-right" style="text-align:center; width: 15%;">{!! $grade !!}</td>
                                </tr>
                                <?php $i++ ?>
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                    <div align="left">
                        <div class="gpa">
                            <?php
                            if($transcript_type == "semester1") {
                                $last_score = $scores[$student['id']]["final_score_s1"];
                            } else {
                                $last_score = $scores[$student['id']]["final_score"];
                            }

                            $gpa = "";
                            if($last_score >= 85){
                                $gpa = "4.0";
                            } else if ($last_score >= 80) {
                                $gpa = "3.5";
                            } else if ($last_score >= 70) {
                                $gpa = "3.0";
                            } else if ($last_score >= 65) {
                                $gpa = "2.5";
                            } else if ($last_score >= 50) {
                                $gpa = "2.0";
                            } else if ($last_score >= 45) {
                                $gpa = "1.5";
                            } else if ($last_score >= 40) {
                                $gpa = "1.0";
                            }  else {

                                $gpa = "0.0";
                            }
                            ?>
                            <h4> <b>GPA: {{$gpa}}</b></h4>
                        </div>
                        <div class="transcript-footer">
                            <div class="grading-system">
                                <h5><b>Note:</b></h5>
                                <table style="margin-left: 18px" width="100%">
                                    <tr>
                                        <td>A = 85% -100% = ល្អប្រសើរ</td>
                                        <td>C = 50% - 64% = មធ្យម</td>
                                    </tr>
                                    <tr>
                                        <td>B<sup>+</sup> = 80% - 84% = ល្អណាស់</td>
                                        <td>D = 45% - 49% = ខ្សោយ</td>
                                    </tr>
                                    <tr>
                                        <td>B = 70% - 79% = ល្អ</td>
                                        <td>E = 40% - 44% = ខ្សោយណាស់</td>
                                    </tr>
                                    <tr>
                                        <td>C<sup>+</sup> = 65% - 69% = ល្អបង្គួរ</td>
                                        <td>F = < 40% = ធ្លាក់</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="remark">
                                <span class="smallest_text"><b>*</b> <span>វិញ្ញាបនបត្រនេះមិនចេញជូនជាលើកទី២ទេ។</span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="border">

                </div>
            </div>
        </div>
    </div>
    @endforeach
@endsection

@section('scripts')
    <script>

    </script>
@endsection
