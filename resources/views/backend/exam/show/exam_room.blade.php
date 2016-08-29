@include('backend.exam.includes.exam_room_merge')
@include('backend.exam.includes.exam_room_split')
@include('backend.exam.includes.exam_room_add')
<div class="row" id="row-main">
    <div class="col-sm-12">
        <div class="box box-solid">
            <div class="box-header with-border">
                <i class="fa fa-building-o"></i>

                <h3 class="box-title">Buildings & Rooms <span class="label label-primary" style="margin-left: 10px;">Total Seat: <span id="all_reserve_seat">0</span></span></h3>
                @permission('generate-exam-room-secret-code')
                <button class="btn btn-sm btn-default pull-right" id="btn-secret-code" style="margin-left: 5px;"><i class="fa fa-user-secret"></i> Anonymus Code</button>
                @endauth
                @permission('modify-exam-room')
                <button class="btn btn-sm btn-info pull-right" id="btn_room_modify" style="margin-left: 5px;"><i class="fa fa-edit"></i> Modify</button>
                <button class="btn btn-sm btn-danger pull-right room_editing" id="btn_room_cancel" style="margin-left: 5px; display: none">Cancel</button>
                <div class="btn-group pull-right room_editing" style="display: none;">
                    <button type="button" id="btn_room_add" class="btn btn-sm btn-warning" style="color: #fff; border-color: #3c8dbc;"><i class="fa fa-plus-square-o"></i> Add</button>
                    <button type="button" id="btn_room_merge" class="btn btn-sm btn-warning" style="color: #fff; border-color: #3c8dbc;" disabled><i class="fa fa-long-arrow-right"></i><i class="fa fa-long-arrow-left"></i> Merge</button>
                    <button type="button" id="btn_room_delete" class="btn btn-sm btn-warning" style="color: #fff; border-color: #3c8dbc;" disabled><i class="glyphicon glyphicon-trash"></i> Delete</button>
                </div>
                @endauth
            </div>

            <!-- /.box-header -->
            <div class="box-body">
                <blockquote>
                    <div id="selected_rooms">
                    @if(count($exam_rooms) == 0)
                        <div id="empty_room_notification" class="well">
                            There is no any room selected.

                            <button class="btn btn-sm btn-info pull-right" id="generate_room_exam">
                                Generate Rooms
                            </button>
                        </div>
                        <div id="form_generate_room_wrapper" class="well" style="display: none">

                            <form class="form-horizontal" id="form_generate_exam_room">
                                <div class="form-group">
                                    <label for="available_room" class="col-sm-2 control-label">Total Available Room</label>

                                    <div class="col-sm-2">
                                        <input type="number" name="available_room" class="form-control" id="available_room" value="{{$usable_room_exam}}" disabled>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="exam_chair" class="col-sm-2 control-label">Number of seat</label>

                                    <div class="col-sm-5">
                                        <input type="number" name="exam_chair" class="form-control" id="exam_chair">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <span class="text-muted">Estimation: <span id="exam_seat_estimation">0</span></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <i class="glyphicon glyphicon-info-sign text-danger"></i> Total Available Room can be changed from room management.
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <i class="glyphicon glyphicon-info-sign text-danger"></i> Number of seat is the maximum that each room can take. You may modify it later.
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <button type="button" id="submit_exam_room" class="btn btn-danger">Generate</button>

                                    </div>
                                </div>
                            </form>

                        </div>
                    @else
                        @include('backend.exam.includes.exam_room_list')
                    @endif
                    </div>
                    <small>Selected rooms and buildings</small>
                </blockquote>
            </div>
            <!-- /.box-body -->
        </div>

    </div>
</div>

