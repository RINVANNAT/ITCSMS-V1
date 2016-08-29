

    <table class="table ">
        <thead>
        <tr>
            <th> </th>
            <th>Saff Name</th>
            <th> Department </th>
            <th> Selected Rooms </th>
        </tr>
        </thead>
        <tbody>
            @foreach($staffs as $staff)
                <tr>
                    <td><input  class="checkbox_staff" type="checkbox" value="{{$staff['id']}}"></td>
                    <td> {{$staff['text']}}</td>
                    <td> {{$staff['department_name']}}</td>
                    <td> <?php if(is_array($staff['room_name']))  { $i =0;
                         ?>
                            <?php
                            foreach($staff['room_name'] as $room) {
                                $i++;
                            ?>
                                <a href="#" value="{{$room['room_id']}}" id="room_<?php echo $staff['id'].'_'.$room['room_name'];?>" onclick="deleteRoom(<?php echo "'".$staff['id'].'_'.$room['room_name']."'";?>)">{{$room['room_name']}}</a>

                            <?php } ?>

                        <?php
                         } else {

                        ?>
                            {{$staff['room_name']}}

                        <?php
                        }
                         ?>
                    </td>
                </tr>

            @endforeach
        </tbody>
    </table>