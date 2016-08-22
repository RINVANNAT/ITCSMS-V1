<form id="form_editing_exam_room">
<table id="exam_room_list_table" class="table">
    <thead>
        <tr>
            <th width="20px;" class="room_editing" style="display: none"></th>
            <th width="20px;">No.</th>
            <th>Room Name</th>
            <th>Capacity</th>
            <th>Building</th>
            <th class="room_editing" style="display: none">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
                $index = 1;
        ?>
        @foreach($exam_rooms as $exam_room)
            <tr>
                <td class="room_editing" style="display: none"><input type="checkbox" name="exam_room[]" value="{{$exam_room->id}}" disabled/></td>
                <td>{{$index}}</td>
                <td>{{$exam_room->name." ".$exam_room->building->code}}</td>
                <td>{{$exam_room->nb_chair_exam}}</td>
                <td>{{$exam_room->building->name}}</td>
                <td class="room_editing" style="display: none">
                    <button type="button" class="btn btn-sm btn-warning" style="color: #fff; border-color: #3c8dbc;"><i class="fa fa-long-arrow-left"></i> <i class="fa fa-long-arrow-right"></i> Split</button>
                </td>
            </tr>
            <?php $index++ ?>
        @endforeach
    </tbody>

</table>
</form>