<div class="modal fade" id="modal_exam_room_add">
    <div class="modal-dialog" style="width: 1000px">
        <div class="modal-content">
            <div class="modal-body">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ trans('labels.backend.exams.exam_room.title.add') }}</h3>
                    </div><!-- /.box-header -->

                    <div class="box-body">
                        <div class="col-xs-12">
                            <form class="form-horizontal" id="form_exam_room_add">
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
                                        {!! Form::select('building_id',$buildings, null, ['class' => 'form-control']) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="description" class="col-sm-3 control-label">Description</label>

                                    <div class="col-sm-5">
                                        <input type="text" name="description" class="form-control">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div><!-- /.box-body -->
                </div><!--box-->

                <div class="box box-success" style="margin-bottom: 0px !important;">
                    <div class="box-body">
                        <div class="pull-left">
                            <input type="button" class="btn btn-default btn-xs" data-dismiss="modal" value="{{ trans('buttons.general.close') }}" />
                        </div>

                        <div class="pull-right">
                            <input type="button" id="btn_add_save" class="btn btn-danger btn-xs" value="{{ trans('buttons.general.save') }}" />
                        </div>
                        <div class="clearfix"></div>
                    </div><!-- /.box-body -->
                </div><!--box-->
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->