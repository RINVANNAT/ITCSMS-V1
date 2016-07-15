
<div class="row" id="row-main">
    <div class="col-sm-12" id="main_window_staff_role">
        <div class="box box-solid">
            <div class="box-header with-border">
                <i class="fa fa-users" aria-hidden="true"></i>

                <h3 class="box-title">Staffs & Roles</h3>
                <button class="btn btn-sm btn-info pull-right" id="btn_add_role">Add Group Role</button>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <blockquote>
                    <div id="selected_staffs">
                    </div>
                    <small>Selected staffs and roles</small>

                </blockquote>
            </div>
            <!-- /.box-body -->
        </div>

    </div>
    <div class="col-sm-6 side-window" id="side_window_right_staff_role" style="display: none">
        <div class="box box-solid">
            <div class="box-header with-border">
                <i class="fa fa-users" aria-hidden="true"></i>

                <h3 class="box-title"> Asign Role for Each Staff </h3><br>
                {{--<strong>Please Select Role Before Saving </strong>--}}
                <button class="btn btn-sm btn-danger pull-right" id="btn_save_staff_role" style="margin-left: 10px;">Save</button>
                <button class="btn btn-sm btn-default pull-right" id="btn_cancel_staff_role">Cancel</button>
            </div>
            <!-- /.box-header -->
            <div  style="float:left; margin-left: 35px; margin-bottom:10px;width: 50%;">

                <select class="form-control" name="role" id="role" selected>
                    @foreach($roles as $role)
                        <option value="{{$role->id}}" > {{$role->name}} </option>
                    @endforeach
                </select>

            </div>
            <di style="width: 60%;">
                <button class="btn btn-sm btn-primary" style="float:left; margin-left: 20px;" id="btn_add_new_role">Add Role</button>
                <div class="popUpRole addRolePopUp">
                        <div class="form-group">
                            New Role: <input type="text" class="form-control" id="new_role" placeholder="Please Write Here">
                            Description: <input type="text" class="form-control" id="new_des" placeholder="Please Write Here">
                        </div>
                        <button class="btn btn-sm btn-primary" id="submit_new_role"> Submit </button>
                </div>
            </di>



            <div class="box-body">
                <blockquote>
                    <div id="all_staff_role">
                    </div>
                    <small>Staff and Roles</small>
                </blockquote>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
</div>
