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
        $('#calendar').fullCalendar({
            schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',
            editable: true,
            selectable: true,
            aspectRatio: 1,
            scrollTime: '00:00',
            header: {
                left: 'promptResource today prev,next',
                center: 'title',
                right: 'timelineThreeDays'
            },
            customButtons: {
            promptResource: {
                text: '+ room',
                click: function() {
                var title = prompt('Room name');
                if (title) {
                    $('#calendar').fullCalendar(
                    'addResource',
                    { title: title },
                    true // scroll to the new resource?
                    );
                }
                }
            }
            },
            defaultView: 'timelineThreeDays',
            defaultDate: '2018-12-01',
            nowIndicator: true,
            locale: 'en',
            displayEventTime: true,
            displayEventEnd: true,
            now: '2018-12-02T09:25:00',
            views: {
                timelineThreeDays: {
                    type: 'timeline',
                    minTime: "07:00:00",
                    maxTime: "17:00:00",
                    duration: { days: 7 },
                    slotDuration: '00:30:00',
                    slotLabelFormat: [
                        'dddd, DD/MM/YY', // top level of text
                        'h(:mm)a'        // lower level of text
                    ]
                    // slotDuration: {hours: 6},
                    // snapDuration: {hours: 24},
                }
            },
            eventOverlap: false,
            resourceGroupField: 'building',
            resourceAreaWidth: '25%',
            resourceLabelText: 'Rooms',
            resources: [
                { id: 'a', building: 'Building A', title: 'A102' },
                { id: 'b', building: 'Building A', title: 'A103', eventColor: 'green' },
                { id: 'c', building: 'Building A', title: 'A104', eventColor: 'orange' },
                { id: 'd', building: 'Building B', title: 'B200'},
                { id: 'e', building: 'Building B', title: 'B202' },
                { id: 'f', building: 'Building F', title: 'F301', eventColor: 'red' },
                { id: 'g', building: 'Building F', title: 'F401' },
                { id: 'h', building: 'Building F', title: 'F404' },
                { id: 'i', building: 'Building I', title: 'I206' },
                { id: 'j', building: 'Building I', title: 'I208' },
                { id: 'k', building: 'Building I', title: 'I209' }
            ],
            events: [
                { id: '1', resourceId: 'a', start: '2018-12-01T12:00:00', end: '2018-12-01T13:30:00', title: 'I3GIC' },
                { id: '2', resourceId: 'b', start: '2018-12-02T12:00:00', end: '2018-12-03T12:00:00', title: 'I5GIM-A' },
                { id: '3', resourceId: 'e', start: '2018-12-01', end: '2018-12-04', title: 'event 3' },
                { id: '4', resourceId: 'f', start: '2018-12-02T12:00:00', end: '2018-12-05T12:00:00', title: 'I5GIM-B' },
                { id: '5', resourceId: 'g', start: '2018-12-01T12:00:00', end: '2018-12-06T12:00:00', title: 'I3GEE Calculus' }
            ]
        });
    });
    </script>

@stop
