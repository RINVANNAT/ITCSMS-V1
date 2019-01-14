<?php

namespace App\Console\Commands\Backend\Schedule\Timetable;

use Illuminate\Console\Command;

/**
 * Class CreateTimetablePermissionConsole
 * @package App\Console\Commands\Backend\Schedule\Timetable
 */
class CreateTimetablePermissionConsole extends Command
{
    protected $timetableSlotRepo;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'timetable:setPermissionCreateTimetable';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set permission creating timetable.';

    /**
     * Create a new command instance.
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
        //
    }
}
