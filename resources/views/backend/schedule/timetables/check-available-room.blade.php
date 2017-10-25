@extends ('backend.layouts.master')

@section ('title', 'Check Available Room | SMIS')

@section('page-header')

    <h1>
        Check Available Room
        <small>Checking available room</small>
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
    </style>

@stop

@section('content')
    <div class="box box-success">
        <div class="box-header with-border">
            <div class="mailbox-controls">
                <div class="pull-right">
                    <button class="btn btn-primary btn-sm" id="refresh">
                        <i class="fa fa-refresh"></i> Refresh
                    </button>
                </div>
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
                defaultView: 'agendaWeek',
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
                events: rooms
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

        $(function () {

            getRooms($('select[name="academic"]').val(), $('select[name="week"]').val());

            $(document).on('change', '#room-filter', function (e) {
                e.preventDefault();
                getRooms($('select[name="academic"]').val(), $('select[name="week"]').val());
            })
        });
    </script>

@stop
