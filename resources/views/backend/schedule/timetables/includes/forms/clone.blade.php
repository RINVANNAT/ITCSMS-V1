<div class="form-group">
    {{ csrf_field() }}
</div>

<input type="hidden" name="academicYeas" value="2017">
<input type="hidden" name="degree" value="23">
<input type="hidden" name="grade" value="2">
<input type="hidden" name="semester" value="1">
<input type="hidden" name="group" value="a">
<input type="hidden" name="option" value="_ee">

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

<div class="form-group">
    <div class="col-md-10 col-md-offset-2">
        <div class="row">
            <div class="col-md-12">
                <label for="all-groups">
                    <input type="checkbox" id="all-groups"
                           class="square"> {{ trans('labels.backend.schedule.timetable.modal_clone.body.all_groups') }}
                </label>
            </div>
        </div>
    </div>
</div>

<div class="form-group">
    <div class="col-md-8 col-md-offset-2">
        <div class="row render_groups"></div>
    </div>
</div>

<hr/>
<div class="form-group">
    <div class="col-md-offset-2 col-md-8">
        <input type="submit" class="btn btn-primary btn-sm button_clone_timetable"
               value="{{ trans('buttons.backend.schedule.timetable.modal_clone.clone') }}">
        <button type="button" class="btn btn-default btn-sm btn_cancel_clone_timetable"
                data-dismiss="modal">{{ trans('buttons.backend.schedule.timetable.modal_clone.close') }}</button>
    </div>
</div>
