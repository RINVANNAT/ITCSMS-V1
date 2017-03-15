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
                    <td colspan="2"><h4><b>Department:</b> <span class="no-bold">{{$student->department_en}}</span></h4></td>
                </tr>
                <tr>
                    <td width="80%"><h4><b>Degree:</b> <span class="no-bold">Engineer</span> </h4></td>
                    <td width="20%"><h4><b>Class:</b> <span class="no-bold">{{$student->grade_en}}</span></h4></td>
                </tr>
                <tr>
                    <td width="80%"></td>
                    <td width="20%"><h4>1<sup>st</sup> <span class="no-bold">Semester</span></h4></td>
                </tr>

                <tr>
                    <td colspan="2" class="break-line"><h4><b>ID:</b> <span class="no-bold">{{$student->id_card}}</span></h4></td>
                </tr>
                <tr>
                    <td width="80%"><h4><b>Name: {{$student->name_latin}}</b></h4></td>
                    <td width="20%"><h4><b>Sex:</b> <span class="no-bold">Female</span></h4></td>
                </tr>
            </table>
            <div class="transcript-title">
                <center><img src="{{url('img/transcript/transcript.gif')}}"></center>
                <h4><b>Academic Year: {{$student->academic_year_latin}}</b></h4>
            </div>
        </div>
        {{--transcript body--}}
        <div class="transcript-body">
            <div class="subject-credits-grades">
                <table class="subject">
                    <tr>
                        <th style="text-align: left;" colspan="2">Subjects</th>
                        <th style="text-align: center;">Credits</th>
                        <th style="text-align: center;">Grades</th>
                    </tr>

                    <?php $i = 1 ?>
                    @foreach($scores as $key => $score)
                        @if(is_numeric($key))
                        <tr>
                            <td width="5%">{{ $i }} -</td>
                            <td style="text-align: left;width: 35%">{{isset($score['name_en'])?$score['name_en']:""}}</td>
                            <td style="width: 20%">{{ $score["credit"] }}</td>
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
                            <td class="col-right" style="width: 40%;">{!! $grade !!}</td>
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
                <h4> <b>GPA: {{$gpa}}</b></h4>
            </div>
            <div class="director-signature">
                <center>
                <p>Phnom Penh, March 13, 2017</p>
                <h4><b>Deputy Director General</b></h4>
                </center>
            </div>
            <div class="clearfix"></div>


        {{--transcript footer--}}
        <div class="transcript-footer">
            <div class="grading-system">
                <h4><b>GRADING SYSTEM:</b></h4>
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
                <h4><b>Remark:</b></h4>
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
