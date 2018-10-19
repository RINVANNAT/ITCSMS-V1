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
                                <select class="form-control">
                                    <option v-for="item in 3">A</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-md-12">
                                <h3>Rooms</h3>
                            </div>
                            <div class="col-md-12">
                                <select class="form-control">
                                    <option v-for="item in 3">F207</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-md-12">
                                <h3>Lecturers</h3>
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-12">
                                    <select class="form-control">
                                        <option v-for="item in 3">HENG Sothearith</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr/>
                    
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
                            <tr v-for="(item, key) in 3">
                                <td>@{{ key+1 }}</td>
                                <td>A</td>
                                <td>F207</td>
                                <td>HENG Sothearith</td>
                                <td>
                                    <button class="btn btn-primary btn-xs"><i class="fa fa-edit"></i> </button>
                                    <button class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>