<form id="form_editing_exam_room">
<table id="exam_room_list_table" class="table">
    <thead>
        <tr>
            <th width="20px;" class="room_editing" style="display: none"><input type="checkbox" id="exam_room_header" disabled/></th>
            <th width="20px;">No.</th>
            <th><a href="#" id="refresh_room_list">Room Name</a></th>
            <th><a href="#" id="sort_room_capacity">Capacity</a></th>
            <th>Building</th>
            <th>Description</th>
            <th class="room_editing" style="display: none">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
                $index = 1;
                $average_seat = 0;
                foreach ($exam_rooms as $exam_room) {
                    $average_seat = $average_seat+$exam_room->nb_chair_exam;
                }

                if(count($exam_rooms)>0){
                    $average_seat = $average_seat / count($exam_rooms);
                }

        ?>
        @foreach($exam_rooms as $exam_room)
            <tr>
                <td class="room_editing" style="display: none"><input type="checkbox" class="exam_room_checkbox" name="exam_room[]" data-roomname="{{$exam_room->name}}" value="{{$exam_room->id}}" disabled/></td>
                <td>{{$index}}</td>
                <td>{{$exam_room->building->code.$exam_room->name}}</td>
                @if($exam_room->nb_chair_exam < $average_seat -5 )
                <td class="badge bg-red">
                @elseif($exam_room->nb_chair_exam > $average_seat +5)
                <td class="badge bg-yellow">
                @else
                <td>
                @endif
                    {{$exam_room->nb_chair_exam}}
                </td>
                <td>{{$exam_room->building->name}}</td>
                <td>{{$exam_room->description}}</td>
                <td class="room_editing" style="display: none">
                    <button type="button" class="btn_room_split btn btn-sm btn-warning" data-roomname="{{$exam_room->name}}" data-roomid="{{$exam_room->id}}" data-capacity="{{$exam_room->nb_chair_exam}}" data-building="{{$exam_room->building->id}}" style="color: #fff; border-color: #3c8dbc;">
                        <i class="fa fa-long-arrow-left"></i> <i class="fa fa-long-arrow-right"></i> Split
                    </button>
                    <button type="button" class="btn_room_edit btn btn-sm btn-info" data-roomname="{{$exam_room->name}}" data-roomid="{{$exam_room->id}}" data-capacity="{{$exam_room->nb_chair_exam}}" data-building="{{$exam_room->building->id}}" data-description="{{$exam_room->description}}" style="color: #fff; border-color: #3c8dbc;">
                        <i class="fa fa-pencil-square-o"></i> Edit
                    </button>
                </td>
            </tr>
            <?php $index++ ?>
        @endforeach
    </tbody>

</table>
</form>