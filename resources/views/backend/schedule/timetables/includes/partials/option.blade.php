@if(isset($academicYears))
    <select name="academicYear"
            data-toggle="tooltip"
            data-placement="top"
            title="{{ trans('inputs.backend.schedule.timetable.options.academic_year') }}">
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
@if(access()->allow('global-timetable'))
    @if(isset($departments))
        <select name="department"
                data-toggle="tooltip"
                data-placement="top"
                title="Department">
            @foreach($departments as $department)
                <option value="{{ $department->id }}">{{ $department->code }}</option>
            @endforeach
        </select>
    @endif

    @if(isset($degrees))
        <select name="degree"
                data-toggle="tooltip"
                data-placement="top"
                title="{{ trans('inputs.backend.schedule.timetable.options.degree') }}">
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
        <select name="option"
                data-toggle="tooltip"
                data-placement="top"
                title="{{ trans('inputs.backend.schedule.timetable.options.option') }}">
            @if(isset($options_))
                @foreach($options_ as $item)
                    <option value="{{ $item->id }}">{{ $item->code }}</option>
                @endforeach
            @else
                <option disabled selected>{{ trans('inputs.backend.schedule.timetable.options.option') }}</option>
                @foreach($options as $option)
                    <option value="{{ $option->id }}">{{ $option->code }}</option>
                @endforeach
            @endif
        </select>
    @endif
@else
    {{--end of global timetable--}}
    @if(isset($department))
        <select name="department"
                data-toggle="tooltip"
                data-placement="top"
                title="Department">
            <option value="{{ $department->id }}">{{ $department->code }}</option>
        </select>
    @endif

    @if(isset($department))
        <select name="degree"
                data-toggle="tooltip"
                data-placement="top"
                title="{{ trans('inputs.backend.schedule.timetable.options.degree') }}">
            <option selected disabled>{{ trans('inputs.backend.schedule.timetable.options.degree') }}</option>
            @foreach($department->degrees as $index => $degree)
                @if($index==0)
                    <option value="{{ $degree->id }}" selected>{{ $degree->name_en }}</option>
                @else
                    <option value="{{ $degree->id }}">{{ $degree->name_en }}</option>
                @endif
            @endforeach
        </select>
    @endif

    @if(count($department->department_options)>0)
        <select name="option"
                data-toggle="tooltip"
                data-placement="top"
                title="{{ trans('inputs.backend.schedule.timetable.options.option') }}">
            @if(isset($options_))
                @foreach($options_ as $item)
                    <option value="{{ $item->id }}">{{ $item->code }}</option>
                @endforeach
            @else
                <option disabled selected>{{ trans('inputs.backend.schedule.timetable.options.option') }}</option>
                @foreach($department->department_options as $option)
                    <option value="{{ $option->id }}">{{ $option->code }}</option>
                @endforeach
            @endif
        </select>
    @endif
@endif

@if(isset($grades))
    <select name="grade"
            data-toggle="tooltip"
            data-placement="top"
            title="{{ trans('inputs.backend.schedule.timetable.options.grade') }}">
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
    <select name="semester"
            data-toggle="tooltip"
            data-placement="top"
            title="{{ trans('inputs.backend.schedule.timetable.options.semester') }}">
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

<select name="group"
        data-toggle="tooltip"
        data-placement="top"
        title="{{ trans('inputs.backend.schedule.timetable.options.group') }}">
    @if($group_id != 0)
        <option selected disabled>{{ trans('inputs.backend.schedule.timetable.options.group') }}</option>
    @endif
    @if(isset($groups))
        @foreach($groups as $group)
            <option value="{{ $group->id }}">{{ $group->name }}</option>
        @endforeach
    @endif
</select>

@if(isset($weeks))
    <select name="weekly"
            data-toggle="tooltip"
            data-placement="top"
            title="{{ trans('inputs.backend.schedule.timetable.options.weekly') }}">
        <option selected disabled>{{ trans('inputs.backend.schedule.timetable.options.weekly') }}</option>
        @if(isset($weeks_))
            @foreach($weeks_ as $item)
                <option value="{{ $item->id }}">{{ $item->name_en }}</option>
            @endforeach
        @else
            @foreach($weeks as $week)
                <option value="{{ $week->id }}">{{ $week->name_en }}</option>
            @endforeach
        @endif
    </select>
@endif