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
        //$promotion_list = array('1','2','3','4','5','6','7','8','20','21','22','23','24','25','26','27','28','29','30','31','32','33','34','35');


        $promotions = array();
        for($value=1;$value<36;$value++ ){
            $temp = array(
                'id'=>$value,
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
