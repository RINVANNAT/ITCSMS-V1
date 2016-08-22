<div class="modal fade" id="modal_exam_room_merge">
    <div class="modal-dialog" style="width: 1000px">
        <div class="modal-content">

            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12">
                        <form class="form-horizontal" id="form_exam_room_merge">
                            <div class="form-group">
                                <label for="name" class="col-sm-3 control-label">Name</label>

                                <div class="col-sm-5">
                                    <input type="text" name="name" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="nb_chair_exam" class="col-sm-3 control-label">Capacity</label>

                                <div class="col-sm-5">
                                    <input type="number" name="nb_chair_exam" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="building" class="col-sm-3 control-label">Building</label>

                                <div class="col-sm-5">
                                    <input type="text" name="building" class="form-control">
                                </div>
                            </div>
                        </form>

                    </div>


                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">{{trans('buttons.general.close')}}</button>
                <button id="submit_payment" type="button" class="btn btn-primary">{{trans('buttons.general.save')}}</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->