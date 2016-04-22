<div class="panel box box-solid">
    <div class="box-header with-border">
        <h3 style="font-size: 16px;" class="box-title">
            <button class="btn btn-primary btn-sm" id="btn_add_more_scholarship_holder">{{trans('buttons.general.add')}}</button>
            <button class="btn btn-primary btn-sm" id="btn_import_scholarship_holder">{{trans('buttons.general.import')}}</button>
        </h3>
    </div>
    <div class="box-body">
        <table id="scholarship_holder_table" class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>{{trans('labels.backend.scholarships.scholarship_holder_tab.id_card')}}</th>
                <th>{{trans('labels.backend.scholarships.scholarship_holder_tab.name_kh')}}</th>
                <th>{{trans('labels.backend.scholarships.scholarship_holder_tab.name_latin')}}</th>
                <th>{{trans('labels.backend.scholarships.scholarship_holder_tab.dob')}}</th>
                <th>{{ trans('labels.backend.students.fields.gender_id') }}</th>
                <th>{{trans('labels.backend.scholarships.scholarship_holder_tab.class')}}</th>
                <th>{{ trans('labels.backend.students.fields.department_option_id') }}</th>
            </tr>
            </thead>

        </table>
    </div>
    <!-- /.box-body -->
</div>