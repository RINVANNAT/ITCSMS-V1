<select name="course_id" id="course_id" class="form-control" required>
    <option value="" disabled selected>Course</option>
    @foreach($courses as $course)
        <option value="{{$course->id}}" time_course="{{$course->time_course}}" time_tp="{{$course->time_tp}}" time_td="{{$course->time_td}}" name_kh="{{$course->name_kh}}" name_en="{{$course->name_en}}" name_fr="{{$course->name_fr}}" credit="{{$course->credit}}" dept="{{$course->department_id}}" grade="{{$course->grade_id}}" degree="{{$course->degree_id}}" dept_option="{{$course->department_option_id}}" semester="{{$course->semester_id}}">{{$course->name_en}}</option>
    @endforeach
</select>