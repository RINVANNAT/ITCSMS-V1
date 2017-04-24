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
    {!! Html::style('plugins/iCheck/all.css') !!}
    {!! Html::style('css/backend/schedule/timetable.css') !!}

@stop

@section('content')
    <div class="box box-success">
        <div class="box-header with-border">
            <div class="mailbox-controls">
                <div class="pull-right">
                    <a href="#">
                        <button class="btn btn-primary btn-sm" disabled="true">
                            {{ trans('buttons.backend.schedule.timetable.generate') }}
                        </button>
                    </a>
                    <button class="btn btn-warning btn-sm"
                            data-toggle="modal"
                            data-target="#clone-timetable">
                        {{ trans('buttons.backend.schedule.timetable.clone') }}
                    </button>
                    <a href="#">
                        <button class="btn btn-info btn-sm">
                            {{ trans('buttons.backend.schedule.timetable.publish') }}
                        </button>
                    </a>
                    <a href="#">
                        <button class="btn btn-danger btn-sm">
                            {{ trans('buttons.backend.schedule.timetable.save_change') }}
                        </button>
                    </a>
                </div>

                <form name="filter-courses-sessions"
                      id="filter-courses-sessions"
                      method="POST"
                      action="{{ route('admin.schedule.timetables.filter') }}">
                    @include('backend.schedule.timetables.includes.partials.option')
                </form>

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
                                    <h3 class="box-title">{{ trans('labels.backend.schedule.timetable.courses_sessions') }}</h3>
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
                                    <h3 class="box-title">{{ trans('labels.backend.schedule.timetable.rooms') }}</h3>
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

    {!! Html::script('plugins/ichecks/icheck.js') !!}
    {!! Html::script('plugins/datatables/jquery.dataTables.min.js') !!}
    {!! Html::script('plugins/datatables/dataTables.bootstrap.min.js') !!}
    {!! Html::script('plugins/jQueryUI/jquery-ui.js') !!}
    {!! Html::script('plugins/moment/moment.min.js') !!}
    {!! Html::script('plugins/fullcalendar/fullcalendar.js') !!}
    {!! Html::script('plugins/sweetalert2/dist/sweetalert2.js') !!}
    {!! Html::script('js/backend/schedule/timetable.js') !!}
    {!! Html::script('js/backend/schedule/clone-timetable.js') !!}

@stop
