@if(isset($academicYears))
    <select name="academicYear">
        <option disabled>{{ trans('inputs.backend.schedule.timetable.options.academic_year') }}</option>
        @foreach($academicYears as $index => $academicYear)
            @if($index == 0)
                <option value="{{ $academicYear->id }}" selected>{{ $academicYear->name_latin }}</option>
            @else
                <option value="{{ $academicYear->id }}">{{ $academicYear->name_latin }}</option>
            @endif
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

    @if(isset($degrees))
        <select name="degree">
            <option disabled>{{ trans('inputs.backend.schedule.timetable.options.degree') }}</option>
            @foreach($degrees as $index => $degree)
                @if($index == 0)
                    <option value="{{ $degree->id }}" selected>{{ $degree->name_en }}</option>
                @else
                    <option value="{{ $degree->id }}">{{ $degree->name_en }}</option>
                @endif
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
@endif

@if(isset($grades))
    <select name="grade">
        <option disabled>{{ trans('inputs.backend.schedule.timetable.options.grade') }}</option>
        @foreach($grades as $index => $grade)
            @if($index == 2)
                <option value="{{ $grade->id }}" selected>{{ $grade->name_en }}</option>
            @else
                <option value="{{ $grade->id }}">{{ $grade->name_en }}</option>
            @endif
        @endforeach
    </select>
@endif

@if(isset($semesters))
    <select name="semester">
        <option disabled>{{ trans('inputs.backend.schedule.timetable.options.semester') }}</option>
        @foreach($semesters as $index => $semester)
            @if($index == 0)
                <option value="{{ $semester->id }}" selected>{{ $semester->name_en }}</option>
            @else
                <option value="{{ $semester->id }}">{{ $semester->name_en }}</option>
            @endif
        @endforeach
    </select>
@endif

<select name="group">
    <option selected disabled>{{ trans('inputs.backend.schedule.timetable.options.group') }}</option>
</select>

@if(isset($weeks))
    <select name="weekly">
        <option selected disabled>{{ trans('inputs.backend.schedule.timetable.options.weekly') }}</option>
        @foreach($weeks as $week)
            <option value="{{ $week->id }}">{{ $week->name_en }}</option>
        @endforeach
    </select>
@endif