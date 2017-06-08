<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

/**
 * Class Kernel
 * @package App\Console
 */
class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\Inspire::class,
        Commands\LogDemo::class,
        Commands\Backend\Schedule\Timetable\CreateTimetablePermissionConsole::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        /** Timetable Assignment */
        $schedule->command('timetable:setPermissionCreateTimetable')
            ->timezone('Asia/Phnom_Penh')
            ->dailyAt('00:00');

        $schedule->command('timetable:setPermissionCreateTimetable')
            ->timezone('Asia/Phnom_Penh')
            ->dailyAt('06:00');

        /** Testing Simple Console */
        $schedule->command('log:demo')
            ->timezone('Asia/Phnom_Penh')
            ->dailyAt('20:55');
    }
}
