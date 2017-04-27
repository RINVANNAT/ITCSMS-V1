<div class="row">
    <div class="col-md-12">
        <div class="box box-solid">
            <div class="box-header with-border">
                <i class="fa fa-drivers-license-o"></i>
                <h3 class="box-title">{{ trans('labels.backend.schedule.timetable.courses_sessions') }}</h3>
            </div>
            <div class="box-body courses-sessions">
                <ul class="courses todo-list">
                    @for($i=0; $i<10; $i++)

                        <li class="course-item">
                                                <span class="handle ui-sortable-handle">
                                                    <i class="fa fa-ellipsis-v"></i>
                                                    <i class="fa fa-ellipsis-v"></i>
                                                </span>
                            <span class="text course-name">Cloud Computing</span><br>
                            <span style="margin-left: 28px;" class="teacher-name">Mr. YOU Vanndy</span><br/>
                            <span style="margin-left: 28px;" class="course-type">Course</span> :
                            <span class="times">8</span> H
                        </li>

                    @endfor
                </ul>
            </div>
        </div>
    </div>
</div>