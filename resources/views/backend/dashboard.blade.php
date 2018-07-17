@extends('backend.layouts.master')

@section('after-styles-end')

    {!! Html::style('plugins/datatables/dataTables.bootstrap.css') !!}
    {!! Html::style('plugins/fullcalendar/fullcalendar.css') !!}
    {!! Html::style('plugins/sweetalert2/dist/sweetalert2.css') !!}
    {!! Html::style('plugins/toastr/toastr.min.css') !!}
    {!! Html::style('plugins/select2/select2.min.css') !!}
    {!! Html::style('css/backend/schedule/timetable.css') !!}
    {!! Html::style('bower_components/bootstrap-toggle/css/bootstrap2-toggle.min.css') !!}

    <style type="text/css">
        .not-mine {
            border: 4px solid red;
        }

        .bg-primary {
            background-color: #337ab7 !important;
        }

        .bg-danger {
            background-color: #dd4b39 !important;
            color: #fff;
        }

        .toggle.ios, .toggle-on.ios, .toggle-off.ios {
            border-radius: 20px;
        }

        .toggle.ios .toggle-handle {
            border-radius: 20px;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #3c8dbc;
            color: #fff;
        }
    </style>

    <style type="text/css">
        .timeline-inverse > li > .timeline-item {
            background: #f0f0f0 !important;
            border: 1px solid #ddd !important;
            -webkit-box-shadow: none;
            box-shadow: none !important;
        }
    </style>

@stop

@section('page-header')
    <h1>
        {!! app_name() !!}
        <small>{{ trans('strings.backend.dashboard.title') }}</small>
    </h1>
@endsection

@section('content')
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('strings.backend.dashboard.welcome') }} {!! access()->user()->name !!}!</h3>
            <div class="box-tools pull-right">
                {{--<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>--}}
                {{--<div class="btn-group">--}}
                {{--<button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">--}}
                {{--Export data <span class="caret"></span>--}}
                {{--</button>--}}
                {{--<ul class="dropdown-menu" role="menu">--}}
                {{--<li><a href="#" id="export_student_list">Export current student list</a></li>--}}
                {{--<li><a href="#" id="export_student_list_custom">Export custom student list</a></li>--}}

                {{--</ul>--}}
                {{--</div>--}}
            </div>
        </div>
        <div class="box-body">

            <div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="fa fa-info"></i> Welcome to ITC-School Management Information System.</h4>
                <p>
                    This application is under construction with partial release. Please report the problems or your
                    demanding to our developers by using this <a href="{{route('admin.reporting.index')}}">REPORTING
                        SYSTEM</a>.
                    We appreciate your contributions and we hope to run this system in full scale very soon.
                </p>
                <p>
                    - Developer Team
                </p>
            </div>

            <?php
            $teacher = false;
            foreach ($user->roles as $role) {
                if ($role->name == "Teacher") {
                    $teacher = true;
                    break;
                }
            }
           ?>

            <div class="row">
                <div class="col-md-12">
                    <ul class="timeline timeline-inverse">

                        @if($teacher)
                            @if(isset($timetables) && access()->allow('teacher-view-timetable'))
                                @include('backend.dashboard.includes.timetable')
                            @endif
                            @include('backend.dashboard.teacher')
                        @endif

                        <li>
                            <i class="fa fa-user bg-purple"></i>

                            <div class="timeline-item">
                                {{--<span class="time"><i class="fa fa-clock-o"></i> 2 days ago</span>--}}

                                <h3 class="timeline-header"><a href="#">User information</a> view/update your
                                    information</h3>

                                <div class="timeline-body">
                                    <div class="panel panel-default">
                                        <div class="panel-body">
                                            <div role="tabpanel">

                                                <!-- Nav tabs -->
                                                <ul class="nav nav-tabs" role="tablist">
                                                    <li role="presentation" class="active">
                                                        <a href="#profile" aria-controls="profile" role="tab"
                                                           data-toggle="tab">{{ trans('navs.frontend.user.my_information') }}</a>
                                                    </li>
                                                </ul>

                                                <div class="tab-content">

                                                    <div role="tabpanel" class="tab-pane active" id="profile">
                                                        <table class="table table-striped table-hover table-bordered dashboard-table">
                                                            <tr>
                                                                <th>{{ trans('labels.frontend.user.profile.avatar') }}</th>
                                                                <td><img src="{!! $user->picture !!}"
                                                                         class="user-profile-image"/></td>
                                                            </tr>
                                                            <tr>
                                                                <th>{{ trans('labels.frontend.user.profile.name') }}</th>
                                                                <td>{!! $user->name !!}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>{{ trans('labels.frontend.user.profile.email') }}</th>
                                                                <td>{!! $user->email !!}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>{{ trans('labels.frontend.user.profile.created_at') }}</th>
                                                                <td>{!! $user->created_at !!}
                                                                    ({!! $user->created_at->diffForHumans() !!})
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th>{{ trans('labels.frontend.user.profile.last_updated') }}</th>
                                                                <td>{!! $user->updated_at !!}
                                                                    ({!! $user->updated_at->diffForHumans() !!})
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th>{{ trans('labels.general.actions') }}</th>
                                                                <td>
                                                                    <a href="{!! route('frontend.user.profile.edit') !!}"
                                                                       class="btn btn-primary btn-xs">{{ trans('labels.frontend.user.profile.edit_information') }}</a>

                                                                    @if ($user->canChangePassword())
                                                                        <a href="{!! route('auth.password.change') !!}"
                                                                           class="btn btn-warning btn-xs">{{ trans('navs.frontend.user.change_password') }}</a>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div><!--tab panel profile-->

                                                </div><!--tab content-->

                                            </div><!--tab panel-->

                                        </div><!--panel body-->

                                    </div><!-- panel -->
                                </div>
                            </div>
                        </li>

                        {{--<li>--}}
                        {{--<i class="fa fa-camera bg-purple"></i>--}}

                        {{--<div class="timeline-item">--}}
                        {{--<span class="time"><i class="fa fa-clock-o"></i> 2 days ago</span>--}}

                        {{--<h3 class="timeline-header"><a href="#">Mina Lee</a> uploaded new photos</h3>--}}

                        {{--<div class="timeline-body">--}}
                        {{--<img src="http://placehold.it/150x100" alt="..." class="margin">--}}
                        {{--<img src="http://placehold.it/150x100" alt="..." class="margin">--}}
                        {{--<img src="http://placehold.it/150x100" alt="..." class="margin">--}}
                        {{--<img src="http://placehold.it/150x100" alt="..." class="margin">--}}
                        {{--</div>--}}
                        {{--</div>--}}
                        {{--</li>--}}
                        <li>
                            <i class="fa fa-clock-o bg-gray"></i>
                        </li>
                    </ul>
                </div>
            </div>

        </div><!-- /.box-body -->
    </div><!--box box-success-->
@endsection

@section('after-scripts-end')

    {!! Html::script('plugins/moment/moment.min.js') !!}
    {!! Html::script('plugins/fullcalendar/fullcalendar.js') !!}
    {!! Html::script('plugins/sweetalert2/dist/sweetalert2.js') !!}
    {!! Html::script('plugins/toastr/toastr.min.js') !!}
    {!! Html::script('plugins/select2/select2.full.min.js') !!}
    {!! Html::script('bower_components/bootstrap-toggle/js/bootstrap2-toggle.min.js') !!}
    {!! Html::script('js/backend/schedule/timetable.js') !!}

    <script type="text/javascript">
        /** move timetable slot */
        function move_timetable_slot(event, start_date) {
            toggleLoading(true);
            $('#timetable').fullCalendar({
                eventDurationEditable: false
            });
            $.ajax({
                type: 'POST',
                url: '{!! route('teacher.move_timetable_slot') !!}',
                data: {
                    timetable_slot_id: event.id,
                    start_date: start_date
                },
                success: function (response) {
                    if (response.status === true) {
                        notify('info', 'Timetable Slot was moved.', 'Move Timetable Slot');
                        $('#timetable').fullCalendar('refresh');
                    } else {
                        notify('error', 'Something went wrong.', 'Move Timetable Slot');
                    }
                },
                error: function (response) {
                    if (response.status === 403) {
                        notify('error', 'You are not allowed to move  timetable slot.', 'Unauthorized');
                    }
                    else {
                        notify('error', 'Something went wrong.', 'Move Timetable Slot');
                    }
                },
                complete: function () {
                    get_teacher_timetable();
                    toggleLoading(false);
                }
            })
        }
        /** resize timetable slot */
        function resize_timetable_slot(timetable_slot_id, end_date, revertFunc) {
            $.ajax({
                type: 'POST',
                url: '{!! route('teacher.resize_timetable_slot') !!}',
                data: {
                    timetable_slot_id: timetable_slot_id,
                    end: end_date
                },
                success: function (response) {
                    if (response.status === true) {
                        notify('info', 'Timetable slot have been changed.', 'Resize Timetable Slot');
                    } else {
                        notify('error', 'Something went wrong.', 'Resize Timetable Slot');
                        revertFunc();
                    }
                },
                error: function (response) {
                    if (response.status === 403) {
                        notify('error', 'You are not allowed to resize timetable slot.', 'Unauthorized');
                    } else {
                        notify('error', response.message, "Resize timetable Slot");
                    }
                    get_teacher_timetable();
                },
                complete: function () {
                    get_teacher_timetable();
                    $('#timetable').fullCalendar({
                        eventDurationEditable: true
                    });
                }
            })
        }
        /** timetable */
        function show_timetable() {
            var date = new Date();
            var d = date.getDate(),
                m = date.getMonth(),
                y = date.getFullYear();
            $('#timetable_for_teacher').fullCalendar({
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
                timezone: 'Asia/Phnom_Penh',
                droppable: true,
                dragRevertDuration: 0,
                editable: '{{ access()->allow('teacher-edit-timetable') ? true : false }}',
                eventConstraint: {
                    start: '07:00:00',
                    end: '20:00:00'
                },
                eventDrop: function (event, delta, revertFunc) {
                    var start_date = event.start.format();
                    move_timetable_slot(event, start_date);
                    get_teacher_timetable();
                },
                eventRender: function (event, element, view) {
                    set_background_color_slot_not_allow();
                    var isFilter = $('input[name="filter_by"]:checked').val();
                    if (isFilter === 'on') {
                        event.editable = false;
                    } else {
                        if ('{{ auth()->user()->name }}' !== event.teacher_name) {
                            event.editable = false;
                        }
                    }
                    var object = '<a class="fc-time-grid-event fc-v-event fc-event fc-start fc-end course-item  fc-draggable fc-resizable" style="top: 65px; bottom: -153px; z-index: 1; left: 0%; right: 0%;">' +
                        '<div class="fc-content">' +
                        '<div class="container-room">';
                    if (typeof event.slotsForLanguage !== 'undefined') {
                        object += '<div class="side-courses" id="' + event.id + '" style="width: 100% !important;padding: 2px;" ​​​>';
                        // check conflict room and render
                        object += '<div class="row"> <div class="col-md-12"><div class="fc-title">' + event.course_name + '</div></div>';
                        event.editable = false;
                        object += '<div class="lang-info">';
                        for (var i = 0; i < event.slotsForLanguage.length; i++) {
                            if (i % 2 === 0) {
                                object += '<div class="lang-info-left"> Gr: ' + event.slotsForLanguage[i].group + ' (' + event.slotsForLanguage[i].building + '-' + event.slotsForLanguage[i].room + ')</div>';
                            } else {
                                object += '<div class="lang-info-right"> Gr: ' + event.slotsForLanguage[i].group + ' (' + event.slotsForLanguage[i].building + '-' + event.slotsForLanguage[i].room + ')</div>';
                            }
                        }
                        object += '</div></div></div>';
                    } else {
                        object += '<div class="side-course" id="' + event.id + '"​​​>';

                        // check conflict room and render
                        object += '<div class="fc-title">' + (event.course_name).substr(0, 20) + '...';
                        if (typeof event.type !== 'undefined') {
                            object += '<span class="text-primary"> (' + event.type + ')</span> ';
                        }
                        object += '<span class="text-primary"> (' + event.degree_name + event.grade_name + '-' + event.department_name + ')</span> ';
                        object += '</div>';

                        // check conflict lecturer and render
                        if (typeof event.conflict_lecturer !== 'undefined') {
                            if (event.conflict_lecturer.canMerge.length > 0 || event.conflict_lecturer.canNotMerge.length > 0) {
                                object += '<p class="text-primary conflict">' + event.teacher_name + '</p> ';
                            }
                            else {
                                object += '<p class="text-primary">' + event.teacher_name + '</p> ';
                            }
                        }
                        else {
                            object += '<p class="text-primary">' + event.teacher_name + '</p> ';
                        }

                        object += '</div>';
                    }

                    if (typeof event.slotsForLanguage === 'undefined') {
                        object += '<div class="side-room">' +
                            '<div class="room-name">';

                        // check conflict and render room
                        if (event.room !== null && event.building !== null) {
                            if (event.conflict_room === true) {
                                object += '<p class="fc-room bg-danger badge">' + event.building + '-' + event.room + '</p>';
                            } else {
                                object += '<p class="fc-room">' + event.building + '-' + event.room + '</p>';
                            }
                        }
                        object += '</div>';

                        // render groups
                        if (typeof event.groups !== 'undefined') {
                            if (event.groups.length > 0) {
                                var groups = '<p>Gr: ';
                                event.groups.forEach(function (ele) {
                                    groups += ele;
                                });
                                groups += '</p>';
                            }
                            object += groups;
                        }
                        object += '</div> ';
                    }
                    object += '<div class="clearfix"></div> ' +
                        '</div>' +
                        '</div>' +
                        '<div class="fc-bgd"></div>' +
                        '<div class="fc-resizer fc-end-resizer"></div>' +
                        '</a>';
                    return $(object);
                },
                eventOverlap: function (stillEvent, movingEvent) {
                    return stillEvent.allDay && movingEvent.allDay
                },
                eventResize: function (event, delta, revertFunc) {
                    var end = event.end.format();
                    resize_timetable_slot(event.id, end, revertFunc);
                    $('#timetable').fullCalendar('rerenderEvents');
                    hide_conflict_information();
                },
                loading: function (isLoading, view) {
                    if (isLoading) {
                        toggleLoading(isLoading);
                    }
                    else {
                        toggleLoading(false);
                    }
                },
                eventAfterRender: function (event, element, view) {
                    var isFilter = $('input[name="filter_by"]:checked').val();
                    if (isFilter === 'on') {
                        event.editable = false;
                    } else {
                        if ('{{ auth()->user()->name }}' !== event.teacher_name) {
                            event.editable = false;
                            element.find('.fc-content').parent().addClass('not-mine');
                        }
                    }
                }
            });
        }
        /** get timetable for teacher */
        function get_teacher_timetable() {
            toggleLoading(true);
            $.ajax({
                type: 'POST',
                url: '/admin/dashboard/get_teacher_timetable',
                data: $('#form_teacher_timetable').serialize(),
                success: function (response) {
                    $('#timetable_for_teacher').fullCalendar('removeEvents');
                    $('#timetable_for_teacher').fullCalendar('renderEvents', response.timetableSlots, true);
                    $('#timetable_for_teacher').fullCalendar('rerenderEvents');
                    toggleLoading(false);
                },
                complete: function () {
                    toggleLoading(false);
                }
            });
        }
        /** load script */
        $(function () {
            show_timetable();

            get_teacher_timetable();

            $('#form_teacher_timetable').on('change', function () {
                get_teacher_timetable();
            });

            $('#academic_years').select2({
                placeholder: "Academic Year"
            });

            $('#weeks').select2({
                placeholder: "Week"
            });
        });
    </script>

@stop