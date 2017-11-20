@extends ('backend.layouts.master')

@section ('title', 'ITC | TTMS')

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

    <style type="text/css">
        .lang-info {
            margin: 0;
            padding: 13px;
            width: 100%;
            position: relative;
        }

        .lang-info-left, .lang-info-right {
            width: 50%;
        }

        .lang-info-left {
            float: left;
        }

        .lang-info-right {
            float: right;
        }
    </style>

@stop

@section('content')

    <div class="box box-success">
        <div class="box-header with-border">
            <div class="mailbox-controls">

                @if(isset($createTimetablePermissionConfiguration))
                    @if(((strtotime($now) >= strtotime($createTimetablePermissionConfiguration->created_at) && strtotime($now) <= strtotime($createTimetablePermissionConfiguration->updated_at)) && access()->allow('create-timetable')))
                        <div class="pull-left">
                            <a class="btn btn-primary btn-sm"
                               data-toggle="tooltip"
                               data-placement="top" title="Create a new timetable"
                               data-original-title="Create a new timetable"
                               href="{{ route('admin.schedule.timetables.create') }}">
                                <i class="fa fa-plus-circle"></i>
                                Manage Timetable
                            </a>
                        </div>
                    @endif
                @else
                    <div class="pull-left">
                        <a class="btn btn-primary btn-sm"
                           data-toggle="tooltip"
                           data-placement="top"
                           title="Manage timetable"
                           data-original-title="Manage timetable"
                           href="{{ route('admin.schedule.timetables.create', $options) }}">
                            <i class="fa fa-plus-circle"></i>
                            Manage Timetable
                        </a>
                    </div>
                @endif

            </div>
        </div>

        <div class="box-body">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    {{--Timetable render--}}
                    <div id="timetable" class="view-timetable"></div>
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
                eventConstraint: {
                    start: '07:00:00',
                    end: '20:00:00'
                },
                slotLabelFormat: 'h:mm a',
                columnFormat: 'dddd',
                dragRevertDuration: 0,
                events: {!! $timetableSlots !!},
                eventRender: function (event, element, view) {
                    set_background_color_slot_not_allow();
                    var object = '<a class="fc-time-grid-event fc-v-event fc-event fc-start fc-end course-item  fc-draggable fc-resizable" style="top: 65px; bottom: -153px; z-index: 1; left: 0%; right: 0%;">' +
                        '<div class="fc-content">' +
                        '<div class="container-room">';
                    if (typeof event.slotsForLanguage !== 'undefined') {
                        object += '<div class="side-courses" id="' + event.id + '" style="width: 100% !important;padding: 2px;" ​​​>';
                        // check conflict room and render
                        object += '<div class="row"> <div class="col-md-12"><div class="fc-title">' + event.course_name + '</div></div>';
                        event.editable = false;
                        object += '<div class="lang-info">';
                        for (var i = 0; i < event.slotsForLanguage.length; i++) {
                            if (i % 2 !== 0) {
                                object += '<div class="lang-info-left"> Gr: ' + event.slotsForLanguage[i].group + ' (' + event.slotsForLanguage[i].building + '-' + event.slotsForLanguage[i].room + ')</div>';
                            } else {
                                object += '<div class="lang-info-right"> Gr: ' + event.slotsForLanguage[i].group + ' (' + event.slotsForLanguage[i].building + '-' + event.slotsForLanguage[i].room + ')</div>';
                            }
                        }
                        object += '</div></div></div>';
                    } else {
                        object += '<div class="side-course" id="' + event.id + '"​​​>';

                        // check conflict room and render
                        object += '<div class="fc-title">' + event.course_name + '</div>';

                        // check conflict lecturer and render
                        if (typeof event.conflict_lecturer !== 'undefined') {
                            if (event.conflict_lecturer.canMerge.length > 0 || event.conflict_lecturer.canNotMerge.length > 0) {
                                object += '<p class="text-primary conflict">' + event.teacher_name + '</p> ';
                            }
                            else {
                                object += '<p class="text-primary">' + event.teacher_name + '</p> ';
                            }
                        }
                        else {
                            object += '<p class="text-primary">' + event.teacher_name + '</p> ';
                        }


                        if (typeof event.type !== 'undefined') {
                            object += '<p class="text-primary">' + event.type + '</p> ';
                        }
                        object += '</div>';
                    }

                    if (typeof event.slotsForLanguage === 'undefined') {
                        object += '<div class="side-room">' +
                            '<div class="room-name">';

                        // check conflict and render room
                        if (event.room !== null && event.building !== null) {
                            if (event.conflict_room === true) {
                                object += '<p class="fc-room bg-danger badge">' + event.building + '-' + event.room + '</p>';
                            } else {
                                object += '<p class="fc-room">' + event.building + '-' + event.room + '</p>';
                            }
                        }
                        object += '</div>';

                        // render groups
                        if (typeof event.groups !== 'undefined') {
                            if (event.groups.length > 0) {
                                var groups = '<p>Gr: ';
                                for (var i = 0; i < event.groups.length; i++) {
                                    if (event.groups[i] !== null) {
                                        groups += event.groups[i].code + ' ';
                                    }
                                    else {
                                        groups = '';
                                    }
                                }
                                groups += '</p>';
                            }
                            object += groups;
                        }
                        object += '</div> ';
                    }
                    object += '<div class="clearfix"></div> ' +
                        '</div>' +
                        '</div>' +
                        '<div class="fc-bgd"></div>' +
                        '<div class="fc-resizer fc-end-resizer"></div>' +
                        '</a>';
                    return $(object);
                },
                loading: function (isLoading, view) {
                    toggleLoading(isLoading);
                },
                eventAfterAllRender: function (view) {
                    toggleLoading(false);
                }
            });
        }

        $(function () {
            get_timetable();
        });
    </script>
@stop
