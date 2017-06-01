<?php

namespace App\Console\Commands;

use App\Models\Configuration;
use Carbon\Carbon;
use Illuminate\Console\Command;

/**
 * Class LogDemo
 * @package App\Console\Commands
 */
class LogDemo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'log:demo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Track time for all a minute.';

    /**
     * Create a new command instance.
     *
     * @return mixed
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $now = Carbon::now('Asia/Phnom_Penh');
        $departments = Configuration::where('key', 'like', 'timetable_%')->get();
        foreach ($departments as $department) {
            if (strtotime($now) >= strtotime($department->created_at) && strtotime($now) <= strtotime($department->updated_at)) {
                $department->description = 'true';
                $department->timestamps = false;
                $department->update();
            } elseif (strtotime($now) > strtotime($department->updated_at)) {
                $department->description = 'finished';
                $department->timestamps = false;
                $department->update();
            } else {
                $department->description = 'false';
                $department->timestamps = false;
                $department->update();
            }
        }
    }
}
