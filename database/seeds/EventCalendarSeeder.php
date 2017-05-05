<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventCalendarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $events = [
            [
                'category_event_id' => 3,
                'title'             => "ទិវាចូលឆ្នាំសកល",
                'study'             => 1,
                'fix'               => 1
            ],
            [
                'category_event_id' => 2,
                'title'             => "ទិវាជ័យជំនះលើរបបប្រល័យពូជសាសន៍",
                'study'             => 1,
                'fix'               => 1
            ],
            [
                'category_event_id' => 2,
                'title'             => "ពិធីបុណ្យមាឃបូជា",
                'study'             => 0,
                'fix'               => 0
            ],
            [
                'category_event_id' => 3,
                'title'             => "ទិវានារីអន្តរជាតិ",
                'study'             => 1,
                'fix'               => 1
            ],
            [
                'category_event_id' => 2,
                'title'             => "ពិធីបុណ្យចូលឆ្នាំថ្មី ប្រពៃណីជាតិ",
                'study'             => 0,
                'fix'               => 0
            ],
            [
                'category_event_id' => 3,
                'title'             => "ទិវាពលកម្មអន្តរជាតិ",
                'study'             => 1,
                'fix'               => 1
            ],
            [
                'category_event_id' => 2,
                'title'             => "ពិធីបុណ្យវិសាខបូជា",
                'study'             => 0,
                'fix'               => 0
            ],
            [
                'category_event_id' => 2,
                'title'             => "ព្រះរាជពិធីបុណ្យចម្រើនព្រះជន្ម ព្រះករុណាព្រះបាទសម្តេចព្រះបរមនាថ នរោត្តម សីហមុនី ព្រះមហាក្សត្រនៃព្រះរាជាណាចក្រកម្ពុជាិ",
                'study'             => 0,
                'fix'               => 1
            ],
            [
                'category_event_id' => 2,
                'title'             => "ព្រះរាជពិធីច្រត់ព្រះនង្គ័លិ",
                'study'             => 1,
                'fix'               => 1
            ],
            [
                'category_event_id' => 3,
                'title'             => "ទិវាកុមារអន្តរជាតិ",
                'study'             => 1,
                'fix'               => 1
            ],
            [
                'category_event_id' => 2,
                'title'             => "ព្រះរាជពិធីបុណ្យចម្រើនព្រះជន្ម សម្តេចព្រះមហាក្សត្រី ព្រះវររាជមាតា នរោត្តម មុនិនាថ សីហនុ",
                'study'             => 1,
                'fix'               => 1
            ],
            [
                'category_event_id' => 2,
                'title'             => "ទិវាប្រកាសរដ្ឋធម្មនុញ្ញ",
                'study'             => 0,
                'fix'               => 1
            ],
            [
                'category_event_id' => 2,
                'title'             => "ពិធីបុណ្យភ្ជុំបិណ្ឌ",
                'study'             => 0,
                'fix'               => 0
            ],
            [
                'category_event_id' => 2,
                'title'             => "ទិវាប្រារព្ធពិធីគោរពព្រះវិញ្ញាណក្ខន្ធ ព្រះករុណាព្រះបាទសម្តេចព្រះ នរោត្តម សីហនុ",
                'study'             => 0,
                'fix'               => 1
            ],
            [
                'category_event_id' => 2,
                'title'             => "ទិវារំលឹកខួបនៃកិច្ចព្រមព្រៀងសន្តិភាពទីក្រុងប៉ារីស",
                'study'             => 1,
                'fix'               => 1
            ],
            [
                'category_event_id' => 2,
                'title'             => "ព្រះរាជពិធីគ្រងព្រះបរមរាជសម្បត្តិរបស់ ព្រះករុណាព្រះបាទសម្តេចព្រះបរមនាថ នរោត្តម សីហមុនី",
                'study'             => 0,
                'fix'               => 1
            ],
            [
                'category_event_id' => 2,
                'title'             => "ព្រះរាជពិធីបុណ្យអុំទូក បណ្ដែតប្រទីប និងសំពះព្រះខែ អកអំបុក",
                'study'             => 0,
                'fix'               => 0
            ],
            [
                'category_event_id' => 2,
                'title'             => "ពិធីបុណ្យឯករាជ្យជាតិី",
                'study'             => 0,
                'fix'               => 1
            ],
            [
                'category_event_id' => 2,
                'title'             => "ទិវាសិទ្ធិមនុស្សអន្តរជាតិី",
                'study'             => 0,
                'fix'               => 1
            ]
        ];

        DB::table('events')->insert($events);
    }
}
