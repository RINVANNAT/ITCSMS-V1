<?php

namespace App\Providers;

use App\Models\AcademicYear;
use App\Models\Degree;
use App\Models\DepartmentOption;
use App\Models\Grade;
use App\Models\Schedule\Calendar\Year\Year;
use App\Models\Semester;
use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * Application locale defaults for various components
         *
         * These will be overridden by LocaleMiddleware if the session local is set
         */

        /**
         * setLocale for php. Enables ->formatLocalized() with localized values for dates
         */
        setLocale(LC_TIME, config('app.locale_php'));

        /**
         * setLocale to use Carbon source locales. Enables diffForHumans() localized
         */
        Carbon::setLocale(config('app.locale'));

        /**
         * Passing academicYears, Degree,... to option partials (Timetable).
         */
        view()->composer('backend.schedule.timetables.includes.partials.option', function ($view){
            $view->with([
                'academicYears' => AcademicYear::all(),
                'degrees' => Degree::all(),
                'grades' => Grade::all(),
                'options' => DepartmentOption::all(),
                'semesters' => Semester::all()
            ]);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
