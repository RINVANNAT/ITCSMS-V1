<div class="row">
    <div class="col-md-12">
        <div class="box box-default" style="border-top: 1px solid #f1f1f1;">
            <div class="box-header with-border">
                <i class="fa fa-building"></i>
                <h3 class="box-title">{{ trans('labels.backend.schedule.timetable.rooms') }}</h3>
                <div class="box-tools pull-right">
                    <form id="search-rooms"
                          class="search-rooms"
                          method="POST"
                          action="{{ route('admin.schedule.timetables.search_rooms') }}">
                        <input type="text"
                               class="form-control input-sm"
                               name="search_room_query"
                               placeholder="SEARCH ROOMS..."/>
                    </form>
                </div>
            </div>
            <div class="box-body">
                @if(access()->allow('remove-room-timetable-slot'))
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
                        <h4><i class="icon fa fa-info"></i>Adding room is blocked</h4>
                        <p>You are not allow to add room. Please contact to Study Office to get more information.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>