<?php

namespace App\Providers;

use App\Services\Access\Access;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

/**
 * Class AccessServiceProvider
 * @package App\Providers
 */
class AccessServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Package boot method
     */
    public function boot()
    {
        $this->registerBladeExtensions();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerAccess();
        $this->registerFacade();
        $this->registerBindings();
    }

    /**
     * Register the application bindings.
     *
     * @return void
     */
    private function registerAccess()
    {
        $this->app->bind('access', function ($app) {
            return new Access($app);
        });
    }

    /**
     * Register the vault facade without the user having to add it to the app.php file.
     *
     * @return void
     */
    public function registerFacade()
    {
        $this->app->booting(function () {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('Access', \App\Services\Access\Facades\Access::class);
        });
    }

    /**
     * Register service provider bindings
     */
    public function registerBindings()
    {
        $this->app->bind(
            \App\Repositories\Frontend\User\UserContract::class,
            \App\Repositories\Frontend\User\EloquentUserRepository::class
        );

        $this->app->bind(
            \App\Repositories\Backend\User\UserContract::class,
            \App\Repositories\Backend\User\EloquentUserRepository::class
        );

        $this->app->bind(
            \App\Repositories\Backend\Role\RoleRepositoryContract::class,
            \App\Repositories\Backend\Role\EloquentRoleRepository::class
        );

        $this->app->bind(
            \App\Repositories\Backend\Permission\PermissionRepositoryContract::class,
            \App\Repositories\Backend\Permission\EloquentPermissionRepository::class
        );

        $this->app->bind(
            \App\Repositories\Backend\Permission\Group\PermissionGroupRepositoryContract::class,
            \App\Repositories\Backend\Permission\Group\EloquentPermissionGroupRepository::class
        );

        $this->app->bind(
            \App\Repositories\Backend\Permission\Dependency\PermissionDependencyRepositoryContract::class,
            \App\Repositories\Backend\Permission\Dependency\EloquentPermissionDependencyRepository::class
        );

        $this->app->bind(
            \App\Repositories\Backend\Department\DepartmentRepositoryContract::class,
            \App\Repositories\Backend\Department\EloquentDepartmentRepository::class
        );

        $this->app->bind(
            \App\Repositories\Backend\AcademicYear\AcademicYearRepositoryContract::class,
            \App\Repositories\Backend\AcademicYear\EloquentAcademicYearRepository::class
        );

        $this->app->bind(
            \App\Repositories\Backend\Account\AccountRepositoryContract::class,
            \App\Repositories\Backend\Account\EloquentAccountRepository::class
        );

        $this->app->bind(
            \App\Repositories\Backend\Building\BuildingRepositoryContract::class,
            \App\Repositories\Backend\Building\EloquentBuildingRepository::class
        );

        $this->app->bind(
            \App\Repositories\Backend\Degree\DegreeRepositoryContract::class,
            \App\Repositories\Backend\Degree\EloquentDegreeRepository::class
        );

        $this->app->bind(
            \App\Repositories\Backend\Grade\GradeRepositoryContract::class,
            \App\Repositories\Backend\Grade\EloquentGradeRepository::class
        );

        $this->app->bind(
            \App\Repositories\Backend\HighSchool\HighSchoolRepositoryContract::class,
            \App\Repositories\Backend\HighSchool\EloquentHighSchoolRepository::class
        );

        $this->app->bind(
            \App\Repositories\Backend\IncomeType\IncomeTypeRepositoryContract::class,
            \App\Repositories\Backend\IncomeType\EloquentIncomeTypeRepository::class
        );

        $this->app->bind(
            \App\Repositories\Backend\OutcomeType\OutcomeTypeRepositoryContract::class,
            \App\Repositories\Backend\OutcomeType\EloquentOutcomeTypeRepository::class
        );

        $this->app->bind(
            \App\Repositories\Backend\Room\RoomRepositoryContract::class,
            \App\Repositories\Backend\Room\EloquentRoomRepository::class
        );

        $this->app->bind(
            \App\Repositories\Backend\StudentBac2\StudentBac2RepositoryContract::class,
            \App\Repositories\Backend\StudentBac2\EloquentStudentBac2Repository::class
        );

        $this->app->bind(
            \App\Repositories\Backend\Candidate\CandidateRepositoryContract::class,
            \App\Repositories\Backend\Candidate\EloquentCandidateRepository::class
        );

        $this->app->bind(
            \App\Repositories\Backend\Exam\ExamRepositoryContract::class,
            \App\Repositories\Backend\Exam\EloquentExamRepository::class
        );

        $this->app->bind(
            \App\Repositories\Backend\Income\IncomeRepositoryContract::class,
            \App\Repositories\Backend\Income\EloquentIncomeRepository::class
        );

        $this->app->bind(
            \App\Repositories\Backend\Outcome\OutcomeRepositoryContract::class,
            \App\Repositories\Backend\Outcome\EloquentOutcomeRepository::class
        );

        $this->app->bind(
            \App\Repositories\Backend\Employee\EmployeeRepositoryContract::class,
            \App\Repositories\Backend\Employee\EloquentEmployeeRepository::class
        );

        $this->app->bind(
            \App\Repositories\Backend\Customer\CustomerRepositoryContract::class,
            \App\Repositories\Backend\Customer\EloquentCustomerRepository::class
        );

        $this->app->bind(
            \App\Repositories\Backend\CourseAnnual\CourseAnnualRepositoryContract::class,
            \App\Repositories\Backend\CourseAnnual\EloquentCourseAnnualRepository::class
        );


        $this->app->bind(
            \App\Repositories\Backend\CourseProgram\CourseProgramRepositoryContract::class,
            \App\Repositories\Backend\CourseProgram\EloquentCourseProgramRepository::class
        );

        $this->app->bind(
            \App\Repositories\Backend\Scholarship\ScholarshipRepositoryContract::class,
            \App\Repositories\Backend\Scholarship\EloquentScholarshipRepository::class
        );
        $this->app->bind(
            \App\Repositories\Backend\SchoolFee\SchoolFeeRepositoryContract::class,
            \App\Repositories\Backend\SchoolFee\EloquentSchoolFeeRepository::class
        );

        $this->app->bind(
            \App\Repositories\Backend\StudentAnnual\StudentAnnualRepositoryContract::class,
            \App\Repositories\Backend\StudentAnnual\EloquentStudentAnnualRepository::class
        );

        $this->app->bind(
            \App\Repositories\Backend\DepartmentOption\DepartmentOptionRepositoryContract::class,
            \App\Repositories\Backend\DepartmentOption\EloquentDepartmentOptionRepository::class
        );

        $this->app->bind(
            \App\Repositories\Backend\Promotion\PromotionRepositoryContract::class,
            \App\Repositories\Backend\Promotion\EloquentPromotionRepository::class
        );

        $this->app->bind(
            \App\Repositories\Backend\Redouble\RedoubleRepositoryContract::class,
            \App\Repositories\Backend\Redouble\EloquentRedoubleRepository::class
        );

        $this->app->bind(
            \App\Repositories\Backend\RoomType\RoomTypeRepositoryContract::class,
            \App\Repositories\Backend\RoomType\EloquentRoomTypeRepository::class
        );

        $this->app->bind(
            \App\Repositories\Backend\Reporting\ReportingRepositoryContract::class,
            \App\Repositories\Backend\Reporting\EloquentReportingRepository::class
        );

        $this->app->bind(
            \App\Repositories\Backend\TempEmployeeExam\TempEmployeeExamRepositoryContract::class,
            \App\Repositories\Backend\TempEmployeeExam\EloquentTempEmployeeExamRepository::class
        );

        $this->app->bind(
            \App\Repositories\Backend\DepartmentEmployeeExamPosition\DepartmentEmployeeExamPositionRepositoryContract::class,
            \App\Repositories\Backend\DepartmentEmployeeExamPosition\EloquentDepartmentEmployeeExamPositionRepository::class
        );

        $this->app->bind(
            \App\Repositories\Backend\EntranceExamCourse\EntranceExamCourseRepositoryContract::class,
            \App\Repositories\Backend\EntranceExamCourse\EloquentEntranceExamCourseRepository::class
        );
        /*---------------------------------------
            binding For Module score
        ----------------------------------------*/
    }

    /**
     * Register the blade extender to use new blade sections
     */
    protected function registerBladeExtensions()
    {
        /**
         * Role based blade extensions
         * Accepts either string of Role Name or Role ID
         */
        Blade::directive('role', function ($role) {
            return "<?php if (access()->hasRole{$role}): ?>";
        });

        /**
         * Accepts array of names or id's
         */
        Blade::directive('roles', function ($roles) {
            return "<?php if (access()->hasRoles{$roles}): ?>";
        });

        Blade::directive('needsroles', function ($roles) {
            return '<?php if (access()->hasRoles(' . $roles . ', true)): ?>';
        });

        /**
         * Permission based blade extensions
         * Accepts wither string of Permission Name or Permission ID
         */
        Blade::directive('permission', function ($permission) {
            return "<?php if (access()->allow{$permission}): ?>";
        });

        /**
         * Accepts array of names or id's
         */
        Blade::directive('permissions', function ($permissions) {
            return "<?php if (access()->allowMultiple{$permissions}): ?>";
        });

        Blade::directive('needspermissions', function ($permissions) {
            return '<?php if (access()->allowMultiple(' . $permissions . ', true)): ?>';
        });

        /**
         * Generic if closer to not interfere with built in blade
         */
        Blade::directive('endauth', function () {
            return '<?php endif; ?>';
        });
    }
}