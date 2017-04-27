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