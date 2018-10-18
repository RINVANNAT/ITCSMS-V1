<div class="row">
    <div class="col-md-12">
        <div class="box box-default" style="border: 1px solid #dddddd;">
            <div class="box-header with-border">
                <div class="input-group margin">
                    <input type="text"
                           class="form-control"
                           id="search_course_program"
                           placeholder="Search subject">
                    <span class="input-group-btn">
                        <button class="btn btn-primary btn_export_course_program"
                              data-toggle="modal"
                              data-target="#choose-timetable-group"
                              title="Synchronous course">
                            <i class="fa fa-refresh"></i>
                        </button>
                    </span>
                </div>
            </div>
            <div class="box-body courses-sessions">
                @if(access()->allow('drag-course-session'))
                <ul class="courses todo-list"></ul>
                @else
                    <div class="alert alert-danger {{--alert-dismissible--}}">
                        {{--<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>--}}
                        <h4><i class="icon fa fa-info"></i>{{ trans('strings.backend.timetable.block_drag_course_session') }}</h4>
                        <p>{{ trans('strings.backend.timetable.block_drag_course_session') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @include('backend.schedule.timetables.includes.modals.group-choosen')
</div>

