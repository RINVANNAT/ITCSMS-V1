@extends('backend.layouts.printing_portrait_a4_transcript')
@section('title')
    ITC-SMS | ព្រឹត្តិបត្រ័ពិន្ទុ
@stop

@section('after-styles-end')
    <link rel="stylesheet" media="print, screen" href="{{ url('css/backend/transcript.css') }}">
    <style>

    </style>
@stop
@section('content')

<div class="page">
    <div class="transcript">
        {{--transcript header--}}
        <div class="transcript-header">
            <table class="head">
                <tr>
                    <td colspan="2"><h4>Department: <span class="no-bold">{{$student->department_en}}</span></h4></td>
                </tr>
                <tr>
                    <td width="50%"><h4>Degree: <span class="no-bold">Engineer</span> </h4></td>
                    <td class="col-right" width="50%"><h4>Class: <span class="no-bold">{{$student->grade_en}}</span></h4></td>
                </tr>
                <tr>
                    <td colspan="2" class="col-right"><h4>1<sup>st</sup> <span class="no-bold">Semester</span></h4></td>
                </tr>

                <tr>
                    <td colspan="2" class="break-line"><h4>ID: <span class="no-bold">{{$student->id_card}}</span></h4></td>
                </tr>
                <tr>
                    <td><h4><b>Name:</b> {{$student->name_latin}}</h4></td>
                    <td class="col-right"><h4>Sex: <span class="no-bold">Female</span></h4></td>
                </tr>
            </table>
            <div class="transcript-title">
                <h2>Academic Transcript</h2>
                <h4>Academic Year: {{$student->academic_year_latin}}</h4>
            </div>
        </div>
        {{--transcript body--}}
        <div class="transcript-body">
            <div class="subject-credits-grades">
                <table class="subject">
                    <tr>
                        <th align="left">Subjects</th>
                        <th style="text-align: center;width: 30mm">Credits</th>
                        <th style="text-align: center;width: 30mm">Grades</th>
                    </tr>

                    <?php $i = 1 ?>
                    @foreach($scores as $key => $score)
                        @if(is_numeric($key))
                        <tr>
                            <td>{{ $i }} - {{isset($score['name_en'])?$score['name_en']:""}}</td>
                            <td>{{ $score["credit"] }}</td>
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
                            <td class="col-right">{!! $grade !!}</td>
                        </tr>
                        <?php $i++ ?>
                        @endif
                    @endforeach
                </table>
            </div>
            <div class="gpa">
                <?php
                    if($semester == 1) {
                        $last_score = $scores["final_score_s1"];
                    } else if ($semester == 2) {
                        $last_score = $scores["final_score_s2"];
                    } else {
                        $last_score = $scores["final_score"];
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
                <h4>GPA: {{$gpa}}</h4>
            </div>
            <div class="director-signature">
                <p>Phnom Penh, March 13, 2017</p>
                <h4>Deputy Director General</h4>
            </div>
            <div class="clearfix"></div>


        {{--transcript footer--}}
        <div class="transcript-footer">
            <div class="grading-system">
                <h4>GRADING SYSTEM:</h4>
                <table class="">
                    <tr>
                        <td>A = 85% -100% = 4.00 = Excellent</td>
                        <td>C = 50% - 64% = 2.00 = Fair</td>
                    </tr>
                    <tr>
                        <td>B<sup>+</sup> = 80% - 84% = 3.50 = Very Good</td>
                        <td>D = 45% - 49% = 1.50 = Poor</td>
                    </tr>
                    <tr>
                        <td>B = 70% - 79% = 3.00 = Good</td>
                        <td>E = 40% - 44% = 1.00 = Very Poor</td>
                    </tr>
                    <tr>
                        <td>C<sup>+</sup> = 65% - 69% = 2.5 = Fairly Good</td>
                        <td>F = < 40% = 0.00 = Failure</td>
                    </tr>
                </table>
            </div>
            <div class="remark">
                <h4>Remark:</h4>
                <ul class="list-remark" type="square">
                    <li>The annual Grade Point Average minimum requirement to pass to the higher class is 2.0.</li>
                    <li>This transcript cannot be given for the second time.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script>

    </script>
@stop
