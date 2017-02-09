<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TitleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class=TitleTableSeeder
     * @return void
     */
    public function run()
    {
        $titles = array(
            array(
                'title_kh' => 'លោក',
                'title_en' => 'Mr.',
                'title_fr' => 'M.',
                'create_uid' => 1,
                'created_at' => \Carbon\Carbon::now()
            ),
            array(
                'title_kh' => 'លោកបណ្ឌិត',
                'title_en' => 'Dr.',
                'title_fr' => 'Dr.',
                'create_uid' => 1,
                'created_at' => \Carbon\Carbon::now()
            ),
            array(
                'title_kh' => 'លោកស្រី',
                'title_en' => 'Ms.',
                'title_fr' => 'Mme.',
                'create_uid' => 1,
                'created_at' => \Carbon\Carbon::now()
            ),
            array(
                'title_kh' => 'លោកស្រីបណ្ឌិត',
                'title_en' => 'Dr.',
                'title_fr' => 'Dr.',
                'create_uid' => 1,
                'created_at' => \Carbon\Carbon::now()
            ),
            array(
                'title_kh' => 'កញ្ញា',
                'title_en' => 'Ms.',
                'title_fr' => 'Ms.',
                'create_uid' => 1,
                'created_at' => \Carbon\Carbon::now()
            )
        )
        ;
        foreach($titles as $title){
            DB::table('titles')->insert($title);
        }
    }
}
