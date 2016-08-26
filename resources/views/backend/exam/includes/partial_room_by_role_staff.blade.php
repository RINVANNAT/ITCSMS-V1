<table class="table text-center">
    <thead>
    <tr>
        <th>ROOM</th>
    </tr>
    </thead>
    <tbody>


    <?php
            //the php code is to display room data in two lines
    if(fmod(count($arrayRooms), 2) != 0) {
    for($i = 0; $i <=  (int)(count($arrayRooms)/2) ; $i++) {

    ?>
    <tr>
        <td><?php echo $i+1;?></td>
        <?php if($i < (int)(count($arrayRooms)/2) ) {
        ?>
        <td><input class="checkbox_room" type="checkbox" value="<?php echo $arrayRooms[2*$i]['room_id'];?>"></td>
        <td><?php echo $arrayRooms[2*$i]['room_name'];?></td>

        <td><input class="checkbox_room"  type="checkbox" value="<?php echo $arrayRooms[2*$i+1]['room_id'];?>"></td>
        <td><?php echo $arrayRooms[2*$i+1]['room_name'];?></td>

        <?php

        } else if($i == (int)(count($arrayRooms)/2)  ) {?>

        <td><input  class="checkbox_room"  type="checkbox" value="<?php echo $arrayRooms[2*$i]['room_id'];?>"></td>
        <td><?php echo $arrayRooms[2*$i]['room_name'];?></td>
        <?php
        }
        ?>

    </tr>

    <?php

    }
    } else {
    for($i = 0; $i < (int)(count($arrayRooms)/2) ; $i++) {
    ?>
    <tr>
        <td><?php echo $i+1;?></td>
        <td><input class="checkbox_room" type="checkbox" value="<?php echo $arrayRooms[2*$i]['room_id'];?>"></td>
        <td><?php echo $arrayRooms[2*$i]['room_name'];?></td>

        <td><input class="checkbox_room"  type="checkbox" value="<?php echo $arrayRooms[2*$i+1]['room_id'];?>"></td>
        <td><?php echo $arrayRooms[2*$i+1]['room_name'];?></td>
    </tr>

    <?php
    }
    }
    ?>
    </tbody>
</table>