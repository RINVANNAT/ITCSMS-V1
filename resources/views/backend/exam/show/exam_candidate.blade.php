<div class="alert alert-warning alert-dismissible" style="display: none" id="candidate_notification">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <h4><i class="icon fa fa-warning"></i> Candidates are missing!</h4>
    There are some missing candidates. Click <a href="{!! route('admin.exam.find_missing_candidates',$exam->id) !!}" id="btn_show_missing_candidate">here</a> to see missing register ID.
</div>
<div style="padding-bottom: 20px;">
    <!-- Check all button -->
    @permission('create-exam-candidate')
    <button class="btn btn-primary btn-sm" id="btn_add_candidate"><i class="fa fa-plus-circle"></i> {!! trans('buttons.exam.candidate.add_from_bac2') !!}</button>
    <button class="btn btn-info btn-sm" id="btn_add_candidate_manual"><i class="fa fa-plus-circle"></i> {!! trans('buttons.exam.candidate.add_manually') !!}</button>
    @endauth
    <!-- /.btn-group -->
    <button class="btn btn-default btn-sm" id="btn-candidate-refresh"><i class="fa fa-refresh"></i></button>

    @if($exam->type_id == 2)
        @permission('create-entrance-exam-score')
        <button  class="btn btn-primary btn-sm pull-right" id="btn_generate_result" style="margin-right: 5px"><i class="fa fa-plus-circle" ></i> Generate Result </button>
        @endauth
    @endif

    @if($exam->type_id == 1)

        @permission('generate-room-exam-candidate')
        <button class="btn btn-default btn-sm pull-right" id="btn-candidate-generate-room"><i class="fa fa-map-signs"></i> {!! trans('buttons.exam.candidate.generate_room') !!}</button>
        @endauth

        @permission('create-entrance-exam-score')
        <button  class="btn btn-primary btn-sm pull-right" id="btn_input_score_course" style="margin-right: 5px"><i class="fa fa-plus-circle" ></i> {!! trans('buttons.exam.course.input_score') !!} </button>
        @endauth

    @endif


</div>


<table class="table table-striped table-bordered table-hover dt-responsive nowrap" cellspacing="0" width="100%" id="candidates-table">
    <thead>
    <tr>
        <th>{{ trans('labels.backend.candidates.fields.register_id') }}</th>
        <th>{{ trans('labels.backend.candidates.fields.name_kh') }}</th>
        <th>{{ trans('labels.backend.candidates.fields.name_latin') }}</th>
        <th>{{ trans('labels.backend.candidates.fields.gender_id') }}</th>
        <th>{{ trans('labels.backend.candidates.fields.dob') }}</th>
        <th>{{ trans('labels.backend.candidates.fields.province_id') }}</th>
        <th>{{ trans('labels.backend.candidates.fields.highschool_id') }}</th>
        <th>{{ trans('labels.backend.candidates.fields.bac_total_grade') }}</th>
        <th>{{ trans('labels.backend.candidates.fields.room_id') }}</th>
        <th>{{ trans('labels.backend.candidates.fields.result') }}</th>
        <th>{{ trans('labels.general.actions') }}</th>
    </tr>
    </thead>
</table>
