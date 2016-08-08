
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
