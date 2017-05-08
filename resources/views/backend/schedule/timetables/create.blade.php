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
    {!! Html::script('plugins/list.js/list.min.js') !!}

    <script type="text/javascript">
        /** get rooms **/
        function get_rooms() {
            $.ajax({
                type: 'POST',
                url: '/admin/schedule/timetables/get_rooms',
                success: function (response) {
                    if (response.status == true) {
                        var room_item = '';
                        $.each(response.rooms, function (key, val) {
                            room_item += '<div class="room-item" id="' + val.id + '">'
                                + '<i class="fa fa-building-o"></i> '
                                + '<span>' + val.name + '-' + val.code + '</span>'
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
        };
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
                    var room_item = '';
                    $.each(response.roomRemain, function (key, val) {
                        room_item += '<div class="room-item" id="' + val.id + '">'
                            + '<i class="fa fa-building-o"></i> '
                            + '<span>' + val.name + '-' + val.code + '</span>'
                            + '</div> ';
                    });

                    $.each(response.roomUsed, function (key, val) {
                        room_item += '<div class="room-item bg-danger" id="' + val.id + '">'
                            + '<i class="fa fa-building-o"></i> '
                            + '<span>' + val.name + '-' + val.code + '</span>'
                            + '</div> ';
                    });

                    $('.rooms').html(room_item);

                },
                error: function () {
                    toastr['error']('Somthing went wrong.', 'ERROR SUGGESTION ROOM');
                }
            })
        }
        function get_timetable_slots() {
            setTimeout(function () {
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
            }, 400);
        }
        function create_timetable_slots(copiedEventObject) {
            var event = copiedEventObject;
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
                },
                error: function () {
                    toastr['error']('The course was not added.', 'ERROR ADDING COURSE');
                }
            });
            
            $('#timetable').fullCalendar("rerenderEvents");
        };

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
        };
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
                },
                eventRender: function (event, element, view) {
                    var btn_delete = '<button class="btn btn-danger btn-xs remove-room"><i class="fa fa-trash"></i></button>';
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
                        object += '<p>' + event.room + '-' + event.building + '</p>';
                    }
                    object += '</div> ' +
                        '<div class="room-action">';
                    if (event.room != null) {
                        object += btn_delete;
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
                eventAfterRender:function( event, element, view ) {
                    console.log(event.id);
                },
                eventOverlap: function (stillEvent, movingEvent) {
                    return stillEvent.allDay && movingEvent.allDay;
                },
                eventResize: function (event, delta, revertFunc) {

                    var end = event.end.format();

                    $.ajax({
                        type: 'POST',
                        url: '{!! route('resize_timetable_slot') !!}',
                        data: {
                            timetable_slot_id: event.id,
                            end: end
                        },
                        success: function (response) {
                            if (response.status == true) {
                                toastr["success"]("Timetable slot have been changed.", "Timetable Slot Change");
                            } else {
                                toastr['error']('The course was not resize.', "ERROR RESIZE COURSE");
                            }
                        },
                        error: function () {
                            toastr['error']('The course was not resize.', "ERROR RESIZE COURSE");
                        }
                    })

                }
            });

        }

        /*function get_suggest_room(academic_year_id, week_id, timetable_slot_id) {
         $.ajax({
         type: 'POST',
         url: '/admin/schedule/timetables/get_suggest_room',
         data: {
         academic_year_id: academic_year_id,
         week_id: week_id,
         timetable_slot_id: timetable_slot_id
         },
         success: function (response) {
         $('ul.list').empty();
         var values =[];
         var options = {
         valueNames: ['id', 'name', 'code'],
         item: '<li><span class="id hidden"></span><span class="name"></span>-<span class="code"></span></li>'
         };

         $.each(response.roomRemain, function (key, val) {
         values.push(val);
         });

         $.each(response.roomUsed, function (key, val) {
         values.push(val);
         });

         new List('rooms', options, values);
         },
         error: function () {
         toastr['error']('Somthing went wrong.', 'ERROR SUGGESTION ROOM');
         }
         })
         }*/
        /** List all rooms. **/
        /*function ini_rooms() {
         $.ajax({
         type: 'POST',
         url: '/admin/schedule/timetables/get_rooms',
         success: function (response) {
         if (response.status == true) {
         $('ul.list').empty();
         var options = {
         valueNames: [ 'id', 'name', 'code'],
         item: '<li><span class="id hidden"></span><span class="name"></span>-<span class="code"></span></li>'
         };
         var values = [];

         $.each(response.rooms, function (key, val) {
         values.push(val);
         });
         new List('rooms', options, values);

         }
         else {
         var message = '<div class="room-item bg-danger" style="width: 100%; background-color: red; color: #fff;">' +
         '<i class="fa fa-warning"></i> Room not found!' +
         '</div>';
         $('.rooms').html(message);
         }
         }
         })
         };*/

        $(document).ready(function () {
            // load modules.
            get_options($('select[name="department"] :selected').val());
            get_weeks($('select[name="semester"] :selected').val());
            get_course_sessions();
            get_groups();
            drag_course_session();
            get_rooms();
            get_timetable();
            get_timetable_slots();


            $(document).on('click', '.side-course', function () {
                $('body').find('.course-selected').removeClass('course-selected');
                $(this).addClass('course-selected');
                var academic_year_id = $('select[name="academicYear"] :selected').val();
                var week_id = $('select[name="weekly"] :selected').val();
                var timetable_slot_id = $(this).attr('id');
                get_suggest_room(academic_year_id, week_id, timetable_slot_id);

            });

            // get weeks.
            $(document).on('change', 'select[name="semester"]', function () {
                get_weeks($(this).val());
                get_course_sessions();
                get_timetable_slots();
            });
            // get options.
            $(document).on('change', 'select[name="department"]', function () {
                get_options($(this).val());
                get_groups();
                get_course_sessions();
                get_timetable_slots();
            });
            // get course session.
            $(document).on('change', 'select[name="academicYear"]', function () {
                get_course_sessions();
                get_timetable();
                get_timetable_slots();
            });
            // get group and course session.
            $(document).on('change', 'select[name="option"]', function () {
                get_groups();
                get_course_sessions();
                get_timetable_slots();
            });
            // get course session.
            $(document).on('change', 'select[name="grade"]', function () {
                get_groups();
                get_course_sessions();
                get_timetable_slots();
            });
            // get groups
            $(document).on('change', 'select[name="group"]', function () {
                get_course_sessions();
                get_timetable_slots();
            });

            $(document).on('change', 'select[name="weekly"]', function () {
                get_timetable_slots();
            });

            // search rooms.
            $(document).on('keyup', 'input[name="search_room_query"]', function () {
                search_rooms($(this).val());
            });

        });

    </script>
@stop
