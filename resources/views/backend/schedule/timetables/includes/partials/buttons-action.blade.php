@permission('generate-timetable')
<a href="#">
    <button class="btn btn-primary btn-sm"
            data-placement="right"
            title="Tooltip on top"
            disabled="true">
        {{ trans('buttons.backend.schedule.timetable.generate') }}
    </button>
</a>
@endauth

@permission('clone-timetable')
<button class="btn btn-success btn-sm"
        data-toggle="modal"
        data-target="#clone-timetable"
        data-toggle="tooltip"
        data-placement="top"
        title="{{ trans('buttons.backend.schedule.timetable.clone') }}">
    {{ trans('buttons.backend.schedule.timetable.clone') }}
</button>
@endauth

@permission('publish-timetable')
<a href="#">
    <button class="btn btn-info btn-sm"
            data-toggle="tooltip"
            data-placement="top"
            title="{{ trans('buttons.backend.schedule.timetable.publish') }}">
        {{ trans('buttons.backend.schedule.timetable.publish') }}
    </button>
</a>
@endauth

@permission('save-change-timetable')
<a href="#">
    <button class="btn btn-danger btn-sm"
            data-toggle="tooltip"
            data-placement="top"
            title="{{ trans('buttons.backend.schedule.timetable.save_change') }}">
        {{ trans('buttons.backend.schedule.timetable.save_change') }}
    </button>
</a>
@endauth