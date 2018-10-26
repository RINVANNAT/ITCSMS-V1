<div class="modal fade"
     id="assign-lecturer-room"
     tabindex="-1"
     role="dialog"
     data-keyboard="false"
     data-backdrop="static"
     aria-labelledby="cloneTimetable">
    <div class="modal-dialog modal-lg"
         role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"
                    id="myModalLabel">Assign Lecturer And Room</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-11">
                        <div class="row">
                            <div class="col-md-4">
                                <multiselect v-model="newGroupRoomLecturer.groups"
                                             :multiple="true"
                                             label="code"
                                             track-by="id"
                                             :options="groups"
                                             :searchable="true"
                                             :close-on-select="true"
                                             :show-labels="false"
                                             placeholder="Chose groups"></multiselect>
                            </div>
                            <div class="col-md-4">
                                <multiselect v-model="newGroupRoomLecturer.room"
                                             label="code"
                                             track-by="id"
                                             :options="roomOptions"
                                             :searchable="true"
                                             :close-on-select="true"
                                             :show-labels="false"
                                             placeholder="Chose room"></multiselect>
                            </div>

                            <div class="col-md-4">
                                <multiselect v-model="newGroupRoomLecturer.lecturer"
                                             label="name_latin"
                                             track-by="id"
                                             :options="employees"
                                             :searchable="true"
                                             :close-on-select="true"
                                             :show-labels="false"
                                             placeholder="Chose lecturer"></multiselect>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <button class="btn btn-info pull-right" @click="addItem">
                            <i class="fa fa-plus-circle"></i> Add
                        </button>
                    </div>
                </div>

                <div class="row" style="margin-top: 15px; margin-bottom: 15px;"></div>

                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Group</th>
                                <th width="40%">Room</th>
                                <th width="40%">Lecturer</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <template v-if="groupRoomLecturers.length > 0">
                                <tr v-for="(item, key) in groupRoomLecturers"
                                    :key="key">
                                    <td>@{{ item.group.code }}</td>
                                    <td>@{{ item.room.code }}</td>
                                    <td>@{{ item.lecturer.name_latin }}</td>
                                    <td>
                                        <button class="btn btn-danger btn-xs" @click="removeItem(item)">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                            <template v-else>
                                <tr>
                                    <td colspan="5" class="text-center">No groups are found.</td>
                                </tr>
                            </template>

                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-12" v-if="groupRoomLecturers.length > 0">
                        <button class="btn btn-primary" @click="onClickAssignRoomAndLecturerToTimetableSlot">Save</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>