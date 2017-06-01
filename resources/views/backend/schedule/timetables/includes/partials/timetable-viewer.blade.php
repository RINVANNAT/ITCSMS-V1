<div class="box box-success">
    <div class="box-header with-border">
        <h3 class="box-title">Timetable Management</h3>
        <div class="box-tools pull-right">
            @permission('create-timetable')
            <div class="pull-left">
                <a class="btn btn-primary btn-sm"
                   data-toggle="tooltip"
                   data-placement="top" title="Create a new timetable"
                   data-original-title="Create a new timetable"
                   href="{{ route('admin.schedule.timetables.create') }}">
                    <i class="fa fa-plus-circle"></i>
                    {{ trans('buttons.backend.schedule.timetable.create') }}
                </a>
            </div>
            @endauth
        </div>
    </div>

    <div class="box-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover dt-responsive nowrap"
                   id="timetables-table">
                <thead>
                <tr>
                    <th>{{ trans('labels.backend.schedule.timetable.table.academic_year') }}</th>
                    <th>{{ trans('labels.backend.schedule.timetable.table.department') }}</th>
                    <th>{{ trans('labels.backend.schedule.timetable.table.degree') }}</th>
                    <th>{{ trans('labels.backend.schedule.timetable.table.grade') }}</th>
                    <th>{{ trans('labels.backend.schedule.timetable.table.option') }}</th>
                    <th>{{ trans('labels.backend.schedule.timetable.table.semester') }}</th>
                    <th>{{ trans('labels.backend.schedule.timetable.table.group') }}</th>
                    <th>{{ trans('labels.backend.schedule.timetable.table.week') }}</th>
                    <th>{{ trans('labels.backend.schedule.timetable.table.status') }}</th>
                    @if(access()->allow('view-timetable') || access()->allow('delete-timetable'))
                        <th>{{ trans('labels.general.actions') }}</th>
                    @endif
                </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                </tfoot>
            </table>
        </div>
        <div class="clearfix"></div>
    </div>
</div>