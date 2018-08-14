@extends('backend.layouts.printing_portrait_a4_transcript')
@section('title')
    ITC-SMS | ព្រឹត្តិបត្រ័ពិន្ទុ
@stop

@section('content')
@foreach($students as $key => $student)
    <?php
        if($transcript_type == "semester1") {
            $last_score = $scores[$student['id']]["final_score_s1"];
            $gpa = get_gpa($last_score, $passedScoreI);
        } else {
            $last_score = $scores[$student['id']]["final_score"];
            $gpa = get_gpa($last_score, $passedScoreFinal);
        }
    ?>
    @if(floatval($gpa) > 1.5 || $transcript_type == "semester1")
        @if (count($scores[$student['id']]) > 18)
            <div class="page">
                <div class="transcript">
                    {{--transcript header--}}
                    <div class="transcript-header">
                        <table class="head">
                            <tr>
                                <td colspan="2"><h4><b>Department1:</b> <span class="no-bold">{{$student['department_en']}}</span></h4></td>
                            </tr>
                            @if($student['option_en'] != "" && ($student['department_id'] != 2 && $student['degree_id'] != 2 && $student['grade_id'] != 2 ))
                                <tr>
                                    <td colspan="2"><h4><b>Option:</b> <span class="no-bold">{{$student['option_en']}}</span></h4></td>
                                </tr>
                            @elseif($student['option_en'] != "" && ($student['department_id'] == 2 && $student['degree_id'] == 1 ))
                                <tr>
                                    <td colspan="2"><h4><b>Option:</b> <span class="no-bold">{{$student['option_en']}}</span></h4></td>
                                </tr>
                            @endif
                            <tr>
                                <td width="75%"><h4><b>Degree:</b> <span class="no-bold">{{$student['degree_en']}}</span> </h4></td>
                                <td width="25%"><h4><b>Class:</b> <span class="no-bold">{{$student['grade_en']}}</span></h4></td>
                            </tr>
                            @if($transcript_type == "semester1")
                                <tr>
                                    <td width="75%"></td>
                                    <td width="25%"><h4>1<sup>st</sup> <span class="no-bold">Semester</span></h4></td>
                                </tr>
                            @endif

                            <tr>
                                <td colspan="2" class="break-line custom-break-line"><h4><b>ID:</b> <span class="no-bold">{{$student['id_card']}}</span></h4></td>
                            </tr>
                            <tr>
                                <td width="75%"><h4><b>Name: {{strtoupper($student['name_latin'])}}</b></h4></td>
                                <td width="25%">
                                    <h4>
                                        <b>Sex:</b>
                                        <span class="no-bold">
                                    @if(strtolower($student['gender']) == 'm')
                                                Male
                                            @else
                                                Female
                                            @endif
                                </span>
                                    </h4>
                                </td>
                            </tr>
                        </table>
                        <div class="transcript-title custom-transcript-title">
                            <center><img src="{{url('img/transcript/transcript.gif')}}"></center>
                            <h4><b>Academic Year: {{$student['academic_year_latin']}}</b></h4>
                        </div>
                    </div>
                    {{--transcript body--}}
                    <div class="transcript-body">
                        <div class="subject-credits-grades">
                            <table class="subject custom-subject">
                                <tr>
                                    <th style="text-align: left;" colspan="2">Subjects</th>
                                    <th style="text-align: center;">Credits</th>
                                    <th style="text-align: right; padding-right: 30px;">Grades</th>
                                </tr>

                                <?php $i = 1 ?>
                                @foreach($scores[$student['id']] as $key => $score)
                                    @if(is_numeric($key))
                                        <tr>
                                            <td style="width: 5%;">{{ $i }} -</td>
                                            <td style="text-align: left;width: 50%">{{isset($score['name_en'])?$score['name_en']:""}}</td>
                                            <td style="width: 22.5%;">{{ $score["credit"] }}</td>
                                            @if($score['semester'] == 1)
                                                @php $grade = get_grading($score["score"], $passedScoreI); @endphp
                                            @else
                                                @php $grade = get_grading($score["score"], $passedScoreII); @endphp
                                            @endif
                                            <td class="col-right" style="width: 22.5%; padding-right: 40px;">{!! $grade !!}</td>
                                        </tr>
                                        <?php $i++ ?>
                                    @endif
                                @endforeach
                            </table>
                        </div>
                        <div class="gpa">
                            <h4> <b>GPA: {{$gpa}}</b></h4>
                        </div>
                        <div class="director-signature">
                            <center>
                                <?php
                                $date = \Carbon\Carbon::createFromFormat("d/m/Y",$issued_date);
                                ?>
                                <p>Phnom Penh, {{$date->formatLocalized('%B %d, %Y')}}</p>
                                <h4><b>{{$issued_by}}</b></h4>
                            </center>
                        </div>
                        <div class="clearfix"></div>
                    </div>

                    {{--transcript footer--}}
                    <div class="transcript-footer">
                        <div class="grading-system">
                            <h5 style="font-weight: bold !important; font-family: times_new_roman_normal; line-height: 0;">GRADING SYSTEM:</h5>
                            <table style="margin-left: 0px; width: 60%;">
                                <tr>
                                    <td>
                                        <table style="width: 150%;">
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
                                        <table style="width: 150%; margin-left: 100px">
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
                                                <td style="width: 6% !important"> &nbsp;= &lt;</td>
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
                        <div class="remark" style="font-family: times_new_roman_normal;">
                            <h5 style="font-weight: bold !important; font-family: times_new_roman_normal; line-height: 0;">Remark:</h5>
                            <span style="margin-left: 10mm; display: block;"><b style="font-size: 14pt">*</b> The annual Grade Point Average minimum requirement to pass to the higher class is 2.0.</span>
                            <span style="margin-left: 10mm; display: block; line-height: 0.5;"><b style="font-size: 14pt">*</b> This transcript cannot be given for the second time.</span>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="page">
                <div class="transcript">
                    {{--transcript header--}}
                    <div class="transcript-header">
                        <table class="head">
                            <tr>
                                <td colspan="2"><h4><b>Department:</b> <span class="no-bold">{{$student['department_en']}}</span></h4></td>
                            </tr>
                            @if($student['option_en'] != "" && ($student['department_id'] != 2 && $student['degree_id'] != 2 && $student['grade_id'] != 2 ))
                                <tr>
                                    <td colspan="2"><h4><b>Option:</b> <span class="no-bold">{{$student['option_en']}}</span></h4></td>
                                </tr>
                            @elseif($student['option_en'] != "" && ($student['department_id'] == 2 && $student['degree_id'] == 1 ))
                                <tr>
                                    <td colspan="2"><h4><b>Option:</b> <span class="no-bold">{{$student['option_en']}}</span></h4></td>
                                </tr>
                            @endif
                            <tr>
                                <td width="75%"><h4><b>Degree:</b> <span class="no-bold">{{$student['degree_en']}}</span> </h4></td>
                                <td width="25%"><h4><b>Class:</b> <span class="no-bold">{{$student['grade_en']}}</span></h4></td>
                            </tr>
                            @if($transcript_type == "semester1")
                                <tr>
                                    <td width="75%"></td>
                                    <td width="25%"><h4>1<sup>st</sup> <span class="no-bold">Semester</span></h4></td>
                                </tr>
                            @endif

                            <tr>
                                <td colspan="2" class="break-line"><h4><b>ID:</b> <span class="no-bold">{{$student['id_card']}}</span></h4></td>
                            </tr>
                            <tr>
                                <td width="75%"><h4><b>Name: {{strtoupper($student['name_latin'])}}</b></h4></td>
                                <td width="25%">
                                    <h4>
                                        <b>Sex:</b>
                                        <span class="no-bold">
                                    @if(strtolower($student['gender']) == 'm')
                                                Male
                                            @else
                                                Female
                                            @endif
                                </span>
                                    </h4>
                                </td>
                            </tr>
                        </table>
                        <div class="transcript-title">
                            <center><img src="{{url('img/transcript/transcript.gif')}}"></center>
                            <h4><b>Academic Year: {{$student['academic_year_latin']}}</b></h4>
                        </div>
                    </div>
                    {{--transcript body--}}
                    <div class="transcript-body">
                        <div class="subject-credits-grades">
                            <table class="subject">
                                <tr>
                                    <th style="text-align: left;" colspan="2">Subjects</th>
                                    <th style="text-align: center;">Credits</th>
                                    <th style="text-align: right; padding-right: 30px;">Grades</th>
                                </tr>

                                <?php $i = 1 ?>
                                @foreach($scores[$student['id']] as $key => $score)
                                    @if(is_numeric($key))
                                        <tr>
                                            <td style="width: 5%;">{{ $i }} -</td>
                                            <td style="text-align: left;width: 50%">{{isset($score['name_en'])?$score['name_en']:""}}</td>
                                            <td style="width: 22.5%;">{{ $score["credit"] }}</td>
                                            @if($score['semester'] == 1)
                                                @php $grade = get_grading($score["score"], $passedScoreI); @endphp
                                            @else
                                                @php $grade = get_grading($score["score"], $passedScoreII); @endphp
                                            @endif
                                            <td class="col-right" style="width: 22.5%; padding-right: 40px;">{!! $grade !!}</td>
                                        </tr>
                                        <?php $i++ ?>
                                    @endif
                                @endforeach
                            </table>
                        </div>
                        <div class="gpa">
                            <h4> <b>GPA: {{$gpa}}</b></h4>
                        </div>
                        <div class="director-signature">
                            <center>
                                <?php
                                $date = \Carbon\Carbon::createFromFormat("d/m/Y",$issued_date);
                                ?>
                                <p>Phnom Penh, {{$date->formatLocalized('%B %d, %Y')}}</p>
                                <h4><b>{{$issued_by}}</b></h4>
                            </center>
                        </div>
                        <div class="clearfix"></div>
                    </div>

                    {{--transcript footer--}}
                    <div class="transcript-footer">
                        <div class="grading-system">
                            <h5 style="font-weight: bold !important; font-family: times_new_roman_normal;">GRADING SYSTEM:</h5>
                            <table style="margin-left: 0px; width: 60%;">
                                <tr>
                                    <td>
                                        <table style="width: 150%;">
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
                                        <table style="width: 150%; margin-left: 100px">
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
                                                <td style="width: 6% !important"> &nbsp;= &lt;</td>
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
                        <div class="remark" style="font-family: times_new_roman_normal;">
                            <h5 style="font-weight: bold !important; font-family: times_new_roman_normal; line-height: 0;">Remark:</h5>
                            <span style="margin-left: 10mm; display: block;"><b style="font-size: 14pt">*</b> The annual Grade Point Average minimum requirement to pass to the higher class is 2.0.</span>
                            <span style="margin-left: 10mm; display: block; line-height: 0.5;"><b style="font-size: 14pt">*</b> This transcript cannot be given for the second time.</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif
@endforeach

@endsection

@section('scripts')
    <script>

    </script>
@endsection
