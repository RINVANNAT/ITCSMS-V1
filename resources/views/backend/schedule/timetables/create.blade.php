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
        {{--Group buttons action--}}
        @include('backend.schedule.timetables.includes.partials.buttons-action')

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
    </div>

@stop

@section('after-scripts-end')

    {!! Html::script('plugins/datatables/jquery.dataTables.min.js') !!}
    {!! Html::script('plugins/datatables/dataTables.bootstrap.min.js') !!}
    {!! Html::script('plugins/moment/moment.min.js') !!}
    {!! Html::script('plugins/fullcalendar/fullcalendar.js') !!}
    {!! Html::script('plugins/sweetalert2/dist/sweetalert2.js') !!}
    {!! Html::script('plugins/toastr/toastr.min.js') !!}
    {!! Html::script('js/backend/schedule/timetable.js') !!}

    <script type="text/javascript">
        /** init rooms */
        function get_rooms() {
            $.ajax({
                type: 'POST',
                url: '/admin/schedule/timetables/get_rooms',
                data: {_token: '{{csrf_token()}}'},
                success: function (response) {
                    if (response.status == true) {
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
                    toastr['error']('Something went wrong.', 'ERROR SUGGESTION ROOM');
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
                    if (response.status == true) {
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
                    toastr['error']('Something went wrong.', 'ERROR SUGGESTION ROOM');
                }
            })
        }
        /** get timetable slots */
        function get_timetable_slots() {
            $.ajax({
                type: 'POST',
                url: '{!! route('get_timetable_slots') !!}',
                data: $('#options-filter').serialize(),
                success: function (response) {
                    $('#timetable').fullCalendar('removeEvents');
                    $('#timetable').fullCalendar('renderEvents', response, true);
                    $('#timetable').fullCalendar('rerenderEvents');
                }
            });
        }
        /** create timetable slot */
        function create_timetable_slots(copiedEventObject) {
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
                    'course_name': copiedEventObject.course_name,
                    'teacher_name': copiedEventObject.teacher_name,
                    'course_type': copiedEventObject.course_type,
                    'start': copiedEventObject.start,
                    'end': copiedEventObject.end
                },
                success: function (response) {
                    if (response.status == true) {
                        toastr['info']('The course was added.', 'ADDING COURSE');
                        get_timetable_slots();
                    }
                    else{
                        toastr['error']('The timetable slot was not created.', 'ERROR !');
                        get_timetable();
                    }
                },
                error: function () {
                    toastr['error']('The course was not added.', 'ERROR ADDING COURSE');
                },
                complete:function () {
                    get_course_sessions();
                }
            });

            $('#timetable').fullCalendar("rerenderEvents");
        }
        /** move timetable slot */
        function move_timetable_slot(event, start_date) {
            $.ajax({
                type: 'POST',
                url: '{!! route('move_timetable_slot') !!}',
                data: {
                    timetable_slot_id: event.id,
                    start_date: start_date
                },
                success: function (response) {
                    if (response.status == true) {
                        toastr["success"]("The course was moved.", "MOVING COURSE");
                        $('#timetable').fullCalendar('refresh');
                    } else {
                        toastr["error"]("Something went wrong.", "ERROR MOVING COURSE");
                    }
                },
                error: function () {
                    toastr["error"]("Something went wrong.", "ERROR MOVING COURSE");
                }
            })
        }
        /** resize timetable slot */
        function resize_timetable_slot(timetable_slot_id, end_date) {
            $.ajax({
                type: 'POST',
                url: '{!! route('resize_timetable_slot') !!}',
                data: {
                    timetable_slot_id: timetable_slot_id,
                    end: end_date
                },
                success: function (response) {
                    if (response.status == true) {
                        toastr["success"]("Timetable slot have been changed.", "Timetable Slot Change");
                    } else {
                        toastr['error'](response.message, "ERROR RESIZE COURSE");
                    }
                },
                error: function (response) {
                    toastr['error'](response.message, "ERROR RESIZE COURSE");
                },
                complete: function () {
                    get_course_sessions();
                }
            })
        }
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
                editable: true,
                droppable: true,
                dragRevertDuration: 0,
                drop: function (date) {
                    var originalEventObject = $(this).data('event');

                    var copiedEventObject = $.extend({}, originalEventObject);

                    var tempDate = new Date(date);
                    copiedEventObject.id = Math.floor(Math.random() * 1800) + 1;
                    copiedEventObject.start = tempDate;
                    copiedEventObject.start.setHours(copiedEventObject.start.getHours() - 7);
                    copiedEventObject.end = new Date(copiedEventObject.start);
                    copiedEventObject.end.setHours(copiedEventObject.start.getHours() + 2);
                    copiedEventObject.allDay = true;

                    create_timetable_slots(copiedEventObject);
                    get_course_sessions();
                },
                eventDragStart: function (event, jsEvent, ui, view) {
                    get_rooms();
                },
                eventDragStop: function (event, jsEvent, ui, view) {
                    //$('#timetable').fullCalendar('rerenderEvents');
                },
                eventClick: function (calEvent, jsEvent, view) {
                    // Trigger when click the event.
                },
                eventDrop: function (event, delta, revertFunc) {
                    var start_date = event.start.format();
                    move_timetable_slot(event, start_date);
                    get_timetable();
                    get_course_sessions();
                },
                eventRender: function (event, element, view) {
                    var object = '<a class="fc-time-grid-event fc-v-event fc-event fc-start fc-end course-item  fc-draggable fc-resizable" style="top: 65px; bottom: -153px; z-index: 1; left: 0%; right: 0%;">' +
                        '<div class="fc-content">' +
                        '<div class="container-room">' +
                        '<div class="side-course" id="' + event.id + '">' +
                        '<div class="fc-title">' + (event.course_name).substring(0, 10) + '...</div>' +
                        '<p class="text-primary">' + event.teacher_name + '</p> ' +
                        '<p class="text-primary">' + event.course_type + '</p> ' +
                        '</div>' +
                        '<div class="side-room">' +
                        '<div class="room-name">';
                    if (event.room != null) {
                        object += '<p class="fc-room">' + event.building + '-' + event.room + '</p>';
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
                },
                eventOverlap: function (stillEvent, movingEvent) {
                    return stillEvent.allDay && movingEvent.allDay;
                },
                eventResize: function (event, delta, revertFunc) {
                    var end = event.end.format();
                    resize_timetable_slot(event.id, end);
                    $('#timetable').fullCalendar('rerenderEvents');
                }
            });
        }

        $(function () {
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
                        toastr['info']('The room was removed.', 'REMOVING ROOM');
                    },
                    error: function () {
                        swal(
                            'Oops...',
                            'Something went wrong!',
                            'error'
                        )
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
                        if (response.status == true) {
                            var btn_delete = '<button class="btn btn-danger btn-xs remove-room"><i class="fa fa-trash"></i></button>';
                            $('.container-room').find('.side-course.course-selected').parent().children().eq(1).children().eq(1).html(btn_delete);
                            $('.container-room').find('.side-course.course-selected').parent().children().eq(1).children().eq(0).html('<p class="fc-room">' + dom_room.children().eq(1).text() + '</p>');

                            dom_room.remove();
                            toastr['success']('Room was added.', 'ADDING ROOM');
                        } else {
                            toastr['warning']('Please select which course.', 'ADDING ROOM ERROR');
                        }
                    },
                    error: function () {
                        toastr['error']('Something went wrong.', 'ADDING ROOM ERROR');
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
                get_weeks($('select[name="semester"] :selected').val());
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
                if ($('.container-room').find('.side-course.course-selected').length == 1) {
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
        });

    </script>
@stop
