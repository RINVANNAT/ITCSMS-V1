<div class="modal fade" id="reset-timetable" tabindex="-1" role="dialog" aria-labelledby="resetTimetable">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    {{ trans('labels.backend.schedule.timetable.modal_reset.title') }}
                </h4>
            </div>
            <div class="modal-body">
                <div class="form-horizontal">
                    @include('backend.schedule.timetables.includes.forms.reset')
                </div>
            </div>
        </div>
    </div>
</div>