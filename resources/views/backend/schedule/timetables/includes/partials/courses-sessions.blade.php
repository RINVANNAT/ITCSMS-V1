<div class="row">
    <div class="col-md-12">
        <div class="box box-default" style="border-top: 1px solid #f1f1f1;">
            <div class="box-header with-border">
                <div class="box-tools pull-right">
                    <button class="btn btn-primary btn-xs btn_export_course_session"
                            data-toggle="tooltip"
                            data-placement="top"
                            title="Refresh">
                        <i class="fa fa-refresh {{--fa-pulse--}} fa-1x"></i>
                    </button>
                    {{--<button class="btn btn-info btn-xs btn_refresh_course_session"
                            data-toggle="tooltip"
                            data-placement="top"
                            title="Refresh">
                        <i class="fa fa-refresh"></i>
                    </button>--}}
                </div>
                <h3 class="box-title"><i
                            class="fa fa-drivers-license-o"></i> {{ trans('labels.backend.schedule.timetable.courses_sessions') }}
                </h3>
            </div>
            <div class="box-body courses-sessions">
                <ul class="courses todo-list"></ul>
            </div>
        </div>
    </div>
</div>