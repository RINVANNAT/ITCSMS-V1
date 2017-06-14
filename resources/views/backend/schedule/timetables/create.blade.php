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
                            title="{{ trans('buttons.backend.schedule.timetable.clone') }}">
                        {{ trans('buttons.backend.schedule.timetable.clone') }}
                    </button>
                    @endauth

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
                <div class="col-md-9 col-sm-12 col-xs-12" style="overflow-x: auto">
                    {{--Timetable render--}}
                    <div id="timetable" style="width: 1345px;"></div>
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
                            room_item += '<div class="room-item enabled" id="' + val.id + '">'
                                + '<i class="fa fa-building-o"></i> '
                                + '<span>' + val.code + '-' + val.name + '</span>'
                                + '</div> ';
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
                    if (response.status == true) {
                        var room_item = '';

                        $.each(response.roomRemain, function (key, val) {
                            room_item += '<div class="room-item enabled" id="' + val.id + '">'
                                + '<i class="fa fa-building-o"></i> '
                                + '<span>' + val.code + '-' + val.name + '</span>'
                                + '</div> ';
                        });

                        $.each(response.roomUsed, function (key, val) {
                            room_item += '<div class="room-item disabled bg-danger" id="' + val.id + '">'
                                + '<i class="fa fa-building-o"></i> '
                                + '<span>' + val.code + '-' + val.name + '</span>'
                                + '</div> ';
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
                            room_item += '<div class="room-item enabled" id="' + val.id + '">'
                                + '<i class="fa fa-building-o"></i> '
                                + '<span>' + val.code + '-' + val.name + '</span>'
                                + '</div> ';
                        });

                        $.each(response.roomUsed, function (key, val) {
                            room_item += '<div class="room-item bg-danger" id="' + val.id + '">'
                                + '<i class="fa fa-building-o"></i> '
                                + '<span>' + val.code + '-' + val.name + '</span>'
                                + '</div> ';
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
                    $('#timetable').fullCalendar('removeEvents');
                    $('#timetable').fullCalendar('renderEvents', response, true);
                    $('#timetable').fullCalendar('rerenderEvents');
                },
                error: function () {
                    notify('error', 'error load timetable slot');
                },
                complete: function () {
                    toggleLoading(false);
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
                    toggleLoading(false);
                    get_timetable_slots();
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
                }
            })
        }

        var remove_timetable_sltos = function (event) {
            toggleLoading(true);
            $.ajax({
                type: 'POST',
                url: '{!! route('remove_timetable_slot') !!}',
                data: {timetable_slot_id: event.id},
                success: function (response) {
                    if (response.status === true) {
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
                    get_timetable_slots();
                    get_course_sessions();
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
                @if(access()->allow('edit-timetable')) editable: true, @endif
                droppable: true,
                dragRevertDuration: 0,
                drop: function (date) {
                    var originalEventObject = $(this).data('event');

                    var copiedEventObject = $.extend({}, originalEventObject);

                    var tempDate = new Date(Date.UTC(date._d.getFullYear(), date._d.getMonth(), date._d.getDay(), date._d.getHours(), date._d.getMinutes(), date._d.getSeconds()));
                    copiedEventObject.id = Math.floor(Math.random() * 1800) + 1;
                    copiedEventObject.start = tempDate;
                    copiedEventObject.start.setHours(copiedEventObject.start.getHours() - 7);
                    copiedEventObject.end = new Date(copiedEventObject.start);
                    copiedEventObject.end.setHours(copiedEventObject.start.getHours() + 1);
                    copiedEventObject.allDay = true;

                    create_timetable_slots(copiedEventObject);
                    get_course_sessions();
                },
                eventDragStart: function (event, jsEvent, ui, view) {
                    get_rooms();
                },
                eventClick: function (calEvent, jsEvent, view) {
                    // Trigger when click the event.
                },
                eventDrop: function (event, delta, revertFunc) {
                    var start_date = event.start.format();
                    move_timetable_slot(event, start_date);
                    hide_conflict_information();
                    get_timetable();
                    get_course_sessions();
                },
                eventRender: function (event, element, view) {
                    var object = '<a class="fc-time-grid-event fc-v-event fc-event fc-start fc-end course-item  fc-draggable fc-resizable" style="top: 65px; bottom: -153px; z-index: 1; left: 0%; right: 0%;">' +
                        '<div class="fc-content">' +
                        '<div class="container-room">' +
                        '<div class="side-course" id="' + event.id + '"​​​>';

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
                    object += '</div>' +
                        '<div class="side-room">' +
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
                    object += '</div> ' +
                        '<div class="clearfix"></div> ' +
                        '</div>' +
                        '</div>' +
                        '<div class="fc-bgd"></div>' +
                        '<div class="fc-resizer fc-end-resizer"></div>' +
                        '</a>';
                    return $(object);
                },
                eventOverlap: function (stillEvent, movingEvent) {
                    return stillEvent.allDay && movingEvent.allDay;
                },
                eventResize: function (event, delta, revertFunc) {
                    var end = event.end.format();
                    resize_timetable_slot(event.id, end, revertFunc);
                    $('#timetable').fullCalendar('rerenderEvents');
                    hide_conflict_information();
                },
                eventDragStop: function (event, jsEvent, ui, view) {
                    if (isEventOverDiv(jsEvent.clientX, jsEvent.clientY)) {
                        remove_timetable_sltos(event);
                        $('#timetable').fullCalendar('removeEvent', event.id);
                    }
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
                                '<div class="rooms" style="height: 70px !important; overflow: hidden;margin-top: 10px; border-radius: 4px;">' +
                                '<div class="room-item" style="width: 100%;"><i class="fa fa-angle-double-right"></i> ' +
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
                                '<div class="rooms" style="margin-top: 10px; border-radius: 4px; min-height: 70px; max-height: 100px;">';
                            for (var i = 0; i < response.data.lecturer.canMerge.length; i++) {
                                panel_conflict += '<div class="room-item" style="width: 100%;"><i class="fa fa-angle-double-right"></i> '
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
                            console.log(100);
                            panel_conflict += '<li class="list-group-item">' +
                                '<i class="fa fa-user"></i> Lecturer ' +
                                '<div class="rooms" style="margin-top: 10px; border-radius: 4px; min-height: 70px; max-height: 100px;">';
                            for (var i = 0; i < response.data.lecturer.canNotMerge.length; i++) {
                                panel_conflict += '<div class="room-item" style="width: 100%;"><i class="fa fa-angle-double-right"></i> '
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
            $(document).on('click', '.rooms .room-item.enabled', function () {
                var dom_room = $(this);
                $.ajax({
                    type: 'POST',
                    url: '/admin/schedule/timetables/insert_room_into_timetable_slot',
                    data: {
                        timetable_slot_id: $('.side-course.course-selected').attr('id'),
                        room_id: $(this).attr('id')
                    },
                    success: function (response) {
                        if (response.status === true) {
                            $('.container-room').find('.side-course.course-selected').parent().children().eq(1).children().eq(0).html('<p class="fc-room">' + dom_room.children().eq(1).text() + '</p>');
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
                        //toggleLoading(false);
                    }
                });
            });

            // export course session into slots table.
            $(document).on('click', '.btn_export_course_session', function () {
                toggleLoading(true);
                $.ajax({
                    type: 'POST',
                    url: '{!! route('export_course_session') !!}',
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
        });

    </script>
@stop
