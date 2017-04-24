<div class="modal fade" id="clone-timetable" tabindex="-1" role="dialog" aria-labelledby="cloneTimetable">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    {{ trans('labels.backend.schedule.timetable.modal_clone.title') }}
                </h4>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.schedule.timetables.clone') }}"
                      method="POST"
                      name="form-clone-timetable"
                      id="form-clone-timetable"
                      class="form-horizontal">
                    @include('backend.schedule.timetables.includes.forms.clone')
                </form>
            </div>
        </div>
    </div>
</div>