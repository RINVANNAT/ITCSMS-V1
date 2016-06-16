<div style="padding-bottom: 20px;">
    <!-- Check all button -->
    <button class="btn btn-primary btn-sm" id="btn_add_course"><i class="fa fa-plus-circle"></i> Add
    </button>
    <!-- /.btn-group -->
    <button class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></button>
</div>

<table class="table table-striped table-bordered table-hover dt-responsive nowrap" cellspacing="0" width="100%" id="table-exam-course">
    <thead>
    <tr>
        <th>{{ trans('labels.backend.courseAnnuals.fields.name') }}</th>
        <th>{{ trans('labels.backend.courseAnnuals.fields.semester') }}</th>
        <th>{{ trans('labels.backend.courseAnnuals.fields.academic_year_id') }}</th>
        <th>{{ trans('labels.backend.courseAnnuals.fields.class') }}</th>
        <th>{{ trans('labels.backend.courseAnnuals.fields.employee_id') }}</th>
        <th>{{ trans('labels.general.actions') }}</th>
    </tr>
    </thead>
</table>