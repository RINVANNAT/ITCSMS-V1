<div class="form-group">
    {{ csrf_field() }}
</div>

<div class="form-group">
    <label class="col-md-3 control-label">Academic Year</label>
    <div class="col-md-8">
        <select name="academicYear" class="form-control">
            <option selected disabled>Academic</option>
            @foreach($academicYears as $academicYear)
                <option value="{{ $academicYear->id }}">{{ $academicYear->name_latin }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="form-group">
    <label class="col-md-3 control-label">Semester</label>
    <div class="col-md-8">
        <select name="grade" class="form-control">
            <option selected disabled>Semester</option>
            @foreach($grades as $grade)
                <option value="{{ $grade->id }}">{{ $grade->name_en }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="form-group">
    <label class="col-md-3 control-label">Weekly</label>
    <div class="col-md-8">
        @for($i=1; $i<=18; $i++)
            <label for="{{ $i }}" class="control-label">
                <input type="checkbox" name="weekly[]"> Week {{ $i }}
            </label>
        @endfor
    </div>
</div>

<div class="form-group">
    <label class="col-md-3 control-label">Group</label>
    <div class="col-md-8">
        <select name="grade" class="form-control">
            <option selected disabled>Group</option>
            @foreach($grades as $grade)
                <option value="{{ $grade->id }}">{{ $grade->name_en }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="form-group">
    <div class="col-md-offset-3 col-md-8">
        <input type="submit" class="btn btn-primary btn-sm" value="Clone">
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
    </div>
</div>

