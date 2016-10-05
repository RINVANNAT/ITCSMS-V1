
<div class="row" id="row-main">
    <div class="col-sm-12" id="main_window_staff_role">
        <div class="box box-solid">
            <div class="box-header with-border">
                <i class="fa fa-users" aria-hidden="true"></i>

                <h3 class="box-title">Download Documents</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    @if($exam->type_id == 2)
                    <div class="img">
                        <a target="_blank" href="{{route('admin.exam.download_candidate_list_dut',$exam->id)}}">
                            <div class="desc">Candidate DUT List</div>
                        </a>
                    </div>
                    @endif
                    @if($exam->type_id == 1)
                    <div class="img">
                        <a target="_blank" href="{{route('admin.exam.download_candidate_list_ing',$exam->id)}}">
                            <div class="desc">Candidate Engineer List</div>
                        </a>
                    </div>
                    @endif
                    <div class="img">
                        <a target="_blank" href="{{route('admin.exam.download_registration_statistic',$exam->id)}}">
                            <div class="desc">Registration Statistic</div>
                        </a>
                    </div>
                </div>

                <div class="row">
                    @if($exam->type_id == 1)
                    <div class="img">
                        @permission('download-examination-document')
                            <div class="desc">Candidate Attendance List By Course</div>
                            <div class="desc">
                                <a id="download_attendance_list" target="_blank" href="{{route('admin.exam.download_attendance_list',$exam->id)}}">
                                    <button  class="btn btn-primary btn-xs" id="print_att_list"> Print </button>
                                </a>

                                <a id="excel_attendance_list" target="_blank" href="{{route('admin.exam.export_attendance_list',$exam->id)}}">
                                    <button class="btn btn-info btn-xs"id="export_att_list"> Excel </button>
                                </a>
                            </div>
                        @endauth

                    </div>

                    <div class="img">
                        @permission('download-examination-document')
                        <div class="desc">Candidate List By Room ordering</div>
                        <div class="desc">
                            <a id="download_student_list" target="_blank" href="{{route('admin.exam.download_candidate_list',$exam->id)}}">
                                <button  class="btn btn-primary btn-xs" id="print_candidate_list"> Print </button>
                            </a>

                            <a id="download_student_list" target="_blank" href="{{route('admin.exam.export_candidate_list',$exam->id)}}">
                                <button class="btn btn-info btn-xs"id="export_candidate_list"> Excel </button>
                            </a>
                        </div>

                        @endauth

                    </div>
                    <div class="img">
                        @permission('download-examination-document')
                        <div class="desc">Candidate List Order by register_id</div>
                        <div class="desc">
                            <a id="download_student_list" target="_blank" href="{{route('admin.exam.download_candidate_list_by_register_id',$exam->id)}}">
                                <button  class="btn btn-primary btn-xs" id="print_candidate_list_by_register_id"> Print </button>
                            </a>

                            <a id="download_student_list" target="_blank" href="{{route('admin.exam.export_candidate_list_by_register_id',$exam->id)}}">
                                <button class="btn btn-info btn-xs"id="export_candidate_list_by_register_id"> Excel </button>
                            </a>
                        </div>

                        @endauth

                    </div>


                    <div class="img">
                        @permission('download-examination-document')

                        <div class="desc">List of The Room Sticker</div>
                        <div class="desc">
                            <a id="download_room_sticker" target="_blank" href="{{route('admin.exam.download_room_sticker',$exam->id)}}">
                                <button  class="btn btn-primary btn-xs" id="print_att_list"> Print </button>
                            </a>
                        </div>

                        @endauth

                    </div>

                    <div class="img">
                        @permission('download-examination-document')
                        <div class="desc">List of Correction sheets</div>
                        <div class="desc">
                            <a id="download_correction_sheet" target="_blank" href="{{route('admin.exam.download_correction_sheet',$exam->id)}}">
                                <button  class="btn btn-primary btn-xs" id="print_att_list"> Print </button>
                            </a>
                        </div>

                        @endauth

                    </div>

                    <div class="img">
                        @permission('download-examination-document')
                        <div class="desc">Candidate Result Sheet</div>
                        <div class="desc">
                            <a id="download_candidate_result" target="_blank"  href="{{route('print_candidate_result_lists', '/status='.'request_print_page'.'&exam_id='.$exam->id)}}">
                                <button  class="btn btn-primary btn-xs" id="print_att_list"> Print </button>
                            </a>
                            <a id="download_candidate_result" target="_blank"  href="{{route('admin.exam.export_candidate_result_lists', $exam->id)}}">
                                <button class="btn btn-info btn-xs"id="export_att_list"> Excel </button>
                            </a>
                        </div>

                        @endauth

                    </div>
                    @endif
                </div>



            </div>
        </div>

    </div>

</div>
