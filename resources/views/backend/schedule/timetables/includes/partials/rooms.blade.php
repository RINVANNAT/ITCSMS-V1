<div class="row">
    <div class="col-md-12">
        <div class="box box-solid">
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
            </div>
        </div>
    </div>
</div>