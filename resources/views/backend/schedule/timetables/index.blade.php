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
    {!! Html::style('plugins/datetimepicker/bootstrap-datetimepicker.min.css') !!}
    {!! Html::style('css/backend/schedule/timetable.css') !!}

    <style>
        .toolbar {
            float: left;
        }
    </style>
@stop

@section('content')

    <div class="row">
        <div class="col-md-4">
            @include('backend.schedule.timetables.includes.partials.timetable-assignment')
        </div>
        <div class="col-md-8">
            @include('backend.schedule.timetables.includes.partials.timetable-viewer')
        </div>
    </div>

    {{--modal timetable assignment--}}
    @include('backend.schedule.timetables.includes.modals.assign')
@stop

@section('after-scripts-end')

    {!! Html::script('plugins/datatables/jquery.dataTables.min.js') !!}
    {!! Html::script('plugins/datatables/dataTables.bootstrap.min.js') !!}
    {!! Html::script('plugins/sweetalert2/dist/sweetalert2.js') !!}
    {!! Html::script('plugins/select2/select2.full.min.js') !!}
    {!! Html::script('plugins/moment/moment.min.js') !!}
    {!! Html::script('plugins/datetimepicker/bootstrap-datetimepicker.min.js') !!}
    {!! Html::script('js/backend/schedule/timetable.js') !!}

    {{--timetable management--}}
    <script type="text/javascript">
        $(function () {
            $('#timetables-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{!! route('admin.schedule.timetables.get_timetables') !!}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    }
                },
                columns: [
                    {data: 'academic_year', name: 'academic_year', searchable: true},
                    {data: 'department', name: 'department', searchable: true},
                    {data: 'degree', name: 'degree', searchable: true},
                    {data: 'grade', name: 'grade', searchable: true},
                    {data: 'option', name: 'option', searchable: true},
                    {data: 'semester', name: 'semester', searchable: true},
                    {data: 'group', name: 'group', searchable: true},
                    {data: 'weekly', name: 'weekly', searchable: true},
                    {data: 'status', name: 'status', searchable: false, orderable: false},
                        @if(access()->allow('view-timetable') || access()->allow('delete-timetable') || access()->allow('edit-timetable'))
                    {
                        data: 'action', name: 'action', searchable: false, orderable: false
                    }
                    @endif
                ],
                initComplete: function () {
                    var td_level = 0;
                    this.api().columns().every(function () {
                        var column = this;
                        var select = '';
                        if (td_level === 0) {
                            select = '<select class="form-control">';
                            select += '<option selected disabled>{{ trans('labels.backend.schedule.timetable.table.academic_year') }}</option>';
                            @foreach($academicYears as $academicYear)
                                select += '<option>{!! $academicYear->name_latin !!}</option>';
                            @endforeach
                                select += '</select>';
                        }
                        else if (td_level === 1) {
                            select = '<select class="form-control">';
                            select += '<option selected disabled>{{ trans('labels.backend.schedule.timetable.table.department') }}</option>';
                            @foreach($departments as $department)
                                select += "<option>" + "{!! $department->code !!}" + "</option>";
                            @endforeach
                                select += '</select>';
                        }

                        else if (td_level === 2) {
                            select = '<select class="form-control">';
                            select += '<option selected disabled>{{ trans('labels.backend.schedule.timetable.table.degree') }}</option>';
                            @foreach($degrees as $degree)
                                select += "<option>" + "{!! $degree->name_en !!}" + "</option>";
                            @endforeach
                                select += '</select>';
                        }

                        else if (td_level === 3) {
                            select = '<select class="form-control">';
                            select += '<option selected disabled>{{ trans('labels.backend.schedule.timetable.table.grade') }}</option>';
                            @foreach($grades as $grade)
                                select += "<option>" + "{!! $grade->code !!}" + "</option>";
                            @endforeach
                                select += '</select>';
                        }

                        else if (td_level === 5) {
                            select = '<select class="form-control">';
                            select += '<option selected disabled>{{ trans('labels.backend.schedule.timetable.table.semester') }}</option>';
                            @foreach($semesters as $semester)
                                select += "<option>" + "{!! $semester->name_en !!}" + "</option>";
                            @endforeach
                                select += '</select>';
                        }
                        else if (td_level === 7) {
                            select = '<select class="form-control">';
                            select += '<option selected disabled>{{ trans('labels.backend.schedule.timetable.table.week') }}</option>';
                            @foreach($weeks as $week)
                                select += "<option>" + "{!! $week->name_en !!}" + "</option>";
                            @endforeach
                                select += '</select>';
                        }

                        $(select).appendTo($(column.footer()).empty())
                            .on('change', function () {
                                column.search($(this).val(), false, false, true).draw();
                            });
                        td_level++;
                    });
                }
            });

            $('#filter-timetable-view').on('change', function (e) {
                e.preventDefault();

                $.ajax({
                    type: 'POST',
                    url: '/admin/schedule/timetables/filter',
                    data: $('#filter-timetable-view').serialize(),
                    success: function (response) {
                        // console.log(response);
                    },
                    error: function () {
                        swal(
                            'Oops...',
                            'Something went wrong!',
                            'error'
                        );
                    }
                });

            });
        })
    </script>

    {{--timetable assignment--}}
    <script type="text/javascript">
        var get_timetable_assignment = $('#display-assign').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{!! route('get_timetable_assignment') !!}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    }
                },
                columns: [
                    {data: 'code', name: 'code', searchable: true},
                    {data: 'start', name: 'start', searchable: false, orderable: false},
                    {data: 'end', name: 'end', searchable: false, orderable: false},
                    {data: 'status', name: 'status', searchable: false, orderable: false},
                    {data: 'action', name: 'action', searchable: false, orderable: false}
                ]
            });

        $(function () {
            get_timetable_assignment.draw();

            $('#start').datetimepicker({
                format: 'YYYY-MM-DD hh:mm:ss'
            });
            $('#end').datetimepicker({
                format: 'YYYY-MM-DD hh:mm:ss'
            });

            $('select[name="departments[]"]').select2({
                placeholder: 'Chose Department'
            });

            $(document).on('click', '#btn_assign', function (event) {
                event.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '{!! route('assign_turn_create_timetable') !!}',
                    data: $('form[id="form-assign"]').serialize(),
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
                        get_timetable_assignment.draw();
                    }
                })
            });
        });
    </script>

@stop
