<?php

use Illuminate\Database\Seeder;

class GroupOptionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $group_options = array(
            array(
                'title' => 'មហាវិទ្យាល័យបច្ចេកទេសអគ្គិសនី',
                'description' => "(ដេប៉ាតឺម៉ង់អគ្គិសនីនិងថាមពល; ដេប៉ាតឺម៉ង់ព័ត៍មានវិទ្យានិងទូរគមនាគមន៏; ដេប៉ាតឺម៉ង់ឧស្សាហកម្មនិងមេកានិក)",
                'create_uid' => 1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'title' => "មហាវិទ្យាល័យសំណង់ ; មហាវិទ្យាល័យរ៉ែនិងភូគព្ឆសាស្ត្រ",
                'description' => '',
                'create_uid' => 1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'title' => "មហាវិទ្យាល័យគីមីឧស្សាហកម្ម និង មហាវិទ្យាល័យវារីសាស្ត្រ (ធនធានទឹក)",
                'description' => '',
                'create_uid' => 1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            )
        )
        ;
        foreach($group_options as $group_option){
            DB::table('group_options')->insert($group_option);
        }
    }
}
