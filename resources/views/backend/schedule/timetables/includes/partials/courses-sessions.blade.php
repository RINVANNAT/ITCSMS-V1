<div class="row">
    <div class="col-md-12">
        <div class="box box-default" style="border-top: 1px solid #f1f1f1;">
            <div class="box-header with-border">
                <div class="box-tools pull-right">
                    <div class="row">
                        <div class="col-xs-9 col-xs-offset-3 col-sm-9 col-sm-offset-9 col-md-9 col-md-offset-3">
                            <div class="input-group">
                                <input type="text"
                                       class="form-control input-sm"
                                       id="search_course_session"
                                       placeholder="Search Course Session">
                                <span class="input-group-btn">
                                    <button class="btn btn-primary btn-sm btn_export_course_session"
                                            data-container="body"
                                            data-toggle="popover"
                                            data-placement="left"
                                            data-trigger="hover"
                                            title="Update Course Session"
                                            data-content="After you clicked, your course session will exported.">
                                        <i class="fa fa-refresh"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <h3 class="box-title">
                    <i class="fa fa-drivers-license-o" data-toggle="tooltip" data-placement="top" title="List all course sessions"></i>
                    {{--{{ trans('labels.backend.schedule.timetable.courses_sessions') }}--}}
                </h3>
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