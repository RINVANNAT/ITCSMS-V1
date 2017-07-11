<input type="hidden" name="academic_year_id" value="{{$academicYear->id}}">
<table class="table table-bordered">

    <thead>
    <tr class="active">
        <th>No</th>
        <th>Subject</th>
        <th>Student </th>
        <th >Date Time</th>
        <th>Room</th>
        <th>Amount</th>

    </tr>
    </thead>
    <tbody>

    <?php $index = 1;?>

    <?php $totalCredit=0;  $courseProgramBaseIds = [];?>
    @foreach($courseAnnuals as $courseAnnual)
        <input type="hidden" name="department_id" value="{{$courseAnnual->department_id}}">
        <input type="hidden" name="degree_id" value="{{$courseAnnual->degree_id}}">
        <input type="hidden" name="grade_id" value="{{$courseAnnual->grade_id}}">
        <input type="hidden" name="department_option_id" value="{{$courseAnnual->department_option_id}}">

        @if(in_array($courseAnnual->course_annual_id, $failCourseAnnuals))

            @if(count($courseProgramBaseIds) > 0)

                @if(!in_array($courseAnnual->course_id, $courseProgramBaseIds))

                    @if($courseAnnual->is_counted_creditability)

                        <?php $courseProgramBaseIds[] = $courseAnnual->course_id; $cournResitStudent = 0; ?>

                        <tr course_annual_id = "{{$courseAnnual->course_annual_id}}" course_annual_name = "{{$courseAnnual->name_en}}">
                            <input type="hidden" name="course_annual_id[]" value="{{$courseAnnual->course_annual_id}}">
                            <td>{{$index}}</td>
                            <td >{{$courseAnnual->name_en}}</td>
                            <td class="td_student_name" style="text-align:center; vertical-align:middle;">
                                @foreach($studentRattrapages as $student)

                                    <?php
                                    $courseAnnualFailIds = isset($student['fail']) ?(collect($student['fail'])->pluck('course_annual_id')->toArray()):[];
                                    ?>
                                    @if(in_array($courseAnnual->course_annual_id, $courseAnnualFailIds))
                                        <label for="name" class="label" style="width: 100%; color: #0A0A0A; font-size: 10pt">
                                            {{$student['student']->name_kh }}
                                            <input type="hidden" class="student_annual_id" name="student_annual_id[]" value="{{$student['student']->student_annual_id}}">
                                            <input type="hidden" name="{{'course['.$courseAnnual->course_annual_id.']'.'[]'}}" value="{{$student['student']->student_annual_id}}">
                                        </label>
                                        <br>
                                        <?php $cournResitStudent++;?>
                                    @endif

                                @endforeach

                            </td>

                            <td style="text-align:center; vertical-align:middle;">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" name="{{'date_start_end['.$courseAnnual->course_annual_id.']'}}" class="form-control pull-right date_start_end">
                                </div>

                            </td>

                            <td style="text-align:center; vertical-align:middle;">
                                <input type="text" class="form-control room" name="{{'room['.$courseAnnual->course_annual_id.']'}}" >
                            </td>


                            <td class="count_resit" style="text-align:center; vertical-align:middle;">

                                <label for="count_resit" class="label label-success" style="font-size: 14pt"> {{$cournResitStudent}}</label>

                            </td>

                        </tr>

                        <?php $index++;?>
                    @endif
                @endif
            @else
                @if($courseAnnual->is_counted_creditability)

                    <?php $courseProgramBaseIds[] = $courseAnnual->course_id; $cournResitStudent = 0; ?>

                    <tr course_annual_id = "{{$courseAnnual->course_annual_id}}" course_annual_name = "{{$courseAnnual->name_en}}">
                        <input type="hidden" name="course_annual_id[]" value="{{$courseAnnual->course_annual_id}}">
                        <td>{{$index}}</td>
                        <td >{{$courseAnnual->name_en}}</td>
                        <td class="td_student_name" style="text-align:center; vertical-align:middle;" >
                            @foreach($studentRattrapages as $student)

                                <?php
                                $courseAnnualFailIds = isset($student['fail']) ?(collect($student['fail'])->pluck('course_annual_id')->toArray()):[];
                                ?>

                                @if(in_array($courseAnnual->course_annual_id, $courseAnnualFailIds))

                                    <label for="name" class="label" style="width: 100%; color: #0A0A0A; font-size: 10pt">
                                        {{$student['student']->name_kh }}
                                        <input type="hidden" class="student_annual_id" name="student_annual_id[]" value="{{$student['student']->student_annual_id}}">
                                        <input type="hidden" name="{{'course['.$courseAnnual->course_annual_id.']'.'[]'}}" value="{{$student['student']->student_annual_id}}">
                                    </label>
                                    <br>

                                    <?php $cournResitStudent++; ?>
                                @endif

                            @endforeach

                        </td>

                        <td style="text-align:center; vertical-align:middle;">

                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" name="{{'date_start_end['.$courseAnnual->course_annual_id.']'}}" class="form-control pull-right date_start_end" >
                            </div>

                        </td>

                        <td style="text-align:center; vertical-align:middle;">
                            <input type="text" class="form-control room" name="{{'room['.$courseAnnual->course_annual_id.']'}}">
                        </td>

                        <td class="count_resit" style="text-align:center; vertical-align:middle;">
                            <label for="count_resit" class="label label-success" style="font-size: 14pt"> {{$cournResitStudent}}</label>
                        </td>

                    </tr>

                    <?php $index++?>

                @endif

            @endif

        @endif
    @endforeach

    </tbody>
</table>

<button class="btn btn-info" type="submit" id="export_course_lists" style="margin-left: 5px; margin-bottom: 5px"> Save & Export </button>