@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.schedule.timetable.meta_title'))

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
    {!! Html::style('css/backend/schedule/timetable.css') !!}

    <style>
        .toolbar {
            float: left;
        }
    </style>
@stop

@section('content')

    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">Timetable Management</h3>
            <div class="box-tools pull-right">
                @if(isset($createTimetablePermissionConfiguration))
                    @if(((strtotime($now) >= strtotime($createTimetablePermissionConfiguration->created_at) && strtotime($now) <= strtotime($createTimetablePermissionConfiguration->updated_at)) && access()->allow('create-timetable')))
                        <a class="btn btn-primary btn-sm"
                           data-toggle="tooltip"
                           data-placement="top" title="Create a new timetable"
                           data-original-title="Create a new timetable"
                           href="{{ route('admin.schedule.timetables.create') }}">
                            <i class="fa fa-plus-circle"></i>
                            {{ trans('buttons.backend.schedule.timetable.create') }}
                        </a>
                    @endif
                @else
                    <a class="btn btn-primary btn-sm"
                       data-toggle="tooltip"
                       data-placement="top" title="Create a new timetable"
                       data-original-title="Create a new timetable"
                       href="{{ route('admin.schedule.timetables.create') }}">
                        <i class="fa fa-plus-circle"></i>
                        {{ trans('buttons.backend.schedule.timetable.create') }}
                    </a>
                @endif
                @permission('create-timetable-assignment')
                <button class="btn btn-primary btn-sm"
                        data-toggle="modal"
                        data-target="#modal-timetable-assignment">
                    <i class="fa fa-plus-circle"></i> Assign Permission
                </button>
                @endauth
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
                <div class="col-md-12">
                    @include('backend.schedule.timetables.includes.partials.timetable-viewer')
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

    {{--timetable management--}}
    <script type="text/javascript">

        var oTable = $('#timetables-tables').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{!! route('admin.schedule.timetables.get_timetables') !!}',
                method: 'POST',
                data: {
                    'academicYear': $('select[name="academicYear"] :selected').val(),
                    'department': $('select[name="department"] :selected').val(),
                    'option': $('select[name="option"] :selected').val(),
                    'degree': $('select[name="degree"] :selected').val(),
                    'grade': $('select[name="grade"] :selected').val(),
                    'semester': $('select[name="semester"] :selected').val(),
                    'weekly': $('select[name="weekly"] :selected').val(),
                    'group': $('select[name="group"] :selected').val(),
                    '_token': '{!! csrf_token() !!}'
                }
            },
            columns: [
                /*{data: 'academic_year', name: 'academic_year', searchable: true},
                 {data: 'department', name: 'department', searchable: true},
                 {data: 'degree', name: 'degree', searchable: true},
                 {data: 'grade', name: 'grade', searchable: true},
                 {data: 'option', name: 'option', searchable: true},
                 {data: 'semester', name: 'semester', searchable: true},*/
                /*{data: 'group', name: 'group', searchable: true},*/
                {data: 'weekly', name: 'weekly', searchable: true},
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
                    // oTableTimetables.draw();

                    toggleLoading(false);
                }
            });
        }

        $(function () {

            get_options($('select[name="department"] :selected').val());
            {{--var oTableTimetables = $('#timetables-tables').DataTable({--}}
                {{--processing: true,--}}
                {{--serverSide: true,--}}
                {{--ajax: {--}}
                    {{--url: '{!! route('admin.schedule.timetables.get_timetables') !!}',--}}
                    {{--method: 'POST',--}}
                    {{--data: {--}}
                        {{--'academicYear': $('select[name="academicYear"] :selected').val(),--}}
                        {{--'department': $('select[name="department"] :selected').val(),--}}
                        {{--'option': $('select[name="option"] :selected').val(),--}}
                        {{--'degree': $('select[name="degree"] :selected').val(),--}}
                        {{--'grade': $('select[name="grade"] :selected').val(),--}}
                        {{--'semester': $('select[name="semester"] :selected').val(),--}}
                        {{--'weekly': $('select[name="weekly"] :selected').val(),--}}
                        {{--'group': $('select[name="group"] :selected').val(),--}}
                        {{--'_token': '{!! csrf_token() !!}'--}}
                    {{--}--}}
                {{--},--}}
                {{--columns: [--}}
                    {{--{data: 'academic_year', name: 'academic_year', searchable: true},--}}
                    {{--{data: 'department', name: 'department', searchable: true},--}}
                    {{--{data: 'degree', name: 'degree', searchable: true},--}}
                    {{--{data: 'grade', name: 'grade', searchable: true},--}}
                    {{--{data: 'option', name: 'option', searchable: true},--}}
                    {{--{data: 'semester', name: 'semester', searchable: true},--}}
                    {{--{data: 'group', name: 'group', searchable: true},--}}
                    {{--{data: 'weekly', name: 'weekly', searchable: true},--}}
                    {{--{data: 'status', name: 'status', searchable: false, orderable: false},--}}
                        {{--@if(access()->allow('view-timetable') || access()->allow('delete-timetable') || access()->allow('edit-timetable'))--}}
                    {{--{--}}
                        {{--data: 'action', name: 'action', searchable: false, orderable: false--}}
                    {{--}--}}
                    {{--@endif--}}
                {{--]--}}
            {{--});--}}



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
            $(document).on('change', 'select[name="weekly"]', function () {
                // oTableTimetables.draw();
            });
        })
    </script>

    {{--timetable assignment--}}
    <script type="text/javascript">
        function get_assign_table() {
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
                        row += '<td>';
                        row += val.code;
                        row += '</td>';
                        row += '<td>';
                        row += '<span class="start_date"> ' + val.start + '</span> - <span class="start_date">' + val.end + '</span>';
                        row += '</td>';
                        row += '<td>';
                        if (val.description === 'true') {
                            row += '<span class="label label-info">IN PROGRESS</span>';
                        } else if (val.description === 'false') {
                            row += '<span class="label label-danger">WAITING</span>';
                        } else {
                            row += '<span class="label label-success">FINISHED</span>';
                        }
                        row += '</td>';
                        row += '<td>';
                        row += '<button class="btn btn-primary btn-xs" id="btn_assign_update"><i class="fa fa-edit"></i></button> ';
                        row += '<button class="btn btn-danger btn-xs" id="btn_assign_delete"><i class="fa fa-trash"></i></button> ';
                        row += '</td>';
                        row += '</tr>';
                    });
                    $('#display-assign').find('tbody').html(row);
                },
                error: function () {

                },
                complete: function () {

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
                placeholder: 'Chose Department'
            });

            $(document).on('click', '#btn_assign_update', function () {
                $('#display-assign').find('.info').removeClass();
                var dom = $(this).parent().parent();
                dom.addClass('info');
                $('#modal-update-assign').modal('show');
                var startDate = dom.children().eq(2).find('.start_date').text();
                var endDate = dom.children().eq(2).find('.end_date').text();
                $('#modal-update-assign').find('input[name="update-datetime"]').val(startDate + endDate);
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
                var configuration_id = $('#display-assign').find('.info').children().eq(0).text();
                $.ajax({
                    type: 'POST',
                    url: '{!! route('assign.update') !!}',
                    data: {
                        configuration_id: configuration_id,
                        start: startDateUpdate.format('YYYY-MM-DD'),
                        end: endDateUpdate.format('YYYY-MM-DD')
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
                        get_assign_table();
                    }
                });
            });

            // set permission timetable assignment
            $(document).on('click', '#btn_assign', function (event) {
                event.preventDefault();
                var start = $('input[name="datetime"]').data('daterangepicker').startDate.format('YYYY-MM-DD');
                var end = $('input[name="datetime"]').data('daterangepicker').startDate.format('YYYY-MM-DD');
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
                        $('#form-assign').trigger('reset');
                        get_assign_table();
                    }
                });
            });

            // when click on delete button
            $(document).on('click', '#btn_assign_delete', function () {
                $('#display-assign').find('.danger').removeClass();
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
                            swal("Deleted!", "The record has been deleted.", "success");
                        }
                    });
                });
            });
        });
    </script>
@stop
