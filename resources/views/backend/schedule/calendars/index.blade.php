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
    <style type="text/css">
        .fc-content {
            height: 18px;
        }

        .bg-green, .bg-red, .bg-aqua {
            padding:4px;
            border: 1px solid #fff;
        }

        .tooltipevent {
            padding: 10px;
            border-radius: 4px;
            width: 200px;
            height: auto;
            background: #ccc;
            position: absolute;
            z-index: 10001
        }
    </style>
@stop

@section('content')
    <div class="row">
        <div class="col-md-3">

            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Events</h3>
                    <div class="pull-right box-tools">
                        <button type="button"
                                class="btn btn-primary btn-sm"
                                data-toggle="modal"
                                data-target="#modal-add-event">
                            <i class="fa fa-plus-circle"></i> Add
                        </button>
                    </div>
                </div>
                <div class="box-body">

                    @if(isset($events))
                        <div id="external-events"></div>
                    @endif

                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Calendar</h3>
                    <div class="pull-right box-tools">
                        <button type="button" class="btn btn-success btn-sm">
                            <i class="fa fa-file-excel-o"></i> Export
                        </button>
                        <button type="button" class="btn btn-warning btn-sm">
                            <i class="fa fa-power-off"></i> Uneditable
                        </button>
                    </div>
                </div>
                <div class="box-body">

                    <div id="calendar"></div>

                </div>
            </div>
        </div>
    </div>

    @include('backend.schedule.calendars.includes.modal-event')
@stop

@section('after-scripts-end')

    <script type="text/javascript" src="{{ asset('plugins/sweetalert2/dist/sweetalert2.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/backend/schedule/jquery-ui.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/moment/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/fullcalendar/fullcalendar.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/backend/schedule/calendar.js') }}"></script>

@stop
