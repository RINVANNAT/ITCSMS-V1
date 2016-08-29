<div class="modal fade" id="modal_exam_room_split">
    <div class="modal-dialog" style="width: 1000px">
        <div class="modal-content">
            <div class="modal-body">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ trans('labels.backend.exams.exam_room.title.split') }}</h3>
                    </div><!-- /.box-header -->

                    <div class="box-body">
                        <form class="form-horizontal" id="form_exam_room_split">
                            {!! Form::hidden('split_room',null, ['id'=>'split_room']) !!}
                            <div class="col-md-12 col-xs-12 no-padding" id="room_split_wrapper">
                                <div class="col-md-12 col-xs-12">
                                    <div class=" form-group col-md-6">
                                        <div class="col-md-6">
                                            <label for="name" class="control-label">Name</label>
                                            <input type="text" name="name[]" class="form-control room_split_name">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="nb_chair_exam" class="control-label">Capacity</label>
                                            <input type="number" name="nb_chair_exam[]" class="form-control room_split_capacity">
                                        </div>

                                    </div>
                                    <div class=" form-group col-md-6">
                                        <div class="col-md-6">
                                            <label for="building" class="control-label">Building</label>
                                            {!! Form::select('building_id[]',$buildings, null, ['class' => 'form-control room_split_building']) !!}
                                        </div>
                                        <div class="col-md-6">
                                            <label for="description" class="control-label">Description</label>
                                            <input type="text" name="description[]" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xs-12">
                                    <div class=" form-group col-md-6">
                                        <div class="col-md-6">
                                            <input type="text" name="name[]" class="form-control room_split_name">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="number" name="nb_chair_exam[]" class="form-control room_split_capacity">
                                        </div>

                                    </div>
                                    <div class=" form-group col-md-6">
                                        <div class="col-md-6">
                                            {!! Form::select('building_id[]',$buildings, null, ['class' => 'form-control room_split_building']) !!}
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" name="description[]" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button id="btn_add_more_split" type="button" class="btn btn-xs btn-info" style="margin-left: 15px">
                                Add More
                            </button>
                        </form>
                    </div><!-- /.box-body -->
                </div><!--box-->

                <div class="box box-success" style="margin-bottom: 0px !important;">
                    <div class="box-body">
                        <div class="pull-left">
                            <input type="button" class="btn btn-default btn-xs" data-dismiss="modal" value="{{ trans('buttons.general.close') }}" />
                        </div>

                        <div class="pull-right">
                            <input type="button" id="btn_split_save" class="btn btn-danger btn-xs" value="{{ trans('buttons.general.save') }}" />
                        </div>
                        <div class="clearfix"></div>
                    </div><!-- /.box-body -->
                </div><!--box-->
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->