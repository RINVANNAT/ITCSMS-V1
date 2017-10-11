<div class="row">
    <div class="col-md-12">
        <div class="box box-default" style="border-top: 1px solid #f1f1f1;">
            <div class="box-header with-border">
                <i class="fa fa-building" data-toggle="tooltip" data-placement="top" title="List all building"></i>
                {{--<h3 class="box-title">{{ trans('labels.backend.schedule.timetable.rooms') }}</h3>--}}
                <div class="box-tools pull-right">

                    <div class="row">
                        <div class="col-xs-9 col-xs-offset-3 col-sm-9 col-sm-offset-9 col-md-9 col-md-offset-3">
                            <div class="input-group">
                                <input type="text"
                                       class="form-control input-sm"
                                       name="search_room_query"
                                       placeholder="{{ trans('strings.backend.timetable.search_room') }}"/>
                                <span class="input-group-btn">
                                    <button class="btn btn-primary btn-sm">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-body">
                @if(access()->allow('add-room-timetable-slot') && access()->allow('remove-room-timetable-slot'))
                    <div class="rooms">
                        @if(isset($rooms))
                            @foreach($rooms as $room)
                                <div class="room-item">
                                    <i class="fa fa-building-o"></i>
                                    {{ $room->name }}
                                </div>
                            @endforeach
                        @endif
                    </div>
                @else
                    <div class="alert alert-danger {{--alert-dismissible--}}">
                        {{--<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>--}}
                        <h4><i class="icon fa fa-info"></i>{{ trans('strings.backend.timetable.block_add_room') }}</h4>
                        <p>{{ trans('strings.backend.timetable.desc_block_add_room') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>