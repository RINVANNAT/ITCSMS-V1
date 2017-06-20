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
    {!! Html::style('plugins/jQueryUI/jquery-ui.css') !!}
    {!! Html::style('plugins/fullcalendar/fullcalendar.css') !!}
    {!! Html::style('plugins/sweetalert2/dist/sweetalert2.css') !!}
    {!! Html::style('plugins/iCheck/all.css') !!}
    {!! Html::style('css/backend/schedule/timetable.css') !!}

@stop

@section('content')
    <div class="box box-success">
        <div class="box-header with-border">
            <div class="mailbox-controls">
                <div class="pull-right">
                    @if(isset($timetable) && $timetable->completed == true)
                        @permission('clone-timetable')
                        <button class="btn btn-success btn-sm"
                                data-toggle="modal"
                                data-target="#clone-timetable"
                                data-toggle="tooltip"
                                data-placement="top"
                                title="{{ trans('buttons.backend.schedule.timetable.clone') }}">
                            {{ trans('buttons.backend.schedule.timetable.clone') }}
                        </button>
                        @endauth
                    @else
                        @permission('publish-timetable')
                        <a href="#">
                            <button class="btn btn-info btn-sm"
                                    data-toggle="tooltip"
                                    data-placement="top"
                                    title="{{ trans('buttons.backend.schedule.timetable.publish') }}">
                                {{ trans('buttons.backend.schedule.timetable.publish') }}
                            </button>
                        </a>
                        @endauth
                    @endif
                </div>

                @if(isset($createTimetablePermissionConfiguration))
                    @if(((strtotime($now) >= strtotime($createTimetablePermissionConfiguration->created_at) && strtotime($now) <= strtotime($createTimetablePermissionConfiguration->updated_at)) && access()->allow('create-timetable')))
                        <div class="pull-left">
                            <a class="btn btn-primary btn-sm"
                               data-toggle="tooltip"
                               data-placement="top" title="Create a new timetable"
                               data-original-title="Create a new timetable"
                               href="{{ route('admin.schedule.timetables.create') }}">
                                <i class="fa fa-plus-circle"></i>
                                {{ trans('buttons.backend.schedule.timetable.create') }}
                            </a>
                        </div>
                    @endif
                @else
                    <div class="pull-left">
                        <a class="btn btn-primary btn-sm"
                           data-toggle="tooltip"
                           data-placement="top" title="Create a new timetable"
                           data-original-title="Create a new timetable"
                           href="{{ route('admin.schedule.timetables.create') }}">
                            <i class="fa fa-plus-circle"></i>
                            {{ trans('buttons.backend.schedule.timetable.create') }}
                        </a>
                    </div>
                @endif

            </div>
        </div>

        <div class="box-body">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    {{--Timetable render--}}
                    <div id="timetable"></div>
                </div>
            </div>

            <div class="clearfix"></div>
        </div>
    </div>

    @include('backend.schedule.timetables.includes.modals.clone')

@stop

@section('after-scripts-end')

    {!! Html::script('plugins/iCheck/icheck.js') !!}
    {!! Html::script('plugins/datatables/jquery.dataTables.min.js') !!}
    {!! Html::script('plugins/datatables/dataTables.bootstrap.min.js') !!}
    {!! Html::script('plugins/moment/moment.min.js') !!}
    {!! Html::script('plugins/fullcalendar/fullcalendar.js') !!}
    {!! Html::script('plugins/sweetalert2/dist/sweetalert2.js') !!}
    {!! Html::script('js/backend/schedule/timetable.js') !!}
    {!! Html::script('js/backend/schedule/clone-timetable.js') !!}
    {!! Html::script('js/backend/schedule/timetable.js') !!}

    <script type="text/javascript">
        function get_timetable() {
            var date = new Date();
            var d = date.getDate(),
                m = date.getMonth(),
                y = date.getFullYear();
            $('#timetable').fullCalendar({
                defaultView: 'timetable',
                defaultDate: '2017-01-01',
                header: false,
                footer: false,
                views: {
                    timetable: {
                        type: 'agendaWeek',
                        setHeight: '100px'
                    }
                },
                allDaySlot: false,
                hiddenDays: [0],
                height: 650,
                fixedWeekCount: false,
                minTime: '07:00:00',
                maxTime: '20:00:00',
                slotLabelFormat: 'h:mm a',
                columnFormat: 'dddd',
                dragRevertDuration: 0,
                events: {!! $timetableSlots !!},
                eventRender: function (event, element, view) {
                    var object = '<a class="fc-time-grid-event fc-v-event fc-event fc-start fc-end course-item  fc-draggable fc-resizable" style="top: 65px; bottom: -153px; z-index: 1; left: 0%; right: 0%;">' +
                        '<div class="fc-content">' +
                        '<div class="container-room">' +
                        '<div class="side-course" id="' + event.id + '">';
                    if (event.is_conflict_course === true) {
                        object += '<div class="fc-title conflict">' + event.course_name + '</div>';
                    } else {
                        object += '<div class="fc-title">' + event.course_name + '</div>';
                    }
                    if (event.is_conflict_lecturer === true) {
                        object += '<p class="text-primary conflict">' + event.teacher_name + '</p> ';
                    } else {
                        object += '<p class="text-primary">' + event.teacher_name + '</p> ';
                    }
                    object += '<p class="text-primary">' + event.type + '</p> ' +
                        '</div>' +
                        '<div class="side-room">' +
                        '<div class="room-name">';
                    if (event.room !== null) {
                        if (event.is_conflict_room === true) {
                            object += '<p class="fc-room conflict">' + event.building + '-' + event.room + '</p>';
                        } else {
                            object += '<p class="fc-room">' + event.building + '-' + event.room + '</p>';
                        }
                    }
                    object += '</div> ' +
                        '</div> ' +
                        '<div class="clearfix"></div> ' +
                        '</div>' +
                        '</div>' +
                        '<div class="fc-bgd"></div>' +
                        '<div class="fc-resizer fc-end-resizer"></div>' +
                        '</a>';

                    return $(object);
                }
            });
        }

        $(function () {
            get_timetable();
        });
    </script>
@stop
