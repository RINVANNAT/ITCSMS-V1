<div class="modal fade" id="modal-add-event">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header with-border">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4>Add a new event</h4>
            </div>
            <form class="form-horizontal" id="form-create-event" method="POST">
                <div class="modal-body">
                    @include('backend.schedule.calendars.includes.form-event')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-primary btn-sm" value="Save Change"/>
                </div>
            </form>
        </div>
    </div>
</div>