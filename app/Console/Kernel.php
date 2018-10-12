<?php

namespace App\Console;

use App\Console\Commands\DistributionDepartmentGradeIConsole;
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
        Commands\SetOriginalScoreToDistributionDepartmentConsole::class,
        Commands\Backend\Schedule\Timetable\CreateTimetablePermissionConsole::class,
        DistributionDepartmentGradeIConsole::class,
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
            ->hourly();

        // $schedule->command('timetable:setPermissionCreateTimetable')
            // ->timezone('Asia/Phnom_Penh')
            //->dailyAt('06:00');
            // ->hourly();

        /** Testing Simple Console */
        // $schedule->command('log:demo')->everyMinute();
        $schedule->command('backup:run')
            ->timezone('Asia/Phnom_Penh')
            ->daily()
            ->at('8:00');
        $schedule->command('backup:run')
            ->timezone('Asia/Phnom_Penh')
            ->daily()
            ->at('17:00');
    }
}
