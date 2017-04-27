<div class="box-header with-border">
    <div class="mailbox-controls">
        <div class="pull-right">
            <a href="#">
                <button class="btn btn-primary btn-sm"
                        data-placement="right"
                        title="Tooltip on top"
                        disabled="true">
                    {{ trans('buttons.backend.schedule.timetable.generate') }}
                </button>
            </a>

            <button class="btn btn-warning btn-sm"
                    data-toggle="modal"
                    data-target="#clone-timetable"
                    data-toggle="tooltip"
                    data-placement="top"
                    title="{{ trans('buttons.backend.schedule.timetable.clone') }}">
                {{ trans('buttons.backend.schedule.timetable.clone') }}
            </button>

            <a href="#">
                <button class="btn btn-info btn-sm"
                        data-toggle="tooltip"
                        data-placement="top"
                        title="{{ trans('buttons.backend.schedule.timetable.publish') }}">
                    {{ trans('buttons.backend.schedule.timetable.publish') }}
                </button>
            </a>
            <a href="#">
                <button class="btn btn-danger btn-sm"
                        data-toggle="tooltip"
                        data-placement="top"
                        title="{{ trans('buttons.backend.schedule.timetable.save_change') }}">
                    {{ trans('buttons.backend.schedule.timetable.save_change') }}
                </button>
            </a>
        </div>

        <form name="filter-courses-sessions"
              id="filter-courses-sessions"
              method="POST"
              action="{{ route('admin.schedule.timetables.filter') }}">
            @include('backend.schedule.timetables.includes.partials.option')
        </form>

    </div>
</div>