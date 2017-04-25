<select name="academicYear">
    <option selected disabled>{{ trans('inputs.backend.schedule.timetable.options.academic_year') }}</option>
    @foreach($academicYears as $academicYear)
        <option value="{{ $academicYear->id }}">{{ $academicYear->name_latin }}</option>
    @endforeach
</select>

<select name="degree">
    <option selected disabled>{{ trans('inputs.backend.schedule.timetable.options.degree') }}</option>
    @foreach($degrees as $degree)
        <option value="{{ $degree->id }}">{{ $degree->name_en }}</option>
    @endforeach
</select>

<select name="grade">
    <option selected disabled>{{ trans('inputs.backend.schedule.timetable.options.grade') }}</option>
    @foreach($grades as $grade)
        <option value="{{ $grade->id }}">{{ $grade->name_en }}</option>
    @endforeach
</select>

<select name="option">
    <option selected disabled>{{ trans('inputs.backend.schedule.timetable.options.option') }}</option>
    @foreach($options as $option)
        <option value="{{ $option->id }}">{{ $option->code }}</option>
    @endforeach
</select>

<select name="semester">
    <option selected disabled>{{ trans('inputs.backend.schedule.timetable.options.semester') }}</option>
    @foreach($semesters as $semester)
        <option value="{{ $semester->id }}">{{ $semester->name_en }}</option>
    @endforeach
</select>

<select name="weekly">
    <option selected disabled>{{ trans('inputs.backend.schedule.timetable.options.weekly') }}</option>
    <option value="1">Week 1</option>
    <option value="2">Week 2</option>
    <option value="3">Week 3</option>
</select>