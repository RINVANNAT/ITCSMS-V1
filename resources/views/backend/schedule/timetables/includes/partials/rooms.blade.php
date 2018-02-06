<div class="row">
    <div class="col-md-12">
        <div class="box box-default" style="border: 1px solid #dddddd;">
            <div class="box-header with-border">
                <h3 class="box-title" data-toggle="tooltip" data-placement="top" title="Rooms">
                    <i class="fa fa-building"></i>
                </h3>

                <div class="box-tools pull-right">
                    <div class="form-group">
                        <input type="text"
                               class="form-control input-sm"
                               name="search_room_query"
                               placeholder="{{ trans('strings.backend.timetable.search_room') }}"/>
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