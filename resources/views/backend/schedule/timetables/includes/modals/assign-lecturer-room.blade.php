<div class="modal fade"
     id="assign-lecturer-room"
     tabindex="-1"
     role="dialog"
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
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-md-12">
                                <h3>Groups</h3>
                            </div>
                            <div class="col-md-12">
                                <multiselect v-model="newAssignRoomAndLecturer.group"
                                             label="code"
                                             track-by="id"
                                             :options="groupOptions"
                                             :searchable="true"
                                             :close-on-select="true"
                                             :show-labels="false"
                                             placeholder="Chose group"></multiselect>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-md-12">
                                <h3>Rooms</h3>
                            </div>
                            <div class="col-md-12">
                                <multiselect v-model="newAssignRoomAndLecturer.room"
                                             label="code"
                                             track-by="id"
                                             :options="roomOptions"
                                             :searchable="true"
                                             :close-on-select="true"
                                             :show-labels="false"
                                             placeholder="Chose room"></multiselect>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-md-12">
                                <h3 class="box-title">Lecturers</h3>
                            </div>
                            <div class="col-md-12">
                                <multiselect v-model="newAssignRoomAndLecturer.lecturer"
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

                    <div class="col-md-12" style="margin-bottom: 15px; margin-top: 15px;">
                        <button class="btn btn-primary btn-sm"
                                @click="assignRoomLecturer">
                            <i class="fa fa-plus-circle"></i> Add
                        </button>
                    </div>

                    <div class="col-md-12">
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>No.</th>
                                <th>Group</th>
                                <th>Room</th>
                                <th>Lecturer</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <template v-if="groupRoomLecturers.length > 0">
                                <tr v-for="(item, key) in groupRoomLecturers">
                                    <td>@{{ key+1 }}</td>
                                    <td>@{{ item.group }}</td>
                                    <td>@{{ item.room }}</td>
                                    <td>@{{ item.lecturer }}</td>
                                    <td>
                                        <button class="btn btn-primary btn-xs"><i class="fa fa-edit"></i> </button>
                                        <button class="btn btn-danger btn-xs" @click="removeGroupRoomLecturer(item)"><i class="fa fa-trash"></i> </button>
                                    </td>
                                </tr>
                            </template>
                            <template v-else>
                                <tr>
                                    <td colspan="5" class="text-center">No data.</td>
                                </tr>
                            </template>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>