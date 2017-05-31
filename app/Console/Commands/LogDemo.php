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
        $now = Carbon::now();
        $departments = Configuration::where('key', 'like', 'timetable_%')->get();
        foreach ($departments as $department) {
            if (strtotime($now) == strtotime($department->created_at)) {
                $department->description = 'true';
                Log:
                info('This department id=' . $department->value . 'can create timetable');
                $department->update();
            } else {
                if ($department->description == 'true') {
                    $department->description = 'false';
                    $department->update();
                }
            }
        }
    }
}
