<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserStudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * php artisan db:seed --class=UserStudentSeeder
     * @return void
     */
    public function run()
    {
        $students = \App\Models\Student::get();

        foreach($students as $student){
            if(\App\Models\Access\User\User::where('email',$student->id_card)->first() == null){
                $password = $student->dob->format("dmY");
                $user                    = new \App\Models\Access\User\User();
                $user->name              = $student->name_kh;
                $user->email             = $student->id_card;
                $user->password          = bcrypt($password);
                $user->status            = 1;
                $user->confirmation_code = md5(uniqid(mt_rand(), true));
                $user->confirmed         = 1;

                $user->save();

                $user->roles()->attach(8); // number 8 represent student role
            }
        }

    }
}
