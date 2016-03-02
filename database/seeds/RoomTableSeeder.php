<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $gic_rooms = array(
            '206F','207F','208F','209F','210F','306F','307F','308F','309F','310F','404F'
        );
        $sequence = 1;
        $rooms = array();
        foreach($gic_rooms as $gic_room){
            $room = array(
                'id'=>$sequence,
                'name' => $gic_room,
                'department_id' => 4,
                'building_id'=>6,
                'capacity'=>30,
                'room_type_id'=>1,
                'create_uid'=>1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            );
            array_push($rooms,$room);
            $sequence++;

        }

        foreach($rooms as $room){
            DB::table('rooms')->insert($room);
        }
    }
}
