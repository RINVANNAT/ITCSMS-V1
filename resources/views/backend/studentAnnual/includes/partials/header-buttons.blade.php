    <div class="pull-right" style="margin-bottom:10px">
        <div class="btn-group">
          <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
              {{ trans('menus.backend.reporting.title') }} <span class="caret"></span>
          </button>
          <ul class="dropdown-menu" role="menu">
            <li><a href="{{ route('admin.student.reporting',1) }}">{{ trans('menus.backend.reporting.report_student_age') }}</a></li>
            <li><a href="{{ route('admin.student.reporting',2) }}">{{ trans('menus.backend.reporting.report_student_drop_out') }}</a></li>
              <li><a href="{{ route('admin.student.reporting',2) }}">{{ trans('menus.backend.reporting.report_student_degree') }}</a></li>
              <li><a href="{{ route('admin.student.reporting',2) }}">{{ trans('menus.backend.reporting.report_foreign_student_degree') }}</a></li>
            <li class="divider"></li>
            <li><a href="{{ route('admin.student.reporting',3) }}">Print All Report</a></li>

          </ul>
        </div><!--btn group-->

    </div><!--pull right-->

    <div class="clearfix"></div>