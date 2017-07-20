<li>
    <i class="fa fa-calendar-check-o bg-green"></i>

    <div class="timeline-item">

        <div class="timeline-header">
            <a href="#">Timetable Management</a>
        </div>

        <div class="timeline-body">
            <div class="panel panel-default">
                <div class="panel-heading">

                    <form action="/admin/dashboard/get_teacher_timetable" method="POST" id="form_teacher_timetable">
                        <div class="pull-left">

                            @if(isset($academicYears))
                                <select name="academicYears"
                                        data-toggle="tooltip"
                                        data-placement="top"
                                        title="Academic Year">
                                    @foreach($academicYears as $index => $academicYear)
                                        @if($index==0)
                                            <option value="{{ $academicYear->id }}"
                                                    selected>{{ $academicYear->name_latin }}</option>
                                        @else
                                            <option value="{{ $academicYear->id }}">{{ $academicYear->name_latin }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            @endif

                            @if(isset($departments))
                                <select name="departments"
                                        data-toggle="tooltip"
                                        data-placement="top"
                                        title="Department">
                                    @foreach($departments as $index => $department)
                                        @if($index==0)
                                            <option value="{{ $department->id }}"
                                                    selected>{{ $department->code }}</option>
                                        @else
                                            <option value="{{ $department->id }}">{{ $department->code }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            @endif

                            @if(isset($degrees))
                                <select name="degrees"
                                        data-toggle="tooltip"
                                        data-placement="top"
                                        title="Degree">
                                    @foreach($degrees as $index => $degree)
                                        @if($index==0)
                                            <option value="{{ $degree->id }}" selected>{{ $degree->name_en }}</option>
                                        @else
                                            <option value="{{ $degree->id }}">{{ $degree->name_en }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            @endif

                            @if(isset($grades))
                                <select name="grades"
                                        data-toggle="tooltip"
                                        data-placement="top"
                                        title="Grade">
                                    @foreach($grades as $index => $grade)
                                        @if($index==0)
                                            <option value="{{ $grade->id }}" selected>{{ $grade->name_en }}</option>
                                        @else
                                            <option value="{{ $grade->id }}">{{ $grade->name_en }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            @endif

                            @if(isset($options))
                                <select name="options"
                                        data-toggle="tooltip"
                                        data-placement="top"
                                        title="Option">
                                    @foreach($options as $index => $option)
                                        @if($index==0)
                                            <option value="{{ $option->id }}" selected>{{ $option->name_en }}</option>
                                        @else
                                            <option value="{{ $option->id }}">{{ $option->name_en }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            @endif

                            @if(isset($semesters))
                                <select name="semesters"
                                        data-toggle="tooltip"
                                        data-placement="top"
                                        title="Semester">
                                    @foreach($semesters as $index => $semester)
                                        @if($index==0)
                                            <option value="{{ $semester->id }}"
                                                    selected>{{ $semester->name_en }}</option>
                                        @else
                                            <option value="{{ $semester->id }}">{{ $semester->name_en }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            @endif

                            @if(isset($weeks))
                                <select name="weeks"
                                        data-toggle="tooltip"
                                        data-placement="top"
                                        title="Week">
                                    @foreach($weeks as $index => $week)
                                        @if($index==0)
                                            <option value="{{ $week->id }}" selected>{{ $week->name_en }}</option>
                                        @else
                                            <option value="{{ $week->id }}">{{ $week->name_en }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            @endif

                            @if(isset($groups))
                                <select name="groups"
                                        data-toggle="tooltip"
                                        data-placement="top"
                                        title="Group">
                                    @foreach($groups as $index => $group)
                                        @if($index==0)
                                            <option value="{{ $group->id }}" selected>{{ $group->code }}</option>
                                        @else
                                            <option value="{{ $group->id }}">{{ $group->code }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            @endif

                        </div>

                        <div class="pull-right"
                             data-toggle="tooltip"
                             data-placement="top"
                             title="Filter">
                            <input type="checkbox"
                                   name="filter_by"
                                   checked data-toggle="toggle"
                                   class="form-control"
                                   data-on="Only mine"
                                   data-off="All"
                                   data-width="100"
                                   data-height="25"
                                   data-style="ios">
                        </div>
                    </form>

                    <div class="clearfix"></div>

                </div>
                <div class="panel-body">

                    <div id="timetable_for_teacher" class="view-timetable"></div>
                </div>
            </div>
        </div>
    </div>
</li>