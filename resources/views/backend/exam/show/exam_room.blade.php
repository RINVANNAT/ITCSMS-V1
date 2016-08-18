
<div class="row" id="row-main">
    <div class="col-sm-12" id="main-window">
        <div class="box box-solid">
            <div class="box-header with-border">
                <i class="fa fa-building-o"></i>

                <h3 class="box-title">Buildings & Rooms <span class="label label-primary" style="margin-left: 10px;">Total Seat: <span id="all_reserve_seat">0</span></span></h3>
                <button class="btn btn-sm btn-default pull-right" id="btn-secret-code" style="margin-left: 5px;"><i class="fa fa-user-secret"></i> Anonymus Code</button>
                <button class="btn btn-sm btn-info pull-right" id="btn-add"><i class="fa fa-edit"></i> Modify</button>
                <button class="btn btn-sm btn-danger pull-right" id="btn-delete" style="display: none">Delete</button>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <blockquote>
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
                                    <input type="number" name="available_room" class="form-control" id="available_room" value="125" disabled>
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
                    <div id="selected_rooms">

                    </div>
                    <small>Selected rooms and buildings</small>
                </blockquote>
            </div>
            <!-- /.box-body -->
        </div>

    </div>
    <div class="col-sm-6 side-window" id="side-window-right" style="display: none">
        <div class="box box-solid">
            <div class="box-header with-border">
                <i class="fa fa-building-o"></i>

                <h3 class="box-title">Available Buildings & Rooms <span class="label label-primary" style="margin-left: 10px;">Total Seat: <span id="selected_available_seat">0</span> / <span id="all_available_seat">0</span></span></h3>

                <button class="btn btn-sm btn-danger pull-right" id="btn-save" style="margin-left: 10px;">Save</button>
                <button class="btn btn-sm btn-default pull-right" id="btn-cancel">Cancel</button>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <blockquote>
                    <div id="all_rooms">
                    </div>
                    <small>All available rooms and buildings</small>
                </blockquote>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
</div>

