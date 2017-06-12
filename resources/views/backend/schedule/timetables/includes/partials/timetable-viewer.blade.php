<div class="box-body box-smis">
    <form name="options-filter"
          id="options-filter"
          method="POST"
          action="{{ route('admin.schedule.timetables.filter') }}">
        @include('backend.schedule.timetables.includes.partials.option')
    </form> <br/>

    @if(isset($createTimetablePermissionConfiguration))
        @if(!((strtotime($now) >= strtotime($createTimetablePermissionConfiguration->created_at) && strtotime($now) <= strtotime($createTimetablePermissionConfiguration->updated_at)) && access()->allow('create-timetable')))
            @if($createTimetablePermissionConfiguration->description === 'true')
                <div class="alert alert-info alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-info"></i> You can start create timetable.</h4>
                    <p>You can start at :
                        <span class="label label-primary">{{ (new \Carbon\Carbon($createTimetablePermissionConfiguration->created_at))->toDateString() }}</span>
                        To
                        <span class="label label-primary">{{ (new \Carbon\Carbon($createTimetablePermissionConfiguration->updated_at))->toDateString() }}</span> Status:
                    </p>
                </div>
            @elseif($createTimetablePermissionConfiguration->description === 'false')
                <div class="alert alert-info alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-info"></i> You're not allowed to create timetable yet.</h4>
                    <p>You can start from
                        <span class="label label-primary">{{ (new \Carbon\Carbon($createTimetablePermissionConfiguration->created_at))->toDateString() }}</span>
                        to
                        <span class="label label-primary">{{ (new \Carbon\Carbon($createTimetablePermissionConfiguration->updated_at))->toDateString() }}</span> Status:
                    </p>
                </div>
            @else
                <div class="alert alert-info alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-info"></i> You have no more time to create timetable.</h4>
                    <p>If you want to continue with your working, please contact to <a href="#">STUDY OFFICE</a>.</p>
                </div>
            @endif
        @endif
    @endif

    <table class="table table-striped table-bordered table-hover dt-responsive nowrap"
           id="timetables-tables">
        <thead>
        <tr>
            {{--<th>{{ trans('labels.backend.schedule.timetable.table.academic_year') }}</th>
            <th>{{ trans('labels.backend.schedule.timetable.table.department') }}</th>
            <th>{{ trans('labels.backend.schedule.timetable.table.degree') }}</th>
            <th>{{ trans('labels.backend.schedule.timetable.table.grade') }}</th>
            <th>{{ trans('labels.backend.schedule.timetable.table.option') }}</th>
            <th>{{ trans('labels.backend.schedule.timetable.table.semester') }}</th>--}}
            {{--<th>{{ trans('labels.backend.schedule.timetable.table.group') }}</th>--}}
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