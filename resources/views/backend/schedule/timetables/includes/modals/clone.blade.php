<div class="modal fade" id="clone-timetable" tabindex="-1" role="dialog" aria-labelledby="cloneTimetable">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Clone timetable</h4>
            </div>
            <div class="modal-body">
                <form action="" method="POST" class="form-horizontal">
                    @include('backend.schedule.timetables.includes.forms.clone')
                </form>
            </div>
        </div>
    </div>
</div>