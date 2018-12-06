@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.schedule.calendar.title'))

@section('page-header')

    <h1>
        {{ trans('labels.backend.schedule.calendar.title') }}
        <small>{{ trans('labels.backend.schedule.calendar.sub_title') }}</small>
    </h1>

@endsection

@section('after-styles-end')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.print.css" media="print"/>
    <link rel="stylesheet" href="{{ asset('plugins/fullcalendar-scheduler-1.9.4/scheduler.min.css') }}"/>
    <!-- <link rel="stylesheet" href="{{ asset('plugins/sweetalert2/dist/sweetalert2.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('plugins/datetimepicker/bootstrap-datetimepicker.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/backend/schedule/calendar.css') }}"/>
    <link rel="stylesheet" href="{{ asset('plugins/iCheck/square/red.css') }}"/> -->

@stop

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('labels.backend.schedule.calendar.panel.title') }}</h3>
                    <div class="pull-right box-tools">
                        <button type="button" class="btn btn-success btn-xs">
                            <i class="fa fa-file-excel-o"></i> {{ trans('buttons.backend.schedule.calendar.panel.export') }}
                        </button>
                        @permission('edit-event')
                        <button type="button" class="btn btn-danger  btn-xs">
                            <i class="fa fa-power-off"></i> {{ trans('buttons.backend.schedule.calendar.panel.uneditable') }}
                        </button>
                        @endauth
                    </div>
                </div>
                <div class="box-body">
                    {{--Rendering calendar--}}
                    <div id="calendar"></div>
                    {{--End rendering calendar--}}
                </div>
            </div>
        </div>
    </div>

    @include('backend.schedule.calendars.includes.modal-event')

@stop

@section('after-scripts-end')

    <!-- <script type="text/javascript" src="{{ asset('plugins/sweetalert2/dist/sweetalert2.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/select2/select2.full.min.js') }}"></script> -->
    <script type="text/javascript" src="{{ asset('js/backend/schedule/jquery-ui.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/moment/moment.min.js') }}"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.js"></script>
    <script type="text/javascript" src="{{ asset('plugins/fullcalendar-scheduler-1.9.4/scheduler.min.js') }}"></script>
    <!-- <script type="text/javascript" src="{{ asset('js/backend/schedule/calendar.js') }}"></script> -->
    <!-- <script type="text/javascript" src="{{ asset('plugins/datetimepicker/bootstrap-datetimepicker.min.js') }}"></script> -->
    <script type="text/javascript">

        $(function() {
            var date = new Date();
            var d = date.getDate(),
                m = date.getMonth(),
                y = date.getFullYear();
            $('#calendar').fullCalendar({
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
                height: 735,
                fixedWeekCount: false,
                minTime: '07:00:00',
                maxTime: '20:00:00',
                slotLabelFormat: 'h:mm a',
                columnFormat: 'dddd',
                timezone: 'Asia/Phnom_Penh',
            });
        });
    </script>

@stop
