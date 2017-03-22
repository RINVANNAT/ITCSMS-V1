<input type="hidden" name="academic_year_id" value="{{$academicYear->id}}">
<table class="table table-bordered">
    <thead>
    <tr class="active">
        <th>No</th>
        <th>Subject</th>
        <th>Student </th>
        <th>Date Time</th>
        <th>Room</th>
        <th>Amount</th>

    </tr>
    </thead>
    <tbody>

    <?php $index = 1;?>

    @foreach($coursePrograms as $courseProgram)
        <?php $count = 0;?>

        <input type="hidden" value="{{$courseProgram->id}}" name="course_program_id[]">

        @foreach( $courseAnnualByProgram[$courseProgram->id] as $course_annual_id)
            <input type="hidden" value="{{$course_annual_id}}" name="course_program_id_{{$courseProgram->id}}[]">
        @endforeach




        <tr>
            <td>{{$index}}</td>
            <td >{{$courseProgram->name_en}}</td>
            <td>

                @foreach($students as $student)

                    <?php
                    $resitSubject = $courseAnnualByProgram[$courseProgram->id];
                        $subjectResit = $studentRattrapages[$student->id_card];

                        if(isset($subjectResit['fail'])) {
                            $intersect = array_intersect($resitSubject, $studentRattrapages[$student->id_card]['fail']);
                        } else {
                            $intersect=[];
                        }
                    ?>

                    @if($intersect)
                            <input type="hidden" name="student_annual_id[]" value="{{$student->student_annual_id}}">
                        <?php $count++;$intersect = array_values($intersect);?>
                            <input type="hidden" name="{{$student->student_annual_id}}[]" value="{{$intersect[0]}}">
                            <input type="hidden" name="{{$courseProgram->id}}[]" value="{{$student->student_annual_id}}">
                        <li>{{$student->name_latin}}</li>
                    @endif


                @endforeach

            </td>

            <td>
                <label for="start_time" class="spacing"> Date </label><input type="date" name="date_{{$courseProgram->id}}" required >
                <label for="start_time" class="spacing"> Sart </label><input type="time" name="start_time_{{$courseProgram->id}}" required >
                <label for="end_time" class="spacing"> End </label><input type="time" name="end_time_{{$courseProgram->id}}" required>
            </td>
            <td><input type="text" name="room_{{$courseProgram->id}}" required></td>

            <td class="count_resit">
                {{($count > 0)?$count:'-'}}
            </td>

        </tr>

        <?php $index++;?>

    @endforeach


    </tbody>
</table>

<button class="btn btn-info" type="submit" id="export_course_lists" style="margin-left: 5px; margin-bottom: 5px"> Export Schedule </button>