<div class="row">
    <div class="col-md-12">
        <div>
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active">
                    <a href="#room"
                       aria-controls="room"
                       role="tab"
                       data-toggle="tab"><i class="fa fa-building"></i> Room</a></li>
                <li role="presentation">
                    <a href="#lecturer"
                       aria-controls="lecturer"
                       role="tab"
                       data-toggle="tab"><i class="fa fa-users"></i> Lecturer</a></li>
                <li role="presentation">
                    <a href="#timetableGroup"
                       aria-controls="group"
                       role="tab"
                       data-toggle="tab"><i class="fa fa-th-large"></i> Group</a></li>
            </ul>
            <div class="tab-content">
                <div role="tabpanel"
                     class="tab-pane active"
                     id="room">
                    <div class="box box-default" style="border: 1px solid #dddddd; border-top: 0; border-top-left-radius: 0; border-top-right-radius: 0;">
                        <div class="box-header with-border">
                            <div class="form-group">
                                <input type="text"
                                       style="border-radius: 4px !important;"
                                       class="form-control"
                                       name="search_room_query"
                                       placeholder="Find room..."/>
                            </div>
                        </div>
                        <div class="box-body">
                            @if(access()->allow('add-room-timetable-slot') && access()->allow('remove-room-timetable-slot'))
                                <div class="rooms">
                                    @if(isset($rooms))
                                        @foreach($rooms as $room)
                                            <div class="room-item">
                                                <i class="fa fa-building-o"></i>
                                                {{ $room->name }}
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            @else
                                <div class="alert alert-danger {{--alert-dismissible--}}">
                                    {{--<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>--}}
                                    <h4><i class="icon fa fa-info"></i>{{ trans('strings.backend.timetable.block_add_room') }}</h4>
                                    <p>{{ trans('strings.backend.timetable.desc_block_add_room') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div role="tabpanel"
                     class="tab-pane"
                     id="lecturer">
                    <div class="box box-default" style="border: 1px solid #dddddd; border-top: 0;">
                        <div class="box-header with-border">
                            <div class="form-group">
                                <input type="text"
                                       style="border-radius: 4px !important;"
                                       class="form-control"
                                       id="search-employee"
                                       name="employee_query"
                                       placeholder="Find lecturer..."/>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="select2-results">
                                <ul class="select2-results__options"
                                    role="tree"
                                    id="employee-viewer"
                                    aria-expanded="true"
                                    aria-hidden="false"></ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div role="tabpanel"
                     class="tab-pane"
                     id="timetableGroup">
                    <div class="box box-default" style="border: 1px solid #dddddd; border-top: 0;">
                        <div class="box-header with-border">
                            <div class="input-group margin">
                                <input type="text"
                                       style="border-radius: 4px !important;"
                                       class="form-control"
                                       id="search-timetable-group"
                                       name="employee_query"
                                       placeholder="Find group..."/>
                                <span class="input-group-btn">
                                    <button class="btn btn-primary btn_add_new_group"
                                            data-toggle="modal"
                                            data-target="#add-new-group"
                                            data-placement="top"
                                            title="Add new group">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="timetable_group_width">
                                @foreach ($timetable_groups as $group)
                                    <div class="col-md-2 timetable_group">
                                        {{$group->code}}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="add-new-group" tabindex="-1" role="dialog" aria-labelledby="add-new-group">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    Add New Group
                </h4>
            </div>
            <div class="modal-body">
                <div class="form-horizontal form-new-group">
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Group Parent</label>
                        <div class="col-sm-8">
                            <select name="timetable_group_parent_id" class="form-control">
                                <option></option>
                                @foreach ($timetable_groups as $group)
                                    <option value="{{ $group->id }}">
                                        {{$group->code}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-4 control-label">Group Name</label>
                        <div class="col-sm-8">
                            <input name="timetable_group_name" type="text" class="form-control" id="group_name" placeholder="Group Name">
                        </div>
                    </div>
                    <hr>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button class="btn btn-primary btn-sm btn-save-new-group">Save</button>
                            <button type="button" class="btn btn-default btn-sm btn_cancel_clone_timetable"
                                    data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>