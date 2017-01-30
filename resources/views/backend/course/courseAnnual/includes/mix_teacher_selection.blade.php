@if($totalTeachers)
    <select name="employee_id_" id="employee_id">
        @foreach($totalTeachers as $teacher)
            <option value="{{$teacher->teacher_id}}"> {{$teacher->teacher_name}}</option>
        @endforeach

    </select>
@endif