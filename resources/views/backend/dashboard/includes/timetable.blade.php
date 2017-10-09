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
                            <select name="timetable_id" id="teacher_timetable" class="form-control"
                                    style="width: 300px;">
                                @foreach($timetables as $timetable)
                                    <option value="{{ $timetable->id }}">

                                        @if(!is_null($timetable->group) && !is_null($timetable->option))
                                            {{
                                               $timetable->academicYear->name_latin. '::'
                                               .$timetable->department->code
                                               .$timetable->option->code. '('
                                               .$timetable->degree->code
                                               .$timetable->grade->code. '-'
                                               .$timetable->group->code. ')::'
                                               .$timetable->week->name_en
                                            }}
                                        @elseif(is_null($timetable->option) && !is_null($timetable->group))
                                            {{
                                               $timetable->academicYear->name_latin. '::'
                                               .$timetable->department->code. '('
                                               .$timetable->degree->code
                                               .$timetable->grade->code. '-'
                                               .$timetable->group->code. ')::'
                                               .$timetable->week->name_en
                                            }}
                                        @elseif(!is_null($timetable->option) && is_null($timetable->group))
                                            {{
                                               $timetable->academicYear->name_latin. '::'
                                               .$timetable->department->code
                                               .$timetable->option->code. '('
                                               .$timetable->degree->code
                                               .$timetable->grade->code. ')::'
                                               .$timetable->week->name_en
                                            }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>

                        </div>

                        @if(access()->allow('teacher-edit-timetable'))
                            <div class="pull-right"
                                 data-toggle="tooltip"
                                 data-placement="top"
                                 title="Mode">
                                <input type="checkbox"
                                       name="filter_by"
                                       checked data-toggle="toggle"
                                       class="form-control"
                                       data-on="View"
                                       data-off="Edit"
                                       data-width="100"
                                       data-height="25"
                                       data-onstyle="primary"
                                       data-offstyle="warning"
                                       data-style="ios">
                            </div>
                        @endif
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