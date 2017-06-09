@extends ('backend.layouts.popup_master')

@section ('title', 'Print Timetable | Timetable Management')

@section('after-styles-end')

    {!! Html::style('plugins/iCheck/all.css') !!}
    {!! Html::style('plugins/toastr/toastr.min.css') !!}
    {!! Html::style('css/backend/schedule/timetable.css') !!}

@stop

@section('content')

    <div class="box box-success">
        <form action="{{ route('timetables.export_file') }}" method="POST" id="form_export_timetable">
            {{ csrf_field() }}
            <div class="box-header with-border">
                <h3 class="box-title">Export Timetable</h3>
            </div>

            <div class="box-body">
                <input type="hidden" name="timetable" value="{{ $timetable->id }}"/>

                @if(count($weeks) >0)
                    <div class="form-group">
                        <div class="col-xs-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="all-weeks">
                                        <input type="checkbox"
                                               id="all-weeks"
                                               class="square"> {{ trans('labels.backend.schedule.timetable.modal_clone.body.all_weeks') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{--class="square"--}}
                    <div class="form-group">
                        <div class="col-xs-12">
                            <div class="row">
                                @foreach($weeks as $week)
                                    <div class="col-xs-4">
                                        <label for="{{ $week->id }}">
                                            <input type="checkbox"
                                                   data-target="weeks"
                                                   name="weeks[]"
                                                   value="{{ $week->id }}"
                                                   class="square weeks_value"/> {{ $week->name_en }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
                @if(count($groups)>0)
                    <div class="form-group" style="margin-top: 200px !important;">
                        <div class="col-xs-12">
                            <div class="row">
                                <div class="col-xs-12">
                                    <label for="all-groups">
                                        <input type="checkbox"
                                               id="all-groups"
                                               class="square"> {{ trans('labels.backend.schedule.timetable.modal_clone.body.all_groups') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-xs-12">
                            <div class="row">
                                @foreach($groups as $group)
                                    <div class="col-xs-2">
                                        <label for="{{ $group->id }}">
                                            <input type="checkbox"
                                                   data-target="groups"
                                                   name="groups[]"
                                                   value="{{ $group->id }}"
                                                   class="square groups_value"/> {{ $group->code }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

            </div>

            <div class="box-footer">
                <div class="form-group">
                    <div class="col-md-offset-2 col-md-8">
                        <button type="submit" href="{{ route('timetables.template_print') }}" class="btn btn-primary btn-sm btn-sm"><i class="fa fa-download"></i></button>
                        <button class="btn btn-danger btn-sm" id="clone-window-print"><i class="fa fa-times-circle-o"></i></button>
                    </div>
                </div>
            </div>
        </form>

    </div>

@stop

@section('after-scripts-end')
    {!! Html::script('plugins/iCheck/icheck.js') !!}
    {!! Html::script('js/backend/schedule/clone-timetable.js') !!}
    {!! Html::script('js/backend/schedule/timetable-print.js') !!}
@stop

