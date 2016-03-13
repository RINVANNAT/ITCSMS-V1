<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PromotionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $promotion_list = array('20','21','22','23','24','25','26','27','28','29','30','31','32','33','34','35');


        $promotions = array();
        foreach($promotion_list as $value){
            $temp = array(
                'name'=>$value,
                'create_uid'=>1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            );
            array_push($promotions,$temp);

        }

        foreach($promotions as $promotion){
            DB::table('promotions')->insert($promotion);
        }
    }
}
