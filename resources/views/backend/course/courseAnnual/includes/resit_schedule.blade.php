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

<button class="btn btn-info" type="submit" id="export_course_lists" style="margin-left: 5px; margin-bottom: 5px"> Export Schedule </button>