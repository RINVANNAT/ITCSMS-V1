<div class="row">
    <div class="col-md-12">
        <div class="box box-solid">
            <div class="box-header with-border">
                <i class="fa fa-building-o"></i>
                <h3 class="box-title">{{ trans('labels.backend.schedule.timetable.rooms') }}</h3>
            </div>
            <div class="box-body">
                <div class="rooms">
                    @if(isset($rooms))
                        @foreach($rooms as $room)
                            <div class="room-item">
                                <i class="fa fa-ellipsis-v"></i>
                                <i class="fa fa-ellipsis-v"></i>
                                {{ $room->name }}
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>