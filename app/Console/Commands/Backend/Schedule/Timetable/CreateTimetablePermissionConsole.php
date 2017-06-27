<?php

namespace App\Console\Commands\Backend\Schedule\Timetable;

use App\Repositories\Backend\Schedule\Timetable\EloquentTimetableSlotRepository;
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
     *
     * @param EloquentTimetableSlotRepository $timetableSlotRepository
     */
    public function __construct(EloquentTimetableSlotRepository $timetableSlotRepository)
    {
        parent::__construct();
        $this->timetableSlotRepo = $timetableSlotRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->timetableSlotRepo->set_permission_create_timetable();
    }
}
