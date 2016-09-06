<div style="padding-bottom: 20px;">
    <!-- Check all button -->
    @permission('create-entrance-exam-course')
    <button class="btn btn-primary btn-sm" id="btn-add-course"><i class="fa fa-plus-circle"></i> {!! trans('buttons.general.add') !!} </button>
    @endauth
    <button class="btn btn-default btn-sm" id="btn-course-refresh"><i class="fa fa-refresh"></i></button>

    @permission('report-error-on-inputted-score')
    <button  class="btn btn-primary btn-sm pull-right" id="btn_check_course_error"><i class="fa fa-plus-circle" ></i> Check</button>
    @endauth

    @permission('get-candidate-result-score')
    <button class="btn btn-primary  btn-sm pull-right" id="btn_result_score_candidate" style="margin-right: 5px"><i class="fa fa-plus-circle"></i> {!! trans('buttons.exam.course.generate_score') !!} </button>
    @endauth

    <!-- /.btn-group -->

</div>

<table class="table table-striped table-bordered table-hover dt-responsive nowrap" cellspacing="0" width="100%" id="table-exam-course">
    <thead>
    @if($exam->type_id == 1)
        <tr>
            <th>{{ trans('labels.backend.exams.course.fields.course_name') }}</th>
            <th>{{ trans('labels.backend.exams.course.fields.total_question') }}</th>
            <th>{{ trans('labels.backend.exams.course.fields.description') }}</th>
            <th>{{ trans('labels.general.actions') }}</th>
        </tr>
    @else
        <tr>
            <th>{{ trans('labels.backend.courseAnnuals.fields.name') }}</th>
            <th>{{ trans('labels.backend.courseAnnuals.fields.semester') }}</th>
            <th>{{ trans('labels.backend.courseAnnuals.fields.academic_year_id') }}</th>
            <th>{{ trans('labels.backend.courseAnnuals.fields.class') }}</th>
            <th>{{ trans('labels.backend.courseAnnuals.fields.employee_id') }}</th>
            <th>{{ trans('labels.general.actions') }}</th>
        </tr>
    @endif
    </thead>
</table>

