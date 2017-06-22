<div class="box-body box-smis">
    <form name="options-filter"
          id="options-filter"
          method="POST"
          action="{{ route('admin.schedule.timetables.filter') }}">
        @include('backend.schedule.timetables.includes.partials.option-index')
    </form>
    <br/>
    @if(isset($createTimetablePermissionConfiguration))
        @if(access()->allow('create-timetable'))
            @if($createTimetablePermissionConfiguration->description === 'true')
                <div class="smis-notification success" style="display: block;">
                    <div class="smis-notification-heading">
                        <span class="smis-close-icon">&times;</span>
                        <h3 class="smis-notification-title">
                            Progress{{--{{ trans('strings.backend.timetable.in_progress') }}--}}
                            ({{ (new \Carbon\Carbon($createTimetablePermissionConfiguration->created_at))->toFormattedDateString() }}
                            <i class="fa fa-arrow-right"></i>
                            {{ (new \Carbon\Carbon($createTimetablePermissionConfiguration->updated_at))->toFormattedDateString() }})
                        </h3>
                    </div>
                    <div class="smis-notification-body">
                        <p>{{ trans('strings.backend.timetable.message_progress') }}.</p>
                    </div>
                </div>
            @elseif($createTimetablePermissionConfiguration->description === 'false')
                <div class="smis-notification danger">
                    <div class="smis-notification-heading">
                        <span class="smis-close-icon">&times;</span>
                        <h3 class="smis-notification-title">
                            Waiting{{--{{ trans('strings.backend.timetable.waiting') }}--}}
                            ({{ (new \Carbon\Carbon($createTimetablePermissionConfiguration->created_at))->toFormattedDateString() }}
                            <i class="fa fa-arrow-right"></i>
                            {{ (new \Carbon\Carbon($createTimetablePermissionConfiguration->updated_at))->toFormattedDateString() }})
                        </h3>
                    </div>
                    <div class="smis-notification-body">
                        <p>{{ trans('strings.backend.timetable.message_waiting') }}.</p>
                    </div>
                </div>
            @else
                <div class="smis-notification info">
                    <div class="smis-notification-heading">
                        <span class="smis-close-icon">&times;</span>
                        <h3 class="smis-notification-title">Finished{{--{{ trans('strings.backend.timetable.finished') }}--}}</h3>
                    </div>
                    <div class="smis-notification-body">
                        <p>{{ trans('strings.backend.timetable.message_finished') }}<a href="#">STUDY OFFICE</a>.</p>
                    </div>
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