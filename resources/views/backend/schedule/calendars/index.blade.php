@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.schedule.calendar.title'))

@section('page-header')

    <h1>
        {{ trans('labels.backend.schedule.calendar.title') }}
        <small>{{ trans('labels.backend.schedule.calendar.sub_title') }}</small>
    </h1>

@endsection

@section('after-styles-end')

    <link rel="stylesheet" href="{{ asset('plugins/fullcalendar/fullcalendar.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2/dist/sweetalert2.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('plugins/datetimepicker/bootstrap-datetimepicker.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/calendar.css') }}"/>
    <link rel="stylesheet" href="{{ asset('plugins/iCheck/square/red.css') }}"/>

@stop

@section('content')

    <div class="row">
        <div class="col-md-3">

            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('labels.backend.schedule.event.panel.title') }}</h3>
                    @permission('create-event')
                    <div class="pull-right box-tools">
                        <button type="button"
                                class="btn btn-primary btn-xs"
                                data-toggle="modal"
                                data-target="#modal-add-event">
                            <i class="fa fa-plus-circle"></i> {{ trans('buttons.backend.schedule.event.panel.add') }}
                        </button>
                    </div>
                    @endauth
                </div>
                <div class="box-body">
                    {{--List all events--}}
                    <div id="external-events"></div>
                    @if(isset($events))
                    @endif
                    {{--End list events--}}
                </div>
            </div>
        </div>

        <div class="col-md-9">
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

    <script type="text/javascript" src="{{ asset('plugins/sweetalert2/dist/sweetalert2.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/select2/select2.full.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/backend/schedule/jquery-ui.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/moment/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/fullcalendar/fullcalendar.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/iCheck/icheck.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/backend/schedule/calendar.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>
    <script type="text/javascript">
        var calendar = function () {
            /* initialize the external events
             -----------------------------------------------------------------*/
            function ini_events(ele) {
                ele.each(function () {

                    // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
                    // it doesn't need to have a start or end
                    var eventObject = {
                        title: $.trim($(this).text()), // use the element's text as the event title
                        id: $(this).attr('event-id'),
                        className: $(this).attr('data-bg')
                    };

                    // store the Event Object in the DOM element so we can get to it later
                    $(this).data('eventObject', eventObject);

                    // make the event draggable using jQuery UI
                    $(this).draggable({
                        zIndex: 100070,
                        revert: true, // will cause the event to go back to its
                        revertDuration: 0  //  original position after the drag
                    });

                });
            }

            ini_events($('#external-events div.external-event'));

            /* initialize the calendar
             -----------------------------------------------------------------*/
            //Date for the calendar events (dummy data)
            var date = new Date();
            var d = date.getDate(),
                m = date.getMonth(),
                y = date.getFullYear();
            $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: ''
                },
                buttonText: {
                    today: 'Today',
                    month: 'Month',
                    week: 'Week',
                    day: 'Day'
                },
                // Random default events
                events: '/admin/schedule/events/{{ auth()->user()->getDepartment() }}',
                columnFormat: 'dddd',
                drop: function (date) { // this function is called when something is dropped
                    var originalEventObject = $(this).data('eventObject');

                    // we need to copy it, so that multiple events don't have a reference to the same object
                    var copiedEventObject = $.extend({}, originalEventObject);

                    // assign it the date that was reported
                    var tempDate = new Date(date);  //clone date
                    copiedEventObject.start = tempDate;
                    copiedEventObject.end = new Date(tempDate.setHours(tempDate.getHours() + 2));
                    copiedEventObject.allDay = true;

                    addEvent(copiedEventObject);
                    // $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);
                    // renderingEventsOnSideLeft($('#calendar').fullCalendar('getDate').format('YYYY'));

                },
                editable: true,
                droppable: true, // this allows things to be dropped onto the calendar !!!
                eventResize: function (event, delta, revertFunc) {
                    // Get current end date.
                    var end = event.end.format();
                    // Call resize event function.
                    resizeEvent(event.id, event.start.format(), end);

                },
                eventDrop: function (event, delta, revertFunc) {
                    moveEvent(event);
                },
                eventClick: function (event) {
                    removeEvent(event);
                },
                eventMouseover: function (calEvent, jsEvent) {
                    var tooltip = '<div class="tooltipevent">' + calEvent.title + '</div>';
                    var $tooltip = $(tooltip).appendTo('body');

                    $(this).mouseover(function (e) {
                        $(this).css('z-index', 10000);
                        $tooltip.fadeIn('500');
                        $tooltip.fadeTo('10', 1.9);
                    }).mousemove(function (e) {
                        $tooltip.css('top', e.pageY + 10);
                        $tooltip.css('left', e.pageX + 20);
                    });
                },
                eventMouseout: function (calEvent, jsEvent) {
                    $(this).css('z-index', 8);
                    $('.tooltipevent').remove();
                },
                eventRender: function (event, element) {
                    if (event.public == true) {
                        element.addClass('bg-red');
                    }
                    else {
                        element.addClass('bg-green');
                    }
                }
            });
        };
    </script>

@stop
