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
                <div class="col-md-9">
                    {{--Timetable render--}}
                    <div id="timetable"></div>
                </div>
                <div class="col-md-3">

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

@stop
