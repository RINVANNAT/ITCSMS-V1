<div class="box-body box-smis">
    <form name="options-filter"
          id="options-filter"
          method="POST"
          action="{{ route('admin.schedule.timetables.filter') }}">
        @include('backend.schedule.timetables.includes.partials.option-index')
    </form>
    <br/>

    @if(isset($createTimetablePermissionConfiguration))
        @if(!((strtotime($now) >= strtotime($createTimetablePermissionConfiguration->created_at) && strtotime($now) <= strtotime($createTimetablePermissionConfiguration->updated_at)) && access()->allow('create-timetable')))
            @if($createTimetablePermissionConfiguration->description === 'true')
                <div class="alert alert-info alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-info"></i> {{ trans('strings.backend.timetable.in_progress') }}</h4>
                    <p>{{ trans('strings.backend.timetable.start_at') }} :
                        <span class="label label-primary">{{ (new \Carbon\Carbon($createTimetablePermissionConfiguration->created_at))->toDateString() }}</span>
                        {{ trans('strings.backend.timetable.to') }}
                        <span class="label label-primary">{{ (new \Carbon\Carbon($createTimetablePermissionConfiguration->updated_at))->toDateString() }}</span>
                        {{ trans('strings.backend.timetable.status') }} :
                    </p>
                </div>
            @elseif($createTimetablePermissionConfiguration->description === 'false')
                <div class="alert alert-info alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-info"></i> {{ trans('strings.backend.timetable.waiting') }}</h4>
                    <p>{{ trans('strings.backend.timetable.start_from') }} :
                        <span class="label label-primary">{{ (new \Carbon\Carbon($createTimetablePermissionConfiguration->created_at))->toDateString() }}</span>
                        {{ trans('strings.backend.timetable.to') }}
                        <span class="label label-primary">{{ (new \Carbon\Carbon($createTimetablePermissionConfiguration->updated_at))->toDateString() }}</span>
                        {{ trans('strings.backend.timetable.status') }} :
                    </p>
                </div>
            @else
                <div class="alert alert-info alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-info"></i> {{ trans('strings.backend.timetable.finished') }}</h4>
                    <p>{{ trans('strings.backend.timetable.message_finished') }}<a href="#">STUDY OFFICE</a>.</p>
                </div>
            @endif
        @endif
    @endif

    <table class="table table-striped table-bordered table-hover dt-responsive nowrap"
           id="timetables-table">
        <thead>
        <tr>
            <th>{{ trans('labels.backend.schedule.timetable.table.week') }}</th>
            <th>{{ trans('labels.backend.schedule.timetable.table.status') }}</th>
            @if(access()->allow('view-timetable') || access()->allow('delete-timetable'))
                <th>{{ trans('labels.general.actions') }}</th>
            @endif
        </tr>
        </thead>
    </table>
    <div class="clearfix"></div>
</div>