@extends ('backend.layouts.popup_master')

@section ('title', trans('labels.backend.exams.title') . ' | ' . trans('labels.backend.exams.course.add_course'))

@section('content')


        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Request Input Score</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                {{--here what i need to write --}}
                <div class="col-sm-12 no-padding">
                    <label class="col-sm-3 "> Select Building: </label>
                    <div class="col-sm-3 no-padding">
                        <select style="margin-left: -55px" name="building" id="building_id">
                            @foreach($buildings as $building)
                                <option value="{{$building->id}}"> {{$building->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <label class="col-sm-3" style="margin-left: -50px"> Select Room: </label>
                    <div class="col-sm-3 no-padding">
                        <select name="room" id="room_id" style="margin-left: -70px">
                            @foreach($rooms as $room)
                                <option class="col-sm-4" value="{{$room->id}}"> {{$room->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-sm-12" style="margin-top: 10px;">
                    <label class="col-sm-3 no-padding"> Select Subject: </label>
                    <div class="col-sm-9 no-padding">
                        <select name="subject" id="subject_id" style="float: left; margin-left: -62px">
                            @foreach($rooms as $room)
                                <option class="col-sm-4" value="{{$room->id}}"> {{$room->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{--<div class="col-sm-12">--}}
                    {{--<botton class="btn btn-default" id="request_input"> OK </botton>--}}
                {{--</div>--}}

            </div>
        </div>

    <div class="box box-success">
        <div class="box-body">
            <div class="pull-right">
                <a href="#" id="request_input" class="btn btn-primary btn-xm">OK</a>
            </div>

            {{--<div class="pull-right">--}}
                {{--<input type="button" id="btn-save" class="btn btn-danger btn-xs" value="{{ trans('buttons.general.save') }}" />--}}
            {{--</div>--}}
            <div class="clearfix"></div>
        </div><!-- /.box-body -->
    </div><!--box-->
@stop

@section('after-scripts-end')
   {{--here where i need to write the js script --}}
@stop