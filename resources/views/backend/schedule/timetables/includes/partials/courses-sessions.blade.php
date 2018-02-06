<div class="row">
    <div class="col-md-12">
        <div class="box box-default" style="border: 1px solid #dddddd;">
            <div class="box-header with-border">

                <h3 class="box-title" data-toggle="tooltip" data-placement="top" data-title="Course Session">
                    <i class="fa fa-drivers-license-o"></i>
                    {{--{{ trans('labels.backend.schedule.timetable.courses_sessions') }}--}}
                </h3>

                <div class="box-tools pull-right">
                    <form class="form-inline">
                        <input type="text"
                               class="form-control input-sm"
                               id="search_course_program"
                               placeholder="SEARCH COURSE SESSION">
                        <button class="btn btn-primary btn-sm btn_export_course_program"
                                data-container="body"
                                data-toggle="popover"
                                data-placement="bottom"
                                data-trigger="hover"
                                title="Update Course Session"
                                data-content="After you clicked, your course session will exported.">
                            <i class="fa fa-refresh"></i>
                        </button>
                    </form>
                    <div class="clearfix"></div>
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
</div>