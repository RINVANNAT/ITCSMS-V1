
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
                        <a id="download_attendance_list" target="_blank" href="{{route('admin.exam.download_attendance_list',$exam->id)}}">
                            @endauth
                            <img src="{{url('img/exam/list_student_to_sign.png')}}" alt="Fjords" width="300" height="200">
                            @permission('download-examination-document')
                        </a>
                        @endauth
                        <div class="desc">Candidate Attendance List By Course</div>
                    </div>

                    <div class="img">
                        @permission('download-examination-document')
                        <a id="download_student_list" target="_blank" href="{{route('admin.exam.download_candidate_list',$exam->id)}}">
                            @endauth
                            <img src="{{url('img/exam/student_list_by_room.png')}}" alt="Forest" width="300" height="200">
                            @permission('download-examination-document')
                        </a>
                        @endauth
                        <div class="desc">Candidate List By Room ordering</div>
                    </div>
                    <div class="img">
                        @permission('download-examination-document')
                        <a id="download_student_list" target="_blank" href="{{route('admin.exam.download_candidate_list_by_register_id',$exam->id)}}">
                            @endauth
                            <img src="{{url('img/exam/candidate_list_by_register_id.png')}}" alt="Forest" width="300" height="200">
                            @permission('download-examination-document')
                        </a>
                        @endauth
                        <div class="desc">Candidate List Order by register_id</div>
                    </div>


                    <div class="img">
                        @permission('download-examination-document')
                        <a id="download_room_sticker" target="_blank" href="{{route('admin.exam.download_room_sticker',$exam->id)}}">
                            @endauth
                            <img src="{{url('img/exam/room_sticker.png')}}" alt="Northern Lights" width="300" height="200">
                            @permission('download-examination-document')
                        </a>
                        @endauth
                        <div class="desc">Room Sticker</div>
                    </div>

                    <div class="img">
                        @permission('download-examination-document')
                        <a id="download_correction_sheet" target="_blank" href="{{route('admin.exam.download_correction_sheet',$exam->id)}}">
                            @endauth
                            <img src="{{url('img/exam/correction_sheet.png')}}" alt="Mountains" width="300" height="200">
                            @permission('download-examination-document')
                        </a>
                        @endauth
                        <div class="desc">Correction sheets</div>
                    </div>

                    <div class="img">
                        @permission('download-examination-document')
                        <a id="download_candidate_result"  href="{{route('print_candidate_result_lists', '/status='.'request_print_page')}}">
                            @endauth

                            <img src="{{url('img/exam/candidate_score_list.png')}}" alt="Mountains" width="300" height="200">

                            @permission('download-examination-document')
                        </a>
                        @endauth
                        <div class="desc">Candidate Result Sheet</div>
                    </div>
                    @endif
                </div>



            </div>
        </div>

    </div>

</div>
