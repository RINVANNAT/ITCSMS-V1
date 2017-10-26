@extends ('backend.layouts.master')

@section ('title', 'Check Unavailable Room | SMIS')

@section('page-header')

    <h1>
        Check Unavailable Room
        <small>Checking unavailable room</small>
    </h1>

@endsection

@section('after-styles-end')

    {!! Html::style('plugins/fullcalendar/fullcalendar.css') !!}
    {!! Html::style('plugins/sweetalert2/dist/sweetalert2.css') !!}
    {!! Html::style('plugins/toastr/toastr.min.css') !!}
    {!! Html::style('bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css') !!}

    <style type="text/css">
        .fc-time-grid .fc-slats td {
            height: 3.5em;
        }

        .fc-event, .fc-event:hover, .ui-widget .fc-event {
            color: #000;
            text-decoration: none;
        }

        .fc-event {
            background-color: #f1f1f1;
            border: 1px solid #dddddd;
            overflow: scroll;
        }

        .fc-day-grid-event .fc-content {
            padding: 10px;
            white-space: normal;
            overflow: hidden;
        }

        button.btn {
            margin-top: 5px;
            margin-bottom: 5px;
        }

        .fc-time {
            font-size: 14px;
        }

        .modal-content {
            background-image: none !important;
        }
    </style>

@stop

@section('content')
    <div class="box box-success">
        <div class="box-header with-border">
            <div class="mailbox-controls">
                {{--<div class="pull-right">
                    <button class="btn btn-primary btn-sm" id="refresh">
                        <i class="fa fa-refresh"></i> Refresh
                    </button>
                </div>--}}
                <form class="form-inline" id="room-filter">
                    @if(isset($academics))
                        <div class="form-group">
                            <select name="academic" class="form-control">
                                @foreach($academics as $index => $academic)
                                    <option {{ $index == 0 ? 'selected' : '' }} value="{{ $academic->id }}">{{ $academic->name_latin }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    @if(isset($weeks))
                        <div class="form-group">
                            <select name="week" class="form-control">
                                @foreach($weeks as $index => $week)
                                    <option {{ $index == 0 ? 'selected' : '' }} value="{{ $week->id }}">{{ $week->name_en }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                </form>
            </div>
        </div>

        <div class="box-body">
            <div class="row">
                <div class="col-md-12">
                    <div id="timetable"></div>
                </div>
            </div>
        </div>
    </div>

@stop

@section('after-scripts-end')

    {!! Html::script('plugins/iCheck/icheck.js') !!}
    {!! Html::script('plugins/moment/moment.min.js') !!}
    {!! Html::script('plugins/fullcalendar/fullcalendar.js') !!}
    {!! Html::script('plugins/sweetalert2/dist/sweetalert2.js') !!}
    {!! Html::script('plugins/toastr/toastr.min.js') !!}
    {!! Html::script('bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js') !!}

    <script type="text/javascript">
        function showRoom(rooms) {
            $('#timetable').fullCalendar({
                defaultView: 'basicWeek',
                defaultDate: '2017-01-01',
                header: false,
                footer: false,
                allDaySlot: false,
                hiddenDays: [0],
                height: 650,
                fixedWeekCount: false,
                minTime: '07:00:00',
                maxTime: '20:00:00',
                slotLabelFormat: 'h:mm a',
                columnFormat: 'dddd',
                timezone: 'Asia/Phnom_Penh',
                events: rooms,
                eventRender: function (event, element, view) {
                    var template = '';

                    template += '<a class="fc-day-grid-event fc-h-event fc-event fc-start fc-end">' +
                        '<div class="fc-content">' +
                        '<span class="fc-time">' + moment(event._start._i).format('LT') + ' - </span>' +
                        '<span class="fc-time">' + moment(event._end._i).format('LT') + '</span> <br/>';

                    $.each(event.rooms, function (key, val) {
                        template += '<span class="fc-title">' +
                            '<button class="btn btn-danger" id="showRoomInfo" data-toggle="popover" data-trigger="focus" title="Dismissible popover" data-content="dsafsdfasfd"' +
                            'data-start="' + event._start._i + '" ' +
                            'data-end="' + event._end._i + '" ' +
                            'data-room-id="' + val.id + '" ' +
                            'data-timetable-slot-id="' + val.timetable_slot_id + '">' +
                            '<i class="fa fa-building-o"></i> ' + val.room +
                            '</button>' +
                            '</span> ';
                    });

                    template += '</div></a>';

                    return template;
                }
            });
        }

        function getRooms(academic, week) {
            $.ajax({
                type: 'POST',
                url: '/admin/schedule/timetables/check-available-room/get_rooms',
                data: {
                    academic: academic,
                    week: week,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    showRoom(response.data);
                    $('#timetable').fullCalendar('removeEvents');
                    $('#timetable').fullCalendar('renderEvents', response.data);
                }
            })
        }
        function getUnavailableRoomInfo(timetableSlotId) {

            $.ajax({
                type: 'POST',
                url: '/admin/schedule/timetables/check-available-room/get-unavailable-room-info',
                data: {
                    timetableSlotId: timetableSlotId,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    var course = response.course_name;
                    var teacher = response.teacher_name;
                    var dept = response.dept_code;
                    var room = response.building + '-' + response.room;
                    sweetAlert({
                        type: 'info',
                        showConfirmButton: false,
                        html: '<table class="table table-bordered"> ' +
                        '<tr><th>Course</th><td>' + course + '</td></tr>' +
                        '<tr><th>Teacher</th><td>' + teacher + '</td></tr>' +
                        '<tr><th>Dept</th><td>' + dept + '</td></tr>' +
                        '<tr><th>Room</th><td>' + room + '</td></tr>' +
                        '</table>'
                    })
                }
            })
        }

        $(function () {

            getRooms($('select[name="academic"]').val(), $('select[name="week"]').val());

            $(document).on('change', '#room-filter', function (e) {
                e.preventDefault();
                getRooms($('select[name="academic"]').val(), $('select[name="week"]').val());
            });

            $(document).on('click', '#showRoomInfo', function () {
                var data_timetable_slot_id = $(this).attr('data-timetable-slot-id');
                getUnavailableRoomInfo(data_timetable_slot_id)
            });

            $('.showRoomInfo').popover({
                trigger: 'over'
            });

        });
    </script>

@stop
