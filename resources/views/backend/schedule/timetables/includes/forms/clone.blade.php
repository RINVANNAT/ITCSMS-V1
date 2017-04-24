<div class="form-group">
    {{ csrf_field() }}
</div>

<div class="form-group">
    <label class="col-md-3 control-label">Weekly</label>
    <div class="col-md-8">
        <div class="row">
            @for($i=1; $i<=18; $i++)
                <div class="col-md-3">
                    <label for="{{ $i }}">
                        <input type="checkbox" name="weeks[]" class="square" value="{{ $i }}"> Week {{ $i }}
                    </label>
                </div>
            @endfor
        </div>
    </div>
</div>

<div class="form-group">
    <label class="col-md-3 control-label">Group</label>
    <div class="col-md-8">
        <div class="row">
            @for($i=1; $i<=18; $i++)
                <div class="col-md-2">
                    <label for="{{ $i }}">
                        <input type="checkbox" name="groups[]" class="square" value="{{ $i }}"> A
                    </label>
                </div>
            @endfor
        </div>
    </div>
</div>

<div class="form-group">
    <div class="col-md-offset-3 col-md-8">
        <input type="submit" class="btn btn-primary btn-sm" value="Clone">
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
    </div>
</div>

