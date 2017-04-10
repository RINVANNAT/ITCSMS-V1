{{ csrf_field() }}

<div class="form-group @if($errors->has('title')) has-error @endif">
    <label for="title" class="control-label col-md-2">
        {{ trans('labels.backend.schedule.event.modal_create_event.form_input.title') }}
    </label>
    <div class="col-md-10">
        <input type="text"
               name="title"
               id="title"
               class="form-control"
               value="{{ old('title') }}"
               placeholder="{{ trans('labels.backend.schedule.event.modal_create_event.form_input.placeholder.title') }}"/>
    </div>
</div>


<div class="form-group @if($errors->has('department')) has-error @endif">
    <label for="category" class="control-label col-md-2">
        {{ trans('labels.backend.schedule.event.modal_create_event.form_input.event_type') }}
    </label>
    <div class="col-md-10">
        <select class="form-control" name="public" id="public">
            @permission('create-public-event')
            <option value="true">Public</option>
            @endauth
            @permission('create-private-event')
            <option value="false">Private</option>
            @endauth
        </select>
    </div>
</div>



<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        <div class="checkbox">
            <label for="study">
                <input type="checkbox"
                       name="study"
                       value="true"
                       id="study" checked/> {{ trans('labels.backend.schedule.event.modal_create_event.form_input.allow_student_study') }}.
            </label>
        </div>
    </div>
</div>

<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        <div class="checkbox">
            <label for="fix">
                <input type="checkbox"
                       name="fix"
                       value="true"
                       id="fx"/> {{ trans('labels.backend.schedule.event.modal_create_event.form_input.repeat_all_year') }}.
            </label>
        </div>
    </div>
</div>