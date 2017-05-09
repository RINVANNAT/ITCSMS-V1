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
    {!! Html::style('plugins/sweetalert2/dist/sweetalert2.css') !!}
    <style>
        .toolbar {
            float: left;
        }
    </style>
@stop

@section('content')

    <div class="box box-success">
        <div class="box-header with-border">
            <div class="mailbox-controls">
                @permission('create-timetable')
                <div class="pull-left">
                    <a href="{{ route('admin.schedule.timetables.create') }}">
                        <button class="btn btn-primary btn-sm" data-toggle="tooltip"
                                data-placement="top" title="Create a new timetable"
                                data-original-title="Create a new timetable">
                            <i class="fa fa-plus-circle"
                            ></i>
                            {{ trans('buttons.backend.schedule.timetable.create') }}
                        </button>
                    </a>
                </div>
                @endauth
            </div>
        </div>

        <div class="box-body">
            <div class="tables-responsive">
                <table class="table table-striped table-bordered table-hover dt-responsive nowrap"
                       id="timetables-table">
                    <thead>
                    <tr>
                        <th>{{ trans('labels.backend.schedule.timetable.table.academic_year') }}</th>
                        <th>{{ trans('labels.backend.schedule.timetable.table.department') }}</th>
                        <th>{{ trans('labels.backend.schedule.timetable.table.degree') }}</th>
                        <th>{{ trans('labels.backend.schedule.timetable.table.grade') }}</th>
                        <th>{{ trans('labels.backend.schedule.timetable.table.option') }}</th>
                        <th>{{ trans('labels.backend.schedule.timetable.table.semester') }}</th>
                        <th>{{ trans('labels.backend.schedule.timetable.table.group') }}</th>
                        <th>{{ trans('labels.backend.schedule.timetable.table.week') }}</th>
                        <th>{{ trans('labels.backend.schedule.timetable.table.status') }}</th>
                        @if(access()->allow('view-timetable') || access()->allow('delete-timetable'))
                            <th>{{ trans('labels.general.actions') }}</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    </tfoot>
                </table>
            </div>

            <div class="clearfix"></div>
        </div>
    </div>

@stop

@section('after-scripts-end')

    {!! Html::script('plugins/datatables/jquery.dataTables.min.js') !!}
    {!! Html::script('plugins/datatables/dataTables.bootstrap.min.js') !!}
    {!! Html::script('plugins/sweetalert2/dist/sweetalert2.js') !!}
    {!! Html::script('js/backend/schedule/timetable.js') !!}

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
                    {data: 'action', name: 'action', searchable: false, orderable: false}
                ],
                initComplete: function () {
                    var td_level = 0;
                    this.api().columns().every(function () {
                        var column = this;
                        var select = '';
                        if (td_level == 0) {
                            select = '<select class="form-control">';
                            select += '<option selected disabled>{{ trans('labels.backend.schedule.timetable.table.academic_year') }}</option>';
                            @foreach($academicYears as $academicYear)
                                select += '<option>{!! $academicYear->name_latin !!}</option>';
                            @endforeach
                                select += '</select>';
                        }
                        else if (td_level == 1) {
                            select = '<select class="form-control">';
                            select += '<option selected disabled>{{ trans('labels.backend.schedule.timetable.table.department') }}</option>';
                            @foreach($departments as $department)
                                select += "<option>" + "{!! $department->code !!}" + "</option>";
                            @endforeach
                                select += '</select>';
                        }

                        else if (td_level == 2) {
                            select = '<select class="form-control">';
                            select += '<option selected disabled>{{ trans('labels.backend.schedule.timetable.table.degree') }}</option>';
                            @foreach($degrees as $degree)
                                select += "<option>" + "{!! $degree->name_en !!}" + "</option>";
                            @endforeach
                                select += '</select>';
                        }

                        else if (td_level == 3) {
                            select = '<select class="form-control">';
                            select += '<option selected disabled>{{ trans('labels.backend.schedule.timetable.table.grade') }}</option>';
                            @foreach($grades as $grade)
                                select += "<option>" + "{!! $grade->code !!}" + "</option>";
                            @endforeach
                                select += '</select>';
                        }

                        else if (td_level == 5) {
                            select = '<select class="form-control">';
                            select += '<option selected disabled>{{ trans('labels.backend.schedule.timetable.table.semester') }}</option>';
                            @foreach($semesters as $semester)
                                select += "<option>" + "{!! $semester->name_en !!}" + "</option>";
                            @endforeach
                                select += '</select>';
                        }
                        else if (td_level == 7) {
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

@stop
