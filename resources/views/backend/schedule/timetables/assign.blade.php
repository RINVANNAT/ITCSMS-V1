@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.schedule.timetable.meta_title'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.schedule.timetable.title') }}
        <small>Timetable Assignment</small>
    </h1>

@endsection

@section('after-styles-end')

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
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Create Timetable Assignment: <span class="label label-primary label-lg">#2016-2017</span>
                    </h3>
                    <div class="box-tools">
                        <div class="pull-right">
                            <button class="btn btn-primary btn-sm"
                                    data-toggle="modal"
                                    data-target="#settings-create-timetable">
                                <i class="fa fa-plus-circle"></i> Assign Permission
                            </button>

                            <a href="{{ route('admin.schedule.timetables.index') }}"
                               class="btn btn-default btn-sm"> Back
                            </a>
                        </div>
                    </div>
                </div>

                <div class="box-body">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Department</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Duration</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>GGG</td>
                            <td>{{ (new \Carbon\Carbon('2017-05-10 00:00:00'))->toFormattedDateString() }}</td>
                            <td>{{ (new \Carbon\Carbon('2017-05-10 00:00:00'))->toFormattedDateString() }}</td>
                            <td>{{ (new \Carbon\Carbon('2017-05-10 00:00:00'))->diffForHumans() }}</td>
                            <td>
                                <button class="btn btn-success btn-xs"><i class="fa fa-check-circle"></i></button>
                            </td>
                            <td>
                                <button class="btn btn-primary btn-xs"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>GGG</td>
                            <td>{{ (new \Carbon\Carbon('2017-05-10 00:00:00'))->toFormattedDateString() }}</td>
                            <td>{{ (new \Carbon\Carbon('2017-05-10 00:00:00'))->toFormattedDateString() }}</td>
                            <td>{{ (new \Carbon\Carbon('2017-05-10 00:00:00'))->diffForHumans() }}</td>
                            <td>
                                <button class="btn btn-danger btn-xs"><i class="fa fa-stop"></i></button>
                            </td>
                            <td>
                                <button class="btn btn-primary btn-xs"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>GCA</td>
                            <td>{{ (new \Carbon\Carbon('2017-05-10 00:00:00'))->toFormattedDateString() }}</td>
                            <td>{{ (new \Carbon\Carbon('2017-05-10 00:00:00'))->toFormattedDateString() }}</td>
                            <td>{{ (new \Carbon\Carbon('2017-05-10 00:00:00'))->diffForHumans() }}</td>
                            <td>
                                <button class="btn btn-info btn-xs"><i class="fa fa-refresh fa-pulse fa-1x"></i>
                                </button>
                            </td>
                            <td>
                                <button class="btn btn-primary btn-xs"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>GIC</td>
                            <td>{{ (\Carbon\Carbon::now())->toFormattedDateString() }}</td>
                            <td>{{ (\Carbon\Carbon::now())->toFormattedDateString() }}</td>
                            <td>{{ (new \Carbon\Carbon('2017-05-10 00:00:00'))->diffForHumans() }}</td>
                            <td>
                                <button class="btn btn-danger btn-xs"><i class="fa fa-stop"></i></button>
                            </td>
                            <td>
                                <button class="btn btn-primary btn-xs"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
    @include('backend.schedule.timetables.includes.modals.assign')
@stop

@section('after-scripts-end')

    {!! Html::script('plugins/sweetalert2/dist/sweetalert2.js') !!}
    {!! Html::script('plugins/select2/select2.full.min.js') !!}
    {!! Html::script('plugins/moment/moment.min.js') !!}
    {!! Html::script('plugins/datetimepicker/bootstrap-datetimepicker.min.js') !!}
    {!! Html::script('js/backend/schedule/timetable.js') !!}

    <script type="text/javascript">
        $(function () {
            $('#start').datetimepicker();
            $('#end').datetimepicker();

            $('select[name="departments[]"]').select2({
                placeholder: 'Chose Department'
            });

            $(document).on('click', '#btn_assign', function (event) {
                event.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '{!! route('assign_turn_create_timetable') !!}',
                    data: $('form[id="assign"]').serialize(),
                    success: function (response) {
                        if(response.status === true){
                            notify('info', response.message, 'Successfully');
                        }
                        if(response.status === false){
                            notify('error', response.message, 'Oops...');
                        }
                    },
                    error: function () {
                        notify('error', 'Something went wrong.', 'Oops...');
                    },
                    complete: function () {

                    }
                })
            });
        });
    </script>
@stop
