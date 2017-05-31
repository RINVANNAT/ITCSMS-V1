<div class="modal fade" id="modal-timetable-assignment" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Timetable Assignment</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="form-assign">
                    <div class="form-group">
                        <label class="control-label col-sm-3">Department:</label>
                        <div class="col-md-9">
                            <select name="departments[]" multiple="multiple" style="width: 100%;">
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->code }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-3">Start Date:</label>
                        <div class="col-md-9">
                            <div class='input-group date' id='start'>
                                <input type='text' class="form-control" placeholder="Start Date" name="start"/>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-3">End Date:</label>
                        <div class="col-md-9">
                            <div class='input-group date' id='end'>
                                <input type='text' class="form-control" placeholder="End Date" name="end"/>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-9 col-md-offset-3">
                            <button class="btn btn-primary" id="btn_assign">Assign</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>