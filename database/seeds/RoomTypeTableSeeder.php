<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $room_types = array(
            array(
                'id'=>1,
                'name' => 'Teaching',
                'create_uid'=>1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'id'=>2,
                'name' => 'Laboratory',
                'create_uid'=>1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'id'=>3,
                'name' => 'Office',
                'create_uid'=>1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'id'=>4,
                'name' => 'Storage',
                'create_uid'=>1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
        )
        ;
        foreach($room_types as $room_type){
            DB::table('roomTypes')->insert($room_type);
        }
    }
}
