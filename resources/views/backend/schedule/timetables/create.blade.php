@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.schedule.timetable.title'))

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

    <style>
        .courses-sessions {
            margin-bottom: 10px;
            height: 280px;
            overflow-y: auto;
        }

        .modal-content {
            background-image: none;
        }

        #timetable {
            border: 1px solid #f1f1f1;
            padding: 10px;
        }

        .box-body .rooms {
            background-color: #f1f1f1;
            height: 280px;
            overflow-y: auto;
        }

        .rooms {
            padding: 10px;
            margin: 0px;
            width: 100%;
            cursor: pointer;
        }

        .rooms .room-item {
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 5px;
            display: inline-block;
            background-color: #FFF;
            box-shadow: 0 1px 2px rgba(0, 0, 0, .1);
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }

        .course-selected {
            background-color: green;
            color: #fff;
            border: 1px solid transparent;
        }
    </style>
@stop

@section('content')
    <div class="box box-success">
        <div class="box-header with-border">
            <div class="mailbox-controls">
                <div class="pull-right">
                    <a href="#">
                        <button class="btn btn-primary btn-sm" data-toggle="tooltip"
                                data-placement="top" title="Generate"
                                data-original-title="Generate" disabled="true">
                            Generate
                        </button>
                    </a>
                    <button type="button"
                            class="btn btn-warning btn-sm"
                            data-toggle="modal"
                            data-target="#clone-timetable"

                            data-placement="top" title="Clone"
                            data-original-title="Clone">
                        Clone
                    </button>

                    <a href="#">
                        <button class="btn btn-info btn-sm" data-toggle="tooltip"
                                data-placement="top" title="Publish"
                                data-original-title="Publish">
                            Publish
                        </button>
                    </a>
                    <a href="#">
                        <button class="btn btn-danger btn-sm" data-toggle="tooltip"
                                data-placement="top" title="Save Change"
                                data-original-title="Save Change">
                            Save Change
                        </button>
                    </a>
                </div>

                {{--Option--}}
                <select name="academicYear">
                    <option selected disabled>Academic</option>
                    @foreach($academicYears as $academicYear)
                        <option value="{{ $academicYear->id }}">{{ $academicYear->name_latin }}</option>
                    @endforeach
                </select>

                <select name="degree">
                    <option selected disabled>Degree</option>
                    @foreach($degrees as $degree)
                        <option value="{{ $degree->id }}">{{ $degree->name_en }}</option>
                    @endforeach
                </select>

                <select name="grade">
                    <option selected disabled>Year</option>
                    @foreach($grades as $grade)
                        <option value="{{ $grade->id }}">{{ $grade->name_en }}</option>
                    @endforeach
                </select>

                <select name="grade">
                    <option selected disabled>Option</option>
                    @foreach($grades as $grade)
                        <option value="{{ $grade->id }}">{{ $grade->name_en }}</option>
                    @endforeach
                </select>

                <select name="grade">
                    <option selected disabled>Semester</option>
                    @foreach($grades as $grade)
                        <option value="{{ $grade->id }}">{{ $grade->name_en }}</option>
                    @endforeach
                </select>

                <select name="grade">
                    <option selected disabled>Group</option>
                    @foreach($grades as $grade)
                        <option value="{{ $grade->id }}">{{ $grade->name_en }}</option>
                    @endforeach
                </select>

            </div>
        </div>

        <div class="box-body">
            <div class="row">
                <div class="col-md-9">
                    <div id="timetable"></div>
                </div>
                <div class="col-md-3">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box box-solid">
                                <div class="box-header with-border">
                                    <i class="fa fa-drivers-license-o"></i>
                                    <h3 class="box-title">Courses Sessions</h3>
                                </div>
                                <div class="box-body courses-sessions">
                                    <ul class="courses todo-list">
                                        @for($i=0; $i<10; $i++)

                                            <li class="course-item">
                                                <span class="handle ui-sortable-handle">
                                                    <i class="fa fa-ellipsis-v"></i>
                                                    <i class="fa fa-ellipsis-v"></i>
                                                </span>
                                                <span class="text">Cloud Computing</span><br>
                                                <span style="margin-left: 28px;">Mr. YOU Vanndy</span><br/>
                                                <span style="margin-left: 28px;">Course = 8H</span>
                                            </li>

                                        @endfor
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box box-solid">
                                <div class="box-header with-border">
                                    <i class="fa fa-building-o"></i>
                                    <h3 class="box-title">Available Rooms</h3>
                                </div>
                                <div class="box-body">
                                    <div class="rooms">
                                        @for($i=0; $i<100; $i++)
                                            <div class="room-item">
                                                <i class="fa fa-ellipsis-v"></i>
                                                <i class="fa fa-ellipsis-v"></i>
                                                F-309
                                            </div>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="clearfix"></div>
        </div>
    </div>

    @include('backend.schedule.timetables.includes.modals.clone')

@stop

@section('after-scripts-end')
    {!! Html::script('plugins/datatables/jquery.dataTables.min.js') !!}
    {!! Html::script('plugins/datatables/dataTables.bootstrap.min.js') !!}
    {!! Html::script('plugins/jQueryUI/jquery-ui.js') !!}
    {!! Html::script('plugins/moment/moment.min.js') !!}
    {!! Html::script('plugins/fullcalendar/fullcalendar.js') !!}
    <script type="text/javascript">
        $(document).ready(function () {

            $('#employees-table').DataTable();

            $('.courses .course-item').each(function () {

                // store data so the calendar knows to render an event upon drop
                $(this).data('event', {
                    title: $.trim($(this).text()), // use the element's text as the event title
                    stick: true // maintain when user navigates (see docs on the renderEvent method)
                });

                // make the event draggable using jQuery UI
                $(this).draggable({
                    zIndex: 999,
                    revert: true,      // will cause the event to go back to its
                    revertDuration: 0  //  original position after the drag
                });

            });

            $('.rooms .room-item').each(function () {

                // store data so the calendar knows to render an event upon drop
                $(this).data('event', {
                    title: $.trim($(this).text()), // use the element's text as the event title
                    stick: true // maintain when user navigates (see docs on the renderEvent method)
                });

                // make the event draggable using jQuery UI
                $(this).draggable({
                    zIndex: 999,
                    revert: true,      // will cause the event to go back to its
                    revertDuration: 0  //  original position after the drag
                });

            });

            $('#timetable').fullCalendar({
                defaultView: 'timetable',
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
                events: [
                    {
                        title: 'Cloud Computing',
                        start: '2017-03-17 07:00:00',
                        description: 'Course: 4H, Room: F-404, Group: A',
                        end: '2017-03-17 09:00:00',
                        backgroundColor: '#00a65a',
                        borderColor: 'white',
                        textColor: 'white',
                        fontSize: 90,
                        className: 'test'
                    }
                ],
                editable: true,
                droppable: true, // this allows things to be dropped onto the calendar
                dragRevertDuration: 0,
                eventDragStart: function (event, jsEvent, ui, view) {
                    var room = '';
                    room += '<div class="room-item ui-draggable ui-draggable-handle" style="">';
                    room += '<i class="fa fa-ellipsis-v"></i> ';
                    room += '<i class="fa fa-ellipsis-v"></i> Reverse to being!';
                    room += '</div>';
                    $('.rooms').html(room);
                },
                eventDragStop: function (event, jsEvent, ui, view) {
                    if (isEventOverDiv(jsEvent.clientX, jsEvent.clientY)) {
                        $('#calendar').fullCalendar('removeEvents', event._id);
                        var el = $("<div class='fc-event'>").appendTo('.courses').text(event.title);
                        el.draggable({
                            zIndex: 999,
                            revert: true,
                            revertDuration: 0
                        });
                        el.data('event', {title: event.title, id: event.id, stick: true});
                    }
                },
                eventClick: function (calEvent, jsEvent, view) {
//                    sweetAlert('Clicked', 'Processing ajax request to get all available rooms');
                    $(this).addClass('course-selected');
                    var room = '';
                    room += '<div class="room-item ui-draggable ui-draggable-handle" style="">';
                    room += '<i class="fa fa-ellipsis-v"></i> ';
                    room += '<i class="fa fa-ellipsis-v"></i> F-309';
                    room += '</div>';
                    $('.rooms').html(room);
                }
            });

            $('.room-item').removeAttr('style');
            $('.course-item').removeAttr('style');

            var isEventOverDiv = function (x, y) {

                var courses = $('.courses');
                var offset = courses.offset();
                offset.right = courses.width() + offset.left;
                offset.bottom = courses.height() + offset.top;

                // Compare
                if (x >= offset.left
                    && y >= offset.top
                    && x <= offset.right
                    && y <= offset.bottom) {
                    return true;
                }
                return false;

            }
        })
    </script>
@stop
