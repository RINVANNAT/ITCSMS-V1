<div class="modal fade" id="choose-timetable-group" tabindex="-1" role="dialog" aria-labelledby="chooseTimetableGroup">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    Choose Timetable Group
                </h4>
            </div>
            <div class="modal-body">
                <div id="form-choose-timetable-group"
                      class="form-horizontal">
                    <div class="form-group">
                        <div class="col-md-10 col-md-offset-1">
                            <div class="col-md-12">
                                <label for="all-groups">
                                    <input type="checkbox" class="all-groups" value="all" name="all"> All Groups
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-10 col-md-offset-1">
                            <div class="col-md-2 timetable-group-course" v-for="(group) in groups">
                                <label>
                                    <input class="group"
                                           type="checkbox"
                                           data-target="groups"
                                           name="groups[]"
                                           :value="group.id">
                                    <span v-text="group.code"></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <hr/>
                    <div class="form-group">
                        <div class="col-md-offset-1 col-md-10">
                            <div class="col-md-12">
                                <input type="submit" class="btn btn-primary btn-sm btn-save-group-course"
                                       value="Save Group" @click="saveGroupCourse">
                                <button type="button" class="btn btn-default btn-sm btn_cancel_clone_timetable"
                                        data-dismiss="modal">{{ trans('buttons.backend.schedule.timetable.modal_clone.close') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>