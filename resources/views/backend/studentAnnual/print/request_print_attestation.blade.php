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

    <div class="box box-success" id="app">
        <div class="box-header with-border">

            <div class="pull-right" style="margin-right: 5px;">
                <input type="text" name="issued_by" class="form-control" placeholder="Issued by"
                       value="Deputy Director General"/>
            </div>

            <div class="pull-right" style="margin-right: 5px;">

                <input type="text"
                       readonly
                       name="issued_date"
                       class="form-control"
                       v-model="issued_date"
                       placeholder="Issued date"/>
            </div>

            <div class="pull-right" style="margin-right: 5px;">
                <button class="btn btn-primary btn-sm" @click="showModal()">New Issue Date</button>
            </div>
        </div><!-- /.box-header -->

        <div class="box-body">
            @include('backend.studentAnnual.includes.partials.table-header-transcript')
        </div>
    </div>

    <!-- Modal Modify Date -->
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
                                   name="issued_date"
                                   v-model="issued_date"
                                   id="issued_date"
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
    {{--End Modal--}}
@stop

@section('after-scripts-end')
    {!! Html::script('plugins/iCheck/icheck.min.js') !!}
    {!! Html::script('plugins/DataTables-1.10.15/media/js/jquery.dataTables.min.js') !!}
    {!! Html::script('plugins/DataTables-1.10.15/media/js/dataTables.bootstrap.min.js') !!}
    {!! Html::script('plugins/daterangepicker/moment.min.js') !!}
    {!! Html::script('plugins/daterangepicker/daterangepicker.js') !!}
    {!! Html::script('plugins/datetimepicker/bootstrap-datetimepicker.min.js') !!}
    {!! Html::script('plugins/select2/select2.full.min.js') !!}
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.18.0/axios.min.js"></script>


    <script>
        new Vue({
            el: '#app',

            data () {
            	return {
		            issued_date: null,
		            class_modal_toggle: '',
		            style_css: ''
                }
            },

            methods: {
	            createIssuedDate () {
		            this.issued_date = $('#issued_date').val()
		            this.academic_year_id = '{{ $academicYear }}'
		            let key = 'attestation_'+ this.academic_year_id
		            axios.post('/admin/course/attestation/get-key-issued-date-attestation/store', {
			            key: key,
			            value: this.issued_date
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
	            getIssuedDate () {
		            this.academic_year_id = '{{ $academicYear }}'
		            let key = 'attestation_'+ this.academic_year_id
		            axios.post('/admin/course/attestation/get-key-issued-date-attestation', {
			            key: key,
			            value: this.issued_date
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
                showModal () {
	                this.class_modal_toggle = ' in'
	                this.style_css = 'display: block; padding-left: 0px;'
                }
            },

            mounted() {
                this.getIssuedDate()
            }
        })
    </script>

    <script>
        var selected_ids = null;
        var print_url = "{{ route('admin.student.print_attestation') }}";
        var mark_url = "{{ route('admin.student.mark_printed_transcript') }}";
        var filter_class_url = '{{route('admin.filter.get_filter_by_class_final_year')}}';
        var oTable;

        function redraw_student_list() {
            oTable.draw();
        }

        function update_filter_class(callback) {
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

        function print(selected_ids) {

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
            $('input[name="issued_date"]').datetimepicker({
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
                    url: "{!! route('admin.student.request_print_attestation_data') !!}",
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

                    // print attestation
                    $(".btn-print-attestation").on("click", function () {
                        selected_ids = [];
                        $('#students-table input:checked').each(function () {
                            selected_ids.push($(this).data('id'));
                        });
                        print(selected_ids, true, true, true);
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
                        <button type="button" class="btn btn-default btn-sm btn-print-attestation" data-value="attestation"><i class="fa fa-print"></i> Print Attestation</button>

                    ` +
                '<button type="button" data-toggle="tooltip" data-placement="right" title="You can mark the printed date on every transcript " class="btn btn-default btn-sm btn-mark-printed-date"><i class="fa fa-calendar"></i> Mark Printed Date</button>' +
                '{!! Form::select('gender',$genders,null, array('class'=>'form-control filter','id'=>'filter_gender','placeholder'=>'Gender')) !!} ' +
                '{!! Form::text('group',null, array('class'=>'form-control filter','id'=>'filter_group','placeholder'=>'Group')) !!} ' +
                '<select name="student_class" class="form-control filter" multiple="multiple" id="filter_class"></select>' +
                '{!! Form::select('academic_year',$academicYears,$academicYear, array('class'=>'form-control filter','id'=>'filter_academic_year')) !!} '
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
        });

    </script>
@stop