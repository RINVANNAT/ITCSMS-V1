    <div class="pull-right" style="margin-bottom:10px">
        <div class="btn-group">
            <button type="button" class="btn btn-warning btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                Actions <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu">
                <li><a href="{{ route('admin.student.reporting',1) }}">{{ trans('menus.backend.student.generate_group') }}</a></li>
                <li><a href="#" id="generate_id_card">{{ trans('menus.backend.student.generate_id_card') }}</a></li>
                <li><a href="{{ route('admin.student.print_id_card') }}">{{ trans('menus.backend.student.print_id_card') }}</a></li>
            </ul>
        </div><!--btn group-->
        <div class="btn-group">
          <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
              {{ trans('menus.backend.reporting.title') }} <span class="caret"></span>
          </button>

          <ul class="dropdown-menu" role="menu">
            <li><a href="{{ route('admin.student.reporting',1) }}">{{ trans('menus.backend.reporting.report_student_age') }}</a></li>
            <li><a href="{{ route('admin.student.reporting',2) }}">{{ trans('menus.backend.reporting.report_student_redouble') }}</a></li>
              <li><a href="{{ route('admin.student.reporting',3) }}">{{ trans('menus.backend.reporting.report_student_degree') }}</a></li>
            <li class="divider"></li>
            <li><a href="#">Print All Report</a></li>

          </ul>
        </div><!--btn group-->

        <div class="btn-group">
            <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                Export data <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu">
                <li><a href="#" id="export_student_list">Export current student list</a></li>
                <li><a href="#" id="export_student_list_custom">Export custom student list</a></li>

            </ul>
        </div><!--btn group-->



    </div><!--pull right-->

    <div class="clearfix"></div>