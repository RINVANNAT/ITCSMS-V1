@extends ('backend.layouts.popup_master')

@section ('title', 'Student Redouble Lists' . ' | ' . 'Redouble Courses')

@section('content')

    <div class="box box-success">

        <style>

            div.vertical{
                position: relative;
                height: 130px;
                margin-left: 0;
                writing-mode: tb-rl;
                filter: flipv fliph;
                text-align: center;
            }

            th.vertical {
                padding-bottom: 10px;
                vertical-align: bottom;
            }

            th, td.center {
                text-align: center;
            }

        </style>
        <div class="box-header with-border">
            <h3 class="box-title">Student Supplementary Lists: {{$academicYear->name_latin}}</h3>

        </div>
        <!-- /.box-header -->
        <div class="box-body panel">

            {!! Form::open(['route' => 'course_annual.export_student_re_exam', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post', 'id' => 'student_re_exam_form']) !!}

            <input type="hidden" name="academic_year_id" value="{{$academicYear->id}}">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>No</th>
                    <th>ID-Card</th>
                    <th>Name</th>
                    <?php $totalCredit=0?>
                    @foreach($coursePrograms as $program)
                        <?php $course = $courseAnnualByCourseProgramObject[$program->id][0]?>
                        <?php
                            $totalCredit = $totalCredit + $course->credit;
                        ?>
                        <th><div class="vertical">{{$program->name_en}}<br>{{$course->credit}}  </div> </th>
                    @endforeach
                    <th>Moyenne <br>{{$totalCredit}} </th>
                </tr>
                </thead>
                <tbody>

                <?php $index = 1;?>

                @foreach($students as $student)

                    <?php $totalScore =0;?>

                    <input type="hidden" value="{{$student->id_card}}" name="student_id_card[]">
                    <tr>
                        <td>{{$index}}</td>
                        <td>{{$student->id_card}}</td>
                        <td >{{$student->name_latin}}</td>
                        @foreach($coursePrograms as $program)
                            <?php $courseAnnual = $courseAnnualByCourseProgramObject[$program->id][0]?>

                            @if(count(array_intersect($exam_subjects[$student->id_card]['fail'], $courseAnnualByProgram[$program->id]))> 0)

                                <?php

                                    $courseAnnualIdArray = array_intersect($exam_subjects[$student->id_card]['fail'], $courseAnnualByProgram[$program->id]);
                                    //---this array have only one element of exact course annual
                                    foreach($courseAnnualIdArray as $courseAnnualId) {
                                        $studentScore = isset($averages[$courseAnnualId])?$averages[$courseAnnualId][$student->student_annual_id]:null;
                                    }
                                    //---we need to find the course annual credit not course program credit
                                    $totalScore = $totalScore + ( (($studentScore != null)?$studentScore->average:0) * $courseAnnual->credit);
                                ?>
                                <td>
                                    <label for="score"><input type="checkbox" name="{{$student->id_card}}[]" value="{{$program->id}}" checked> {{($studentScore!=null)?$studentScore->average:0}}</label>

                                </td>
                            @else

                                <?php

                                $courseAnnualIdArray = array_intersect($exam_subjects[$student->id_card]['pass'], $courseAnnualByProgram[$program->id]);
                                //---this array have only one element of exact course annual
                                foreach($courseAnnualIdArray as $courseAnnualId) {
                                    $studentScore = isset($averages[$courseAnnualId])?$averages[$courseAnnualId][$student->student_annual_id]:null;
                                    //-----course annual credit not course program credit
                                    $totalScore = $totalScore + ( (($studentScore !=null)?$studentScore->average:0) * $courseAnnual->credit);
                                }
                                ?>
                                <td width="">
                                    <label for="score"><input type="checkbox" name="{{$student->id_card}}[]" value="{{$program->id}}"> {{($studentScore!=null)?$studentScore->average:0}}</label>

                                </td>

                            @endif

                        @endforeach
                        <?php

                            $moyenne = number_format((float)($totalScore/(($totalCredit >0)?$totalCredit:1)), 2, '.', '');
                        ?>
                        <td>{{$moyenne}}</td>
                    </tr>

                    <?php $index++;?>

                @endforeach


                </tbody>
            </table>

            <button type="submit" class="btn btn-info"> Export Lists </button>


            {!! Form::close() !!}

        </div>

    </div>

    {{--secod table--}}


    <div class="box box-success">

        <style>
            .spacing{
                margin-right: 5px;
            }

        </style>
        <div class="box-header with-border">
            <h3 class="box-title">Schedule Supplementary Subjects</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body panel">

            {!! Form::open(['route' => 'course_annual.export_supplementary_subject', 'class' => 'form-horizontal ', 'role' => 'form', 'method' => 'post', 'id' => 'supplementary_subject_lists']) !!}

            <input type="hidden" name="academic_year_id" value="{{$academicYear->id}}">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Subject</th>
                    <th>Date Time</th>
                    <th>Room</th>

                </tr>
                </thead>
                <tbody>

                <?php $index = 1;?>

                @foreach($coursePrograms as $courseProgram)

                    <input type="hidden" value="{{$courseProgram->id}}" name="course_program_id[]">
                    <tr>
                        <td>{{$index}}</td>
                        <td>{{$courseProgram->name_en}}</td>
                        <td>
                            <label for="start_time" class="spacing"> Date </label><input type="date" name="date_{{$courseProgram->id}}" required >
                            <label for="start_time" class="spacing"> Sart-Time </label><input type="time" name="start_time_{{$courseProgram->id}}" required >
                            <label for="end_time" class="spacing"> End Time </label><input type="time" value="2" name="end_time_{{$courseProgram->id}}" required>
                        </td>
                        <td><input type="text" name="room_{{$courseProgram->id}}" required></td>

                    </tr>

                    <?php $index++;?>

                @endforeach


                </tbody>
            </table>

            <button class="btn btn-info" type="submit" id="export_course_lists"> Export Schedule </button>

            {!! Form::close() !!}

        </div>

    </div>

    <div class="box box-success">
        <div class="box-body">
            <div class="pull-left">
                <a href="#" id="cancel_table" class="btn btn-default btn-xs">{{ trans('buttons.general.cancel') }}</a>
            </div>

            <div class="pull-right">
                {{--<input type="button" id="btn_update_course" class="btn btn-danger btn-xs" value="Save and Export" />--}}
            </div>
            <div class="clearfix"></div>
        </div><!-- /.box-body -->
    </div><!--box-->
@stop

@section('after-scripts-end')

    {{--myscript--}}

    <script>

        $('#cancel_table').on('click', function() {
            window.close();
        })



    </script>


@stop