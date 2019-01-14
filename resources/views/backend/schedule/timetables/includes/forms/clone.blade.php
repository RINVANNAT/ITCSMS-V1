<input type="hidden" name="academic_year_id" id="academic_year_id"/>
<input type="hidden" name="department_id" id="department_id"/>
<input type="hidden" name="degree_id" id="degree_id"/>
<input type="hidden" name="option_id" id="option_id"/>
<input type="hidden" name="grade_id" id="grade_id"/>
<input type="hidden" name="semester_id" id="semester_id"/>
<input type="hidden" name="group_id" id="group_id"/>
<input type="hidden" name="week_id" id="week_id"/>

<div class="form-group">
    <div class="col-md-10 col-md-offset-2">
        <div class="row">
            <div class="col-md-12">
                <label for="all-weeks">
                    <input type="checkbox" id="all-weeks"
                           class="square"> {{ trans('labels.backend.schedule.timetable.modal_clone.body.all_weeks') }}
                </label>
            </div>
        </div>
    </div>
</div>

{{--class="square"--}}
<div class="form-group">
    <div class="col-md-8 col-md-offset-2">
        <div class="row render_weeks"></div>
    </div>
</div>

<hr/>
<div class="form-group">
    <div class="col-md-offset-2 col-md-8">
        <input type="submit"
               class="btn btn-primary btn-sm button_clone_timetable"
               value="{{ trans('buttons.backend.schedule.timetable.modal_clone.clone') }}">
        <button type="button"
                class="btn btn-default btn-sm btn_cancel_clone_timetable"
                data-dismiss="modal">{{ trans('buttons.backend.schedule.timetable.modal_clone.close') }}</button>
    </div>
</div>
