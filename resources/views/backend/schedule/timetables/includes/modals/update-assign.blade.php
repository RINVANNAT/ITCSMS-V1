<div class="modal fade" id="modal-update-assign" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">{{ trans('modals.backend.timetable.update_assignment_permission.modal_title') }}</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="update-form-assign">

                    <div class="form-group">
                        <label class="control-label col-sm-3">{{ trans('modals.backend.timetable.update_assignment_permission.modal_body.form.datetime.label') }}:</label>
                        <div class="col-md-9">
                            <div class='input-group date' id='update-datetime'>
                                <input type='text' class="form-control" placeholder="{{ trans('modals.backend.timetable.update_assignment_permission.modal_body.form.datetime.placeholder') }}" name="update-datetime" id="datetime"/>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-9 col-md-offset-3">
                            <button class="btn btn-info" id="btn_form_update_assign">{{ trans('modals.backend.timetable.update_assignment_permission.modal_body.form.submit.update') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>