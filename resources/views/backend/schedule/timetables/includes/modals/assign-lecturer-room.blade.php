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
                    <div class="col-md-12">
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Group</th>
                                <th width="40%">Room</th>
                                <th width="40%">Lecturer</th>
                            </tr>
                            </thead>
                            <tbody>
                            <template v-if="groupOptions.length > 0">
                                <tr v-for="(item, key) in groupOptions" :key="key">
                                    <td>@{{ item.code }}</td>
                                    <td>
                                        <multiselect v-model="groupRoomLecturers[key].room"
                                                     label="code"
                                                     track-by="id"
                                                     :options="roomOptions"
                                                     :searchable="true"
                                                     :close-on-select="true"
                                                     :show-labels="false"
                                                     placeholder="Chose room"></multiselect>
                                    </td>
                                    <td>
                                        <multiselect v-model="groupRoomLecturers[key].lecturer"
                                                     label="name_latin"
                                                     track-by="id"
                                                     :options="employees"
                                                     :searchable="true"
                                                     :close-on-select="true"
                                                     :show-labels="false"
                                                     placeholder="Chose lecturer"></multiselect>
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

                    <div class="col-md-12" v-if="groupOptions.length > 0">
                        <button class="btn btn-primary" @click="onClickAssignRoomAndLecturerToTimetableSlot">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>