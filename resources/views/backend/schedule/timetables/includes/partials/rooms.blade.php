<div class="row">
    <div class="col-md-12">
        <div>
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active">
                    <a href="#room"
                       aria-controls="room"
                       role="tab"
                       data-toggle="tab"><i class="fa fa-building"></i> Room</a></li>
                <li role="presentation">
                    <a href="#lecturer"
                       aria-controls="lecturer"
                       role="tab"
                       data-toggle="tab"><i class="fa fa-users"></i> Lecturer</a></li>
            </ul>
            <div class="tab-content">
                <div role="tabpanel"
                     class="tab-pane active"
                     id="room">
                    <div class="box box-default" style="border: 1px solid #dddddd; border-top: 0; border-top-left-radius: 0; border-top-right-radius: 0;">
                        <div class="box-header with-border">
                            <div class="form-group">
                                <input type="text"
                                       style="border-radius: 4px !important;"
                                       class="form-control"
                                       name="search_room_query"
                                       placeholder="Find room..."/>
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
                <div role="tabpanel"
                     class="tab-pane"
                     id="lecturer">
                    <div class="box box-default" style="border: 1px solid #dddddd; border-top: 0;">
                        <div class="box-header with-border">
                            <div class="form-group">
                                <input type="text"
                                       style="border-radius: 4px !important;"
                                       class="form-control"
                                       id="search-employee"
                                       name="employee_query"
                                       placeholder="Find lecturer..."/>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="select2-results">
                                <ul class="select2-results__options"
                                    role="tree"
                                    id="employee-viewer"
                                    aria-expanded="true"
                                    aria-hidden="false"></ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>