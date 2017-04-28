@if(isset($academicYears))
    <select name="academicYear">
        <option selected disabled>{{ trans('inputs.backend.schedule.timetable.options.academic_year') }}</option>
        @foreach($academicYears as $academicYear)
            <option value="{{ $academicYear->id }}">{{ $academicYear->name_latin }}</option>
        @endforeach
    </select>
@endif
{{--admin--}}
@if(access()->allow('global-timetable-management'))

    @if(isset($departments))
        <select name="department">
            @foreach($departments as $department)
                <option value="{{ $department->id }}">{{ $department->code }}</option>
            @endforeach
        </select>
    @endif

    @if(isset($degree))
        <select name="degree">
            <option selected disabled>{{ trans('inputs.backend.schedule.timetable.options.degree') }}</option>
            @foreach($degrees as $degree)
                <option value="{{ $degree->id }}">{{ $degree->name_en }}</option>
            @endforeach
        </select>
    @endif

    @if(isset($options))
        <select name="option">
            <option selected disabled>{{ trans('inputs.backend.schedule.timetable.options.option') }}</option>
            @foreach($options as $option)
                <option value="{{ $option->id }}">{{ $option->code }}</option>
            @endforeach
        </select>
    @endif

    @if(isset($grades))
        <select name="grade">
            <option selected disabled>{{ trans('inputs.backend.schedule.timetable.options.grade') }}</option>
            @foreach($grades as $grade)
                <option value="{{ $grade->id }}">{{ $grade->name_en }}</option>
            @endforeach
        </select>
    @endif

@else

    @if(isset($department))
        <select name="degree">
            <option selected disabled>{{ trans('inputs.backend.schedule.timetable.options.degree') }}</option>
            @foreach($department->degrees as $degree)
                <option value="{{ $degree->id }}">{{ $degree->name_en }}</option>
            @endforeach
        </select>
    @endif

    @if(count($department->department_options)>0)
        <select name="option">
            <option selected disabled>{{ trans('inputs.backend.schedule.timetable.options.option') }}</option>
            @foreach($department->department_options as $option)
                <option value="{{ $option->id }}">{{ $option->code }}</option>
            @endforeach
        </select>
    @endif

    @if(isset($grades))
        <select name="grade">
            <option selected disabled>{{ trans('inputs.backend.schedule.timetable.options.grade') }}</option>
            @foreach($grades as $grade)
                <option value="{{ $grade->id }}">{{ $grade->name_en }}</option>
            @endforeach
        </select>
    @endif

@endif

@if(isset($semesters))
    <select name="semester">
        <option selected disabled>{{ trans('inputs.backend.schedule.timetable.options.semester') }}</option>
        @foreach($semesters as $semester)
            <option value="{{ $semester->id }}">{{ $semester->name_en }}</option>
        @endforeach
    </select>
@endif

<select name="group">
    <option selected disabled>{{ trans('inputs.backend.schedule.timetable.options.group') }}</option>
    <option value="a">A</option>
    <option value="b">B</option>
    <option value="c">C</option>
</select>

<select name="weekly">
    <option selected disabled>{{ trans('inputs.backend.schedule.timetable.options.weekly') }}</option>
    <option value="1">Week 1</option>
    <option value="2">Week 2</option>
    <option value="3">Week 3</option>
</select>
