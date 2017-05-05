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
    {!! Html::style('css/backend/schedule/timetable.css') !!}

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
    {!! Html::script('js/backend/schedule/timetable.js') !!}

    <script type="text/javascript">
        $(document).ready(function () {
            // Timetable sections.
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
                events: '{{ route('admin.schedule.timetables.create') }}',
                editable: true,
                droppable: true,
                dragRevertDuration: 10,
                drop: function () {
                    //store_save_timetable();
                },
                eventDragStart: function (event, jsEvent, ui, view) {
                    get_rooms();
                },
                eventDragStop: function (event, jsEvent, ui, view) {
                    if (isEventOverDiv(jsEvent.clientX, jsEvent.clientY)) {
                        $('#timetable').fullCalendar('removeEvents', event._id);
                        $('#timetable').fullCalendar('removeEvents', event._id);
                        var course = '';
                        course += '<li class="course-item drag-course-back">'
                            + '<span class="handle ui-sortable-handle">'
                            + '<i class="fa fa-ellipsis-v"></i> '
                            + '<i class="fa fa-ellipsis-v"></i>'
                            + '</span>'
                            + '<span class="text course-name">' + event.title + '</span><br>'
                            + '<span style="margin-left: 28px;" class="teacher-name">' + event.teacherName + '</span><br/>'
                            + '<span style="margin-left: 28px;" class="course-type">' + event.typeCourseSession + '</span> :'
                            + '<span class="times">' + event.times + '</span> H'
                            + '</li>';

                        $('.courses').prepend(course);

                        setTimeout(function () {
                            $('.courses').find('.drag-course-back').removeClass('drag-course-back');
                        }, 300);

                        drag_course_session();
                    }
                },
                eventClick: function (calEvent, jsEvent, view) {
                    // Trigger when click the event.
                },
                eventDrop: function (event, delta, revertFunc) {
                    // Trigger where move and drop the event on full calendar.
                    alert('Drop all events.');
                },
                eventRender: function (event, element, view) {
                    var object = '<a class="fc-time-grid-event fc-v-event fc-event fc-start fc-end course-item  fc-draggable fc-resizable" style="top: 65px; bottom: -153px; z-index: 1; left: 0%; right: 0%;">' +

                        '<div class="fc-content">' +
                        '<div class="container-room">' +
                        '<div class="side-course">' +
                        '<div class="fc-title">' + (event.title).substring(0, 10) + '...</div>' +
                        '<p class="text-primary">' + event.teacherName + '</p> ' +
                        '<p class="text-primary">' + event.typeCourseSession + '</p> ' +
                        '</div>' +
                        '<div class="side-room">' +
                        '<div class="room-name"><span class="render-room"></span></div> ' +
                        '<div class="room-action">' +
                        '<span class="render-trash"></span> ' +
                        '</div> ' +
                        '</div> ' +
                        '<div class="clearfix"></div> ' +
                        '</div>' +
                        '</div>' +
                        '<div class="fc-bgd"></div>' +
                        '<div class="fc-resizer fc-end-resizer"></div>' +
                        '</a>';

                    return $(object);
                },
                eventAfterAllRender: function (view) {

                },
                eventOverlap: function (stillEvent, movingEvent) {
                    return stillEvent.allDay && movingEvent.allDay;
                }

            });

            // EventOverDiv
            var isEventOverDiv = function (x, y) {

                var courses = $('.courses');
                var offset = courses.offset();
                offset.right = courses.width() + offset.left;
                offset.bottom = courses.height() + offset.top;

                /** Compare*/
                return x >= offset.left
                    && y >= offset.top
                    && x <= offset.right
                    && y <= offset.bottom;
            };

            // Reload courses.
            $('#timetable').fullCalendar('rerenderEvents');
        });
    </script>
@stop
