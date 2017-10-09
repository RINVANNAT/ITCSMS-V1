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
    {!! Html::style('plugins/toastr/toastr.min.css') !!}
    {!! Html::style('css/backend/schedule/timetable.css') !!}

    <style type="text/css">
        .bg-primary {
            background-color: #337ab7 !important;
        }

        .bg-danger {
            background-color: #dd4b39 !important;
            color: #fff;
        }

        .bg-red {
            background-color: #dd4b39 !important;
        }
    </style>

@stop

@section('content')
    <div class="box box-success">
        <div class="box-header with-border">
            <div class="mailbox-controls">
                <div class="pull-right">
                    @permission('generate-timetable')
                    <a href="#">
                        <button class="btn btn-primary btn-sm"
                                data-placement="right"
                                title="Tooltip on top"
                                disabled="true">
                            {{ trans('buttons.backend.schedule.timetable.generate') }}
                        </button>
                    </a>
                    @endauth

                    @permission('clone-timetable')
                    <button class="btn btn-success btn-sm btn_clone_timetable"
                            data-toggle="modal"
                            data-target="#clone-timetable"
                            data-toggle="tooltip"
                            data-placement="top"
                            id="btn_clone"
                            title="{{ trans('buttons.backend.schedule.timetable.clone') }}">
                        {{ trans('buttons.backend.schedule.timetable.clone') }}
                    </button>
                    @endauth

                    @permission('publish-timetable')
                    <a href="#">
                        <button class="btn btn-info btn-sm"
                                data-toggle="tooltip"
                                data-placement="top"
                                id="btn_publish"
                                title="{{ trans('buttons.backend.schedule.timetable.publish') }}">
                            {{ trans('buttons.backend.schedule.timetable.publish') }}
                        </button>
                    </a>
                    @endauth

                    @permission('save-change-timetable')
                    <a href="#">
                        <button class="btn btn-danger btn-sm"
                                data-toggle="tooltip"
                                data-placement="top"
                                title="{{ trans('buttons.backend.schedule.timetable.save_change') }}">
                            {{ trans('buttons.backend.schedule.timetable.save_change') }}
                        </button>
                    </a>
                    @endauth
                </div>

                <form name="options-filter"
                      id="options-filter"
                      method="POST"
                      action="{{ route('admin.schedule.timetables.filter') }}">
                    @include('backend.schedule.timetables.includes.partials.option')
                </form>

            </div>
        </div>

        <div class="box-body">
            <div class="row">
                <div class="col-md-9 col-sm-12 col-xs-12">
                    <div id="timetable" class="view-timetable"></div>
                </div>
                <div class="col-md-3 col-sm-12 col-xs-12">

                    @include('backend.schedule.timetables.includes.partials.courses-sessions')

                    @include('backend.schedule.timetables.includes.partials.rooms')

                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="panel-conflict box box-danger" id="conflict" style="display: none;"></div>
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
    {!! Html::script('plugins/toastr/toastr.min.js') !!}
    {!! Html::script('js/backend/schedule/clone-timetable.js') !!}
    {!! Html::script('js/backend/schedule/timetable.js') !!}

    <script type="text/javascript">
        /*Drag course session into timetable.*/
        function drag_course_session() {

            @if(access()->allow('drag-course-session'))
            $('.courses .course-item').each(function () {
                // store data so the calendar knows to render an event upon drop
                $(this).data('event', {
                    slot_id: $(this).find('.slot-id').text(),
                    course_session_id: $(this).find('.courses-session-id').text(),
                    course_name: $(this).find('.course-name').text(),
                    class_name: 'course-item',
                    teacher_name: $(this).find('.teacher-name').text(),
                    course_type: $(this).find('.course-type').text(),
                    times: $(this).find('.times').text()
                });
                if ($(this).data('event').teacher_name !== 'Unsigned') {
                    // make the event draggable using jQuery UI
                    $(this).draggable({
                        zIndex: 9999,
                        revert: true,      // will cause the event to go back to its
                        revertDuration: 0  //  original position after the drag
                    });
                }
            });
            @endif
        }

        /** init rooms */
        function get_rooms() {
            $.ajax({
                type: 'POST',
                url: '/admin/schedule/timetables/get_rooms',
                data: {_token: '{{csrf_token()}}'},
                success: function (response) {
                    if (response.status === true) {
                        var room_item = '';
                        $.each(response.rooms, function (key, val) {

                            room_item += '<div class="info-box">'
                                + '<span class="info-box-icon bg-aqua">'
                                + '<span>' + val.code + '-' + val.name + '</span>'
                                + '</span>'
                                + '<div class="info-box-content">'
                                + '<span class="info-box-number">' + val.room_type + '</span>'
                                + '<span class="info-box-number room_id hidden">' + val.id + '</span>'
                                + '<span class="info-box-text text-muted">' + (val.desk === null ? 0 : val.desk) + ' Desk</span>'
                                + '<span class="info-box-text text-muted">' + (val.chair === null ? 0 : val.chair) + ' Chair</span>'
                                + '</div>'
                                + '</div>'
                        });

                        $('.rooms').html(room_item);
                    }
                    else {
                        var message = '<div class="room-item bg-danger" style="width: 100%; background-color: red; color: #fff;">' +
                            '<i class="fa fa-warning"></i> Room not found!' +
                            '</div>';
                        $('.rooms').html(message);
                    }
                }
            })
        }
        /** get suggest rooms */
        function get_suggest_room(academic_year_id, week_id, timetable_slot_id) {
            $.ajax({
                type: 'POST',
                url: '/admin/schedule/timetables/get_suggest_room',
                data: {
                    academic_year_id: academic_year_id,
                    week_id: week_id,
                    timetable_slot_id: timetable_slot_id
                },
                success: function (response) {
                    if (response.status === true) {
                        var room_item = '';

                        $.each(response.roomRemain, function (key, val) {

                            room_item += '<div class="info-box">'
                                + '<span class="info-box-icon bg-aqua">'
                                + '<span>' + val.code + '-' + val.name + '</span>'
                                + '</span>'
                                + '<div class="info-box-content">'
                                + '<span class="info-box-number room_title">' + val.room_type + '</span>'
                                + '<span class="info-box-number room_id hidden">' + val.id + '</span>'
                                + '<span class="info-box-text text-muted">' + (val.desk === null ? 0 : val.desk) + ' Desk</span>'
                                + '<span class="info-box-text text-muted">' + (val.chair === null ? 0 : val.chair) + ' Chair</span>'
                                + '</div>'
                                + '</div>';
                        });

                        $.each(response.roomUsed, function (key, val) {

                            room_item += '<div class="info-box-room-use">'
                                + '<span class="info-box-icon bg-red">'
                                + '<span>' + val.code + '-' + val.name + '</span>'
                                + '</span>'
                                + '<div class="info-box-content">'
                                + '<span class="info-box-number">' + val.room_type + '</span>'
                                + '<span class="info-box-number room_id hidden">' + val.id + '</span>'
                                + '<span class="info-box-text text-muted">' + (val.desk === null ? 0 : val.desk) + ' Desk</span>'
                                + '<span class="info-box-text text-muted">' + (val.chair === null ? 0 : val.chair) + ' Chair</span>'
                                + '</div>'
                                + '</div>';
                        });
                        $('.rooms').html(room_item);
                    }
                    else {
                        var message = '<div class="room-item bg-danger" style="width: 100%; background-color: red; color: #fff;">' +
                            '<i class="fa fa-warning"></i> Room not found!' +
                            '</div>';
                        $('.rooms').html(message);
                    }
                },
                error: function () {
                    notify('error', 'Something went wrong.', 'Suggestion Room');
                }
            })
        }
        /** search suggest rooms */
        function search_suggest_room(academic_year_id, week_id, timetable_slot_id, room_number) {
            $.ajax({
                type: 'POST',
                url: '/admin/schedule/timetables/get_suggest_room',
                data: {
                    academic_year_id: academic_year_id,
                    week_id: week_id,
                    timetable_slot_id: timetable_slot_id,
                    room_number: room_number
                },
                success: function (response) {
                    if (response.status === true) {
                        var room_item = '';

                        $.each(response.roomRemain, function (key, val) {
                            var desks = null;
                            var chairs = null
                            if (val.desk == null) {
                                desks = 0;
                            } else {
                                desks = val.desk;
                            }

                            if (val.chair == null) {
                                chairs = 0;
                            } else {
                                chairs = val.chair;
                            }
                            room_item += '<div class="info-box">'
                                + '<span class="info-box-icon bg-aqua">'
                                + '<span>' + val.code + '-' + val.name + '</span>'
                                + '</span>'
                                + '<div class="info-box-content">'
                                + '<span class="info-box-number room_title">' + val.room_type + '</span>'
                                + '<span class="info-box-number room_id hidden">' + val.id + '</span>'
                                + '<span class="info-box-text text-muted">' + desks + ' Desk</span>'
                                + '<span class="info-box-text text-muted">' + chairs + ' Chair</span>'
                                + '</div>'
                                + '</div>';
                        });

                        $.each(response.roomUsed, function (key, val) {
                            var desks = null;
                            var chairs = null
                            if (val.desk == null) {
                                desks = 0;
                            } else {
                                desks = val.desk;
                            }

                            if (val.chair == null) {
                                chairs = 0;
                            } else {
                                chairs = val.chair;
                            }
                            room_item += '<div class="info-box-room-use">'
                                + '<span class="info-box-icon bg-red">'
                                + '<span>' + val.code + '-' + val.name + '</span>'
                                + '</span>'
                                + '<div class="info-box-content">'
                                + '<span class="info-box-number">' + val.room_type + '</span>'
                                + '<span class="info-box-number room_id hidden">' + val.id + '</span>'
                                + '<span class="info-box-text text-muted">' + desks + ' Desk</span>'
                                + '<span class="info-box-text text-muted">' + chairs + ' Chair</span>'
                                + '</div>'
                                + '</div>';
                        });
                        $('.rooms').html(room_item);
                    } else {
                        var message = '<div class="room-item disabled bg-danger" style="width: 100%; background-color: red; color: #fff;">' +
                            '<i class="fa fa-warning"></i> Room not found!' +
                            '</div>';
                        $('.rooms').html(message);
                    }
                },
                error: function () {
                    notify('error', 'Something went wrong.', 'Suggestion Room');
                }
            })
        }
        /** get timetable slots */
        function get_timetable_slots() {
            toggleLoading(true);
            $.ajax({
                type: 'POST',
                url: '{!! route('get_timetable_slots') !!}',
                data: $('#options-filter').serialize(),
                success: function (response) {
                    if (typeof  response.timetable === "undefined") {
                        if (response.timetable.completed === false) {
                            $("#btn_clone").attr('disabled', true);
                            $('#btn_publish').attr('disabled', false);
                        } else {
                            $("#btn_clone").attr('disabled', false);
                            $('#btn_publish').attr('disabled', true);
                        }
                    } else {
                        $("#btn_clone").attr('disabled', true);
                        $('#btn_publish').attr('disabled', true);
                    }
                    $('#timetable').fullCalendar('removeEvents');
                    $('#timetable').fullCalendar('renderEvents', response.timetableSlots, true);
                    $('#timetable').fullCalendar('changeView', 'agendaWeek');
                    $('#timetable').fullCalendar('rerenderEvents');
                    toggleLoading(false);
                },
                error: function () {
                    notify('error', 'error load timetable slot');
                }
            });
        }
        /** create timetable slot */
        function create_timetable_slots(copiedEventObject) {
            toggleLoading(true);
            $.ajax({
                type: 'POST',
                url: '{{ route('admin.schedule.timetables.store') }}',
                data: {
                    'academicYear': $('select[name="academicYear"] :selected').val(),
                    'department': $('select[name="department"] :selected').val(),
                    'option': $('select[name="option"] :selected').val(),
                    'degree': $('select[name="degree"] :selected').val(),
                    'grade': $('select[name="grade"] :selected').val(),
                    'semester': $('select[name="semester"] :selected').val(),
                    'weekly': $('select[name="weekly"] :selected').val(),
                    'group': $('select[name="group"] :selected').val(),
                    'course_session_id': copiedEventObject.course_session_id,
                    'slot_id': copiedEventObject.slot_id,
                    'course_name': copiedEventObject.course_name,
                    'teacher_name': copiedEventObject.teacher_name,
                    'course_type': copiedEventObject.course_type,
                    'start': copiedEventObject.start,
                    'end': copiedEventObject.end
                },
                success: function (response) {
                    if (response.status === true) {
                        notify('success', 'Timetable Slot was added.', 'Add Timetable Slot');
                        get_timetable_slots();
                    }
                    else {
                        notify('error', 'Timetable Slot was not created yet.', 'Add Timetable Slot');
                        get_timetable();
                    }
                },
                error: function (response) {
                    if (response.status === 403) {
                        notify('error', 'You are not allowed to drag.', 'Unauthorized');
                    } else {
                        notify('error', 'Timetable Slot was not created yet.', 'Add Timetable Slot');
                    }
                },
                complete: function () {
                    toggleLoading(false);
                    get_course_sessions();
                    $('.panel-conflict').hide();
                }
            });

            $('#timetable').fullCalendar("rerenderEvents");
        }

        /** move timetable slot */
        function move_timetable_slot(event, start_date) {
            toggleLoading(true);
            $('#timetable').fullCalendar({
                eventDurationEditable: false
            });
            $.ajax({
                type: 'POST',
                url: '{!! route('move_timetable_slot') !!}',
                data: {
                    timetable_slot_id: event.id,
                    start_date: start_date
                },
                success: function (response) {
                    if (response.status === true) {
                        notify('info', 'Timetable Slot was moved.', 'Move Timetable Slot');
                        $('#timetable').fullCalendar('refresh');
                    } else {
                        notify('error', 'Something went wrong.', 'Move Timetable Slot');
                    }
                },
                error: function (response) {
                    if (response.status === 403) {
                        notify('error', 'You are not allowed to move  timetable slot.', 'Unauthorized');
                    }
                    else {
                        notify('error', 'Something went wrong.', 'Move Timetable Slot');
                    }
                },
                complete: function () {
                    get_timetable_slots();
                    get_course_sessions();
                    toggleLoading(false);
                }
            })
        }

        /** resize timetable slot */
        function resize_timetable_slot(timetable_slot_id, end_date, revertFunc) {
            $.ajax({
                type: 'POST',
                url: '{!! route('resize_timetable_slot') !!}',
                data: {
                    timetable_slot_id: timetable_slot_id,
                    end: end_date
                },
                success: function (response) {
                    if (response.status === true) {
                        notify('info', 'Timetable slot have been changed.', 'Resize Timetable Slot');
                    } else {
                        notify('error', 'Something went wrong.', 'Resize Timetable Slot');
                        revertFunc();
                    }
                },
                error: function (response) {
                    if (response.status === 403) {
                        notify('error', 'You are not allowed to resize timetable slot.', 'Unauthorized');
                    } else {
                        notify('error', response.message, "Resize timetable Slot");
                    }
                    get_timetable_slots();
                    get_course_sessions();
                },
                complete: function () {
                    get_timetable_slots();
                    get_course_sessions();
                    $('#timetable').fullCalendar({
                        eventDurationEditable: true
                    });
                }
            })
        }

        var remove_timetable_slots = function (event) {
            toggleLoading(true);
            $.ajax({
                type: 'POST',
                url: '{!! route('remove_timetable_slot') !!}',
                data: {timetable_slot_id: event.id},
                success: function (response) {
                    if (response.status === true) {
                        $('#timetable').fullCalendar('removeEvent', event.id);
                        notify('info', 'Timetable slot remove from timetable.', 'Remove Timetable Slot');
                    }
                },
                error: function (response) {
                    if (response.status === 403) {
                        notify('error', 'You are not allowed to remove timetable slot.', 'Unauthorized');
                    } else {
                        notify('error', 'Timetable slot can not remove from timetable.', 'Remove Timetable Slot');
                    }

                },
                complete: function () {
                    get_course_sessions();
                    get_timetable_slots();
                    toggleLoading(false);
                }
            })
        };

        /** get timetable */
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
                timezone: 'Asia/Phnom_Penh',
                @if(access()->allow('edit-timetable')) editable: true, @endif
                droppable: true,
                dragRevertDuration: 0,
                eventConstraint: {
                    start: '07:00:00',
                    end: '20:00:00'
                },
                drop: function (date) {

                    var originalEventObject = $(this).data('event');
                    var copiedEventObject = $.extend({}, originalEventObject);

                    var datetime = moment(date, 'YYYY-MM-DD HH:mm:ss');
                    var start = datetime.format('YYYY-MM-DD HH:mm:ss');
                    var end = datetime.hour(parseInt(datetime.hour()) + 2).format('YYYY-MM-DD HH:mm:ss');

                    copiedEventObject.id = Math.floor(Math.random() * 1800) + 1;
                    copiedEventObject.start = start;
                    copiedEventObject.end = end;
                    copiedEventObject.allDay = true;

                    create_timetable_slots(copiedEventObject);
                    get_course_sessions();
                },
                eventDragStart: function (event, jsEvent, ui, view) {
                    get_rooms();
                },
                eventDrop: function (event, delta, revertFunc) {
                    var start_date = event.start.format();
                    move_timetable_slot(event, start_date);
                    hide_conflict_information();
                    get_timetable();
                    set_background_color_slot_not_allow();
                    get_course_sessions();
                },
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
                            if (i % 2 === 0) {
                                object += '<div class="lang-info-left"> Gr: ' + event.slotsForLanguage[i].group + ' (' + event.slotsForLanguage[i].building + '-' + event.slotsForLanguage[i].room + ')</div>';
                            } else {
                                object += '<div class="lang-info-right"> Gr: ' + event.slotsForLanguage[i].group + ' (' + event.slotsForLanguage[i].building + '-' + event.slotsForLanguage[i].room + ')</div>';
                            }
                        }
                        object += '</div></div></div>';
                    } else {
                        object += '<div class="side-course" id="' + event.id + '"​​​>';

                        // check conflict room and render
                        object += '<div class="fc-title">' + (event.course_name).substr(0, 20) + '...';
                        if (typeof event.type !== 'undefined') {
                            object += '<span class="text-primary"> (' + event.type + ')</span> ';
                        }
                        object += '</div>';

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
                eventOverlap: function (stillEvent, movingEvent) {
                    return stillEvent.allDay && movingEvent.allDay
                },
                eventResize: function (event, delta, revertFunc) {
                    var end = event.end.format();
                    resize_timetable_slot(event.id, end, revertFunc);
                    $('#timetable').fullCalendar('rerenderEvents');
                    hide_conflict_information();
                },
                eventDragStop: function (event, jsEvent, ui, view) {
                    if (isEventOverDiv(jsEvent.clientX, jsEvent.clientY)) {
                        remove_timetable_slots(event);
                        $('#timetable').fullCalendar('removeEvent', event.id);
                    }
                },
                loading: function (isLoading, view) {
                    if (isLoading) {
                        toggleLoading(isLoading);
                    }
                    else {
                        toggleLoading(false);
                    }
                },
                dayRender: function (date, cell) {
                    cell.css("background-color", "red");
                }
            });
        }

        var isEventOverDiv = function (x, y) {

            var rooms = $('.box-body.courses-sessions');
            var offset = rooms.offset();
            offset.right = rooms.width() + offset.left;
            offset.bottom = rooms.height() + offset.top;

            // Compare
            return (x >= offset.left
            && y >= offset.top
            && x <= offset.right
            && y <= offset.bottom);

        };

        function check_conflict(timetable_slot_id) {
            $.ajax({
                type: 'POST',
                url: '{!! route('get_conflict_info') !!}',
                data: {timetable_slot_id: timetable_slot_id},
                success: function (response) {
                    if (response.data.lecturer_conflict === true || response.data.is_conflict_room === true) {
                        var panel_conflict = '<div class="box-header with-border bg-danger">' +
                            '<h3 class="box-title"><i class="fa fa-info-circle"></i> CONFLICT INFORMATION</h3>' +
                            '<div class="box-tools pull-right"> ' +
                            '<button type="button" class="btn-conflict-cancel btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>' +
                            '</button></div></div>' +
                            '<div class="box-body">';

                        if (response.data.is_conflict_room === true) {
                            panel_conflict += '<ul class="list-group">' +
                                '<li class="list-group-item"> <i class="fa fa-building-o"></i> Room ' +
                                '<div class="box-conflict" style="height: 70px !important; overflow: hidden;margin-top: 10px; border-radius: 4px;">' +
                                '<div class="box-conflict-item" style="width: 100%;"><i class="fa fa-angle-double-right"></i> ' +
                                response.data.room_info[0].department + '-' +
                                response.data.room_info[0].degree +
                                response.data.room_info[0].grade;
                            panel_conflict += '<span class="badge bg-primary pull-right"> Group: ';
                            if (response.data.room_info[0].group !== null) {
                                panel_conflict += response.data.room_info[0].group + ', ';
                            }
                            if (response.data.room_info[0].option !== null) {
                                panel_conflict += '_' + response.data.room_info[0].option + ', ';
                            }
                            panel_conflict += response.data.room_info[0].week;
                            panel_conflict += '</span></div></div></li>';
                        }

                        if (response.data.lecturer.canMerge.length > 0) {
                            panel_conflict += '<li class="list-group-item">' +
                                '<i class="fa fa-user"></i> Lecturer ' +
                                '<i data-toggle="tooltip" data-placement="right" title="Merge" data-original-title="Merge" class="btn btn-info btn-xs fa fa-code-fork pull-right" id="merge"></i>' +
                                '<div class="box-conflict" style="margin-top: 10px; border-radius: 4px; min-height: 70px; max-height: 100px;">';
                            for (var i = 0; i < response.data.lecturer.canMerge.length; i++) {
                                panel_conflict += '<div class="box-conflict-item" style="width: 100%;"><i class="fa fa-angle-double-right"></i> '
                                    + response.data.lecturer.canMerge[i].department + '-'
                                    + response.data.lecturer.canMerge[i].degree
                                    + response.data.lecturer.canMerge[i].grade
                                    + '<span class="badge bg-primary pull-right">Group: '
                                    + response.data.lecturer.canMerge[i].group + ', '
                                    + response.data.lecturer.canMerge[i].week
                                    + '</span></div>';
                            }
                            panel_conflict += '</div></li>';
                        }

                        if (response.data.lecturer.canNotMerge.length > 0) {
                            panel_conflict += '<li class="list-group-item">' +
                                '<i class="fa fa-user"></i> Lecturer ' +
                                '<div class="box-conflict" style="margin-top: 10px; border-radius: 4px; min-height: 70px; max-height: 100px;">';
                            for (var i = 0; i < response.data.lecturer.canNotMerge.length; i++) {
                                panel_conflict += '<div class="box-conflict-item" style="width: 100%;"><i class="fa fa-angle-double-right"></i> '
                                    + response.data.lecturer.canNotMerge[i].department + '-'
                                    + response.data.lecturer.canNotMerge[i].degree
                                    + response.data.lecturer.canNotMerge[i].grade + '-'
                                    + response.data.lecturer.canNotMerge[i].week + '<span class="badge bg-primary pull-right">Group: '
                                    + response.data.lecturer.canNotMerge[i].group + '</span></div>';
                            }
                            panel_conflict += '<div/></li>';
                        }
                        panel_conflict += '</ul></div>';
                        $('#conflict').html(panel_conflict).hide().fadeIn();
                    }
                },
                error: function () {
                    notify('error', 'Something went wrong.', 'Check Conflict Info');
                }
            })
        }

        function hide_conflict_information() {
            $('#conflict').fadeOut();
        }

        $(function () {
            // popup export course session btn.
            $('.btn_export_course_session').popover({
                trigger: 'hover'
            });

            // filter options
            get_options($('select[name="department"] :selected').val());
            drag_course_session();
            get_rooms();

            // select timetable slot to add room.
            $(document).on('click', '.side-course', function () {
                $('body').find('.course-selected').removeClass('course-selected');
                $(this).addClass('course-selected');
                var academic_year_id = $('select[name="academicYear"] :selected').val();
                var week_id = $('select[name="weekly"] :selected').val();
                var timetable_slot_id = $(this).attr('id');
                get_suggest_room(academic_year_id, week_id, timetable_slot_id);
                hide_conflict_information();
                check_conflict(timetable_slot_id);
            });

            // remove room from timetable slot.
            $(document).on('click', '.fc-room', function () {
                var dom = $(this);
                var timetable_slot_id = $(this).parent().parent().parent().children().eq(0).attr('id');
                $.ajax({
                    type: 'POST',
                    url: '/admin/schedule/timetables/remove_room_from_timetable_slot',
                    data: {
                        timetable_slot_id: timetable_slot_id
                    },
                    success: function () {
                        dom.parent().parent().children().eq(0).empty();
                        dom.remove();
                        notify('info', 'Room was removed.', 'Remove Room');
                    },
                    error: function (response) {
                        if (response.status === 403) {
                            notify('error', 'You are not allow to remove room.', 'Unauthorized');
                        }
                        else {
                            notify('error', 'Something went wrong.', 'Remove Room');
                        }
                    }
                })
            });

            // add room to timetable slot.
            $(document).on('click', '.rooms .info-box', function () {
                var dom_room = $(this);
                console.log(dom_room.find('.room_id').text());
                $.ajax({
                    type: 'POST',
                    url: '/admin/schedule/timetables/insert_room_into_timetable_slot',
                    data: {
                        timetable_slot_id: $('.side-course.course-selected').attr('id'),
                        room_id: $(this).find('.room_id').text()
                    },
                    success: function (response) {
                        if (response.status === true) {
                            $('.container-room').find('.side-course.course-selected').parent().children().eq(1).children().eq(0).html('<p class="fc-room">' + dom_room.find('.room_name').text() + '</p>');
                            dom_room.remove();
                            notify('success', 'Room was added', 'Add Room');
                            get_timetable_slots();
                        } else {
                            notify('warning', 'Please select which course.', 'Add Room');
                            get_timetable_slots();
                        }
                    },
                    error: function (response) {
                        if (response.status === 403) {
                            notify('error', 'You can not add room.', 'Unauthorized');
                        } else {
                            notify('error', 'Something went wrong.', 'Add Room');
                        }
                    }
                });
            });

            // get timetable slot by on change semester option.
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
                get_course_sessions();
                get_timetable();
                get_timetable_slots();
            });
            // search rooms.
            $(document).on('keyup', 'input[name="search_room_query"]', function () {
                if ($('.container-room').find('.side-course.course-selected').length === 1) {
                    var academic_year_id = $('select[name="academicYear"] :selected').val();
                    var week_id = $('select[name="weekly"] :selected').val();
                    var timetable_slot_id = $('.container-room').find('.side-course.course-selected').attr('id');
                    var query = $(this).val();
                    search_suggest_room(academic_year_id, week_id, timetable_slot_id, query);
                }
                else {
                    search_rooms($(this).val());
                }
            });

            $(document).on('click', '.btn-conflict-cancel', function () {
                hide_conflict_information();
            });

            $(document).on('change', '#options-filter', function () {
                hide_conflict_information();
            });

            // merge timetable slot together in case that those timetable slots are the same condition.
            $(document).on('click', '#merge', function () {
                //toggleLoading(true);
                $.ajax({
                    type: 'POST',
                    url: '{!! route('merge_timetable_slot') !!}',
                    data: {
                        timetable_slot_id: $('.side-course.course-selected').attr('id')
                    },
                    success: function (response) {
                        get_timetable_slots();
                        hide_conflict_information();
                    },
                    complete: function () {
                        get_timetable_slots();
                        hide_conflict_information();
                    }
                });
            });

            // export course session into slots table.
            $(document).on('click', '.btn_export_course_session', function (event) {
                event.preventDefault();
                toggleLoading(true);
                $.ajax({
                    type: 'POST',
                    url: '{!! route('export_course_session') !!}',
                    data: {
                        _token: '{{ csrf_token() }}',
                        academic_year_id: $('select[name=academicYear]').val(),
                        department_id: $('select[name=department]').val(),
                    },
                    success: function (response) {
                        if (response.status === true) {
                            get_course_sessions();
                            notify('info', 'Slots was exported', 'Export Slots');
                        }
                        else {
                            notify('warning', 'Slots was not exported', 'Export Slots');
                        }
                    },
                    complete: function () {
                        toggleLoading(false);
                    }
                });
            });

            // publish timetable
            $(document).on('click', '#btn_publish', function (e) {
                e.preventDefault();
                swal({
                    title: 'Publish Timetable',
                    text: "Do you want to publish timetable?",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'No',
                    confirmButtonText: 'Yes'
                }).then(function () {
                    toggleLoading(true);
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('admin.schedule.timetables.publish') }}',
                        data: $('#options-filter').serialize(),
                        success: function () {
                            notify('info', 'Timetable was published.', 'Publish Timetable');
                        },
                        error: function () {
                            notify('error', 'Something went wrong', 'Publish Timetable');
                        },
                        complete: function () {
                            get_timetable_slots();
                        }
                    })
                })

            });

            $(document).on('keyup', '#search_course_session', function (event) {
                event.preventDefault();
                var query = $(this).val();
                $.ajax({
                    type: 'POST',
                    url: '{{ route('search_course_session') }}',
                    data: {
                        _token: '{{ csrf_token() }}',
                        academic: $('select[name=academicYear]').val(),
                        department: $('select[name=department]').val(),
                        degree: $('select[name=degree]').val(),
                        option: $('select[name=option]').val(),
                        grade: $('select[name=grade]').val(),
                        semester: $('select[name=semester]').val(),
                        group: $('select[name=group]').val(),
                        week: $('select[name=weekly]').val(),
                        query: query
                    },
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
                            drag_course_session()
                        }
                        else {
                            $('.courses.todo-list').html("<li class='course-item'>There are no course sessions created yet.</li>");
                        }
                    },
                    error: function () {
                        swal(
                            'Oops...',
                            'Searching search course went wrong!',
                            'error'
                        );
                    }
                })
            });
        });

    </script>
@stop
