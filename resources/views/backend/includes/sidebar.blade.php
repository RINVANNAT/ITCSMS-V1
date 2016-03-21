<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{!! access()->user()->picture !!}" class="img-circle" alt="User Image" />
            </div>
            <div class="pull-left info">
                <p>{!! access()->user()->name !!}</p>
                <!-- Status -->
                <a href="#"><i class="fa fa-circle text-success"></i> {{ trans('strings.backend.general.status.online') }}</a>
            </div>
        </div>

        <!-- search form (Optional) -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="{{ trans('strings.backend.general.search_placeholder') }}"/>
                  <span class="input-group-btn">
                    <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>
                  </span>
            </div>
        </form>
        <!-- /.search form -->

        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
            <li class="header">{{ trans('menus.backend.sidebar.general') }}</li>

            <!-- Optionally, you can add icons to the links -->
            <li class="{{ Active::pattern('admin/dashboard') }}">
                <a href="{!! route('admin.dashboard') !!}"><span>{{ trans('menus.backend.sidebar.dashboard') }}</span></a>
            </li>

            @permission('view-student-management')
            <li class="{{ Active::pattern('admin/student*') }}">
                <a href="{!!url('admin/studentAnnuals')!!}"><span>{{ trans('menus.backend.student.title') }}</span></a>
            </li>
            @endauth
            @permission('view-candidate-management')
            <li class="{{ Active::pattern('admin/candidates') }}">
                <a href="{!!url('admin/candidates')!!}"><span>{{ trans('menus.backend.candidate.title') }}</span></a>
            </li>
            @endauth
            @permission('view-employee-management')
            <li class="{{ Active::pattern('admin/employees') }}">
                <a href="{!!url('admin/employees')!!}"><span>{{ trans('menus.backend.employee.title') }}</span></a>
            </li>
            @endauth
            @permission('view-scholarship-management')
            <li class="{{ Active::pattern('admin/scholarships*') }}">
                <a href="{!!url('admin/scholarships')!!}"><span>{{ trans('menus.backend.scholarship.title') }}</span></a>
            </li>
            @endauth
            @permission('view-access-management')
                <li class="{{ Active::pattern('admin/access/*') }}">
                    <a href="{!!url('admin/access/users')!!}"><span>{{ trans('menus.backend.access.title') }}</span></a>
                </li>
            @endauth
            @permission('view-exam-management')
            <li class="{{ Active::pattern('admin/exams*') }} treeview">
                <a href="#">
                    <span>{{ trans('menus.backend.exam.title') }}</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu {{ Active::pattern('admin/exams/*', 'menu-open') }}" style="display: none; {{ Active::pattern('admin/exams/*', 'display: block;') }}">
                    <li class="{{ Active::pattern('admin/exams/'.config('access.exam.entrance_engineer').'/*') }}">
                        <a href="{!! route('admin.exam.index',config('access.exam.entrance_engineer')) !!}">{{ trans('menus.backend.exam.entrances-engineer') }}</a>
                    </li>
                    <li class="{{ Active::pattern('admin/exams/'.config('access.exam.entrance_dut').'/*') }}">
                        <a href="{!! route('admin.exam.index',config('access.exam.entrance_dut')) !!}">{{ trans('menus.backend.exam.entrances-dut') }}</a>
                    </li>
                    <li class="{{ Active::pattern('admin/exams/'.config('access.exam.final_semester').'/*') }}">
                        <a href="{!! route('admin.exam.index',config('access.exam.final_semester')) !!}">{{ trans('menus.backend.exam.finals') }}</a>
                    </li>
                </ul>
            </li>
            @endauth
            @permission('view-course-management')
            <li class="{{ Active::pattern('admin/course*') }} treeview">
                <a href="#">
                    <span>{{ trans('menus.backend.course.title') }}</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu {{ Active::pattern('admin/course/*', 'menu-open') }}" style="display: none; {{ Active::pattern('admin/course*', 'display: block;') }}">
                    <li class="{{ Active::pattern('admin/course/course_annual*') }}">
                        <a href="{!! url('admin/course/course_annual') !!}">{{ trans('menus.backend.course.course_annuals') }}</a>
                    </li>
                    <li class="{{ Active::pattern('admin/course/course_program*') }}">
                        <a href="{!! url('admin/course/course_program') !!}">{{ trans('menus.backend.course.course_programs') }}</a>
                    </li>
                </ul>
            </li>
            @endauth
            @permission('view-accounting-management')
            <li class="{{ Active::pattern('admin/accounting*') }} treeview">
                <a href="#">
                    <span>{{ trans('menus.backend.accounting.title') }}</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu {{ Active::pattern('admin/accounting/*', 'menu-open') }}" style="display: none; {{ Active::pattern('admin/accounting*', 'display: block;') }}">
                    <li class="{{ Active::pattern('admin/accounting/incomes*') }}">
                        <a href="{!! url('admin/accounting/incomes') !!}">{{ trans('menus.backend.accounting.incomes') }}</a>
                    </li>
                    <li class="{{ Active::pattern('admin/accounting/outcomes*') }}">
                        <a href="{!! url('admin/accounting/outcomes') !!}">{{ trans('menus.backend.accounting.outcomes') }}</a>
                    </li>
                    <li class="{{ Active::pattern('admin/accounting/studentPayments*') }}">
                        <a href="{!! url('admin/accounting/studentPayments') !!}">{{ trans('menus.backend.accounting.student_payments') }}</a>
                    </li>
                    <li class="{{ Active::pattern('admin/accounting/candidatePayments*') }}">
                        <a href="{!! url('admin/accounting/candidatePayments') !!}">{{ trans('menus.backend.accounting.candidate_payments') }}</a>
                    </li>
                    <li class="{{ Active::pattern('admin/accounting/customers*') }}">
                        <a href="{!! url('admin/accounting/customers') !!}">{{ trans('menus.backend.accounting.customers') }}</a>
                    </li>
                </ul>
            </li>
            @endauth
            @permission('view-configuration-management')
            <li class="{{ Active::pattern('admin/configuration*') }} treeview">
                <a href="#">
                    <span>{{ trans('menus.backend.configuration.main') }}</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu {{ Active::pattern('admin/configuration/*', 'menu-open') }}" style="display: none; {{ Active::pattern('admin/configuration*', 'display: block;') }}">
                    <li class="{{ Active::pattern('admin/configuration/departments*') }}">
                        <a href="{!! url('admin/configuration/departments') !!}">{{ trans('menus.backend.configuration.departments') }}</a>
                    </li>
                    <li class="{{ Active::pattern('admin/configuration/degrees*') }}">
                        <a href="{!! url('admin/configuration/degrees') !!}">{{ trans('menus.backend.configuration.degrees') }}</a>
                    </li>
                    <li class="{{ Active::pattern('admin/configuration/grades*') }}">
                        <a href="{!! url('admin/configuration/grades') !!}">{{ trans('menus.backend.configuration.grades') }}</a>
                    </li>
                    <li class="{{ Active::pattern('admin/configuration/academicYears*') }}">
                        <a href="{!! url('admin/configuration/academicYears') !!}">{{ trans('menus.backend.configuration.academicYears') }}</a>
                    </li>
                    <li class="{{ Active::pattern('admin/configuration/accounts*') }}">
                        <a href="{!! url('admin/configuration/accounts') !!}">{{ trans('menus.backend.configuration.accounts') }}</a>
                    </li>
                    <li class="{{ Active::pattern('admin/configuration/buildings*') }}">
                        <a href="{!! url('admin/configuration/buildings') !!}">{{ trans('menus.backend.configuration.buildings') }}</a>
                    </li>
                    <li class="{{ Active::pattern('admin/configuration/highSchools*') }}">
                        <a href="{!! url('admin/configuration/highSchools') !!}">{{ trans('menus.backend.configuration.highSchools') }}</a>
                    </li>
                    <li class="{{ Active::pattern('admin/configuration/incomeTypes*') }}">
                        <a href="{!! url('admin/configuration/incomeTypes') !!}">{{ trans('menus.backend.configuration.incomeTypes') }}</a>
                    </li>
                    <li class="{{ Active::pattern('admin/configuration/outcomeTypes*') }}">
                        <a href="{!! url('admin/configuration/outcomeTypes') !!}">{{ trans('menus.backend.configuration.outcomeTypes') }}</a>
                    </li>
                    <li class="{{ Active::pattern('admin/configuration/schoolFees*') }}">
                        <a href="{!! url('admin/configuration/schoolFees') !!}">{{ trans('menus.backend.configuration.schoolFees') }}</a>
                    </li>
                    <li class="{{ Active::pattern('admin/configuration/rooms*') }}">
                        <a href="{!! url('admin/configuration/rooms') !!}">{{ trans('menus.backend.configuration.rooms') }}</a>
                    </li>
                    <li class="{{ Active::pattern('admin/configuration/roomTypes*') }}">
                        <a href="{!! url('admin/configuration/roomTypes') !!}">{{ trans('menus.backend.configuration.roomTypes') }}</a>
                    </li>
                    <li class="{{ Active::pattern('admin/configuration/studentBac2s*') }}">
                        <a href="{!! url('admin/configuration/studentBac2s') !!}">{{ trans('menus.backend.configuration.studentBac2s') }}</a>
                    </li>
                    <li class="{{ Active::pattern('admin/configuration/departmentOptions*') }}">
                        <a href="{!! url('admin/configuration/departmentOptions') !!}">{{ trans('menus.backend.configuration.departmentOptions') }}</a>
                    </li>
                    <li class="{{ Active::pattern('admin/configuration/promotions*') }}">
                        <a href="{!! url('admin/configuration/promotions') !!}">{{ trans('menus.backend.configuration.promotions') }}</a>
                    </li>
                    <li class="{{ Active::pattern('admin/configuration/redoubles*') }}">
                        <a href="{!! url('admin/configuration/redoubles') !!}">{{ trans('menus.backend.configuration.redoubles') }}</a>
                    </li>
                </ul>
            </li>
            @endauth

            @permission('view-log-viewer-management')
            <li class="{{ Active::pattern('admin/log-viewer*') }} treeview">
                <a href="#">
                    <span>{{ trans('menus.backend.log-viewer.main') }}</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu {{ Active::pattern('admin/log-viewer*', 'menu-open') }}" style="display: none; {{ Active::pattern('admin/log-viewer*', 'display: block;') }}">
                    <li class="{{ Active::pattern('admin/log-viewer') }}">
                        <a href="{!! url('admin/log-viewer') !!}">{{ trans('menus.backend.log-viewer.dashboard') }}</a>
                    </li>
                    <li class="{{ Active::pattern('admin/log-viewer/logs') }}">
                        <a href="{!! url('admin/log-viewer/logs') !!}">{{ trans('menus.backend.log-viewer.logs') }}</a>
                    </li>
                </ul>
            </li>
            @endauth

            <li class="{{ Active::pattern('admin/reporting*') }}">
                <a href="{!!url('admin/reporting')!!}"><span>{{ trans('menus.backend.error.reporting') }}</span></a>
            </li>

        </ul><!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>