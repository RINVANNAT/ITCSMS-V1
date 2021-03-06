@extends ('backend.layouts.master')

@section ('title', 'ITC - TTMS')

@section('page-header')

    <h1>
        {{ trans('labels.backend.schedule.timetable.title') }}
        <small>{{ trans('labels.backend.schedule.timetable.sub_index_title') }}</small>
    </h1>

@endsection

@section('after-styles-end')

    {!! Html::style('plugins/datatables/dataTables.bootstrap.css') !!}
    {!! Html::style('plugins/select2/select2.min.css') !!}
    {!! Html::style('plugins/sweetalert2/dist/sweetalert2.css') !!}
    {!! Html::style('plugins/daterangepicker/daterangepicker-bs3.css') !!}
    {!! Html::style('bower_components/animate.css/animate.min.css') !!}
    {!! Html::style('css/backend/schedule/timetable.css') !!}

    <style>
        .toolbar {
            float: left;
        }

        .dataTables_filter, .dataTables_info {
            display: none;
        }
    </style>
@stop

@section('content')

    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('labels.backend.schedule.timetable.sub_index_title') }}</h3>
            <div class="box-tools pull-right">
                @if(isset($createTimetablePermissionConfiguration))
                    @if(((strtotime($now) >= strtotime($createTimetablePermissionConfiguration->created_at) && strtotime($now) <= strtotime($createTimetablePermissionConfiguration->updated_at)) && access()->allow('create-timetable')))
                        <a class="btn btn-primary btn-sm"
                           data-toggle="tooltip"
                           data-placement="top"
                           title="Manage Timetable"
                           data-original-title="Manage Timetable"
                           href="{{ route('admin.schedule.timetables.create') }}">
                            <i class="fa fa-plus-circle"></i>
                            Manage Timetable
                        </a>
                    @endif
                @else
                    <a class="btn btn-primary btn-sm"
                       data-toggle="tooltip"
                       data-placement="top"
                       title="Manage Timetable"
                       data-original-title="Manage Timetable"
                       href="{{ route('admin.schedule.timetables.create') }}">
                        <i class="fa fa-plus-circle"></i>
                        Manage Timetable
                    </a>
                @endif
                @permission('create-timetable-assignment')
                <button class="btn btn-primary btn-sm"
                        data-toggle="modal"
                        data-target="#modal-timetable-assignment">
                    <i class="fa fa-plus-circle"></i> {{ trans('buttons.backend.schedule.timetable.assignment_permission') }}
                </button>
                @endauth
                <a href="/admin/schedule/timetables/check-available-room" class="btn btn-warning btn-sm">
                    <i class="fa fa-building-o"></i> Check Available Room
                </a>
            </div>
        </div>
        <div class="row">
            @if(access()->allow('create-timetable-assignment'))
                <div class="col-md-7">
                    @include('backend.schedule.timetables.includes.partials.timetable-viewer')
                </div>
                <div class="col-md-5">
                    @include('backend.schedule.timetables.includes.partials.timetable-assignment')
                </div>
            @else
                <div class="col-xs-12 col-sm-9 col-md-9">
                    @include('backend.schedule.timetables.includes.partials.timetable-viewer')
                </div>
                <div class="col-xs-12 col-sm-3 col-md-3">
                    @include('backend.schedule.timetables.includes.partials.rest-course-session')
                </div>
            @endif
        </div>
    </div>

    {{--modal timetable assignment--}}
    @include('backend.schedule.timetables.includes.modals.assign')
    @include('backend.schedule.timetables.includes.modals.update-assign')
@stop

@section('after-scripts-end')

    {!! Html::script('plugins/datatables/jquery.dataTables.min.js') !!}
    {!! Html::script('plugins/datatables/dataTables.bootstrap.min.js') !!}
    {!! Html::script('plugins/sweetalert2/dist/sweetalert2.js') !!}
    {!! Html::script('plugins/select2/select2.full.min.js') !!}
    {!! Html::script('plugins/daterangepicker/moment.js') !!}
    {!! Html::script('plugins/daterangepicker/daterangepicker.js') !!}
    {!! Html::script('js/backend/schedule/filter-options.js') !!}
    {!! Html::script('js/backend/schedule/timetable-print.js') !!}

    {{--timetable management--}}
    <script type="text/javascript">

        function get_groups() {
            toggleLoading(true);
            $.ajax({
                type: 'POST',
                url: '/admin/schedule/timetables/get_groups',
                data: $('#options-filter').serialize(),
                success: function (response) {
                    if (response.status === true) {
                        var group_item = '';
                        $.each(response.groups, function (key, val) {
                            group_item += '<option value="' + val.id + '">' + val.code + '</option>';
                        });

                        $('select[name="group"]').html(group_item);
                    }
                    else {
                        $('select[name="group"]').html('');
                    }
                },
                error: function () {
                    swal(
                        'Oops...',
                        'Something went wrong!',
                        'error'
                    );
                },
                complete: function () {
                    get_weeks($('select[name="semester"] :selected').val());
                    get_rest_course_sessions();
                    toggleLoading(false);
                }
            })
        }

        function get_rest_course_sessions() {
            toggleLoading(true);
            $.ajax({
                type: 'POST',
                url: '/admin/schedule/timetables/get_course_programs',
                data: $('#options-filter').serialize(),
                success: function (response) {
                    if (response.status === true) {
                        var course_session_item = '';
                        $.each(response.course_sessions, function (key, val) {
                            if (val.teacher_name === null) {
                                course_session_item += '<li class="course-item disabled">';
                            }
                            else {
                                course_session_item += '<li class="course-item">';
                            }
                            course_session_item += '<span class="handle ui-sortable-handle">' +
                                '<i class="fa fa-ellipsis-v"></i> ' +
                                '<i class="fa fa-ellipsis-v"></i>' +
                                '</span>' +
                                '<span class="text course-name">' + val.course_name + '</span><br>';
                            if (val.teacher_name === null) {
                                course_session_item += '<span style="margin-left: 28px;" class="teacher-name bg-danger badge">Unsigned</span><br/>';
                            } else {
                                course_session_item += '<span style="margin-left: 28px;" class="teacher-name">' + val.teacher_name + '</span><br/>';
                            }
                            if (val.tp !== 0) {
                                course_session_item += '<span style="margin-left: 28px;" class="course-type">TP</span> : ' +
                                    '<span class="times">' + val.remaining + '</span> H'
                            }
                            else if (val.td !== 0) {
                                course_session_item += '<span style="margin-left: 28px;" class="course-type">TD</span> : ' +
                                    '<span class="times">' + val.remaining + '</span> H'
                            }
                            else {
                                course_session_item += '<span style="margin-left: 28px;" class="course-type">Course</span> : ' +
                                    '<span class="times">' + val.remaining + '</span> H'
                            }
                            course_session_item += '<span class="text courses-session-id" style="display: none;">' + val.course_session_id + '</span><span class="text slot-id" style="display: none;">' + val.id + '</span><br>' + '</li>';
                        });

                        $('.courses.todo-list').html(course_session_item);
                    }
                    else {
                        $('.courses.todo-list').html("<li class='course-item'>There are no rest course sessions.</li>");
                    }
                },
                error: function () {
                    swal(
                        'Oops...',
                        'Something went wrong!',
                        'error'
                    );
                },
                complete: function () {
                    toggleLoading(false);
                }
            });
        }

        var oTable = $('#timetables-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{!! route('admin.schedule.timetables.get_timetables') !!}',
                method: 'POST',
                data: function (d) {

                    d.academicYear = $('select[name="academicYear"] :selected').val();
                    d.department = $('select[name="department"] :selected').val();
                    d.option = $('select[name="option"] :selected').val();
                    d.degree = $('select[name="degree"] :selected').val();
                    d.grade = $('select[name="grade"] :selected').val();
                    d.semester = $('select[name="semester"] :selected').val();
                    d.weekly = $('select[name="weekly"] :selected').val();
                    d.group = $('select[name="group"] :selected').val();
                    d._token = '{!! csrf_token() !!}';

                }
            },
            columns: [
                {data: 'week', name: 'week', searchable: true, orderable: true},
                {data: 'status', name: 'status', searchable: false, orderable: false},
                    @if(access()->allow('view-timetable') || access()->allow('delete-timetable') || access()->allow('edit-timetable'))
                {
                    data: 'action', name: 'action', searchable: false, orderable: false
                }
                @endif
            ]
        });

        /** Get weeks. **/
        function get_weeks(semester_id) {
            toggleLoading(true);
            $.ajax({
                type: 'POST',
                url: '/admin/schedule/timetables/get_weeks',
                data: {semester_id: semester_id},
                success: function (response) {
                    var option = '';
                    $.each(response.weeks, function (key, val) {
                        option += '<option value="' + val.id + '">' + val.name_en + '</option>';
                    });

                    $('select[name="weekly"]').html(option);
                },
                error: function () {
                    swal(
                        'Oops...',
                        'Something went wrong!',
                        'error'
                    );
                },
                complete: function () {
                    oTable.draw();
                    toggleLoading(false);
                }
            });
        }

        $(function () {
            $('.smis-notification').addClass('animated slideInRight');
            $('.smis-close-icon').click(function () {
                $('.smis-notification').addClass('animated slideOutRight');
            });

            get_options($('select[name="department"] :selected').val());

            $(document).on('change', 'select[name="semester"]', function () {
                get_weeks($(this).val());
            });
            // get timetable slot by on change department option.
            $(document).on('change', 'select[name="department"]', function () {
                get_options($('select[name="department"] :selected').val());
            });
            // get timetable slot by on change department option.
            $(document).on('change', 'select[name="degree"]', function () {
                get_options($('select[name="department"] :selected').val());
            });
            // get timetable slot by on change academic year option.
            $(document).on('change', 'select[name="academicYear"]', function () {
                get_weeks($('select[name="semester"] :selected').val());
            });
            // get timetable slot by on change option.
            $(document).on('change', 'select[name="option"]', function () {
                get_groups();
            });
            // get timetable slot by on change grade option.
            $(document).on('change', 'select[name="grade"]', function () {
                get_groups();
            });
            // get timetable slot by on change group option.
            $(document).on('change', 'select[name="group"]', function () {
                get_weeks($('select[name="semester"] :selected').val());
            });
            // get timetable slots by on change weekly option.
            $(document).on('change', 'select[name="weekly"]', function (e) {
                e.preventDefault();
                oTable.draw();
            });
            $(document).on('click', '.btn_delete_timetable', function (e) {
                e.preventDefault();
                toggleLoading(true);
                var self = $(this);
                swal({
                    title: 'Are you sure?',
                    text: "Do want to delete this timetable?",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then(function () {
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('admin.schedule.timetables.delete') }}',
                        data: {
                            'id': self.attr('id')
                        },
                        success: function () {
                            swal(
                                'Deleted!',
                                'Your timetable has been deleted.',
                                'success'
                            );
                        },
                        error: function () {
                            swal(
                                'Deleting timetable',
                                'Something went wrong!',
                                'error'
                            );
                        },
                        complete: function () {
                            toggleLoading(false);
                            oTable.draw();
                        }
                    });

                });
                toggleLoading(false);
            });
        })
    </script>

    {{--timetable assignment--}}
    <script type="text/javascript">
        function get_assign_table() {
            toggleLoading(true);
            $.ajax({
                type: 'POST',
                url: '{!! route('get_timetable_assignment') !!}',
                data: {
                    '_token': '{!! csrf_token() !!}'
                },
                success: function (response) {
                    var row = '';
                    $.each(response.departments, function (key, val) {
                        row += '<tr>';
                        row += '<td class="hidden">';
                        row += val.key_id;
                        row += '</td>';
                        row += '<td class="hidden">';
                        row += val.start + ' - ' + val.end;
                        row += '</td>';
                        row += '<td>';
                        row += val.code;
                        row += '</td>';
                        row += '<td>';
                        if (val.description === 'true') {
                            row += '<span class="start_date"> end ' + moment(val.end, 'YYYY-MM-DD').fromNow() + '</span>';
                        } else if (val.description === 'false') {
                            row += '<span class="start_date"> start ' + moment(val.start, 'YYYY-MM-DD').fromNow() + '</span>';
                        } else {
                            row += '<span class="start_date">' + moment(val.end, 'YYYY-MM-DD').fromNow() + '</span>';
                        }
                        row += '</td>';
                        row += '<td>';
                        if (val.description === 'true') {
                            row += '<span class="label label-info">{{ trans('labels.backend.schedule.timetable.status.in_progress') }}</span>';
                        } else if (val.description === 'false') {
                            row += '<span class="label label-danger">{{ trans('labels.backend.schedule.timetable.status.waiting') }}</span>';
                        } else {
                            row += '<span class="label label-success">{{ trans('labels.backend.schedule.timetable.status.finished') }}</span>';
                        }
                        
                        row += '</td>';
                        row += '<td>';
                        row += '<button class="btn btn-primary btn-xs" id="btn_assign_update"><i class="fa fa-clock-o"></i> Re-set</button> ';
                        // row += '<button class="btn btn-danger btn-xs" id="btn_assign_delete"><i class="fa fa-trash"></i></button> ';
                        row += '</td>';
                        row += '</tr>';
                    });
                    $('#display-assign').find('tbody').html(row);
                },
                error: function () {
                    swal(
                        'Get Assignment Table',
                        'Something went wrong!',
                        'error'
                    );
                },
                complete: function () {
                    toggleLoading(false);
                }
            });
        }
        var startDate, endDate, startDateUpdate, endDateUpdate;

        $(function () {
            get_assign_table();
            $('input[name="datetime"]').daterangepicker(
                {
                    format: 'DD/MM/YYYY'
                },
                function (start, end) {
                    startDate = start;
                    endDate = end;
                }
            );

            $('select[name="departments[]"]').select2({
                placeholder: '{{ trans('modals.backend.timetable.assignment_permission.modal_body.form.department.placeholder') }}'
            });

            // click edit timetable assignment
            $(document).on('click', '#btn_assign_update', function () {
                $('#display-assign').find('.info').removeClass();
                $('#display-assign').find('.danger').removeClass();
                var dom = $(this).parent().parent();
                dom.addClass('info');
                $('#modal-update-assign').modal('show');
                $('#modal-update-assign').find('input[name="update-datetime"]').val(dom.children().eq(1).text());
                $('input[name="update-datetime"]').daterangepicker({
                    timeZone: 'Asia/Phnom_Penh',
                    format: 'YYYY-MM-DD'
                }, function (start, end) {
                    startDateUpdate = start;
                    endDateUpdate = end;
                });
            });

            // update timetable assignment
            $(document).on('click', '#btn_form_update_assign', function (e) {
                e.preventDefault();
                toggleLoading(true);
                var start = $('input[name="update-datetime"]').data('daterangepicker').startDate.format('YYYY-MM-DD');
                var end = $('input[name="update-datetime"]').data('daterangepicker').endDate.format('YYYY-MM-DD');
                var configuration_id = $('#display-assign').find('.info').children().eq(0).text();
                $.ajax({
                    type: 'POST',
                    url: '{!! route('assign.update') !!}',
                    data: {
                        configuration_id: configuration_id,
                        start: start,
                        end: end
                    },
                    success: function (response) {
                        $('#modal-update-assign').modal('hide');
                        $('#update-form-assign')[0].reset();

                        if (response.status === true) {
                            get_assign_table();
                            notify('info', 'Timetable assignment have benn updated.', 'Update Timetable Assignment');
                        } else {
                            notify('error', 'Something went wrong.', 'Update Timetable Assignment');
                        }
                    },
                    error: function () {
                        notify('error', 'Something went wrong.', 'Update Timetable Assignment');
                    },
                    complete: function () {
                        toggleLoading(false);
                        get_assign_table();
                    }
                });
            });

            // set permission timetable assignment
            $(document).on('click', '#btn_assign', function (event) {
                event.preventDefault();
                toggleLoading(true);
                var start = $('input[name="datetime"]').data('daterangepicker').startDate.format('YYYY-MM-DD');
                var end = $('input[name="datetime"]').data('daterangepicker').endDate.format('YYYY-MM-DD');
                $.ajax({
                    type: 'POST',
                    url: '{!! route('assign_turn_create_timetable') !!}',
                    data: {
                        start: start,
                        end: end,
                        departments: $('select[name="departments[]"]').val()
                    },
                    success: function (response) {
                        if (response.status === true) {
                            $('#modal-timetable-assignment').modal('toggle');
                            notify('info', response.message, 'Successfully');
                            $('#form-assign').trigger('reset');
                        }
                        if (response.status === false) {
                            notify('error', response.message, 'Oops...');
                        }
                    },
                    error: function () {
                        notify('error', 'Something went wrong.', 'Oops...');
                    },
                    complete: function () {
                        toggleLoading(false);
                        $('#form-assign').trigger('reset');
                        get_assign_table();
                    }
                });
            });

            // when click on delete button
            $(document).on('click', '#btn_assign_delete', function () {
                $('#display-assign').find('.danger').removeClass();
                $('#display-assign').find('.info').removeClass();
                var dom = $(this).parent().parent();
                dom.addClass('danger');

                swal({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then(function () {
                    toggleLoading(true);
                    $.ajax({
                        type: 'POST',
                        url: '{!! route('assign.delete') !!}',
                        data: {
                            id: dom.children().eq(0).text()
                        },
                        success: function (response) {
                            if (response.status === true) {
                                get_assign_table();
                            }
                        },
                        error: function () {

                        },
                        complete: function () {
                            toggleLoading(false);
                            swal("Deleted!", "The record has been deleted.", "success");
                        }
                    });
                });
            });

            $('#options-filter').on('change', function (event) {
                event.preventDefault();
                get_rest_course_sessions();
            });
        });
    </script>
@stop
