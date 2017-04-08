{{ csrf_field() }}

<div class="form-group @if($errors->has('title')) has-error @endif">
    <label for="title" class="control-label col-md-2">Title</label>
    <div class="col-md-10">
        <input type="text"
               name="title"
               id="title"
               class="form-control"
               value="{{ old('title') }}"
               placeholder="Title event"/>
    </div>
</div>

@permission('create-public-event')
<div class="form-group @if($errors->has('department')) has-error @endif">
    <label for="category" class="control-label col-md-2">Department</label>
    <div class="col-md-10">
        <select class="form-control"
                name="departments[]"
                id="departments" style="width: 100%;" multiple>
            @foreach($departments as $department)

                <option value="{{ $department->id }}">{{ $department->name_en }}</option>

            @endforeach
        </select>
    </div>
</div>
@endauth


<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        <div class="checkbox">
            <label for="study">
                <input type="checkbox"
                       name="study"
                       value="1"
                       id="study" checked/> Study or not?
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
                       value="1"
                       id="fx"/> Fix date or not?
            </label>
        </div>
    </div>
</div>