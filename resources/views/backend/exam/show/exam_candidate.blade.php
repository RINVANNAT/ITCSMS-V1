<div style="padding-bottom: 20px;">
    <!-- Check all button -->
    @permission('create-exam-candidate')
    <button class="btn btn-primary btn-sm" id="btn_add_candidate"><i class="fa fa-plus-circle"></i> Add from BacII</button>
    <button class="btn btn-info btn-sm" id="btn_add_candidate_manual"><i class="fa fa-plus-circle"></i> Add Manually</button>
    @endauth
    <!-- /.btn-group -->
    <button class="btn btn-default btn-sm" id="btn-candidate-refresh"><i class="fa fa-refresh"></i></button>

    @permission('generate-room-exam-candidate')
    <button class="btn btn-default btn-sm pull-right" id="btn-candidate-generate-room"><i class="fa fa-map-signs"></i> Generate Room</button>
    @endauth
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
        <th>{{ trans('labels.backend.candidates.fields.bac_total_grade') }}</th>
        <th>{{ trans('labels.backend.candidates.fields.room_id') }}</th>
        <th>{{ trans('labels.backend.candidates.fields.result') }}</th>
        <th>{{ trans('labels.general.actions') }}</th>
    </tr>
    </thead>
</table>
