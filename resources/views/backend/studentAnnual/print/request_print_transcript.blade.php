@extends ('backend.layouts.popup_master')

@section ('title', 'ITC-SMIS' . ' | ' . 'Print Transcript')

@section('page-header')
    <h1>
        {{ trans('labels.backend.candidates.title') }}
        <small>{{ trans('labels.backend.candidates.sub_create_title') }}</small>
    </h1>
@endsection

@section('after-styles-end')
    {!! Html::style('plugins/iCheck/square/red.css') !!}
    {!! Html::style('plugins/DataTables-1.10.15/media/css/dataTables.bootstrap.min.css') !!}
    {!! Html::style('plugins/daterangepicker/daterangepicker-bs3.css') !!}
    {!! Html::style('plugins/datetimepicker/bootstrap-datetimepicker.min.css') !!}
    {!! Html::style('plugins/select2/select2.min.css') !!}

    <style>
        .text-10 {
            font-size: 9pt !important;
        }

        .daterange {
            border: none;
            text-decoration: underline;
            width: 220px;
            padding: 0px;
            color: red;
        }

        .action_buttons, .btn-print {
            margin-left: 10px;
            margin-right: 10px;
        }

        .checkbox-toggle {
            margin-left: 5px;
        }

        select[name="decision"] {
            margin-left: 3mm;
            height: 8mm;
        }

        #filter_group {
            width: 2cm;
        }

        .filter {
            margin-bottom: 5px;
            margin-left: 5px;
            height: 8mm;
            float: right;
        }

        .select2 {
            margin-left: 5px;
            width: 50mm !important;
            float: right;
        }

        #filter_group {
            margin-left: 5px;
            border-radius: 5px !important;
        }

        .select2-selection, .select2-selection--multiple {
            min-height: 30px !important;
            line-height: 1.3;
        }

        .select2-search__field, .select2-selection__choice, .select2-selection__clear {
            margin-top: 3px !important;
        }

        input[name=issued_by] {
            width: 50mm;
        }
    </style>
@stop

@section('content')

    <div class="box box-success">
        <div class="box-header with-border">
            <h2 class="box-title pull-left" style="padding-top: 8px;">Printing Transcript for the exam in </h2>
            <div class="pull-left" style="padding-left: 10px;">
                <select name="transcript_type"
                        v-model="semester_id"
                        @change="onChangeSemester"
                        class="form-control">
                    <option value="semester1">Semester 1</option>
                    <option value="year">End of Year</option>
                </select>
            </div>

            {{--<div class="pull-right">--}}
            {{--<input type="text" name="issued_number" class="form-control"  placeholder="Issued number"/>--}}
            {{--</div>--}}
            <div class="pull-right" style="margin-right: 5px;">
                <input type="text" name="issued_by" class="form-control" placeholder="Issued by"
                       value="Deputy Director General"/>
            </div>
            <div class="pull-right" style="margin-right: 5px;">
                <input type="text"
                       id="issued_date"
                       :readonly="readonly"
                       name="issued_date"
                       v-model="issued_date"
                       class="form-control"
                       ref="issue_date"
                       placeholder="Issued date"/>
            </div>
            <div class="pull-right" style="margin-right: 5px; margin-top: 6px;">
                <input type="checkbox" name="photo" placeholder="Photo" value="photo" checked/> Photo
            </div>
            <div class="pull-right" style="margin-right: 5px;">
                <div class="btn-group">
                    <button class="btn btn-primary btn-sm" @click="showModal()">New Issue Date</button>
                    <button class="btn btn-warning btn-sm" @click="onClickSecondPrint">Second Print</button>
                </div>
            </div>
        </div><!-- /.box-header -->

        <div class="box-body">
            @include('backend.studentAnnual.includes.partials.table-header-transcript')
        </div>
    </div>
    <!-- Modal -->
    <div id="myModal"
         :class="'modal fade' + class_modal_toggle"
         :style="style_css"
         role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" @click="closeModal" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Create Issued Date</h4>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <input type="text"
                                   v-model="issued_date"
                                   id="input_issued_date"
                                   class="form-control"/>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button"
                            id="create_issued_date"
                            @click="createIssuedDate"
                            class="btn btn-primary"
                            data-dismiss="modal">Create</button>
                    <button type="button"
                            @click="closeModal"
                            class="btn btn-default"
                            data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

@stop

@section('after-scripts-end')
    {!! Html::script('plugins/iCheck/icheck.min.js') !!}
    {!! Html::script('plugins/DataTables-1.10.15/media/js/jquery.dataTables.min.js') !!}
    {!! Html::script('plugins/DataTables-1.10.15/media/js/dataTables.bootstrap.min.js') !!}
    {!! Html::script('plugins/daterangepicker/moment.min.js') !!}
    {!! Html::script('plugins/daterangepicker/daterangepicker.js') !!}
    {!! Html::script('plugins/datetimepicker/bootstrap-datetimepicker.min.js') !!}
    {!! Html::script('plugins/select2/select2.full.min.js') !!}
    <script src="{{ asset('node_modules/vue/dist/vue.js') }}"></script>
    <script src="{{ asset('node_modules/axios/dist/axios.js') }}"></script>
    <script src="https://unpkg.com/vue-swal"></script>

    <script>
        new Vue({
            el: '#app',
            data () {
            	return {
		            issued_date: null,
                    semester_id: 'semester1',
                    academic_year_id: null,
                    class_modal_toggle: '',
		            style_css: '',
		            input_issued_date: null,
                    readonly: true,
                }
            },

            methods: {
	            showModal () {
	                this.readonly = true
		            this.class_modal_toggle = ' in'
		            this.style_css = 'display: block; padding-left: 0px;'
	            },
	            onChangeSemester () {
	            	this.getIssuedData()
                },
	            createIssuedDate () {
	            	this.input_issued_date = $('#input_issued_date').val()
		            this.academic_year_id = '{{ $academicYearSelected }}'
		            let key = '_key_'+ this.academic_year_id + '_' + this.semester_id
		            axios.post('/admin/course/get-key-issued-data/store', {
		            	key: key,
                        value: this.input_issued_date
		            }).then((response) => {
				            if (response.data.hasOwnProperty('config')) {
					            this.issued_date = response.data.config.value
                                this.closeModal()
				            } else {
					            this.class_modal_toggle = ' in'
					            this.style_css = 'display: block; padding-left: 0px;'
				            }
			            })
			            .catch((error) => {
				            console.log(error)
			            })
                },
                getIssuedData () {
	            	this.academic_year_id = '{{ $academicYearSelected }}'
	                let key = '_key_'+ this.academic_year_id + '_' + this.semester_id
	                axios.post('/admin/course/get-key-issued-data', {
	                	key: key,
		                value: this.create_issused_date
	                }).then((response) => {
                        	if (response.data.hasOwnProperty('config')) {
                        		this.issued_date = response.data.config.value
                            } else {
		                        this.class_modal_toggle = ' in'
                                this.style_css = 'display: block; padding-left: 0px;'
                            }
                        })
                        .catch((error) => {
                        	console.log(error)
                        })
                },
                closeModal () {
	                this.class_modal_toggle = ''
	                this.style_css = 'display: hide;'
	                window.location.reload(true)
                },
                onClickSecondPrint () {
                    this.$swal('Wish to do second printing?', "The current issued date is " + this.issued_date, 'warning')
                    this.readonly = false
                }
            },

            mounted () {
                this.getIssuedData()
            }
        })
    </script>

    <script>
        var selected_ids = null;
        var print_url = "{{ route('admin.student.print_transcript') }}";
        var mark_url = "{{ route('admin.student.mark_printed_transcript') }}";
        var filter_class_url = '{{route('admin.filter.get_filter_by_class')}}';
        var print_student_list_url = '{{route('admin.student.print_student_list_transcript')}}';
        var oTable;

        function redraw_student_list() {
            oTable.draw();
        }

        function update_filter_class(callback) {
            $('#filter_class').empty();
            $.ajax({
                type: 'POST',
                url: filter_class_url,
                data: {'academic_year_id': $('#filter_academic_year').val()},
                dataType: "json",
                success: function (response) {
                    if (response.status == "success") {
                        $('#filter_class').select2({
                            data: response.data,
                            placeholder: "Select a class",
                            allowClear: true
                        });
                        try {
                            callback();
                        } catch (exception) {

                        }
                    } else {
                        notify("error", "info", "Something went wrong! Cannot filtering value");
                    }
                }
            });
        }

        function initIcheker() {
            $('#students-table input[type="checkbox"]').iCheck({
                checkboxClass: 'icheckbox_square-red',
                radioClass: 'iradio_square-red'
            });
        }

        function print(selected_ids, is_back = null, is_front = null, is_certificate = false) {

            // Check if exam date is selected
            if (selected_ids.length === 0) {
                alert_error("", "You need to select some students", null);
                return;
            }
            var issued_by = $('input[name="issued_by"]').val();
            if (issued_by == "") {
                alert_error("", "You need to tell who issue this transcript", $('input[name="issued_by"]').focus());
                return;
            }
            var issued_date = $('input[name="issued_date"]').val();
            if (issued_date == "") {
                alert_error("", "You need to select issue date", $('input[name="issued_date"]').focus());
                return;
            }
            // var issued_number = $('input[name="issued_number"]').val();
            var transcript_type = $('select[name="transcript_type"]').val();
            var photo = $('input[name="photo"]').is(":checked");
            // Open new window to print
            PopupCenterDual(
                print_url
                + "?transcript_type=" + transcript_type
                + "&issued_by=" + issued_by
                + "&issued_date=" + issued_date
                // +"&issued_number="+issued_number
                + "&photo=" + photo
                + "&is_back=" + is_back
                + "&is_front=" + is_front
                + "&is_certificate=" + is_certificate
                + '&ids=' + JSON.stringify(selected_ids),
                'Printing', '1200', '800');

        }

        function mark_printed_transcript() {
            var transcript_type = $('select[name="transcript_type"]').val();
            selected_ids = [];
            $('#students-table input:checked').each(function () {
                selected_ids.push($(this).data('id'));
            });
            $.ajax({
                url: mark_url + "?transcript_type=" + transcript_type + '&ids=' + JSON.stringify(selected_ids),
                cache: false,
                success: function (response) {
                    notify("info", response.message, "info");
                    $(".fa", $(".checkbox-toggle")).data("clicks", true);
                    $(".fa", $(".checkbox-toggle")).removeClass("fa-square-o").addClass('fa-check-square-o');
                    oTable.draw("page");
                }
            });
        }

        $(function () {
            $('input[name="issued_date"], #input_issued_date').datetimepicker({
                useCurrent: false,
                format: 'DD/MM/YYYY'
            });
            oTable = $('#students-table').DataTable({
                dom: 'f<"toolbar">rtip',
                processing: true,
                serverSide: true,
                pageLength: 50,
                deferLoading: 0,
                ajax: {
                    url: "{!! route('admin.student.request_print_transcript_data') !!}",
                    method: 'POST',
                    data: function (d) {
                        // In case additional fields is added for filter, modify export view as well: popup_export.blade.php
                        d.academic_year = $('#filter_academic_year').val();
                        d.gender = $('#filter_gender').val();
                        d.group = $('#filter_group').val();
                        d.student_class = JSON.stringify($("#filter_class").select2('data'));
                    }
                },
                columns: [
                    {data: 'checkbox', name: 'checkbox', searchable: false, orderable: false},
                    {data: 'id_card', name: 'students.id_card', orderable: false},
                    {data: 'name', name: 'students.name', orderable: false, searchable: false},
                    {data: 'dob', name: 'dob', orderable: false, searchable: false},
                    {data: 'gender', name: 'gender', orderable: false, searchable: false},
                    {data: 'class', name: 'class', orderable: false, searchable: false},
                    {data: 'group', name: 'group', orderable: false, searchable: false},
                    {data: 'printed_transcript', name: 'printed_transcript', orderable: false, searchable: false},
                    // {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                drawCallback: function () {
                    initIcheker();
                    $(".btn-print").off("click");

                    $(".btn-print").on("click", function () {
                        selected_ids = [];
                        $('#students-table input:checked').each(function () {
                            selected_ids.push($(this).data('id'));
                        });
                        print(selected_ids, true, true, true);
                    });
                    // print front
                    $(".btn-print-front").on("click", function () {
                        selected_ids = [];
                        $('#students-table input:checked').each(function () {
                            selected_ids.push($(this).data('id'));
                        });
                        print(selected_ids, false, true, true);
                    });
                    // print back
                    $(".btn-print-back").on("click", function () {
                        selected_ids = [];
                        $('#students-table input:checked').each(function () {
                            selected_ids.push($(this).data('id'));
                        });
                        print(selected_ids, true, false, true);
                    });
                    // print certificate
                    $(".btn-print-certificate").on("click", function () {
                        selected_ids = [];
                        $('#students-table input:checked').each(function () {
                            selected_ids.push($(this).data('id'));
                        });
                        print(selected_ids, true, true, true);
                    });
                    // print transcript
                    $(".btn-print-transcript").on("click", function () {
                        selected_ids = [];
                        $('#students-table input:checked').each(function () {
                            selected_ids.push($(this).data('id'));
                        });
                        print(selected_ids, false, false);
                    });

                    $(".btn-single-print").off("click");
                    $(".btn-single-print").on("click", function () {
                        var selected_ids = [$(this).data('id')];
                        print(selected_ids);
                    });
                    $(".btn-mark-printed-date").off("click");
                    $(".btn-mark-printed-date").on("click", function () {
                        alert_confirm("Confirm", "The selected students' transcript will be marked as printed. Are you sure?", mark_printed_transcript);
                    });
                    //Enable check and uncheck all functionality
                    $(".checkbox-toggle").off('click');
                    $(".checkbox-toggle").on('click', function () {
                        var clicks = $(this).data('clicks');
                        if (clicks) {
                            //Check all checkboxes
                            $("#students-table input[type='checkbox']").iCheck("check");
                            $(".fa", this).removeClass("fa-square-o").addClass('fa-check-square-o');
                        } else {
                            //Uncheck all checkboxes
                            $("#students-table input[type='checkbox']").iCheck("uncheck");
                            $(".fa", this).removeClass("fa-check-square-o").addClass('fa-square-o');
                        }
                        $(this).data("clicks", !clicks);
                    });
                }
            });

            $(".toolbar").html(
                '<button type="button" class="btn btn-default btn-sm checkbox-toggle">' +
                '<i class="fa fa-check-square-o"></i>' +
                '</button>' +
                `
                        <div class="btn-group">
                            <button type="button" class="btn btn-default btn-sm btn-print-front" data-value="front"><i class="fa fa-print"></i> FRONT</button>
                            <button type="button" class="btn btn-default btn-sm btn-print-back" data-value="back"><i class="fa fa-print"></i> BACK</button>
                        </div>

                        <div class="btn-group">
                            <button type="button" class="btn btn-default btn-sm btn-print-transcript" data-value="transcript"><i class="fa fa-print"></i> Print Transcript</button>
                            <button type="button" class="btn btn-default btn-sm btn-print-certificate" data-value="certificate"><i class="fa fa-print"></i> Print Certificate</button>
                        </div>

                    ` +
                '<button type="button" data-toggle="tooltip" data-placement="right" title="You can mark the printed date on every transcript " class="btn btn-default btn-sm btn-mark-printed-date"><i class="fa fa-calendar"></i> Mark Printed Date</button>' +
                '{!! Form::select('gender',$genders,null, array('class'=>'form-control filter','id'=>'filter_gender','placeholder'=>'Gender')) !!} ' +
                '{!! Form::text('group',null, array('class'=>'form-control filter','id'=>'filter_group','placeholder'=>'Group')) !!} ' +
                '<select name="student_class" class="form-control filter" multiple="multiple" id="filter_class"></select>' +
                '{!! Form::select('academic_year',$academicYears,$academicYearSelected, array('class'=>'form-control filter','id'=>'filter_academic_year')) !!} ' +
                '<button type="button" data-toggle="tooltip" data-placement="right" title="You can print this student list " class="btn btn-default btn-sm btn-print-student-list"><i class="fa fa-print"></i> Print Student List</button>'
            );

            update_filter_class(redraw_student_list);

            $(document.body).on("change", "#filter_class", function (e) {
                redraw_student_list();
            });
            $(document.body).on("change", "#filter_group", function (e) {
                redraw_student_list();
            });
            $(document.body).on("change", "#filter_gender", function (e) {
                redraw_student_list();
            });
            $(document.body).on("change", "#filter_academic_year", function (e) {
                update_filter_class(redraw_student_list);
            });

            $(document.body).on("click", ".btn-print-student-list", function () {

                if ($("#filter_class").select2('data').length !== 1) {
                    alert_error("", "You need to select only one class", null);
                    return;
                }

                PopupCenterDual(
                    print_student_list_url
                    +'?academic_year=' + $('#filter_academic_year').val()
                    +'&student_class=' + $("#filter_class").select2('data')[0].id
                    +'&gender=' + $('#filter_gender').val()
                    +'&group=' + $('#filter_group').val(),
                    'Printing', '1200', '800');
            })
        });

    </script>
@stop
