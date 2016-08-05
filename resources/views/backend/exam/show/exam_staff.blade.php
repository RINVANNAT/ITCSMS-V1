
<div class="row" id="row-main">
    <div class="col-sm-12" id="main_window_staff_role">
        <div class="box box-solid">
            <div class="box-header with-border">
                <i class="fa fa-users" aria-hidden="true"></i>

                <h3 class="box-title">Staffs & Roles</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <blockquote>
                    <div id="selected_staffs">
                    </div>

                    <div id="alert_add_role_staff" class="alert-danger col-md-12">
                        <h5>Please Select Role and Check on Staff Before Saving!! </h5>
                    </div>

                    <small>Selected staffs and roles</small>

                    <div id="check_ok" class="col-md-12  no-padding alert-info" style="margin-bottom: 5px">
                        <div class="col-md-9">
                            <strong>Do you really want to delete the record ??</strong>
                        </div>
                        <div class="col-md-1 no-padding">
                            <a id="ok_delete"  class="text-info" style="margin-right: 15px" href="#"> OK</a>
                        </div>
                        <div class="col-md-2 no-padding">
                            <a id="cancel_delete" class="text-info" style="margin-left: 20px;" href="#"> Cancel</a>
                        </div>
                    </div>

                    <div id="alert_delete_role_staff" class="alert-danger col-md-12" style="margin-bottom: 5px">
                        <h5>Please Select Role and Check on Staff Before deleting!! </h5>
                    </div>

                    <div id="alert_delete_role_staff_success" class="alert-info col-md-12" style="margin-bottom: 5px">
                        <h5>Your Selected Record Have Deleted!</h5>
                    </div>

                    <div class="col-md-12 no-padding">
                        <div class="col-md-12 no-padding" style="margin-bottom: 5px;">
                            @permission('modify-exam-staff')
                            <button class="btn btn-sm btn-info pull-right" id="btn_add_role">Modify Group Role</button>
                            <button class="btn btn-sm btn-danger pull-right" style="float: right;" id="btn_delete_node">Delete Role</button>
                            <button class="btn btn-sm btn-default pull-right" style="float: right; margin-right: 5px;" id="btn_move_node" onclick="clickAddRole( $('.popUpRoleDown'));">Change Role</button>
                            @endauth
                        </div>
                        <div class=" popUpRoleDown col-md-12 no-padding " style="display:none;">
                            <div class="col-md-10 " style="padding-left: 0px !important">
                                <select class="form-control" name="role" id="role_change" selected>
                                    @foreach($roles as $role)
                                        <option value="{{$role->id}}" > {{$role->name}} </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-1 no-padding">
                                <button class="btn btn-sm btn-danger pull-right" id="btn_save_chang_role" style="margin-top: 2px; margin-right: 12px;">Save</button>
                            </div>
                            <div class="col-md-1 no-padding">
                                <button class="btn btn-sm btn-default pull-right" id="btn_cancel_chang_role" style="margin-top: 2px;  float: right;">Cancel</button>
                            </div>
                        </div>
                    </div>
                </blockquote>

            </div>
            <!-- /.box-body -->
        </div>

    </div>
    <div class="col-sm-6 side-window" id="side_window_right_staff_role" style="display: none">
        <div class="box box-solid">
            <div class="box-header with-border">
                <i class="fa fa-users" aria-hidden="true"></i>

                <h3 class="box-title"> Asign Role for Each Staff </h3>
                {{--<strong>Please Select Role Before Saving </strong>--}}

            </div>
            <!-- /.box-header -->

            <div class="box-body">
                <blockquote>
                    <div class="col-md-12 no-padding" style="margin-bottom: 10px;">
                        <div class="col-md-11 no-padding">
                            <select class="form-control" name="role" id="role" selected>
                                @foreach($roles as $role)
                                    <option value="{{$role->id}}" > {{$role->name}} </option>
                                @endforeach
                            </select>

                        </div>
                        <div class="col-md-1 no-padding">
                            <button class="btn btn-sm pull-right fa fa-plus" style="margin-top: 2px; margin-right: 0px;" id="btn_add_new_role"></button>
                        {{--<span class="fa fa-plus" style="margin-top: 10px; border: 1px solid black; width: 30px; height: 30px;"></span>--}}
                        </div>

                        <div id="block_slide_down" class="col-md-12 no-padding text-muted well-sm no-shadow">

                            <div class="popUpRole addRolePopUp no-padding ">
                                <div class="form-group col-md-5" style="padding-right: 5px; padding-left: 0px;">
                                   <h5> New Role: </h5><input type = "text"  class="form-control" id = "new_role" placeholder = "Please Write Here" >
                                </div >
                                <div class="form-group col-md-5" style="padding-left: 5px; padding-right: 0px;">
                                    <h5> Description: </h5><input type = "text"  class="form-control" id = "new_des" placeholder = "Please Write Here" > </input>
                                </div>
                                <div class="col-md-2 no-padding">
                                    <button class="btn btn-sm btn-primary pull-right" id = "submit_new_role" style="margin-top: 37px; width: 80px;" > Submit </button >
                                </div>

                            </div >
                        </div>
                    </div>

                    <div id="all_staff_role">
                    </div>
                    <div id="alert_save_staff_role" class="alert-danger col-md-12">
                        <h5> Please Select Role and Check On Staff Before Saving New Record !!!! </h5>
                    </div>
                    <small>Staff and Roles</small>
                    <button class="btn btn-sm btn-danger pull-right" id="btn_save_staff_role" style=" margin-right: 20px;">Save</button>
                    <button class="btn btn-sm btn-default pull-right" id="btn_cancel_staff_role" style="margin-right: 8px;">Cancel</button>

                </blockquote>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
</div>
